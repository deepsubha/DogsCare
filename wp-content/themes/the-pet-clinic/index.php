<?php
/**
 * The main template file
 * @package WordPress
 * @subpackage The Pet Clinic
 * @since 1.0
 * @version 0.1
 */

get_header(); ?>

<div class="container">
	<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="page-header">
			<h2 class="page-title"><?php single_post_title(); ?></h2>
		</header>
	<?php else : ?>
		<header class="page-header">
			<h3 class="page-title"><?php esc_html_e( 'Posts', 'the-pet-clinic' ); ?></h3>
		</header>
	<?php endif; ?>

	<div class="content-area">
		<main id="wp-toolbar" class="site-main" role="main">
			<?php
			    $layout_option = get_theme_mod( 'the_pet_clinic_theme_options',__( 'Right Sidebar','the-pet-clinic') );
			    if($layout_option == 'Left Sidebar'){ ?>
			    	<div class="row">
				        <div id="sidebar" class="col-lg-4 col-md-4"><?php dynamic_sidebar('sidebar-1'); ?></div>
				        <div id="" class="content_area col-lg-8 col-md-8">
					    	<section id="post_section">
								<?php
								if ( have_posts() ) :
									while ( have_posts() ) : the_post();

										get_template_part( 'template-parts/post/content' );

									endwhile;

									else :

										get_template_part( 'template-parts/post/content', 'none' );

									endif;
								?>
								<div class="navigation">
					                <?php
					                    
					                    the_posts_pagination( array(
					                        'prev_text'          => __( 'Previous page', 'the-pet-clinic' ),
					                        'next_text'          => __( 'Next page', 'the-pet-clinic' ),
					                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-pet-clinic' ) . ' </span>',
					                    ) );
					                ?>
					                <div class="clearfix"></div>
				           	 	</div>
							</section>
						</div>
						<div class="clearfix"></div>
					</div>					
			<?php }else if($layout_option == 'Right Sidebar'){ ?>
				<div class="row">
					<div id="" class="content_area col-lg-8 col-md-8">
						<section id="post_section">
							<?php
							if ( have_posts() ) :

								/* Start the Loop */
								while ( have_posts() ) : the_post();

									get_template_part( 'template-parts/post/content' );

								endwhile;

								else :

									get_template_part( 'template-parts/post/content', 'none' );

								endif;
							?>
							<div class="navigation">
				                <?php
				                    // Previous/next page navigation.
				                    the_posts_pagination( array(
				                        'prev_text'          => __( 'Previous page', 'the-pet-clinic' ),
				                        'next_text'          => __( 'Next page', 'the-pet-clinic' ),
				                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-pet-clinic' ) . ' </span>',
				                    ) );
				                ?>
				                <div class="clearfix"></div>
				            </div>
						</section>
					</div>
					<div id="sidebar" class="col-lg-4 col-md-4"><?php dynamic_sidebar('sidebar-1'); ?></div>
				</div>
			<?php }else if($layout_option == 'One Column'){ ?>	
				<div id="" class="content_area">
					<section id="post_section">
						<?php
							if ( have_posts() ) :

							/* Start the Loop */
							while ( have_posts() ) : the_post();

								get_template_part( 'template-parts/post/content' );

							endwhile;

							else :

								get_template_part( 'template-parts/post/content', 'none' );

							endif;
						?>
						<div class="navigation">
			                <?php
			                    // Previous/next page navigation.
			                    the_posts_pagination( array(
			                        'prev_text'          => __( 'Previous page', 'the-pet-clinic' ),
			                        'next_text'          => __( 'Next page', 'the-pet-clinic' ),
			                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-pet-clinic' ) . ' </span>',
			                    ) );
			                ?>
			                <div class="clearfix"></div>
			            </div>
					</section>
				</div>
			<?php }else if($layout_option == 'Three Columns'){ ?>	
				<div class="row">
					<div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar('sidebar-1'); ?></div>	
					<div id="" class="content_area col-lg-6 col-md-6">
						<section id="post_section">
							<?php
							if ( have_posts() ) :

								/* Start the Loop */
								while ( have_posts() ) : the_post();

									
									get_template_part( 'template-parts/post/content' );

								endwhile;

								else :

									get_template_part( 'template-parts/post/content', 'none' );

								endif;
							?>
							<div class="navigation">
				                <?php
				                    // Previous/next page navigation.
				                    the_posts_pagination( array(
				                        'prev_text'          => __( 'Previous page', 'the-pet-clinic' ),
				                        'next_text'          => __( 'Next page', 'the-pet-clinic' ),
				                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-pet-clinic' ) . ' </span>',
				                    ) );
				                ?>
				                <div class="clearfix"></div>
				            </div>
						</section>
					</div>
					<div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar('sidebar-2'); ?></div>
				</div>
			<?php }else if($layout_option == 'Four Columns'){ ?>
				<div class="row">
					<div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar('sidebar-1'); ?></div>
					<div id="" class="content_area col-lg-3 col-md-3">
						<section id="post_section">
							<?php
							if ( have_posts() ) :

								/* Start the Loop */
								while ( have_posts() ) : the_post();

									get_template_part( 'template-parts/post/content' );

								endwhile;

								else :

									get_template_part( 'template-parts/post/content', 'none' );

								endif;
							?>
								<div class="navigation">
					                <?php
					                    // Previous/next page navigation.
					                    the_posts_pagination( array(
					                        'prev_text'          => __( 'Previous page', 'the-pet-clinic' ),
					                        'next_text'          => __( 'Next page', 'the-pet-clinic' ),
					                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-pet-clinic' ) . ' </span>',
					                    ) );
					                ?>
					                <div class="clearfix"></div>
					            </div>
						</section>
					</div>
					<div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar('sidebar-2'); ?></div>
			        <div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar('sidebar-3'); ?></div>
		        </div>
		    <?php } else if($layout_option == 'Grid Layout'){ ?>
		    	<div id="" class="content_area">
					<section id="post_section">
						<div class="row">
							<?php
							if ( have_posts() ) :

								/* Start the Loop */
								while ( have_posts() ) : the_post();

									get_template_part( 'template-parts/post/grid-layout' );

								endwhile;
								
								else :

									get_template_part( 'template-parts/post/content', 'none' );

								endif;
							?>
							<div class="navigation">
				                <?php
				                    // Previous/next page navigation.
				                    the_posts_pagination( array(
				                        'prev_text'          => __( 'Previous page', 'the-pet-clinic' ),
				                        'next_text'          => __( 'Next page', 'the-pet-clinic' ),
				                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-pet-clinic' ) . ' </span>',
				                    ) );
				                ?>
				                <div class="clearfix"></div>
				            </div>
						</div>
					</section>
				</div>
			<?php } else { ?>
				<div class="row">
					<div id="" class="content_area col-lg-8 col-md-8">
						<section id="post_section">
							<?php
							if ( have_posts() ) :
								/* Start the Loop */
								while ( have_posts() ) : the_post();

									get_template_part( 'template-parts/post/content' );
								endwhile;
								else :

									get_template_part( 'template-parts/post/content', 'none' );
								endif;
							?>
							<div class="navigation">
				                <?php
				                    // Previous/next page navigation.
				                    the_posts_pagination( array(
				                        'prev_text'          => __( 'Previous page', 'the-pet-clinic' ),
				                        'next_text'          => __( 'Next page', 'the-pet-clinic' ),
				                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'the-pet-clinic' ) . ' </span>',
				                    ) );
				                ?>
				                <div class="clearfix"></div>
				            </div>
						</section>
					</div>
					<div id="sidebar" class="col-lg-4 col-md-4"><?php dynamic_sidebar('sidebar-1'); ?>
					</div>
				</div>
			<?php } ?>
		</main>
	</div>
</div>

<?php get_footer();