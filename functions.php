<?php

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function theme_enqueue_styles()
{
    wp_enqueue_style('divi-style', get_template_directory_uri() . '/style.css');
}

if (is_admin()) {
    include_once get_stylesheet_directory() . '/class.mrkwp.plugin.dependency.php';
    $dependency_checker = new MRKWP_Plugin_Dependency();

    $plugins = [
        [
            'name'     => 'One Click Demo Import',
            'slug'     => 'one-click-demo-import/one-click-demo-import.php',
            'url'      => 'https://wordpress.org/plugins/one-click-demo-import/',
            'trial' => false,
        ],
        [
            'name'     => 'WooCommerce',
            'slug'     => 'woocommerce/woocommerce.php',
            'url'      => 'https://wordpress.org/plugins/woocommerce',
            'trial' => false,
        ],
        [
            'name'     => 'Yoast SEO',
            'slug'     => 'wordpress-seo/wp-seo.php',
            'url'      => 'https://wordpress.org/plugins/wordpress-seo',
            'trial' => false,
        ],
        [
            'name'     => 'Kirki',
            'slug'     => 'kirki/kirki.php',
            'url'      => 'https://wordpress.org/plugins/kirki',
            'trial' => false,
        ],

        [
            'name'     => 'WooCommerce Colours For Divi',
            'slug'     => 'df-divi-woocommerce-tweaks/df-divi-woocommerce-tweaks.php',
            'url'   => 'https://github.com/MRKWP/df-divi-woocommerce-tweaks',
            'trial' => false,
        ],
        [
            'name'     => 'Extra Icons Plugin for Divi',
            'slug'     => 'mrkwp-extra-icons-for-divi/mrkwp-extra-icons-for-divi.php',
            'url'      => 'https://wordpress.org/plugins/mrkwp-extra-icons-for-divi',
            'trial' => false,
        ],
        [
            'name'     => 'FAQ Plugin',
            'slug'     => 'divi-framework-faq-premium/divi-framework-faq.php',
            'url'      => 'https://www.mrkwp.com/wp/faq-plugin/',
            'trial' => true,
        ],
        [
            'name'     => 'Testimonials for Divi',
            'slug'     => 'df-testimonials-premium/df-testimonials.php',
            'url'      => 'https://www.mrkwp.com/wp/testimonials-plugin/',
            'trial' => true,
        ],
        [
            'name'     => 'Footer Plugin for Divi',
            'slug'     => 'mrkwp-footer-for-divi/mrkwp-footer-for-divi.php',
            'url'      => 'https://wordpress.org/plugins/mrkwp-footer-for-divi/',
            'trial' => false,
        ],
        [
            'name'     => 'Gravityforms',
            'slug'     => 'gravityforms/gravityforms.php',
            'url'      => 'https://www.gravityforms.com',
            'trial' => false,
        ],
        [
            'name'     => 'Form Styler For Gravity Forms And Divi',
            'slug'     => 'df-gravityforms-divi-plugin-premium/df-gravityforms-divi-plugin.php',
            'url'      => 'https://www.mrkwp.com/wp/gravity-forms-divi-plugin/',
            'trial' => true,
        ],
        [
            'name'     => 'Breadcrumbs For Divi',
            'slug'     => 'divi-framework-breadcrumbs-premium/divi-framework-breadcrumbs.php',
            'url'      => 'https://www.mrkwp.com/wp/divi-breadcrumb-module/',
            'trial' => true,
        ],
    ];

    foreach ($plugins as $plugin) {
        if (!$dependency_checker->is_plugin_active($plugin['slug'])) {
            $message = sprintf(
                'Plugin `%s` needs to be installed and activated. Get the plugin from <a target="_blank" href="%s">%s</a>',
                $plugin['name'],
                $plugin['url'],
                $plugin['url']
            );

            if ($plugin['trial']) {
                $message .= ". This plugin has a 7 day free trial!";
            }

            $dependency_checker->add_notification($message);
        }
    }
}

function ocdi_import_files()
{
    return [
        [
            'import_file_name'           => 'Divi Flower Store Import',
            'categories'                 => ['Divi Flower Store Import'],
            'import_file_url'            => get_stylesheet_directory_uri() . '/data/content.xml',
            'import_widget_file_url'     => get_stylesheet_directory_uri() . '/data/widgets.wie',
            'import_customizer_file_url' => get_stylesheet_directory_uri() . '/data/customizer.dat',
            'import_notice'              => __('Please wait for a few minutes. Do not close the window or refresh the page until the data is imported.', 'your_theme_name'),
        ],
    ];
}

add_filter('pt-ocdi/import_files', 'ocdi_import_files');

// Reset the standard WordPress widgets
function ocdi_before_widgets_import($selected_import)
{
    if (!get_option('acme_cleared_widgets')) {
        update_option('sidebars_widgets', []);
        update_option('acme_cleared_widgets', true);
    }
}

add_action('pt-ocdi/before_widgets_import', 'ocdi_before_widgets_import');

function ocdi_after_import_setup()
{
    $main_menu      = get_term_by('name', 'Main Menu', 'nav_menu');
    $secondary_menu = get_term_by('name', 'Secondary Menu', 'nav_menu');
    set_theme_mod(
        'nav_menu_locations', [
            'primary-menu'   => $main_menu->term_id,
            'secondary-menu' => $secondary_menu->term_id,
        ]
    );

    // Assign home page and posts page (blog page).
    $front_page_id = get_page_by_title('Home');
    update_option('show_on_front', 'page');
    update_option('page_on_front', $front_page_id->ID);

    $et_divi = json_decode('{"divi_2_4_documentation_message":"triggered","divi_1_3_images":"checked","link_color":"#36b1bf","header_color":"#f2385a","accent_color":"#f2385a","vertical_nav":false,"nav_fullwidth":false,"hide_primary_logo":false,"menu_link_active":"#f2385a","primary_nav_dropdown_line_color":"#f2385a","color_schemes":"none","show_header_social_icons":true,"show_search_icon":false,"phone_number":"02 1111 1111","header_email":"divi-flowerstore@example.com","header_style":"centered","divi_logo":"\/wp-content\/uploads\/2018\/07\/divi-flowerstore-logo.png","divi_favicon":"\/wp-content\/uploads\/2015\/09\/favicon.png","divi_fixed_nav":"on","divi_grab_image":"false","divi_blog_style":"false","divi_shop_page_sidebar":"et_right_sidebar","divi_mailchimp_api_key":"","divi_regenerate_mailchimp_lists":"false","divi_regenerate_aweber_lists":"false","divi_show_facebook_icon":"false","divi_show_twitter_icon":"false","divi_show_google_icon":"false","divi_show_rss_icon":"false","divi_facebook_url":"https:\/\/www.facebook.com\/","divi_twitter_url":"#","divi_google_url":"#","divi_rss_url":"","divi_catnum_posts":6,"divi_archivenum_posts":5,"divi_searchnum_posts":5,"divi_tagnum_posts":5,"divi_date_format":"M j, Y","divi_use_excerpt":"false","divi_responsive_shortcodes":"on","divi_gf_enable_all_character_sets":"on","divi_back_to_top":"on","divi_smooth_scroll":"false","divi_custom_css":"","divi_enable_dropdowns":"on","divi_home_link":"on","divi_sort_pages":"post_title","divi_order_page":"asc","divi_tiers_shown_pages":3,"divi_enable_dropdowns_categories":"on","divi_categories_empty":"on","divi_tiers_shown_categories":3,"divi_sort_cat":"name","divi_order_cat":"asc","divi_disable_toptier":"false","divi_postinfo2":["author","date","categories"],"divi_show_postcomments":"false","divi_thumbnails":"on","divi_page_thumbnails":"false","divi_show_pagescomments":"false","divi_postinfo1":["author","date","categories"],"divi_thumbnails_index":"on","divi_seo_home_title":"false","divi_seo_home_description":"false","divi_seo_home_keywords":"false","divi_seo_home_canonical":"false","divi_seo_home_titletext":"","divi_seo_home_descriptiontext":"","divi_seo_home_keywordstext":"","divi_seo_home_type":"BlogName | Blog description","divi_seo_home_separate":" | ","divi_seo_single_title":"false","divi_seo_single_description":"false","divi_seo_single_keywords":"false","divi_seo_single_canonical":"false","divi_seo_single_field_title":"seo_title","divi_seo_single_field_description":"seo_description","divi_seo_single_field_keywords":"seo_keywords","divi_seo_single_type":"Post title | BlogName","divi_seo_single_separate":" | ","divi_seo_index_canonical":"false","divi_seo_index_description":"false","divi_seo_index_type":"Category name | BlogName","divi_seo_index_separate":" | ","divi_integrate_header_enable":"on","divi_integrate_body_enable":"on","divi_integrate_singletop_enable":"on","divi_integrate_singlebottom_enable":"on","divi_integration_head":"","divi_integration_body":"","divi_integration_single_top":"","divi_integration_single_bottom":"","divi_468_enable":"false","divi_468_image":"","divi_468_url":"","divi_468_adsense":"","menu_height":"99","logo_height":"80","primary_nav_font_spacing":0,"primary_nav_font_style":"","2_5_flush_rewrite_rules":"done","divi_gallery_layout_enable":"false","divi_color_palette":"#000000|#ffffff|#f2385a|#5e3e00|#36b1bf|#ffffff|#ffffff|#ffffff","divi_woocommerce_archive_num_posts":9,"divi_disable_translations":"false","divi_scroll_to_anchor_fix":"false","body_font_size":16,"body_font_height":1.8,"body_header_size":36,"body_header_spacing":1,"body_header_height":1.2,"heading_font":"Playfair Display","body_font":"Open Sans","primary_nav_font_size":16,"primary_nav_font":"Open Sans","hide_fixed_logo":true,"secondary_nav_font":"Open Sans","footer_columns":"_1_2__1_4","footer_widget_header_color":"#36b1bf","footer_widget_bullet_color":"#f2385a","widget_header_font_size":20,"widget_header_font_style":"bold","widget_body_font_size":14,"3_0_flush_rewrite_rules_2":"done","et_fb_pref_settings_bar_location":"bottom","et_fb_pref_modal_snap_location":"left","et_fb_pref_modal_snap":"false","et_fb_pref_modal_fullscreen":"false","et_fb_pref_modal_dimension_width":400,"et_fb_pref_modal_dimension_height":400,"et_fb_pref_modal_position_x":30,"et_fb_pref_modal_position_y":50,"divi_email_provider_credentials_migrated":true,"static_css_custom_css_safety_check_done":true,"bottom_bar_social_icon_color":"#f2385a","custom_footer_credits":"Copyright Divi Flower Store 2018 | Designed by <a href=\"https:\/\/www.diviframework.com\/\">Divi Framework<\/a>","divi_skip_font_subset_force":true,"product_tour_status":{"2":"off","4":"off"},"et_pb_layouts_updated":true,"library_removed_legacy_layouts":true,"et_fb_pref_builder_animation":"true","et_fb_pref_builder_display_modal_settings":"false","et_fb_pref_builder_enable_dummy_content":"true","et_fb_pref_event_mode":"hover","et_fb_pref_hide_disabled_modules":"false","et_fb_pref_history_intervals":1,"et_fb_pref_modal_preference":"default","et_fb_pref_toolbar_click":"false","et_fb_pref_toolbar_desktop":"true","et_fb_pref_toolbar_grid":"false","et_fb_pref_toolbar_hover":"false","et_fb_pref_toolbar_phone":"true","et_fb_pref_toolbar_tablet":"true","et_fb_pref_toolbar_wireframe":"true","et_fb_pref_toolbar_zoom":"true","divi_previous_installed_version":"3.12.2","divi_latest_installed_version":"3.13.1","footer_widget_text_color":"#5e5e5e","footer_widget_link_color":"#f2385a","divi_sidebar":"et_right_sidebar","divi_minify_combine_scripts":"on","divi_minify_combine_styles":"on","et_pb_static_css_file":"on","et_pb_css_in_footer":"off","et_pb_product_tour_global":"on","bottom_bar_background_color":"#ffffff","bottom_bar_text_color":"#36b1bf","bottom_bar_social_icon_size":14,"footer_menu_background_color":"#36b1bf","footer_menu_text_color":"#ffffff","footer_menu_active_link_color":"rgba(255,255,255,0.8)","footer_bg":"#f9f9f9","widget_body_font_style":"","et_fb_pref_page_creation_flow":"default","et_pb_post_type_integration":{"page":"on","post":"on","project":"on","product":"on"},"divi_show_fa-instagram_icon":"on","divi_show_fa-youtube-square_icon":"on","divi_show_fa-pinterest_icon":"on","divi_show_fa-linkedin_icon":"false","divi_show_fa-skype_icon":"false","divi_show_fa-flickr_icon":"false","divi_show_fa-dribbble_icon":"false","divi_show_fa-vimeo_icon":"false","divi_show_fa-500px_icon":"false","divi_show_fa-behance_icon":"false","divi_show_fa-github_icon":"false","divi_show_fa-bitbucket_icon":"false","divi_show_fa-deviantart_icon":"false","divi_show_fa-medium_icon":"false","divi_show_fa-meetup_icon":"false","divi_show_fa-slack_icon":"false","divi_show_fa-snapchat_icon":"false","divi_show_fa-tripadvisor_icon":"false","divi_show_fa-twitch_icon":"false","divi_fa-instagram_url":"https:\/\/www.instagram.com\/","divi_fa-youtube-square_url":"https:\/\/www.youtube.com\/","divi_fa-pinterest_url":"https:\/\/pinterest.com","divi_fa-linkedin_url":"#","divi_fa-skype_url":"#","divi_fa-flickr_url":"#","divi_fa-dribbble_url":"#","divi_fa-vimeo_url":"#","divi_fa-500px_url":"#","divi_fa-behance_url":"#","divi_fa-github_url":"#","divi_fa-bitbucket_url":"#","divi_fa-deviantart_url":"#","divi_fa-medium_url":"#","divi_fa-meetup_url":"#","divi_fa-slack_url":"#","divi_fa-snapchat_url":"#","divi_fa-tripadvisor_url":"#","divi_fa-twitch_url":"#","et_pb_clear_templates_cache":true}', true);

    update_option('et_divi', $et_divi);
}

add_action('pt-ocdi/after_import', 'ocdi_after_import_setup');

add_filter('pt-ocdi/disable_pt_branding', '__return_true');

function ocdi_plugin_intro_text($default_text)
{
    $default_text .= '<div class="ocdi__intro-text">One click import of demo data, Divi theme customizer settings and WordPress widgets for the <b>Divi Flower Store Child Theme</b></div>';

    return $default_text;
}

add_filter('pt-ocdi/plugin_intro_text', 'ocdi_plugin_intro_text');