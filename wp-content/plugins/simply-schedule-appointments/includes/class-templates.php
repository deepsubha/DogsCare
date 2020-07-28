<?php
/**
 * Simply Schedule Appointments Templates.
 *
 * @since   2.0.3
 * @package Simply_Schedule_Appointments
 */


/**
 * Simply Schedule Appointments Templates.
 *
 * @since 2.0.3
 */
class SSA_Templates {
	/**
	 * Parent plugin class.
	 *
	 * @since 2.0.3
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  2.0.3
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		
		SSA_Utils::define( 'SSA_TEMPLATE_DEBUG_MODE', false );

		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  2.0.3
	 */
	public function hooks() {
		add_filter( 'ssa/templates/get_template_vars', array( $this, 'add_global_template_vars' ), 10, 2 );
		add_filter( 'ssa/templates/get_template_vars', array( $this, 'add_appointment_template_vars' ), 10, 2 );
		add_filter( 'ssa/templates/get_template_vars', array( $this, 'add_example_appointment_type_template_vars' ), 10, 2 );
	}

	public function get_template_vars( $template, $vars = array() ) {
		$vars = apply_filters( 'ssa/templates/get_template_vars', $vars, $template );

		return $vars;
	}

	public function add_global_template_vars( $vars, $template ) {
		if ( empty( $vars['Global'] ) ) {
			$vars['Global'] = array();
		}

		$global_settings = $this->plugin->settings->get()['global'];
		$vars['Global'] = array_merge( $vars['Global'], array(
			'site_url' => site_url(),
			'home_url' => home_url(),
		), $global_settings );

		return $vars;
	}

	public function add_example_appointment_type_template_vars( $vars, $template ) {
		if ( empty( $vars['example_appointment_type_id'] ) ) {
			return $vars;
		}

		if ( empty( $vars['Appointment'] ) ) {
			$vars['Appointment'] = array();
		}

		$settings = $this->plugin->settings->get();
		$appointment_type_object = new SSA_Appointment_Type_Object( (int)$vars['example_appointment_type_id'] );
		
		$vars['Appointment']['AppointmentType'] = $appointment_type_object->data;
		if ( isset( $vars['Appointment']['AppointmentType']['availability'] ) ) {
			unset( $vars['Appointment']['AppointmentType']['availability'] );
		}
		if ( isset( $vars['Appointment']['AppointmentType']['notifications'] ) ) {
			unset( $vars['Appointment']['AppointmentType']['notifications'] );
		}
		if ( isset( $vars['Appointment']['AppointmentType']['google_calendars_availability'] ) ) {
			unset( $vars['Appointment']['AppointmentType']['google_calendars_availability'] );
		}
		if ( !empty( $vars['Appointment']['AppointmentType']['custom_customer_information'] ) ) {
			$vars['Appointment']['AppointmentType']['customer_information'] = $vars['Appointment']['AppointmentType']['custom_customer_information'];
		}
		if ( isset( $vars['Appointment']['AppointmentType']['custom_customer_information'] ) ) {
			unset( $vars['Appointment']['AppointmentType']['custom_customer_information'] );
		}
		if ( isset( $vars['Author'] ) ) {
			$vars['Customer'] = $vars['Author'];
			unset( $vars['Author'] );
		}

		$vars['Appointment']['customer_information'] = array();
		$vars['Appointment']['customer_information_strings'] = array();
		foreach ($vars['Appointment']['AppointmentType']['customer_information'] as $key => $value) {
			$vars['Appointment']['customer_information'][$value['field']] = __( '[customer info will go here...]', 'simply-schedule-appointments' );
			$vars['Appointment']['customer_information_strings'][$value['field']] = __( '[customer info will go here...]', 'simply-schedule-appointments' );
		}
		$vars['Appointment']['start_date'] = gmdate( 'Y-m-d H:i:s' );
		$vars['Appointment']['end_date'] = gmdate( 'Y-m-d H:i:s' );
		$vars['Appointment']['status'] = 'booked';
		$vars['Appointment']['customer_id'] = 0;
		$vars['Appointment']['customer_timezone'] = 'UTC';
		$vars['Appointment']['appointment_type_id'] = $vars['example_appointment_type_id'];

		$vars['admin_email'] = $settings['global']['admin_email'];
		$vars['customer_email'] = $vars['Appointment']['customer_information']['Email'];
		$vars['customer_name'] = $vars['Appointment']['customer_information']['Name'];

		return $vars;
	}

	public function add_appointment_template_vars( $vars, $template ) {
		if ( empty( $vars['appointment_id'] ) ) {
			return $vars;
		}

		if ( empty( $vars['Appointment'] ) ) {
			$vars['Appointment'] = array();
		}

		$settings = $this->plugin->settings->get();
		$appointment_obj = new SSA_Appointment_Object( (int)$vars['appointment_id'] );
		
		$vars['Appointment'] = array_merge( $vars['Appointment'], $appointment_obj->get_data( 1 ) );
		if ( isset( $vars['Appointment']['AppointmentType']['availability'] ) ) {
			unset( $vars['Appointment']['AppointmentType']['availability'] );
		}
		if ( isset( $vars['Appointment']['AppointmentType']['notifications'] ) ) {
			unset( $vars['Appointment']['AppointmentType']['notifications'] );
		}
		if ( isset( $vars['Appointment']['AppointmentType']['google_calendars_availability'] ) ) {
			unset( $vars['Appointment']['AppointmentType']['google_calendars_availability'] );
		}
		if ( !empty( $vars['Appointment']['AppointmentType']['custom_customer_information'] ) ) {
			$vars['Appointment']['AppointmentType']['customer_information'] = $vars['Appointment']['AppointmentType']['custom_customer_information'];
		}
		if ( isset( $vars['Appointment']['AppointmentType']['custom_customer_information'] ) ) {
			unset( $vars['Appointment']['AppointmentType']['custom_customer_information'] );
		}

		if ( isset( $vars['Author'] ) ) {
			$vars['Customer'] = $vars['Author'];
			unset( $vars['Author'] );
		}

		$vars['admin_email'] = $settings['global']['admin_email'];
		$vars['customer_email'] = $vars['Appointment']['customer_information']['Email'];
		$vars['customer_name'] = $vars['Appointment']['customer_information']['Name'];
		
		$vars['Appointment']['customer_information_strings'] = array();
		foreach ( $vars['Appointment']['customer_information'] as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = implode( ', ', $value );
			}
			
			$vars['Appointment']['customer_information_strings'][$key] = $value;
		}

		if ( ! empty( $vars['Appointment']['customer_timezone'] ) && false !== strpos( $vars['Appointment']['customer_timezone'], 'Etc/' ) ) {
			$vars['Appointment']['customer_timezone'] = $vars['Appointment']['date_timezone'];
		}

		return $vars;
	}

	public function render_template_string( $template_string, $vars ) {
		$context = array();

		$loader = new Twig_Loader_Array( array(
			'template' => $template_string,
		) );
		$twig = new Twig_Environment( $loader );
		$twig->addExtension( new SSA_Twig_Extension() );
		$twig->getExtension('Twig_Extension_Core')->setTimezone('UTC');
		try {
			$rendered_template = $twig->render( 'template', $vars );
		} catch (Exception $e) {
			return $e;
		}
		
		return $rendered_template;
	}



	/**
	 * Get template part.
	 *
	 * SSA_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
	 *
	 * @access public
	 * @param mixed  $slug Template slug.
	 * @param string $name Template name (default: '').
	 * @param boolean $echo  Should echo $output to screen. (default: false).
	 * 
	 * @return string
	 */
	function get_template_part( $slug, $name = '', $echo = false ) {
		ob_start();
		$template = '';

		// Look in yourtheme/slug-name.php and yourtheme/ssa/slug-name.php.
		if ( $name && ! SSA_TEMPLATE_DEBUG_MODE ) {
			$template = locate_template( array( "{$slug}-{$name}.php", $this->plugin->template_subdirectory() . "{$slug}-{$name}.php" ) );
		}

		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( $this->plugin->dir() . "templates/{$slug}-{$name}.php" ) ) {
			$template = $this->plugin->dir() . "templates/{$slug}-{$name}.php";
		}

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/ssa/slug.php.
		if ( ! $template && ! SSA_TEMPLATE_DEBUG_MODE ) {
			$template = locate_template( array( "{$slug}.php", $this->plugin->template_subdirectory() . "{$slug}.php" ) );
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'ssa/templates/get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}

		$output = ob_get_clean();
		if ( !empty( $echo ) ) {
			echo $output;
		}

		return $output;
	}

	/**
	 * Get other templates passing attributes and including the file.
	 *
	 * @access public
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 * @param boolean $echo  Should echo $output to screen. (default: false).
	 * 
	 * @return string
	 */
	function get_template( $template_name, $args = array(), $template_path = '', $default_path = '', $echo = false ) {
		ob_start();

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		$located = $this->locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			return;
		}

		// Allow 3rd party plugin filter template file from their plugin.
		$located = apply_filters( 'ssa/templates/get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'ssa_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'ssa_after_template_part', $template_name, $template_path, $located, $args );

		$output = ob_get_clean();
		if ( !empty( $echo ) ) {
			echo $output;
		}

		return $output;
	}

	public function get_template_rendered( $template, $template_vars = array() ) {
		$template_string = $this->get_template( $template );
		$template_vars = $this->get_template_vars( $template, $template_vars );
		if ( empty( $template_string ) ) {
			return false;
		}

		$rendered_template_string = $this->render_template_string( $template_string, $template_vars );

		return $rendered_template_string;
	}


	/**
	 * Like get_template, but returns the HTML instead of outputting.
	 *
	 * @see get_template_html
	 * @since 2.5.0
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	function get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		ob_start();
		$this->get_template( $template_name, $args, $template_path, $default_path );
		return ob_get_clean();
	}
	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/$template_name
	 * $default_path/$template_name
	 *
	 * @access public
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 * @return string
	 */
	function locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = $this->plugin->template_subdirectory();
		}

		if ( ! $default_path ) {
			$default_path = $this->plugin->dir() . 'templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template/.
		if ( ! $template || SSA_TEMPLATE_DEBUG_MODE ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'ssa/templates/locate_template', $template, $template_name, $template_path );
	}

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/$template_name
	 * $default_path/$template_name
	 *
	 * @access public
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 * @return string
	 */
	function locate_template_url( $template_name, $template_path = '', $default_path = '' ) {
		$template = $this->locate_template( $template_name, $template_path, $default_path );

		if ( empty( $template ) ) {
			return;
		}

		if ( $themes_pos = strpos( $template, 'themes/' ) ) {
			return content_url( substr( $template, $themes_pos ) );
		}

		if ( $plugins_pos = strpos( $template, 'plugins/' ) ) {
			return content_url( substr( $template, $plugins_pos ) );
		}

		return false;
	}


	public function cleanup_variables_in_string( $string ) {
		$string = str_replace(
			array( '{{', '{{  ', '}}', '  }}', '{%', '{%  ', '%}', '  %}', ),
			array( '{{ ', '{{ ', ' }}', ' }}', '{% ', '{% ', ' %}', ' %}', ),
			$string
		);

		return $string;
	}


}
