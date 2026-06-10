jQuery(document).ready(function ($) {
  let currentPage = 1;
  let currentFilters = {
    categories: [],
    priceMin: '',
    priceMax: '',
    orderby: 'popularity',
  };

  function initShopPage() {
    initPriceSlider();
    bindEvents();
  }

  function initPriceSlider() {
    // Desktop price sliders
    const minRange = $('#minRange');
    const maxRange = $('#maxRange');
    const priceMin = $('#priceMin');
    const priceMax = $('#priceMax');

    // Mobile price sliders
    const mobileMinRange = $('#mobileMinRange');
    const mobileMaxRange = $('#mobileMaxRange');
    const mobilePriceMin = $('#mobilePriceMin');
    const mobilePriceMax = $('#mobilePriceMax');

    // Set initial values for sliders
    minRange.val(0);
    maxRange.val(1000);
    mobileMinRange.val(0);
    mobileMaxRange.val(1000);

    // Set initial values for inputs (empty by default)
    priceMin.val('');
    priceMax.val('');
    mobilePriceMin.val('');
    mobilePriceMax.val('');

    function updateSlider(context = 'desktop', skipLoading = false) {
      let minRangeEl, maxRangeEl, priceMinEl, priceMaxEl;

      if (context === 'mobile') {
        minRangeEl = mobileMinRange;
        maxRangeEl = mobileMaxRange;
        priceMinEl = mobilePriceMin;
        priceMaxEl = mobilePriceMax;
      } else {
        minRangeEl = minRange;
        maxRangeEl = maxRange;
        priceMinEl = priceMin;
        priceMaxEl = priceMax;
      }

      const min = parseInt(minRangeEl.val());
      const max = parseInt(maxRangeEl.val());

      if (min > max - 10) {
        if ($(this).hasClass('slider-thumb-min')) {
          minRangeEl.val(max - 10);
        } else {
          maxRangeEl.val(min + 10);
        }
      }

      priceMinEl.val(minRangeEl.val());
      priceMaxEl.val(maxRangeEl.val());

      // Update green track width
      const minVal = parseInt(minRangeEl.val());
      const maxVal = parseInt(maxRangeEl.val());
      const minPercent = (minVal / 1000) * 100;
      const maxPercent = (maxVal / 1000) * 100;
      const activeWidth = maxPercent - minPercent;
      const activeLeft = minPercent;

      const selector = context === 'mobile' ? '.mobile-filters-modal .slider-track' : '.shop-sidebar .slider-track';
      $(selector).attr('style', `--active-width: ${activeWidth}%; --active-left: ${activeLeft}%;`);

      if (!skipLoading) {
        updateFilters();
        loadProducts(1);
      }
    }

    function updateInputs(context = 'desktop') {
      let priceMinEl, priceMaxEl, minRangeEl, maxRangeEl;

      if (context === 'mobile') {
        priceMinEl = mobilePriceMin;
        priceMaxEl = mobilePriceMax;
        minRangeEl = mobileMinRange;
        maxRangeEl = mobileMaxRange;
      } else {
        priceMinEl = priceMin;
        priceMaxEl = priceMax;
        minRangeEl = minRange;
        maxRangeEl = maxRange;
      }

      const min = parseInt(priceMinEl.val()) || 0;
      const max = parseInt(priceMaxEl.val()) || 1000;

      if (min <= max) {
        minRangeEl.val(min);
        maxRangeEl.val(max);

        // Update green track width
        const minPercent = (min / 1000) * 100;
        const maxPercent = (max / 1000) * 100;
        const activeWidth = maxPercent - minPercent;
        const activeLeft = minPercent;

        const selector = context === 'mobile' ? '.mobile-filters-modal .slider-track' : '.shop-sidebar .slider-track';
        $(selector).attr('style', `--active-width: ${activeWidth}%; --active-left: ${activeLeft}%;`);

        if (!skipLoading) {
          updateFilters();
          loadProducts(1);
        }
      }
    }

    // Desktop events
    minRange.on('change', function () {
      updateSlider('desktop');
    });
    maxRange.on('change', function () {
      updateSlider('desktop');
    });
    priceMin.on('change', function () {
      updateInputs('desktop');
    });
    priceMax.on('change', function () {
      updateInputs('desktop');
    });

    // Mobile events
    mobileMinRange.on('input', function () {
      updateSlider('mobile');
    });
    mobileMaxRange.on('input', function () {
      updateSlider('mobile');
    });
    mobilePriceMin.on('change', function () {
      updateInputs('mobile');
    });
    mobilePriceMax.on('change', function () {
      updateInputs('mobile');
    });

    // Initialize slider displays
    updateSlider('desktop', true);
    updateSlider('mobile', true);
  }

  function loadProducts(page = 1) {
    showLoading();

    const data = {
      action: 'load_shop_products',
      page: page,
      orderby: currentFilters.orderby,
      categories: getAllSelectedCategories(),
      ...currentFilters,
    };

    const ajaxUrl = window.ajaxurl;

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      data: data,
      success: function (response) {
        hideLoading();
        if (response.success) {
          $('#productsGrid').html(response.data.products);
          $('#shopPagination').html(response.data.pagination);
          $('#resultsCount').text(`(${response.data.total} results)`);

          // Update mobile results count
          const mobileResultsCountEl = document.getElementById('mobileResultsCount');
          if (mobileResultsCountEl) {
            mobileResultsCountEl.textContent = response.data.total;
          }

          setTimeout(() => {
            if (typeof window.initEqualHeights === 'function') {
              window.initEqualHeights();
            }

            if (typeof window.initProductInteractions === 'function') {
              window.initProductInteractions();
            }

            if (typeof window.initProductVariations === 'function') {
              window.initProductVariations();
            }
          }, 100);

          if (response.data.total === 0) {
            $('#noProducts').show();
          } else {
            $('#noProducts').hide();
          }
        } else {
          $('#noProducts').show();
        }
      },
      error: function () {
        hideLoading();
        $('#noProducts').show();
      },
    });
  }

  function getAllSelectedCategories() {
    const selected = [];
    $('.filter-checkbox input:checked').each(function () {
      selected.push($(this).val());
    });

    return selected;
  }

  function updateFilters() {
    currentFilters.categories = $('input[name="category[]"]:checked')
      .map(function () {
        return this.value;
      })
      .get();

    currentFilters.priceMin = $('#priceMin').val();
    currentFilters.priceMax = $('#priceMax').val();
    currentFilters.orderby = $('.dropdown-selected').attr('data-value') || 'popularity';
  }

  function showLoading() {
    $('#loadingSpinner').addClass('show');
    $('#productsGrid').addClass('loading');
  }

  function hideLoading() {
    $('#loadingSpinner').removeClass('show');
    $('#productsGrid').removeClass('loading');
  }

  function bindEvents() {
    $(document).on('change', '.filter-checkbox input, #priceMin, #priceMax', function () {
      updateFilters();
      currentPage = 1;
      loadProducts(currentPage);
    });

    $(document).on('click', '.filter-group__title', function () {
      const target = $(this).data('toggle');
      const content = $(`#${target}Filters`);
      const icon = $(this).find('.toggle-icon svg');
      const filterGroup = $(this).closest('.filter-group');

      const isHidden = content.hasClass('hidden');

      if (isHidden) {
        content.removeClass('hidden').addClass('visible').slideDown();
        icon.addClass('rotated');
        filterGroup.addClass('active');
      } else {
        content.slideUp(function () {
          content.removeClass('visible').addClass('hidden');
        });
        icon.removeClass('rotated');
        filterGroup.removeClass('active');
      }
    });

    $(document).on('click', '.filter-group__title > .filter-checkbox', function (e) {
      e.stopPropagation();
    });

    $(document).on('click', '.toggle-icon', function (e) {
      e.stopPropagation();
      e.preventDefault();

      $(this).closest('.filter-group__title').trigger('click');
    });

    $(document).on('change', '.all-checkbox', function () {
      const category = $(this).data('category');
      const isChecked = $(this).is(':checked');
      const subCheckboxes = $(`.sub-checkbox[data-parent="${category}"]`);

      if (isChecked) {
        subCheckboxes.prop('checked', false);
      }

      updateFilters();
      currentPage = 1;
      loadProducts(currentPage);
    });

    $(document).on('change', '.sub-checkbox', function () {
      const category = $(this).data('parent');
      const allCheckbox = $(`.all-checkbox[data-category="${category}"]`);
      const subCheckboxes = $(`.sub-checkbox[data-parent="${category}"]`);
      const checkedSubs = subCheckboxes.filter(':checked');

      if (checkedSubs.length > 0) {
        allCheckbox.prop('checked', false);
      }

      updateFilters();
      currentPage = 1;
      loadProducts(currentPage);
    });

    $(document).on('change', '.grandchild-checkbox', function () {
      const parent = $(this).data('parent');
      const grandparent = $(this).data('grandparent');
      const allGrandchildCheckboxes = $(`.grandchild-checkbox[data-parent="${parent}"]`);
      const checkedGrandchildren = allGrandchildCheckboxes.filter(':checked');
      const parentCheckbox = $(`.sub-checkbox[value="${parent}"]`);
      const allCheckbox = $(`.all-checkbox[data-category="${grandparent}"]`);

      // If any grandchild is checked, uncheck the parent subcategory and "All"
      if (checkedGrandchildren.length > 0) {
        parentCheckbox.prop('checked', false);
        allCheckbox.prop('checked', false);
      }

      updateFilters();
      currentPage = 1;
      loadProducts(currentPage);
    });

    // Universal dropdown functionality for both shop and category pages
    $(document).on('click', '.dropdown-selected', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const dropdown = $(this).closest('.custom-dropdown');
      const options = dropdown.find('.dropdown-options');

      // Close other dropdowns
      $('.custom-dropdown').not(dropdown).find('.dropdown-options').hide();
      $('.dropdown-selected').not($(this)).removeClass('active');

      // Toggle current dropdown
      const isActive = $(this).hasClass('active');
      if (isActive) {
        $(this).removeClass('active');
        options.hide();
      } else {
        $(this).addClass('active');
        options.show();
      }
    });

    $(document).on('click', '.dropdown-option', function () {
      const option = $(this);
      const dropdown = option.closest('.custom-dropdown');
      const selected = dropdown.find('.dropdown-selected');
      const options = dropdown.find('.dropdown-options');

      // Update selected value and text
      const value = option.data('value');
      const text = option.text();

      selected.attr('data-value', value);
      selected.find('.selected-text').text(text);

      // Update active states
      dropdown.find('.dropdown-option').removeClass('active');
      option.addClass('active');

      // Hide dropdown
      selected.removeClass('active');
      options.hide();

      // Handle different page types
      if ($('body').hasClass('tax-product_cat')) {
        if (window.categoryPageInstance && typeof window.categoryPageInstance.loadProducts === 'function') {
          window.categoryPageInstance.loadProducts(1, value);
        }
      } else {
        currentFilters.orderby = value;
        currentPage = 1;
        loadProducts(currentPage);
      }
    });

    // Close dropdown when clicking outside (universal for both page types)
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.custom-dropdown').length) {
        $('.dropdown-options').hide();
        $('.dropdown-selected').removeClass('active');
      }
    });

    $(document).on('click', '.page-number', function (e) {
      e.preventDefault();

      if ($(this).hasClass('disabled')) {
        return false;
      }

      // Check if we're on a category page - if so, don't handle pagination here
      // Category pages have their own pagination handling
      if ($('body').hasClass('tax-product_cat')) {
        return;
      }

      currentPage = parseInt($(this).data('page'));
      loadProducts(currentPage);

      $('html, body').animate(
        {
          scrollTop: $('.shop-header').offset().top - 100,
        },
        500,
      );
    });

    $('#clearAllFilters, #clearFiltersButton').on('click', function () {
      $('.filter-checkbox input').prop('checked', false);
      $('#priceMin, #priceMax').val('');
      $('#minRange, #maxRange').val('');
      $('#minRange').val(0);
      $('#maxRange').val(1000);

      // Reset custom dropdown
      $('.dropdown-selected').attr('data-value', 'popularity');
      $('.dropdown-selected .selected-text').text('Sort by popularity');
      $('.dropdown-option').removeClass('active');
      $('.dropdown-option[data-value="popularity"]').addClass('active');

      currentFilters = {
        categories: [],
        priceMin: '',
        priceMax: '',
        orderby: 'popularity',
      };
      currentPage = 1;
      loadProducts(currentPage);
    });

    // Handle subcategory expansion (for subcategories with children)
    $(document).on('click', '.filter-checkbox.expandable .toggle-icon', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const expandable = $(this).closest('.expandable');
      const target = expandable.data('toggle');
      const content = $(`#${target}Filters`);
      const icon = $(this).find('img');

      const isHidden = content.hasClass('hidden');

      if (isHidden) {
        content.removeClass('hidden').addClass('visible').slideDown();
        icon.addClass('rotated');
      } else {
        content.slideUp(function () {
          $(this).removeClass('visible').addClass('hidden');
        });
        icon.removeClass('rotated');
      }
    });
  }

  // Mobile filters functionality
  function initMobileFilters() {
    const mobileFiltersBtn = document.getElementById('mobileFiltersBtn');
    const mobileFiltersModal = document.getElementById('mobileFiltersModal');
    const closeFiltersBtn = document.getElementById('closeFilters');
    const showResultsBtn = document.getElementById('showResultsBtn');
    const mobileClearAllFilters = document.getElementById('mobileClearAllFilters');
    const filtersCountEl = document.getElementById('filtersCount');

    if (!mobileFiltersBtn || !mobileFiltersModal) return;

    // Open mobile filters
    mobileFiltersBtn.addEventListener('click', function () {
      mobileFiltersModal.classList.add('active');
      document.body.style.overflow = 'hidden';
      syncFiltersToModal();
    });

    // Close mobile filters
    function closeMobileFilters() {
      mobileFiltersModal.classList.remove('active');
      document.body.style.overflow = '';
    }

    closeFiltersBtn?.addEventListener('click', closeMobileFilters);

    // Mobile clear all filters
    mobileClearAllFilters?.addEventListener('click', function () {
      $('.mobile-filters-modal .filter-checkbox input').prop('checked', false);
      $('#mobilePriceMin, #mobilePriceMax').val('');
      $('#mobileMinRange').val(0);
      $('#mobileMaxRange').val(1000);

      // Close all opened filter groups
      $('.mobile-filters-modal .filter-group').removeClass('active');
      $('.mobile-filters-modal .filter-group__content').removeClass('visible').addClass('hidden');
      $('.mobile-filters-modal .toggle-icon img').removeClass('rotated');

      updateFiltersCount();
      updateMobileResultsCount();
    });

    // Close on overlay click
    mobileFiltersModal.addEventListener('click', function (e) {
      if (e.target === mobileFiltersModal) {
        closeMobileFilters();
      }
    });

    // Show results button
    showResultsBtn?.addEventListener('click', function () {
      syncFiltersFromModal();
      updateFilters();
      currentPage = 1;
      loadProducts(currentPage);
      closeMobileFilters();
    });

    // Sync filters from desktop to modal
    function syncFiltersToModal() {
      // Sync checkboxes
      $('.shop-sidebar input[type="checkbox"]').each(function () {
        const value = $(this).val();
        const name = $(this).attr('name');
        const checked = $(this).is(':checked');

        $('.mobile-filters-modal input[value="' + value + '"][name="' + name + '"]').prop('checked', checked);
      });

      // Sync price inputs and sliders
      const priceMin = $('#priceMin').val();
      const priceMax = $('#priceMax').val();
      $('#mobilePriceMin').val(priceMin);
      $('#mobilePriceMax').val(priceMax);
      $('#mobileMinRange').val(priceMin);
      $('#mobileMaxRange').val(priceMax);
    }

    // Sync filters from modal to desktop
    function syncFiltersFromModal() {
      // Sync checkboxes
      $('.mobile-filters-modal input[type="checkbox"]').each(function () {
        const value = $(this).val();
        const name = $(this).attr('name');
        const checked = $(this).is(':checked');

        $('.shop-sidebar input[value="' + value + '"][name="' + name + '"]').prop('checked', checked);
      });

      // Sync price inputs
      const priceMin = $('#mobilePriceMin').val();
      const priceMax = $('#mobilePriceMax').val();
      $('#priceMin').val(priceMin);
      $('#priceMax').val(priceMax);

      // Update desktop price slider if it exists
      if (typeof updatePriceSlider === 'function') {
        updatePriceSlider();
      }
    }

    // Mobile price slider handlers
    $('.mobile-filters-modal #mobileMinRange, .mobile-filters-modal #mobileMaxRange').on('input', function () {
      const minRange = parseFloat($('#mobileMinRange').val());
      const maxRange = parseFloat($('#mobileMaxRange').val());
      $('#mobilePriceMin').val(minRange);
      $('#mobilePriceMax').val(maxRange);
    });

    $('.mobile-filters-modal #mobilePriceMin, .mobile-filters-modal #mobilePriceMax').on('input', function () {
      const minPrice = parseFloat($('#mobilePriceMin').val());
      const maxPrice = parseFloat($('#mobilePriceMax').val());
      $('#mobileMinRange').val(minPrice);
      $('#mobileMaxRange').val(maxPrice);
    });

    // Mobile filter toggle handlers
    $(document).on('click', '.mobile-filters-modal .filter-group__title', function () {
      const target = $(this).data('toggle');
      const content = $(`.mobile-filters-modal #${target}Filters`);
      const icon = $(this).find('.toggle-icon img');
      const filterGroup = $(this).closest('.filter-group');

      const isHidden = content.hasClass('hidden');

      if (isHidden) {
        content.removeClass('hidden').addClass('visible').slideDown();
        icon.addClass('rotated');
        filterGroup.addClass('active');

        // Auto-select "All" checkbox when opening category
        const allCheckbox = content.find('.all-checkbox');
        if (allCheckbox.length > 0 && !allCheckbox.is(':checked')) {
          allCheckbox.prop('checked', true).trigger('change');
        }
      } else {
        content.slideUp(function () {
          $(this).removeClass('visible').addClass('hidden');
        });
        icon.removeClass('rotated');
        filterGroup.removeClass('active');
      }
    });

    // Mobile "All" checkbox handler
    $(document).on('change', '.mobile-filters-modal .all-checkbox', function () {
      const category = $(this).data('category');
      const isChecked = $(this).is(':checked');
      const subCheckboxes = $(`.mobile-filters-modal .sub-checkbox[data-parent="${category}"]`);

      if (isChecked) {
        subCheckboxes.prop('checked', false);
      }

      updateFiltersCount();
      updateMobileResultsCount();
    });

    // Mobile sub-checkbox handler
    $(document).on('change', '.mobile-filters-modal .sub-checkbox', function () {
      const category = $(this).data('parent');
      const allCheckbox = $(`.mobile-filters-modal .all-checkbox[data-category="${category}"]`);
      const subCheckboxes = $(`.mobile-filters-modal .sub-checkbox[data-parent="${category}"]`);
      const checkedSubs = subCheckboxes.filter(':checked');

      if (checkedSubs.length > 0) {
        allCheckbox.prop('checked', false);
      }

      updateFiltersCount();
      updateMobileResultsCount();
    });

    // Update mobile results count with live AJAX call
    function updateMobileResultsCount() {
      const data = {
        action: 'get_shop_products_count',
        categories: getMobileSelectedCategories(),
        priceMin: $('#mobilePriceMin').val(),
        priceMax: $('#mobilePriceMax').val(),
      };

      const ajaxUrl = window.ajaxurl;

      $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: data,
        success: function (response) {
          if (response.success) {
            const resultsCountEl = document.getElementById('mobileResultsCount');
            if (resultsCountEl) {
              resultsCountEl.textContent = response.data.count;
            }
          }
        },
      });
    }

    function getMobileSelectedCategories() {
      const selected = [];
      $('.mobile-filters-modal .filter-checkbox input:checked').each(function () {
        selected.push($(this).val());
      });
      return selected;
    }

    // Update filters count
    function updateFiltersCount() {
      const checkedFilters = $('.mobile-filters-modal input[type="checkbox"]:checked').length;
      const priceMin = $('#mobilePriceMin').val();
      const priceMax = $('#mobilePriceMax').val();

      let count = checkedFilters;
      if (priceMin && priceMin !== '0') count++;
      if (priceMax && priceMax !== '1000') count++;

      if (filtersCountEl) {
        filtersCountEl.textContent = count;
        filtersCountEl.style.display = count > 0 ? 'flex' : 'none';
      }
    }

    // Listen to filter changes in modal
    $(document).on('change', '.mobile-filters-modal input[type="checkbox"], .mobile-filters-modal input[type="number"]', function () {
      updateFiltersCount();
      updateMobileResultsCount();
    });

    // Initialize count on page load
    updateFiltersCount();
  }

  // Initialize for shop pages
  if ($('body').hasClass('page-template-page-shop') || $('.shop-page').length > 0) {
    initShopPage();
    initMobileFilters();
  }

  // Initialize dropdown events for category pages (but not full shop functionality)
  if ($('body').hasClass('tax-product_cat')) {
    // Only bind dropdown events for category pages
    bindDropdownEvents();
  }

  function bindDropdownEvents() {
    // Just bind the universal dropdown events that are already defined above
    // The actual event handlers are already bound via $(document).on() so they work globally
  }
});
