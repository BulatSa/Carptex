	<div class="group_button_top" id="agoo_second_menu">

	<span id="agoo_first_menu_block">
		<a href="<?php echo $agoo_menu_url_options; ?>" class="markbutton<?php echo $agoo_menu_active_options; ?>"><div><img src="view/image/agootemplates-options-m.png"></div>
		<div><?php echo $agoo_menu_options; ?></div></a>

		<a href="<?php echo $agoo_menu_url_layouts; ?>" class="markbutton<?php echo $agoo_menu_active_layouts; ?>"><div><img src="view/image/agootemplates-schemes-m.png"></div>
		<div><?php echo $agoo_menu_layouts; ?></div></a>


		<a href="<?php echo $agoo_menu_url_widgets; ?>" class="markbutton<?php echo $agoo_menu_active_widgets; ?>"><div><img src="view/image/agootemplates-widgets-m.png"></div>
		<div style=" "><?php echo $agoo_menu_widgets; ?></div></a>

    </span>

    <span id="agoo_second_menu_block">
		<a href="<?php echo $agoo_menu_url_categories; ?>" class="markbutton<?php echo $agoo_menu_active_categories; ?>"><div><img src="view/image/blog-icon-m.png" ></div>
		<div style=" "><?php echo $agoo_menu_categories; ?></div></a>

		<a href="<?php echo $agoo_menu_url_records; ?>" class="markbutton<?php echo $agoo_menu_active_records; ?>"><div><img src="view/image/blog-rec-m.png"  ></div>
		<div><?php echo $agoo_menu_records; ?></div></a>

		<a href="<?php echo $agoo_menu_url_comments; ?>" class="markbutton<?php echo $agoo_menu_active_comments; ?>"><div><img src="view/image/blog-com-m.png"  ></div>
		<div><?php echo $agoo_menu_comments; ?></div></a>

		<a href="<?php echo $agoo_menu_url_reviews; ?>" class="markbutton<?php echo $agoo_menu_active_reviews; ?>"><div><img src="view/image/blog-com-m.png"  ></div>
		<div><?php echo $agoo_menu_reviews; ?></div></a>
	</span>


    <span id="agoo_third_menu_block" style="display: none;">
		<a href="<?php echo $agoo_menu_url_adapter; ?>" class="markbutton<?php echo $agoo_menu_active_adapter; ?>"><div><img src="view/image/blog-icon-m.png" ></div>
		<div style=" "><?php echo $agoo_menu_adapter; ?></div></a>
		<a href="<?php echo $agoo_menu_url_fields; ?>" class="markbutton<?php echo $agoo_menu_active_fields; ?>"><div><img src="view/image/blog-rec-m.png"  ></div>
		<div><?php echo $agoo_menu_fields; ?></div></a>

		<a href="<?php echo $agoo_menu_url_sitemap; ?>" class="markbutton<?php echo $agoo_menu_active_sitemap; ?>"><div><img src="view/image/blog-com-m.png" ></div>
		<div style=" "><?php echo $agoo_menu_sitemap; ?></div></a>

	</span>


		<a href="<?php echo $agoo_menu_url_modules; ?>" class="markbutton"><div><img src="view/image/blog-back-m.png"></div>
		<div><?php echo $agoo_menu_modules; ?></div></a>

	</div>


	<div style="margin-left:0px; float:left;" id="agoo_first_menu">

		<a onclick="$('.group_button_top').toggle('slow', function() {
		        if ($('.updown').hasClass('button_down')) {
		            $('.updown').removeClass('button_down').addClass('button_up');
		            $('.up_down').removeClass('markactive');
		        } else {
		            $('.updown').removeClass('button_up').addClass('button_down');
		            $('.up_down').addClass('markactive');
		        }
		    });" class="up_down markbutton"><div class="updown button_up"></div>
		</a>


	</div>

<script>
	$('#agoo_first_menu').append( $('#<?php echo $agoo_menu_block;?>').show() );
</script>