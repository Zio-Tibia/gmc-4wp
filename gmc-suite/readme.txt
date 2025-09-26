=== GMC Suite ===
Contributors: Jules
Tags: woocommerce, google, merchant center, widgets, reviews, google customer reviews, store widget
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modular and extensible suite of widgets for Google Merchant Center integration with your WooCommerce store.

== Description ==

GMC Suite provides a simple and powerful way to integrate various Google Merchant Center services directly into your WordPress/WooCommerce website. It features a single settings page from which you can enable and configure different modules.

The plugin is built with a modular architecture, making it easy to extend with new Google Merchant Center services in the future.

**Current Modules:**

*   **Google Customer Reviews:** Automatically display the Google Customer Reviews badge and the survey opt-in module on your order confirmation page to start collecting valuable seller ratings.
*   **Store Widget:** Display the new Google Merchant Store Widget anywhere on your site using a simple shortcode. This widget shows your store's information and profile directly from Google.

== Installation ==

1.  Upload the `gmc-suite` folder to your `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Navigate to **Settings > GMC Suite** to configure the plugin.

== Configuration ==

1.  Go to **Settings > GMC Suite**.
2.  Enter your **Google Merchant ID**. This is required for all services to work.
3.  Use the checkboxes to enable the services you want to use.
4.  Save your changes.

== Usage ==

**Google Customer Reviews**

Once enabled, this module will automatically:
1.  Display the Google Customer Reviews badge on all pages of your site. You can configure its position (bottom right or bottom left) in the settings.
2.  Display the survey opt-in pop-up on the WooCommerce "Thank You" (order confirmation) page.

**Store Widget**

1.  Enable the "Store Widget" module in the settings.
2.  Place the shortcode `[gmc_store_widget]` in any page, post, or text widget where you want the widget to appear.

== For Developers ==

This plugin is designed to be easily extensible. To add a new service:
1.  Create a new file in the `/services` directory named `class-gmc-new-service-name.php`.
2.  Create a class inside the file (e.g., `GMC_New_Service_Name`) that defines `get_title()` and `get_description()` methods.
3.  The plugin will automatically detect the new service and show it on the settings page.

== Changelog ==

= 1.0.0 =
*   Initial release.
*   Added Google Customer Reviews module (Badge & Survey Opt-in).
*   Added Store Widget module (Shortcode).
*   Modular architecture for easy extension.