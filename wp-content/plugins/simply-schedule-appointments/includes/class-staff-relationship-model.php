<?php
/**
 * Simply Schedule Appointments Staff Relationship Model.
 *
 * @since   3.5.3
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Staff Relationship Model.
 *
 * @since 3.5.3
 */
class SSA_Staff_Relationship_Model extends SSA_Db_Model {
	protected $slug = 'staff_relationship';
	protected $version = '1.0.0';

	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.2
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.0.2
	 *
	 * @param  Simply_Schedule_Appointments $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		// $this->version = $this->version.'.'.time(); // dev mode
		parent::__construct( $plugin );

		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.2
	 */
	public function hooks() {

	}

	public function rest_api_init() {
		// No API Endpoints for this model
		// All manipulations should be done through an Appointment
		// $this->register_routes();
	}

	public function belongs_to() {
		return array(
			// 'Author' => array(
			// 	'model' => 'WP_User_Model',
			// 	'foreign_key' => 'author_id',
			// ),
			// 'AppointmentType' => array(
			// 	'model' => $this->plugin->appointment_type_model,
			// 	'foreign_key' => 'appointment_type_id',
			// ),
		);
	}

	protected $schema = array(
		'staff_id' => array(
			'field' => 'staff_id',
			'label' => 'Staff ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'appointment_id' => array(
			'field' => 'appointment_id',
			'label' => 'Appointment ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'appointment_type_id' => array(
			'field' => 'appointment_type_id',
			'label' => 'Appointment Type ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => false,
			'mysql_extra' => '',
			'cache_key' => false,
		),
	);

	public $indexes = array(
		'staff_id' => [ 'staff_id' ],
		'appointment_id' => [ 'appointment_id' ],
		'appointment_type_id' => [ 'appointment_type_id' ],
	);

	public function filter_where_conditions( $where, $args ) {
		if ( !empty( $args['email'] ) ) {
			$where .= ' AND email="'.sanitize_text_field( $args['email'] ).'"';
		}

		return $where;
	}

	public function create_item_permissions_check( $request ) {
		if ( current_user_can( 'ssa_manage_staff' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if a given request has access to update a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function update_item_permissions_check( $request ) {
		if ( current_user_can( 'ssa_manage_staff' ) ) {
			return true;
		}

		return false;
	}

	public function get_items_permissions_check( $request ) {
		if ( current_user_can( 'ssa_manage_staff' ) ) {
			return true;
		}

		return false;
	}

	public function get_item_permissions_check( $request ) {
		if ( current_user_can( 'ssa_manage_staff' ) ) {
			return true;
		}

		return false;
	}

}
