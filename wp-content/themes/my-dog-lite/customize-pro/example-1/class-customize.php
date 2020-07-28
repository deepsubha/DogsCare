<?php
/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class my_dog_lite_Example_1_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function my_dog_lite_get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->my_dog_lite_setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function my_dog_lite_setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'my_dog_lite_sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'my_dog_lite_enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function my_dog_lite_sections( $manager ) {

		// Load custom sections.
		get_template_part( 'customize-pro/example-1/section', 'pro' );

		// Register custom section types.
		$manager->register_section_type( 'my_dog_lite_Example_1_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(
			new my_dog_lite_Example_1_Customize_Section_Pro(
				$manager,
				'example_1',
				array(
					'title'    => esc_html__( 'Upgrade to Pro', 'my-dog-lite' ),
					'pro_text' => esc_html__( 'Upgrade Now', 'my-dog-lite' ),
					'pro_url'  => 'https://www.sktthemes.org/shop/dog-wordpress-theme/',
					'priority'   => 1,
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function my_dog_lite_enqueue_control_scripts() {

		wp_enqueue_script( 'example-1-customize-controls', trailingslashit( get_template_directory_uri() ) . 'customize-pro/example-1/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'example-1-customize-controls', trailingslashit( get_template_directory_uri() ) . 'customize-pro/example-1/customize-controls.css' );
	}
}

// Doing this customizer thang!
my_dog_lite_Example_1_Customize::my_dog_lite_get_instance();