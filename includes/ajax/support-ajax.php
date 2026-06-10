<?php

add_action('wp_ajax_support_form', 'handle_support_form');
add_action('wp_ajax_nopriv_support_form', 'handle_support_form');

function support_form_get_logo_html(): string {
  ob_start();
  if (has_custom_logo()) {
    the_custom_logo();
  } else {
    echo '<a href="' . esc_url(home_url('/')) . '" rel="home"><strong>' . esc_html(get_bloginfo('name')) . '</strong></a>';
  }
  return ob_get_clean();
}

function handle_support_form() {
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'support_form')) {
    wp_send_json_error(['message' => 'Invalid nonce'], 403);
  }
  $recipients = get_field('email_recipients_of_the_message', 'option');

  $name = sanitize_text_field($_POST['support_name'] ?? '');
  $company = sanitize_text_field($_POST['support_company'] ?? '');
  $phone = sanitize_text_field($_POST['support_phone'] ?? '');
  $email = sanitize_email($_POST['support_email'] ?? '');
  $description = sanitize_textarea_field($_POST['support_description'] ?? '');

  if (!$name || !$email || !$description) {
    wp_send_json_error(['message' => 'Required fields are missing'], 422);
  }

  if (!is_email($email)) {
    wp_send_json_error(['message' => 'Invalid email address'], 422);
  }

  $subject = sprintf('New Support Ticket from %s', $name);

  $body = support_form_email_template([
    'name' => $name,
    'company' => $company,
    'phone' => $phone,
    'email' => $email,
    'description' => $description,
  ]);

  $headers = ['Content-Type: text/html; charset=UTF-8', sprintf('Reply-To: %s <%s>', $name, $email)];
  $sent = false;

  if ($recipients && is_array($recipients) && count($recipients) > 0) {
    foreach ($recipients as $recipient) {
      $recipient_email = $recipient['email_address'];
      $sent = wp_mail($recipient_email, $subject, $body, $headers);
    }
  }

  if (!$sent) {
    wp_send_json_error(['message' => 'Failed to send email'], 500);
  }

  wp_send_json_success(['message' => 'Ticket sent']);
}

function support_form_email_template(array $data): string {
  $name = esc_html($data['name']);
  $company = esc_html($data['company']);
  $phone = esc_html($data['phone']);
  $email = esc_html($data['email']);
  $description = nl2br(esc_html($data['description']));
  $date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'));
  $site_name = esc_html(get_bloginfo('name'));
  $site_url = esc_url(home_url());
  $logo_html = support_form_get_logo_html();

  $html = '<!DOCTYPE html>';
  $html .= '<html lang="en">';
  $html .= '<head>';
  $html .= '<meta charset="UTF-8">';
  $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
  $html .= '<title>Support Ticket</title>';
  $html .= '</head>';
  $html .= '<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Helvetica,Arial,sans-serif;">';

  $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="min-height:100vh;">';
  $html .= '<tr><td align="center" style="padding:48px 16px;">';
  $html .= '<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">';

  $html .= '<tr>';
  $html .= '<td style="padding-bottom:28px;" align="center">';
  $html .= '<table cellpadding="0" cellspacing="0" border="0" style="height:40px;">';
  $html .= '<tr>';
  $html .= '<td style="height:40px;line-height:40px;vertical-align:middle;mix-blend-mode: difference;">';
  $html .= $logo_html;
  $html .= '</td>';
  $html .= '</tr>';
  $html .= '</table>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<td style="background-color:#01033e;border-radius:20px;overflow:hidden;border:1px solid rgba(128,125,254,0.15);">';
  $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';

  $html .= '<tr>';
  $html .= '<td style="height:4px;background:linear-gradient(90deg,#1439cc 0%,#807dfe 50%,#ff5521 100%);line-height:4px;font-size:4px;">&nbsp;</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<td style="padding:40px 40px 0 40px;">';
  $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
  $html .= '<tr style="vertical-align: initial;">';
  $html .= '<td>';
  $html .= '<div style="display:inline-block;background:linear-gradient(135deg,rgba(20,57,204,0.3),rgba(128,125,254,0.15));border:1px solid rgba(128,125,254,0.3);border-radius:50px;padding:6px 16px;margin-bottom:20px;">';
  $html .= '<span style="color:#807dfe;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;">New Support Ticket</span>';
  $html .= '</div>';
  $html .= '</td>';
  $html .= '<td align="right"><span style="color:#8b8a93;font-size:12px;">' . $date . '</span></td>';
  $html .= '</tr>';
  $html .= '</table>';
  $html .= '<h1 style="margin:0 0 8px;color:#ffffff;font-size:28px;font-weight:700;line-height:1.2;">Ticket from ' . $name . '</h1>';
  $html .= '<p style="margin:0;color:#8b8a93;font-size:14px;line-height:1.5;">A new support request has been submitted through the website.</p>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<td style="padding:32px 40px;">';
  $html .= '<div style="height:1px;background:linear-gradient(90deg,transparent,rgba(128,125,254,0.3),transparent);"></div>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<td style="padding:0 40px;">';
  $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';

  $html .= '<tr>';
  $html .= '<td width="48%" style="vertical-align:top;padding-bottom:20px;">';
  $html .= '<div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:16px 20px;">';
  $html .= '<div style="color:#807dfe;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:6px;">Full Name</div>';
  $html .= '<div style="color:#ffffff;font-size:15px;font-weight:500;">' . $name . '</div>';
  $html .= '</div>';
  $html .= '</td>';
  $html .= '<td width="4%"></td>';
  $html .= '<td width="48%" style="vertical-align:top;padding-bottom:20px;">';
  $html .= '<div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:16px 20px;">';
  $html .= '<div style="color:#807dfe;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:6px;">Company</div>';
  $html .= '<div style="color:#ffffff;font-size:15px;font-weight:500;">' . $company . '</div>';
  $html .= '</div>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<td width="48%" style="vertical-align:top;padding-bottom:20px;">';
  $html .= '<div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:16px 20px;">';
  $html .= '<div style="color:#807dfe;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:6px;">Phone</div>';
  $html .= '<div style="color:#ffffff;font-size:15px;font-weight:500;">' . $phone . '</div>';
  $html .= '</div>';
  $html .= '</td>';
  $html .= '<td width="4%"></td>';
  $html .= '<td width="48%" style="vertical-align:top;padding-bottom:20px;">';
  $html .= '<div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:16px 20px;">';
  $html .= '<div style="color:#807dfe;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:6px;">Email</div>';
  $html .= '<a href="mailto:' . $email . '" style="color:#ffffff;font-size:15px;font-weight:500;text-decoration:none;">' . $email . '</a>';
  $html .= '</div>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<td colspan="3" style="padding-bottom:8px;">';
  $html .= '<div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:20px;">';
  $html .= '<div style="color:#807dfe;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;margin-bottom:10px;">Description</div>';
  $html .= '<div style="color:#ffffff;font-size:15px;line-height:1.7;">' . $description . '</div>';
  $html .= '</div>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '</table>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<td style="padding:32px 40px 40px;">';
  $html .= '<table cellpadding="0" cellspacing="0" border="0">';
  $html .= '<tr>';
  $html .= '<td style="border-radius:50px;background:linear-gradient(135deg,#1439cc,#807dfe);">';
  $html .= '<a href="mailto:' . $email . '" style="display:inline-block;padding:14px 32px;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;white-space:nowrap;">Reply to ' . $name . ' &rarr;</a>';
  $html .= '</td>';
  $html .= '</tr>';
  $html .= '</table>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '</table>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<td style="padding-top:32px;" align="center">';
  $html .= '<p style="margin:0;color:#8b8a93;font-size:12px;line-height:1.6;">';
  $html .= 'This email was sent automatically from <a href="' . $site_url . '" style="color:#807dfe;text-decoration:none;">' . $site_name . '</a><br>';
  $html .= 'You received this because you are listed as a support recipient.';
  $html .= '</p>';
  $html .= '</td>';
  $html .= '</tr>';

  $html .= '</table>';
  $html .= '</td></tr>';
  $html .= '</table>';
  $html .= '</body>';
  $html .= '</html>';

  return $html;
}
