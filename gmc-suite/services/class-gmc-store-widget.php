<?php

if ( ! defined( 'WPINC' ) ) {
    die;
}

class GMC_Store_Widget {

    private $options;

    public function __construct() {
        $this->options = get_option( 'gmc_suite_settings' );
        $is_enabled = isset( $this->options['services']['store-widget']['enabled'] ) && $this->options['services']['store-widget']['enabled'];

        if ( ! $is_enabled || empty( $this->options['merchant_id'] ) ) {
            return;
        }

        add_shortcode( 'gmc_store_widget', array( $this, 'render_shortcode' ) );
    }

    public function get_title() {
        return __( 'Store Widget', 'gmc-suite' );
    }

    public function get_description() {
        return __( 'Enable this module to use the <code>[gmc_store_widget]</code> shortcode to display the store information widget. You can place this shortcode in any page, post, or text widget.', 'gmc-suite' );
    }

    public function render_shortcode( $atts ) {
        // Prepare the output buffer
        ob_start();
        ?>
        <div id="gmc-store-widget-container"></div>
        <script src="https://www.gstatic.com/merchant-store-widget/store-widget.js" async></script>
        <script>
            window.addEventListener('load', () => {
                var widget = new MerchantStoreWidget.StoreWidget();
                widget.render('gmc-store-widget-container', {
                    merchantId: <?php echo esc_js( $this->options['merchant_id'] ); ?>
                });
            });
        </script>
        <?php
        // Return the buffered content
        return ob_get_clean();
    }
}