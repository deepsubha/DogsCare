<?php
/**
 * Simply Schedule Appointments Upgrade.
 *
 * @since   0.0.3
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Upgrade.
 *
 * @since 0.0.3
 */
class SSA_Upgrade {
	protected $last_version_seen;
	protected $versions_requiring_upgrade = array(
		'0.0.3',
		'1.2.3', // fix "Email address" -> "email"
		'1.5.1', // fix customer_information vs custom_customer_information capitalization
		'2.6.9_12', // flush permalinks
		'2.6.9_13', // Whitelist for Disable REST API
		'2.7.1', // Notifications
		'2.9.2', // SMS phone
		'3.1.0', // Appointment.date_timezone -> Appointment.customer_timezone
	);

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
		add_action( 'init', array( $this, 'migrations' ), 20 );
	}

	public function get_last_version_seen() {
		$db_versions = get_option( 'ssa_versions', json_encode( array() ) );
		$db_versions = json_decode( $db_versions, true );
		$db_version_keys = array_keys( $db_versions );
		$last_changed_date = array_pop( $db_version_keys );
		if ( $last_changed_date === null ) {
			// First time we're seeing SSA installed
			$this->last_version_seen = '0.0.0';
		} else {
			$this->last_version_seen = $db_versions[$last_changed_date];
		}

		return $this->last_version_seen;
	}

	public function record_version( $version ) {
		$db_versions = get_option( 'ssa_versions', json_encode( array() ) );
		$db_versions = json_decode( $db_versions, true );

		$db_versions[gmdate('Y-m-d H:i:s')] = $version;
		$this->last_version_seen = $version;
		return update_option( 'ssa_versions', json_encode($db_versions) );
	}

	public function migrations() {
		$this->last_version_seen = $this->get_last_version_seen();
		foreach ($this->versions_requiring_upgrade as $version ) {
			if ( $this->last_version_seen >= $version ) {
				continue;
			}
			
			$method_name = 'migrate_to_version_'.str_replace('.', '_', $version);
			$this->$method_name( $this->last_version_seen );
		}
	}

	public function migrate_to_version_0_0_3( $from_version ) {
		$post_id = $this->plugin->wp_admin->maybe_create_booking_page();
		if ( !empty( $post_id ) ) {
			$this->record_version( '0.0.3' );
		}
	}

	public function migrate_to_version_1_2_3( $from_version ) {
		if ( $from_version === '0.0.0' ) {
			return; // we don't need to migrate fresh installs
		}

		$appointment_types = $this->plugin->appointment_type_model->query( array(
			'number' => -1,
		) );

		if ( empty( $appointment_types['0']['id'] ) ) {
			$this->record_version( '1.2.3' );
			return;
		}

		foreach ($appointment_types as $appointment_type_key => $appointment_type) {
			if ( empty( $appointment_type['custom_customer_information']['0']['field'] ) ) {
				continue;
			}

			foreach ($appointment_type['custom_customer_information'] as $field_key => $field ) {
				if ( $field['field'] != 'Email address' ) {
					continue;
				}

				$appointment_types[$appointment_type_key]['custom_customer_information'][$field_key]['field'] = 'Email';
			}

			$this->plugin->appointment_type_model->update( $appointment_types[$appointment_type_key]['id'], $appointment_types[$appointment_type_key] );
		}

		$this->record_version( '1.2.3' );
	}


	public function migrate_to_version_1_5_1( $from_version ) {
		if ( $from_version === '0.0.0' ) {
			return; // we don't need to migrate fresh installs
		}

		$field_name_conversion_map = array(
			'name' => 'Name',
			'email' => 'Email',
			'phone_number' => 'Phone',
			'address' => 'Address',
			'city' => 'City',
			'state' => 'State',
			'zip' => 'Zip',
			'notes' => 'Notes',
		);

		/* Migrate Appointment Types */
		$appointment_types = $this->plugin->appointment_type_model->query( array(
			'number' => -1,
		) );
		foreach ($appointment_types as $appointment_type_key => $appointment_type) {
			if ( !empty( $appointment_type['custom_customer_information']['0']['field'] ) ) {
				foreach ($appointment_type['custom_customer_information'] as $field_key => $field ) {
					if ( empty( $field_name_conversion_map[$field['field']] ) ) {
						continue;
					}

					$appointment_types[$appointment_type_key]['custom_customer_information'][$field_key]['field'] = $field_name_conversion_map[$field['field']];
				}
			}

			if ( !empty( $appointment_type['customer_information']['0']['field'] ) ) {
				foreach ($appointment_type['customer_information'] as $field_key => $field ) {
					if ( empty( $field_name_conversion_map[$field['field']] ) ) {
						continue;
					}

					$appointment_types[$appointment_type_key]['customer_information'][$field_key]['field'] = $field_name_conversion_map[$field['field']];
				}
			}


			$this->plugin->appointment_type_model->update( $appointment_types[$appointment_type_key]['id'], $appointment_types[$appointment_type_key] );
		}

		/* Migrate Appointments */
		$appointments = $this->plugin->appointment_model->query( array(
			'number' => -1,
		) );
		foreach ($appointments as $appointment_key => $appointment) {
			if ( !empty( $appointment['customer_information'] ) ) {
				foreach ($appointment['customer_information'] as $field_key => $value ) {
					if ( empty( $field_name_conversion_map[$field_key] ) ) {
						continue;
					}

					$appointments[$appointment_key]['customer_information'][$field_name_conversion_map[$field_key]] = $value;
					unset( $appointments[$appointment_key]['customer_information'][$field_key] );
				}
			}


			$this->plugin->appointment_model->update( $appointments[$appointment_key]['id'], $appointments[$appointment_key] );
		}

		$this->record_version( '1.5.1' );
	}
	
	public function migrate_to_version_2_6_9_12( $from_version ) {
		global $wp_rewrite;
		$wp_rewrite->init();
		flush_rewrite_rules();

		$this->record_version( '2.6.9_12' );
	}

	public function migrate_to_version_2_6_9_13( $from_version ) {
		$DRA_route_whitelist = get_option( 'DRA_route_whitelist', array() );
		$ssa_routes_to_whitelist = array(
			"/ssa/v1","/ssa/v1/settings",
			"/ssa/v1/settings/(?P&lt;id&gt;[a-zA-Z0-9_-]+)",
			"/ssa/v1/settings/schema",
			"/ssa/v1/notices",
			"/ssa/v1/notices/(?P&lt;id&gt;[a-zA-Z0-9_-]+)",
			"/ssa/v1/notices/schema",
			"/ssa/v1/license",
			"/ssa/v1/license/schema",
			"/ssa/v1/google_calendars",
			"/ssa/v1/google_calendars/disconnect",
			"/ssa/v1/google_calendars/authorize_url",
			"/ssa/v1/mailchimp",
			"/ssa/v1/mailchimp/disconnect",
			"/ssa/v1/mailchimp/authorize",
			"/ssa/v1/mailchimp/deauthorize",
			"/ssa/v1/mailchimp/lists",
			"/ssa/v1/mailchimp/subscribe",
			"/ssa/v1/support_status",
			"/ssa/v1/support_ticket",
			"/oembed/1.0",
			"/ssa/v1/appointments",
			"/ssa/v1/appointments/bulk",
			"/ssa/v1/appointments/(?P&lt;id&gt;[\\d]+)",
			"/ssa/v1/appointments/(?P&lt;id&gt;[\\d]+)/ics",
			"/ssa/v1/appointment_types",
			"/ssa/v1/appointment_types/bulk",
			"/ssa/v1/appointment_types/(?P&lt;id&gt;[\\d]+)",
			"/ssa/v1/appointment_types/(?P&lt;id&gt;[\\d]+)/availability",
			"/ssa/v1/availability",
			"/ssa/v1/availability/bulk",
			"/ssa/v1/availability/(?P&lt;id&gt;[\\d]+)",
			"/ssa/v1/async",
			"/ssa/v1/payments",
			"/ssa/v1/payments/bulk",
			"/ssa/v1/payments/(?P&lt;id&gt;[\\d]+)"
		);
		if ( empty( $DRA_route_whitelist ) ) {
			$DRA_route_whitelist = $ssa_routes_to_whitelist;
		} else {
			foreach ( $ssa_routes_to_whitelist as $key => $route ) {
				if ( ! in_array( $route, $DRA_route_whitelist ) ) {
					$DRA_route_whitelist[] = $route;
				}
			}
		}

		update_option( 'DRA_route_whitelist', $DRA_route_whitelist );

		$this->record_version( '2.6.9_13' );
	}

	public function migrate_to_version_2_7_1( $from_version ) {
		$notifications_settings = $this->plugin->notifications_settings->get();

		$should_enable_admin_notification_for_all_appointment_types = true;
		$should_enable_customer_notification_for_all_appointment_types = true;

		$appointment_type_ids_with_admin_notification = array();
		$appointment_type_ids_with_customer_notification = array();

		$appointment_types = $this->plugin->appointment_type_model->query();
		if ( ! empty( $appointment_types ) ) {
			foreach ( $appointment_types as $key => $appointment_type ) {
				if ( empty( $appointment_type['notifications'] ) ) {
					$appointment_type_ids_with_admin_notification[] = $appointment_type['id'];
					$appointment_type_ids_with_customer_notification[] = $appointment_type['id'];

					continue;
				}

				foreach ($appointment_type['notifications'] as $notification_key => $notification ) {
					if ( $notification['field'] === 'admin' ) {
						if ( empty( $notification['send'] ) ) {
							$should_enable_admin_notification_for_all_appointment_types = false;
						} else {
							$appointment_type_ids_with_admin_notification[] = $appointment_type['id'];
						}
					} elseif ( $notification['field'] === 'customer' ) {
						if ( empty( $notification['send'] ) ) {
							$should_enable_customer_notification_for_all_appointment_types = false;
						} else {
							$appointment_type_ids_with_customer_notification[] = $appointment_type['id'];
						}
					}
				}
			}
		}

		
		$id = time();
		$booked_admin_notification = array(
			'appointment_types' => ( $should_enable_admin_notification_for_all_appointment_types ) ? array() : $appointment_type_ids_with_admin_notification,
			'id' => $id,
			'schema' => '2019-04-02',
			'sent_to' => array(
				'{{admin_email}}',
			),
			'title' => 'Email (Admin)',
			'subject' => '{{ Appointment.customer_information.Name }} just booked an appointment',
			'message' => wpautop( nl2br( $this->plugin->templates->get_template( 'notifications/email/text/booked-staff.php' ) ) ),
			'trigger' => 'appointment_booked',
			'type' => 'email',
			'when' => 'after',
			'duration' => 0,
		);

		$id = time() + 1;
		$booked_customer_notification = array(
			'appointment_types' => ( $should_enable_customer_notification_for_all_appointment_types ) ? array() : $appointment_type_ids_with_customer_notification,
			'id' => $id,
			'schema' => '2019-04-02',
			'sent_to' => array(
				'{{customer_email}}',
			),
			'subject' => 'Your appointment details',
			'message' => wpautop( nl2br( $this->plugin->templates->get_template( 'notifications/email/text/booked-customer.php' ) ) ),
			'title' => 'Email (Customer)',
			'trigger' => 'appointment_booked',
			'type' => 'email',
			'when' => 'after',
			'duration' => 0,
		);

		$id = time() + 2;
		$canceled_admin_notification = array(
			'appointment_types' => ( $should_enable_admin_notification_for_all_appointment_types ) ? array() : $appointment_type_ids_with_admin_notification,
			'id' => $id,
			'schema' => '2019-04-02',
			'sent_to' => array(
				'{{admin_email}}',
			),
			'title' => 'Email (Admin)',
			'subject' => '{{ Appointment.customer_information.Name }} just canceled an appointment',
			'message' => wpautop( nl2br( $this->plugin->templates->get_template( 'notifications/email/text/canceled-staff.php' ) ) ),
			'trigger' => 'appointment_canceled',
			'type' => 'email',
			'when' => 'after',
			'duration' => 0,
		);

		$id = time() + 3;
		$canceled_customer_notification = array(
			'appointment_types' => ( $should_enable_customer_notification_for_all_appointment_types ) ? array() : $appointment_type_ids_with_customer_notification,
			'id' => $id,
			'schema' => '2019-04-02',
			'sent_to' => array(
				'{{customer_email}}',
			),
			'subject' => 'Your appointment has been canceled',
			'message' => wpautop( nl2br( $this->plugin->templates->get_template( 'notifications/email/text/canceled-customer.php' ) ) ),
			'title' => 'Email (Customer)',
			'trigger' => 'appointment_canceled',
			'type' => 'email',
			'when' => 'after',
			'duration' => 0,
		);

		$notifications_settings['notifications'] = array(
			$booked_admin_notification,
			$booked_customer_notification,
			$canceled_admin_notification,
			$canceled_customer_notification,
		);

		$this->plugin->notifications_settings->update( $notifications_settings );

		$this->record_version( '2.7.1' );
	}

	public function migrate_to_version_2_9_2( $from_version ) {
		$appointment_types = $this->plugin->appointment_type_model->query( array(
			'number' => -1,
		) );

		if ( empty( $appointment_types['0']['id'] ) ) {
			$this->record_version( '2.9.2' );
			return;
		}

		foreach ($appointment_types as $appointment_type_key => $appointment_type) {
			if ( empty( $appointment_type['custom_customer_information']['0']['field'] ) ) {
				continue;
			}

			foreach ($appointment_type['custom_customer_information'] as $field_key => $field ) {
				if ( false === stripos( $field['field'], 'phone' ) ) {
					if ( $field['type'] !== 'single-text' ) {
						continue;
					}

					if ( empty( $field['icon'] ) || ( $field['icon'] !== 'call' ) ) {
						continue;
					}
				}

				$appointment_types[$appointment_type_key]['custom_customer_information'][$field_key]['type'] = 'phone';
			}

			$this->plugin->appointment_type_model->update( $appointment_types[$appointment_type_key]['id'], $appointment_types[$appointment_type_key] );
		}

		$this->record_version( '2.9.2' );
	}

	public function migrate_to_version_3_1_0( $from_version ) {
		$notifications_settings = $this->plugin->notifications_settings->get();
		foreach ($notifications_settings['notifications'] as $key => $notification) {
			if ( empty( $notification['sent_to'] ) || ! is_array( $notification['sent_to'] ) ) {
				continue;
			}

			$is_customer_notification = false;
			foreach ( $notification['sent_to'] as $recipient ) {
				if ( false !== strpos( $recipient, '{{customer' ) ) {
					$is_customer_notification = true;
				}
			}
			
			if ( ! $is_customer_notification ) {
				continue;
			}

			$notifications_settings['notifications'][$key]['message'] = str_replace( 'Appointment.date_timezone', 'Appointment.customer_timezone', $notifications_settings['notifications'][$key]['message'] );
		}

		$this->plugin->notifications_settings->update( $notifications_settings );


		$this->record_version( '3.1.0' );
	}
}
