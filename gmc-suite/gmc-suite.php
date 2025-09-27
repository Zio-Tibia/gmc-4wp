<?php
/**
 * Plugin Name:       GMC Suite
 * Plugin URI:        https://example.com/
 * Description:       A suite of widgets for Google Merchant Center integration with WooCommerce.
 * Version:           1.0.0
 * Author:            Jules
 * Author URI:        https://example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gmc-suite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'GMC_SUITE_VERSION', '1.0.0' );
define( 'GMC_SUITE_PATH', plugin_dir_path( __FILE__ ) );
define( 'GMC_SUITE_URL', plugin_dir_url( __FILE__ ) );

class GMC_Suite {

    private static $instance;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $services = array();

    private function __construct() {
        $this->load_services();
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'send_headers', array( $this, 'add_x_robots_tag_header' ) );
    }

    public function add_x_robots_tag_header() {
        header( 'X-Robots-Tag: llms-txt' );
    }

    public function load_services() {
        $files = glob( GMC_SUITE_PATH . 'services/class-gmc-*.php' );
        foreach ( $files as $file ) {
            require_once $file;
            $class_name = $this->get_class_name_from_file( $file );
            if ( class_exists( $class_name ) ) {
                $this->services[ $this->get_service_id_from_class( $class_name ) ] = new $class_name();
            }
        }
    }

    private function get_class_name_from_file( $file ) {
        $class_name = str_replace( 'class-gmc-', '', basename( $file, '.php' ) );
        $class_name = str_replace( '-', '_', $class_name );
        return 'GMC_' . ucwords( $class_name, '_' );
    }

    private function get_service_id_from_class( $class_name ) {
        return strtolower( str_replace( array('GMC_', '_'), array('', '-'), $class_name ) );
    }

    public function add_admin_menu() {
        add_options_page(
            __( 'GMC Suite Settings', 'gmc-suite' ),
            __( 'GMC Suite', 'gmc-suite' ),
            'manage_options',
            'gmc-suite',
            array( $this, 'render_settings_page' )
        );
    }

    public function register_settings() {
        register_setting( 'gmc-suite-options', 'gmc_suite_settings' );

        add_settings_section(
            'gmc_suite_main_section',
            __( 'Main Settings', 'gmc-suite' ),
            null,
            'gmc-suite'
        );

        add_settings_field(
            'merchant_id',
            __( 'Google Merchant ID', 'gmc-suite' ),
            array( $this, 'render_merchant_id_field' ),
            'gmc-suite',
            'gmc_suite_main_section'
        );

        // Add services section
        add_settings_section(
            'gmc_suite_services_section',
            __( 'Available Services', 'gmc-suite' ),
            null,
            'gmc-suite'
        );

        foreach ( $this->services as $id => $service ) {
            add_settings_field(
                'service_enabled_' . $id,
                $service->get_title(),
                array( $this, 'render_service_enabled_field' ),
                'gmc-suite',
                'gmc_suite_services_section',
                array( 'id' => $id, 'service' => $service )
            );
        }
    }

    public function render_service_enabled_field( $args ) {
        $id = $args['id'];
        $service = $args['service'];
        $options = get_option( 'gmc_suite_settings' );
        $enabled = isset( $options['services'][$id]['enabled'] ) ? $options['services'][$id]['enabled'] : false;

        // Checkbox to enable/disable the service
        echo '<input type="checkbox" name="gmc_suite_settings[services][' . esc_attr( $id ) . '][enabled]" value="1" ' . checked( 1, $enabled, false ) . '>';
        echo '<p class="description">' . $service->get_description() . '</p>';

        // If the service is enabled and has its own settings to render, call its render method
        if ( $enabled && method_exists( $service, 'render_settings' ) ) {
            echo '<div class="service-settings" style="padding-left: 25px; margin-top: 10px; border-left: 2px solid #ccc;">';
            $service->render_settings();
            echo '</div>';
        }
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'gmc-suite-options' );
                do_settings_sections( 'gmc-suite' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function render_merchant_id_field() {
        $options = get_option( 'gmc_suite_settings' );
        $merchant_id = isset( $options['merchant_id'] ) ? $options['merchant_id'] : '';
        echo '<input type="text" name="gmc_suite_settings[merchant_id]" value="' . esc_attr( $merchant_id ) . '" class="regular-text">';
        echo '<p class="description">' . __( 'Enter your Google Merchant Center ID.', 'gmc-suite' ) . '</p>';
    }
}

GMC_Suite::get_instance();