<?php
/**
 * Template Name: Custom Home
 */

get_header(); ?>

<main id="wp-toolbar" role="main">
	<?php do_action( 'the_pet_clinic_above_slider' ); ?>

	<?php if( get_theme_mod('the_pet_clinic_slider_hide_show') != ''){ ?>
		<section id="slider">
		  	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel"> 
			    <?php $the_pet_clinic_slider_pages = array();
			      	for ( $count = 1; $count <= 4; $count++ ) {
				        $mod = intval( get_theme_mod( 'the_pet_clinic_slider' . $count ));
				        if ( 'page-none-selected' != $mod ) {
				          $the_pet_clinic_slider_pages[] = $mod;
				        }
			      	}
			      	if( !empty($the_pet_clinic_slider_pages) ) :
			        $args = array(
			          	'post_type' => 'page',
			          	'post__in' => $the_pet_clinic_slider_pages,
			          	'orderby' => 'post__in'
			        );
			        $query = new WP_Query( $args );
			        if ( $query->have_posts() ) :
			          $i = 1;
			    ?>     
			    <div class="carousel-inner" role="listbox">
			      	<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			        <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
			          	<img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php esc_html( the_title() ); ?> post thumbnail image"/>
			          	<div class="carousel-caption">
				            <div class="inner_carousel">				            	
				              	<h2><?php esc_html( the_title() ); ?></h2>
				            </div>
				            <div class="read-btn">
			            		<a href="<?php esc_url( the_permalink() );?>" title="<?php esc_attr_e( 'VIEW PROJECTS', 'the-pet-clinic' ); ?>" ><?php esc_html_e('VIEW PROJECTS','the-pet-clinic'); ?><span class="screen-reader-text"><?php esc_html( the_title() ); ?></span></a>
					       	</div>
			          	</div>
			        </div>
			      	<?php $i++; endwhile; 
			      	wp_reset_postdata();?>
			    </div>
			    <?php else : ?>
			    <div class="no-postfound"></div>
			      <?php endif;
			    endif;?>
			    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev" alt="<?php esc_attr_e( 'Previous','the-pet-clinic' );?>">
		      		<span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
		      		<span class="screen-reader-text"><?php esc_attr_e( 'Previous','the-pet-clinic' );?></span>
			    </a>
			    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next" alt="<?php esc_attr_e( 'Next','the-pet-clinic' );?>">
		      		<span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
		      		<span class="screen-reader-text"><?php esc_attr_e( 'Next','the-pet-clinic' );?></span>
			    </a>
		  	</div>  
		  	<div class="clearfix"></div>
		</section>
	<?php }?>

	<?php do_action('the_pet_clinic_below_slider'); ?>

	<?php /*--- Our services ---*/ ?>
	<?php if( get_theme_mod('the_pet_clinic_facilities_title') != '' || get_theme_mod( 'the_pet_clinic_facilities_cat' )!= ''){ ?>
		<section id="our_facilities">
			<div class="container">
				<div class="facilities-head">
					<h4><?php echo esc_html(get_theme_mod('the_pet_clinic_facilities_smalltitle','')); ?></h4>
					<hr>
					<?php if( get_theme_mod('the_pet_clinic_facilities_title') != ''){ ?>
			        	<h3><?php echo esc_html(get_theme_mod('the_pet_clinic_facilities_title','')); ?></h3>
			        <?php }?>
			    </div>
			    <div class="facilities-content">
					<div class="row">
						<div class="col-lg-4">
							<div class="row">
								<?php $the_pet_clinic_catData1=  get_theme_mod('the_pet_clinic_facilities_cat_left'); 
								if($the_pet_clinic_catData1){ 
						  			$args = array(
										'post_type' => 'post',
										'category_name' => esc_html($the_pet_clinic_catData1 ,'the-pet-clinic')
							        );
					      		$the_pet_clinic_page_query = new WP_Query($args);?>
								<?php while( $the_pet_clinic_page_query->have_posts() ) : $the_pet_clinic_page_query->the_post(); ?>
								<div class="col-lg-3 col-md-3">
									<div class="facilitiesbox-img">
										<?php if(has_post_thumbnail()) { ?><?php the_post_thumbnail(); ?>
										<?php } ?>
									</div>
								</div>
								<div class="facilities_left col-lg-9 col-md-9">
									<h4><?php esc_html( the_title() ); ?></h4>
									<p><?php $the_pet_clinic_excerpt = get_the_excerpt(); echo esc_html( the_pet_clinic_string_limit_words( $the_pet_clinic_excerpt,15 ) ); ?></p>
									<div class="read-more">
										<a href="<?php esc_url( the_permalink() );?>"><?php esc_html_e('Read Details','the-pet-clinic'); ?><span class="screen-reader-text"><?php esc_html_e( 'Read Details','the-pet-clinic' );?></span></a>
									</div>
								</div>
								<?php endwhile; 
							      	wp_reset_postdata();
							    }?>
							</div>
						</div>
						<div class="col-lg-4">
							<img src="<?php echo esc_url(get_theme_mod('the_pet_clinic_facilities_image')); ?>">
						</div>
						<div class="col-lg-4">
							<div class="row">
								<?php $the_pet_clinic_catData1=  get_theme_mod('the_pet_clinic_facilities_cat_right'); 
								if($the_pet_clinic_catData1){ 
						  			$args = array(
										'post_type' => 'post',
										'category_name' => esc_html($the_pet_clinic_catData1 ,'the-pet-clinic')
							        );
					      		$the_pet_clinic_page_query = new WP_Query($args);?>
								<?php while( $the_pet_clinic_page_query->have_posts() ) : $the_pet_clinic_page_query->the_post(); ?>
								<div class="facilities_right col-lg-9 col-md-9">
									<h4><?php esc_html( the_title() ); ?></h4>
									<p><?php $the_pet_clinic_excerpt = get_the_excerpt(); echo esc_html( the_pet_clinic_string_limit_words( $the_pet_clinic_excerpt,15 ) ); ?></p>
									<div class="read-more">
										<a href="<?php esc_url( the_permalink() );?>"><?php esc_html_e('Read Details','the-pet-clinic'); ?><span class="screen-reader-text"><?php esc_html_e( 'Read Details','the-pet-clinic' );?></span></a>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="facilitiesbox-img">
										<?php if(has_post_thumbnail()) { ?><?php the_post_thumbnail(); ?>
										<?php } ?>
									</div>
								</div>
								<?php endwhile; 
							      	wp_reset_postdata();
							    }?>
							</div>
						</div>
			        </div>
			    </div>
			</div>
		</section>
	<?php }?>

	<?php do_action('the_pet_clinic_below_services_section'); ?>

	<div class="container">
	  	<?php while ( have_posts() ) : the_post(); ?>
	        <?php the_content(); ?>
	    <?php endwhile; // end of the loop. ?>
	</div>
</main>

<?php get_footer(); ?>