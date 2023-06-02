# WooCommerce Store Selector

**Version:** 1.0.2  
**Author:** Darren Kandekore  
**Plugin URI:** [https://woostoreselect.wordpresswizard.net/](https://woostoreselect.wordpresswizard.net/)  
**Author URI:** [https://www.darrenk.uk](https://www.darrenk.uk)

The WooCommerce Store Selector is a WordPress plugin that provides your customers with a store selector popup when they add a product to the cart in your WooCommerce store. This is an ideal plugin for businesses operating in multiple locations, franchises, dropshipping networks, or online shops with different regional branches.

## Features:

- Display a store selector popup on product add to cart.
- Configurable list of stores in the WordPress admin panel.
- Store Locator URL settings for the popup.
- Customize the "Add to Cart" button text.
- Shortcode support to display the store selector.

## Installation:

1. Download the plugin zip file.
2. Login to your WordPress admin panel.
3. Navigate to Plugins > Add New > Upload Plugin.
4. Click Choose File, select the plugin zip file you downloaded and click Install Now.
5. After the plugin is installed, click Activate Plugin.

## Usage:

Once activated, the WooCommerce Store Selector plugin will add new menu items in the WordPress admin panel. You will find `Store Management` and `Add To Cart Text` options for configuring the plugin.

### Store Management:

Here, you can add the name and URL for each of your stores. Click on `Add Store` to add a new store. Click `Save Changes` when you are done.

### Store Locator Settings:

You can specify the URL of your store locator in this section. This will be displayed in the store selection popup, providing users with the option to use the store locator.

### Store Selector Popup:

The Store Selector Popup will automatically appear whenever a customer clicks the 'Add to Cart' button on any of your WooCommerce products. The customer can then select their preferred store before the product is added to the cart.

### Customization:

You can customize the 'Add to Cart' button text from the `Add To Cart Text` menu in the admin panel.

## Shortcodes:

This plugin provides a shortcode to display the store selector manually. You can use this shortcode in your posts, pages, widgets, or directly in your theme files.

Shortcode: `[wss-display id=PRODUCT_ID]`

Replace `PRODUCT_ID` with the ID of your WooCommerce product.

## Important Note:

For the plugin to work effectively, it's crucial that the URL structure of your product pages across all your store domains matches the structure on your root domain. This enables the plugin to correctly generate the URLs for the product pages in each store.

For example, if a product URL on the root domain is `https://rootdomain.com/product/cool-product`, then the equivalent URL on a store domain should be `https://storedomain.com/product/cool-product`. The `/product/cool-product` part of the URL should be consistent across all domains.

## Support:

If you encounter any issues while using this plugin, please don't hesitate to contact us.

## Contributions:

Contributions, issues, and feature requests are welcome. Feel free to open an issue if you find anything worth mentioning or if there is something you'd like to suggest for this plugin.

## License:

This project is licensed under the GNU General Public License v3.0.

## Acknowledgements:

Thanks to WooCommerce for creating a robust e-commerce platform for WordPress, and to the WordPress community for its ongoing development and support.
