<?php
/**
 * Template for displaying search forms in The Pet Clinic
 *
 * @package WordPress
 * @subpackage The Pet Clinic
 * @since 1.0
 * @version 0.1
 */
?>

<?php $the_pet_clinic_unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_attr_e( 'Search for:','the-pet-clinic' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder','the-pet-clinic' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<button type="submit" class="search-submit" role="tab"><?php echo esc_attr_x( 'Search', 'submit button', 'the-pet-clinic' ); ?></button>
</form>