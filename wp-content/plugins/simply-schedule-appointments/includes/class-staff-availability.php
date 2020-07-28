<?php
/**
 * Simply Schedule Appointments Staff Availability.
 *
 * @since   3.5.3
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Staff Availability.
 *
 * @since 3.5.3
 */
class SSA_Staff_Availability {
	/**
	 * Parent plugin class.
	 *
	 * @since 3.5.3
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  3.5.3
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
	 * @since  3.5.3
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'add_filters' ), 1 );
	}

	public function add_filters() {
		$is_enabled = $this->plugin->settings_installed->is_enabled( 'staff' );
		if ( ! $is_enabled ) {
			return;
		}

		add_filter( 'ssa/availability/default_args', array( $this, 'filter_default_args'), 10, 2 );
	}

	public function filter_default_args( $args, $appointment_type ) {
		$staff_args = array( 
			'staff_ids_and' => array(),
			'staff_ids_or' => array(),
			'staff_required_count' => 1,
		);

		// TODO: customize args based on appointment_type settings

		$args = array_merge( $args, $staff_args );

		return $args;
	}
}
