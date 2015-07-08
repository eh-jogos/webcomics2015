<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
		<?php if ( have_posts() ) { ?>
		
		<?php get_template_part( 'webcomic/content', 'webcomicArchive' ); ?>
		
		<div id="archive-separator" <?php post_class("entry-content"); ?>>
			<p>Lista de todas as histórias, capitulos e páginas dessa webcomic:</p>
		</div>
		
		<article id="character-archive" <?php post_class("webcomic-archive"); ?>>
			
			<div class="entry-content">
												
				<ul class="webcomic-terms">	
				<?php 						
					$story_tax = get_taxonomies(array("name"=> get_post_type()."_storyline"));
					$storylineObj = get_terms($story_tax, array("orderby"=>"id", "order"=>"ASC"));
					
					//var_dump( get_queried_object()->term_id,get_query_var("term"));
					
					foreach($storylineObj as $storyline){
						$slug = $storyline->slug;
						$name = $storyline->name;
						$description = $storyline->description;
						$image = $storyline->webcomic_image;
						$count = $storyline->count;
						$parent = $storyline->parent;
						
						$args = array(
							'posts_per_page' => -1,
							'post_type' => get_post_type(),
							'order' => "ASC",
						);
						$Storyline_query = new WP_Query( $args);
						
						if ( $Storyline_query->have_posts() ) { 
							if ( $count == 0 && $parent == 0){?> 
								<li class="webcomic-term parent-storyline">
									<a href="<?php echo get_term_link($slug,get_post_type()."_storyline"); ?>" class="webcomic-term-link"><?php echo $name;?>
										<div class="webcomic-term-name"><?php echo $name; ?></div>
										<?php if ($image) : ?>
										<div class="webcomic-term-image"><?php  echo  wp_get_attachment_image($image,"full");  ?></div>
										<?php endif; ?>
									</a>
									<?php if ($description) : ?>
									<div class="webcomic-term-description"><?php echo $description;?></div>
									<?php endif; ?>
								</li>							
							<?php } elseif ( $parent != 0 ){ ?>
								<li class="webcomic-term child-storyline">
									<a href="<?php echo get_term_link($slug,get_post_type()."_storyline"); ?>" class="webcomic-term-link"><?php echo $name;?>
										<div class="webcomic-term-name"><?php echo $name; ?></div>
										<?php if ($image) : ?>
										<div class="webcomic-term-image"><?php  echo  wp_get_attachment_image($image,"full");  ?></div>
										<?php endif; ?>
									</a>
									<?php if ($description) : ?>
									<div class="webcomic-term-description"><?php echo $description;?></div>
									<?php endif; ?>
									<ul class="webcomics">
										<?php 
										while ( $Storyline_query->have_posts() ) { 
											$Storyline_query->the_post();
											get_template_part('webcomic/content',"thumbailList", get_post_type());
										}
										?>
									</ul>
								</li>
							<?php } else { ?>
								<li class="webcomic-term">
									<a href="<?php echo get_term_link($slug,get_post_type()."_storyline"); ?>" class="webcomic-term-link"><?php echo $name;?>
										<div class="webcomic-term-name"><?php echo $name; ?></div>
										<?php if ($image) : ?>
										<div class="webcomic-term-image"><?php  echo  wp_get_attachment_image($image,"full");  ?></div>
										<?php endif; ?>
									</a>
									<?php if ($description) : ?>
									<div class="webcomic-term-description"><?php echo $description;?></div>
									<?php endif; ?>
									<ul class="webcomics">
										<?php 
										while ( $Storyline_query->have_posts()) { 
											$Storyline_query->the_post();
											get_template_part('webcomic/content',"thumbailList", get_post_type());
										}
										?>
									</ul>
								</li>
							<?php 
							};
						};
						wp_reset_postdata(); 
					};
					?>
				</ul>
			</div><!-- .entry-content -->
		</article>
		
		<?php 
		} else {
			get_template_part( 'content', 'none' ); 
		}
		?>
	

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
