<?php
/**
 * Advanced Woo Reporting - Helper Functions
 *
 * Centralized reusable helper methods following WordPress Coding Standards.
 *
 * @package PW_Advanced_Woo_Reporting
 * @since 6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class PW_Report_AWR_Helpers
 *
 * Provides reusable utility methods for the reporting plugin.
 */
class PW_Report_AWR_Helpers {

	/**
	 * Get option with plugin prefix.
	 *
	 * @param string $key     Option key (without prefix).
	 * @param mixed  $default Default value.
	 * @return mixed Option value.
	 */
	public static function get_option( $key, $default = false ) {
		return get_option( __PW_REPORT_WCREPORT_FIELDS_PERFIX__ . $key, $default );
	}

	/**
	 * Update option with plugin prefix.
	 *
	 * @param string $key   Option key (without prefix).
	 * @param mixed  $value Option value.
	 * @return bool True if successful, false otherwise.
	 */
	public static function update_option( $key, $value ) {
		return update_option( __PW_REPORT_WCREPORT_FIELDS_PERFIX__ . $key, $value );
	}

	/**
	 * Delete option with plugin prefix.
	 *
	 * @param string $key Option key (without prefix).
	 * @return bool True if successful, false otherwise.
	 */
	public static function delete_option( $key ) {
		return delete_option( __PW_REPORT_WCREPORT_FIELDS_PERFIX__ . $key );
	}

	/**
	 * Render AWR box wrapper start.
	 *
	 * @param string $title       Box title.
	 * @param string $icon        Font Awesome icon class (default: 'fa-desktop').
	 * @param bool   $show_icons  Show toggle/settings/close icons (default: true).
	 * @param array  $extra_class Additional CSS classes.
	 * @return void
	 */
	public static function box_start( $title = '', $icon = 'fa-desktop', $show_icons = true, $extra_class = array() ) {
		$classes = array_merge( array( 'awr-box' ), $extra_class );
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php if ( ! empty( $title ) ) : ?>
				<div class="awr-title">
					<h3><i class="fa <?php echo esc_attr( $icon ); ?>"></i><?php echo esc_html( $title ); ?></h3>
					<?php if ( $show_icons ) : ?>
						<div class="awr-title-icons">
							<div class="awr-title-icon awr-toggle-icon"><i class="fa fa-arrow-up"></i></div>
							<div class="awr-title-icon awr-setting-icon"><i class="fa fa-cog"></i></div>
							<div class="awr-title-icon awr-close-icon"><i class="fa fa-times"></i></div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="awr-box-content">
		<?php
	}

	/**
	 * Render AWR box wrapper end.
	 *
	 * @return void
	 */
	public static function box_end() {
		?>
			</div><!-- .awr-box-content -->
		</div><!-- .awr-box -->
		<?php
	}

	/**
	 * Render grid column start.
	 *
	 * @param string $col_class Column class (e.g., 'col-xs-12 col-md-6').
	 * @return void
	 */
	public static function col_start( $col_class = 'col-xs-12 col-md-12' ) {
		?>
		<div class="<?php echo esc_attr( $col_class ); ?>">
		<?php
	}

	/**
	 * Render grid column end.
	 *
	 * @param bool $clear_after Add clearfix after column (default: false).
	 * @return void
	 */
	public static function col_end( $clear_after = false ) {
		?>
		</div>
		<?php if ( $clear_after ) : ?>
			<div class="awr-clearboth"></div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Sanitize and cast year to integer.
	 *
	 * @param mixed $year Year value.
	 * @return int Sanitized year.
	 */
	public static function sanitize_year( $year ) {
		return absint( $year );
	}

	/**
	 * Build date range strings for SQL queries.
	 *
	 * @param int    $year       Year value.
	 * @param string $start_md   Month-day for start (default: '01-01').
	 * @param string $end_md     Month-day for end (default: '12-31').
	 * @return array Array with 'start' and 'end' date strings.
	 */
	public static function build_date_range( $year, $start_md = '01-01', $end_md = '12-31' ) {
		$year = self::sanitize_year( $year );
		return array(
			'start' => $year . '-' . $start_md,
			'end'   => $year . '-' . $end_md,
		);
	}

	/**
	 * Prepare order query for country data.
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 * @param int    $year       Year value.
	 * @param string $start_date Start date (Y-m-d).
	 * @param string $end_date   End date (Y-m-d).
	 * @return string Prepared SQL query.
	 */
	public static function prepare_country_query( $year, $start_date, $end_date ) {
		global $wpdb;
		$year       = self::sanitize_year( $year );
		$start_date = sanitize_text_field( $start_date );
		$end_date   = sanitize_text_field( $end_date );

		return $wpdb->prepare(
			"SELECT SUM(pw_postmeta1.meta_value) AS Total, pw_postmeta2.meta_value AS BillingCountry, COUNT(*) AS OrderCount
			 FROM {$wpdb->prefix}posts AS pw_posts
			 LEFT JOIN {$wpdb->prefix}postmeta AS pw_postmeta1 ON pw_postmeta1.post_id = pw_posts.ID
			 LEFT JOIN {$wpdb->prefix}postmeta AS pw_postmeta2 ON pw_postmeta2.post_id = pw_posts.ID
			 WHERE pw_posts.post_type = 'shop_order'
			   AND pw_postmeta1.meta_key = '_order_total'
			   AND pw_postmeta2.meta_key = '_billing_country'
			   AND pw_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing')
			   AND DATE(pw_posts.post_date) BETWEEN %s AND %s
			   AND pw_posts.post_status NOT IN ('trash')
			 GROUP BY pw_postmeta2.meta_value
			 ORDER BY Total DESC",
			$start_date,
			$end_date
		);
	}

	/**
	 * Prepare order query for state data.
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 * @param int    $year       Year value.
	 * @param string $start_date Start date (Y-m-d).
	 * @param string $end_date   End date (Y-m-d).
	 * @return string Prepared SQL query.
	 */
	public static function prepare_state_query( $year, $start_date, $end_date ) {
		global $wpdb;
		$year       = self::sanitize_year( $year );
		$start_date = sanitize_text_field( $start_date );
		$end_date   = sanitize_text_field( $end_date );

		return $wpdb->prepare(
			"SELECT SUM(pw_postmeta1.meta_value) AS Total, pw_postmeta2.meta_value AS billing_state, pw_postmeta3.meta_value AS billing_country, COUNT(*) AS OrderCount
			 FROM {$wpdb->prefix}posts AS pw_posts
			 LEFT JOIN {$wpdb->prefix}postmeta AS pw_postmeta1 ON pw_postmeta1.post_id = pw_posts.ID
			 LEFT JOIN {$wpdb->prefix}postmeta AS pw_postmeta2 ON pw_postmeta2.post_id = pw_posts.ID
			 LEFT JOIN {$wpdb->prefix}postmeta AS pw_postmeta3 ON pw_postmeta3.post_id = wp_posts.ID
			 WHERE pw_posts.post_type = 'shop_order'
			   AND pw_postmeta1.meta_key = '_order_total'
			   AND pw_postmeta2.meta_key = '_billing_state'
			   AND pw_postmeta3.meta_key = '_billing_country'
			   AND pw_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing')
			   AND DATE(pw_posts.post_date) BETWEEN %s AND %s
			   AND pw_posts.post_status NOT IN ('trash')
			 GROUP BY pw_postmeta2.meta_value
			 ORDER BY Total DESC",
			$start_date,
			$end_date
		);
	}

	/**
	 * Render a data grid using table_html.
	 *
	 * @param object $pw_rpt_main_class Main reporting class instance.
	 * @param array  $grids              Array of grid configurations.
	 * @return void
	 */
	public static function render_data_grids( $pw_rpt_main_class, $grids ) {
		foreach ( $grids as $grid ) {
			if ( method_exists( $pw_rpt_main_class, 'get_dashboard_capability' ) && $pw_rpt_main_class->get_dashboard_capability( $grid['cap'] ) ) {
				self::col_start( $grid['col'] );
				$table_name = $grid['table'];
				$pw_rpt_main_class->table_html( $table_name );
				self::col_end( $grid['clear_after'] );
			}
		}
	}

	/**
	 * Escape and echo a translatable string.
	 *
	 * @param string $text       Text to translate.
	 * @param string $textdomain Text domain.
	 * @return void
	 */
	public static function e( $text, $textdomain = __PW_REPORT_WCREPORT_TEXTDOMAIN__ ) {
		echo esc_html__( $text, $textdomain );
	}

	/**
	 * Get escaped translatable string.
	 *
	 * @param string $text       Text to translate.
	 * @param string $textdomain Text domain.
	 * @return string Escaped translated string.
	 */
	public static function get_e( $text, $textdomain = __PW_REPORT_WCREPORT_TEXTDOMAIN__ ) {
		return esc_html__( $text, $textdomain );
	}

	/**
	 * Check current user capability.
	 *
	 * @param string $capability Capability to check (default: 'manage_woocommerce').
	 * @return bool True if current user has capability.
	 */
	public static function current_user_can( $capability = 'manage_woocommerce' ) {
		return current_user_can( $capability );
	}

	/**
	 * Verify nonce for AJAX requests.
	 *
	 * @param string $nonce_field Nonce field name.
	 * @param string $nonce_action Nonce action name.
	 * @return bool True if nonce is valid.
	 */
	public static function verify_nonce( $nonce_field, $nonce_action ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$nonce = isset( $_POST[ $nonce_field ] ) ? wp_unslash( $_POST[ $nonce_field ] ) : '';
		return wp_verify_nonce( $nonce, $nonce_action );
	}

	/**
	 * Send JSON error response and exit.
	 *
	 * @param string $message Error message.
	 * @param int    $code    HTTP status code (default: 403).
	 * @return void
	 */
	public static function json_error( $message, $code = 403 ) {
		wp_send_json_error( array( 'message' => $message ), $code );
	}

	/**
	 * Send JSON success response and exit.
	 *
	 * @param mixed $data Response data.
	 * @return void
	 */
	public static function json_success( $data ) {
		wp_send_json_success( $data );
	}

	/**
	 * Render standard report view with search form and table.
	 *
	 * @param object $pw_rpt_main_class Main reporting class instance.
	 * @param string $table_name        Table name for the report.
	 * @param string $title             Report title (translatable).
	 * @param string $icon              Font Awesome icon class (default: 'fa-filter').
	 * @param string $smenu             Submenu identifier for favorites.
	 * @return void
	 */
	public static function render_standard_report( $pw_rpt_main_class, $table_name, $title, $icon = 'fa-filter', $smenu = '' ) {
		if ( ! $pw_rpt_main_class->dashboard( $pw_rpt_main_class->pw_plugin_status ) ) {
			wp_safe_redirect( admin_url() . 'admin.php?page=wcx_wcreport_plugin_active_report&parent=active_plugin' );
			exit;
		}

		if ( empty( $smenu ) ) {
			$smenu = isset( $_REQUEST['smenu'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['smenu'] ) ) : '';
		}

		$fav_icon = ' fa-star-o ';
		if ( $pw_rpt_main_class->fetch_our_menu_fav( $smenu ) ) {
			$fav_icon = ' fa-star ';
		}
		?>
		<div class="wrap">
			<div class="row">
				<div class="col-xs-12">
					<div class="awr-box">
						<div class="awr-title">
							<h3>
								<i class="fa <?php echo esc_attr( $icon ); ?>"></i>
								<span><?php echo esc_html( $title ); ?></span>
							</h3>
							<div class="awr-title-icons">
								<div class="awr-title-icon awr-add-fav-icon" data-smenu="<?php echo esc_attr( $smenu ); ?>"><i class="fa <?php echo esc_attr( $fav_icon ); ?>"></i></div>
								<div class="awr-title-icon awr-toggle-icon"><i class="fa fa-arrow-up"></i></div>
								<div class="awr-title-icon awr-setting-icon"><i class="fa fa-cog"></i></div>
								<div class="awr-title-icon awr-close-icon"><i class="fa fa-times"></i></div>
							</div>
						</div>
						<div class="awr-box-content-form">
							<?php $pw_rpt_main_class->search_form_html( $table_name ); ?>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12" id="target">
					<?php $pw_rpt_main_class->table_html( $table_name ); ?>
				</div>
			</div>
		</div>
		<?php
	}
}

