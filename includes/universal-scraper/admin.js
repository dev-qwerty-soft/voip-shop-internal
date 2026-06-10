jQuery(document).ready(function ($) {
  // Тестування категорії товарів
  $('#test-category-products').on('click', function () {
    const url = $('#test-category-url').val();
    const button = $(this);

    if (!url) {
      alert('Будь ласка, введіть URL категорії');
      return;
    }

    button.prop('disabled', true).text('Тестування...');
    $('#category-test-result').hide();

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'test_category_products',
        nonce: productImportData.nonce,
        url: url,
      },
      success: function (response) {
        if (response.success) {
          $('#category-test-content').text(JSON.stringify(response.data, null, 2));
          $('#category-test-result').slideDown();
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при тестуванні категорії');
      },
      complete: function () {
        button.prop('disabled', false).text('Test Category');
      },
    });
  });

  // Debug category HTML
  $('#debug-category-html').on('click', function () {
    const url = $('#test-category-url').val();
    const button = $(this);

    if (!url) {
      alert('Будь ласка, введіть URL категорії');
      return;
    }

    button.prop('disabled', true).text('Завантаження...');

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'debug_category_html',
        nonce: productImportData.nonce,
        url: url,
      },
      success: function (response) {
        if (response.success) {
          alert('HTML збережено у файли:\n' + response.data.file + '\n' + response.data.scripts_file + '\nРозмір: ' + response.data.size + ' байт\nScript тегів: ' + response.data.scripts_count);
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при завантаженні HTML');
      },
      complete: function () {
        button.prop('disabled', false).text('Debug Category HTML');
      },
    });
  });

  // Парсинг товарів по категоріям (з автоматичною прив'язкою)
  $('#scrape-by-categories').on('click', function () {
    const button = $(this);

    if (!confirm("Це спарсить всі товари з усіх категорій і автоматично прив'яже їх до категорій. Це може зайняти час. Продовжити?")) {
      return;
    }

    button.prop('disabled', true).text('Парсинг товарів по категоріям...');
    $('#scrape-progress').show();
    $('#scraped-products').hide();
    $('#import-results').hide();

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'scrape_products_by_categories',
        nonce: productImportData.nonce,
      },
      success: function (response) {
        if (response.success) {
          const data = response.data;
          // Перевіряємо чи дані в форматі {products: [...], total: X}
          if (data.products && Array.isArray(data.products)) {
            displayScrapedProducts(data.products, data.products.length);

            // Показуємо статистику
            let statsMsg = `Всього товарів: ${data.total || 0}`;
            if (data.matched) {
              statsMsg += ` | Зіставлено з категоріями: ${data.matched}`;
            }
            if (data.unmatched) {
              statsMsg += ` | Без категорії: ${data.unmatched}`;
            }
            $('#scrape-progress').after(`<div id="scrape-stats" style="margin-top: 15px;"><p style="color: #0073aa; font-weight: bold;">${statsMsg}</p></div>`);
          } else {
            // Старий формат відповіді
            displayScrapedProducts(data.products || data, data.count || data.length);
          }
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при парсингу товарів');
      },
      complete: function () {
        button.prop('disabled', false).text("Спарсити товари по категоріям (з прив'язкою категорій)");
        $('#scrape-progress').hide();
      },
    });
  });

  // Парсинг категорій
  $('#scrape-categories').on('click', function () {
    const button = $(this);

    button.prop('disabled', true).text('Парсинг категорій...');
    $('#categories-progress').show();
    $('#scraped-categories').hide();

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'scrape_categories',
        nonce: productImportData.nonce,
      },
      success: function (response) {
        if (response.success) {
          displayScrapedCategories(response.data.categories, response.data.count);
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при парсингу категорій');
      },
      complete: function () {
        button.prop('disabled', false).text('Спарсити категорії');
        $('#categories-progress').hide();
      },
    });
  });

  // Відображення спарсених категорій
  function displayScrapedCategories(categories, count) {
    $('#categories-count').text(count);

    let html = '<table class="wp-list-table widefat fixed striped">';
    html += '<thead><tr>';
    html += '<th>Назва</th>';
    html += '<th>Slug</th>';
    html += '<th>Опис</th>';
    html += '<th>Зображення</th>';
    html += '</tr></thead><tbody>';

    categories.forEach(function (category) {
      html += '<tr>';
      html += '<td><strong>' + escapeHtml(category.name) + '</strong></td>';
      html += '<td><code>' + escapeHtml(category.slug) + '</code></td>';
      html += '<td style="font-size: 12px;">' + escapeHtml(category.description).substring(0, 100) + '...</td>';
      html += '<td>';
      if (category.image) {
        html += '<img src="' + category.image + '" style="max-width: 60px; height: auto;">';
      }
      html += '</td>';
      html += '</tr>';
    });

    html += '</tbody></table>';

    $('#categories-list').html(html);
    $('#scraped-categories').slideDown();
  }

  // Імпорт категорій
  $('#import-categories').on('click', function () {
    const button = $(this);

    if (!confirm('Імпортувати всі категорії в WooCommerce?')) {
      return;
    }

    button.prop('disabled', true).text('Імпорт категорій...');

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'import_categories',
        nonce: productImportData.nonce,
      },
      success: function (response) {
        if (response.success) {
          displayCategoryImportResults(response.data);
          $('#scraped-categories').slideUp();
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при імпорті категорій');
      },
      complete: function () {
        button.prop('disabled', false).text('Імпортувати всі категорії в WooCommerce');
      },
    });
  });

  // Відображення результатів імпорту категорій
  function displayCategoryImportResults(results) {
    let summaryHtml = '<div class="notice notice-success">';
    summaryHtml += '<p><strong>Імпорт категорій завершено!</strong></p>';
    summaryHtml += '<ul>';
    summaryHtml += '<li>Всього категорій: ' + results.total + '</li>';
    summaryHtml += '<li>Імпортовано: ' + results.imported + '</li>';
    summaryHtml += '<li>Пропущено (вже існують): ' + results.skipped + '</li>';
    summaryHtml += '<li>Помилок: ' + results.errors + '</li>';
    summaryHtml += '</ul></div>';

    $('#import-summary').html(summaryHtml);

    let detailsHtml = '<h3>Детальна інформація:</h3>';
    detailsHtml += '<table class="wp-list-table widefat fixed striped">';
    detailsHtml += '<thead><tr>';
    detailsHtml += '<th>Статус</th>';
    detailsHtml += '<th>Повідомлення</th>';
    detailsHtml += '<th>Посилання</th>';
    detailsHtml += '</tr></thead><tbody>';

    results.details.forEach(function (detail) {
      const status = detail.success ? 'success' : 'error';
      const statusIcon = detail.success ? '✓' : '✗';

      detailsHtml += '<tr>';
      detailsHtml += '<td><span class="status-' + status + '">' + statusIcon + '</span></td>';
      detailsHtml += '<td>' + escapeHtml(detail.message) + '</td>';
      detailsHtml += '<td>';
      if (detail.category_url) {
        detailsHtml += '<a href="' + detail.category_url + '" target="_blank">Переглянути</a>';
      }
      detailsHtml += '</td>';
      detailsHtml += '</tr>';
    });

    detailsHtml += '</tbody></table>';

    $('#import-details').html(detailsHtml);
    $('#import-results').slideDown();
  }

  // Тестування одного продукту
  $('#test-single-product').on('click', function () {
    const url = $('#test-product-url').val();
    const button = $(this);

    if (!url) {
      alert('Будь ласка, введіть URL товару');
      return;
    }

    button.prop('disabled', true).text('Парсинг...');
    $('#test-result').hide();

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'test_single_product',
        nonce: productImportData.nonce,
        url: url,
      },
      success: function (response) {
        if (response.success) {
          $('#test-result-content').text(JSON.stringify(response.data, null, 2));
          $('#test-result').slideDown();
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при парсингу товару');
      },
      complete: function () {
        button.prop('disabled', false).text('Протестувати');
      },
    });
  });

  // Debug HTML
  $('#debug-html').on('click', function () {
    const url = $('#test-product-url').val();
    const button = $(this);

    if (!url) {
      alert('Будь ласка, введіть URL товару');
      return;
    }

    button.prop('disabled', true).text('Завантаження...');

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'debug_html',
        nonce: productImportData.nonce,
        url: url,
      },
      success: function (response) {
        if (response.success) {
          alert('HTML збережено у файл: ' + response.data.file + '\nРозмір: ' + response.data.size + ' байт');
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при завантаженні HTML');
      },
      complete: function () {
        button.prop('disabled', false).text('Debug HTML');
      },
    });
  });

  // Парсинг вручну вказаних URL
  $('#parse-manual-urls').on('click', function () {
    const urls = $('#manual-urls').val();
    const button = $(this);

    if (!urls) {
      alert('Будь ласка, введіть URL товарів');
      return;
    }

    button.prop('disabled', true).text('Парсинг...');
    $('#scrape-progress').show();
    $('#scraped-products').hide();

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'parse_manual_urls',
        nonce: productImportData.nonce,
        urls: urls,
      },
      success: function (response) {
        if (response.success) {
          displayScrapedProducts(response.data.products, response.data.count);
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при парсингу товарів');
      },
      complete: function () {
        button.prop('disabled', false).text('Парсити вказані товари');
        $('#scrape-progress').hide();
      },
    });
  });

  // Парсинг всіх товарів
  $('#scrape-products').on('click', function () {
    const url = $('#shop-url').val();
    const button = $(this);

    if (!url) {
      alert('Будь ласка, введіть URL магазину');
      return;
    }

    button.prop('disabled', true).text('Парсинг...');
    $('#scrape-progress').show();
    $('#scraped-products').hide();
    $('#import-results').hide();

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'scrape_products',
        nonce: productImportData.nonce,
        url: url,
      },
      success: function (response) {
        if (response.success) {
          displayScrapedProducts(response.data.products, response.data.count);
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при парсингу товарів');
      },
      complete: function () {
        button.prop('disabled', false).text('Спарсити товари');
        $('#scrape-progress').hide();
      },
    });
  });

  // Відображення спарсених товарів
  function displayScrapedProducts(products, count) {
    $('#products-count').text(count);

    let html = '<table class="wp-list-table widefat fixed striped">';
    html += '<thead><tr>';
    html += '<th>Назва</th>';
    html += '<th>Категорія</th>';
    html += '<th>Ціна</th>';
    html += '<th>Зображення</th>';
    html += '<th>Особливості</th>';
    html += '</tr></thead><tbody>';

    products.forEach(function (product) {
      html += '<tr>';
      html += '<td><strong>' + escapeHtml(product.title) + '</strong></td>';
      html += '<td>';
      if (product.category_name) {
        html += '<span class="category-badge" style="background: #2271b1; color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px;">';
        html += escapeHtml(product.category_name);
        html += '</span>';
      } else if (product.category_slug) {
        html += '<code style="font-size: 11px;">' + escapeHtml(product.category_slug) + '</code>';
      } else {
        html += '<span style="color: #999;">-</span>';
      }
      html += '</td>';
      html += '<td>$' + product.price + '</td>';
      html += '<td>';
      if (product.image) {
        html += '<img src="' + product.image + '" style="max-width: 80px; height: auto;">';
      }
      html += '</td>';
      html += '<td>';
      if (product.features && product.features.length > 0) {
        html += '<ul style="margin: 0; padding-left: 20px;">';
        product.features.forEach(function (feature) {
          html += '<li style="font-size: 12px;">' + escapeHtml(feature) + '</li>';
        });
        html += '</ul>';
      }
      html += '</td>';
      html += '</tr>';
    });

    html += '</tbody></table>';

    $('#products-list').html(html);
    $('#scraped-products').slideDown();
  }

  // Імпорт товарів
  $('#import-products').on('click', function () {
    const button = $(this);

    if (!confirm('Імпортувати всі товари в WooCommerce?')) {
      return;
    }

    button.prop('disabled', true).text('Імпорт...');

    $.ajax({
      url: productImportData.ajax_url,
      type: 'POST',
      data: {
        action: 'import_products',
        nonce: productImportData.nonce,
      },
      success: function (response) {
        if (response.success) {
          displayImportResults(response.data);
          $('#scraped-products').slideUp();
        } else {
          alert('Помилка: ' + (response.data.message || 'Невідома помилка'));
        }
      },
      error: function () {
        alert('AJAX помилка при імпорті товарів');
      },
      complete: function () {
        button.prop('disabled', false).text('Імпортувати всі товари в WooCommerce');
      },
    });
  });

  // Відображення результатів імпорту
  function displayImportResults(results) {
    let summaryHtml = '<div class="notice notice-success">';
    summaryHtml += '<p><strong>Імпорт завершено!</strong></p>';
    summaryHtml += '<ul>';
    summaryHtml += '<li>Всього товарів: ' + results.total + '</li>';
    summaryHtml += '<li>Імпортовано: ' + results.imported + '</li>';
    summaryHtml += '<li>Пропущено (вже існують): ' + results.skipped + '</li>';
    summaryHtml += '<li>Помилок: ' + results.errors + '</li>';
    summaryHtml += '</ul></div>';

    $('#import-summary').html(summaryHtml);

    let detailsHtml = '<h3>Детальна інформація:</h3>';
    detailsHtml += '<table class="wp-list-table widefat fixed striped">';
    detailsHtml += '<thead><tr>';
    detailsHtml += '<th>Статус</th>';
    detailsHtml += '<th>Повідомлення</th>';
    detailsHtml += '<th>Посилання</th>';
    detailsHtml += '</tr></thead><tbody>';

    results.details.forEach(function (detail) {
      const status = detail.success ? 'success' : 'error';
      const statusIcon = detail.success ? '✓' : '✗';

      detailsHtml += '<tr>';
      detailsHtml += '<td><span class="status-' + status + '">' + statusIcon + '</span></td>';
      detailsHtml += '<td>' + escapeHtml(detail.message) + '</td>';
      detailsHtml += '<td>';
      if (detail.product_url) {
        detailsHtml += '<a href="' + detail.product_url + '" target="_blank">Переглянути</a>';
      }
      detailsHtml += '</td>';
      detailsHtml += '</tr>';
    });

    detailsHtml += '</tbody></table>';

    $('#import-details').html(detailsHtml);
    $('#import-results').slideDown();
  }

  // Escape HTML
  function escapeHtml(text) {
    if (!text) return '';
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;',
    };
    return text.toString().replace(/[&<>"']/g, function (m) {
      return map[m];
    });
  }
});
