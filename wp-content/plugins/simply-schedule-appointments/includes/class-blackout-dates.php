<?php
/**
 * Simply Schedule Appointments Blackout Dates.
 *
 * @since   0.1.0
 * @package Simply_Schedule_Appointments
 */
use League\Period\Period;

/**
 * Simply Schedule Appointments Blackout Dates.
 *
 * @since 0.1.0
 */
class SSA_Blackout_Dates {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.1.0
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.1.0
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
	 * @since  0.1.0
	 */
	public function hooks() {
		add_filter( 'ssa/get_blocked_periods/blocked_periods', array( $this, 'filter_blocked_periods' ), 5, 3 );
	}

	public function get_blackout_dates() {
		$blackout_settings = $this->plugin->blackout_dates_settings->get();
		if ( empty( $blackout_settings['dates'] ) ) {
			$array_of_blocked_dates = array();
		} else {
			$array_of_blocked_dates = $blackout_settings['dates'];
		}

		sort( $array_of_blocked_dates );

		return $array_of_blocked_dates;
	}

	public function filter_blocked_periods( $blocked_periods, $appointment_type, $args ) {
		if ( !$this->plugin->settings_installed->is_enabled( 'blackout_dates' ) ) {
			return $blocked_periods;
		}
		
		$array_of_blocked_dates = $this->plugin->blackout_dates_settings->get();
		if ( empty( $array_of_blocked_dates['dates'] ) || !is_array( $array_of_blocked_dates['dates'] ) ) {
			return $blocked_periods;
		}

		foreach ($array_of_blocked_dates['dates'] as $key => $blocked_date_string ) {
			$local_timezone = $this->plugin->utils->get_datetimezone( $appointment_type['id'] );
			$blocked_start_local = new DateTimeImmutable( $blocked_date_string, $local_timezone );
			$blocked_period = new Period( $blocked_start_local, $blocked_start_local->add( new DateInterval( 'P1D' ) ) );
			$blocked_periods[] = $blocked_period;
		}

		return $blocked_periods;
	}

	public function get_schedule( SSA_Appointment_Type_Object $appointment_type, Period $query_period, $args ) {
		if ( !$this->plugin->settings_installed->is_enabled( 'blackout_dates' ) ) {
			return new SSA_Availability_Schedule();
		}

		$query_period = SSA_Utils::get_query_period( $query_period );
		$blackout_dates = $this->get_blackout_dates();
		
		$blackout_availability_blocks = array();

		$local_timezone = $this->plugin->utils->get_datetimezone( $appointment_type->id );

		$schedule = new SSA_Availability_Schedule();
		foreach ($blackout_dates as $blocked_date_string) {
			$start_date = new DateTimeImmutable( $blocked_date_string, $local_timezone );
			$blackout_period = new Period(
				$start_date,
				$start_date->add( new DateInterval( 'P1D' ) )
			);
			$blackout_period = SSA_Utils::get_period_in_utc( $blackout_period );
			if ( ! $query_period->overlaps( $blackout_period ) ) {
				continue;
			}

			$schedule = $schedule->pushmerge( SSA_Availability_Block_Factory::available_for_period( $blackout_period, array(
				'capacity_available' => 0,
				'buffer_available' => SSA_Constants::CAPACITY_MAX
			) ) );
		}

		return $schedule;
	}
}
