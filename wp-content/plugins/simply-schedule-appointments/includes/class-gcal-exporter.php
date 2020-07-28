<?php
/**
 * Simply Schedule Appointments Gcal Exporter.
 *
 * @since   0.0.3
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Gcal Exporter.
 *
 * @since 0.0.3
 */
class SSA_Gcal_Exporter {
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

	public function get_add_link_from_appointment( $appointment, $template = 'customer' ) {
		$link = 'https://www.google.com/calendar/event';
		$link = add_query_arg( array(
			'action' => 'TEMPLATE',
			'text' => urlencode( $appointment->get_customer_calendar_title() ),
			'dates' => date('Ymd', $appointment->start_date_timestamp).'T'.date('His', $appointment->start_date_timestamp).'Z' . '/' . date('Ymd', $appointment->end_date_timestamp).'T'.date('His', $appointment->end_date_timestamp).'Z',
			'details' => urlencode( $appointment->get_description( 'customer' ) ),
			'location' => '',
			'trp' => false,
			'sprop' => 'name:',
		), $link );

		return $link;
	}
}
