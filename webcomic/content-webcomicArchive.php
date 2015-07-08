<?php
/**
 * The default template for displaying content
 *
 * Used for my custom Webcomic Archives from Webcomic 20-12 and Webcomic 20-15 child Themes
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<header class="page-header">
		<h1 class="page-title">
		<?php
			if ( is_webcomic_archive()) :
				post_type_archive_title(sprintf('%s', __('Arquivo: ', 'twentytwelve')));
			elseif ( is_webcomic_storyline() ) :
				webcomic_storyline_title(sprintf('%s', __('Arquivo: ', 'twentytwelve')));
			elseif ( is_webcomic_character()) :
				webcomic_character_title(sprintf('%s', __('Personagem: ', 'twentytwelve')));
			else :
				_e( 'Archives', 'twentytwelve' );
			endif;
		?>
		</h1>
	</header><!-- .archive-header -->

	<article id="post-<?php the_ID(); ?>" <?php post_class("webcomic-archive-content"); ?>>
		<div class="entry-content">
			
			<?php if ( is_webcomic_archive()) : ?>
				<?php if (WebcomicTag::webcomic_collection_image()) : ?>

					<div class="page-image"><?php webcomic_collection_poster(); ?></div><!-- .page-image -->

				<?php endif; ?>

				<?php if (WebcomicTag::webcomic_collection_description()) : ?>

					<p><?php webcomic_collection_description(); ?></p>

				<?php endif; ?>
					
			<?php elseif ( is_webcomic_storyline() ) : ?>
				<?php if (WebcomicTag::webcomic_term_image()) : ?>

					<div class="taxonomy-image"><?php webcomic_storyline_cover(); ?></div><!-- .page-image -->

				<?php endif; ?>

				<?php if (WebcomicTag::webcomic_term_description()) : ?>

					<p><?php webcomic_storyline_description(); ?></p>

				<?php endif; ?>
			<?php elseif ( is_webcomic_character()) : ?>
				<?php if (WebcomicTag::webcomic_term_image()) : ?>

					<div class="taxonomy-image"><?php webcomic_character_avatar(); ?></div><!-- .page-image -->

				<?php endif; ?>

				<?php if (WebcomicTag::webcomic_term_description()) : ?>

					<p><?php webcomic_character_description(); ?></p>

				<?php endif; ?>
				<p>
				Apareceu pela primeira vez na página: <?php first_webcomic_link( '%link', '%title',get_queried_object()->term_id, false, 'character')?> <br>
				Apareceu pela última vez na página: <?php last_webcomic_link( '%link', '%title',get_queried_object()->term_id, false, 'character')?> <br>
				Total de páginas em que apareceu: <?php echo get_queried_object()->count ?> <br>
				</p>
	
			<?php else :
				get_template_part( 'content', 'none' );
			 endif; ?>
			
		</div><!-- .entry-content -->
	</article><!-- #post -->
