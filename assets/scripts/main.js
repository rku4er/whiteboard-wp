/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {


        // helper functions
        Array.prototype.max = function () {
            return Math.max.apply(Math, this);
        };

        Array.prototype.min = function () {
            return Math.min.apply(Math, this);
        };

        // Debounced resize
        function debounce(func, wait, immediate) {
          var timeout;
          return function() {
            var context = this, args = arguments;
            var later = function() {
              timeout = null;
              if (!immediate){
                func.apply(context, args);
              }
            };
            if (immediate && !timeout){
              func.apply(context, args);
            }
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
          };
        }

        // Gravity Forms custom input classes
        function gravity_forms_custom_input_classes(form) {
            $(form).find('.gfield_checkbox > li').addClass('checkbox-wrapper');
            $(form).find('.gfield_radio > li').addClass('radio-wrapper');
        }

        // Material inputs
        function material_input_html(form) {
            $(form).find('.checkbox-wrapper input[type=checkbox]').after("<span class=checkbox-material><span class=check></span></span>");
            $(form).find('.radio-wrapper input[type=radio]').after("<span class=radio-material><span class=circle></span><span class=check></span></span>");
            $(form).find('.togglebutton input[type=checkbox]').after("<span class=toggle></span>");
        }

        // apply material inputs to static gravity forms
        gravity_forms_custom_input_classes($('.gform_wrapper'));
        material_input_html($('.gform_wrapper'));

        // apply material inputs to gravity forms on ajax reload
        $(document).bind('gform_post_render', function(event, form_id, cur_page){
            gravity_forms_custom_input_classes($('#gform_' + form_id));
            material_input_html($('#gform_' + form_id));
        });

        // wait until users finishes resizing the browser
        var debouncedResize = debounce(function() {

            // fixed navbar offsets

            $('.navbar-fixed-top').each(function(){
                var $self = $(this);
                var adminbar = $('#wpadminbar');

                // wrapper
                $('.wrap').css('margin-top', $self.height());

                // adminbar offset
                if(adminbar.length){
                    $self.css('margin-top', adminbar.height());
                }
            });

            $('.navbar-fixed-bottom').each(function(){
                var $self = $(this);

                // footer offset
                $('.content-info').css('padding-bottom', $self.height());
            });

            // carousel min-height
            $('.carousel-inner').each(function() {
                var $slides = $(this).children(),
                    $maxHeight = [];

                $slides.each(function(i){
                    if ($(this).height() > $maxHeight) {
                        $maxHeight[i] = $(this).height();
                    }
                });

                $(this).css('min-height', $maxHeight.max());
            });

            // reset article list height
            $('.article-list').height('auto');

        }, 100);


        //window load callback
        $(window).load(function(){
            // needed by preloaded
            $('body').addClass('loaded');

            // when the window resizes, redraw the grid
            $(window).resize(debouncedResize).trigger('resize');

        });

        // Disable 300ms click delay on mobile
        FastClick.attach(document.body);

        // Responsive video
        $('.main').fitVids();

        // carousel
        $('.jcarousel').jcarousel({
            animation: {
                duration: 500,
                easing:   'ease-out'
            },
            transitions: Modernizr.csstransitions ? {
                transforms:   Modernizr.csstransforms,
                transforms3d: Modernizr.csstransforms3d,
                easing:       'ease-out'
            } : false,
            wrap: 'circular'
        }).on('jcarousel:reload jcarousel:create', function () {
            var carousel = $(this),
                width = carousel.innerWidth();

            if (width >= 722) {
                width = width / 3;
            } else if (width >= 528) {
                width = width / 2;
            }

            carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
        });

        // carousel controls
        $('.jcarousel-control-next').jcarouselControl({
            target: '+=1'
        });
        $('.jcarousel-control-prev').jcarouselControl({
            target: '-=1'
        });

       // Video lightbox
        $('.video-lightbox').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-with-zoom'
        });

        // Image gallery lightbox
        $('.gallery').each(function(){
            var $thumb = $(this).find('a.gallery-thumb');
            $thumb.magnificPopup({
                type:       'image',
                enableEscapeKey: true,
                gallery:    {
                    enabled:    false,
                    tPrev:      '',
                    tNext:      '',
                    tCounter: '%curr% | %total%'
                },
                image: {
                    verticalFit: true,
                    markup: '<div class="mfp-figure gallery-lightbox">'+
                              '<div class="mfp-close"></div>'+
                              '<a class="mfp-pin-it" href="http://pinterest.com/pin/create/bookmarklet/' +
                                '?media='+ window.location.protocol + '//' + window.location.host + $thumb.data('media') +
                                '&url='+ $thumb.data('url') +
                                '&is_video=false' +
                                '&description='+ $thumb.data('description') +
                                '"><span class="fa fa-pinterest"></span></a>'+
                              '<div class="mfp-img"></div>'+
                              '<div class="mfp-bottom-bar">'+
                                '<div class="mfp-title"></div>'+
                                '<div class="mfp-counter"></div>'+
                              '</div>'+
                            '</div>',
                    titleSrc: function(item) {
                        return item.el[0].nextSibling.innerHTML;
                    }
                },
                mainClass: 'mfp-with-zoom',
                zoom: {
                  enabled: true,
                  duration: 300,
                  easing: 'ease-in-out',
                }
            });
        });


        // ripples
        $([
          ".navbar-toggle",
          ".btn",
          ".nav-link",
          ".withripple"
        ].join(",")).ripples();

        // Handle hash anchors
        $('.nav-link').on('click', function(e){
            var target = $($(this).attr('href'));

            if(target.length){
                var offset = Math.round(target.offset().top - $('.navbar-fixed-top').outerHeight() - $('.navbar-sticky-top').outerHeight() - $('#wpadminbar').outerHeight());
                $('html,body').animate({ scrollTop: offset }, 1000, 'easeInOutCubic');
            }

            e.preventDefault();

        });

        // Intro Video
        $('.video-wrapper a').click(function(e){

          var $src = $(this).attr('href'),
              $query_opened = $src.match(/^[^?]+\?/),
              $separator = $query_opened ? '&' : '?',
              $video = '<iframe src="'+ $src + $separator + 'autoplay=1" frameborder="0" allowfullscreen></iframe>';

          $(this).replaceWith($video);

          e.preventDefault();
        });

        // Blog
        $('.section.blog').each(function(){

            var $list   = $(this).find('.article-list'),
                $button = $(this).find('button'),
                $data   = {
                    'action' : 'get_posts',
                    'width'  : $(window).width(),
                    'offset' : $list.children().length
                },
                get_posts = function(data) {
                    jQuery.ajax({
                        type: "POST",
                        url: getposts.ajax_url,
                        dataType: "json",
                        data: data,
                        success: function(response) {
                            $resp = $(response.content);
                            $resp.css({
                                'visibility' : 'hidden',
                                'opacity'    : '0',
                                'position'   : 'absolute',
                                'left'       : 0
                            });
                            $list_H = $list.height();
                            $list.css('height', $list_H);
                            $list.append($resp);
                            $height = $resp.height() + $list_H;
                            $button.removeClass('loading');
                            $list.animate({'height' : $height}, 600, 'easeInOutQuad', function(){
                              $resp.each(function(i){
                                var $target = $(this),
                                    $timeout = 100*i;
                                setTimeout(function(){
                                  $target.css({
                                    'visibility' : 'visible',
                                    'opacity'    : '1',
                                    'position'   : 'static'
                                  });
                                }, $timeout);
                              });
                            });

                            if(response.status === 'full') {
                                $button.hide();
                            }

                        },
                        error: function(response){
                            console.log(response);
                        }
                    });
                };

            get_posts($data);

            $button.click(function(){
                $(this).addClass('loading');
                $('.article-list').height('auto');
                $data.offset = $list.children().length;
                get_posts($data);
            });

        });

      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
