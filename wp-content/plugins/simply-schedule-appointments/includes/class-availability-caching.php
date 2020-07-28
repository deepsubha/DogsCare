<?php
/**
 * Simply Schedule Appointments Availability Caching.
 *
 * @since   3.6.0
 * @package Simply_Schedule_Appointments
 */
use League\Period\Period;
use Cake\Chronos\Date;

/**
 * Simply Schedule Appointments Availability Caching.
 *
 * @since 3.6.0
 */
class SSA_Availability_Caching {
	/**
	 * Parent plugin class.
	 *
	 * @since 3.6.0
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  3.6.0
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	public function get_args( $appointment_type, $args = array() ) {
		$default_args = apply_filters( 'ssa/availability/default_args', array(
			'start_date' => '',
			'duration' => '',
			'end_date' => '',

			'start_date_min' => '',
			'start_date_max' => '',
			'end_date_min' => '',
			'end_date_max' => '',
			'buffered' => false,

			'excluded_appointment_ids' => array(),
			'appointment_statuses' => SSA_Appointment_Model::get_unavailable_statuses(),
		), $appointment_type );

		$args = shortcode_atts( $default_args, $args );

		return $args;
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  3.6.0
	 */
	public function hooks() {

	}
}
