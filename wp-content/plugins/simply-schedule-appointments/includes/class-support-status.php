<?php
/**
 * Simply Schedule Appointments Support Status.
 *
 * @since   2.1.6
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Support Status.
 *
 * @since 2.1.6
 */
class SSA_Support_Status {
	/**
	 * Parent plugin class.
	 *
	 * @since 2.1.6
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  2.1.6
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  2.1.6
	 */
	public function hooks() {

	}

	public function get_site_status() {
		$site_status = new TD_Health_Check_Site_Status();
		return array(
			'ssa_version' => $this->plugin->version,
			'php_version' => $site_status->test_php_version(),
			'wordpress_version' => $site_status->test_wordpress_version(),
			'sql_server' => $site_status->test_sql_server(),
			'json_extension' => $site_status->test_json_extension(),
			'utf8mb4_support' => $site_status->test_utf8mb4_support(),
			'dotorg_communication' => $site_status->test_dotorg_communication(),
			'https_status' => $site_status->test_https_status(),
			'ssl_support' => $site_status->test_ssl_support(),
			'scheduled_events' => $site_status->test_scheduled_events(),

		);

	}
}
