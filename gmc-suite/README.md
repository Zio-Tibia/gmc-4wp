# GMC Suite for WooCommerce

A modular and extensible WordPress plugin to integrate Google Merchant Center services with your WooCommerce store.

## Description

GMC Suite provides a simple and powerful way to integrate various Google Merchant Center services directly into your WordPress/WooCommerce website. It features a single settings page from which you can enable and configure different modules.

The plugin is built with a modular architecture, making it easy to extend with new Google Merchant Center services in the future.

---

## Features

*   **Modular Architecture:** Easily enable or disable the services you need.
*   **Extensible:** Designed for developers to easily add new services.
*   **Simple Configuration:** A single settings page to manage your Merchant ID and all modules.
*   **WooCommerce Integration:** Built specifically to work with WooCommerce.

### Current Modules

*   **Google Customer Reviews:** Automatically display the Google Customer Reviews badge and the survey opt-in module on your order confirmation page to start collecting valuable seller ratings.
*   **Store Widget:** Display the new Google Merchant Store Widget anywhere on your site using a simple `[gmc_store_widget]` shortcode.

---

## Installation

1.  Download the latest release from the [releases](../../releases) page.
2.  In your WordPress admin dashboard, navigate to **Plugins > Add New**.
3.  Click **Upload Plugin** and select the downloaded `.zip` file.
4.  Activate the plugin through the 'Plugins' menu in WordPress.
5.  Navigate to **Settings > GMC Suite** to configure the plugin.

---

## Configuration

1.  Go to **Settings > GMC Suite** in your WordPress admin area.
2.  Enter your **Google Merchant ID**. This is required for all services to work.
3.  Use the checkboxes to enable the services you want to use.
    *   For **Google Customer Reviews**, you can also select the badge position.
4.  Click **Save Changes**.

---

## Usage

### Google Customer Reviews

Once the module is enabled in the settings, it will work automatically:

1.  The **Google Customer Reviews badge** will be displayed on all pages of your site in the position you selected.
2.  The **survey opt-in pop-up** will appear on the WooCommerce "Thank You" (order confirmation) page after a customer completes a purchase.

### Store Widget

1.  Enable the "Store Widget" module in the settings.
2.  Place the shortcode `[gmc_store_widget]` in any page, post, or text widget where you want the widget to appear. The widget will be rendered in that location.

---

## For Developers: Extending the Plugin

This plugin is designed to be easily extensible. To add a new service:

1.  Create a new class file in the `/services` directory. The filename should follow the pattern `class-gmc-new-service-name.php`.
2.  Create a class inside the file (e.g., `GMC_New_Service_Name`).
3.  The class must, at a minimum, implement the `get_title()` and `get_description()` public methods.
4.  The plugin will automatically detect the new service and display it on the settings page, allowing users to enable/disable it. You can add more complex logic by adding a `render_settings()` method and hooking into WordPress actions in the class constructor.