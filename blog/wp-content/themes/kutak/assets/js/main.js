( function( $ ) {
    'use strict';

    var $window = $( window ),
        window_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 ),
        scrollTop = window.pageYOffset || document.documentElement.scrollTop,
        lazy_elements = [],
        infinite_scroll_elements = [],
        is_loading_more_posts = false,

        rAF = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame || function( callback ) { return window.setTimeout( callback, 1000 / 60 ); },
        user_agent = navigator.userAgent || navigator.vendor || window.opera,
        
        is_mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test( user_agent ) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test( user_agent.substr( 0, 4 ) ),
        is_object_fit_supported = ( 'objectFit' in document.documentElement.style ),
        is_ie = /Trident\/|MSIE /.test( user_agent ),

        on_resize = function( callback, timeout ) { onresize = function() { clearTimeout( timeout ); timeout = setTimeout( callback, 10 ) }; return callback };

    if ( is_mobile ) { $( 'html' ).addClass( 'is-mobile' ); }
    if ( is_ie ) { $( 'html' ).addClass( 'is-ie' ); }

    $( 'html' ).addClass( 'is-using-mouse' );

    // Add class to html when the mouse is being used
    $( document ).on( 'click', function() {
        $( 'html' ).addClass( 'is-using-mouse' );
    });

    // Remove class from html when the keyboard is being used
    $( document ).on( 'keydown', function() {
        $( 'html' ).removeClass( 'is-using-mouse' );
    });

    setTimeout( function() {
        $( 'html' ).addClass( 'is-animation-ready' );
    }, 300 );

    var $adebouncedresize = $.event.special.adebouncedresize = {
        timeout: null, 
        setup: function() {
            $( this ).on( 'resize', $adebouncedresize.handler );
        },
        teardown: function() {
            $( this ).off( 'resize', $adebouncedresize.handler );
        },
        handler: function( event, execAsap ) {
            // Save the context
            var context = this,
                args = arguments,
                dispatch = function() {
                    // set correct event type
                    event.type = 'adebouncedresize';
                    $.event.dispatch.apply( context, args );
                };

            if ( $adebouncedresize.timeout ) {
                clearTimeout( $adebouncedresize.timeout );
            }

            execAsap ?
                dispatch() :
                $adebouncedresize.timeout = setTimeout( dispatch, 50 );
        },
    };

    function resize_window() {
        var temp_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );
        if ( is_mobile && window_width == temp_width ) {
            // do nothing, mobile browsers address bar hides and fires resize event
        } else {
            window_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );
            initLazyLoadMedia();
            initInfiniteScroll();
            // image_vw();
        }
    }

    function image_vw() {
        $( '.window-width, .img-vw' ).remove();
        $( 'body' ).append( '<span class="window-width">'+ window_width.toFixed(0) + '</span>' );

        $( '.post-media' ).each( function( index, element ) {
            var $el = $( element );
            var width = $el.find( '.image-wrapper' ).outerWidth( true );
            var vw = width / window_width * 100;
            $el.find( '.image-wrapper' ).append( '<span class="img-vw">'+ width.toFixed(0) + ' - ' + vw.toFixed(2) +'</span>' );
        });
    }
    // image_vw();

    $window.on( 'adebouncedresize orientationchange', function() {
        rAF( resize_window );
    } );

    function stickyHeader() {
        if ( scrollTop > 5 ) {
            $( '#masthead' ).addClass( 'is-sticky' );
        } else {
            $( '#masthead' ).removeClass( 'is-sticky' );
        }
    }

    $( document ).on( 'click', '.menu-trigger', function( event ) {
        event.preventDefault();

        var $this = $( this ),
            $html = $( 'html' ),
            $header = $( '.site-header' ),
            $search_trigger = $( '.search-trigger' );

        $( '.site-actions-bg' ).css( 'height', $( '.site-navigation-container' ).outerHeight() + 85 );

        if ( $this.hasClass( 'is-active' ) ) {

            $header.removeClass( 'is-menu-ready' );
            $html.removeClass( 'is-scroll-disabled' );

            setTimeout( function() {
                $this.removeClass( 'is-active' );
                $header.removeClass( 'is-menu-active' );
                $( '.site-actions-bg' ).css( 'height', $( '.site-search' ).outerHeight() + 65 );
            }, 50 );

        } else {

            $html.addClass( 'is-scroll-disabled' );
            $this.addClass( 'is-active' );
            $header.addClass( 'is-menu-active' ).removeClass( 'is-search-active is-search-ready' );
            $search_trigger.removeClass( 'is-active' );

            setTimeout( function() {
                $header.addClass( 'is-menu-ready' );
            }, 250 );
        }

    } );

    $( document ).on( 'click', '.search-trigger', function( event ) {
        event.preventDefault();

        var $this = $( this ),
            $html = $( 'html' ),
            $header = $( '.site-header' ),
            $menu_trigger = $( '.menu-trigger' );

        $( '.site-actions-bg' ).css( 'height', $( '.site-search' ).outerHeight() + 65 );

        if ( $this.hasClass( 'is-active' ) ) {

            $header.removeClass( 'is-search-ready' );
            $html.removeClass( 'is-scroll-disabled' );

            setTimeout( function() {
                $this.removeClass( 'is-active' );
                $header.removeClass( 'is-search-active' );
                $( '.site-actions-bg' ).css( 'height', $( '.site-search' ).outerHeight() + 65 );
            }, 50 );

        } else {

            $html.addClass( 'is-scroll-disabled' );
            $this.addClass( 'is-active' );
            $header.addClass( 'is-search-active' ).removeClass( 'is-menu-active is-menu-ready' );
            $menu_trigger.removeClass( 'is-active' );

            setTimeout( function() {
                $header.addClass( 'is-search-ready' );
                $( '.site-search .search-form .search-field' ).focus();
            }, 150 );
        }

    } );

    $( document ).on( 'click', '.site-navigation li.menu-item-has-children > a', function( event ) { 

        if ( window_width < 980 ) {
            event.preventDefault();

            var $this = $( this ),
                $list = $this.closest( 'li' ),
                $dropdown = $list.find( 'ul:first' ),
                $parentUl = $list.closest( 'ul' ),
                subLevel = $list.parents( 'ul' ).length,
                dropHeight = $dropdown.prop( 'scrollHeight' ),
                parentUlHeight = $parentUl.prop( 'scrollHeight' );

            if ( $list.hasClass( 'is-active' ) ) {

                if ( 1 < subLevel ) {
                    $parentUl.css({ 'max-height': parentUlHeight - dropHeight });
                }

                $list.removeClass( 'is-active' );
                $dropdown.css({ 'max-height': 0 });

            } else {

                if ( 1 < subLevel ) {
                    $parentUl.css({ 'max-height': parentUlHeight + dropHeight });
                }

                $list.addClass( 'is-active' );
                $dropdown.css({ 'max-height': dropHeight });
            }

        }

    } );

    $( document ).on( 'click', '.site-actions-backdrop', function( event ) {
        event.preventDefault();

        var $header = $( '.site-header' ),
            $html = $( 'html' ),
            $menu_trigger = $( '.menu-trigger' ),
            $search_trigger = $( '.search-trigger' );

        $html.removeClass( 'is-scroll-disabled' );
        $header.removeClass( 'is-search-ready is-menu-ready' );

        setTimeout( function() {
            $header.removeClass( 'is-menu-active is-search-active' );
            $menu_trigger.removeClass( 'is-active' );
            $search_trigger.removeClass( 'is-active' );
            $( '.site-actions-bg' ).css( 'height', $( '.site-search' ).outerHeight() + 65 );
        }, 50 );

    } );

    $( document ).on( 'click', '.featured-tab', function( event ) {
        event.preventDefault();

        var $this = $( this ),
            $tabs = $( '.featured-tab' ),
            $panels = $( '.featured-panel' ),
            $panel = $( $this.data( 'id' ) );

        if ( !$this.hasClass( 'is-active' ) ) {
            $tabs.removeClass( 'is-active' );
            $this.addClass( 'is-active' );

            $panels.removeClass( 'is-active' );
            $panel.addClass( 'is-active' )
        }
    } );

    function waitForImages( element, callback ) {

        var matchUrl = /url\(\s*(['"]?)(.*?)\1\s*\)/g;
        var image = false;

        if ( element.hasAttribute( 'srcset' ) ) {
            image = {
                src: element.getAttribute( 'src' ),
                srcset: element.getAttribute( 'srcset' ),
                sizes: element.getAttribute( 'sizes' ),
            };
        } else if ( element.hasAttribute( 'src' ) ) {
            image = {
                src: element.getAttribute( 'src' ),
            };
        } else if ( '' !== element.style.backgroundImage ) {
            var match = matchUrl.exec( element.style.backgroundImage );
            if ( match ) {
                image = {
                    src: match[2],
                };
            }
        }

        if ( image ) {
            var img = new Image();
            img.onload = function() {
                callback( element );
            }
            if ( image.srcset ) {
                img.sizes = image.sizes;
                img.srcset = image.srcset;
            }
            img.src = image.src;
        }
    }

    // let's fix image object fit for galleries on browsers that don't support it
    function fix_ie_image_object_fit() {
        if ( ! is_object_fit_supported ) {
            $( '.wp-block-gallery.is-cropped' ).each( function() {
                var $imgs = $( this ).find( 'img' );
                $imgs.each( function() {
                    var $img = $( this );
                    $img.parents( 'figure' ).addClass( 'is-js-cropped' ).css( 'background-image', 'url(' + $img.attr( 'src' ) + ')' );
                });
            });
            $( '.post-media-cropped' ).each( function() {
                var $this = $( this ),
                    $wrapper = $this.find( '.image-wrapper' ),
                    $img = $this.find( 'img' );
                    $wrapper.addClass( 'is-js-cropped' ).css( 'background-image', 'url(' + $img.attr( 'src' ) + ')' );
            });
        }
    }

    if ( '1' !== kutak_vars.is_lazy_load ) {
        fix_ie_image_object_fit();
    }

    function initLazyLoadMedia() {

        var lazys = document.querySelectorAll( '.lazy-load-img:not(.is-loaded), .lazy-load-bg-img:not(.is-loaded), .lazy-load-iframe .iframe-placeholder:not(.is-loaded)' ),
            windowHeight = window.innerHeight,
            belowFold = windowHeight * 0.2, // 20% below fold
            offset = windowHeight + belowFold;

        // empty the set
        lazy_elements = [];

        for ( var i = 0; i < lazys.length; i++ ) {
            var rect = lazys[i].getBoundingClientRect(),
                imgOffset = rect.top + scrollTop - offset,
                imgOffset = Math.max( 0, imgOffset );

            lazy_elements.push({ 
                'offset': imgOffset.toFixed(),
                'element': lazys[i],
                'type': lazys[i].tagName
            });
        };
    }

    // let's wait a bit for the above the fold script to collect items
    setTimeout( function() {
        initLazyLoadMedia();
    }, 50 );

    function lazy_load_media() {

        for ( var i = 0; i < lazy_elements.length; i++ ) {
            var lazy = lazy_elements[i];

            if ( lazy.offset <= scrollTop ) {

                if ( 'IMG' === lazy.type ) {

                    var image = lazy.element,
                        src = image.getAttribute( 'data-src' );

                    image.classList.add( 'is-loaded' );

                    if ( image.hasAttribute( 'data-srcset' ) ) {
                        var sizes = image.getAttribute( 'data-sizes' ),
                        srcset = image.getAttribute( 'data-srcset' );
                        image.setAttribute( 'sizes', sizes );
                        image.setAttribute( 'srcset', srcset );
                    }

                    image.setAttribute( 'src', src );
                    lazy_elements.splice( i, 1 );

                    waitForImages( image, function( element ) {
                        element.classList.add( 'is-ready' );

                        if ( ! is_object_fit_supported ) {
                            var $el = $( element ),
                                el_src = element.getAttribute( 'src' ),
                                $gallery_cropped = $el.parents( '.wp-block-gallery.is-cropped' ),
                                $post_media_cropped = $el.parents( '.post-media-cropped' );

                            if ( $gallery_cropped.length ) {
                                $el.parents( 'figure' ).addClass( 'is-js-cropped' ).css( 'background-image', 'url(' + el_src + ')' );
                            }

                            if ( $post_media_cropped.length ) {
                                $post_media_cropped.find( '.image-wrapper' ).addClass( 'is-js-cropped' ).css( 'background-image', 'url(' + el_src + ')' );
                            }
                        }
                    } );

                } else if ( 'I' === lazy.type ) {

                    var placeholder = lazy.element,
                        wrapper = placeholder.parentNode,
                        src = placeholder.getAttribute( 'data-src' ),
                        iframe = document.createElement( 'iframe' );

                    wrapper.insertBefore( iframe, placeholder );
                    wrapper.removeChild( placeholder );
                    iframe.classList.add( 'is-loaded' );
                    iframe.setAttribute( 'src', src );
                    iframe.setAttribute( 'allowfullscreen', '' );
                    lazy_elements.splice( i, 1 );

                } else {

                    var image = lazy.element,
                        src = image.getAttribute( 'data-src' );

                    image.classList.add( 'is-loaded' );
                    image.style.backgroundImage = 'url(' + src + ')';

                    waitForImages( image, function( element ) {
                        element.classList.add( 'is-ready' );
                    } );
                    lazy_elements.splice( i, 1 );
                }
            }
        };
    }

    function init_photoswipe( selector, type ) {

        $( selector ).on( 'click' , 'a', function( event ) {

            var $this = $( this ),
                link_url = $this.attr( 'href' );

            // if link to image
            if ( /\.(?:jpg|jpeg|gif|png)$/i.test( link_url ) ) {
                event.preventDefault();

                if ( 'gallery' === type ) {
                    var $gallery = $this.parents( selector ),
                        $figures = $gallery.find( 'figure' ),
                        $list = $this.parents( 'li' ),
                        index = $list.index(),
                        items = get_items( $figures );

                    open_photoswipe( index, items );

                } else if ( 'gallery-grid' === type ) {
                    var $gallery = $this.parents( selector ),
                        $figures = $gallery.find( 'figure' ),
                        $list = $this.parents( 'figure' ),
                        index = $list.index(),
                        items = get_items( $figures );

                    open_photoswipe( index, items );

                } else {
                    var $figures = $this.parent(),
                        items = get_items( $figures );

                    open_photoswipe( 0, items );
                }
            }
        });

        var open_photoswipe = function( index, items ) {

            var pswp = $( '.pswp' )[ 0 ];
            var options = {
                index: parseInt( index, 10 ),
                shareEl: false,
                fullscreenEl: false,
                bgOpacity: 0.95,
                showHideOpacity: true,
                history: false,
                loadingIndicatorDelay: 10,
                timeToIdle: 0,
                timeToIdleOutside: 0,
                // tapToClose: true,
                clickToCloseNonZoomable: true,
                getThumbBoundsFn: function( index ) {
                    var img = items[ index ].el[ 0 ].getElementsByTagName( 'img' )[ 0 ],
                        pageYScroll = $( window ).scrollTop(),
                        rect = img.getBoundingClientRect();

                    return { x: rect.left, y: rect.top + pageYScroll, w: rect.width };
                }
            };

            // exit if index not found
            if ( isNaN( options.index ) ) {
                return;
            }

            var ps = new PhotoSwipe( pswp, PhotoSwipeUI_Default, items, options );
            ps.init();
        }

        var get_items = function( figures ) {
            var items = [];

            figures.each( function( index, element ) {
                var $figure = $( element ),
                    $link = $figure.find( 'a:first-child' ),
                    $img = $figure.find( 'img' ),
                    $caption = $figure.find( 'figcaption' ),
                    size = $img.attr( 'data-full-size' ).split( 'x' ),
                    src = $img.data( 'full' ) ? $img.data( 'full' ) : $link.attr( 'href' );

                var item = {
                    el: $figure,
                    src: src,
                    msrc: $img.attr( 'src' ),
                    srcset: $img.attr( 'srcset' ),
                    w: parseInt( size[ 0 ], 10 ),
                    h: parseInt( size[ 1 ], 10 ),
                };

                if ( $caption.length ) {
                    item.title = $caption.html();
                }

                items.push( item );
            } );

            return items;
        }
    }

    init_photoswipe( '.wp-block-image', 'single' );
    init_photoswipe( '.wp-block-gallery', 'gallery' );
    init_photoswipe( '.gallery-grid', 'gallery-grid' );

    function ajax_load_more_posts( element, is_infinite_scroll ) {
        var $loader = $( element ),
            $nav = $loader.parent(),
            $container = $loader.parents( '.posts-container' ).find( '.row' ),
            found_posts = parseInt( $loader.data( 'found-posts' ), 10 ),
            post_count = parseInt( $loader.data( 'post-count' ), 10 ),
            query = $loader.data( 'query' ),
            // options = $loader.data( 'options' ),
            posts_per_page = parseInt( query.posts_per_page, 10 ),
            offset = parseInt( query.offset, 10 );

        if ( post_count >= found_posts ) {
            return false;
        }

        if ( is_loading_more_posts ) {
            return false;
        }

        $loader.addClass( 'is-ready is-loading' );
        is_loading_more_posts = true;

        $.ajax( {
            type: 'POST',
            url: kutak_vars.ajax_url.toString().replace( '%%action%%', 'load-more-posts' ),
            data: {
                query: query,
                // options: options
            },
            success: function( response ) {
                // console.log( response );

                if ( response.status === 'success' ) {

                    var $new_items = $( response.html ),
                        new_post_count = parseInt( response.post_count, 10 ) + post_count,
                        new_offset = offset + posts_per_page;

                    $loader.data( 'post-count', new_post_count );
                    $loader.data( 'query', $.extend( query, { offset: new_offset } ) );

                    $loader.removeClass( 'is-loading' );
                    $container.append( $new_items );

                    setTimeout( function() {
                        $loader.removeClass( 'is-ready' );
                    }, 300 );

                    initLazyLoadMedia();

                    // Are there more items to load?
                    if ( new_post_count < found_posts ) {
                        if ( is_infinite_scroll ) {
                            initInfiniteScroll();
                        }
                    } else {
                        // $nav.slideUp( 200 );
                        $nav.remove();
                    }

                    is_loading_more_posts = false;

                } else if ( response.status === 'empty' ) {
                    // $nav.slideUp( 200 );
                    $nav.remove();
                } else if ( response.status === 'error' ) {
                    $loader.addClass( 'is-error' ).removeClass( 'is-ready is-loading' );
                    $loader.find( '.loader-error-text' ).attr( 'aria-hidden', 'false' );
                }
            },
            error: function() {
                $loader.addClass( 'is-error' ).removeClass( 'is-ready is-loading' );
                $loader.find( '.loader-error-text' ).attr( 'aria-hidden', 'false' );
            }
        });
    }

    function initInfiniteScroll() {        

        var iscrolls = document.querySelectorAll( '.pagination-infinite-scroll' ),
            windowHeight = window.innerHeight,
            belowFold = windowHeight * 0.35, // 35% below fold
            offset = windowHeight + belowFold;

        // empty the set
        infinite_scroll_elements = [];

        for ( var i = 0; i < iscrolls.length; i++ ) {
            var rect = iscrolls[i].getBoundingClientRect(),
                offset = rect.top + scrollTop - offset,
                offset = Math.max( 0, offset );

            infinite_scroll_elements.push({ 
                'offset': offset.toFixed(),
                'element': iscrolls[i],
            });
        };
    }

    initInfiniteScroll();

    function infinite_scroll() {

        for ( var i = 0; i < infinite_scroll_elements.length; i++ ) {
            var iscroll = infinite_scroll_elements[i];

            if ( iscroll.offset <= scrollTop ) {
                ajax_load_more_posts( iscroll.element, true );
                infinite_scroll_elements.splice( i, 1 );
            }
        }
    }

    $( '.pagination-load-more' ).on( 'click', function( event ) {
        event.preventDefault();
        ajax_load_more_posts( this, false );
    });

    function inifinityLoop() {
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        stickyHeader();
        lazy_load_media();
        infinite_scroll();
        rAF( inifinityLoop );
    }

    rAF( inifinityLoop );

    window.addEventListener( 'load', function() {
        // le't get the new values if something has pushed the content down
        initLazyLoadMedia();
        initInfiniteScroll();
    });

} )( jQuery );