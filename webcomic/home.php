<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>
		
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
		<?php 
			
			$webcomics = false;

			if (get_theme_mod('webcomic_home_order', 'DESC') and ! is_paged()) :
				$webcomics = new WP_Query(array(
					'order' => get_theme_mod('webcomic_home_order', 'DESC'),
					'post_type' => get_theme_mod('webcomic_home_collection', '') ? get_theme_mod('webcomic_home_collection', '') : get_webcomic_collections(),
					'posts_per_page' => 1
				));
			endif;	

			if ($webcomics and $webcomics->have_posts()) :
				while ($webcomics->have_posts()) : $webcomics->the_post();
					$compassus = get_post_meta(get_the_ID(),"is_compassus_webcomic",true);
					//var_dump($compassus);
					if ( $compassus == true ){
						get_template_part('webcomic/display', "compassus"); 
					} else{
						get_template_part('webcomic/display', get_post_type()); 
					}
					
					get_template_part('webcomic/content', get_post_type()); 

				endwhile;
				
				if (get_theme_mod('webcomic_front_page_transcripts', false)) :
					webcomic_transcripts_template();
				endif;

				if (get_theme_mod('webcomic_front_page_comments', false)) :
					$withcomments = true;
					
					comments_template();
					
					$withcomments = false;
				endif;
				$webcomics->rewind_posts();
			endif;

		?>
		
		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
				'next_text'          => __( 'Next page', 'twentyfifteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
