<?php
/**
 * Simply Schedule Appointments Customer Information.
 *
 * @since   3.1.0
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Customer Information.
 *
 * @since 3.1.0
 */
class SSA_Customer_Information {
	/**
	 * Parent plugin class.
	 *
	 * @since 3.1.0
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  3.1.0
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
	 * @since  3.1.0
	 */
	public function hooks() {
	}

	public function get_defaults() {
		$defaults = array(
			'Name' => '',
			'Email' => '',
		);

		if ( is_user_logged_in() && ! current_user_can( 'edit_users' ) && ! current_user_can( 'ssa_manage_staff' ) ) {
			$current_user = new WP_User( get_current_user_id() );
			if ( ! empty( $current_user ) && ! empty( $current_user->data ) ) {
				if ( ! empty( $current_user->data->display_name ) ) {
					$defaults['Name'] = $current_user->data->display_name;
				}
				if ( ! empty( $current_user->data->user_email ) ) {
					$defaults['Email'] = $current_user->data->user_email;
				}
			}
		}

		$defaults = apply_filters( 'ssa/appointments/customer_information/get_defaults', $defaults );

		return $defaults;
	}
}
