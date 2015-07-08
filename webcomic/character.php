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
	
	$charExist = get_queried_object()->count;
	?>
	
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			
			<?php  if ( !$charExist == 0 ){  ?>
				
				<?php get_template_part( 'webcomic/content', 'webcomicArchive' ); ?>
				
				<div id="archive-separator" <?php post_class("entry-content"); ?>>
					<p>Lista de todas as histórias, capitulos e páginas onde esse personagem apareceu:</p>
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
									"tax_query" => array("relation" => "AND"),
									get_post_type()."_character" => get_query_var("term"),
									get_post_type()."_storyline" => $slug,
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
				
				<nav class="navigation post-navigation archive-navigation" role="navigation">
					<?php
					
					$character_tax = get_taxonomies(array("name"=> get_post_type()."_character"));
					$characterObj = get_terms($character_tax);
					$char_count = count($characterObj);
					$current_char;
					$i = 0;
					foreach($characterObj as $character){
						//var_dump($character->slug,get_query_var("term"), $i );
						if($character->slug == get_query_var("term")){
							$current_char = $i;
							break;
						} else {
							$i++;
						}
					};
					
					$first_char = 0;
					$previous_char = $current_char -1;
					$next_char = $current_char +1;
					$last_char = $char_count-1;
					$css = "";
					//var_dump($characterObj,wp_get_attachment_image_src( $characterObj[$last_char]->webcomic_image));

					?>
					
					<h2 class="screen-reader-text">Post navigation</h2>
					<div class="nav-links">
					<?php if( $previous_char < 0 ){ 
						if ($last_char != $next_char){ ?>
							<?php
							if (WebcomicTag::webcomic_term_image($characterObj[$last_char]->term_id,"character")) :
								$prevthumb =  wp_get_attachment_image_src( $characterObj[$last_char]->webcomic_image);
								$css .= '
								/* last char */
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
								<a href="<?php 	echo WebcomicTag::get_relative_webcomic_term_link("archive","last","character") ; ?>" rel="prev">
									<span class="meta-nav" aria-hidden="true">Previous Character</span> 
									<span class="screen-reader-text">Previous character archive:</span> 
									<span class="post-title"><?php echo $characterObj[$last_char]->name ?></span>

								</a>
							</div>
					<?php }					
					 } else{ ?>
						
						<?php
						if (WebcomicTag::webcomic_term_image($characterObj[$previous_char]->term_id,"character")) :
							$prevthumb =  wp_get_attachment_image_src( $characterObj[$previous_char]->webcomic_image);
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
							<a href="<?php 	echo home_url()."/oeste-selvagem-character/".$characterObj[$previous_char]->slug ; ?>" rel="prev">
								<span class="meta-nav" aria-hidden="true">Previous Character</span> 
								<span class="screen-reader-text">Previous chcaracter archive:</span> 
								<span class="post-title"><?php echo $characterObj[$previous_char]->name ?></span>
							</a>
						</div>

					<?php };
					if( $next_char >= $char_count ){ 
						if ($first_char != $previous_char){ ?>
							<?php
							if (WebcomicTag::webcomic_term_image($characterObj[$first_char]->term_id,"character")) :
								$nextthumb =  wp_get_attachment_image_src( $characterObj[$first_char]->webcomic_image);
								$css .= '
									/* first char */
									.post-navigation .nav-next { 
										background-image: url(' . esc_url( $nextthumb[0] ) . '); 
										border-top: 0; 
									}
									
									.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { 
										color: #fff; 
									}
									
									.post-navigation .nav-next a:before { 
										background-color: rgba(0, 0, 0, 0.4); 
									}
								';
								?> <style> <?php echo $css; ?> </style> <?php
								$css = "";
							endif;
							?>
						
							<div class="nav-next">
								<a href="<?php 	echo WebcomicTag::get_relative_webcomic_term_link("archive","first","character") ; ?>" rel="next">
									<span class="meta-nav" aria-hidden="true">Next Character</span> 
									<span class="screen-reader-text">Next character archive:</span> 
									<span class="post-title"><?php echo $characterObj[$first_char]->name ?></span>
								</a>
							</div>
					<?php  }
					} else{ ?>
					
						<?php
						if (WebcomicTag::webcomic_term_image($characterObj[$next_char]->term_id,"character")) :
							$nextthumb =  wp_get_attachment_image_src($characterObj[$next_char]->webcomic_image);
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
							<a href="<?php 	echo WebcomicTag::get_relative_webcomic_term_link("archive","next","character") ; ?>" rel="next">
								<span class="meta-nav" aria-hidden="true">Next Character</span> 
								<span class="screen-reader-text">Next character archive:</span> 
								<span class="post-title"><?php echo $characterObj[$next_char]->name ?></span>
							</a>
						</div>
					<?php }; ?>
					
					</div>
				</nav>
			<?php 
			} else { 
				get_template_part( 'content', 'none' );
			};
			?>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
