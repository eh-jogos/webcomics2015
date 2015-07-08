<?php
/**
 * The default template for displaying content
 *
 * Used for both webcomic lists using only the thumbnail for my custom archives
 *
 * @package WordPress
 */
?>

<li>	
	<a href="<?php the_permalink(); ?>" rel="bookmark">
	<?php the_webcomic('thumbnail'); ?>
	</a>
</li>