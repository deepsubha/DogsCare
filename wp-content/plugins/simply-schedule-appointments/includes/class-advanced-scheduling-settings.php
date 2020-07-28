<?php
/**
 * Simply Schedule Appointments Advanced Scheduling Settings.
 *
 * @since   1.8.3
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Advanced Scheduling Settings.
 *
 * @since 1.8.3
 */
class SSA_Advanced_Scheduling_Settings extends SSA_Settings_Schema {
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

	protected $slug = 'advanced_scheduling';

	public function get_schema() {
		if ( !empty( $this->schema ) ) {
			return $this->schema;
		}

		$this->schema = array(
			'version' => '2018-09-04',
			'fields' => array(
				'enabled' => array(
					'name' => 'enabled',
					'default_value' => false,
				),
			),
		);

		return $this->schema;
	}

}
