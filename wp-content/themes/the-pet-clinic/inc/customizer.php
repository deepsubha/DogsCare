<?php
/**
 * The Pet Clinic: Customizer
 *
 * @package WordPress
 * @subpackage The Pet Clinic
 * @since 1.0
 */

use WPTRT\Customize\Section\The_Pet_Clinic_Button;

add_action( 'customize_register', function( $manager ) {

	$manager->register_section_type( The_Pet_Clinic_Button::class );

	$manager->add_section(
		new The_Pet_Clinic_Button( $manager, 'the_pet_clinic_pro', [
			'title'       => __( 'Pet Clinic Pro', 'the-pet-clinic' ),
			'priority'    => 0,
			'button_text' => __( 'Go Pro', 'the-pet-clinic' ),
			'button_url'  => 'https://www.luzuk.com/themes/pet-care-wordpress-theme/'
		] )
	);

} );

// Load the JS and CSS.
add_action( 'customize_controls_enqueue_scripts', function() {

	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script(
		'the-pet-clinic-customize-section-button',
		get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/js/customize-controls.js' ),
		[ 'customize-controls' ],
		$version,
		true
	);

	wp_enqueue_style(
		'the-pet-clinic-customize-section-button',
		get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/css/customize-controls.css' ),
		[ 'customize-controls' ],
 		$version
	);

} );

function the_pet_clinic_customize_register( $wp_customize ) {

	$wp_customize->add_setting('the_pet_clinic_show_site_title',array(
       'default' => true,
       'sanitize_callback'	=> 'sanitize_text_field'
    ));
    $wp_customize->add_control('the_pet_clinic_show_site_title',array(
       'type' => 'checkbox',
       'label' => __('Show / Hide Site Title','the-pet-clinic'),
       'section' => 'title_tagline'
    ));

    $wp_customize->add_setting('the_pet_clinic_show_tagline',array(
       'default' => true,
       'sanitize_callback'	=> 'sanitize_text_field'
    ));
    $wp_customize->add_control('the_pet_clinic_show_tagline',array(
       'type' => 'checkbox',
       'label' => __('Show / Hide Site Tagline','the-pet-clinic'),
       'section' => 'title_tagline'
    ));

	$wp_customize->add_panel( 'the_pet_clinic_panel_id', array(
	    'priority' => 10,
	    'capability' => 'edit_theme_options',
	    'theme_supports' => '',
	    'title' => __( 'Theme Settings', 'the-pet-clinic' ),
	    'description' => __( 'Description of what this panel does.', 'the-pet-clinic' ),
	) );

	$wp_customize->add_section( 'the_pet_clinic_theme_options_section', array(
    	'title'      => __( 'General Settings', 'the-pet-clinic' ),
		'priority'   => 30,
		'panel' => 'the_pet_clinic_panel_id'
	) );

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('the_pet_clinic_theme_options',array(
        'default' => __('Right Sidebar','the-pet-clinic'),
        'sanitize_callback' => 'the_pet_clinic_sanitize_choices'	        
	));

	$wp_customize->add_control('the_pet_clinic_theme_options',array(
        'type' => 'radio',
        'label' => __('Do you want this section','the-pet-clinic'),
        'section' => 'the_pet_clinic_theme_options_section',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','the-pet-clinic'),
            'Right Sidebar' => __('Right Sidebar','the-pet-clinic'),
            'One Column' => __('One Column','the-pet-clinic'),
            'Three Columns' => __('Three Columns','the-pet-clinic'),
            'Four Columns' => __('Four Columns','the-pet-clinic'),
            'Grid Layout' => __('Grid Layout','the-pet-clinic')
        ),
	));

	// Top Bar
	$wp_customize->add_section( 'the_pet_clinic_top_bar', array(
    	'title'      => __( 'Contact Details', 'the-pet-clinic' ),
		'priority'   => null,
		'panel' => 'the_pet_clinic_panel_id'
	) );

	$wp_customize->add_setting('the_pet_clinic_email_address',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_pet_clinic_email_address',array(
		'label'	=> __('Add Email Address','the-pet-clinic'),
		'section'=> 'the_pet_clinic_top_bar',
		'setting'=> 'the_pet_clinic_email_address',
		'type'=> 'text'
	));

	$wp_customize->add_setting('the_pet_clinic_email_address_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_pet_clinic_email_address_text',array(
		'label'	=> __('Add Email Address Text','the-pet-clinic'),
		'section'=> 'the_pet_clinic_top_bar',
		'setting'=> 'the_pet_clinic_email_address_text',
		'type'=> 'text'
	));

	$wp_customize->add_setting('the_pet_clinic_phone_number',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_pet_clinic_phone_number',array(
		'label'	=> __('Add Phone Number','the-pet-clinic'),
		'section'=> 'the_pet_clinic_top_bar',
		'setting'=> 'the_pet_clinic_phone_number',
		'type'=> 'text'
	));

	$wp_customize->add_setting('the_pet_clinic_phone_number_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_pet_clinic_phone_number_text',array(
		'label'	=> __('Add Phone Number Text','the-pet-clinic'),
		'section'=> 'the_pet_clinic_top_bar',
		'setting'=> 'the_pet_clinic_phone_number_text',
		'type'=> 'text'
	));

	//social icons
	$wp_customize->add_section( 'the_pet_clinic_social', array(
    	'title'      => __( 'Social Icons', 'the-pet-clinic' ),
		'priority'   => null,
		'panel' => 'the_pet_clinic_panel_id'
	) );

	$wp_customize->add_setting('the_pet_clinic_facebook_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('the_pet_clinic_facebook_url',array(
		'label'	=> __('Add Facebook link','the-pet-clinic'),
		'section'	=> 'the_pet_clinic_social',
		'setting'	=> 'the_pet_clinic_facebook_url',
		'type'	=> 'url'
	));

	$wp_customize->add_setting('the_pet_clinic_twitter_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('the_pet_clinic_twitter_url',array(
		'label'	=> __('Add Twitter link','the-pet-clinic'),
		'section'	=> 'the_pet_clinic_social',
		'setting'	=> 'the_pet_clinic_twitter_url',
		'type'	=> 'url'
	));

	$wp_customize->add_setting('the_pet_clinic_instagram_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('the_pet_clinic_instagram_url',array(
		'label'	=> __('Add Instagram link','the-pet-clinic'),
		'section'	=> 'the_pet_clinic_social',
		'setting'	=> 'the_pet_clinic_instagram_url',
		'type'	=> 'url'
	));

	$wp_customize->add_setting('the_pet_clinic_youtube_url',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));	
	$wp_customize->add_control('the_pet_clinic_youtube_url',array(
		'label'	=> __('Add Youtube link','the-pet-clinic'),
		'section'	=> 'the_pet_clinic_social',
		'setting'	=> 'the_pet_clinic_youtube_url',
		'type'	=> 'url'
	));

	//home page slider
	$wp_customize->add_section( 'the_pet_clinic_slider_section' , array(
    	'title'      => __( 'Slider Settings', 'the-pet-clinic' ),
		'priority'   => null,
		'panel' => 'the_pet_clinic_panel_id'
	) );

	$wp_customize->add_setting('the_pet_clinic_slider_hide_show',array(
       	'default' => 'true',
       	'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('the_pet_clinic_slider_hide_show',array(
	   	'type' => 'checkbox',
	   	'label' => __('Show / Hide slider','the-pet-clinic'),
	   	'description' => __('Image Size ( 1400px x 800px )','the-pet-clinic'),
	   	'section' => 'the_pet_clinic_slider_section',
	));

	for ( $count = 1; $count <= 4; $count++ ) {

		$wp_customize->add_setting( 'the_pet_clinic_slider' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'the_pet_clinic_sanitize_dropdown_pages'
		) );

		$wp_customize->add_control( 'the_pet_clinic_slider' . $count, array(
			'label'    => __( 'Select Slide Image Page', 'the-pet-clinic' ),
			'section'  => 'the_pet_clinic_slider_section',
			'type'     => 'dropdown-pages'
		) );
	}

	// Our Facilities 
	$wp_customize->add_section('the_pet_clinic_facilities_section',array(
		'title'	=> __('Our Facilities','the-pet-clinic'),
		'description'=> __('This section will appear below the Slider section.','the-pet-clinic'),
		'panel' => 'the_pet_clinic_panel_id',
	));

	$wp_customize->add_setting('the_pet_clinic_facilities_smalltitle',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_pet_clinic_facilities_smalltitle',array(
		'label'	=> __('Section Small Title','the-pet-clinic'),
		'section'	=> 'the_pet_clinic_facilities_section',
		'setting'	=> 'the_pet_clinic_facilities_smalltitle',
		'type'		=> 'text'
	));
	
	$wp_customize->add_setting('the_pet_clinic_facilities_title',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_pet_clinic_facilities_title',array(
		'label'	=> __('Section Title','the-pet-clinic'),
		'section'	=> 'the_pet_clinic_facilities_section',
		'setting'	=> 'the_pet_clinic_facilities_title',
		'type'		=> 'text'
	));

	$wp_customize->add_setting('the_pet_clinic_facilities_image',array(
		'default'	=> '',
		'sanitize_callback'	=> 'esc_url_raw',
	));
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'the_pet_clinic_facilities_image',array(
            'label' => __('Facility Image','the-pet-clinic'),
            'section' => 'the_pet_clinic_facilities_section',
            'settings' => 'the_pet_clinic_facilities_image'
	)));

	$categories = get_categories();
	$cats = array();
	$i = 0;
	$cat_pst4[]= 'select';
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_pst4[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('the_pet_clinic_facilities_cat_left',array(
		'default'	=> 'select',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('the_pet_clinic_facilities_cat_left',array(
		'type'    => 'select',
		'choices' => $cat_pst4,
		'label' => __('Select Category to display Left Facilities Posts','the-pet-clinic'),
		'section' => 'the_pet_clinic_facilities_section',
	));

	$wp_customize->add_setting('the_pet_clinic_facilities_cat_right',array(
		'default'	=> 'select',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('the_pet_clinic_facilities_cat_right',array(
		'type'    => 'select',
		'choices' => $cat_pst4,
		'label' => __('Select Category to display Right Facilities Posts','the-pet-clinic'),
		'section' => 'the_pet_clinic_facilities_section',
	));

	//Footer
    $wp_customize->add_section( 'the_pet_clinic_footer', array(
    	'title'      => __( 'Footer Text', 'the-pet-clinic' ),
		'priority'   => null,
		'panel' => 'the_pet_clinic_panel_id'
	) );

    $wp_customize->add_setting('the_pet_clinic_footer_copy',array(
		'default'	=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('the_pet_clinic_footer_copy',array(
		'label'	=> __('Footer Text','the-pet-clinic'),
		'section'	=> 'the_pet_clinic_footer',
		'setting'	=> 'the_pet_clinic_footer_copy',
		'type'		=> 'text'
	));

	$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title a',
		'render_callback' => 'the_pet_clinic_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'the_pet_clinic_customize_partial_blogdescription',
	) );

	//front page
	$num_sections = apply_filters( 'the_pet_clinic_front_page_sections', 4 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		$wp_customize->add_setting( 'panel_' . $i, array(
			'default'           => false,
			'sanitize_callback' => 'the_pet_clinic_sanitize_dropdown_pages',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'panel_' . $i, array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Content', 'the-pet-clinic' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Select pages to feature in each area from the dropdowns. Add an image to a section by setting a featured image in the page editor. Empty sections will not be displayed.', 'the-pet-clinic' ) ),
			'section'        => 'theme_options',
			'type'           => 'dropdown-pages',
			'allow_addition' => true,
			'active_callback' => 'the_pet_clinic_is_static_front_page',
		) );

		$wp_customize->selective_refresh->add_partial( 'panel_' . $i, array(
			'selector'            => '#panel' . $i,
			'render_callback'     => 'the_pet_clinic_front_page_section',
			'container_inclusive' => true,
		) );
	}
}
add_action( 'customize_register', 'the_pet_clinic_customize_register' );

function the_pet_clinic_customize_partial_blogname() {
	bloginfo( 'name' );
}

function the_pet_clinic_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

function the_pet_clinic_is_static_front_page() {
	return ( is_front_page() && ! is_home() );
}

function the_pet_clinic_is_view_with_layout_option() {
	// This option is available on all pages. It's also available on archives when there isn't a sidebar.
	return ( is_page() || ( is_archive() && ! is_active_sidebar( 'sidebar-1' ) ) );
}