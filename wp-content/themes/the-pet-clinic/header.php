<?php
/**
 * The header for our theme
 *
 * @package WordPress
 * @subpackage The Pet Clinic
 * @since 1.0
 * @version 0.1
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="<?php echo esc_url( __( 'http://gmpg.org/xfn/11', 'the-pet-clinic' ) ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
}?>

<a class="screen-reader-text skip-link" href="#wp-toolbar"><?php esc_html_e( 'Skip to content', 'the-pet-clinic' ); ?></a>

<div class="header-box">
	<div class="topbar">
		<div class="container">
			<div class="row m-0">
				<div class="col-lg-5 col-md-12">	
				</div>
				<div class="col-lg-7 col-md-12">
					<div class="contact-details">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<?php if( get_theme_mod( 'the_pet_clinic_email_address') != '' || get_theme_mod( 'the_pet_clinic_email_address_text') != '') { ?>
								<div class="row">
									<div class="col-lg-2 col-md-2">
										<i class="far fa-envelope-open"></i>
									</div>
									<div class="col-lg-10 col-md-10">
										<div class="email">
											<?php echo '<a href="mailto:' . esc_html(antispambot( get_theme_mod( 'the_pet_clinic_email_address','' ) ) ) . '">'. esc_html( get_theme_mod( 'the_pet_clinic_email_address','' ) ) .'</a>'; ?>
											<p><?php echo esc_html( get_theme_mod( 'the_pet_clinic_email_address_text','' ) ); ?></p>
										</div>
									</div>
								</div>
								<?php }?>
							</div>
							<div class="col-lg-6 col-md-6">
								<?php if( get_theme_mod( 'the_pet_clinic_phone_number') != '' || get_theme_mod( 'the_pet_clinic_phone_number_text') != '') { ?>
								<div class="row">
									<div class="col-lg-2 col-md-2">
										<i class="fas fa-phone-volume"></i>
									</div>
									<div class="col-lg-10 col-md-10">
										<div class="numbers">
											<p><?php echo esc_html( get_theme_mod( 'the_pet_clinic_phone_number','' ) ); ?></p>
											<p><?php echo esc_html( get_theme_mod( 'the_pet_clinic_phone_number_text','' ) ); ?></p>
										</div>
									</div>
								</div>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<header class="top-header" role="banner">
		<div class="logo">
	       	<?php if ( has_custom_logo() ) : ?>
		        <div class="site-logo"><?php the_custom_logo(); ?></div>
		    <?php endif; ?>
            <?php if (get_theme_mod('the_pet_clinic_show_site_title',true)) {?>
		        <?php $blog_info = get_bloginfo( 'name' ); ?>
		        <?php if ( ! empty( $blog_info ) ) : ?>
		            <?php if ( is_front_page() && is_home() ) : ?>
			            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		        	<?php else : ?>
	            		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		            <?php endif; ?>
		        <?php endif; ?>
		    <?php }?>
        	<?php if (get_theme_mod('the_pet_clinic_show_tagline',true)) {?>
		        <?php
		        $description = get_bloginfo( 'description', 'display' );
		        if ( $description || is_customize_preview() ) :
		          ?>
			        <p class="site-description">
			            <?php echo esc_html($description); ?>
			        </p>
		        <?php endif; ?>
		    <?php }?>
	    </div>
	    <div class="toggle-menu responsive-menu">
            <button onclick="the_pet_clinic_open()"><i class="fas fa-bars"></i><span class="screen-reader-text"><?php esc_html_e('Open Menu','the-pet-clinic'); ?></span></button>
        </div>
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-12">
				</div>
				<div class="col-lg-6 col-md-12">
					<div id="header" class="menu-section">
			            <div id="sidelong-menu" class="nav sidenav">
			                <nav id="primary-site-navigation" class="nav-menu" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'the-pet-clinic' ); ?>">
			                  <?php 
			                    wp_nav_menu( array( 
			                      'theme_location' => 'primary',
			                      'container_class' => 'main-menu-navigation clearfix' ,
			                      'menu_class' => 'clearfix',
			                      'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
			                      'fallback_cb' => 'wp_page_menu',
			                    ) ); 
			                  ?>
			                  <a href="javascript:void(0)" class="closebtn responsive-menu" onclick="the_pet_clinic_close()"><i class="fas fa-times"></i><span class="screen-reader-text"><?php esc_html_e('Close Menu','the-pet-clinic'); ?></span></a>
			                </nav>
			            </div>
					</div>
				</div>
				<div class="col-lg-3 col-md-12">
					<div class="social-icons">
						<?php if( get_theme_mod( 'the_pet_clinic_facebook_url') != '') { ?>
				      		<a href="<?php echo esc_url( get_theme_mod( 'the_pet_clinic_facebook_url','' ) ); ?>"><i class="fab fa-facebook-f" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_attr_e( 'Facebook','the-pet-clinic' );?></span></a>
					    <?php } ?>
					    <?php if( get_theme_mod( 'the_pet_clinic_twitter_url') != '') { ?>
					      	<a href="<?php echo esc_url( get_theme_mod( 'the_pet_clinic_twitter_url','' ) ); ?>"><i class="fab fa-twitter"></i><span class="screen-reader-text"><?php esc_attr_e( 'Twitter','the-pet-clinic' );?></span></a>
					    <?php } ?>
					    <?php if( get_theme_mod( 'the_pet_clinic_instagram_url') != '') { ?>
				     		<a href="<?php echo esc_url( get_theme_mod( 'the_pet_clinic_instagram_url','' ) ); ?>"><i class="fab fa-instagram"></i><span class="screen-reader-text"><?php esc_attr_e( 'Instagram','the-pet-clinic' );?></span></a>
					    <?php } ?>	
					    <?php if( get_theme_mod( 'the_pet_clinic_youtube_url') != '') { ?>
				     		<a href="<?php echo esc_url( get_theme_mod( 'the_pet_clinic_youtube_url','' ) ); ?>"><i class="fab fa-youtube"></i><span class="screen-reader-text"><?php esc_attr_e( 'YouTube','the-pet-clinic' );?></span></a>
					    <?php } ?>	 
					</div>
				</div>
			</div>
		</div>
	</header>
</div>