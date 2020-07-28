<?php
/**
 * Custom template tags for this theme
 * @package WordPress
 * @subpackage The Pet Clinic
 * @since 1.0
 */

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */

if ( ! function_exists( 'the_pet_clinic_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function the_pet_clinic_entry_footer() {

	$separate_meta = __( ', ', 'the-pet-clinic' );
	$categories_list = get_the_category_list( $separate_meta );
	$tags_list = get_the_tag_list( '', $separate_meta );
	if ( ( ( the_pet_clinic_categorized_blog() && $categories_list ) || $tags_list ) || get_edit_post_link() ) {

		echo '<footer class="entry-footer">';			

			the_pet_clinic_edit_link();

		echo '</footer> <!-- .entry-footer -->';
	}
}
endif;


if ( ! function_exists( 'the_pet_clinic_edit_link' ) ) :

function the_pet_clinic_edit_link() {
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'the-pet-clinic' ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

function the_pet_clinic_categorized_blog() {
	$category_count = get_transient( 'the_pet_clinic_categories' );

	if ( false === $category_count ) {
		// Create an array of all the categories that are attached to posts.
		$categories = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$category_count = count( $categories );

		set_transient( 'the_pet_clinic_categories', $category_count );
	}

	// Allow viewing case of 0 or 1 categories in post preview.
	if ( is_preview() ) {
		return true;
	}

	return $category_count > 1;
}

if ( ! function_exists( 'the_pet_clinic_the_custom_logo' ) ) :

function the_pet_clinic_the_custom_logo() {
	the_custom_logo();
}
endif;

function the_pet_clinic_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'the_pet_clinic_categories' );
}
add_action( 'edit_category', 'the_pet_clinic_category_transient_flusher' );
add_action( 'save_post',     'the_pet_clinic_category_transient_flusher' );