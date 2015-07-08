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

	<?php
	if ('ASC' === get_theme_mod('webcomic_archive_order', 'ASC')) :
		global $wp_query;
		
		query_posts(array_merge($wp_query->query_vars, array(
			'order' => get_theme_mod('webcomic_archive_order', 'ASC')
		)));
	endif; 
	
	//variable declaration for ease of acess to some of the queried object properties
	$count = get_queried_object()->count;
	$parent = get_queried_object()->parent;
	$storylineID = get_queried_object()->term_id;
	$name = get_queried_object()->name;
	$description = get_queried_object()->description;

	//var_dump(get_queried_object());
	//END OF queried object properties
	
	//get an array with all the storyline objects for the current collection
	$storylineObj = get_terms(get_post_type()."_storyline", array("orderby"=>"id", "order"=>"ASC"));

	//declares the array that will receive the filtered child storylines
	$childStoryObj = Array ();
	
	?>

<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			
			<?php 	
			
			get_template_part( 'webcomic/content', 'webcomicArchive' );
			
			//If the queried storyline is a main storyline but has no webcomics, it means it's a parent storyline
			//made for holding child storylines inside it.
			if ($count != 0 && $parent != 0){ ?>
				<div id="archive-separator" <?php post_class("entry-content"); ?>>
					<p>Lista de páginas desse capítulo:</p>
				</div>
			<?php } else{ ?>
				<div id="archive-separator" <?php post_class("entry-content"); ?>>
					<p>Lista de capitulos e páginas dessa história:</p>
				</div>
			<?php } ?>
			<article id="storyline-archive" <?php post_class("webcomic-archive"); ?>>

					<?php 						
						//We need to creat a list of child storylines from the current queried storyline
						//Let's check each of the storylines objects from this collection to find the ones 
						//that are childs of the queried storyline
						foreach($storylineObj as $storyline){
							//if the current stroyline object's parent property is the same as the 
							//queried storyline ID, it means it's a child of the queried storyline
							//so push it to the child storylines array
							if($storyline->parent == $storylineID){
							array_push($childStoryObj,$storyline);
							}
						};
						
						//var_dump( $storylineID,$storylineObj, $childStoryObj );
						
						$args = array(
							'posts_per_page' => -1,
							'post_type' => get_post_type(),
							'order' => "ASC",
							get_post_type()."_storyline" => get_query_var("term"),
						);

						$Storyline_query = new WP_Query( $args);
						if ( $Storyline_query->have_posts() ) { 
								if ( $count == 0 && $parent == 0){ ?>
									<div class="entry-content">
										<?php 
											
											foreach($childStoryObj as $childStory){
												$argsChild = array(
													'posts_per_page' => -1,
													'post_type' => get_post_type(),
													'order' => "ASC",
													get_post_type()."_storyline" => $childStory->slug,
												);
												
												$childStory_query = new WP_Query( $argsChild);
												if ( $childStory_query->have_posts() ) { ?>
											
													<li class="webcomic-term">
														<a href="<?php echo get_term_link($childStory->slug,get_post_type()."_storyline"); ?>" class="webcomic-term-link">
															<?php echo $childStory->name;?>
															<div class="webcomic-term-name"><?php echo $childStory->name; ?></div>
															<?php if ($childStory->webcomic_image) : ?>
																<div class="webcomic-term-image"><?php  echo  wp_get_attachment_image($childStory->webcomic_image,"full");  ?></div>
															<?php endif; ?>
														</a>
														
														<?php if ($childStory->description) : ?>
														<div class="webcomic-term-description"><?php echo $childStory->description;?></div>
														<?php endif; ?>
														
														<ul class="webcomics">
															<?php
															while ( $childStory_query->have_posts()) { 
																$childStory_query->the_post();
																get_template_part('webcomic/content',"thumbailList", get_post_type());
															}
															?>
														</ul><!-- .webcomics -->
													</li><!-- .webcomic-term -->
											
												<?php }
												wp_reset_postdata();
											}
										
										?>
									</div><!-- .entry-content -->
								<?php } elseif ( $count == 0 ){
									get_template_part( 'content', 'none' );	
								} else { ?>
									<div class="entry-content">
											<ul class="webcomics">
												<?php 
												while ( $Storyline_query->have_posts()) { 
													$Storyline_query->the_post();
													get_template_part('webcomic/content',"thumbailList", get_post_type());
												}
												?>
											</ul><!-- .webcomics -->
									</div><!-- .entry-content -->
								<?php 
								};
							};
							wp_reset_postdata();
					?>
					

			</article>
			
			<nav class="navigation post-navigation archive-navigation" role="navigation">
				<?php
				
				$story_count = count($storylineObj);
				$current_story;
				$i = 0;
				foreach($storylineObj as $storyline){
					//var_dump($character->slug,get_query_var("term"), $i );
					if($storyline->slug == get_query_var("term")){
						$current_story = $i;
						break;
					} else {
						$i++;
					}
				};
					
				$first_story = 0;
				$previous_story = $current_story -1;
				$next_story = $current_story +1;
				$last_story = $story_count-1;
				$css = "";
				//var_dump($characterObj,wp_get_attachment_image_src( $characterObj[$last_char]->webcomic_image));

				?>
					
				<h2 class="screen-reader-text">Post navigation</h2>
				<div class="nav-links">
				<?php if( $previous_story >= 0 ){ 
					
					if (WebcomicTag::webcomic_term_image($storylineObj[$previous_story]->term_id,"storyline")) :
						$prevthumb =  wp_get_attachment_image_src( $storylineObj[$previous_story]->webcomic_image);
						$css .= '
						/* previous char */
						.post-navigation .nav-previous { 
							background-image: url(' . esc_url( $prevthumb[0] ) . '); 
						}
						
						.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { 
							color: #fff; 
						}
						
						.post-navigation .nav-previous a:before { 
							background-color: rgba(0, 0, 0, 0.4); 
						}
						
						';
						?> <style> <?php echo $css; ?> </style> <?php
						$css = "";
					endif;
					?>
			 
					<div class="nav-previous">
						<a href="<?php 	echo home_url()."/oeste-selvagem-storyline/".$storylineObj[$previous_story]->slug ; ?>" rel="prev">
							<span class="meta-nav" aria-hidden="true">Previous</span> 
							<span class="screen-reader-text">Previous storyline archive:</span> 
							<span class="post-title"><?php echo $storylineObj[$previous_story]->name ?></span>
						</a>
					</div>

				<?php };
				if( $next_story < $story_count ){ 
						
					if (WebcomicTag::webcomic_term_image($storylineObj[$next_story]->term_id,"storyline")) :
						$nextthumb =  wp_get_attachment_image_src($storylineObj[$next_story]->webcomic_image);
						$css .= '
							/* next char */
							.post-navigation .nav-next { 
								background-image: url(' . esc_url( $nextthumb[0] ) . ');
								border-top: 0; 
							}
							
							.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { 
								color: #fff; 
							}
							
							.post-navigation .nav-next a:before { 
								background-color: rgba(0, 0, 0, 0.2); 
							}
						';
						?> <style> <?php echo $css; ?> </style> <?php
						$css = "";
					endif;
					?>
										
					<div class="nav-next">
						<a href="<?php 	echo WebcomicTag::get_relative_webcomic_term_link("archive","next","storyline") ; ?>" rel="next">
							<span class="meta-nav" aria-hidden="true">Next</span> 
							<span class="screen-reader-text">Next storyline archive:</span> 
							<span class="post-title"><?php echo $storylineObj[$next_story]->name ?></span>
						</a>
					</div>
				<?php }; ?>
					
				</div>
			</nav>
			
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
