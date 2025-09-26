<?php

if ( ! defined( 'WPINC' ) ) {
    die;
}

class GMC_Customer_Reviews {

    private $options;

    public function __construct() {
        $this->options = get_option( 'gmc_suite_settings' );
        $is_enabled = isset( $this->options['services']['customer-reviews']['enabled'] ) && $this->options['services']['customer-reviews']['enabled'];

        if ( ! $is_enabled || empty( $this->options['merchant_id'] ) ) {
            return;
        }

        // Only add actions if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        add_action( 'wp_footer', array( $this, 'add_badge_script' ) );
        add_action( 'woocommerce_thankyou', array( $this, 'add_survey_script' ) );
    }

    public function get_title() {
        return __( 'Google Customer Reviews', 'gmc-suite' );
    }

    public function get_description() {
        return __( 'Enable this module to display the Google Customer Reviews badge and the survey opt-in on your order confirmation page.', 'gmc-suite' );
    }

    public function render_settings() {
        $position = isset( $this->options['services']['customer-reviews']['badge_position'] ) ? $this->options['services']['customer-reviews']['badge_position'] : 'BOTTOM_RIGHT';
        ?>
        <div class="service-setting-row" style="margin-top: 15px;">
            <label for="gmc_badge_position" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php _e( 'Badge Position', 'gmc-suite' ); ?></label>
            <select name="gmc_suite_settings[services][customer-reviews][badge_position]" id="gmc_badge_position">
                <option value="BOTTOM_RIGHT" <?php selected( $position, 'BOTTOM_RIGHT' ); ?>><?php _e( 'Bottom Right', 'gmc-suite' ); ?></option>
                <option value="BOTTOM_LEFT" <?php selected( $position, 'BOTTOM_LEFT' ); ?>><?php _e( 'Bottom Left', 'gmc-suite' ); ?></option>
            </select>
            <p class="description"><?php _e( 'Select the position for the Google Customer Reviews badge on your site.', 'gmc-suite' ); ?></p>
        </div>
        <?php
    }

    public function add_badge_script() {
        ?>
        <script src="https://apis.google.com/js/platform.js?onload=renderBadge" async defer></script>
        <script>
          window.renderBadge = function() {
            var ratingBadgeContainer = document.createElement("div");
            document.body.appendChild(ratingBadgeContainer);
            window.gapi.load('ratingbadge', function() {
              window.gapi.ratingbadge.render(
                ratingBadgeContainer, {
                  "merchant_id": <?php echo esc_js( $this->options['merchant_id'] ); ?>,
                  "position": "<?php echo esc_js( isset( $this->options['services']['customer-reviews']['badge_position'] ) ? $this->options['services']['customer-reviews']['badge_position'] : 'BOTTOM_RIGHT' ); ?>"
                });
            });
          }
        </script>
        <?php
    }

    public function add_survey_script( $order_id ) {
        if ( ! $order_id ) {
            return;
        }

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return;
        }

        $email = $order->get_billing_email();
        $country = $order->get_billing_country();
        // Estimated delivery date is required. We'll estimate it as 7 days from the order date.
        $delivery_date = date( 'Y-m-d', strtotime( '+7 days', $order->get_date_created()->getOffsetTimestamp() ) );

        ?>
        <script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>
        <script>
          window.renderOptIn = function() {
            window.gapi.load('surveyoptin', function() {
              window.gapi.surveyoptin.render(
                {
                  "merchant_id": <?php echo esc_js( $this->options['merchant_id'] ); ?>,
                  "order_id": "<?php echo esc_js( $order->get_order_number() ); ?>",
                  "email": "<?php echo esc_js( $email ); ?>",
                  "delivery_country": "<?php echo esc_js( $country ); ?>",
                  "estimated_delivery_date": "<?php echo esc_js( $delivery_date ); ?>",
                  "opt_in_style": "CENTER_DIALOG"
                });
            });
          }
        </script>
        <?php
    }
}