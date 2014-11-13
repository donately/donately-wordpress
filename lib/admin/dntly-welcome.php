<?php
/**
 * Weclome Page Class
 *
 * @package     Donately
 * @subpackage  Admin/Welcome
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * DNTLY_Welcome Class
 *
 * A general class for About and Credits page.
 *
 * @since 1.4
 */
class DNTLY_Welcome {

    /**
     * @var string The capability users should have to view the page
     */
    public $minimum_capability = 'manage_options';

    /**
     * Get things started
     *
     * @since 1.4
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus') );
        add_action( 'admin_head', array( $this, 'admin_head' ) );
        add_action( 'admin_init', array( $this, 'welcome'    ) );
    }

    /**
     * Register the Dashboard Pages which are later hidden but these pages
     * are used to render the Welcome and Credits pages.
     *
     * @access public
     * @since 1.4
     * @return void
     */
    public function admin_menus() {
        // About Page
        add_dashboard_page(
            __( 'Welcome to Donately for WordPress', 'dntly' ),
            __( 'Welcome to Donately for WordPress', 'dntly' ),
            $this->minimum_capability,
            'dntly-about',
            array( $this, 'about_screen' )
        );

        // Credits Page
        add_dashboard_page(
            __( 'Credits', 'dntly' ),
            __( 'Credits', 'dntly' ),
            $this->minimum_capability,
            'dntly-credits',
            array( $this, 'credits_screen' )
        );
    }

    /**
     * Hide Individual Dashboard Pages
     *
     * @access public
     * @since 1.4
     * @return void
     */
    public function admin_head() {
        remove_submenu_page( 'index.php', 'dntly-about' );
        remove_submenu_page( 'index.php', 'dntly-credits' );

        // Badge for welcome page
        //$badge_url = DNTLY_PLUGIN_URL . 'assets/images/edd-badge.png';
        ?>
        <style type="text/css" media="screen">
        /*<![CDATA[*/
        .dntly-badge {
            padding-top: 150px;
            height: 52px;
            width: 185px;
            color: #666;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
            margin: 0 -5px;
            background: url('<?php echo $badge_url; ?>') no-repeat;
        }

        .about-wrap .dntly-badge {
            position: absolute;
            top: 0;
            right: 0;
        }

        .dntly-welcome-screenshots {
            float: right;
            margin-left: 10px!important;
        }
        /*]]>*/
        </style>
        <?php
    }

    /**
     * Render About Screen
     *
     * @access public
     * @since 1.4
     * @return void
     */
    public function about_screen() {
        list( $display_version ) = explode( '-', DNTLY_VERSION );
        ?>
        <div class="wrap about-wrap">
            <h1><?php printf( __( 'Donately for WordPress %s', 'dntly' ), $display_version ); ?></h1>
            <div class="about-text"><?php printf( __( 'An integrated donation platform.', 'dntly' ), $display_version ); ?></div>
            <div class="edd-badge"><?php printf( __( 'Version %s', 'dntly' ), $display_version ); ?></div>

            <h2 class="nav-tab-wrapper">
                <a class="nav-tab nav-tab-active" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-dashboard' ) ) ) ); ?>">
                    <?php _e( "About Donately", 'dntly' ); ?>
                </a>
                <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-settings' ), 'admin.php' ) ) ); ?>" class="nav-tab">
                    <?php _e( 'Settings', 'dntly') ?>
                </a>
                <!-- <a class="nav-tab" href="<?php //echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-credits' ), 'index.php' ) ) ); ?>">
                    <?php _e( 'Credits', 'dntly' ); ?>
                </a> -->
            </h2>

            <div class="changelog">
                <h3><?php _e( 'A Great Donation Experience', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/17checkout.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'Simple, Donations', 'dntly' );?></h4>
                    <p><?php _e( 'When it comes to accepting donations, you need options. You need campaigns and fundraisers and secure donation forms. Donately was created to solve the problems all non-profits have without creating a ton of overhead.', 'dntly' );?></p>

                    <h4><?php _e( 'It\'s free to sign up!', 'dntly' );?></h4>
                    <p><?php _e( 'There are some transaction fees but you won\'t find a cheaper solution out there. Check out <a href="http://donate.ly">Donate.ly</a> for more information.', 'dntly' );?></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'The Platform', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/18cart-saving.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'About the Plugin','dntly' );?></h4>
                    <p><?php _e( 'Donately is built on a powerful and flexible API. We built this plugin using the API to create a better experience for your donors. They can donate directly to your organization and campaigns without ever leaving your site!', 'dntly' );?></p>

                    <h4><?php _e( 'Loads of Settings', 'dntly' );?></h4>
                    <p><?php _e( 'We\'e given you a host of options, shortcodes and widgets. If you\'re a developer, you can find a ton of actions and filters to shape your experience. If you are interested in extending the plugin you can checkout our docs at <a href="http://support.donate.ly">http://support.donate.ly</a>.', 'dntly' );?></p>


                </div>
            </div>

             <div class="changelog">
                <h3><?php _e( 'Sign Up for Donately', 'dntly' );?></h3>

                <div class="feature-section">

                    <!-- <img src="<?php echo DNTLY_PLUGIN_URL . 'assets/images/screenshots/18cart-saving.png'; ?>" class="edd-welcome-screenshots"/> -->

                    <h4><?php _e( 'Create an Account','dntly' );?></h4>
                    <p><?php _e( 'Need an account? No worries.', 'dntly' );?></p>
                    <p><a href="https://www.dntly.com/a#/npo/signup" class="button button-primary">Create an Account</a></p>

                    <h4><?php _e( 'Have an account already?', 'dntly' );?></h4>
                    <p><?php _e( 'Head over to our settings page and enter the details.', 'dntly' );?></p>
                    <p><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-settings' ), 'admin.php' ) ) ); ?>" class="button">Donately for WordPress Settings</a></p>

                </div>
            </div>

            <div class="changelog">
                <h3><?php _e( 'Additional Features', 'dntly' );?></h3>

                <div class="feature-section col three-col">
                    <div>
                        <h4><?php _e( 'Widget Ready', 'dntly' );?></h4>
                        <p><?php _e( 'Need a simple form in your sidebar? No problem, just drag and drop our widget and you are good to go.', 'dntly' );?></p>

                        <h4><?php _e( 'Shortcodes', 'dntly' );?></h4>
                        <p><?php _e( 'We have a number of shortcodes that output forms and donation statistics wherever you need them.', 'dntly' );?></p>
                    </div>

                    <div>
                        <h4><?php _e( 'Custom Forms', 'dntly' );?></h4>
                        <p><?php _e( 'You can enter your a URL for a custom css file and override the default styles.', 'dntly' );?></p>

                        <h4><?php _e( 'Campaigns', 'dntly' );?></h4>
                        <p><?php _e( 'Create campaigns on Dntly and sync them to WordPress. Keep your followers in the loop with your goals and other statistics. You can change the name from campaigns to anything.', 'dntly' );?></p>
                    </div>

                    <div class="last-feature">
                        <h4><?php _e( 'Email', 'dntly' );?></h4>
                        <p><?php _e( 'You can disable the email Donately sends and send your own from the settings panel.' ,'dntly' );?></p>

                        <h4><?php _e( 'Form Builder','dntly' );?></h4>
                        <p><?php _e( 'Sometimes you don\'t want to use the global settings. Create your form shortcode and use it wherever you need it.', 'dntly' );?></p>
                    </div>
                </div>
            </div>

            <div class="return-to-dashboard">
                <a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'dntly-settings' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to Donately for WordPress Settings', 'dntly' ); ?></a>
            </div>
        </div>
        <?php
    }



    /**
     * Sends user to the Welcome page on first activation of DNTLY as well as each
     * time DNTLY is upgraded to a new version
     *
     * @access public
     * @since 1.4
     * @global $dntly_settings Array of all the DNTLY Options
     * @return void
     */
    public function welcome() {
        global $dntly_settings;

        // Bail if no activation redirect
        if ( ! get_transient( '_dntly_activation_redirect' ) )
            return;

        // Delete the redirect transient
        delete_transient( '_dntly_activation_redirect' );

        // Bail if activating from network, or bulk
        if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
            return;

        wp_safe_redirect( admin_url( 'index.php?page=dntly-about' ) ); exit;
    }
}
new DNTLY_Welcome();
