<?php
/**
 * Simply Schedule Appointments Ics Exporter.
 *
 * @since   0.0.3
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Ics Exporter.
 *
 * @since 0.0.3
 */
class SSA_Ics_Exporter {
	public $template = 'customer';

	/**
	 * Appointments list to export
	 *
	 * @var array
	 */
	protected $appointments = array();

	/**
	 * File path
	 *
	 * @var string
	 */
	protected $file_path = '';

	/**
	 * UID prefix.
	 *
	 * @var string
	 */
	protected $uid_prefix = 'ssa_';

	/**
	 * End of line.
	 *
	 * @var string
	 */
	protected $eol = "\r\n";

	/**
	 * Get appointment .ics
	 *
	 * @param  SSA_Appointment_Object $appointment Booking data
	 *
	 * @return string .ics path
	 */
	public function get_ics_for_appointment( $appointment, $template = 'customer', $filename = '' ) {
		$this->appointments = array( $appointment );

		return $this->get_ics( $this->appointments, $template, $filename );
	}

	/**
	 * Get .ics for appointments.
	 *
	 * @param  array  $appointments Array with SSA_Appointment_Object objects
	 * @param  string $filename .ics filename
	 *
	 * @return string .ics path
	 */
	public function get_ics( $appointments, $template = 'customer', $filename = '' ) {
		$this->appointments = $appointments;
		$this->template = $template;

		if ( '' == $filename ) {
			$filename = 'appointment-' . time() . '-' . wp_hash( json_encode( $this->appointments ) . $this->template );
		}

		$this->file_path = $this->get_file_path( $filename );
		$this->file_url = $this->get_file_url( $filename );

		// Create the .ics
		$this->create();

		return array(
			'file_path' => $this->file_path,
			'file_url' => $this->file_url,
		);
	}

	/**
	 * Get file path
	 *
	 * @param  string $filename Filename
	 *
	 * @return string
	 */
	protected function get_file_path( $filename ) {
		$path = SSA_Filesystem::get_uploads_dir_path();
		if ( empty( $path ) ) {
			return false;
		}

		$path .= '/ics';
		if ( ! wp_mkdir_p( $path ) ) {
			return false;
		}

		return $path . '/' . sanitize_title( $filename ) . '.ics';
	}

	/**
	 * Get file url
	 *
	 * @param  string $filename Filename
	 *
	 * @return string
	 */
	protected function get_file_url( $filename ) {
		$url = SSA_Filesystem::get_uploads_dir_url();
		if ( empty( $url ) ) {
			return false;
		}

		$url .= '/ics';

		return $url . '/' . sanitize_title( $filename ) . '.ics';
	}

	/**
	 * Create the .ics file
	 *
	 * @return void
	 */
	protected function create() {
		// @codingStandardIgnoreStart
		$handle = @fopen( $this->file_path, 'w' );
		$ics = $this->generate();
		@fwrite( $handle, $ics );
		@fclose( $handle );
		// @codingStandardIgnoreEnd
	}

	/**
	 * Format the date
	 *
	 * @param int        $timestamp Timestamp to format.
	 * @param SSA_Appointment_Object $appointment   Booking object.
	 *
	 * @return string Formatted date for ICS.
	 */
	protected function format_date( $timestamp, $appointment = null ) {
		$pattern = 'Ymd\THis';

		if ( $appointment ) {
			$pattern = ( $appointment->is_all_day() ) ? 'Ymd' : $pattern;

			// If we're working on the end timestamp
			if ( $appointment->end_date_timestamp === $timestamp ) {
				// If appointments are more than 1 day, ics format for the end date should be the day after the appointment ends
				if ( strtotime( 'midnight', $appointment->start_date_timestamp ) !== strtotime( 'midnight', $appointment->end_date_timestamp ) ) {
					$timestamp += 86400;
				}
			}
		}

		$formatted_date = gmdate( $pattern, $timestamp );
		$formatted_date .= 'Z'; // Zulu (UTC)
		
		return $formatted_date;
	}

	/**
	 * Sanitize strings for .ics
	 *
	 * @param  string $string
	 *
	 * @return string
	 */
	protected function sanitize_string( $string ) {
		$string = preg_replace( '/([\,;])/', '\\\$1', $string );
		$string = str_replace( "\n", '\n', $string );
		$string = sanitize_text_field( $string );

		return $string;
	}

	/**
	 * Generate the .ics content
	 *
	 * @return string
	 */
	protected function generate() {
		$settings = Simply_Schedule_Appointments::get_instance()->settings->get();
		$sitename = $settings['global']['company_name'];

		// Set the ics data.
		$ics = 'BEGIN:VCALENDAR' . $this->eol;
		$ics .= 'VERSION:2.0' . $this->eol;
		$ics .= 'PRODID:-//SSA//Simply Schedule Appointments ' . Simply_Schedule_Appointments::VERSION . '//EN' . $this->eol;
		$ics .= 'CALSCALE:GREGORIAN' . $this->eol;
		$ics .= 'X-ORIGINAL-URL:' . $this->sanitize_string( home_url( '/' ) ) . $this->eol;
		$ics .= 'X-WR-CALDESC:' . $this->sanitize_string( sprintf( __( 'Appointments from %s', 'simply-schedule-appointments' ), $sitename ) ) . $this->eol;
		$ics .= 'TRANSP:' . 'OPAQUE' . $this->eol;

		foreach ( $this->appointments as $appointment ) {
			if ( $this->template === 'customer' ) {
				$url         = "";
				$summary     = $appointment->get_customer_calendar_title();
				$description = $appointment->get_description( 'customer' );
				$date_prefix = ( $appointment->is_all_day() ) ? ';VALUE=DATE:' : ':';
			} elseif ( $this->template === 'staff' ) {
				$url = Simply_Schedule_Appointments::get_instance()->wp_admin->url( 'ssa/appointments' );
				$summary = $customer_information['name'];
				$description = $appointment->get_description( 'staff' );
				$date_prefix = ( $appointment->is_all_day() ) ? ';VALUE=DATE:' : ':';
			}

			$ics .= 'BEGIN:VEVENT' . $this->eol;
			$ics .= 'UID:' . $this->uid_prefix . $appointment->id . $this->template . $this->eol;
			$ics .= 'DTSTAMP:' . $this->format_date( time() ) . $this->eol;
			$ics .= 'LOCATION:' . $this->eol;
			$ics .= 'DESCRIPTION:' . $this->sanitize_string( $description ) . $this->eol;
			$ics .= 'URL;VALUE=URI:' . $this->sanitize_string( $url ) . $this->eol;
			$ics .= 'SUMMARY:' . $this->sanitize_string( $summary ) . $this->eol;
			$ics .= 'DTSTART' . $date_prefix . $this->format_date( $appointment->start_date_timestamp, $appointment ) . $this->eol;
			$ics .= 'DTEND' . $date_prefix . $this->format_date( $appointment->end_date_timestamp, $appointment ) . $this->eol;
			$ics .= 'END:VEVENT' . $this->eol;
		}

		$ics .= 'END:VCALENDAR';

		return $ics;
	}

}
