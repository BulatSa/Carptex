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
			<meta itemprop="author" content="admin">
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
		</div>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>