<?php
/**
 * Simply Schedule Appointments Availability Query.
 *
 * @since   3.6.2
 * @package Simply_Schedule_Appointments
 */

use League\Period\Period;

/**
 * Simply Schedule Appointments Availability Query.
 *
 * @since 3.6.2
 */
class SSA_Availability_Query {
	/**
	 * Parent plugin class.
	 *
	 * @since 3.6.2
	 *
	 * @var   Simply_Schedule_Appointments
	 */

	protected $period;

	protected $appointment_type;

	protected $args = array(
		'staff_ids_all_required' => -1,
		'staff_ids_some_required' => -1,
		'any_staff_count' => -1,

		'location_ids_all_required' => -1,
		'location_ids_some_required' => -1,
		'any_location_count' => -1,

		'resource_ids_all_required' => -1,
		'resource_ids_some_required' => -1,
		'any_resource_count' => -1,

		'type' => '',
		'subtype' => '',

		'blackout_dates' => true,
		'google_calendar' => true,
	);

	protected $schedule;

	protected $_queried_appointments;
	protected $_booked_group_appointments;

	public static function create( SSA_Appointment_Type_Object $appointment_type, Period $period, $args = array() ) {
		$instance = new self( $appointment_type, $period, $args );
		return $instance;
	}

	/**
	 * Constructor.
	 *
	 * @since  3.6.2
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct( 
		SSA_Appointment_Type_Object $appointment_type, Period $period, $args = array()
	) {
		$this->period = $period;
		$this->args = array_merge( $this->args, $args );

		$this->appointment_type = $appointment_type;
	}

	public function get_schedule( $args = array() ) {
		if ( null !== $this->schedule ) {
			return $this->schedule;
		}

		$args = array_merge( $this->args, $args );

		$this->schedule = $this->appointment_type->get_schedule( $this->period, $args );

		if ( ! empty( $this->args['blackout_dates'] ) ) {
			$blackout_dates_schedule = ssa()->blackout_dates->get_schedule( $this->appointment_type, $this->period, $args );

			if ( null !== $blackout_dates_schedule ) {
				$this->schedule = $this->schedule->merge( $blackout_dates_schedule );
			}
		}

		if ( empty( $this->appointment_type ) ) {
			return $this->schedule;
		}

		if ( ! empty( $args['google_calendar'] ) ) {
			$google_calendar_schedule = ssa()->google_calendar->get_schedule( $this->appointment_type, $this->period, $args );
			if ( null !== $google_calendar_schedule ) {
				$this->schedule = $this->schedule->merge( $google_calendar_schedule );
			}
		}

		return $this->schedule;
	}

	public function why_not_bookable() {
		// TODO // so we can return an error code "blackout dates"
	}

	public function get_bookable_appointments() {
		if ( empty( $this->appointment_type ) ) {
			throw new SSA_Exception( 'Appointment Type required' );
		}

		$schedule = $this->get_schedule();

		$bookable_appointments = array();

		$availability_interval = $this->appointment_type->get_availability_interval();
		$availability_increment = $this->appointment_type->availability_increment;
		$availability = $this->appointment_type->availability;
		$timezone = $this->appointment_type->get_timezone();
		$duration = $this->appointment_type->duration;
		$capacity_type = $this->appointment_type->capacity_type;
		if ( 'group' === $capacity_type ) {
			$booked_group_appointments = $this->appointment_type->get_appointment_objects( $this->period, array(
				'status' => SSA_Appointment_Model::get_unavailable_statuses(),
			) );
		}

		foreach ($schedule->get_blocks() as $block) {
			if ( $block->capacity_available <= 0 ) {
				continue;
			}

			$starting_minute = (int)$block->get_period()->getStartDate()->format( 'i' );
			$minutes_to_add = 0;
			while( ( $starting_minute + $minutes_to_add ) % $availability_increment !== 0 ) {
				$minutes_to_add++;
			}
			if ( $minutes_to_add ) {
				$start_date = $block->get_period()->getStartDate();
				$end_date = $block->get_period()->getEndDate();
				$new_start_date = $start_date->add( new DateInterval( 'PT'.$minutes_to_add.'M' ) );
				if ( $new_start_date >= $end_date ) {
					continue;
				}
				$block = $block->set_period( new Period( $new_start_date, $end_date ) );
			}

			foreach ( $block->get_period()->split( $availability_interval ) as $period ) {
				if ( 'start_times' === $this->appointment_type->availability_type ) {
					$start_datetime_tz = $period->getStartDate()->setTimezone( $timezone );
					$day_of_week = $start_datetime_tz->format( 'l' );
					if ( empty( $availability[$day_of_week]['0']['time_start'] ) ) {
						continue; // no start times set for this day of the week
					}

					$is_available_start_time = false;
					foreach ( $availability[$day_of_week] as $availability_value ) {
						if ( $availability_value['time_start'] === $start_datetime_tz->format( 'H:i:s' ) ) {
							$is_available_start_time = true;
							break;
						}
					}

					if ( ! $is_available_start_time ) {
						continue;
					}
				}

				if ( 'group' === $capacity_type ) {
					foreach ( $booked_group_appointments as $booked_group_appointment ) {
						if ( $booked_group_appointment->get_buffered_period()->overlaps( $period ) ) {
							if ( $booked_group_appointment->get_appointment_period()->getStartDate() != $period->getStartDate() ) {
								continue 2;
							}
						}
					}
				}

				$appointment = SSA_Appointment_Factory::create( $this->appointment_type, array(
					'start_date' => $period->getStartDate()->format( 'Y-m-d H:i:s' ),
				) );
				if ( ! $this->is_prospective_appointment_bookable( $appointment ) ) {
					continue;
				}

				$bookable_appointments[] = $appointment;
			}
		}

		return $bookable_appointments;
	}

	public function get_bookable_appointment_periods() {
		$bookable_appointment_periods = array();
		$bookable_appointments = $this->get_bookable_appointments();
		foreach ($bookable_appointments as $appointment) {
			$bookable_appointment_periods[] = $appointment->get_appointment_period();
		}

		return $bookable_appointment_periods;
	}

	public function get_bookable_appointment_start_datetimes() {
		$bookable_appointment_start_datetimes = array();
		$bookable_appointments = $this->get_bookable_appointments();
		foreach ($bookable_appointments as $appointment) {
			$bookable_appointment_start_datetimes[] = $appointment->get_appointment_period()->getStartDate();
		}

		return $bookable_appointment_start_datetimes;
	}

	public function get_queried_appointments() {
		if ( null !== $this->_queried_appointments ) {
			return $this->_queried_appointments;
		}

		$queried_appointments_array = $this->get_queried_appointments_array();

		$queried_appointments = array();
		foreach ($queried_appointments_array as $queried_appointment) {
			$queried_appointment = SSA_Appointment_Object::instance( $queried_appointment );
			$queried_appointments[] = $queried_appointment;
		}

		$this->_queried_appointments = $queried_appointments;
		return $this->_queried_appointments;
	}

	public function get_queried_appointments_array() {
		$args = array(
			'number' => -1,
			'orderby' => 'start_date',
			// 'appointment_type_id' => $appointment_type->id,
			'start_date_min' => $this->period->getStartDate()->format( 'Y-m-d H:i:s' ),
			'start_date_max' => $this->period->getEndDate()->format( 'Y-m-d H:i:s' ),
			'status' => SSA_Appointment_Model::get_unavailable_statuses(),
		);

		$queried_appointments_array = ssa()->appointment_model->query( $args );
		return $queried_appointments_array;
	}

	public function get_booked_group_appointments() {
		if ( null !== $this->_booked_group_appointments ) {
			return $this->_booked_group_appointments;
		}

		if ( null === $this->appointment_type ) {
			return;
		}

		$this->_booked_group_appointments = $this->appointment_type->get_appointment_objects( $this->period, array(
			'status' => SSA_Appointment_Model::get_unavailable_statuses(),
		) );

		return $this->_booked_group_appointments;
	}

	public function is_prospective_appointment_bookable( SSA_Appointment_Object $appointment ) {
		$schedule = $this->get_schedule( array(
			'skip_appointment_id' => $appointment->id,
		) );
		if ( null === $schedule ) {
			return false;
		}

		$appointment_buffered_period = $appointment->get_buffered_period();
		$blocks = $schedule->get_blocks_for_period( $appointment_buffered_period );

		if ( ! empty( $this->appointment_type ) ) {
			// We should use the appointment type that we're querying for if it's set (faster than getting appointment type dynamically each run)
			$appointment_type = $this->appointment_type;
		} else {
			$appointment_type = $appointment->get_appointment_type();
		}

		/* Check Buffered Period conflicts */
		if ( $appointment_type->buffer_before || $appointment_type->buffer_after ) {		
			foreach ($blocks as $block) {
				if ( $block->buffer_available <= 0 ) {
					return false;
				}
				
				if ( $block->capacity_available <= 0 && $block->capacity_reserved > 0 ) {
					return false;
				}
			}
		}

		/* Check raw appointment period - less common case, deferred from initial loop for performance reasons */
		$appointment_period = $appointment->get_appointment_period();
		foreach ($blocks as $block) {
			if ( ! $block->get_period()->overlaps( $appointment_period ) ) {
				continue;
			}

			if ( $block->capacity_available <= 0 ) {
				return false;
			}
		}

		if ( 'group' === $appointment_type->capacity_type ) {
			$booked_group_appointments = $this->get_booked_group_appointments();

			foreach ( $booked_group_appointments as $booked_group_appointment ) {
				if ( $booked_group_appointment->get_buffered_period()->overlaps( $appointment_buffered_period ) ) {
					// There might be a potential conflict

					if ( $booked_group_appointment->get_appointment_period()->getStartDate() != $appointment_period->getStartDate() ) {
						// And we've confirmed it's not the exact same start time (since that would be allowed)

						if ( $booked_group_appointment->get_buffered_period()->overlaps( $appointment_period ) ) {
							return false;
						}
						
						if ( $booked_group_appointment->get_appointment_period()->overlaps( $appointment_buffered_period ) ) {
							return false;
						}
					}
				}
			}
		}

		// Other Appointment Types Shared Availability
		$developer_settings = ssa()->developer_settings->get();
		$separate_appointment_type_availability = $developer_settings['separate_appointment_type_availability'];
		if ( $separate_appointment_type_availability ) {
			return true;
		}

		$queried_appointments = $this->get_queried_appointments();

		if ( empty( $queried_appointments ) ) {
			return true;
		}

		foreach ($queried_appointments as $queried_appointment) {
			if ( $queried_appointment->appointment_type_id == $appointment_type->id ) {
				continue;
			}

			if ( $appointment_buffered_period->overlaps( $queried_appointment->get_appointment_period() )) {
				return false;
			}

			if ( $appointment_period->overlaps( $queried_appointment->get_buffered_period() ) ) {
				return false;
			}
		}

		return true;
	}



}
