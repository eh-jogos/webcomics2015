<?php
/**
 * Webcomic display template.
 * 
 * Handles webcomic image and navigation display.
 * 
 * @subpackage Twenty_Twelve Child - Twenty_Twelve 
 */

?>
<div id="webcomic-display" class="webcomic-display-wrapper">

	<nav id="webcomic-display-nav-up" class="webcomic-nav-wrapper" >
		<ul id="webcomic-nav-up" class="webcomic-nav">
			<li id= "webcomic-nav-leftmost-btn" class="webcomic-nav-button" ><?php  first_webcomic_link("%link","&laquo; <span>Primeira Página</span>", true); ?></li>
			<li id= "webcomic-nav-left-btn" class="webcomic-nav-button" ><?php  previous_webcomic_link("%link","&lsaquo; <span>Página Anterior</span>"); ?></li>

			<?php if (get_post_type() == "webcomic1"):?>
				<li id= "webcomic-nav-middle-btn" class="webcomic-nav-button" ><?php  webcomic_dropdown_storylines( array( "id"=>"webcomic-dropdown", "class"=> "webcomic-dropdown-menu", "collection"=>get_post_type(), "show_option_all"=>"Escolha uma História","webcomics"=>true, "selected" => get_the_ID()) ); ?></li>
			<?php else : ?>
				<li id= "webcomic-nav-middle-btn" class="webcomic-nav-button" ><?php  webcomic_dropdown_storylines( array( "id"=>"webcomic-dropdown", "class"=> "webcomic-dropdown-menu", "collection"=>get_post_type(), "show_option_all"=>"Escolha uma Página") ); ?></li>
			<?php endif; ?>
			
			<li id= "webcomic-nav-right-btn" class="webcomic-nav-button" ><?php  next_webcomic_link("%link","<span>Próxima Página</span> &rsaquo;"); ?></li>
			<li id= "webcomic-nav-rightmost-btn" class="webcomic-nav-button" ><?php  last_webcomic_link("%link","<span>Última Página</span> &raquo;", true); ?></li>
		</ul>
	</nav>
	<div id="webcomic-display-image" class="webcomic-image-wrapper" >

	<?php echo get_post_meta(get_the_ID(),"the_compassus_webcomic",true);?>
			
	</div>
	<nav id="webcomic-display-nav-below" class="webcomic-nav-wrapper" >
		<ul id="webcomic-nav-up" class="webcomic-nav">
			<li id= "webcomic-nav-leftmost-btn" class="webcomic-nav-button" ><?php  first_webcomic_link("%link","&laquo; <span>Primeira Página</span>", true); ?></li>
			<li id= "webcomic-nav-left-btn" class="webcomic-nav-button" ><?php  previous_webcomic_link("%link","&lsaquo; <span>Página Anterior</span>"); ?></li>

			<?php if (get_post_type() == "webcomic1"):?>
				<li id= "webcomic-nav-middle-btn" class="webcomic-nav-button" ><?php  webcomic_dropdown_storylines( array( "id"=>"webcomic-dropdown", "class"=> "webcomic-dropdown-menu", "collection"=>get_post_type(), "show_option_all"=>"Escolha uma História","webcomics"=>true, "selected" => get_the_ID()) ); ?></li>
			<?php else : ?>
				<li id= "webcomic-nav-middle-btn" class="webcomic-nav-button" ><?php  webcomic_dropdown_storylines( array( "id"=>"webcomic-dropdown", "class"=> "webcomic-dropdown-menu", "collection"=>get_post_type(), "show_option_all"=>"Escolha uma Página") ); ?></li>
			<?php endif; ?>
			
			<li id= "webcomic-nav-right-btn" class="webcomic-nav-button" ><?php  next_webcomic_link("%link","<span>Próxima Página</span> &rsaquo;"); ?></li>
			<li id= "webcomic-nav-rightmost-btn" class="webcomic-nav-button" ><?php  last_webcomic_link("%link","<span>Última Página</span> &raquo;", true); ?></li>
		</ul>
	</nav>
	
</div> <!-- .webcomic-display-wrapper -->
	