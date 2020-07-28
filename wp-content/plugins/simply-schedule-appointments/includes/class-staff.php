<?php
/**
 * Simply Schedule Appointments Staff.
 *
 * @since   0.8.0
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Staff.
 *
 * @since 0.8.0
 */
class SSA_Staff {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.8.0
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.8.0
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
	 * @since  0.8.0
	 */
	public function hooks() {
		add_filter( 'ssa/appointment/get_item_permissions_check', array( $this, 'filter_appointment_get_item_permissions_check' ), 10, 2 );
		add_filter( 'query_appointment_db_where_conditions', array( $this, 'restrict_appointment_queries_by_staff' ), 10, 2 );
		add_filter( 'query_payment_db_where_conditions', array( $this, 'restrict_payment_queries_by_staff' ), 10, 2 );
	}

	public function restrict_appointment_queries_by_staff( $where, $args ) {
		if ( current_user_can( 'ssa_manage_others_appointments' ) ) {
			return $where;
		}

		// TODO: 
		
		// $where .= " AND `".$this->plugin->appointment_model->get_primary_key()."` IN( SELECT appointment_id FROM appointments_join_staff WHERE staff_id = current_user_id ) ";

		return $where;
	}
	public function restrict_payment_queries_by_staff( $where, $args ) {
		if ( current_user_can( 'ssa_manage_others_appointments' ) ) {
			return $where;
		}

		// TODO: 
		
		// $where .= " AND `appointment_id` IN( SELECT appointment_id FROM appointments_join_staff WHERE staff_id = current_user_id ) ";

		return $where;
	}

	public function filter_appointment_get_item_permissions_check( $response, $params ) {
		if ( empty( $params['id'] ) ) {
			return $response;
		}

		if ( ! current_user_can( 'ssa_manage_appointments' ) ) {
			return $response;
		}

		$staff = $this->get_staff_ids_for_appointment_id( $params['id'] );
		if ( empty( $staff ) ) {
			return true;
		}

		if ( in_array( get_current_user_id(), $staff ) ) {
			return true;
		}

		return $response;
	}

	public function get_staff_ids_for_appointment_id( $appointment_id ) {
		return array();

		// TODO: query join table and return array of staff_ids
		// return array( 1, 2, 3 );
		// Test with class-appointment-model: get_item_permissions_check()
	}
}
