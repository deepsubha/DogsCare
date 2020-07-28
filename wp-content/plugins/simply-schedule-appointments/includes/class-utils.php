<?php
/**
 * Simply Schedule Appointments Utils.
 *
 * @since   0.0.3
 * @package Simply_Schedule_Appointments
 */
use League\Period\Period;

/**
 * Simply Schedule Appointments Utils.
 *
 * @since 0.0.3
 */
class SSA_Utils {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.3
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	protected $server_default_timezone_before_ssa;

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

	public function defensive_timezone_fix() {
		if ( 'UTC' === date_default_timezone_get() ) {
			return;
		}

		$this->server_default_timezone_before_ssa = date_default_timezone_get();
		
		// We know that setting the default_timezone on a server is bad practice
		// WordPress expects it to be UTC to function properly
		// Our plugin also expects it to be UTC to function properly
		// Unfortunately we have found that some plugins do change the default timezone
		// We only call this function as a defensive measure, so SSA can co-exist with plugins
		// that set the timezone. Looking at you...
		// 
		// * Ajax Event Calendar plugin [https://wordpress.org/support/plugin/ajax-event-calendar] 
		// *** already removed from the wordpress.org repository
		// 
		// * Series Engine plugin [https://seriesengine.com/]
		// ** Pro plugin not available on wordpress.org
		// 
		// Here's our approach to addressing this issue:
		// We ONLY set the timezone to UTC if it's something different
		// 
		// We feel that it should be forced to UTC to adhere to WordPress standards, 
		// but that will probably break users' sites running these problematic plugins
		// 
		// To try and play nicely with others and to protect the user, we will call our
		// defensive_timezone_fix() before SSA does anything where we rely on a UTC timezone
		// at the end of our functions, we will call defensive_timezone_reset() so we'll put it
		// back to whatever the server already had set.
		// 
		// We see this as the only way to co-exist with these problematic plugins and simplify
		// life for the user. If there is a better approach, please get in touch.
		// We'd love to remove this code :)
		date_default_timezone_set( 'UTC' );
	}
	public function defensive_timezone_reset() {
		if ( empty( $this->server_default_timezone_before_ssa ) || 'UTC' === $this->server_default_timezone_before_ssa ) {
			return;
		}

		// We know that setting the default_timezone on a server is bad practice
		// ^^^ See note above in defensive_timezone_fix() ^^^
		date_default_timezone_set( $this->server_default_timezone_before_ssa );
	}

	public static function hash( $string ) {
		if ( defined( 'SSA_AUTH_SALT' ) ) {
			$salt = SSA_AUTH_SALT;
		} else {
			$salt = '6U2aRk6oGvAZAEXstbFNMppRF=D|H.NX!-gU:-aXGVH<)8kcF~FPor5{Z<SFr~wKz';
		}
		
		return hash_hmac('md5', $string, $salt);
	}

	public static function get_home_id() {
		return self::hash( get_home_url() );
	}

	public static function is_assoc_array( array $arr ) {
		if ( array() === $arr ) return false;
		return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
	}

	public static function array_key( $array, $key ) {
		if ( isset( $array[ $key ] ) ) {
			return $array[ $key ];
		}

		return false;
	}

	public static function datetime( $time='now' ) {
		if ( $time instanceof DateTimeImmutable ) {
			return $time;
		}
		
		if ( 0 === strpos( $time, 'Invalid Date' ) ) {
			ssa_debug_log( 'SSA_Utils::datetime()  `Invalid Date` detected' );
			// $time = 'now';
			return null; // TODO: handle error state gracefully
		} else if ( empty( $time ) ) {
			$time = 'now';
		}
		
		$timezone = new DateTimeZone( 'UTC' );

		return new DateTimeImmutable( $time, $timezone );
	}

	public static function ceil_datetime( DateTimeImmutable $datetime, $mins = 5 ) {
		$seconds = $mins * 60;
		$time = ( ceil( $datetime->getTimestamp() / $seconds ) ) * $seconds;

		return $datetime->setTimestamp( $time );
	}

	public static function floor_datetime( DateTimeImmutable $datetime, $mins = 5 ) {
		$seconds = $mins * 60;
		$time = ( floor( $datetime->getTimestamp() / $seconds ) ) * $seconds;

		return $datetime->setTimestamp( $time );
	}

	public static function get_datetime_in_utc( $datetime, $datetimezone='UTC' ) {
		if ( ! ( $datetimezone instanceof DateTimeZone ) ) {
			$datetimezone = new DateTimeZone( $datetimezone );
		}

		if ( ! ( $datetime instanceof DateTimeImmutable ) ) {
			$datetime = new DateTimeImmutable( $datetime, $datetimezone );
		}

		$datetime = $datetime->setTimezone( new DateTimeZone( 'UTC' ) );
		return $datetime;
	}

	public static function get_period_in_utc( Period $period ) {
		return self::get_period_in_timezone( $period, new DateTimeZone( 'UTC' ) );
	}

	public static function get_period_in_timezone( Period $period, DateTimeZone $timezone ) {
		return new Period(
			$period->getStartDate()->setTimezone( $timezone ),
			$period->getEndDate()->setTimezone( $timezone )
		);
	}

	public function get_datetime_as_local_datetime( $datetime, $appointment_type_id=0, $staff_id = 0, $location_id = 0 ) {
		$local_timezone = $this->get_datetimezone( $appointment_type_id, $staff_id, $location_id );

		if ( empty( $local_timezone ) || ! ( $local_timezone instanceof DateTimeZone ) ) {
			throw new SSA_Exception( __( 'No $local_timezone defined' ), 500 );
		}

		if ( $datetime instanceof DateTime ) {
			$datetime = new DateTimeImmutable( $datetime );
		} elseif ( ! ( $datetime instanceof DateTimeImmutable ) ) {
			$utc_timezone = new DateTimeZone( 'UTC' );
			$datetime = new DateTimeImmutable( $datetime, $utc_timezone );
			$datetime = $datetime->setTimezone( $local_timezone );
		} else {
			$datetime = $datetime->setTimezone( $local_timezone );
		}


		return $datetime;
	}

	public function get_datetimezone( $appointment_type_id = 0, $staff_id = 0, $location_id = 0 ) {
		if ( !empty( $staff_id ) ) {
			throw new SSA_Exception( __( 'Staff members are not supported yet' ), 500 );
		} elseif ( !empty( $location_id ) ) {
			throw new SSA_Exception( __( 'Locations are not supported yet' ), 500 );
		} else {
			$local_timezone = $this->plugin->settings_global->get_datetimezone();
		}

		return $local_timezone;
	}

	public static function localize_default_date_strings( $php_date_format ) {
		if ( 'F j, Y' === $php_date_format ) {
			$php_date_format = __( 'F j, Y', 'default' );	
		} else if ( 'g:i a' === $php_date_format ) {
			$php_date_format = __( 'g:i a', 'default' );	
		} else if ( 'F j, Y g:i a' === $php_date_format ) {
			$php_date_format = __( 'F j, Y g:i a', 'default' );	
		}

		return $php_date_format;
	}

	public static function php_to_moment_format($php_date_format) {
		$php_date_format = self::localize_default_date_strings( $php_date_format );

	    $replacements = array(
	        '\\h' => '[h]',
	        '\\m\\i\\n' => '[min]',
	        '\\m' => '[m]',
	        '\\' => '',

	        'd' => 'DD',
	        'D' => 'ddd',
	        'j' => 'D',
	        'l' => 'dddd',
	        'N' => 'E',
	        'S' => 'o',
	        'w' => 'e',
	        'z' => 'DDD',
	        'W' => 'W',
	        'F' => 'MMMM',
	        'm' => 'MM',
	        'M' => 'MMM',
	        'n' => 'M',
	        't' => '', // no equivalent
	        'L' => '', // no equivalent
	        'o' => 'YYYY',
	        'Y' => 'YYYY',
	        'y' => 'YY',
	        'a' => 'a',
	        'A' => 'A',
	        'B' => '', // no equivalent
	        'g' => 'h',
	        'G' => 'H',
	        'h' => 'hh',
	        'H' => 'HH',
	        'i' => 'mm',
	        's' => 'ss',
	        'u' => 'SSS',
	        'e' => 'zz', // deprecated since version 1.6.0 of moment.js
	        'I' => '', // no equivalent
	        'O' => '', // no equivalent
	        'P' => '', // no equivalent
	        'T' => '', // no equivalent
	        'Z' => '', // no equivalent
	        'c' => '', // no equivalent
	        'r' => '', // no equivalent
	        'U' => 'X',
	    );

	    $moment_format = strtr($php_date_format, $replacements);

	    return $moment_format;
	}

	public static function moment_to_php_format($moment_date_format) {
	    $replacements = array(
	        'DD' => 'd', 
	        'ddd' => 'D', 
	        'D' => 'j', 
	        'dddd' => 'l', 
	        'E' => 'N', 
	        'o' => 'S', 
	        'e' => 'w', 
	        'DDD' => 'z', 
	        'W' => 'W', 
	        'MMMM' => 'F', 
	        'MM' => 'm', 
	        'MMM' => 'M', 
	        'M' => 'n', 
	        'YYYY' => 'o', 
	        'YYYY' => 'Y', 
	        'YY' => 'y', 
	        'a' => 'a', 
	        'A' => 'A', 
	        'h' => 'g', 
	        'H' => 'G', 
	        'hh' => 'h', 
	        'HH' => 'H', 
	        'mm' => 'i', 
	        'ss' => 's', 
	        'SSS' => 'u', 
	        'zz' => 'e',  // deprecated since version 1.6.0 of moment.js
	        'X' => 'U', 
	    );
	    
	    $php_format = strtr($moment_date_format, $replacements);

	    return $php_format;
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	public static function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public static function get_appointment_type( $appointment_type ) {
		if ( (int)$appointment_type == $appointment_type ) {
			$appointment_type = ssa()->appointment_type_model->get( $appointment_type );
		}
		if ( empty( $appointment_type['id'] ) ) {
			return false;
		}

		return $appointment_type;
	}

	public static function get_query_period( Period $query_period = null ) {
		if ( null === $query_period ) {
			$query_period = SSA_Constants::EPOCH_PERIOD();
		}

		return $query_period;
	}
}

function ssa_datetime( $time='now' ) {
	return SSA_Utils::datetime( $time );
}

function ssa_gmtstrtotime( $string ) {
	$time = strtotime($string . ' +0000');

	if ( -1 == $time ) {
		return strtotime($string);
	}

	return $time;
}

function ssa_defensive_timezone_fix() {
	ssa()->utils->defensive_timezone_fix();
}
function ssa_defensive_timezone_reset() {
	ssa()->utils->defensive_timezone_reset();
}

function ssa_log( $type, $message, $data ) {
	ssa_debug_log( $message, $type );
	ssa_debug_log( $data, $type . ' data' );
}

function ssa_debug_log( $var, $label = '' ) {
	if ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) {
		return;
	}
	
	if ( ! empty( $label ) ) {
		error_log( $label );
	}

	if ( is_string( $var ) ) {
		error_log( $var );
	} else {
		error_log( print_r( $var, true ) );
	}
}