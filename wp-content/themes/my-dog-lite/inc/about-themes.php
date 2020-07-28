<?php
//about theme info
add_action( 'admin_menu', 'my_dog_lite_abouttheme' );
function my_dog_lite_abouttheme() {    	
	add_theme_page( esc_html__('About Theme', 'my-dog-lite'), esc_html__('About Theme', 'my-dog-lite'), 'edit_theme_options', 'my_dog_lite_guide', 'my_dog_lite_mostrar_guide');   
} 
//guidline for about theme
function my_dog_lite_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
?>
<div class="wrapper-info">
	<div class="col-left">
   		   <div class="col-left-area">
			  <?php esc_attr_e('Theme Information', 'my-dog-lite'); ?>
		   </div>
          <p><?php esc_attr_e('My Dog Lite WordPress theme is dog WordPress theme for pets, animals, pet shop, veterinary, horse, equestrian, fisheries, aquarium, pet food. Apart from that it can be used for kids, schools, spa and since its green color can be used for nature, eco friendly, solar energy, green and clean energy. It is a multipurpose responsive template and can be used in sync with page builders like Divi, Visual composer, Elementor, Beaver builder, live composer, site origin and others. Ready to plug and play with WooCommerce and other contact form plugins and other plugins.','my-dog-lite'); ?></p>
		  <a href="<?php echo esc_url(MY_DOG_LITE_SKTTHEMES_PRO_THEME_URL); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/free-vs-pro.png" alt="" /></a>
	</div><!-- .col-left -->
	<div class="col-right">			
			<div class="centerbold">
				<hr />
				<a href="<?php echo esc_url(MY_DOG_LITE_SKTTHEMES_LIVE_DEMO); ?>" target="_blank"><?php esc_attr_e('Live Demo', 'my-dog-lite'); ?></a> | 
				<a href="<?php echo esc_url(MY_DOG_LITE_SKTTHEMES_PRO_THEME_URL); ?>"><?php esc_attr_e('Buy Pro', 'my-dog-lite'); ?></a> | 
				<a href="<?php echo esc_url(MY_DOG_LITE_SKTTHEMES_THEME_DOC); ?>" target="_blank"><?php esc_attr_e('Documentation', 'my-dog-lite'); ?></a>
                <div class="space5"></div>
				<hr />                
                <a href="<?php echo esc_url(MY_DOG_LITE_SKTTHEMES_THEMES); ?>" target="_blank"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/sktskill.jpg" alt="" /></a>
			</div>		
	</div><!-- .col-right -->
</div><!-- .wrapper-info -->
<?php } ?>