<?php
/**
 * Simply Schedule Appointments Support Status Api.
 *
 * @since   2.1.6
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Support Status Api.
 *
 * @since 2.1.6
 */
class SSA_Support_Status_Api extends WP_REST_Controller {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since 1.0.0
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function hooks() {
		$this->register_routes();
	}


	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version = '1';
		$namespace = 'ssa/v' . $version;
		$base = 'support_status';
		register_rest_route( $namespace, '/' . $base, array(
			array(
				'methods'         => WP_REST_Server::READABLE,
				'callback'        => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'            => array(

				),
			),
		) );

		register_rest_route( $namespace, '/' . 'support_ticket', array(
			array(
				'methods'         => WP_REST_Server::CREATABLE,
				'callback'        => array( $this, 'create_support_ticket' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'            => array(

				),
			),
		) );
	}

	public function create_support_ticket( $request ) {
		$params = $request->get_params();
		$response = wp_remote_post( 'https://api.simplyscheduleappointments.com/support_ticket/', array(
			'headers' => array(
				'content-type' => 'application/json',
			),
			'body' => json_encode( $params ),
		) );
		$response = wp_remote_retrieve_body( $response );
		if ( empty( $response ) ) {
			return new WP_Error( 'empty_response', __( 'No response', 'simply-schedule-appointments' ) );
		}
		$response = json_decode( $response, true );
		if ( ! is_array( $response ) ) {
			$response = json_decode( $response, true );
		}

		if ($response['status'] != 'success' ) {
			return new WP_Error( 'failed_submission', __( 'Your support ticket failed to be sent, please send details to support@simplyscheduleappointments.com', 'simply-schedule-appointments' ) );
		}

		return $response;
	}

	public function get_items_permissions_check( $request ) {
		return current_user_can( 'ssa_manage_site_settings' );
	}

	public function get_items( $request ) {
		$params = $request->get_params();

		return array(
			'response_code' => 200,
			'error' => '',
			'data' => array(
				'site_status' => $this->plugin->support_status->get_site_status(),
			),
		);
	}

}
