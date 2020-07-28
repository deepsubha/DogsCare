<?php
/**
 * Simply Schedule Appointments Notifications Settings.
 *
 * @since   2.6.7
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Notifications Settings.
 *
 * @since 2.6.7
 */
class SSA_Notifications_Settings extends SSA_Settings_Schema {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.3
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.0.3
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		parent::__construct();
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.3
	 */
	public function hooks() {
	}

	protected $slug = 'notifications';

	public function get_schema() {
		if ( !empty( $this->schema ) ) {
			return $this->schema;
		}

		$this->schema = array(
			'version' => '2018-09-18 18:30',
			'fields' => array(
				'enabled' => array(
					'name' => 'enabled',
					'default_value' => true,
				),

				'notifications' => array(
					'name' => 'notifications',
					'default_value' => array(),
					'required_capability' => 'ssa_manage_site_settings',
					'before_save_function' => array( $this, 'cleanup_p_tags_and_br_tags' ),
				),
			),
		);

		return $this->schema;
	}

	public function get_notifications() {
		$settings = $this->get();
		if ( empty( $settings['notifications']['0']['type'] ) ) {
			return array();
		}

		return $settings['notifications'];
	}

	public function cleanup_p_tags_and_br_tags( $notifications ) {
		if ( empty( $notifications ) ) {
			return $notifications;
		}

		foreach ($notifications as $key => &$notification) {
			if ( ! empty( $notification['message'] ) ) {
				$notification['message'] = $this->plugin->templates->cleanup_variables_in_string( $notification['message'] );
			}
			if ( ! empty( $notification['subject'] ) ) {
				$notification['subject'] = $this->plugin->templates->cleanup_variables_in_string( $notification['subject'] );
			}
		}

		return $notifications;
	}

}
