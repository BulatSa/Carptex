<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="seocmspro_content">
		<div class="record-info record_content">
			<div class="blog-small-record">
				<ul>
					<?php if (isset ($settings_blog['view_date']) && $settings_blog['view_date'] ) { ?>
					<li class="blog-data-record"> <?php echo $date_added; ?></li>
					<?php } ?>
					<?php if (isset ($settings_blog['view_comments']) && $settings_blog['view_comments'] && $comment_status) { ?>
					<li class="blog-comments-record">
						<?php echo $tab_comment; ?>:
						<ins style="text-decoration:none;" class="comment_count">
						<?php echo $comment_count; ?>
						</ins>
					</li>
					<?php } ?>
					<?php if (isset ($settings_blog['view_viewed']) && $settings_blog['view_viewed'] ) { ?>
					<li class="blog-viewed-record"><?php echo $text_viewed; ?> <?php echo $viewed; ?></li>
					<?php } ?>

					<?php if (isset ($settings_blog['author_record_status']) && $settings_blog['author_record_status'] &&  $author!='') { ?>
						<li class="blog-author-record"><?php echo $text_author;?><?php echo $author; ?>
						</li>
					<?php } ?>

					<?php if (isset ($settings_blog['view_rating']) && $settings_blog['view_rating'] && $comment_status) { ?>
                    <li class="floatright" style="float: right; ">
					<?php if ($theme_stars) { ?>
					<img style="border: 0px;"  title="<?php echo $rating; ?>" alt="<?php echo $rating; ?>" src="catalog/view/theme/<?php echo $theme_stars; ?>/image/blogstars-<?php echo $rating; ?>.png">
					<?php } ?>

						<!-- RDFa. -->
						<?php if  ($comment_count>0) { ?>
						<span xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate">
						<span property="v:itemreviewed" style="display:none;"><?php echo $heading_title; ?></span>
						<span rel="v:rating">
						<span typeof="v:Rating">
						<span property="v:average" content="<?php echo $rating; ?>"></span>
						<span property="v:best" content="5"></span>
						</span>
						</span>
						<span property="v:votes" content="<?php echo $comment_count; ?>"></span>
						<span property="v:count" content="<?php echo $comment_count; ?>"></span>
						</span>
						<?php } ?>
					</li>
					<?php } ?>

				</ul>
			</div>
			<div class="divider100 borderbottom2"></div>

			<?php if (isset ($settings_blog['thumb_view']) && $settings_blog['thumb_view'] ) { ?>
			<?php if ($thumb) { ?>
			<div class="image blog-image">
				<a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="imagebox" rel="imagebox">
				<img src="<?php echo $thumb; ?>"  title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>>" >
				</a>
			</div>
			<?php } ?>
			<?php } ?>

			<div class="blog-record-description">
				<?php echo $description; ?>
			</div>
			<div class="divider100"></div>
			<div class="blog-next-prev">
				<?php if($record_previous['name']!='') {?>
				<a href="<?php echo $record_previous['url']; ?>"><?php echo $language->get('separator_previus'); ?>&nbsp;<?php echo $record_previous['name']; ?></a>
				<?php } ?>
				<?php if($record_previous['name']!='' || $record_next['name']!='') {?>
				&nbsp;<?php echo $language->get('separator_center'); ?>&nbsp;
				<?php } ?>
				<?php if($record_next['name']!='') {?>
				<a href="<?php echo $record_next['url']; ?>"><?php echo $record_next['name']; ?>&nbsp;<?php echo $language->get('separator_next'); ?></a>
				<?php } ?>
			</div>
			<div>
				<div class="description">
					<?php if ($comment_status) {
						$h=end($breadcrumbs);
						$href=$h['href'];
						?>
					<div class="comment">
						<div>
						</div>
						<div class="divider100"></div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>

	<div class="divider100"></div>
           <ul class="nav nav-tabs <?php if (SCP_VERSION < 2) { ?>htabs<?php } ?>" id="<?php if (SCP_VERSION < 2) { ?>tabs<?php } else { ?>scp-tabs<?php } ?>">

			<?php if (isset ($settings_blog['thumb_view']) && $settings_blog['thumb_view'] ) { ?>
				<?php if ($images) { ?>
				<li class="active"><a href="#tab-images"  data-toggle="tab"><?php echo $tab_images; ?></a></li>
				<?php } ?>
			<?php } ?>


			<?php if (!isset ($settings_blog['thumb_view']) || !$settings_blog['thumb_view'] ) { ?>
				<?php if ($images) { ?>
				<li><a href="#tab-images"  data-toggle="tab"><?php echo $tab_images; ?></a></li>
				<?php } ?>
			<?php } ?>


			<?php if ($attribute_groups) { ?>
			<li><a href="#tab-attribute"  data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
			<?php } ?>


			<?php if ($records) { ?>
			<li><a href="#tab-related"  data-toggle="tab"><?php echo $tab_related; ?> (<?php echo count($records); ?>)</a></li>
			<?php } ?>


			<?php if ($products) { ?>
			<li><a href="#tab-product-related"  data-toggle="tab"><?php echo $tab_product_related; ?> (<?php echo count($products); ?>)</a></li>
			<?php } ?>
		  </ul>
		<div class="tab-content">


        <?php if ($records) { ?>

		<?php if (SCP_VERSION > 1) { ?>

        <?php $i = 0; ?>
        <?php foreach ($records as $record) { ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12'; ?>
        <?php } else { ?>
        <?php $class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12'; ?>
        <?php } ?>
        <?php } ?>

		<?php } else { ?>
		<?php $class = ''; ?>
		<?php } ?>
		<div id="tab-related" class="tab-pane tab-content">
			<div class="box-product ascp_row">
				<?php foreach ($records as $record) { ?>
				<div class="<?php echo $class; ?>" style="width: <?php echo $settings_blog['product_image']['width']; ?>px; min-width: 200px;">
				<div  class="record-thumb transition">
					<?php if ($record['thumb']) { ?>
					<div class="image">
						<a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" alt="<?php echo $record['name']; ?>" class="img-responsive"/></a>
					</div>
					<?php } ?>
					<div class="caption">
						<div class="name"><h4><a href="<?php echo $record['href']; ?>"><?php echo $record['name']; ?></a></h></div>
                        <p><?php echo $record['description']; ?></p>
						<?php if ($record['rating']) { ?>
						<div class="rating">
						<?php if (SCP_VERSION > 1) { ?>
						<?php for ($i = 1; $i <= 5; $i++) { ?>
		                <?php if ($record['rating'] < $i) { ?>
		                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
		                <?php } else { ?>
		                <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
		                <?php } ?>
		                <?php } ?>
                         <?php } else { ?>
						<img src="catalog/view/theme/<?php echo $theme; ?>/image/stars-<?php echo $record['rating']; ?>.png" alt="<?php echo $record['rating']; ?>" />
						 <?php } ?>
						</div>
						<?php } ?>
					</div>

				</div>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>


		<?php if ($images) { ?>
		<div id="tab-images" class="tab-pane">
			<div class="left">
				<?php if ($images) { ?>
				<div class="<?php if (SCP_VERSION < 2) { ?>image-additional<?php }  ?>">
					<?php foreach ($images as $image) { ?>
					<div style="float: left; margin: 5px;">
						<?php if (isset($image['title']) && $image['title']!='') { ?>
						<div class="left">
							<?php if (isset($image['url']) && $image['url']!='') { ?>
							<a href="<?php echo $image['url']; ?>">
							<?php }  ?>
							<?php echo $image['title']; ?>
							<?php if (isset($image['url']) && $image['url']!='') { ?>
							</a>
							<?php }  ?>
						</div>
						<?php } ?>
						<a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="imagebox" rel="imagebox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
						<?php if (isset($image['description']) && $image['description']!='') { ?>
						<div>
							<?php echo $image['description']; ?>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<?php if ($attribute_groups) { ?>
		<div id="tab-attribute" class="tab-pane">
			<table class="attribute">
				<?php foreach ($attribute_groups as $attribute_group) { ?>
				<thead>
					<tr>
						<td colspan="2"><?php echo $attribute_group['name']; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
					<tr>
						<td><?php echo $attribute['name']; ?></td>
						<td><?php echo $attribute['text']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
				<?php } ?>
			</table>
		</div>
		<?php } ?>
        <?php if ($products) { ?>

		<?php if (SCP_VERSION > 1) { ?>

        <?php $i = 0; ?>
        <?php foreach ($products as $product) { ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12'; ?>
        <?php } else { ?>
        <?php $class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12'; ?>
        <?php } ?>
        <?php } ?>

		<?php } else { ?>
		<?php $class = ''; ?>
		<?php } ?>
		<div id="tab-product-related" class="tab-pane tab-content">
			<div class="box-product ascp_row">
				<?php foreach ($products as $product) { ?>
				<div class="<?php echo $class; ?>" style="width: <?php echo $settings_blog['product_image']['width']; ?>px; min-width: 280px;">
				<div  class="product-thumb transition">
					<?php if ($product['thumb']) { ?>
					<div class="image">
						<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive"/></a>
					</div>
					<?php } ?>
					<div class="caption">
						<div class="name"><h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h></div>
						<p><?php echo $product['description']; ?></p>

						<?php if ($product['price']) { ?>
						<div class="price">
							<?php if (!$product['special']) { ?>
							<?php echo $product['price']; ?>
							<?php } else { ?>
							<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
							<?php } ?>
						</div>
						<?php } ?>
						<?php if ($product['rating']) { ?>
						<div class="rating">
						<?php if (SCP_VERSION > 1) { ?>
						<?php for ($i = 1; $i <= 5; $i++) { ?>
		                <?php if ($product['rating'] < $i) { ?>
		                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
		                <?php } else { ?>
		                <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
		                <?php } ?>
		                <?php } ?>
                         <?php } else { ?>

						<img src="catalog/view/theme/<?php echo $theme; ?>/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['rating']; ?>" />
						 <?php } ?>
						</div>
						<?php } ?>
					</div>

					<?php if (SCP_VERSION < 2) { ?>
					<a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><span><?php echo $button_cart; ?></span></a>
					<?php } else { ?>
		            <div class="button-group">
		              <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span> <i class="fa fa-shopping-cart"></i></button>
		              <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
		              <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
		            </div>
					<?php } ?>
				</div>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>


		</div>
		<div class="overflowhidden width100 lineheight1 bordernone clearboth">&nbsp;</div>

		<?php if (isset ($settings_blog['view_share']) && $settings_blog['view_share'] ) { ?>
		<div class="share floatleft">
			<?php echo $box_share; ?>
		</div>
		<div class="powered_blog_icon" style="padding: 0px; margin: 0px; line-height: 10px;">
			<h9 class="blog-icon  floatleft" style="margin: 0; padding: 0;">Powered by SEO CMS PRO ver.: <?php echo $blog_version; ?> (opencartadmin.com)</h9>
		</div>
		<div class="overflowhidden lineheight1 bordernone clearboth">&nbsp;</div>
		<?php } ?>

		<?php if ($tags) {
			?>
		<div class="tags"><b><?php echo $text_tags; ?></b>
			<?php for ($i = 0; $i < count($tags); $i++) { ?>
			<?php if ($i < (count($tags) - 1)) { ?>
			<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
			<?php } else { ?>
			<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
			<?php } ?>
			<?php } ?>
		</div>
		<?php } ?>

		<script type="text/javascript">
		<?php if (SCP_VERSION < 2) {  ?>
		$('#tabs a').each(function() {
		   var obj = $(this);
		   $(obj.attr('href')).hide();
		   $(obj).unbind( "click" );
		});
		$('#tabs a').tabs();
		<?php } else { ?>
		$(document).ready(function() {
			$('.nav-tabs li:nth-child(1)').removeClass('active');
			$('.nav-tabs li:nth-child(1) a').click();
			$('.share').show();
        });
        <?php } ?>

		</script>


	</div>
  
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>