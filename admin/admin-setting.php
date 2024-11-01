<?php

namespace SalesNotification\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

require( __DIR__ . '/classes/class.settings-api.php' );

class WC_Sale_Notification_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new \WC_Sale_Notification_Settings_API();
        add_action( 'admin_init', [ $this, 'admin_init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 220 );
        add_action( 'admin_init', [$this, 'register_setting_fileds' ] );
        add_action('admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
        add_action( 'wsa_form_bottom_wcsales_general_tabs', [ $this, 'html_general_tabs' ] );
        add_action( 'wsa_form_bottom_wcsales_fakes_data_tabs', [ $this, 'html_fake_data_tabs' ] );
        $this->plugin_recommendations();
    }

    // Admin Initialize
    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->admin_fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Plugins menu Register
    function admin_menu() {

        $menu = 'add_menu_' . 'page';
        $menu(
            'wcsalenotification_panel',
            esc_html__( 'Sales Notification', 'wc-sales-notification' ),
            esc_html__( 'Sales Notification', 'wc-sales-notification' ),
            'wcsalenotification_page',
            NULL,
            'dashicons-megaphone',
            100
        );
        
        add_submenu_page(
            'wcsalenotification_page',
            esc_html__( 'Settings', 'wc-sales-notification' ),
            esc_html__( 'Settings', 'wc-sales-notification' ),
            'manage_options',
            'wcsalenotification',
            array ( $this, 'plugin_page' )
        );

    }

    /**
     * [plugin_recommendations]
     * @return [void]
     */
    public function plugin_recommendations(){

        $get_instance = Recommended_Plugins::instance( 
            array( 
                'text_domain'       => 'wc-sales-notification', 
                'parent_menu_slug'  => 'wcsalenotification_page', 
                'menu_capability'   => 'manage_options', 
                'menu_page_slug'    => 'slasnotification-recommendations',
                'priority'          => 221,
                'assets_url'        => WC_SALENOTIFICATION_PL_URL.'admin/assets',
                'hook_suffix'       => 'sales-notification_page_slasnotification-recommendations'
            )
        );

        $get_instance->add_new_tab( array(

            'title' => esc_html__( 'Recommended', 'wc-sales-notification' ),
            'active' => true,
            'plugins' => array(

                array(
                    'slug'      => 'woolentor-addons',
                    'location'  => 'woolentor_addons_elementor.php',
                    'name'      => esc_html__( 'WooLentor', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'ht-mega-for-elementor',
                    'location'  => 'htmega_addons_elementor.php',
                    'name'      => esc_html__( 'HT Mega', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'hashbar-wp-notification-bar',
                    'location'  => 'init.php',
                    'name'      => esc_html__( 'HashBar', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'ht-slider-for-elementor',
                    'location'  => 'ht-slider-for-elementor.php',
                    'name'      => esc_html__( 'HT Slider For Elementor', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'ht-contactform',
                    'location'  => 'contact-form-widget-elementor.php',
                    'name'      => esc_html__( 'HT Contact Form 7', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'extensions-for-cf7',
                    'location'  => 'extensions-for-cf7.php',
                    'name'      => esc_html__( 'Extensions For CF7', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'ht-wpform',
                    'location'  => 'wpform-widget-elementor.php',
                    'name'      => esc_html__( 'HT WPForms', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'ht-menu-lite',
                    'location'  => 'ht-mega-menu.php',
                    'name'      => esc_html__( 'HT Menu', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'insert-headers-and-footers-script',
                    'location'  => 'init.php',
                    'name'      => esc_html__( 'HT Script', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'wp-plugin-manager',
                    'location'  => 'plugin-main.php',
                    'name'      => esc_html__( 'WP Plugin Manager', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'wc-builder',
                    'location'  => 'wc-builder.php',
                    'name'      => esc_html__( 'WC Builder', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'whols',
                    'location'  => 'whols.php',
                    'name'      => esc_html__( 'Whols', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'just-tables',
                    'location'  => 'just-tables.php',
                    'name'      => esc_html__( 'JustTables', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'wc-multi-currency',
                    'location'  => 'wcmilticurrency.php',
                    'name'      => esc_html__( 'Multi Currency', 'wc-sales-notification' )
                )
            )

        ) );

        $get_instance->add_new_tab(array(
            'title' => esc_html__( 'You May Also Like', 'wc-sales-notification' ),
            'plugins' => array(

                array(
                    'slug'      => 'woolentor-addons-pro',
                    'location'  => 'woolentor_addons_pro.php',
                    'name'      => esc_html__( 'WooLentor Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'WooLentor is one of the most popular WooCommerce Elementor Addons on WordPress.org. It has been downloaded more than 672,148 times and 60,000 stores are using WooLentor plugin. Why not you?', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'htmega-pro',
                    'location'  => 'htmega_pro.php',
                    'name'      => esc_html__( 'HT Mega Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/ht-mega-pro/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'HTMega is an absolute addon for elementor that includes 80+ elements & 360 Blocks with unlimited variations. HT Mega brings limitless possibilities. Embellish your site with the elements of HT Mega.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'swatchly-pro',
                    'location'  => 'swatchly-pro.php',
                    'name'      => esc_html__( 'Product Variation Swatches', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/swatchly-product-variation-swatches-for-woocommerce-products/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Are you getting frustrated with WooCommerce’s current way of presenting the variants for your products? Well, say goodbye to dropdowns and start showing the product variations in a whole new light with Swatchly.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'whols-pro',
                    'location'  => 'whols-pro.php',
                    'name'      => esc_html__( 'Whols Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/whols-woocommerce-wholesale-prices/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Whols is an outstanding WordPress plugin for WooCommerce that allows store owners to set wholesale prices for the products of their online stores. This plugin enables you to show special wholesale prices to the wholesaler. Users can easily request to become a wholesale customer by filling out a simple online registration form. Once the registration is complete, the owner of the store will be able to review the request and approve the request either manually or automatically.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'just-tables-pro',
                    'location'  => 'just-tables-pro.php',
                    'name'      => esc_html__( 'JustTables Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/wp/justtables/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'JustTables is an incredible WordPress plugin that lets you showcase all your WooCommerce products in a sortable and filterable table view. It allows your customers to easily navigate through different attributes of the products and compare them on a single page. This plugin will be of great help if you are looking for an easy solution that increases the chances of landing a sale on your online store.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'multicurrencypro',
                    'location'  => 'multicurrencypro.php',
                    'name'      => esc_html__( 'Multi Currency Pro for WooCommerce', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/multi-currency-pro-for-woocommerce/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Multi-Currency Pro for WooCommerce is a prominent currency switcher plugin for WooCommerce. This plugin allows your website or online store visitors to switch to their preferred currency or their country’s currency.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'cf7-extensions-pro',
                    'location'  => 'cf7-extensions-pro.php',
                    'name'      => esc_html__( 'Extensions For CF7 Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/cf7-extensions/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Contact Form7 Extensions plugin is a fantastic WordPress plugin that enriches the functionalities of Contact Form 7.This all-in-one WordPress plugin will help you turn any contact page into a well-organized, engaging tool for communicating with your website visitors by providing tons of advanced features like drag and drop file upload, repeater field, trigger error for already submitted forms, popup form response, country flags and dial codes with a telephone input field and acceptance field, etc. in addition to its basic features.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'hashbar-pro',
                    'location'  => 'init.php',
                    'name'      => esc_html__( 'HashBar Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/wordpress-notification-bar-plugin/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'HashBar is a WordPress Notification / Alert / Offer Bar plugin which allows you to create unlimited notification bars to notify your customers. This plugin has option to show email subscription form (sometimes it increases up to 500% email subscriber), Offer text and buttons about your promotions. This plugin has the options to add unlimited background colors and images to make your notification bar more professional.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'wp-plugin-manager-pro',
                    'location'  => 'plugin-main.php',
                    'name'      => esc_html__( 'WP Plugin Manager Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/wp-plugin-manager-pro/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'WP Plugin Manager Pro is a specialized WordPress Plugin that helps you to deactivate unnecessary WordPress Plugins page wise and boosts the speed of your WordPress site to improve the overall site performance.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'ht-script-pro',
                    'location'  => 'plugin-main.php',
                    'name'      => esc_html__( 'HT Script Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/insert-headers-and-footers-code-ht-script/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'Insert Headers and Footers Code allows you to insert Google Analytics, Facebook Pixel, custom CSS, custom HTML, JavaScript code to your website header and footer without modifying your theme code.This plugin has the option to add any custom code to your theme in one place, no need to edit the theme code. It will save your time and remove the hassle for the theme update.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'ht-menu',
                    'location'  => 'ht-mega-menu.php',
                    'name'      => esc_html__( 'HT Menu Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/ht-menu-pro/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'WordPress Mega Menu Builder for Elementor', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'ht-slider-addons-pro',
                    'location'  => 'ht-slider-addons-pro.php',
                    'name'      => esc_html__( 'HT Slider Pro For Elementor', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/ht-slider-pro-for-elementor/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'HT Slider Pro is a plugin to create a slider for WordPress websites easily using the Elementor page builder. 80+ prebuild slides/templates are included in this plugin. There is the option to create a post slider, WooCommerce product slider, Video slider, image slider, etc. Fullscreen, full width and box layout option are included.', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'ht-google-place-review',
                    'location'  => 'ht-google-place-review.php',
                    'name'      => esc_html__( 'Google Place Review', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/google-place-review-plugin-for-wordpress/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( 'If you are searching for a modern and excellent google places review WordPress plugin to showcase reviews from Google Maps and strengthen trust between you and your site visitors, look no further than HT Google Place Review', 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'was-this-helpful',
                    'location'  => 'was-this-helpful.php',
                    'name'      => esc_html__( 'Was This Helpful?', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/was-this-helpful/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( "Was this helpful? is a WordPress plugin that allows you to take visitors' feedback on your post/pages or any article. A visitor can share his feedback by like/dislike/yes/no", 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'ht-click-to-call',
                    'location'  => 'ht-click-to-call.php',
                    'name'      => esc_html__( 'HT Click To Call', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/ht-click-to-call/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( "HT – Click to Call is a lightweight WordPress plugin that allows you to add a floating click to call button on your website. It will offer your website visitors an opportunity to call your business immediately at the right moment, especially when they are interested in your products or services and seeking more information.", 'wc-sales-notification' ),
                ),

                array(
                    'slug'      => 'docus-pro',
                    'location'  => 'docus-pro.php',
                    'name'      => esc_html__( 'Docus Pro', 'wc-sales-notification' ),
                    'link'      => 'https://hasthemes.com/plugins/docus-pro-youtube-video-playlist/',
                    'author_link'=> 'https://hasthemes.com/',
                    'description'=> esc_html__( "Embedding a YouTube playlist into your website plays a vital role to curate your content into several categories and make your web content more engaging and popular by keeping the visitors on your website for a longer period.", 'wc-sales-notification' ),
                ),

            )
        ));

        $get_instance->add_new_tab(array(
            'title' => esc_html__( 'Others', 'wc-sales-notification' ),
            'plugins' => array(

                array(
                    'slug'      => 'really-simple-google-tag-manager',
                    'location'  => 'really-simple-google-tag-manager.php',
                    'name'      => esc_html__( 'Really Simple Google Tag Manager', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'ht-instagram',
                    'location'  => 'ht-instagram.php',
                    'name'      => esc_html__( 'HT Feed', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'faster-youtube-embed',
                    'location'  => 'faster-youtube-embed.php',
                    'name'      => esc_html__( 'Faster YouTube Embed', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'wc-sales-notification',
                    'location'  => 'wc-sales-notification.php',
                    'name'      => esc_html__( 'WC Sales Notification', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'preview-link-generator',
                    'location'  => 'preview-link-generator.php',
                    'name'      => esc_html__( 'Preview Link Generator', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'quickswish',
                    'location'  => 'quickswish.php',
                    'name'      => esc_html__( 'QuickSwish', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'docus',
                    'location'  => 'docus.php',
                    'name'      => esc_html__( 'Docus – YouTube Video Playlist', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'data-captia',
                    'location'  => 'data-captia.php',
                    'name'      => esc_html__( 'DataCaptia', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'coupon-zen',
                    'location'  => 'coupon-zen.php',
                    'name'      => esc_html__( 'Coupon Zen', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'sirve',
                    'location'  => 'sirve.php',
                    'name'      => esc_html__( 'Sirve – Simple Directory Listing', 'wc-sales-notification' )
                ),

                array(
                    'slug'      => 'ht-social-share',
                    'location'  => 'ht-social-share.php',
                    'name'      => esc_html__( 'HT Social Share', 'wc-sales-notification' )
                ),

            )
        ));


    }

    // Admin Scripts
    public function enqueue_admin_scripts(){

        // wp core styles
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        // wp core scripts
        wp_enqueue_script( 'jquery-ui-dialog' );

        //styles
        wp_enqueue_style( 'wcsales-admin', WC_SALENOTIFICATION_PL_URL . 'admin/assets/css/admin_optionspanel.css', FALSE, WC_SALENOTIFICATION_VERSION );
        
        //scripts
        wp_enqueue_script( 'wcsales-admin', WC_SALENOTIFICATION_PL_URL . 'admin/assets/js/admin_scripts.js', array('jquery'), WC_SALENOTIFICATION_VERSION, TRUE );

        $datalocalize = array(
            'contenttype' => wcsales_get_option( 'notification_content_type','wcsales_settings_tabs', 'actual' ),
        );
        wp_localize_script( 'wcsales-admin', 'admin_wclocalize_data', $datalocalize );

    }

    // Options page Section register
    function admin_get_settings_sections() {
        $sections = array(
            
            array(
                'id'    => 'wcsales_general_tabs',
                'title' => esc_html__( 'General', 'wc-sales-notification' )
            ),

            array(
                'id'    => 'wcsales_settings_tabs',
                'title' => esc_html__( 'Settings', 'wc-sales-notification' )
            ),

            array(
                'id'    => 'wcsales_fakes_data_tabs',
                'title' => esc_html__( 'Manual Data', 'wc-sales-notification' )
            ),

            array(
                'id'    => 'wcsales_style_tabs',
                'title' => esc_html__( 'Style', 'wc-sales-notification' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function admin_fields_settings() {

        $settings_fields = array(

            'wcsales_general_tabs' => array(),
            
            'wcsales_settings_tabs' => array(

                array(
                    'name'  => 'enableresalenotification',
                    'label'  => esc_html__( 'Enable / Disable Sale Notification', 'wc-sales-notification' ),
                    'desc'  => esc_html__( 'Enable', 'wc-sales-notification' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'    => 'notification_content_type',
                    'label'   => esc_html__( 'Notification Content Type', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'Select Content Type', 'wc-sales-notification' ),
                    'type'    => 'radio',
                    'default' => 'actual',
                    'options' => array(
                        'actual' => esc_html__('Real','wc-sales-notification'),
                        'fakes'  => esc_html__('Manual','wc-sales-notification'),
                    )
                ),

                array(
                    'name'    => 'notification_randomize_order',
                    'label'   => esc_html__( 'Randomize Notification', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'Display notifications in a randomized order for your fake data.', 'wc-sales-notification' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                ),

                array(
                    'name'    => 'notification_pos',
                    'label'   => esc_html__( 'Position', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'Sale Notification Position on frontend.', 'wc-sales-notification' ),
                    'type'    => 'select',
                    'default' => 'bottomleft',
                    'options' => array(
                        'topleft'       =>esc_html__( 'Top Left','wc-sales-notification' ),
                        'topright'      =>esc_html__( 'Top Right','wc-sales-notification' ),
                        'bottomleft'    =>esc_html__( 'Bottom Left','wc-sales-notification' ),
                        'bottomright'   =>esc_html__( 'Bottom Right','wc-sales-notification' ),
                    ),
                ),

                array(
                    'name'    => 'notification_layout',
                    'label'   => esc_html__( 'Image Position', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'Notification Layout.', 'wc-sales-notification' ),
                    'type'    => 'select',
                    'default' => 'imageleft',
                    'options' => array(
                        'imageleft'       =>esc_html__( 'Image Left','wc-sales-notification' ),
                        'imageright'      =>esc_html__( 'Image Right','wc-sales-notification' ),
                    )
                ),

                array(
                    'name'    => 'notification_timing_area_title',
                    'headding'=> esc_html__( 'Notification Timing', 'wc-sales-notification' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'element_section_title_area',
                ),

                array(
                    'name'    => 'notification_loadduration',
                    'label'   => esc_html__( 'First loading time', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'When to start notification load duration.', 'wc-sales-notification' ),
                    'type'    => 'select',
                    'default' => '3',
                    'options' => array(
                        '2'       =>esc_html__( '2 seconds','wc-sales-notification' ),
                        '3'       =>esc_html__( '3 seconds','wc-sales-notification' ),
                        '4'       =>esc_html__( '4 seconds','wc-sales-notification' ),
                        '5'       =>esc_html__( '5 seconds','wc-sales-notification' ),
                        '6'       =>esc_html__( '6 seconds','wc-sales-notification' ),
                        '7'       =>esc_html__( '7 seconds','wc-sales-notification' ),
                        '8'       =>esc_html__( '8 seconds','wc-sales-notification' ),
                        '9'       =>esc_html__( '9 seconds','wc-sales-notification' ),
                        '10'       =>esc_html__( '10 seconds','wc-sales-notification' ),
                        '20'       =>esc_html__( '20 seconds','wc-sales-notification' ),
                        '30'       =>esc_html__( '30 seconds','wc-sales-notification' ),
                        '40'       =>esc_html__( '40 seconds','wc-sales-notification' ),
                        '50'       =>esc_html__( '50 seconds','wc-sales-notification' ),
                        '60'       =>esc_html__( '1 minute','wc-sales-notification' ),
                        '90'       =>esc_html__( '1.5 minutes','wc-sales-notification' ),
                        '120'       =>esc_html__( '2 minutes','wc-sales-notification' ),
                    ),
                ),

                array(
                    'name'    => 'notification_time_showing',
                    'label'   => esc_html__( 'Notification showing time', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'How long to keep the notification.', 'wc-sales-notification' ),
                    'type'    => 'select',
                    'default' => '4',
                    'options' => array(
                        '2'       =>esc_html__( '2 seconds','wc-sales-notification' ),
                        '4'       =>esc_html__( '4 seconds','wc-sales-notification' ),
                        '5'       =>esc_html__( '5 seconds','wc-sales-notification' ),
                        '6'       =>esc_html__( '6 seconds','wc-sales-notification' ),
                        '7'       =>esc_html__( '7 seconds','wc-sales-notification' ),
                        '8'       =>esc_html__( '8 seconds','wc-sales-notification' ),
                        '9'       =>esc_html__( '9 seconds','wc-sales-notification' ),
                        '10'       =>esc_html__( '10 seconds','wc-sales-notification' ),
                        '20'       =>esc_html__( '20 seconds','wc-sales-notification' ),
                        '30'       =>esc_html__( '30 seconds','wc-sales-notification' ),
                        '40'       =>esc_html__( '40 seconds','wc-sales-notification' ),
                        '50'       =>esc_html__( '50 seconds','wc-sales-notification' ),
                        '60'       =>esc_html__( '1 minute','wc-sales-notification' ),
                        '90'       =>esc_html__( '1.5 minutes','wc-sales-notification' ),
                        '120'       =>esc_html__( '2 minutes','wc-sales-notification' ),
                    ),
                ),

                array(
                    'name'    => 'notification_time_int',
                    'label'   => esc_html__( 'Time Interval', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'Time between notifications.', 'wc-sales-notification' ),
                    'type'    => 'select',
                    'default' => '4',
                    'options' => array(
                        '2'       =>esc_html__( '2 seconds','wc-sales-notification' ),
                        '4'       =>esc_html__( '4 seconds','wc-sales-notification' ),
                        '5'       =>esc_html__( '5 seconds','wc-sales-notification' ),
                        '6'       =>esc_html__( '6 seconds','wc-sales-notification' ),
                        '7'       =>esc_html__( '7 seconds','wc-sales-notification' ),
                        '8'       =>esc_html__( '8 seconds','wc-sales-notification' ),
                        '9'       =>esc_html__( '9 seconds','wc-sales-notification' ),
                        '10'       =>esc_html__( '10 seconds','wc-sales-notification' ),
                        '20'       =>esc_html__( '20 seconds','wc-sales-notification' ),
                        '30'       =>esc_html__( '30 seconds','wc-sales-notification' ),
                        '40'       =>esc_html__( '40 seconds','wc-sales-notification' ),
                        '50'       =>esc_html__( '50 seconds','wc-sales-notification' ),
                        '60'       =>esc_html__( '1 minute','wc-sales-notification' ),
                        '90'       =>esc_html__( '1.5 minutes','wc-sales-notification' ),
                        '120'       =>esc_html__( '2 minutes','wc-sales-notification' ),
                    ),
                ),

                array(
                    'name'    => 'notification_product_display_option_title',
                    'headding'=> esc_html__( 'Product Query Option', 'wc-sales-notification' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'element_section_title_area notification_real',
                ),

                array(
                    'name'              => 'notification_limit',
                    'label'             => esc_html__( 'Limit', 'wc-sales-notification' ),
                    'desc'              => esc_html__( 'Order Limit for notification.', 'wc-sales-notification' ),
                    'min'               => 1,
                    'max'               => 100,
                    'default'           => '5',
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'number',
                    'class'       => 'notification_real',
                ),

                array(
                    'name'  => 'showallproduct',
                    'label'  => esc_html__( 'Show/Display all products from each order', 'wc-sales-notification' ),
                    'desc'  => esc_html__( 'Enable', 'wc-sales-notification' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'notification_real',
                ),

                array(
                    'name'    => 'notification_uptodate',
                    'label'   => esc_html__( 'Order Upto', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'Do not show purchases older than.', 'wc-sales-notification' ),
                    'type'    => 'select',
                    'default' => '7',
                    'options' => array(
                        '1'   =>esc_html__( '1 day','wc-sales-notification' ),
                        '2'   =>esc_html__( '2 days','wc-sales-notification' ),
                        '3'   =>esc_html__( '3 days','wc-sales-notification' ),
                        '4'   =>esc_html__( '4 days','wc-sales-notification' ),
                        '5'   =>esc_html__( '5 days','wc-sales-notification' ),
                        '6'   =>esc_html__( '6 days','wc-sales-notification' ),
                        '7'   =>esc_html__( '1 week','wc-sales-notification' ),
                        '10'  =>esc_html__( '10 days','wc-sales-notification' ),
                        '14'  =>esc_html__( '2 weeks','wc-sales-notification' ),
                        '21'  =>esc_html__( '3 weeks','wc-sales-notification' ),
                        '28'  =>esc_html__( '4 weeks','wc-sales-notification' ),
                        '35'  =>esc_html__( '5 weeks','wc-sales-notification' ),
                        '42'  =>esc_html__( '6 weeks','wc-sales-notification' ),
                        '49'  =>esc_html__( '7 weeks','wc-sales-notification' ),
                        '56'  =>esc_html__( '8 weeks','wc-sales-notification' ),
                    ),
                    'class'       => 'notification_real',
                ),

                array(
                    'name'    => 'notification_display_item_option_title',
                    'headding'=> esc_html__( 'Display Item & Custom Label', 'wc-sales-notification' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'condition' => array( 'notification_content_type', '==', 'actual' ),
                    'class'   => 'element_section_title_area',
                ),
                array(
                    'name'  => 'notification_show_city',
                    'label' => esc_html__( 'Show City', 'wc-sales-notification' ),
                    'desc'  => esc_html__( 'You can display / hide city from here.', 'wc-sales-notification' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'condition' => array( 'notification_content_type', '==', 'actual' ),
                    'class'   => 'woolentor-action-field-left',
                ),
                array(
                    'name'  => 'notification_show_state',
                    'label' => esc_html__( 'Show State', 'wc-sales-notification' ),
                    'desc'  => esc_html__( 'You can display / hide state from here.', 'wc-sales-notification' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'condition' => array( 'notification_content_type', '==', 'actual' ),
                    'class'   => 'woolentor-action-field-left',
                ),
                array(
                    'name'  => 'notification_show_country',
                    'label' => esc_html__( 'Show Country', 'wc-sales-notification' ),
                    'desc'  => esc_html__( 'You can display / hide country from here.', 'wc-sales-notification' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'condition' => array( 'notification_content_type', '==', 'actual' ),
                    'class'   => 'woolentor-action-field-left',
                ),

                array(
                    'name'        => 'notification_purchased_by',
                    'label'       => esc_html__( 'Purchased By Label', 'wc-sales-notification' ),
                    'desc'        => esc_html__( 'Custom label for "By" text.', 'wc-sales-notification' ),
                    'type'        => 'text',
                    'default'     => esc_html__( 'By : ', 'wc-sales-notification' ),
                    'placeholder' => esc_html__( 'By : ', 'wc-sales-notification' ),
                    'class'       => 'woolentor-action-field-left'
                ),
                array(
                    'name'        => 'notification_price_prefix',
                    'label'       => esc_html__( 'Price Label', 'wc-sales-notification' ),
                    'desc'        => esc_html__( 'Custom label for "Price" text.', 'wc-sales-notification' ),
                    'type'        => 'text',
                    'default'     => esc_html__( 'Price :', 'wc-sales-notification' ),
                    'placeholder' => esc_html__( 'Price :', 'wc-sales-notification' ),
                    'class'       => 'woolentor-action-field-left'
                ),

                array(
                    'name'    => 'notification_animation_area_title',
                    'headding'=> esc_html__( 'Animation', 'wc-sales-notification' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'element_section_title_area',
                ),

                array(
                    'name'    => 'notification_inanimation',
                    'label'   => esc_html__( 'Animation In', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'Notification Enter Animation.', 'wc-sales-notification' ),
                    'type'    => 'select',
                    'default' => 'fadeInLeft',
                    'options' => array(
                        'bounce'   =>esc_html__( 'bounce','wc-sales-notification' ),
                        'flash'   =>esc_html__( 'flash','wc-sales-notification' ),
                        'pulse'   =>esc_html__( 'pulse','wc-sales-notification' ),
                        'rubberBand'   =>esc_html__( 'rubberBand','wc-sales-notification' ),
                        'shake'   =>esc_html__( 'shake','wc-sales-notification' ),
                        'swing'   =>esc_html__( 'swing','wc-sales-notification' ),
                        'tada'   =>esc_html__( 'tada','wc-sales-notification' ),
                        'wobble'  =>esc_html__( 'wobble','wc-sales-notification' ),
                        'jello'  =>esc_html__( 'jello','wc-sales-notification' ),
                        'heartBeat'  =>esc_html__( 'heartBeat','wc-sales-notification' ),
                        'bounceIn'  =>esc_html__( 'bounceIn','wc-sales-notification' ),
                        'bounceInDown'  =>esc_html__( 'bounceInDown','wc-sales-notification' ),
                        'bounceInLeft'  =>esc_html__( 'bounceInLeft','wc-sales-notification' ),
                        'bounceInRight'  =>esc_html__( 'bounceInRight','wc-sales-notification' ),
                        'bounceInUp'  =>esc_html__( 'bounceInUp','wc-sales-notification' ),
                        'fadeIn'  =>esc_html__( 'fadeIn','wc-sales-notification' ),
                        'fadeInDown'  =>esc_html__( 'fadeInDown','wc-sales-notification' ),
                        'fadeInDownBig'  =>esc_html__( 'fadeInDownBig','wc-sales-notification' ),
                        'fadeInLeft'  =>esc_html__( 'fadeInLeft','wc-sales-notification' ),
                        'fadeInLeftBig'  =>esc_html__( 'fadeInLeftBig','wc-sales-notification' ),
                        'fadeInRight'  =>esc_html__( 'fadeInRight','wc-sales-notification' ),
                        'fadeInRightBig'  =>esc_html__( 'fadeInRightBig','wc-sales-notification' ),
                        'fadeInUp'  =>esc_html__( 'fadeInUp','wc-sales-notification' ),
                        'fadeInUpBig'  =>esc_html__( 'fadeInUpBig','wc-sales-notification' ),
                        'flip'  =>esc_html__( 'flip','wc-sales-notification' ),
                        'flipInX'  =>esc_html__( 'flipInX','wc-sales-notification' ),
                        'flipInY'  =>esc_html__( 'flipInY','wc-sales-notification' ),
                        'lightSpeedIn'  =>esc_html__( 'lightSpeedIn','wc-sales-notification' ),
                        'rotateIn'  =>esc_html__( 'rotateIn','wc-sales-notification' ),
                        'rotateInDownLeft'  =>esc_html__( 'rotateInDownLeft','wc-sales-notification' ),
                        'rotateInDownRight'  =>esc_html__( 'rotateInDownRight','wc-sales-notification' ),
                        'rotateInUpLeft'  =>esc_html__( 'rotateInUpLeft','wc-sales-notification' ),
                        'rotateInUpRight'  =>esc_html__( 'rotateInUpRight','wc-sales-notification' ),
                        'slideInUp'  =>esc_html__( 'slideInUp','wc-sales-notification' ),
                        'slideInDown'  =>esc_html__( 'slideInDown','wc-sales-notification' ),
                        'slideInLeft'  =>esc_html__( 'slideInLeft','wc-sales-notification' ),
                        'slideInRight'  =>esc_html__( 'slideInRight','wc-sales-notification' ),
                        'zoomIn'  =>esc_html__( 'zoomIn','wc-sales-notification' ),
                        'zoomInDown'  =>esc_html__( 'zoomInDown','wc-sales-notification' ),
                        'zoomInLeft'  =>esc_html__( 'zoomInLeft','wc-sales-notification' ),
                        'zoomInRight'  =>esc_html__( 'zoomInRight','wc-sales-notification' ),
                        'zoomInUp'  =>esc_html__( 'zoomInUp','wc-sales-notification' ),
                        'hinge'  =>esc_html__( 'hinge','wc-sales-notification' ),
                        'jackInTheBox'  =>esc_html__( 'jackInTheBox','wc-sales-notification' ),
                        'rollIn'  =>esc_html__( 'rollIn','wc-sales-notification' ),
                        'rollOut'  =>esc_html__( 'rollOut','wc-sales-notification' ),
                    ),
                ),

                array(
                    'name'    => 'notification_outanimation',
                    'label'   => esc_html__( 'Animation Out', 'wc-sales-notification' ),
                    'desc'    => esc_html__( 'Notification Out Animation.', 'wc-sales-notification' ),
                    'type'    => 'select',
                    'default' => 'fadeOutRight',
                    'options' => array(
                        'bounce'   =>esc_html__( 'bounce','wc-sales-notification' ),
                        'flash'   =>esc_html__( 'flash','wc-sales-notification' ),
                        'pulse'   =>esc_html__( 'pulse','wc-sales-notification' ),
                        'rubberBand'   =>esc_html__( 'rubberBand','wc-sales-notification' ),
                        'shake'   =>esc_html__( 'shake','wc-sales-notification' ),
                        'swing'   =>esc_html__( 'swing','wc-sales-notification' ),
                        'tada'   =>esc_html__( 'tada','wc-sales-notification' ),
                        'wobble'  =>esc_html__( 'wobble','wc-sales-notification' ),
                        'jello'  =>esc_html__( 'jello','wc-sales-notification' ),
                        'heartBeat'  =>esc_html__( 'heartBeat','wc-sales-notification' ),
                        'bounceOut'  =>esc_html__( 'bounceOut','wc-sales-notification' ),
                        'bounceOutDown'  =>esc_html__( 'bounceOutDown','wc-sales-notification' ),
                        'bounceOutLeft'  =>esc_html__( 'bounceOutLeft','wc-sales-notification' ),
                        'bounceOutRight'  =>esc_html__( 'bounceOutRight','wc-sales-notification' ),
                        'bounceOutUp'  =>esc_html__( 'bounceOutUp','wc-sales-notification' ),
                        'fadeOut'  =>esc_html__( 'fadeOut','wc-sales-notification' ),
                        'fadeOutDown'  =>esc_html__( 'fadeOutDown','wc-sales-notification' ),
                        'fadeOutDownBig'  =>esc_html__( 'fadeOutDownBig','wc-sales-notification' ),
                        'fadeOutLeft'  =>esc_html__( 'fadeOutLeft','wc-sales-notification' ),
                        'fadeOutLeftBig'  =>esc_html__( 'fadeOutLeftBig','wc-sales-notification' ),
                        'fadeOutRight'  =>esc_html__( 'fadeOutRight','wc-sales-notification' ),
                        'fadeOutRightBig'  =>esc_html__( 'fadeOutRightBig','wc-sales-notification' ),
                        'fadeOutUp'  =>esc_html__( 'fadeOutUp','wc-sales-notification' ),
                        'fadeOutUpBig'  =>esc_html__( 'fadeOutUpBig','wc-sales-notification' ),
                        'flip'  =>esc_html__( 'flip','wc-sales-notification' ),
                        'flipOutX'  =>esc_html__( 'flipOutX','wc-sales-notification' ),
                        'flipOutY'  =>esc_html__( 'flipOutY','wc-sales-notification' ),
                        'lightSpeedOut'  =>esc_html__( 'lightSpeedOut','wc-sales-notification' ),
                        'rotateOut'  =>esc_html__( 'rotateOut','wc-sales-notification' ),
                        'rotateOutDownLeft'  =>esc_html__( 'rotateOutDownLeft','wc-sales-notification' ),
                        'rotateOutDownRight'  =>esc_html__( 'rotateOutDownRight','wc-sales-notification' ),
                        'rotateOutUpLeft'  =>esc_html__( 'rotateOutUpLeft','wc-sales-notification' ),
                        'rotateOutUpRight'  =>esc_html__( 'rotateOutUpRight','wc-sales-notification' ),
                        'slideOutUp'  =>esc_html__( 'slideOutUp','wc-sales-notification' ),
                        'slideOutDown'  =>esc_html__( 'slideOutDown','wc-sales-notification' ),
                        'slideOutLeft'  =>esc_html__( 'slideOutLeft','wc-sales-notification' ),
                        'slideOutRight'  =>esc_html__( 'slideOutRight','wc-sales-notification' ),
                        'zoomOut'  =>esc_html__( 'zoomOut','wc-sales-notification' ),
                        'zoomOutDown'  =>esc_html__( 'zoomOutDown','wc-sales-notification' ),
                        'zoomOutLeft'  =>esc_html__( 'zoomOutLeft','wc-sales-notification' ),
                        'zoomOutRight'  =>esc_html__( 'zoomOutRight','wc-sales-notification' ),
                        'zoomOutUp'  =>esc_html__( 'zoomOutUp','wc-sales-notification' ),
                        'hinge'  =>esc_html__( 'hinge','wc-sales-notification' ),
                    ),
                ),

            ),

            'wcsales_fakes_data_tabs' => array(),
            'wcsales_style_tabs' => array(

                array(
                    'name'        => 'notification_width',
                    'label'       => esc_html__( 'Width', 'wc-sales-notification' ),
                    'desc'        => esc_html__( 'You can handle the notificaton width.', 'wc-sales-notification' ),
                    'type'        => 'text',
                    'default'     => esc_html__( '550px', 'wc-sales-notification' ),
                    'placeholder' => esc_html__( '550px', 'wc-sales-notification' ),
                ),

                array(
                    'name'        => 'notification_mobile_width',
                    'label'       => esc_html__( 'Width for mobile', 'wc-sales-notification' ),
                    'desc'        => esc_html__( 'You can handle the notificaton width.', 'wc-sales-notification' ),
                    'type'        => 'text',
                    'default'     => esc_html__( '90%', 'wc-sales-notification' ),
                    'placeholder' => esc_html__( '90%', 'wc-sales-notification' ),
                ),

                array(
                    'name'    => 'notification_color_area_title',
                    'headding'=> esc_html__( 'Color', 'wc-sales-notification' ),
                    'type'    => 'title',
                    'size'    => 'margin_0 regular',
                    'class' => 'element_section_title_area',
                ),

                array(
                    'name'  => 'background_color',
                    'label' => esc_html__( 'Background Color', 'wc-sales-notification' ),
                    'desc' => wp_kses_post( 'Notification Background Color.', 'wc-sales-notification' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'heading_color',
                    'label' => esc_html__( 'Heading Color', 'wc-sales-notification' ),
                    'desc' => wp_kses_post( 'Notification Heading Color.', 'wc-sales-notification' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'content_color',
                    'label' => esc_html__( 'Content Color', 'wc-sales-notification' ),
                    'desc' => wp_kses_post( 'Notification Content Color.', 'wc-sales-notification' ),
                    'type' => 'color',
                ),

                array(
                    'name'  => 'cross_color',
                    'label' => esc_html__( 'Cross Icon Color', 'wc-sales-notification' ),
                    'desc' => wp_kses_post( 'Notification Cross Icon Color.', 'wc-sales-notification' ),
                    'type' => 'color'
                ),

            ),

        );
        
        return array_merge( $settings_fields );
    }

    // Admin Menu Page Render
    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'WC Sales Notification Settings','wc-sales-notification' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';

    }

    // Save Options Message
    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice is-dismissible"> 
                <p><strong><?php esc_html_e('Successfully Settings Saved.', 'wc-sales-notification') ?></strong></p>
            </div>
            <?php
        }
    }

    function html_fake_data_tabs(){
        ob_start();

        $fakadata = array();
        $fake_title = ( !empty( get_option( 'fake_title' ) ) ? get_option( 'fake_title' ) : array() );
        $fake_price = get_option( 'fake_price' );
        $fake_buyer = get_option( 'fake_buyer' );
        $fake_desc  = get_option( 'fake_description' );
        $fake_image = get_option( 'fake_image' );
        $count      = count( $fake_title );

        for ( $i = 0; $i < $count; $i++ ) {
            if ( $fake_title[$i] != '' ){
                $fakadata[$i]['fake_title'] = $fake_title[$i];
                $fakadata[$i]['fake_price'] = $fake_price[$i];
                $fakadata[$i]['fake_buyer'] = $fake_buyer[$i];
                $fakadata[$i]['fake_description'] = $fake_desc[$i];
                $fakadata[$i]['fake_image'] = $fake_image[$i];
            }
        }

        ?>
            <table id="htrepeatable-fieldset" class="htopt_meta_box_table" width="100%">
                <thead>
                    <tr>
                        <th><?php echo esc_html__( "Title", 'wc-sales-notification' );?></th>
                        <th><?php echo esc_html__( "Price", 'wc-sales-notification' );?></th>
                        <th><?php echo esc_html__( "Buyer", 'wc-sales-notification' );?></th>
                        <th><?php echo esc_html__( "Description", 'wc-sales-notification' );?></th>
                        <th><?php echo esc_html__( "Image", 'wc-sales-notification' );?></th>
                        <th><?php echo esc_html__( "Action", 'wc-sales-notification' );?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if ( $fakadata ) :
                        foreach ( $fakadata as $datainfo ) {
                            ?>
                            <tr>
                                <td>
                                    <input type="text" placeholder="<?php echo esc_attr__( 'Enter Title', 'wc-sales-notification' ); ?>" value="<?php echo $datainfo['fake_title'] ? esc_attr($datainfo['fake_title']) : ''; ?>" name="fake_title[]" />
                                </td>
                                <td>
                                    <input type="text" placeholder="<?php echo esc_attr__( 'Enter Price', 'wc-sales-notification' ); ?>" value="<?php echo $datainfo['fake_price'] ? esc_attr($datainfo['fake_price']) : ''; ?>" name="fake_price[]" />
                                </td>
                                <td>
                                    <input type="text" placeholder="<?php echo esc_attr__( 'Enter Buyer Name', 'wc-sales-notification' ); ?>" value="<?php echo $datainfo['fake_buyer'] ? esc_attr($datainfo['fake_buyer']) : ''; ?>" name="fake_buyer[]" />
                                </td>
                                <td>
                                    <textarea name="fake_description[]" placeholder="<?php echo esc_attr__( 'Enter Description', 'wc-sales-notification' ); ?>"><?php echo $datainfo['fake_description'] ? esc_attr($datainfo['fake_description']) : ''; ?></textarea>
                                </td>
                                <td>
                                    <div class="htmedia_display">
                                        <?php
                                            if( !empty( $datainfo['fake_image'] ) ){
                                                echo '<img src="'.esc_url( $datainfo['fake_image'] ).'" alt="'.esc_attr( $datainfo['fake_title'] ).'">';
                                            }else{
                                                echo '<img src="'.WC_SALENOTIFICATION_PL_URL.'/admin/assets/images/fake_data_placeholder.png" alt="'.esc_attr( $datainfo['fake_title'] ).'">';
                                            }
                                        ?>
                                    </div>
                                    <div class="wcsale_fake_input_box">
                                        <input type="hidden" class="wpsa-url" name="fake_image[]" value="<?php echo $datainfo['fake_image'] ? esc_attr($datainfo['fake_image']) : ''; ?>" />
                                        <input type="button" class="button wpsa-browse" value="<?php echo esc_attr__( 'Upload Image', 'wc-sales-notification' ); ?>" />
                                        <input type="button" class="button wpsa-remove" value="<?php echo esc_attr__( 'Remove Image', 'wc-sales-notification' ); ?>" />
                                    </div>
                                </td>
                                <td width="10%"><a class="button remove-row" href="#1"><?php esc_html_e( 'Remove', 'wc-sales-notification' ); ?></a></td>
                            </tr>
                            <?php
                        }

                    else :
                    // show a blank one
                ?>
                    <tr>
                        <td>
                            <input type="text" placeholder="<?php echo esc_attr__( 'Enter Title', 'wc-sales-notification' ); ?>" name="fake_title[]" />
                        </td>
                        <td>
                            <input type="text" placeholder="<?php echo esc_attr__( 'Enter Price', 'wc-sales-notification' ); ?>" name="fake_price[]" />
                        </td>
                        <td>
                            <input type="text" placeholder="<?php echo esc_attr__( 'Enter Buyer Name', 'wc-sales-notification' ); ?>" name="fake_buyer[]" />
                        </td>
                        <td>
                            <textarea name="fake_description[]" placeholder="<?php echo esc_attr__( 'Enter Description', 'wc-sales-notification' ); ?>"></textarea>
                        </td>
                        <td>
                            <div class="htmedia_display">&nbsp;</div>
                            <div class="wcsale_fake_input_box">
                                <input type="hidden" class="wpsa-url" name="fake_image[]" />
                                <input type="button" class="button wpsa-browse" value="<?php echo esc_attr__( 'Upload Image', 'wc-sales-notification' ); ?>" />
                            </div>
                        </td>
                        <td>
                            <a class="button remove-row button-disabled" href="#">
                                <?php esc_html_e( 'Remove', 'wc-sales-notification' ); ?>
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
                    <!-- empty hidden one for jQuery -->
                    <tr class="empty-row screen-reader-text">
                        <td>
                            <input type="text" placeholder="<?php echo esc_attr__( 'Enter Title', 'wc-sales-notification' ); ?>" name="fake_title[]" />
                        </td>
                        <td>
                            <input type="text" placeholder="<?php echo esc_attr__( 'Enter Price', 'wc-sales-notification' ); ?>" name="fake_price[]" />
                        </td>
                        <td>
                            <input type="text" placeholder="<?php echo esc_attr__( 'Enter Buyer Name', 'wc-sales-notification' ); ?>" name="fake_buyer[]" />
                        </td>
                        <td>
                            <textarea name="fake_description[]" placeholder="<?php echo esc_attr__( 'Enter Description', 'wc-sales-notification' ); ?>"></textarea>
                        </td>
                        <td>
                            <div class="htmedia_display">&nbsp;</div>
                            <div class="wcsale_fake_input_box">
                                <input type="hidden" class="wpsa-url" name="fake_image[]" />
                                <input type="button" class="button wpsa-browse" value="<?php echo esc_attr__( 'Upload Image', 'wc-sales-notification' ); ?>" />
                            </div>
                        </td>
                        <td><a class="button remove-row" href="#"><?php esc_html_e( 'Remove', 'wc-sales-notification' ); ?></a></td>
                    </tr>

                </tbody>
            </table>
            <p style="text-align:right;"><a id="add-row" class="button" href="#"><?php esc_html_e( 'Add Another', 'wc-sales-notification' ); ?></a></p>

        <?php
        echo ob_get_clean();
    }

    // Setting Fileds Register
    function register_setting_fileds() {
        register_setting( 'wcsales_fakes_data_tabs', 'fake_title' );
        register_setting( 'wcsales_fakes_data_tabs', 'fake_price' );
        register_setting( 'wcsales_fakes_data_tabs', 'fake_buyer' );
        register_setting( 'wcsales_fakes_data_tabs', 'fake_description' );
        register_setting( 'wcsales_fakes_data_tabs', 'fake_image' );
    }

    // General tab
    function html_general_tabs(){
        ob_start();
        ?>
            <div class="wcsales-general-tabs">

                <div class="wcsales-document-section">
                    <div class="wcsales-column">
                        <a href="https://hasthemes.com/" target="_blank">
                            <img src="<?php echo WC_SALENOTIFICATION_PL_URL; ?>/admin/assets/images/video-tutorial.jpg" alt="<?php esc_attr_e( 'Video Tutorial', 'wc-sales-notification' ); ?>">
                        </a>
                    </div>
                    <div class="wcsales-column">
                        <a href="https://hasthemes.com/" target="_blank">
                            <img src="<?php echo WC_SALENOTIFICATION_PL_URL; ?>/admin/assets/images/online-documentation.jpg" alt="<?php esc_attr_e( 'Online Documentation', 'wc-sales-notification' ); ?>">
                        </a>
                    </div>
                    <div class="wcsales-column">
                        <a href="https://hasthemes.com/contact-us/" target="_blank">
                            <img src="<?php echo WC_SALENOTIFICATION_PL_URL; ?>/admin/assets/images/contact-us.jpg" alt="<?php esc_attr_e( 'Contact Us', 'wc-sales-notification' ); ?>">
                        </a>
                    </div>
                </div>

            </div>
        <?php
        echo ob_get_clean();
    }
    
}

new WC_Sale_Notification_Admin_Settings();