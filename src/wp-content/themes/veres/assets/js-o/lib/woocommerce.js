(function ($) {
    "use strict";

    var $window = $(window),
        $document = $(document),
        $dochtml = $('html'),
        $htmlbody = $('html,body'),
        $body = $('body');

    // Initialize global variable

    var LaStudioWooCommerce = function (){
        // Bind functions to this.
        this.General = this.General.bind(this);
        this.QuickView = this.QuickView.bind(this);
        this.Wishlist = this.Wishlist.bind(this);
        this.Compare = this.Compare.bind(this);
        this.AjaxFilter = this.AjaxFilter.bind(this);
        this.AutoInit = this.AutoInit.bind(this);
    }

    /**
     *
     * @param options
     * @returns {LaStudio.global.AjaxHandler}
     * @constructor
     */
    const AjaxManager = ( action, options ) => {
        let opts = $.extend({}, options);
        opts.url = la_theme_config.ajax_url;
        opts.handlerSettings = {
            nonce: la_theme_config.ajaxGlobal.nonce,
            data_type: 'json',
            type: 'POST',
            action: action
        }
        return new LaStudio.global.AjaxHandler(opts)
    }

    LaStudioWooCommerce.prototype.AutoInit = function (){
        this.General();
        this.QuickView();
        this.Wishlist();
        this.Compare();
        this.AjaxFilter();
    }

    function wooTabs( $scope ){
        var $tabs = $scope.find('.wc-tabs-wrapper').first();
        if ($tabs.length) {
            $('.wc-tab-title a', $tabs).off('click');
            $('.wc-tabs a', $tabs).off('click');

            $tabs.wrapInner('<div class="lakit-wc-tabs--content"></div>');
            $tabs.find('.wc-tabs').wrapAll('<div class="lakit-wc-tabs--controls"></div>');
            $tabs.find('.lakit-wc-tabs--controls').prependTo($tabs);
            $tabs.find('.wc-tab').wrapInner('<div class="tab-content"></div>');
            $tabs.find('.wc-tab').each(function () {
                var _html = $('#' + $(this).attr('aria-labelledby')).html();
                $(this).prepend('<div class="wc-tab-title">' + _html + '</div>');
            });
            $('.wc-tab-title a', $tabs).wrapInner('<span></span>');
            $('.wc-tab-title a', $tabs).on('click', function (e) {
                e.preventDefault();
                $tabs.find('.wc-tabs').find('li[aria-controls="' + $(this).attr('href').replace('#', '') + '"]').toggleClass('active').siblings().removeClass('active');
                $(this).closest('.wc-tab').toggleClass('active').siblings().removeClass('active');
            });
            $('.wc-tabs li a', $tabs).on('click', function (e) {
                var $wrapper = $(this).closest('.wc-tabs-wrapper, .woocommerce-tabs');
                $wrapper.find($(this).attr('href')).addClass('active').siblings().removeClass('active');
            });
            $('.wc-tabs li', $tabs).removeClass('active');
            $('.wc-tab-title a', $tabs).first().trigger('click');
        }
    }

    LaStudioWooCommerce.prototype.General = function (){

        $document.trigger('reinit_la_swatches');

        $(document).on('click touchend', '.lawl--continue', function (e){
            e.preventDefault();
            $.featherlight.close();
        });

        $('#customer_login .input-text').each(function () {
            if($(this).closest('.form-row').find('label').length){
                $(this).attr('placeholder', $(this).closest('.form-row').find('label').text());
            }
        });

        $(document).on('lastudiokit/woocommerce/single/init_product_slider', function (e, slider){
            slider.controlNav.eq(slider.animatingTo).closest('li').get(0).scrollIntoView({ inline: "center", block: "nearest", behavior: "smooth"});
            slider.viewport.closest('.woocommerce-product-gallery').css('--singleproduct-thumbs-height', slider.viewport.height() + 'px');
        });

        $document.on('click','.product_item .la-swatch-control .swatch-wrapper', function(e){
            e.preventDefault();
            var $swatch_control = $(this),
                $image = $swatch_control.closest('.product_item').find('.p_img-first img').first(),
                $btn_cart = $swatch_control.closest('.product_item').find('.la-addcart');

            if($swatch_control.hasClass('selected')) return;
            $swatch_control.addClass('selected').siblings().removeClass('selected');
            if(!$image.hasClass('--has-changed')){
                $image.attr('data-o-src', $image.attr('src')).attr('data-o-sizes', $image.attr('sizes')).attr('data-o-srcset', $image.attr('srcset')).addClass('--has-changed');
            }
            $image.attr('src', ($swatch_control.attr('data-thumb') ? $swatch_control.attr('data-thumb') : $image.attr('data-o-src'))).removeAttr('sizes srcset');
            if($btn_cart.length > 0){
                var _href = $btn_cart.attr('href');
                _href = LaStudio.global.addQueryArg(_href, 'attribute_' + $swatch_control.attr('data-attribute'), $swatch_control.attr('data-value'));
                $btn_cart.attr('href', _href);
            }
        })

        /**
         * Lazyload image for cart widget
         */
        var cart_widget_timeout = null;
        $(document.body).on('wc_fragments_refreshed updated_wc_div wc_fragments_loaded', function(e){
            clearTimeout( cart_widget_timeout );
            cart_widget_timeout = setTimeout( function(){
                LaStudio.global.eventManager.publish('LaStudio:Component:LazyLoadImage', [$('.widget_shopping_cart_content')]);
            }, 100);
        });
        /**
         * Cart Plus & Minus action
         */
        $document.on('click', '.quantity .qty-minus', function(e){
            e.preventDefault();
            var $qty = $(this).siblings('.qty'),
                val = parseInt($qty.val()),
                _min = !!$qty.attr('min') ? parseInt($qty.attr('min') || 1) : 1;
            $qty.val( val > _min ? val-1 : _min).trigger('change');
        })
        $document.on('click', '.quantity .qty-plus', function(e){
            e.preventDefault();
            var $qty = $(this).siblings('.qty'),
                val = parseInt($qty.val());
            $qty.val( val > 0 ? val+1 : 1 ).trigger('change');
        })

        /**
         * View mode toggle
         */
        $document
            .on('click','.wc-view-item a',function(){
                var _this = $(this),
                    _col = _this.data('col'),
                    $parentWrap = _this.closest('.woocommerce');
                if(!_this.hasClass('active')){
                    $('.wc-view-item a').removeClass('active');
                    _this.addClass('active');
                    _this.closest('.wc-view-item').find('>button>span').html(_this.text());
                    var $ul_products = $parentWrap.find('[data-widget_current_query] ul.ul_products');

                    $ul_products.each(function () {
                        $(this).removeClass('products-list').addClass('products-grid');
                    });

                    $ul_products.find('>li').each(function (){
                        var _classname = $(this).attr('class').replace(/(\scol-desk-\d)/g, ' col-desk-' + _col).replace(/(\scol-lap-\d)/g, ' col-lap-' + _col);
                        $(this).attr('class', _classname);
                    })

                    if( $parentWrap.closest('.elementor-widget-wc-archive-products').length ){
                        var _classname = $parentWrap.attr('class').replace(/(\scolumns-\d)/g, ' columns-' + _col);
                        $parentWrap.attr('class', _classname);
                    }
                    Cookies.set('veres_wc_product_per_row', _col, { expires: 2 });
                }
            })
            .on('click','.wc-view-toggle button',function(){
                var _this = $(this),
                    _mode = _this.data('view_mode'),
                    $parentWrap = _this.closest('.woocommerce');
                if(!_this.hasClass('active')){
                    $('.wc-view-toggle button').removeClass('active');
                    _this.addClass('active');

                    var $ul_products = $parentWrap.find('ul.products[data-grid_layout]'),
                        _old_grid = $ul_products.attr('data-grid_layout');
                    if(_mode == 'grid'){
                        $ul_products.removeClass('products-list').addClass('products-grid').addClass(_old_grid);
                    }
                    else {
                        $ul_products.removeClass('products-grid').addClass('products-list').removeClass(_old_grid);
                    }
                    Cookies.set('veres_wc_catalog_view_mode', _mode, { expires: 2 });
                }
            })
            .on('mouseover', '.lasf-custom-dropdown', function (e) {
                $(this).addClass('is-hover');
            })
            .on('mouseleave', '.lasf-custom-dropdown', function (e) {
                $(this).removeClass('is-hover');
            })
        /**
         * Ajax add-to-cart
         */
        $document.on('adding_to_cart', function (e, $button, data) {
            $('body').addClass('lakit-adding-cart');
            $('.lakit-cart').addClass('lakit-cart-open');
            $('.lakit-cart__icon').addClass('la-loading-spin');
        });
        $document.on('added_to_cart', function( e, fragments, cart_hash, $button ){
            $('body').removeClass('lakit-adding-cart');
            $('.lakit-cart__icon').removeClass('la-loading-spin');
        } );

        /**
         * Ajax add-to-cart - Single Page
         */

        if( la_theme_config.single_ajax_add_cart ) {
            $document.on('submit', 'div.product.type-product:not(.product-type-external) form.cart', function(e){
                e.preventDefault();
                $document.trigger('adding_to_cart');

                var form = $(this),
                    product_url = form.attr('action') || window.location.href,
                    action_url = LaStudio.global.addQueryArg(product_url, 'product_quickview', '1');

                if( typeof $.fn.block != 'undefined' ) {
                    form.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}})
                }

                $.post(action_url, form.serialize() + '&_wp_http_referer=' + product_url, function (result) {
                    // Show message
                    const $notices = $(result).find('.woocommerce-notices-wrapper');
                    if( $notices.length && $('.woocommerce-message, .woocommerce-error', $notices.first()).length ){
                        $('.single-product-article > .woocommerce-notices-wrapper').replaceWith($notices.first());
                    }
                    if( typeof $.fn.unblock != 'undefined' ) {
                        form.unblock();
                    }
                    $document.trigger('LaStudio:Component:Popup:Close');
                    // update fragments
                    $.ajax({
                        url: woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
                        type: 'POST',
                        success: function( data ) {
                            if ( data && data.fragments ) {
                                $.each( data.fragments, function( key, value ) {
                                    $( key ).replaceWith( value );
                                });
                                $( document.body ).trigger( 'wc_fragments_refreshed' );
                                $('body').removeClass('lakit-adding-cart');
                                $('.lakit-cart__icon').removeClass('la-loading-spin');
                            }
                        }
                    });
                });
            });

            $document.on('submit', '.product_item:not(.product-type-external) form.cart', function(e){
                e.preventDefault();
                $document.trigger('adding_to_cart');

                var form = $(this),
                    product_url = form.attr('action') || window.location.href,
                    action_url = LaStudio.global.addQueryArg(product_url, 'product_quickview', '1');

                if( typeof $.fn.block != 'undefined' ) {
                    form.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}})
                }

                $.post(action_url, form.serialize() + '&_wp_http_referer=' + product_url, function (result) {
                    // Show message

                    const $notices = $(result).find('.woocommerce-notices-wrapper');
                    if( $notices.length && $('.woocommerce-message, .woocommerce-error', $notices.first()).length ){
                        $('.single-product-article > .woocommerce-notices-wrapper').replaceWith($notices.first());
                    }
                    if( typeof $.fn.unblock != 'undefined' ) {
                        form.unblock();
                    }
                    $document.trigger('LaStudio:Component:Popup:Close');

                    // update fragments
                    $.ajax({
                        url: woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
                        type: 'POST',
                        success: function( data ) {
                            if ( data && data.fragments ) {
                                $.each( data.fragments, function( key, value ) {
                                    $( key ).replaceWith( value );
                                });
                                $( document.body ).trigger( 'wc_fragments_refreshed' );
                                $('body').removeClass('lakit-adding-cart');
                                $('.lakit-cart__icon').removeClass('la-loading-spin');
                            }
                        }
                    });
                });
            });

            $document.on('click', '.product_item .la-addcart.product_type_variable', function (e){
                var $cart = $(this).closest('.product_item').find('form.cart');
                if($cart.length && $cart.find('.wc-variation-selection-needed').length == 0){
                    e.preventDefault();
                    $cart.find('.single_add_to_cart_button').trigger('click');
                    return false;
                }
            })
        }


        /**
         * My Account toggle
         */

        if(location.hash == '#register' && $('#customer_login .u-column2.col-2').length){
            $('#customer_login .u-column2.col-2').addClass('active');
        }
        else{
            $('#customer_login .u-column1.col-1').addClass('active');
        }

        $document.on('click', '#customer_login h2', function (e) {
            e.preventDefault();
            var $parent = $(this).parent();
            if(!$parent.hasClass('active')){
                $parent.addClass('active').siblings('div').removeClass('active');
            }
        });

        $document.on('click', '.wcr_resigter_link a', function (e){
            e.preventDefault();
            $('#customer_login .u-column2.col-2 h2').trigger('click');
        });

        /**
         * WooCommerce Tabs
         */
        wooTabs($('.single-product-article'));

    }
    LaStudioWooCommerce.prototype.QuickView = function (){
        $document.on('click','.la-quickview-button',function(e){
            if($window.width() > 900){
                e.preventDefault();
                var $this = $(this);
                var show_popup = function(){
                    if($.featherlight.close() !== undefined){
                        $.featherlight.close();
                    }
                    $.featherlight($this.data('href'), {
                        openSpeed:      0,
                        closeSpeed:     0,
                        type:{
                            wc_quickview: true
                        },
                        background: '<div class="featherlight featherlight-loading is--qvpp"><div class="featherlight-outer"><button class="featherlight-close-icon featherlight-close" aria-label="Close"><i class="lastudioicon-e-remove"></i></button><div class="featherlight-content"><div class="featherlight-inner"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div></div><div class="custom-featherlight-overlay"></div></div>',
                        contentFilters: ['wc_quickview'],
                        ajaxSetup: {
                            cache: true,
                            ajax_request_id: LaStudio.global.getUrlParameter('product_quickview', $this.data('href'))
                        },
                        beforeOpen: function (evt) {
                            $body.addClass('open-quickview-product');
                        },
                        afterOpen: function (evt) {
                            var $woo_gallery = $('.woocommerce-product-gallery', this.$content);
                            if($woo_gallery.length && $.fn.wc_product_gallery){
                                $body.addClass('lightcase--pending');
                                $woo_gallery.wc_product_gallery({
                                    flexslider: {
                                        animation: "slide",
                                        animationLoop: false,
                                        animationSpeed: 500,
                                        controlNav: "",
                                        directionNav: true,
                                        rtl: false,
                                        slideshow: false,
                                        smoothHeight: false
                                    },
                                    photoswipe_enabled: 0,
                                    zoom_enabled: 0,
                                });
                            }
                            $document.trigger('reinit_la_swatches');
                        },
                        afterClose: function(evt){
                            $body.removeClass('open-quickview-product lightcase--completed lightcase--pending');
                        }
                    });
                }
                if($.isFunction( $.fn.featherlight )) {
                    show_popup();
                }
                else{
                    LaStudio.global.loadDependencies([ LaStudio.global.loadJsFile('featherlight')], show_popup );
                }
            }
        });
    }
    LaStudioWooCommerce.prototype.Wishlist = function (){
        /**
         * Support YITH Wishlist
         */
        function set_attribute_for_wl_table(){
            var $table = $('table.wishlist_table');
            $table.addClass('shop_table_responsive');
            $table.find('thead th').each(function(){
                var _th = $(this),
                    _text = _th.text().trim();
                if(_text != ""){
                    $('td.' + _th.attr('class'), $table).attr('data-title', _text);
                }
            });
        }
        set_attribute_for_wl_table();
        $body.on('removed_from_wishlist', function(e){
            set_attribute_for_wl_table();
        });
        $document.on('added_to_cart', function(e, fragments, cart_hash, $button){
            setTimeout(set_attribute_for_wl_table, 800);
        });
        $document.on('click','.product a.add_wishlist.la-yith-wishlist',function(e){
            if(!$(this).hasClass('added')) {
                e.preventDefault();
                var $button     = $(this),
                    product_id = $button.data( 'product_id' ),
                    data = {
                        add_to_wishlist: product_id,
                        product_type: $button.data( 'product-type' ),
                        action: yith_wcwl_l10n.actions.add_to_wishlist_action
                    };
                try {
                    if (yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in) {
                        var wishlist_popup_container = $button.parents('.yith-wcwl-popup-footer').prev('.yith-wcwl-popup-content'),
                            wishlist_popup_select = wishlist_popup_container.find('.wishlist-select'),
                            wishlist_popup_name = wishlist_popup_container.find('.wishlist-name'),
                            wishlist_popup_visibility = wishlist_popup_container.find('.wishlist-visibility');

                        data.wishlist_id = wishlist_popup_select.val();
                        data.wishlist_name = wishlist_popup_name.val();
                        data.wishlist_visibility = wishlist_popup_visibility.val();
                    }

                    if (!LaStudio.global.isCookieEnable()) {
                        alert(yith_wcwl_l10n.labels.cookie_disabled);
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: yith_wcwl_l10n.ajax_url,
                        data: data,
                        dataType: 'json',
                        beforeSend: function () {
                            $button.addClass('loading');
                        },
                        complete: function () {
                            $button.removeClass('loading').addClass('added');
                        },
                        success: function (response) {
                            if (yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in) {
                                var wishlist_select = $('select.wishlist-select');
                                if (typeof $.prettyPhoto !== 'undefined') {
                                    $.prettyPhoto.close();
                                }
                                wishlist_select.each(function (index) {
                                    var t = $(this),
                                        wishlist_options = t.find('option');
                                    wishlist_options = wishlist_options.slice(1, wishlist_options.length - 1);
                                    wishlist_options.remove();

                                    if (typeof response.user_wishlists !== 'undefined') {
                                        var i = 0;
                                        for (i in response.user_wishlists) {
                                            if (response.user_wishlists[i].is_default != "1") {
                                                $('<option>')
                                                    .val(response.user_wishlists[i].ID)
                                                    .html(response.user_wishlists[i].wishlist_name)
                                                    .insertBefore(t.find('option:last-child'))
                                            }
                                        }
                                    }
                                });

                            }

                            LaStudio.global.ShowNotice(response.message, '', true, 3000)

                            $button.attr('href',response.wishlist_url);
                            $('.add_wishlist[data-product_id="' + $button.data('product_id') + '"]').addClass('added');
                            $body.trigger('added_to_wishlist');
                        }
                    });
                } catch (ex) {
                    LaStudio.global.log(ex);
                }
            }
        });

        /**
         * Support TI Wishlist
         */
        $document.on('click','.product a.add_wishlist.la-ti-wishlist',function(e){
            e.preventDefault();
            var $ti_action;
            if($(this).closest('.entry-summary').length){
                $ti_action = $(this).closest('.entry-summary').find('form.cart .tinvwl_add_to_wishlist_button');
            }
            else if($(this).closest('.elementor-widget').length){
                $ti_action = $(this).closest('.elementor-widget').find('form.cart .tinvwl_add_to_wishlist_button');
            }
            else{
                $ti_action = $(this).closest('.product').find('.tinvwl_add_to_wishlist_button');
            }
            $ti_action.trigger('click');
        })

        /**
         * Core Wishlist
         */
        $document
            .on('click','.product a.add_wishlist.la-core-wishlist, a.la_remove_from_wishlist, .view-wishlist-list, .lakit-wishlist-trigger',function(e){
                e.preventDefault();
                let $btn = $(this),
                    ajaxType = $btn.hasClass('added') ? 'load' : 'add';

                if($btn.is('.la_remove_from_wishlist')){
                    ajaxType = 'remove';
                }
                if($btn.is('.view-wishlist-list') || $btn.is('.lakit-wishlist-trigger')){
                    ajaxType = 'load';
                }

                const render_table = ( callback ) => {
                    LaStudio.global.ShowMessageBox(LaStudio.utils.localCache.get('la_wishlist').table, 'open-wishlist-pp', false, callback);
                }

                if( ajaxType == 'load' && LaStudio.utils.localCache.exist('la_wishlist', true)){
                    if( $('body').hasClass('open-wishlist-pp') ){
                        $('.la-global-message').html(LaStudio.utils.localCache.get('la_wishlist').table);
                    }
                    else{
                        render_table();
                    }
                }
                else{
                    let wl_timer = null;
                    const WishlistAjax = AjaxManager('wishlist', {
                        beforeSendCallback: function (){
                            $btn.addClass('loading');
                        },
                        completeCallback: function (){
                            $btn.removeClass('loading');
                        },
                        successCallback: function (data, textStatus, jqXHR){
                            const response = data.data.responses.wishlist.data;
                            $('.la-wishlist-count').html(response.count);

                            clearTimeout(wl_timer);

                            if(ajaxType == 'add'){
                                $('.add_wishlist[data-product_id="' + $btn.data('product_id') + '"]').addClass('added').attr('href', response.wishlist_url);
                            }
                            else if (ajaxType == 'remove'){
                                $('.add_wishlist[data-product_id="' + $btn.data('product_id') + '"]').removeClass('added');
                            }
                            LaStudio.utils.localCache.set('la_wishlist', {
                                'count' : response.count,
                                'table' : response.table_output
                            })
                            const callback = function (){
                                if(ajaxType != 'load') {
                                    $('.la_wishlist-bottom_notice').html(response.message).addClass('show-notice');
                                    wl_timer = setTimeout(function () {
                                        $('.la_wishlist-bottom_notice').removeClass('show-notice')
                                    }, 3000);
                                }
                            }
                            if( $('.la-global-message').length ) {
                                $('.la-global-message').html(response.table_output);
                                callback();
                            }
                            else if( $('.la_wishlist_table').length ){
                                $('.la_wishlist_table').replaceWith( $(response.table_output).find('.la_wishlist_table') );
                            }
                            else{
                                render_table( callback );
                            }
                        }
                    });

                    WishlistAjax.sendData({
                        post_id: $btn.data( 'product_id' ),
                        type: ajaxType
                    })
                }
            })


        $('form.variations_form').on('woocommerce_variation_has_changed', function(e){
            var $frm = $(this),
                variation_id = parseInt($frm.find('input[name="variation_id"]').val() || 0);
            if(variation_id == 0){
                variation_id = parseInt($frm.find('input[name="product_id"]').val());
            }
            $frm.closest('.product').find('.cart .la-core-wishlist').attr('data-product_id', variation_id).removeClass('added');
        });
    }
    LaStudioWooCommerce.prototype.Compare = function (){
        /**
         * Support YITH Compare
         */
        $document
            .on('click', 'table.compare-list .remove a', function(e){
                e.preventDefault();
                $('.add_compare[data-product_id="' + $(this).data('product_id') + '"]', window.parent.document).removeClass('added');
            })
            .on('click','.la_com_action--compare', function(e){
                if(typeof yith_woocompare !== "undefined"){
                    e.preventDefault();
                    $document.trigger('LaStudio:Component:Popup:Close');
                    $body.trigger('yith_woocompare_open_popup', { response: LaStudio.global.addQueryArg( LaStudio.global.addQueryArg('', 'action', yith_woocompare.actionview) , 'iframe', 'true') });
                }
            })
            .on('click', '.product a.add_compare:not(.la-core-compare)', function(e){
                e.preventDefault();

                if($(this).hasClass('added')){
                    $body.trigger('yith_woocompare_open_popup', { response: LaStudio.global.addQueryArg( LaStudio.global.addQueryArg('', 'action', yith_woocompare.actionview) , 'iframe', 'true') });
                    return;
                }
                var $button     = $(this),
                    widget_list = $('.yith-woocompare-widget ul.products-list'),
                    data        = {
                        action: yith_woocompare.actionadd,
                        id: $button.data('product_id'),
                        context: 'frontend'
                    }

                $.ajax({
                    type: 'post',
                    url: yith_woocompare.ajaxurl.toString().replace( '%%endpoint%%', yith_woocompare.actionadd ),
                    data: data,
                    dataType: 'json',
                    beforeSend: function(){
                        $button.addClass('loading');
                    },
                    complete: function(){
                        $button.removeClass('loading').addClass('added');
                    },
                    success: function(response){
                        if($.isFunction($.fn.block) ) {
                            widget_list.unblock()
                        }
                        LaStudio.global.ShowNotice(la_theme_config.i18n.compare.success, '', true, 3000)
                        $('.add_compare[data-product_id="' + $button.data('product_id') + '"]').addClass('added');
                        widget_list.unblock().html( response.widget_table );
                    }
                });
            });

        /**
         * Core Compare
         */

        $document
            .on('click', '.product a.add_compare.la-core-compare, a.la_remove_from_compare, .view-compare-list, .lakit-compare-trigger', function(e){
                e.preventDefault();
                let $btn = $(this),
                    ajaxType = $btn.hasClass('added') ? 'load' : 'add';

                if($btn.is('.la_remove_from_compare')){
                    ajaxType = 'remove';
                }

                if($btn.is('.view-compare-list') || $btn.is('.lakit-compare-trigger')){
                    ajaxType = 'load';
                }

                const render_table = ( callback ) => {
                    const wl_data = LaStudio.utils.localCache.get('la_compare');
                    LaStudio.global.ShowMessageBox(wl_data.table, 'open-compare-pp', false, callback);
                }

                if( ajaxType == 'load' && LaStudio.utils.localCache.exist('la_compare', true)){
                    if( $('body').hasClass('open-compare-pp') ){
                        $('.la-global-message').html(LaStudio.utils.localCache.get('la_compare').table);
                    }
                    else{
                        render_table();
                    }
                }
                else{
                    let cp_timer = null;
                    const CompareAjax = AjaxManager('compare', {
                        beforeSendCallback: function (){
                            $btn.addClass('loading');
                        },
                        completeCallback: function (){
                            $btn.removeClass('loading');
                        },
                        successCallback: function (data, textStatus, jqXHR){
                            const response = data.data.responses.compare.data;
                            $('.la-compare-count').html(response.count);

                            clearTimeout(cp_timer);

                            LaStudio.utils.localCache.set('la_compare', {
                                'count' : response.count,
                                'table' : response.table_output
                            })

                            if( $('.la-global-message').length ) {
                                $('.la-global-message').html(response.table_output);
                            }
                            else if( $('.la-compare-table').length ){
                                $('.la-compare-table').replaceWith( $(response.table_output).find('.la-compare-table') );
                            }
                            else{
                                render_table();
                            }
                        }
                    });

                    CompareAjax.sendData({
                        post_id: $btn.data( 'product_id' ),
                        type: ajaxType
                    })
                }
            })
    }
    LaStudioWooCommerce.prototype.AjaxFilter = function (){

        if( $('.woocommerce.lakit_wc_widget_current_query').length == 0){
            return;
        }

        function create_loading(){
            if( $('.woocommerce.lakit_wc_widget_current_query .lakit-products').length ){
                if( $('.woocommerce.lakit_wc_widget_current_query .lakit-products .la-ajax-shop-loading').length == 0 ) {
                    $('<div class="la-ajax-shop-loading"><span class="lakit-css-loader"></span></div>').prependTo( $('.woocommerce.lakit_wc_widget_current_query .lakit-products') );
                }
            }
            else{
                if( $('.woocommerce.lakit_wc_widget_current_query .la-ajax-shop-loading').length == 0 ) {
                    $('<div class="la-ajax-shop-loading"><span class="lakit-css-loader"></span></div>').prependTo( $('.woocommerce.lakit_wc_widget_current_query') );
                }
            }
        }
        create_loading();

        $('li.current-cat, li.current-cat-parent', $('.widget-area')).each(function(){
            $(this).addClass('open');
            $('>ul', $(this)).css('display','block');
        });

        function clone_view_count() {

        }

        clone_view_count();

        function init_price_filter() {
            if ( typeof woocommerce_price_slider_params === 'undefined' ) {
                return false;
            }

            $( 'input#min_price, input#max_price' ).hide();
            $( '.price_slider, .price_label' ).show();

            var min_price = $( '.price_slider_amount #min_price' ).data( 'min' ),
                max_price = $( '.price_slider_amount #max_price' ).data( 'max' ),
                current_min_price = $( '.price_slider_amount #min_price' ).val(),
                current_max_price = $( '.price_slider_amount #max_price' ).val();

            $( '.price_slider:not(.ui-slider)' ).slider({
                range: true,
                animate: true,
                min: min_price,
                max: max_price,
                values: [ current_min_price, current_max_price ],
                create: function() {

                    $( '.price_slider_amount #min_price' ).val( current_min_price );
                    $( '.price_slider_amount #max_price' ).val( current_max_price );

                    $( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
                },
                slide: function( event, ui ) {

                    $( 'input#min_price' ).val( ui.values[0] );
                    $( 'input#max_price' ).val( ui.values[1] );

                    $( document.body ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
                },
                change: function( event, ui ) {

                    $( document.body ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );
                }
            });
        }

        var elm_to_replace = [
            '.woocommerce.lakit_wc_widget_current_query .wc-toolbar-top',
            '.woocommerce.lakit_wc_widget_current_query .la-advanced-product-filters .sidebar-inner',
            '.woocommerce.lakit_wc_widget_current_query .wc_page_description',
            '.woocommerce.lakit_wc_widget_current_query ul.ul_products',
            '.woocommerce.lakit_wc_widget_current_query .woocommerce-pagination',
            '.woocommerce.lakit_wc_widget_current_query .lakit-pagination',
            '.elementor-lakit-woofilters .woocommerce-result-count'
        ];

        var target_to_init = '.woocommerce.lakit_wc_widget_current_query .woocommerce-pagination:not(.lakit-ajax-pagination) a, .la-advanced-product-filters-result a',
            target_to_init2 = '.woo-widget-filter a, .wc-ordering a, .wc-view-count a, .woocommerce.product-sort-by a, .woocommerce.la-price-filter-list a, .woocommerce.widget_layered_nav a, .woocommerce.widget_product_tag_cloud li a, .woocommerce.widget_product_categories a, .wc-block-product-categories-list-item > a',
            target_to_init3 = '.woocommerce.widget_product_tag_cloud:not(.la_product_tag_cloud) a';

        try{
            history.pushState({
                title: document.title,
                href: LaStudio.global.removeURLParameter(window.location.href, 'la_doing_ajax')
            }, document.title, LaStudio.global.removeURLParameter(window.location.href, 'la_doing_ajax'));
            LaStudio.utils.localCache.set(LaStudio.global.removeURLParameter(window.location.href, 'la_doing_ajax'), document.documentElement.outerHTML);
        }
        catch (ex) {
            LaStudio.global.log(ex);
        }

        function ajaxFilterSuccessCallback( response, url, element){
            var ntitle = response.match('<title>(.*)<\/title>');
            if(ntitle[1]){
                document.title = ntitle[1].replaceAll('&#8211;', '–');
            }

            LaStudio.utils.localCache.set(url, response);

            for ( var i = 0; i < elm_to_replace.length; i++){
                if( $(elm_to_replace[i]).length ){
                    if( $(response).find(elm_to_replace[i]).length ){
                        $(elm_to_replace[i]).replaceWith( $(response).find(elm_to_replace[i]) );
                    }
                    else{
                        $(elm_to_replace[i]).addClass('nothing-to-show')
                    }
                }
            }

            // cache for no products found
            if( $('.woocommerce.lakit_wc_widget_current_query > .woocommerce-info').length || $(response).find('.woocommerce.lakit_wc_widget_current_query > .woocommerce-info').length ){
                $('.woocommerce.lakit_wc_widget_current_query').replaceWith($(response).find('.woocommerce.lakit_wc_widget_current_query'));
                create_loading();
            }

            $('.widget-area').each(function (){
                var _tmp_id = $(this).data('id');
                if($(response).find('.widget-area[data-id="'+_tmp_id+'"]').length){
                    $(this).replaceWith($(response).find('.widget-area[data-id="'+_tmp_id+'"]'));
                    LaStudio.core.Blog( $('.widget-area[data-id="'+_tmp_id+'"]') );
                }
            });

            const elementorSidebar = [];

            $('.widget.lakit-wp--widget').each(function (){
                var _col_id = $(this).closest('.elementor-column, .e-container').data('id');
                if(elementorSidebar.indexOf(_col_id) < 0){
                    elementorSidebar.push(_col_id);
                }
            });

            if(elementorSidebar.length > 0){
                elementorSidebar.forEach( function (_w){
                    if($(response).find('.elementor-element[data-id="'+_w+'"]').length){
                        $('.elementor-element[data-id="'+_w+'"]').replaceWith($(response).find('.elementor-element[data-id="'+_w+'"]'));
                        LaStudio.core.elementorFrontendInit( $('.elementor-element[data-id="'+_w+'"]') );
                        LaStudio.core.Blog( $('.elementor-element[data-id="'+_w+'"]') );
                    }
                } )
            }

            if( $('.lakit-breadcrumbs').length && $(response).find('.lakit-breadcrumbs').length ) {
                $('.lakit-breadcrumbs').replaceWith($(response).find('.lakit-breadcrumbs'));
            }
            if( $('.lakit-archive-title').length && $(response).find('.lakit-archive-title').length ) {
                $('.lakit-archive-title').replaceWith($(response).find('.lakit-archive-title'));
            }

            try {
                var _view_mode = Cookies.get('veres_wc_catalog_view_mode');
                $('.wc-toolbar .wc-view-toggle button[data-view_mode="'+_view_mode+'"]').trigger('click');

                var _per_row = Cookies.get('veres_wc_product_per_row');
                $('.wc-toolbar .wc-view-item a[data-col="'+_per_row+'"]').trigger('click');

            }catch (e) {

            }

            $('body').trigger('lastudio-fix-ios-limit-image-resource');

            $('.la-ajax-shop-loading').removeClass('loading');

            LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter:success', [response, url, element]);
        }

        window.addEventListener('popstate', function(event){
            if( event.state !== null && typeof event.state.href !== "undefined"){
                var _href = event.state.href;
                _href = LaStudio.global.removeURLParameter(_href, 'la_doing_ajax');
                if(LaStudio.utils.localCache.exist(_href, true)){
                    ajaxFilterSuccessCallback(LaStudio.utils.localCache.get(_href), _href, $window);
                }
                else{
                    window.location.reload();
                }
            }
            else{
                window.location.reload();
            }
        });

        LaStudio.global.eventManager.subscribe('LaStudio:AjaxShopFilter', function(e, url, element){

            if( $('.wc-toolbar-container').length > 0) {
                var position = $('.wc-toolbar-container').offset().top - 200;
                $htmlbody.stop().animate({
                    scrollTop: position
                }, 800 );
            }

            if ('?' == url.slice(-1)) {
                url = url.slice(0, -1);
            }
            url = url.replace(/%2C/g, ',');

            url = LaStudio.global.removeURLParameter(url,'la_doing_ajax');

            try{
                history.pushState({
                    title: document.title,
                    href: url
                }, document.title, url);
            }catch (ex) {
                LaStudio.global.log(ex);
            }

            LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter:before_send', [url, element]);

            if (LaStudio.utils.ajax_xhr) {
                LaStudio.utils.ajax_xhr.abort();
            }
            var origin_url = url;

            url = LaStudio.global.addQueryArg(url, 'la_doing_ajax', 'true');

            LaStudio.utils.ajax_xhr = $.get(url, function ( response ) {
                ajaxFilterSuccessCallback( response, origin_url, element );
            }, 'html');
        });
        LaStudio.global.eventManager.subscribe('LaStudio:AjaxShopFilter:success', function(e, response, url, element){

            if( $('.widget.woocommerce.widget_price_filter').length ) {
                init_price_filter();
            }
            if($dochtml.hasClass('open-advanced-shop-filter')){
                $dochtml.removeClass('open-advanced-shop-filter');
                $('.la-advanced-product-filters').stop().slideUp('fast');
            }
            clone_view_count();

            var pwb_params = LaStudio.global.getUrlParameter('pwb-brand-filter', location.href);
            if(pwb_params !== null && pwb_params !== ''){
                $('.pwb-filter-products input[type="checkbox"]').prop("checked", false);
                pwb_params.split(',').filter(function (el){
                    $('.pwb-filter-products input[type="checkbox"][value="'+el+'"]').prop("checked", true);
                })
            }
            $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger( 'lastudio-lazy-images-load' ).trigger( 'jetpack-lazy-images-load' ).trigger( 'lastudio-object-fit' );
            LaStudio.core.initAll($document);
        });

        $document
            .on('click', '.btn-advanced-shop-filter', function (e) {
                e.preventDefault();
                $dochtml.toggleClass('open-advanced-shop-filter');
                $('.la-advanced-product-filters').stop().animate({
                    height: 'toggle'
                });
            })
            .on('click', '.la-advanced-product-filters .close-advanced-product-filters', function(e){
                e.preventDefault();
                $('.btn-advanced-shop-filter').trigger('click');
            })
            .on('click', target_to_init, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [$(this).attr('href'), $(this)]);
            })
            .on('click', target_to_init2, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                if($(this).closest('.widget_layered_nav').length){
                    if($(this).parent().hasClass('active') || $(this).parent().hasClass('chosen')){
                        $(this).parent().removeClass('active chosen');
                    }
                    else{
                        $(this).parent().addClass('active chosen');
                    }
                }
                else{
                    $(this).parent().addClass('active').siblings().removeClass('active');
                }

                $('.lasf-custom-dropdown').removeClass('is-hover');

                var _url = $(this).attr('href'),
                    _preset_from_w = LaStudio.global.getUrlParameter('la_preset'),
                    _preset_from_e = LaStudio.global.getUrlParameter('la_preset', _url);

                if(!_preset_from_e && _preset_from_w){
                    _url = LaStudio.global.addQueryArg(_url, 'la_preset', _preset_from_w);
                }

                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $(this)]);
            })
            .on('click', target_to_init3, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                $(this).addClass('active').siblings().removeClass('active');
                var _url = $(this).attr('href'),
                    _preset_from_w = LaStudio.global.getUrlParameter('la_preset'),
                    _preset_from_e = LaStudio.global.getUrlParameter('la_preset', _url);

                if(!_preset_from_e && _preset_from_w){
                    _url = LaStudio.global.addQueryArg(_url, 'la_preset', _preset_from_w);
                }
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $(this)]);
            })
            .on('click', '.woocommerce.widget_layered_nav_filters a', function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [$(this).attr('href'), $(this)]);
            })
            .on('submit', '.widget_price_filter form, .woocommerce-widget-layered-nav form', function(e){
                e.preventDefault();
                var $form = $(this),
                    url = $form.attr('action') + '?' + $form.serialize();
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [url, $form]);
            })
            .on('change', '.woocommerce-widget-layered-nav form select', function(e){
                e.preventDefault();
                var slug = $( this ).val(),
                    _id = $(this).attr('class').split('dropdown_layered_nav_')[1];
                $( ':input[name="filter_'+_id+'"]' ).val( slug );

                // Submit form on change if standard dropdown.
                if ( ! $( this ).attr( 'multiple' ) ) {
                    $( this ).closest( 'form' ).submit();
                }
            })
            .on('change', '.widget_pwb_dropdown_widget .pwb-dropdown-widget', function(e){
                e.preventDefault();
                var $form = $(this),
                    url = $(this).val();
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [url, $form]);
            })
            .on('click', '.widget_pwb_filter_by_brand_widget .pwb-filter-products button', function (e){
                e.preventDefault();
                var $form = $(this).closest('.pwb-filter-products'),
                    _url = $form.data('cat-url'),
                    _params = [];
                $form.find('input[type="checkbox"]:checked').each(function (){
                    _params.push($(this).val());
                });
                if(_params.length > 0){
                    _url = LaStudio.global.addQueryArg(_url, 'pwb-brand-filter', _params.join(','));
                }
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $form]);
            })
            .on('change', '.widget_pwb_filter_by_brand_widget .pwb-filter-products.pwb-hide-submit-btn input', function (e){
                e.preventDefault();
                var $form = $(this).closest('.pwb-filter-products'),
                    _url = $form.data('cat-url'),
                    _params = [];
                $form.find('input[type="checkbox"]:checked').each(function (){
                    _params.push($(this).val());
                });
                if(_params.length > 0){
                    _url = LaStudio.global.addQueryArg(_url, 'pwb-brand-filter', _params.join(','));
                }
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $form]);
            })
        $('.widget_pwb_dropdown_widget .pwb-dropdown-widget').off('change');
        $('.widget_pwb_filter_by_brand_widget .pwb-filter-products button').off('click');
        $('.widget_pwb_filter_by_brand_widget .pwb-filter-products.pwb-hide-submit-btn input').off('change');
    }

    window.LaStudioWooCommerce = new LaStudioWooCommerce()

})(jQuery);
