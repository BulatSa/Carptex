<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
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
		<h1 class="blog-heading_title-n"><?php echo $heading_title; ?></h1>

	    <div class="seocmspro_content">

		</div>

	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>