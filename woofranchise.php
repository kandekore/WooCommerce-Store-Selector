<?php
/*
Plugin Name: WooCommerce Store Selector
Plugin URI: https://woostoreselect.wordpresswizard.net/
Description: A plugin to display a store selector popup when adding a product to the cart in WooCommerce.
Version: 1.0.2
Author: Darren Kandekore
Author URI: https://www.darrenk.uk
*/

if (!defined('ABSPATH')) {
    exit;
}



function wss_register_meta_boxes() {
    add_meta_box('wss_metabox', 'Configuration', 'wss_display_callback', 'wss_product');
}
add_action('add_meta_boxes', 'wss_register_meta_boxes');

function wss_display_callback($post) {
    $page_path = get_post_meta($post->ID, '_wss_page_path', true);
    ?>
    <p>
        <label for="wss-page-path-field">Page Path: </label>
        <input type="text" id="wss-page-path-field" name="wss_page_path" value="<?php echo esc_attr($page_path); ?>" size="25" />
    </p>
    <?php
}

function wss_save_meta_box($post_id) {
    if (array_key_exists('wss_page_path', $_POST)) {
        update_post_meta($post_id, '_wss_page_path', $_POST['wss_page_path']);
    }
}
add_action('save_post', 'wss_save_meta_box');

function wss_add_admin_pages() {
    add_menu_page('Store Management', 'Store Management', 'manage_options', 'store-management', 'wss_store_management_page', 'dashicons-store', 20);
    add_submenu_page('store-management', 'Store Locator Settings', 'Store Locator', 'manage_options', 'store-locator-settings', 'wss_store_locator_settings_page');
}
add_action('admin_menu', 'wss_add_admin_pages');

function wss_store_management_page() {
    $stores = get_option('wss_stores', array());
    ?>
    <div class="wrap">
        <h2>WooCommerce Store Management</h2>
        <form method="post" action="options.php">
            <?php settings_fields('wss-store-settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Stores:</th>
                    <td>
                        <?php 
                        $i = 0;
                        foreach ($stores as $store) { ?>
                            <div>
                                <input type="text" name="wss_stores[<?php echo $i; ?>][name]" value="<?php echo esc_attr($store['name']); ?>" placeholder="Store Name" />
                                <input type="text" name="wss_stores[<?php echo $i; ?>][url]" value="<?php echo esc_attr($store['url']); ?>" placeholder="Store URL" />
                            </div>
                        <?php 
                        $i++;
                        } ?>
                        <div>
                            <input type="text" name="wss_stores[<?php echo $i; ?>][name]" value="" placeholder="Store Name" />
                            <input type="text" name="wss_stores[<?php echo $i; ?>][url]" value="" placeholder="Store URL" />
                        </div>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function wss_store_locator_settings_page() {
    $storeLocatorUrl = get_option('wss_locator_url', '');
    ?>
    <div class="wrap">
        <h2>Store Locator Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('wss-store-locator-settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Store Locator URL:</th>
                    <td>
                        <input type="text" name="wss_locator_url" value="<?php echo esc_attr($storeLocatorUrl); ?>" placeholder="Store Locator URL" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function wss_register_settings() {
    register_setting('wss-store-settings', 'wss_stores');
}
add_action('admin_init', 'wss_register_settings');

function wss_register_locator_settings() {
    register_setting('wss-store-locator-settings', 'wss_locator_url');
}
add_action('admin_init', 'wss_register_locator_settings');

function wss_add_to_cart_validation($passed, $product_id, $quantity) {
    if (!$passed) {
        return $passed;
    }

    $product_url = get_permalink($product_id);
    $stores = get_option('wss_stores', array());
    $storeLocatorUrl = get_option('wss_locator_url', '');

    $options_html = '';
    foreach ($stores as $store) {
        $option_value = esc_attr($store['url'] . '/' . str_replace(home_url(), '', $product_url));
        $option_text = esc_html($store['name']);
        $options_html .= "<option value='$option_value'>$option_text</option>";
    }

    ob_start();
    ?>
    <div class="wss-container">
        <div class="wss-overlay"></div>
        <div class="wss-popup">
            <div class="wss-header">
                <h2>Choose a Store</h2>
                <?php if (!empty($storeLocatorUrl)) : ?>
                    <a href="<?php echo esc_url($storeLocatorUrl); ?>" class="store-locator-link">Click here to use the store locator</a>
                <?php endif; ?>
                <span class="wss-close">&times;</span>
            </div>
            <div class="wss-body">
                <select class="wss-store-select">
                    <option value="">Select a Store</option>
                    <?php echo $options_html; ?>
                </select>
                <button class="wss-add-to-cart">Add to Cart</button>
            </div>
        </div>
    </div>
    <?php
    $popup_content = ob_get_clean();

    // Output the popup content directly
    echo $popup_content;

    // Prevent automatic redirection after adding to cart
    remove_action('woocommerce_add_to_cart_redirect', 'wc_redirect_after_add_to_cart', 10);

    return false;
}
add_filter('woocommerce_add_to_cart_validation', 'wss_add_to_cart_validation', 10, 3);

function wss_enqueue_scripts() {
    wp_enqueue_script('wss_script', plugin_dir_url(__FILE__) . 'wss.js', array('jquery'), '1.0', true);
    wp_enqueue_style('wss_style', plugin_dir_url(__FILE__) . 'wss.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'wss_enqueue_scripts');

function wss_display_shortcode($atts) {
    $atts = shortcode_atts(array('id' => 0), $atts, 'wss-display');
    $id = intval($atts['id']);
    $product_url = get_permalink($id);

    $stores = get_option('wss_stores', array());

    $options_html = '';
    foreach ($stores as $store) {
        $option_value = esc_attr($store['url'] . '/' . str_replace(home_url(), '', $product_url));
        $option_text = esc_html($store['name']);
        $options_html .= "<option value='$option_value'>$option_text</option>";
    }

    ob_start();
    ?>
    <div class="wss-container">
        <div class="wss-overlay"></div>
        <div class="wss-popup">
            <div class="wss-header">
                <h2>Choose a Store</h2>
                <?php if (!empty($storeLocatorUrl)) : ?>
                    <a href="<?php echo esc_url($storeLocatorUrl); ?>" class="store-locator-link">Click here to use the store locator</a>
                <?php endif; ?>
                <span class="wss-close">&times;</span>
            </div>
            <div class="wss-body">
                <select class="wss-store-select">
                    <option value="">Select a Store</option>
                    <?php echo $options_html; ?>
                </select>
                <button class="wss-add-to-cart">Add to Cart</button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('wss-display', 'wss_display_shortcode');

function change_add_to_cart_text($text) {
    // Modify the button text as needed
    $text = 'Select Options';

    return $text;
}
add_filter('woocommerce_product_single_add_to_cart_text', 'change_add_to_cart_text');
