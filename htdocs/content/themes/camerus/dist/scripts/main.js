'use strict';

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var μ = {
  window: $(window),
  document: $(document),
  html: $('html'),
  body: $('body'),
  page: $('#page'),
  header: $('#header'),
  main: $('#main'),
  footer: $('#footer')
};

μ.document.ready(function () {
  ui.form.textarea(); //autosize textarea
  ui.form.dynamicLabel(); //animate the labels
  //ui.form.radio();                  //uiRadio
  ui.form.checkbox(); //uiCheckbox
  ui.form.validation(); //form validation

  ui.elements.matchHeight(); //give the same height to targeted elements

  //ui.browser.detect();              //detect current browser name and version

  ui.animation.slideup(); //animate elements with .slide-up class


  ui.block.product__title(); //customize woocommerce datasheet to fit the design
  ui.jquerUi.selectmenu(); //init the jquery ui selectmenu

  ui.responsive.table(); //add special class for responsive tables

  ui.links.dummyLink(); //avoid page scrolling when an empty link is clicked
  ui.links.scrollTo(); //give a link the ability of scrolling to an anchor

  ui.wrap.articles__preview(); //wrap articles__preview blocks together;
  ui.wrap.blog__preview(); //wrap blog__preview blocks together;

  ui.uikit.offcanvas_nav(); //put elements inside offCanvas
  ui.uikit.dropdown_boundary(); //create boundary for the .block-topbar__primary
  ui.uikit.height_match(); //programmaticaly match block height
  // ui.uikit.slider(); //fix conflict with uk-switcher
  ui.uikit.notifications(); //convert woo-commerce notifications to uikit notifications

  ui.slick.product__slider(); //init the slider inside block-product__slider
  ui.slick.product__nav(); //init the slider inside block-product__nav

  ui.ns.init(); //create .num-spinner programmatically
  //ui.ns.increment();                //increment the .num-spinner
  ui.ns.autogrow(); //autogrow the .num-spinner input

  ui.block.widget__categories(); //make a dropdown version of .block-widget__categories for xs screens
  ui.block.widget__cartnav(); //make a dropdown version of .block-widget__cartnav for xs screens
  ui.block.product__filter(); //make a dropdown version of .block-product__filter for xs screens
  ui.block.inspirations__filter(); //make a dropdown version of .block-inspirations__filter for xs screens
  ui.block.cart__payment(); //move checkbox on the .block-cart__payment accordion
  ui.block.scrolltop(); //customize the apparition of the scrollTop link
  ui.block.cart__estimation(); //customize woocommerce cart
  ui.block.calendar__slider(); //customize .block-calendar__slider behavior
  ui.block.banner__slider(); //customize .block-banner__slider behavior
  ui.block.topbar__primary(); //customize .block-topbar__primary behavior

  ui.devmode.topbar__tools(); //show the user dropdown on dev mode
  ui.print.agenda(); //make modifications for print version of 08-Agenda.html

  ui.page.blog(); //detect blog page and add class;
  ui.page.download(); //detect download page and add class;
  ui.page.payment(); //customize the payment page navigation

  ui.url.print(); //detect '#print' inside the url then print the page;

  ui.sketch.modalSketch();

  ui.dropdownFilter.drop();

  ui.modal.product(); //init slick slider on modal show

  $('.btn-scroller').click(function () {
    $([document.documentElement, document.body]).animate({
      scrollTop: $('#main').offset().top
    }, 1000);
  });

  $('.default-check').change(function () {
    $('.product-filter-list input[name=\'filter\']:checked').prop('checked', false);
  });

  $('.product-filter-list input[name=\'filter\']').change(function () {
    if ($(this).is(':checked')) {
      $('.product-filter-list .default-check').prop('checked', false);
    }
  });

  $(document).scroll(function () {
    var y = $(this).scrollTop();
    if (y > 800) {
      $('.scroller').fadeIn();
      console.log('object');
    } else {
      $('.scroller').fadeOut();
    }
  });
});

μ.document.on('ajaxComplete', function () {
  ui.elements.matchHeight(); //make blocs same height
  $.fn.matchHeight._update();
  ui.links.dummyLink(); //avoid page scrolling when an empty link is clicked
  ui.uikit.notifications(); //convert woo-commerce notifications to uikit notifications
});

μ.window.on('load', function () {
  // ui.animation.loader(); //fully load the page before showing contents
  $.fn.matchHeight._update();
});

μ.window.on('beforeprint', function (e) {
  e.preventDefault();
  μ.body.addClass('print');
  $.fn.matchHeight._update();
});

μ.window.on('afterprint', function () {
  μ.body.removeClass('print');
  $.fn.matchHeight._update();
});

var ui = {
  sketch: {
    modalSketch: function modalSketch() {
      var sketch = $('a[data-src*="sketchfab"]');

      sketch.on('click', function () {
        var regex = /3d-models\/.*-([\w\d]*)/;
        var sketch_code = regex.exec($(this).attr('data-src'))[1];
        var iframe = $('#modal-sketch').find('iframe');

        iframe.attr('src', 'https://sketchfab.com/models/' + sketch_code + '/embed?autostart=1&transparent=0&autospin=0&controls=0&watermark=0');

        $(this).on('hidden', function () {
          iframe.removeAttr('src');
        });
      });
    }
  },
  devmode: {
    topbar__tools: function topbar__tools() {
      var regex = /localhost/;
      if (regex.test(window.location.origin)) {
        UIkit.dropdown('.account [data-uk-dropdown]').show();
      }
    }
  },
  links: {
    dummyLink: function dummyLink() {

      /*-- avoid page scrolling when an empty link is clicked --*/
      $('a[href="#"]').on('click', function (e) {
        e.preventDefault();
      });
      /*-- end: avoid page scrolling when an empty link is clicked --*/
    },
    scrollTo: function scrollTo() {

      /*-- give a link the ability of scrolling to an anchor --*/
      var $link = $('a.action-scrollTo');
      var $body = $('html, body');
      if ($link.length) {

        $link.on('click', function (e) {
          e.preventDefault();
          var $this = $(this);
          var target = $($this.attr('href')).offset().top;
          $body.animate({
            'scrollTop': target
          }, 750);
        });
        /*-- give a link the ability of scrolling to an anchor --*/
      }
    },
    close: function close() {
      history.back();
    }
  },
  elements: {
    matchHeight: function matchHeight() {

      var selector = '';
      selector += '.card-suggestions__product .card-body';
      selector += ',';
      selector += '.block-product__link.block .block-body .cart';
      selector += ',';
      selector += '.card-tools__search.card .container-input.coloris.mat';

      var $selector = $(selector);
      if ($selector.length) {
        $selector.matchHeight();
      }

      /*- give the same height to targeted elements --*/
      for (var x = 0; x < 5; x++) {
        var $matchHeight = $('.match-' + x);
        if ($matchHeight.length) {
          $matchHeight.matchHeight();
        }
      }
      /*- end: give the same height to targeted elements --*/

      ui.elements.matchHeightFallback();
    },
    matchHeightFallback: function matchHeightFallback() {

      var $match = $('[class*="match-"]');
      if ($match.length) {
        var interval = setInterval(function () {
          if ($match.length == $('[class*="match-"][style=""]').length) {
            $.fn.matchHeight._update();
            console.log('iterate');
          } else {
            clearInterval(interval);
            //console.log('stop iterattion');
          }
        }, 1000);
      }
    }
  },
  form: {
    checkbox: function checkbox() {

      var $checkbox = $('input[type="checkbox"].checkbox');

      if ($checkbox.length) {
        $checkbox.checkboxradio({
          classes: {
            'ui-checkboxradio-label': 'ui-checkboxradio-label ui-checkbox-label'
          }
        });
      }
    },
    radio: function radio() {

      var $radio = $('input[type="radio"].radio');
      if ($radio.length) {
        $radio.checkboxradio({
          classes: {
            'ui-checkboxradio-label': 'ui-checkboxradio-label ui-radio-label'
          }
        });
      }
    },
    textarea: function textarea() {
      autosize($('textarea'));
    },
    dynamicLabel: function dynamicLabel() {
      var $superform = $('form.superform');

      if ($superform.length) {
        var refresh = function refresh(input) {
          var $this = $(input);
          var $formGroup = $this.closest('.form-group');
          var $textArealabel = $formGroup.find('> label');
          if ($this.val() != '') {

            $formGroup.find('>.form-control, >.fileWrapper').addClass('active');
            if ($this.prop('tagName') == 'TEXTAREA' && $textArealabel.length) {
              $textArealabel.addClass('active');
            }
          } else {

            $formGroup.find('>.form-control, >.fileWrapper').removeClass('active');
            if ($this.prop('tagName') == 'TEXTAREA' && $textArealabel.length) {
              $textArealabel.removeClass('active');
            }
          }
        };

        var $formControl = $superform.find('.form-group .form-control, .form-group .fileWrapper input[type="file"]');

        $formControl.each(function () {
          refresh(this);
        });

        $formControl.on('change keydown keyup keypress focus blur', function () {
          refresh(this);
        });
      }
    },
    validation: function validation() {
      var $form = $('form');
      if ($form.length) {
        $form.find('[type=submit],.btn').on('click', function () {
          var $this = $(this);
          var $form = $this.closest('form');
          $form.removeClass('invalid');
          if ($form.find(':invalid').length > 0) {
            $form.addClass('invalid');
          }
        });
      }
    }
  },
  browser: {
    detect: function detect() {
      var result = browserDetect();
      μ.html.addClass(result.name);
      //console.log(result);
    }
  },
  animation: {
    slideup: function slideup() {
      var $window = $(window);
      var $slideUp = $('.slide-up');
      //$slideUp = $($slideUp[0]);
      if ($slideUp.length) {
        $slideUp.attr('data-appearance', 'slide-up');

        var slideUp = function method() {
          $slideUp.each(function () {
            var $this = $(this);
            var flotation = $window.height() - $this.offset().top + $window.scrollTop();
            if (flotation > 100) {
              $this.removeClass('slide-up');
              $slideUp = $('.slide-up');
            }
          });
          return method;
        }();
        $window.on('scroll', slideUp);
      }
    },
    loader: function loader() {
      var $loader = $('body > #loader');
      if ($loader.length) {
        $loader.addClass('loaded');
        $loader.fadeOut(1000, function () {
          $loader.remove();
        });
      }
    }
  },
  jquerUi: {
    selectmenu: function selectmenu() {
      $.widget('custom.iconselectmenu', $.ui.selectmenu, {
        _renderButtonItem: function _renderButtonItem(item) {
          var buttonItem = $('<span>', {
            'class': 'ui-selectmenu-text'
          });
          //if there is data-value use it as text instead of the content of the option
          if (item.element.attr('data-value')) {
            buttonItem.html(item.element.attr('data-value'));
          } else {
            this._setText(buttonItem, item.label);
          }

          if (item.element.attr('data-bg')) {
            $('<span>', {
              style: 'background:' + item.element.attr('data-bg'),
              class: 'bg'
            }).prependTo(buttonItem);
          }

          //buttonItem.css( "background-color", item.value )

          return buttonItem;
        },
        _renderItem: function _renderItem(ul, item) {
          var li = $('<li>');
          if (item.element.attr('data-value')) {
            var wrapper = $('<div>', {
              html: item.element.attr('data-value')
            });
          } else {
            // var wrapper = $( '<div>', { text: item.label } );
            var wrapper = $('<div>', {
              text: item.label
            });
          }

          li.on('click', function () {
            setTimeout(function () {
              $(item.element[0].parentElement).trigger('change');
            }, 250);
          });

          if (item.disabled) {
            li.addClass('ui-state-disabled');
          }

          if (item.element.attr('data-bg')) {
            $('<span>', {
              style: 'background:' + item.element.attr('data-bg'),
              class: 'bg'
            }).prependTo(wrapper);
          }

          if (item.element.attr('data-color')) {
            switch (true) {
              case /^#([0-9a-f]{3}){0,2}$/.test(item.element.attr('data-color')):
                $('<span>', {
                  style: 'background:' + item.element.attr('data-color'),
                  class: 'bg-coloris'
                }).prependTo(wrapper);
                break;
              case /.*\.(png|jp(e)?g|gif|svg)$/.test(item.element.attr('data-color')):
                $('<span>', {
                  style: 'background:url(' + item.element.attr('data-color') + ')',
                  class: 'bg-coloris'
                }).prependTo(wrapper);
                break;
            }
          }

          return li.append(wrapper).appendTo(ul);
        }
      });

      $('select.select').on('selectmenucreate', function (event, ui) {}).iconselectmenu({
        classes: {
          'ui-selectmenu-button': 'ui-selectmenu-button',
          'ui-selectmenu-icon': 'ui-selectmenu-icon icon icon-selectmenu-arrows'
        }
      }).iconselectmenu('menuWidget')
      /*.selectmenu({
        classes: {
          'ui-selectmenu-button': 'ui-selectmenu-button',
          'ui-selectmenu-icon': 'ui-selectmenu-icon icon icon-selectmenu-arrows',
        }
      })*/
      .on('selectmenuopen', function (event, ui) {
        var $this = $(this);
        var select_id = $this.attr('id');
        var menu_id = select_id + '-menu';
        var $selectmenu = $('#' + menu_id);
        var $selectmenu_width = $('#' + select_id + '-button').outerWidth();
        $('select.select').not($this).selectmenu('close');

        if (μ.window.width() < 1024) {
          //$('html, body').animate({scrollTop:$this.parent('.form-group').offset().top - 100},750)
        }

        $selectmenu.removeAttr('style');
        $selectmenu.parent().css({
          'width': $selectmenu_width
        }).find('ul').css({
          'max-height': μ.window.height() / 3
        });
      });
    }
  },
  responsive: {
    table: function table() {
      var $tables = $('#rte table');
      $tables.each(function () {
        var $this = $(this);
        var $this_images = $this.find('img');
        if ($this_images.length) {
          $this.addClass('has-images');
        } else {
          $this.addClass('table').wrap('<div class="table-responsive"></div>');
        }
      });
    }
  },
  wrap: {
    articles__preview: function articles__preview() {
      var selector = '.block-articles__preview';
      var $articles__preview = $(selector);

      $('.block-calendar__event').each(function () {
        if ($(this).siblings(selector).length == 0) {
          $(this).addClass('no-preview');
        }
      });

      if ($articles__preview.length) {
        var $parents = $articles__preview.parent();
        $parents.each(function () {
          var $this = $(this);
          $this.find(selector).wrapAll('<div class="container-articles__preview uk-grid-large uk-margin-remove-top" uk-grid></div>');
        });
      }
    },
    blog__preview: function blog__preview() {
      var selector = '.block-blog__preview, .block-blog__introduction';
      var $blog__preview = $(selector);
      if ($blog__preview.length) {
        var $parents = $blog__preview.parent();
        $parents.each(function () {
          var $this = $(this);
          $this.find(selector).wrapAll('<div class="container-blog__preview uk-grid-large uk-margin-remove-top" uk-grid></div>');
        });
      }
    }
  },
  uikit: {
    offcanvas_nav: function offcanvas_nav() {
      var $navbar_nav = $('.block-topbar__primary .uk-navbar-nav');
      var $offcanvas_bar = $('<div id="offcanvas-topbar__primary" uk-offcanvas="flip:true;overlay:true"><div class="uk-offcanvas-bar"><div class="offcanvas-container"></div></div></div>');
      if ($navbar_nav.length) {
        var $navbar_nav_primary = $navbar_nav.clone();
        var $navbar_nav_secondary = $('.block-topbar__secondary .uk-navbar-nav').clone();
        μ.page.after($offcanvas_bar);

        $navbar_nav_primary.add($navbar_nav_secondary).find('.uk-drop').removeAttr('data-uk-drop').attr({
          class: 'uk-nav-sub'
        }).each(function () {
          $(this).html($navbar_nav_primary.find('ul.uk-nav').closest('[data-uk-grid]').html());
        });

        $navbar_nav_primary.add($navbar_nav_secondary).attr('class', 'uk-nav uk-nav-default');

        $offcanvas_bar.find('.offcanvas-container').append($navbar_nav_primary).append($navbar_nav_secondary);
      }
    },
    dropdown_boundary: function dropdown_boundary() {
      μ.page.prepend($('<div id="boundary" class="section"><div class="section-header container-fluid inner"><div class="row"><div class="col-sm-10 col-sm-offset-1"><div class="block block-boundary"></div></div></div></div></div>'));
    },
    height_match: function height_match() {
      var selector = '';
      selector += '.block-rte__default .block-body';
      selector += ',';
      selector += '.block-cart__title .block-body';
      var $blocks = $(selector);
      if ($blocks.length) {
        UIkit.heightMatch('#main', {
          target: selector
        });
      }

      var elements = ['.card-events__item .img-container', '.card-events__item .title', '.block-calendar__event.block .block-content .title', '.block-calendar__event.block .block-content .address'];
      for (var x in elements) {
        $(elements[x]).matchHeight();
      }
    },
    slider: function slider() {
      var $slider = $('.block-calendar__slider .block-container');
      if ($slider.length) {
        UIkit.slider($slider);
        $slider.on('beforeitemshow itemshow itemshown beforeitemhide itemhide itemhidden', function () {
          $slider.find('li.uk-active').removeClass('uk-active');
          UIkit.heightMatch('.ticket .ticket-header, .ticket .ticket-body');
        });
      }
    },
    notifications: function notifications() {

      //wrap unwrapped messages
      var $message = $('.woocommerce-Message');
      if ($message.length && $message.text() != '') {
        $message.wrapInner('<div></div>');
      }

      //begin the notification preprocess
      var $notification = $('.woocommerce-error > li, .woocommerce-notices-wrapper li, .woocommerce-notices-wrapper > div, .woocommerce-Message > div');
      var timeout = 120000;
      $notification.each(function () {
        var $this = $(this);
        var array = [];
        array['woo-class'] = ['woocommerce-error', 'woocommerce-warning', 'woocommerce-success', 'woocommerce-primary'];
        array['uk-class'] = ['danger', 'warning', 'success', 'primary'];
        array['uk-icon'] = ['warning-sign', 'exclamation-sign', 'ok', 'info-sign'];

        //choose the correct notification style
        var status = 'primary';
        var icon = 'info-sign';
        for (var x in array['woo-class']) {
          var tagname = $this[0].tagName.toLowerCase();
          var $tag;
          switch (tagname) {
            case 'li':
              $tag = $this.closest('ul');
              break;
            case 'div':
              $tag = $this;
              break;
          }
          if ($tag.hasClass(array['woo-class'][x])) {
            status = array['uk-class'][x];
            icon = array['uk-icon'][x];
            break;
          }
        }

        //display the uikit notification
        UIkit.notification({
          message: '<span class="icon glyphicon glyphicon-' + icon + '"></span>' + $this.html(),
          status: status,
          pos: 'top-right',
          timeout: timeout
        });

        //remove the woo notification
        $this.remove();
      });

      //"add to favourites" notification
      var $notification_favourites = $('#yith-wcwl-popup-message');

      if ($notification_favourites.length && typeof $notification_favourites.data('events') == 'undefined') {

        $notification_favourites.attrchange({
          trackValues: true,
          /* Default to false, if set to true the event object is
          updated with old and new value.*/
          callback: function callback(event) {
            //event    	          - event object
            //event.attributeName - Name of the attribute modified
            //event.oldValue      - Previous value of the modified attribute
            //event.newValue      - New value of the modified attribute
            //Triggered when the selected elements attribute is added/updated/removed
            if (event.attributeName == 'style') {
              if (typeof $notification_favourites.data('attrchange') == 'undefined' && event.oldValue.search(/display: none;$/) >= 0 && event.newValue.search(/opacity: 0;$/) >= 0) {
                $notification_favourites.data('attrchange', true);
                var status = 'primary';
                UIkit.notification({
                  message: '<span class="icon glyphicon glyphicon-info-sign"></span>' + $notification_favourites.text(),
                  status: status,
                  pos: 'top-right',
                  timeout: timeout
                });
              } else if (event.oldValue.search(/display: none;/) >= 0 && event.newValue.search(/display: none;/) >= 0) {
                $notification_favourites.removeData('attrchange');
              }
            }
          }
        });
      }

      //check periodically for new notifications to show
      setTimeout(ui.uikit.notifications, 1000);
    }
  },
  slick: {
    product__slider: function product__slider() {
      var $slider = $('.block-product__slider .block-body:not(#pdf .block-product__slider .block-body)');
      if ($slider.length) {
        $slider.slick({
          arrows: false,
          infinite: false,
          asNavFor: '.block-product__nav .block-body'
        });
      }
    },
    product__nav: function product__nav() {
      var $slider = $('.block-product__nav .block-body');
      if ($slider.length) {
        $slider.slick({
          arrows: false,
          vertical: true,
          verticalSwiping: true,
          slidesToShow: 3,
          asNavFor: '.block-product__slider .block-body',
          focusOnSelect: true
        });
      }
    }
  },
  ns: {
    init: function init() {
      var selector = '';
      selector += '.quantity .qty';
      var $selector = $(selector);
      $selector.each(function () {
        var $this = $(this);
        if (!$this.parent().hasClass('num-spinner')) {
          $this.wrap('<div class="num-spinner uk-flex"></div>');
          $this.before('<span class="btn-container uk-flex uk-flex-column"><button onclick="ui.ns.increment(\'+\',this)" type="button" class="btn"><i class="icon icon-product-arrow-up"></i></button><button onclick="ui.ns.increment(\'-\',this)" type="button" class="btn"><i class="icon icon-product-arrow-down"></i></button></span>');
        }
      });
    },
    increment: function increment(sign, self) {
      var $this = $(self);
      var $input = $this.closest('.num-spinner').find('input');
      var value = parseInt($input.val());
      switch (sign) {
        case '+':
          var value_new = value + 1;
          $input.val(value + 1);
          $input.attr('value', value_new);
          $input.trigger('change').trigger('input');
          break;
        case '-':
          if (value > 1) {
            var value_new = value - 1;
            $input.val(value - 1);
            $input.attr('value', value_new);
            $input.trigger('change').trigger('input');
          }
          break;
      }
      //remove disabled attribute from the disabled button on cart update
      var $btn_cart_disabled = $('[name="update_cart"][disabled]');
      if ($btn_cart_disabled.length) {
        $btn_cart_disabled.removeAttr('disabled');
      }
    },
    autogrow: function autogrow() {
      var refreshNumSpinner = function method() {
        var $input = $('.num-spinner input');
        if ($input.length) {
          $input.inputAutogrow();
          $input.off('keypress');
          $input.on('keypress', function (e) {
            if (!($(this).val().length < 5 || e.which == 46)) {
              e.preventDefault();
            }
          });
        }
        return method;
      }();
      μ.window.on('load', refreshNumSpinner);
    }
  },
  block: {
    widget__categories: function widget__categories() {
      var $block = $('.block-widget__categories').eq(0);
      if ($block.length) {
        var $block_header = $block.find('.block-header');
        if ($block_header.length == 0) {
          $block.find('.block-content').prepend($('<div class="block-header" data-uk-toggle="target:.block-widget__categories;cls:active"></div>'));
          $block_header = $block.find('.block-header');
        }

        var $block_body = $block.find('.block-body');

        var $block_footer = $block.find('.block-footer');

        if ($block_footer.length == 0) {
          $block.find('.block-content').append($('<div class="block-footer uk-animation-fade uk-animation-fast" data-uk-toggle="target:.block-widget__categories;cls:active"></div>'));
          var $block_footer = $block.find('.block-footer');
        }

        var $block_body_active = $block.find('.block-body .active');
        $block_header.html('<span>' + $block_body_active.text() + '</span><i class="icon icon-selectmenu-arrows"></i>');
      }
    },
    widget__cartnav: function widget__cartnav() {
      var $block = $('.block-widget__cartnav').eq(0);
      if ($block.length) {
        var $block_header = $block.find('.block-header');
        if ($block_header.length == 0) {
          $block.find('.block-content').prepend($('<div class="block-header" data-uk-toggle="target:.block-widget__cartnav;cls:active"></div>'));
          $block_header = $block.find('.block-header');
        }

        var $block_body = $block.find('.block-body');

        var $block_footer = $block.find('.block-footer');

        if ($block_footer.length == 0) {
          $block.find('.block-content').append($('<div class="block-footer uk-animation-fade uk-animation-fast" data-uk-toggle="target:.block-widget__cartnav;cls:active"></div>'));
          var $block_footer = $block.find('.block-footer');
        }

        var $block_body_active = $block.find('.block-body .active');
        $block_header.html('<span>' + $block_body_active.text() + '</span><i class="icon icon-selectmenu-arrows"></i>');
      }
    },
    product__filter: function product__filter() {
      var $block = $('.block-product__filter');

      var $block_footer = $block.find('.block-footer');

      if ($block_footer.length == 0) {
        $block.find('.block-content').append($('<div class="block-footer uk-animation-fade uk-animation-fast" data-uk-toggle="target:.block-product__filter;cls:active"></div>'));
        var $block_footer = $block.find('.block-footer');
      }

      //manually submit the order form on select "change" event
      var $order = $('form.woocommerce-ordering select.orderby');
      if ($order.length) {
        $order.on('change', function () {
          $('form.woocommerce-ordering').trigger('submit');
        });
      }
    },
    cart__payment: function cart__payment() {
      var $accordion = $('.card-payment__method .card-header');
      if ($accordion.length) {
        $accordion.on('click', function () {
          var $this = $(this);
          $this.closest('.card').attr('class');
          $this.find('input')[0].checked = true;
        });
      }
    },
    inspirations__filter: function inspirations__filter() {
      var $block = $('.block-inspirations__filter').eq(0);
      if ($block.length) {
        var $block_header = $block.find('.block-header');
        if ($block_header.length == 0) {
          $block.find('.block-content').prepend($('<div class="block-header" data-uk-toggle="target:.block-inspirations__filter;cls:active"></div>'));
          $block_header = $block.find('.block-header');
        }

        var $block_body = $block.find('.block-body');

        var $block_footer = $block.find('.block-footer');

        if ($block_footer.length == 0) {
          $block.find('.block-content').append($('<div class="block-footer uk-animation-fade uk-animation-fast" data-uk-toggle="target:.block-inspirations__filter;cls:active"></div>'));
          var $block_footer = $block.find('.block-footer');
        }

        var $block_body_active = $block.find('.block-body .active');
        if ($block_body_active.length === 0) {
          $block_body.addClass('no-active');
          $block_body_active = $block.find('.block-body > li:first-child');
          $block_body_active.addClass('active');
        }
        $block_header.html('<span>' + $block_body_active.text() + '</span><i class="icon icon-selectmenu-arrows"></i>');
      }
    },
    scrolltop: function scrolltop() {
      var $block = $('.block-scrolltop');
      if ($block.length) {
        μ.window.on('scroll', function () {
          if (μ.window.scrollTop() >= μ.window.height() / 2) {
            $block.addClass('active');
          } else {
            $block.removeClass('active');
          }
        });
      }
    },
    product__title: function product__title() {

      var $block = $('.block-product__characteristics');
      if ($block.length) {
        var $select = $block.find('select');
        var $form = $block.find('form');

        //put select class on the selects
        $select.each(function () {
          var $this = $(this);
          $this.addClass('select');

          //add data-bg attribute to color select
          if ($this.attr('id') == 'pa_color') {
            $this.find('option').each(function () {
              var $this = $(this);
              if ($this.val()) {
                $this.attr('data-bg', $this.val());
              } else {
                $this.attr('data-bg', 'transparent');
              }
            });
          }

          //put here the code to add data-value **data-value="Paris - <strong>35 €</strong>"** for #product__characteristics-place
        });

        //add .block-content and .block-body
        if (!$form.hasClass('block-content')) {
          $form.addClass('block-content');
          $form.wrapInner('<div class="block-body"></div>');
        }
      }

      //Add Quantity text
      var $quantity = $('.woocommerce-variation-add-to-cart .quantity');
      if ($quantity.length) {
        $quantity.prepend('<strong class="label">' + $quantity.find('.screen-reader-text').text() + '</strong>');
        //add quantity classes
        $quantity.addClass('uk-width-auto uk-flex uk-flex-middle');

        $quantity.next('.single_add_to_cart_button').wrap('<div class="uk-width-expand uk-text-right"></div>');
      }
    },
    cart__estimation: function cart__estimation() {
      var $cart_totals = $('.block-cart__estimation._collaterals .cart_totals');
      if ($cart_totals.length) {
        $cart_totals.addClass('block-body');
        var elements = ['th', 'td', 'tr', 'tbody', 'tfoot', 'thead', 'table'];

        for (var x in elements) {

          var $elements = $cart_totals.find(elements[x]);
          $elements.each(function () {
            var $this = $(this);
            var attr = '';

            //remove tbody elements
            switch (elements[x]) {
              case 'th':
                $this.addClass('name');
                break;
              case 'td':
                $this.addClass('value');
                break;
            }

            $(this).each(function () {
              $.each(this.attributes, function () {
                // this.attributes is not a plain object, but an array
                // of attribute nodes, which contain both the name and value
                if (this.specified) {
                  attr += ' ' + this.name + '="' + this.value + '"';
                }
              });
            });

            //remove tbody elements
            switch (elements[x]) {
              case 'tbody':
                $this.replaceWith($this.html());
                break;
              case 'tr':
                $this.replaceWith('<li' + attr + '>' + $this.html() + '</li>');
                break;
              default:
                $this.replaceWith('<div' + attr + '>' + $this.html() + '</div>');
            }
          });
        }
      }

      /*//add the structure of the block
      $block.find('.cart_totals').addClass('block-content')
      .find('>h2').replaceWith('<div class="block-header"><i class="icon icon-cart__estimation-basket"></i></div>')
      $block.find('.shop_table').addClass('block-body');
      $block.find('.wc-proceed-to-checkout').addClass('block-footer');*/
    },
    calendar__slider: function calendar__slider() {
      var $switcher = $('.block-calendar__slider .block-body');
      if ($switcher.length) {
        var url = window.location.href;
        var target = /#[^\/]+/.exec(url);
        if (target != null) {
          var $li = $(target[0]).addClass('hover').closest('li');
          // UIkit.switcher($switcher).show($li.index());
        }

        var $ticket = $switcher.find('.ticket');
        $ticket.on('click', function () {
          $ticket.removeClass('hover');
          $(this).addClass('hover');
          $switcher.siblings('.block-footer').remove();
        });
      }
    },
    banner__slider: function banner__slider() {
      var $block = $('.home .block-banner__slider');
      if ($block.length) {
        var $block_body = $block.find('.block-body');

        var $img = $block_body.find('.img-container img');
        if ($img.length) {
          UIkit.parallax($img, {
            y: +1200
          });
        }

        var $video = $block_body.find('.img-container iframe, .img-container video');
        var $slogan = $block_body.find('.slogan');

        if ($video.length) {
          console.log('ok video');
          var $volume = $('<i class="volume glyphicon glyphicon-volume-off"></i>');
          $video.after($volume);
          /*try {
            if ($video[0].tagName.toLowerCase() === 'iframe') {
              const height = $video.attr('height');
              const width = $video.attr('width');
              $block_body.attr('data-uk-slideshow', `animation:fade;ratio:${width}:${height}`);
              const regex = /((&|\?)v=|embed\/)([^?&]+)/;
              const video_id = regex.exec($video.attr('src'))[3];
              //const src = `https://www.youtube.com/embed/${video_id}?autoplay=1&controls=0&cc_load_policy=1&disablekb=1&enablejsapi=1&loop=1&modestbranding=1&playsinline=1&color=white&playlist=${video_id}&mute=1&rel=0`;
               let src = $video.attr('src') + '?mute=1';
               $video.attr('src', src);
                $volume.click(function () {
                //const src = `https://www.youtube.com/embed/${video_id}?autoplay=1&controls=0&cc_load_policy=1&disablekb=1&enablejsapi=1&loop=1&modestbranding=1&playsinline=1&color=white&playlist=${video_id}&mute=0&rel=0`;
                 let src = $video.attr('src').replace('mute=1','mute=0&autoplay=1');
                 $video.attr('src', src);
                $video.addClass('sound-on');
              })
            } else if ($video[0].tagName.toLowerCase() === 'video') {
              $volume.click(function () {
                $video[0].play();
                $video[0].muted = false;
                $video.addClass('sound-on');
              })
            }
          } catch (error) {
            console.log(error);
          }*/
        }
      }
    },
    topbar__primary: function topbar__primary() {
      /*- patch for scrolling the topbar menu on touch for mobile devices -*/
      {
        var contact = false;
        var $surface = $('#offcanvas-topbar__primary .offcanvas-container');
        var scrollY = void 0;
        var clickY = void 0;
        if ($surface.length) {
          $surface.on('touchstart', function (e) {
            contact = true;
            clickY = e.touches[0].pageY;
            scrollY = $surface.scrollTop();
            console.log(e);
          });
          $surface.on('touchend', function (e) {
            contact = false;
          });
          $(window).on('touchmove', function (e) {
            if (contact) {
              var distance = clickY - e.touches[0].pageY;
              $surface.scrollTop(scrollY + distance);
            }
          });
        }
      }
    }
  },
  print: {
    screen: function screen() {
      window.print();
    },
    agenda: function agenda() {
      var $block = $('.block-calendar__event');
      $block.each(function () {
        var $this = $(this);
        $this.find('.col-left').prepend($this.find('.col-right .date').clone());
      });
    }
  },
  page: {
    blog: function blog() {
      var $blocks = $('.block-inspirations__logo, .block-inspirations__filter');
      if ($blocks.length == 2) {
        μ.body.addClass('page-blog');
      }
    },
    download: function download() {
      var $blocks = $('.block-download__title');
      if ($blocks.length) {
        μ.body.addClass('page-telechargement');
      }
    },
    payment: function payment() {
      var $btn = $('.btn.nav-process');
      var $link = $('.block-widget__cartnav li .nav-process');
      if ($btn.length) {
        $btn.on('click', function () {
          var n = $(this).attr('data-process');

          console.log($link.filter('[data-process="' + n + '"]').length + ' : ' + n);
          var $target = $link.filter('[data-process="' + n + '"]');
          $target.trigger('click');

          var href = $target.attr('href');
          console.log(/^#?$/.test(href));
          if (/^#?$/.test(href) == false) {
            window.open(href, '_self');
          }
        });
      }
    }
  },
  url: {
    print: function print() {
      var url = window.location.href;
      //check if ther is #print inside url then print the page
      if (url.search(/#print$/) > 0) {
        window.print();
      }
    }
  },
  calendar: {
    loading: function loading(switcher) {
      console.log(switcher);
      switch (switcher) {
        case true:
          $('#calendar').addClass('loading');
          $('body,html').animate({
            'scrollTop': $('#calendar .uk-spinner').offset().top - μ.window.height() / 2
          }, 250);
          break;
        case false:
          $('#calendar').removeClass('loading');
          break;
      }
    }
  },
  dropdownFilter: {
    drop: function drop() {
      $(document).on('click', function (e) {
        var target = $(e.target).parents().attr('id');
        if (target == 'dropdownMenuButton') {
          $('.dropdown-menu').show();
        } else {
          $('.dropdown-menu').hide();
        }
      });

      var arrSelected = [];

      $('.dropdown-menu input').on('change', function (e) {
        var target = e.target.parentElement.textContent.trim();
        var index = arrSelected.indexOf(target);

        if ($('.default-check').is(':checked')) {
          arrSelected = [];
        } else if (index > 0) {
          arrSelected.splice(index, 1);
        } else {
          arrSelected = [target].concat(_toConsumableArray(arrSelected));
        }

        if (arrSelected.length == 1) {
          $('#dropdownMenuButton').children('.ui-selectmenu-text').text(arrSelected[0]);
        } else if (arrSelected.length > 1) {
          $('#dropdownMenuButton').children('.ui-selectmenu-text').text('multi');
        } else {
          $('#dropdownMenuButton').children('.ui-selectmenu-text').text('Défaut');
        }
      });
    }
  },
  modal: {
    product: function product() {
      var $modal = $('#modal-product');
      var $collection = $('#layout .productlist__item-collection');
      var $slider = $modal.find('.slider');
      if ($modal.length) {
        $modal.on('show shown', function () {
          if ($slider.hasClass('slick-initialized')) {
            $slider.slick('setPosition');
          } else {
            ui.slick.product__slider();
            ui.slick.product__nav();
          }
          if ($collection.length) {
            $collection.removeClass('loading');
          }
        });
      }
    }
  }
};

$.fn.loading = function (param) {

  return this.each(function () {
    var $this = $(this);
    //check if the element is span or button and has a btn class
    if (['a', 'button'].indexOf($this[0].tagName) && $this.hasClass('btn')) {
      switch (param) {
        case false:
          $this.find('[uk-spinner]').remove();
          break;
        default:
          $this.append('<div uk-spinner="ratio:0.5"></div>');
      }
    } else {
      console.warn('The element is not a button');
    }
  });
};

// // https://developers.google.com/youtube/iframe_api_reference
//
// // global variable for the player
// var player;
//
// // this function gets called when API is ready to use
// function onYouTubePlayerAPIReady() {
//   // create the global player from the specific iframe (#video)
//   player = new YT.Player('video', {
//     events: {
//       // call this function when player is ready to use
//       'onReady': onPlayerReady,
//       'onStateChange': onPlayerChange
//     }
//   });
// }
//
// function onPlayerReady(event) {
//
//   var status = player.getPlayerState();
//   // console.log(status);
//
//   if (status == 1 || status == 3) {
//     var pauseButton = document.getElementById('play-button');
//     pauseButton.addEventListener('click', function () {
//       player.pauseVideo();
//
//     });
//   } else {
//     // bind events
//     var playButton = document.getElementById('play-button');
//     playButton.addEventListener('click', function () {
//       player.playVideo();
//
//     });
//   }
// }
//
// function onPlayerChange(event) {
//
//   var status = player.getPlayerState();
//
//   if (status == 1 || status == 3) {
//     var pauseButton = document.getElementById('play-button');
//     pauseButton.addEventListener('click', function () {
//       player.pauseVideo();
//
//     });
//   } else {
//     var playButton = document.getElementById('play-button');
//     playButton.addEventListener('click', function () {
//       player.playVideo();
//
//     });
//   }
// }
// var tag = document.createElement('script');
// tag.src = '//www.youtube.com/player_api';
// var firstScriptTag = document.getElementsByTagName('script')[0];
// firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
//# sourceMappingURL=main.js.map
