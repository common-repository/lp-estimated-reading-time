<?php

// Script accessed directly - abort!
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Renders Admin page
 * Regsiters settings
 */
class Lp_Estimated_Reading_Time_Options {

	const SETTINGS_GROUP_NAME = 'lp_ert_settings_group';

	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	}

	// Add admin menu
	function add_admin_page() {
		add_options_page( __( 'LayerPoint Estimated Reading Time', LP_ERT_i18n ), __( 'Lp Reading Time', LP_ERT_i18n ), 'manage_options', 'lp-ert-options', array( $this, 'add_options_page' ) );
	}

	/**
	 * Register all the available options with proper sanitization methods
	 * Data will by automatically saved by the Settings API
	 */
	function register_settings(){
		register_setting( self::SETTINGS_GROUP_NAME, 'lp_ert_words_per_minute', 'absint' );
		register_setting( self::SETTINGS_GROUP_NAME, 'lp_ert_enabled', array( $this, 'sanitize_checkbox' ) );
		register_setting( self::SETTINGS_GROUP_NAME, 'lp_ert_show_in_homepage', array( $this, 'sanitize_checkbox' ) );
		register_setting( self::SETTINGS_GROUP_NAME, 'lp_ert_show_in_archive', array( $this, 'sanitize_checkbox' ) );
		register_setting( self::SETTINGS_GROUP_NAME, 'lp_ert_css_class', 'strip_tags' );
		register_setting( self::SETTINGS_GROUP_NAME, 'lp_ert_before_text', 'wp_kses_post' );
		register_setting( self::SETTINGS_GROUP_NAME, 'lp_ert_after_text', 'wp_kses_post' );
	}

	// load admin scripts and styles
	function register_admin_scripts() {
		wp_enqueue_style( 'lp-ert-admin-css', plugins_url( 'css/admin.css', dirname( __FILE__ ) ) );
	}

	// Sanitize checkbox when user saves form
	function sanitize_checkbox( $value ) {
		return $value === 'on' ? 'on' : false;
	}

	// this function renders the actual admin page
	function add_options_page() {
		?>
		<div class="wrap">

			<h2><?php _e( 'LayerPoint Estimated Reading Time', LP_ERT_i18n ) ?></h2>

			<form method="post" action="options.php">

				<?php settings_fields( self::SETTINGS_GROUP_NAME ); ?>

				<div class="lp-panel">

					<div class="lp-panel__header">
						<h3 class="lp-panel__title"><?php _e( 'Options', LP_ERT_i18n ) ?></h3>
					</div><!-- .lp-panel__header -->

					<div class="lp-panel__body">

						<table class="lp-table">

							<tr class="lp-table__row">
								<td class="lp-table__col"><?php _e( 'Reading speed of words in minute', LP_ERT_i18n ) ?></td>
								<td class="lp-table__col"><input type="number" name="lp_ert_words_per_minute" value="<?php echo get_option( 'lp_ert_words_per_minute', 150 ) ?>"></td>
							</tr>

							<tr class="lp-table__row">
								<td class="lp-table__col"><?php _e( 'Show Estimated Reading Time', LP_ERT_i18n ) ?></td>
								<td class="lp-table__col">
									<input type="hidden" name="lp_ert_enabled">
									<input type="checkbox" name="lp_ert_enabled" <?php checked( 'on', get_option( 'lp_ert_enabled' ) ) ?> >
								</td>
							</tr>

							<tr class="lp-table__row">
								<td class="lp-table__col"><?php _e( 'Show in Homepage', LP_ERT_i18n ) ?></td>
								<td class="lp-table__col">
									<input type="hidden" name="lp_ert_show_in_homepage">
									<input type="checkbox" name="lp_ert_show_in_homepage" <?php checked( 'on', get_option( 'lp_ert_show_in_homepage' ) ) ?> >
								</td>
							</tr>

							<tr class="lp-table__row">
								<td class="lp-table__col"><?php _e( 'Show in Archive', LP_ERT_i18n ) ?></td>
								<td class="lp-table__col">
									<input type="hidden" name="lp_ert_show_in_archive">
									<input type="checkbox" name="lp_ert_show_in_archive" <?php checked( 'on', get_option( 'lp_ert_show_in_archive' ) ) ?> >
								</td>
							</tr>

							<tr class="lp-table__row">
								<td class="lp-table__col"><?php _e( 'CSS class', LP_ERT_i18n ) ?></td>
								<td class="lp-table__col"><input type="text" name="lp_ert_css_class" value="<?php echo get_option( 'lp_ert_css_class' ) ?>"></td>
							</tr>

							<tr class="lp-table__row">
								<td class="lp-table__col"><?php _e( 'Before Text', LP_ERT_i18n ) ?></td>
								<td class="lp-table__col"><input type="text" name="lp_ert_before_text" value="<?php echo esc_attr( get_option( 'lp_ert_before_text', '<span class="lp-ert__icon dashicons dashicons-clock"></span>' ) ) ?>"></td>
							</tr>

							<tr class="lp-table__row">
								<td class="lp-table__col"><?php _e( 'After Text', LP_ERT_i18n ) ?></td>
								<td class="lp-table__col"><input type="text" name="lp_ert_after_text" value="<?php echo esc_attr( get_option( 'lp_ert_after_text', __( 'min read', LP_ERT_i18n ) ) ) ?>"></td>
							</tr>

							<tr class="lp-table__row">
								<td class="lp-table__col">
									<button type="submit" class="button button-primary"><?php _e( 'Save Settings', LP_ERT_i18n ) ?></button>
								</td>
							</tr>

						</table><!-- .lp-table -->

					</div><!-- .lp-panel__body -->

					<div class="lp-panel__footer">
						<strong><?php _e( 'Configure LayerPoint Estimated Reading Time to meet your requirements', LP_ERT_i18n ) ?></strong>
						<br>
						<span>
							<?php printf( '%1$s <strong>%2$s</strong> %3$s <strong>%4$s</strong> %5$s',
								__( 'You can use html tags in', LP_ERT_i18n ),
								__( 'before', LP_ERT_i18n ),
								__( 'and', LP_ERT_i18n ),
								__( 'after', LP_ERT_i18n ),
								__( 'text', LP_ERT_i18n )
							);
							?>
						</span>
					</div><!-- .lp-panel__footer -->

				</div><!-- .lp-panel -->

			</form>

			<div class="lp-panel">

				<div class="lp-panel__header">
					<h3 class="lp-panel__title"><?php _e( 'Integration', LP_ERT_i18n ) ?></h3>
				</div><!-- .lp-panel__header -->

				<div class="lp-panel__body">
					<span><?php _e( 'Shortcode: Use', LP_ERT_i18n ) ?></span>
					<strong>[lp_ert]</strong>
					<span><?php _e( 'inside the WordPress loop.', LP_ERT_i18n ) ?></span>
				</div><!-- .lp-panel__body -->

			</div><!-- lp-panel -->

		</div><!-- .wrap -->
		<?php
	}
}

// Initialize admin page
new Lp_Estimated_Reading_Time_Options();