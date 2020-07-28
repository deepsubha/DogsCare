<?php
/**
 * Simply Schedule Appointments Appointment Object.
 *
 * @since   0.0.3
 * @package Simply_Schedule_Appointments
 */

use League\Period\Period;

/**
 * Simply Schedule Appointments Appointment Object.
 *
 * @since 0.0.3
 */
class SSA_Appointment_Object {
	protected $id = null;
	protected $model = null;
	protected $data = null;
	protected $appointment_type;
	protected $recursive_fetched = -2;

	protected $status;

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
	public function __construct( $id ) {
		if ( $id === 'transient' ) {		
			return;
		}

		$this->id = $id;
	}

	public static function instance( $appointment ) {
		if ( $appointment instanceof SSA_Appointment_Object ) {
			return $appointment;
		}

		if ( is_array( $appointment ) ) {
			$appointment = new SSA_Appointment_Object( $appointment['id'] );
			return $appointment;
		}

		$appointment = new SSA_Appointment_Object( $appointment );
		return $appointment;
	}

	/**
	 * Factory function to create with explicit data
	 *
	 * @param array $data
	 * @return SSA_Appointment_Object
	 * @author 
	 **/
	public static function create( SSA_Appointment_Type_Object $appointment_type, array $data ) {
		$appointment_object = new SSA_Appointment_Object( 'transient' );
		$appointment_object->appointment_type = $appointment_type;
		$data['appointment_type_id'] = $appointment_type->id;
		$appointment_object->data = $data;
		return $appointment_object;
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $field Field to get.
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {
		if ( empty( $this->data ) && $field !== 'id' ) {
			$this->get();
		}

		switch ( $field ) {
			case 'id':
			case 'data':
				return $this->$field;
			case 'start_date_datetime':
			case 'end_date_datetime':
				$date_time = ssa_datetime( $this->data[str_replace('_datetime', '', $field)] );
				return $date_time;
			case 'start_date_timestamp':
			case 'end_date_timestamp':
				return ssa_gmtstrtotime( $this->data[str_replace('_timestamp', '', $field)] );
			default:
				if ( isset( $this->data[$field] ) ) {
					return $this->data[$field];
				}
				
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}

	public function get( $recursive = -1 ) {
		if ( $recursive > $this->recursive_fetched ) {
			if ( null === $this->data ) {
				$this->data = array();
			}

			$this->data = array_merge( $this->data, ssa()->appointment_model->get( $this->id, $recursive ) );
			$this->recursive_fetched = $recursive;
		}
	}

	public function get_appointment_period() {
		return new Period(
			$this->__get( 'start_date' ),
			$this->__get( 'end_date' )
		);
	}

	public function get_buffer_before_period() {
		$buffer_before = $this->get_appointment_type()->buffer_before;
		if ( empty( $buffer_before ) ) {
			return false;
		}

		$buffer_before = '-' . absint( $buffer_before ) . ' MIN';
		$calculated_period = new Period( $this->__get( 'start_date' ), $this->__get( 'start_date' ) );
		$calculated_period = $calculated_period->moveStartDate( $buffer_before );
		
		return $calculated_period;
	}

	public function get_buffer_after_period() {
		$buffer_after = $this->get_appointment_type()->buffer_after;
		if ( empty( $buffer_after ) ) {
			return false;
		}

		$buffer_after = '+' . absint( $buffer_after ) . ' MIN';
		$calculated_period = new Period( $this->__get( 'end_date' ), $this->__get( 'end_date' ) );
		$calculated_period = $calculated_period->moveEndDate( $buffer_after );
		
		return $calculated_period;
	}

	public function get_buffered_period() {
		$period = $this->get_appointment_period();

		$buffer_before_period = $this->get_buffer_before_period();
		$buffer_after_period = $this->get_buffer_after_period();

		if ( false === $buffer_before_period && false === $buffer_after_period ) {
			return $period;
		}

		if ( false !== $buffer_before_period ) {
			$period = new Period( $buffer_before_period->getStartDate(), $period->getStartDate() );
		}
		if ( false !== $buffer_after_period ) {
			$period = new Period( $period->getStartDate(), $buffer_after_period->getEndDate() );
		}

		return $period;
	}


	public function get_data( $recursive = -1, $fetch_fields = array() ) {
		$this->get( $recursive );

		if ( $recursive >= 0 ) {
			if ( !isset( $fetch_fields['public_edit_url'] ) ) {
				$fetch_fields['public_edit_url'] = true;
			}
			if ( !isset( $fetch_fields['date_timezone'] ) ) {
				$fetch_fields['date_timezone'] = true;
			}
		}

		if ( !empty( $fetch_fields ) ) {
			$this->fetch_fields( $fetch_fields );
		}

		return $this->data;
	}

	public function fetch_fields( $fetch_fields = array() ) {
		if ( !is_array( $fetch_fields ) ) {
			throw new SSA_Exception("$fetch_fields must be an array", 1);
		}

		foreach ( $fetch_fields as $fetch_field => $fetch_options ) {
			if ( is_int( $fetch_field ) ) {
				$fetch_field = $fetch_options;
				$fetch_options = array();
			}

			$method_name = 'fetch_'.$fetch_field;
			if ( !method_exists( $this, $method_name ) ) {
				throw new SSA_Exception(__CLASS__ . "->" . $method_name . "() not implemented", 1);
			}

			$this->$method_name( $fetch_options );
		}
	}

	public function fetch_add_to_calendar_links( $atts = array() ) {
		if ( !is_array( $atts ) ) {
			$atts = array();
		}

		$atts = shortcode_atts( array(
			'customer' => true,
		), $atts );

		if ( !empty( $atts['customer'] ) ) {
			$this->data['ics']['customer'] = $this->get_ics( 'customer' )['file_url'];
			$this->data['gcal']['customer'] = $this->get_gcal_add_link( 'customer' );
		}
	}

	public function fetch_date_timezone( $atts = array() ) {
		$this->data['date_timezone'] = $this->get_date_timezone();
	}

	public function fetch_public_edit_url( $atts = array() ) {
		$this->data['public_edit_url'] = $this->get_public_edit_url();
	}

	public function get_appointment_type() {
		if ( ! empty( $this->appointment_type ) ) {
			return $this->appointment_type;
		}

		$this->appointment_type = new SSA_Appointment_Type_Object( $this->appointment_type_id );
		return $this->appointment_type;
	}

	public function get_date_timezone( $for_type = '', $for_id = '' ) {
		if ( $for_type === 'staff' ) {
			// TODO: Customize for staff ID
			// TODO: Customize for location ID
			// TODO: Customize for customer
			// TODO: Customize for admin
		} else {
			$settings = ssa()->settings->get();
			$date_timezone = new DateTimeZone( $settings['global']['timezone_string'] );
		}

		return $date_timezone;
	}

	public function get_customer_name() {
		$customer_information = $this->__get( 'customer_information' );
		$customer_name = '';
		if ( ! empty( $customer_information['name'] ) ) {
			$customer_name = $customer_information['name'];
		} elseif ( ! empty( $customer_information['Name'] ) ) {
			$customer_name = $customer_information['Name'];
		}

		return $customer_name;
	}

	public function get_calendar_event_title( SSA_Recipient $recipient ) {
		if ( $recipient->is_customer() ) {
			$settings = ssa()->settings->get();
			$sitename = $settings['global']['company_name'];
			$title = $this->get_appointment_type()->title .' (' . $sitename . ')';
		} elseif ( $recipient->is_business() ) {
			if ( $this->is_group_event() ) {
				$title = $this->get_appointment_type()->title;
			} elseif ( $this->is_individual_appointment() ) {
				$title = $this->get_customer_name() . ' - ' . $this->get_appointment_type()->title;
			}
		}

		if ( $this->is_group_event() ) {
			if ( $this->is_group_canceled() ) {
				$title = __( 'Canceled', 'simply-schedule-appointments' ) . ': ' . $title;
			}
		} elseif ( $this->is_individual_appointment() ) {		
			if ( $this->is_canceled() ) {
				$title = __( 'Canceled', 'simply-schedule-appointments' ) . ': ' . $title;
			}
		}

		return $title;
	}

	public function get_calendar_event_description( SSA_Recipient $recipient ) {
		$description = '';
		$eol = "\r\n";

		if ( $recipient->is_business() ) {
			if ( $this->is_group_event() ) {
				$appointments = $this->query_group_appointments();
				$description .= __( 'Attendees', 'simply-schedule-appointments' ) . ':' . $eol;
				foreach ($appointments as $appointment) {
					if ( ! $appointment->is_booked() ) {
						continue;
					}

					$description .= $appointment->get_customer_name() . $eol;
				}
			} elseif ( $this->is_individual_appointment() ) {			
				$customer_information = $this->__get( 'customer_information' );

				foreach ($customer_information as $label => $value) {
					$description .= ucwords( str_replace( '_', ' ', $label ) ) . ': ';
					if ( is_array( $value ) ) {
						$value = implode( ', ', $value );
					}

					$description .= $value . $eol;
				}
			}
		}

		return $description;
	}

	public function get_customer_calendar_title() {
		return $this->get_calendar_event_title( SSA_Recipient_Customer::create() );
	}

	public function get_description( $template, $eol = "\r\n" ) {
		$description = '';
		if ( $template == 'staff' ) {
			$customer_information = $this->customer_information;
			foreach ($customer_information as $label => $value) {
				$description .= ucwords( str_replace( '_', ' ', $label ) ) . ': ';
				$description .= $value . $eol;
			}
		} elseif ( $template == 'customer' ) {
			$description = $this->get_appointment_type()->description;
			$instructions = $this->get_appointment_type()->instructions; 
			if ( !empty( $instructions ) ) {
				$description = $instructions . $eol . $description;
			}

			$description .= $eol . "Cancel/Reschedule link:"."\r\n";
			$description .= $this->get_public_edit_url()."\r\n";
		}

		return $description;
	}

	public function get_calendar_id() {
		$group = $this->get_group_appointment();
		if ( ! empty( $group ) ) {
			return $group->google_calendar_id;
		}

		return $this->__get( 'google_calendar_id' );
	}

	public function get_calendar_event_id() {
		$group = $this->get_group_appointment();
		if ( ! empty( $group ) ) {
			return $group->google_calendar_event_id;
		}

		return $this->__get( 'google_calendar_event_id' );
	}

	public function get_ics_exporter( $template = 'customer' ) {
		$ics_exporter = new SSA_Ics_Exporter();
		$ics_exporter->template = $template;

		return $ics_exporter;
	}

	public function get_ics( $template = 'customer' ) {
		$ics_exporter = $this->get_ics_exporter( $template );
		$ics = $ics_exporter->get_ics_for_appointment( $this );

		return $ics;
	}

	public function get_gcal_add_link( $template = 'customer' ) {
		$link = ssa()->gcal_exporter->get_add_link_from_appointment( $this, $template );

		return $link;
	}


	public function is_all_day() {
		return false;
	}

	public function get_public_edit_url() {
		$url = ssa()->appointment_model->get_public_edit_url( $this->id );
		return $url;
	}
	public function get_admin_edit_url() {
		$url = ssa()->appointment_model->get_admin_edit_url( $this->id );
		return $url;
	}

	public function is_unavailable() {
		return in_array( $this->__get( 'status' ), SSA_Appointment_Model::get_unavailable_statuses() );
	}
	public function is_available() {
		return ! $this->is_unavailable();
	}
	public function is_reserved() {
		return in_array( $this->__get( 'status' ), SSA_Appointment_Model::get_reserved_statuses() );
	}
	public function is_booked() {
		return in_array( $this->__get( 'status' ), SSA_Appointment_Model::get_booked_statuses() );
	}
	public function is_canceled() {
		return in_array( $this->__get( 'status' ), SSA_Appointment_Model::get_canceled_statuses() );
	}
	public function is_group_canceled() {
		if ( ! $this->is_group_event() ) {
			return null;
		}

		$group_id = $this->__get( 'group_id' );
		if ( empty( $group_id ) ) {
			return false;
		}

		$appointment_arrays = ssa()->appointment_model->query( array(
			'number' => -1,
			'group_id' => $group_id,
		) );
		if ( empty( $appointment_arrays ) ) {
			return false;
		}

		$is_group_canceled = true;
		foreach ($appointment_arrays as $appointment_array) {
			if ( in_array( $appointment_array['status'], SSA_Appointment_Model::get_booked_statuses() ) ) {
				return false;
			}
		}

		return $is_group_canceled;
	}

	public function get_group_appointment() {
		if ( ! $this->is_group_event() ) {
			return;
		}

		$group_id = $this->__get( 'group_id' );
		if ( empty( $group_id ) ) {
			return;
		}

		$group = new SSA_Appointment_Object( $group_id );
		return $group;
	}

	public function query_group_appointments() {
		if ( ! $this->is_group_event() ) {
			return;
		}

		$group_id = $this->__get( 'group_id' );
		if ( empty( $group_id ) ) {
			return;
		}

		$groups = ssa()->appointment_model->query( array(
			'number' => -1,
			'group_id' => $group_id,
		) );

		$group_objects = array();
		foreach ($groups as $group) {
			$group_objects[] = new SSA_Appointment_Object( $group['id'] );
		}

		return $group_objects;
	}

	public function is_group_event() {
		$capacity_type = $this->get_appointment_type()->capacity_type;
		return ( $capacity_type === 'group' );
	}

	public function is_individual_appointment() {
		$capacity_type = $this->get_appointment_type()->capacity_type;
		return ( $capacity_type === 'individual' );
	}

}
