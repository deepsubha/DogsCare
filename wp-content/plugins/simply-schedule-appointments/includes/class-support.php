<?php
/**
 * Simply Schedule Appointments Support.
 *
 * @since   2.1.6
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Support.
 *
 * @since 2.1.6
 */
class SSA_Support {
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
		add_action( 'admin_init', array( $this, 'fix_appointment_durations' ) );
		add_action( 'admin_init', array( $this, 'reset_settings' ) );
		add_action( 'admin_init', array( $this, 'rebuild_db' ) );
	}

	public function fix_appointment_durations() {
		if ( empty( $_GET['ssa-fix-appointment-durations'] ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$appointments = $this->plugin->appointment_model->query( array(
			'number' => -1,
		) );
		$now = new DateTimeImmutable();

		foreach ($appointments as $key => $appointment) {
			$appointment_type = new SSA_Appointment_Type_Object( $appointment['appointment_type_id'] );
			$duration = $appointment_type->duration;
			$start_date = new DateTimeImmutable( $appointment['start_date'] );

			$end_date = $start_date->add( new DateInterval( 'PT' .$duration. 'M' ) );
			if ( $end_date->format( 'Y-m-d H:i:s' ) != $appointment['end_date'] ) {
				echo '<pre>'.print_r($appointment, true).'</pre>';
				$appointment['end_date'] = $end_date->format( 'Y-m-d H:i:s' );

				$this->plugin->appointment_model->update( $appointment['id'], $appointment );
			}
		}

		wp_redirect( $this->plugin->wp_admin->url(), $status = 302);
		exit;
	}

	public function reset_settings() {
		if ( empty( $_GET['ssa-reset-settings'] ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$options_to_delete = array(
			'wp_ssa_appointments_db_version',
			'wp_ssa_appointment_meta_db_version',
			'wp_ssa_appointment_types_db_version',
			'wp_ssa_availability_db_version',
			'wp_ssa_async_actions_db_version',
			'wp_ssa_staff_relationships_db_version',
			'wp_ssa_payments_db_version',
			'ssa_settings_json',
			'ssa_versions',
		);

		foreach ($options_to_delete as $option_name) {
			delete_option( $option_name );
		}

		wp_redirect( $this->plugin->wp_admin->url(), $status = 302);
		exit;
	}

	public function rebuild_db() {
		if ( empty( $_GET['ssa-rebuild-db'] ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$options_to_delete = array(
			'wp_ssa_appointments_db_version',
			'wp_ssa_appointment_meta_db_version',
			'wp_ssa_appointment_types_db_version',
			'wp_ssa_availability_db_version',
			'wp_ssa_async_actions_db_version',
			'wp_ssa_staff_relationships_db_version',
			'wp_ssa_payments_db_version',
		);

		foreach ($options_to_delete as $option_name) {
			delete_option( $option_name );
		}

		wp_redirect( $this->plugin->wp_admin->url(), $status = 302);
		exit;
	}
}
