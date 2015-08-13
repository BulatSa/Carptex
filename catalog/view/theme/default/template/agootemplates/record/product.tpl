<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
		<span itemscope itemtype="http://schema.org/Article">
			<?php if  ($comment_count>0) { ?>
			<span itemtype="http://schema.org/AggregateRating" itemprop="aggregateRating" itemscope="">
				<meta content="<?php echo $comment_count; ?>" itemprop="reviewCount">
				<meta content="<?php echo $rating; ?>" itemprop="ratingValue">
				<meta content="5" itemprop="bestRating">
				<meta content="1" itemprop="worstRating">
			</span>
			<?php } ?>
			<meta itemprop="description" content="<?php echo htmlspecialchars(trim(utf8_substr(strip_tags(html_entity_decode($description, ENT_QUOTES, 'UTF-8')), 0, 300)),ENT_QUOTES, 'UTF-8'); ?>">
			<?php if ($thumb) { ?>
			<meta itemprop="image" content="<?php echo $thumb; ?>">
			<?php } ?>
			<meta itemprop="name" content="<?php echo $heading_title; ?>">
			<meta itemprop="dateModified" content="<?php echo $date; ?>">
			<?php if (isset ($settings_blog['author_record_status']) && $settings_blog['author_record_status'] &&  $author!='') { ?>
			<meta itemprop="author" content="<?php echo $author; ?>">
			<?php } else { ?>
			<meta itemprop="author" content="admin">
			<?php } ?>
			<meta itemprop="dateCreated" content="<?php echo $date; ?>">
			<?php if  ($comment_count>0) { ?>
			<meta itemprop="interactionCount" content="UserComments:<?php echo $comment_count; ?>">
			<?php } ?>
		</span>
		<div class="breadcrumb">
			<span xmlns:v="http://rdf.data-vocabulary.org/#">
			<?php $i=0; foreach ($breadcrumbs as $breadcrumb) { $i++; ?>
			<span typeof="v:Breadcrumb">
			<?php echo $breadcrumb['separator']; ?><?php if (count($breadcrumbs)!= $i) { ?><a href="<?php echo $breadcrumb['href']; ?>" rel="v:url" property="v:title"><?php } else { ?><a rel="v:url" property="v:title"><?php } ?><?php echo $breadcrumb['text']; ?></a>
			</span>
			<?php } ?>
			</span>
		</div>
		<?php echo $content_top; ?>
		<h1 class="marginbottom5"><?php echo $heading_title; ?></h1>
		<div class="seocmspro_content">
		<div class="record-info record_content">
			<div class="blog-small-record">
				<ul>
					<?php if (isset ($settings_blog['view_date']) && $settings_blog['view_date'] ) { ?>
					<li class="blog-data-record"> <?php echo $date_added; ?></li>
					<?php } ?>
					<?php if (isset ($settings_blog['view_comments']) && $settings_blog['view_comments'] ) { ?>
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

					<li class="floatright" style="float: right; ">
						<?php if (isset ($settings_blog['view_rating']) && $settings_blog['view_rating'] ) { ?>

					<?php if ($theme_stars) { ?>
					<img style="border: 0px;"  title="<?php echo $rating; ?>" alt="<?php echo $rating; ?>" src="catalog/view/theme/<?php echo $theme_stars; ?>/image/blogstars-<?php echo $rating; ?>.png">
					<?php } ?>

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
				</ul>
			</div>
			<div class="divider100 borderbottom2"></div>

			<?php if ($products) { ?>
			<div id="tab-product-related" class="content">
				<div class="product-info">
					<?php foreach ($products as $product) { ?>
					<div class="divider100 borderbottom2"></div>
					<div>
						<?php if ($product['thumb']) { ?>
						<div class="left">
							<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
						</div>
						<?php } ?>
						<div class="right">
							<div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
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
							<img src="catalog/view/theme/<?php echo $theme; ?>/image/blogstars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" />
							</div>
							<?php } ?>
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
			<div class="divider100"></div>
			<?php } ?>


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

           <ul <?php if ($SCP_VERSION < 2) echo 'id="tabs"'; ?>  class="nav nav-tabs <?php if ($SCP_VERSION < 2) echo 'htabs'; ?>">

			<?php if (isset ($settings_blog['thumb_view']) && $settings_blog['thumb_view'] ) { ?>
				<?php if ($images) { ?>
				<li class="active"><a href="#tab-images"  data-toggle="tab"><?php echo $tab_images; ?></a></li>
				<?php } ?>
			<?php } ?>

			<?php  if ($comment_status) { ?>
			<li><a href="#tab-review"  data-toggle="tab"><?php echo $tab_comment; ?><ins style="text-decoration: none;" class="comment_count">(<?php echo $comment_count; ?>)</ins></a></li>
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



		  </ul>
		<div class="tab-content">
		<?php

		if ($comment_status) { ?>
		<div id="tab-review" class=" tab-pane">
		</div>
		<?php } ?>

		<?php if ($records) { ?>
		<div id="tab-related" class=" tab-pane">
			<div class="box-product">
				<?php foreach ($records as $record) { ?>
				<div>
					<?php if ($record['thumb']) { ?>
					<div class="image"><a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" alt="<?php echo $record['name']; ?>" /></a></div>
					<?php } ?>
					<div class="name"><a href="<?php echo $record['href']; ?>"><?php echo $record['name']; ?></a></div>
					<?php if ($record['rating']) { ?>
					<div class="rating"><img src="catalog/view/theme/<?php echo $theme; ?>/image/blogstars-<?php echo $record['rating']; ?>.png" alt="<?php echo $record['comments']; ?>" /></div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<?php if ($images) { ?>
		<div id="tab-images" class=" tab-pane">
			<div class="left">
				<?php if ($images) { ?>
				<div class="<?php if ($SCP_VERSION < 2) echo 'image-additional'; ?>">
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
		<div id="tab-attribute" class=" tab-pane">
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
		<?php if (!$ajax) { ?>
		<script type="text/javascript">

		<?php if ($SCP_VERSION < 2) {  ?>
		$('#tabs a').each(function() {
		   var obj = $(this);
		   $(obj.attr('href')).hide();
		   $(obj).unbind( "click" );
		});
		$('#tabs a').tabs();
		<?php } else { ?>
		$(window).load(function() {
			$('.nav-tabs li:nth-child(1)').removeClass('active');
			$('.nav-tabs li:nth-child(1) a').click();
        });
        <?php } ?>

		</script>
		<?php } ?>

	</div>
	<?php echo $content_bottom; ?>



</div>
<?php echo $footer; ?>