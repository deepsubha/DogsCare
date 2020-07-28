<?php
/**
 * Displays footer site info
 *
 * @package WordPress
 * @subpackage The Pet Clinic
 * @since 1.0
 * @version 1.4
 */

?>
<div class="site-info">
	<p><?php the_pet_clinic_credit(); ?> <?php echo esc_html(get_theme_mod('the_pet_clinic_footer_copy',__('By Luzuk','the-pet-clinic'))); ?> </p>
</div>