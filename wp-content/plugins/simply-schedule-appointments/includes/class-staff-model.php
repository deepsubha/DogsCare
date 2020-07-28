<?php
/**
 * Simply Schedule Appointments Staff Model.
 *
 * @since   3.5.3
 * @package Simply_Schedule_Appointments
 */

/**
 * Simply Schedule Appointments Staff Model.
 *
 * @since 3.5.3
 */
class SSA_Staff_Model extends SSA_Db_Model {
	protected $slug = 'staff';
	protected $version = '1.0.7';

	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.2
	 *
	 * @var   Simply_Schedule_Appointments
	 */
	protected $plugin = null;

	public function get_pluralized_slug() {
		return 'staff';
	}

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
		'user_id' => array(
			'field' => 'user_id',
			'label' => 'User ID',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'BIGINT',
			'mysql_length' => 20,
			'mysql_unsigned' => true,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'capacity' => array(
			'field' => 'capacity',
			'label' => 'Capacity',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'INT',
			'mysql_length' => 6,
			'mysql_unsigned' => true,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'name' => array(
			'field' => 'name',
			'label' => 'Name',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'VARCHAR',
			'mysql_length' => '120',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'display_name' => array(
			'field' => 'display_name',
			'label' => 'Display Name',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'VARCHAR',
			'mysql_length' => '120',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'email' => array(
			'field' => 'email',
			'label' => 'Email',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'VARCHAR',
			'mysql_length' => '120',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'status' => array(
			'field' => 'status',
			'label' => 'Status',
			'default_value' => 'publish', // publish, draft, trash, delete
			'format' => '%s',
			'mysql_type' => 'VARCHAR',
			'mysql_length' => '16',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
			'supports' => array(
				'soft_delete' => true,
			),
		),
		'visibility' => array(
			'field' => 'visibility',
			'label' => 'Visibility',
			'default_value' => 'public', // public, private; todo: add `password`, callable `function` and `capability` (with extra field to store password or current_user_can() cap)
			'format' => '%s',
			'mysql_type' => 'VARCHAR',
			'mysql_length' => '16',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'timezone' => array(
			'field' => 'timezone',
			'label' => 'Timezone',
			'default_value' => '',
			'format' => '%s',
			'mysql_type' => 'VARCHAR',
			'mysql_length' => '16',
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'manages_blackout_dates' => array(
			'field' => 'manages_blackout_dates',
			'label' => 'Manages Blackout Dates',
			'default_value' => 0,
			'format' => '%d',
			'mysql_type' => 'TINYINT',
			'mysql_length' => 1,
			'mysql_unsigned' => true,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
		'blackout_dates' => array(
			'field' => 'blackout_dates',
			'label' => 'Blackout Dates',
			'default_value' => false,
			'format' => '%s',
			'mysql_type' => 'TEXT',
			'mysql_length' => false,
			'mysql_unsigned' => false,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
			'encoder' => 'json',
		),
		'display_order' => array(
			'field' => 'display_order',
			'label' => 'Order',
			'description' => 'Store the display order',
			'default_value' => false,
			'format' => '%d',
			'mysql_type' => 'INT',
			'mysql_length' => '5',
			'mysql_unsigned' => true,
			'mysql_allow_null' => true,
			'mysql_extra' => '',
			'cache_key' => false,
		),
	);

	public $indexes = array(
		'user_id' => [ 'user_id' ],
		'email' => [ 'email' ],
		'status' => [ 'status' ],
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

	public function get_item_permissions_check( $request ) {
		if ( current_user_can( 'ssa_manage_staff' ) ) {
			return true;
		}

		return false;
	}

}
