jQuery(document).ready(function ($) {
  // Product category testing
  $('#test-category-products').on('click', function () {
    const url = $('#test-category-url').val();
    const button = $(this);

    if (!url) {
      alert('Please enter the category URL');
      return;
    }

    button.prop('disabled', true).text('Testing...');
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
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when testing category');
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
      alert('Please enter the category URL');
      return;
    }

    button.prop('disabled', true).text('Loading...');

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
          alert('HTML saved to files:\n' + response.data.file + '\n' + response.data.scripts_file + '\nSize: ' + response.data.size + ' bite\nScript tags: ' + response.data.scripts_count);
        } else {
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when loading HTML');
      },
      complete: function () {
        button.prop('disabled', false).text('Debug Category HTML');
      },
    });
  });

  // Parsing products by category (with automatic binding)
  $('#scrape-by-categories').on('click', function () {
    const button = $(this);

    if (!confirm("This will parse all products from all categories and automatically assign them to categories. This may take some time. Continue?")) {
      return;
    }

    button.prop('disabled', true).text('Parsing products by category...');
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
          // Check if the data is in the format {products: [...], total: X}
          if (data.products && Array.isArray(data.products)) {
            displayScrapedProducts(data.products, data.products.length);

            // Showing statistics
            let statsMsg = `Total products: ${data.total || 0}`;
            if (data.matched) {
              statsMsg += ` | Matched with categories: ${data.matched}`;
            }
            if (data.unmatched) {
              statsMsg += ` | Uncategorized: ${data.unmatched}`;
            }
            $('#scrape-progress').after(`<div id="scrape-stats" style="margin-top: 15px;"><p style="color: #0073aa; font-weight: bold;">${statsMsg}</p></div>`);
          } else {
            // Old response format
            displayScrapedProducts(data.products || data, data.count || data.length);
          }
        } else {
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when parsing products');
      },
      complete: function () {
        button.prop('disabled', false).text("Sort products by category (with category binding)");
        $('#scrape-progress').hide();
      },
    });
  });

  // Category parsing
  $('#scrape-categories').on('click', function () {
    const button = $(this);

    button.prop('disabled', true).text('Category parsing...');
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
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when parsing categories');
      },
      complete: function () {
        button.prop('disabled', false).text('Sparsity categories');
        $('#categories-progress').hide();
      },
    });
  });

  // Displaying Sparse Categories
  function displayScrapedCategories(categories, count) {
    $('#categories-count').text(count);

    let html = '<table class="wp-list-table widefat fixed striped">';
    html += '<thead><tr>';
    html += '<th>Name</th>';
    html += '<th>Slug</th>';
    html += '<th>Description</th>';
    html += '<th>Image</th>';
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

  // Import categories
  $('#import-categories').on('click', function () {
    const button = $(this);

    if (!confirm('Import all categories into WooCommerce?')) {
      return;
    }

    button.prop('disabled', true).text('Import categories...');

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
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when importing categories');
      },
      complete: function () {
        button.prop('disabled', false).text('Import all categories into WooCommerce');
      },
    });
  });

  // Displaying category import results
  function displayCategoryImportResults(results) {
    let summaryHtml = '<div class="notice notice-success">';
    summaryHtml += '<p><strong>Category import complete!</strong></p>';
    summaryHtml += '<ul>';
    summaryHtml += '<li>Total categories: ' + results.total + '</li>';
    summaryHtml += '<li>Imported: ' + results.imported + '</li>';
    summaryHtml += '<li>Skipped (already exist): ' + results.skipped + '</li>';
    summaryHtml += '<li>Errors: ' + results.errors + '</li>';
    summaryHtml += '</ul></div>';

    $('#import-summary').html(summaryHtml);

    let detailsHtml = '<h3>Detailed information:</h3>';
    detailsHtml += '<table class="wp-list-table widefat fixed striped">';
    detailsHtml += '<thead><tr>';
    detailsHtml += '<th>Status</th>';
    detailsHtml += '<th>Message</th>';
    detailsHtml += '<th>Link</th>';
    detailsHtml += '</tr></thead><tbody>';

    results.details.forEach(function (detail) {
      const status = detail.success ? 'success' : 'error';
      const statusIcon = detail.success ? '✓' : '✗';

      detailsHtml += '<tr>';
      detailsHtml += '<td><span class="status-' + status + '">' + statusIcon + '</span></td>';
      detailsHtml += '<td>' + escapeHtml(detail.message) + '</td>';
      detailsHtml += '<td>';
      if (detail.category_url) {
        detailsHtml += '<a href="' + detail.category_url + '" target="_blank">Show</a>';
      }
      detailsHtml += '</td>';
      detailsHtml += '</tr>';
    });

    detailsHtml += '</tbody></table>';

    $('#import-details').html(detailsHtml);
    $('#import-results').slideDown();
  }

  // Testing one product
  $('#test-single-product').on('click', function () {
    const url = $('#test-product-url').val();
    const button = $(this);

    if (!url) {
      alert('Please enter the product URL');
      return;
    }

    button.prop('disabled', true).text('Parsing...');
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
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when parsing the product');
      },
      complete: function () {
        button.prop('disabled', false).text('Protest');
      },
    });
  });

  // Debug HTML
  $('#debug-html').on('click', function () {
    const url = $('#test-product-url').val();
    const button = $(this);

    if (!url) {
      alert('Please enter the product URL');
      return;
    }

    button.prop('disabled', true).text('Loading...');

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
          alert('HTML saved to file: ' + response.data.file + '\nSize: ' + response.data.size + ' byte');
        } else {
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when loading HTML');
      },
      complete: function () {
        button.prop('disabled', false).text('Debug HTML');
      },
    });
  });

  // Manually parsing specified URLs
  $('#parse-manual-urls').on('click', function () {
    const urls = $('#manual-urls').val();
    const button = $(this);

    if (!urls) {
      alert('Please enter the product URL');
      return;
    }

    button.prop('disabled', true).text('Parsing...');
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
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when parsing products');
      },
      complete: function () {
        button.prop('disabled', false).text('Parse the specified products');
        $('#scrape-progress').hide();
      },
    });
  });

  // Parsing all products
  $('#scrape-products').on('click', function () {
    const url = $('#shop-url').val();
    const button = $(this);

    if (!url) {
      alert('Please enter the store URL');
      return;
    }

    button.prop('disabled', true).text('Parsing...');
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
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when parsing products');
      },
      complete: function () {
        button.prop('disabled', false).text('Sparsity goods');
        $('#scrape-progress').hide();
      },
    });
  });

  // Displaying sparsed products
  function displayScrapedProducts(products, count) {
    $('#products-count').text(count);

    let html = '<table class="wp-list-table widefat fixed striped">';
    html += '<thead><tr>';
    html += '<th>Name</th>';
    html += '<th>Category</th>';
    html += '<th>Price</th>';
    html += '<th>Image</th>';
    html += '<th>Features</th>';
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

  // Import of goods
  $('#import-products').on('click', function () {
    const button = $(this);

    if (!confirm('Import all products into WooCommerce?')) {
      return;
    }

    button.prop('disabled', true).text('Importing...');

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
          alert('Error: ' + (response.data.message || 'Unknown error'));
        }
      },
      error: function () {
        alert('AJAX error when importing products');
      },
      complete: function () {
        button.prop('disabled', false).text('Import all products into WooCommerce');
      },
    });
  });

  // Displaying import results
  function displayImportResults(results) {
    let summaryHtml = '<div class="notice notice-success">';
    summaryHtml += '<p><strong>Import completed!</strong></p>';
    summaryHtml += '<ul>';
    summaryHtml += '<li>Total products: ' + results.total + '</li>';
    summaryHtml += '<li>Imported: ' + results.imported + '</li>';
    summaryHtml += '<li>Skipped (already exist): ' + results.skipped + '</li>';
    summaryHtml += '<li>Errors: ' + results.errors + '</li>';
    summaryHtml += '</ul></div>';

    $('#import-summary').html(summaryHtml);

    let detailsHtml = '<h3>Detailed information:</h3>';
    detailsHtml += '<table class="wp-list-table widefat fixed striped">';
    detailsHtml += '<thead><tr>';
    detailsHtml += '<th>Status</th>';
    detailsHtml += '<th>Message</th>';
    detailsHtml += '<th>Link</th>';
    detailsHtml += '</tr></thead><tbody>';

    results.details.forEach(function (detail) {
      const status = detail.success ? 'success' : 'error';
      const statusIcon = detail.success ? '✓' : '✗';

      detailsHtml += '<tr>';
      detailsHtml += '<td><span class="status-' + status + '">' + statusIcon + '</span></td>';
      detailsHtml += '<td>' + escapeHtml(detail.message) + '</td>';
      detailsHtml += '<td>';
      if (detail.product_url) {
        detailsHtml += '<a href="' + detail.product_url + '" target="_blank">Show</a>';
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
