<div class="width100">
<?php
		foreach ($comment['fields'] as $af_name =>$field) {
		if (!isset($field['field_public'])) $field['field_public'] = true;
					if($field['value']!="" && $field['field_public']) {
		?>
	<div style="margin-bottom:5px;">
		<?php
			if (isset($field['field']) && $field['field'][$config_language_id]['field_template_out']!='') {

			$field_html = $field['field'][$config_language_id]['field_template_out'];

			if (isset($field['field_type']) && $field['field_type']=='rating') {
			    if ($field['value']!= "0") {
					if ($theme_stars) {
					$field_html = str_replace('{FIELD}', '<img style="border: 0px;"  title="'.$field['value'].'" alt="'.$field['value'].'" src="catalog/view/theme/'.$theme_stars.'/image/blogstars-'.$field['value'].'.png">', $field_html);
					}
				}
			} else {
				$field_html = str_replace('{FIELD}', $field['value'], $field_html);
			}

			if (isset($field['field_image']) &&  $field['field_image']!='') {
			 $field_html = str_replace('{IMAGE}', '<img src="'.$this->getHttpImage().$field['field_image'].'" title="'.$field['field_description'][$config_language_id].'" alt="'.$field['field_description'][$config_language_id].'">', $field_html);
			} else {
			 $field_html = str_replace('{IMAGE}', '',$field_html);
			}

			if (isset($field['field_description']) &&  $field['field_description'][$config_language_id]!='') {
			 $field_html = str_replace('{DESCRIPTION}',$field['field_description'][$config_language_id], $field_html);
			} else {
			 $field_html = str_replace('{DESCRIPTION}', '',$field_html);
			}
			?>
		<?php echo html_entity_decode($field_html, ENT_QUOTES, 'UTF-8') ; ?>
		<?php
			} else {
			?>
		<div style="float:left;" class="marginright5">
			<?php if ($field['value']!= "0") {
					if (isset($field['field_image']) && $field['field_image']!='') {
			 ?>
			<img src="<?php echo $http_image.$field['field_image']; ?>" title="<?php echo $field['field_description'][$config_language_id]; ?>" alt="<?php echo $field['field_description'][$config_language_id]; ?>">
			<?php  } } ?>
		</div>
		<div>
			<?php
				if (isset($field['field_type']) && $field['field_type']=='rating') {
				    if ($field['value']!= "0") {
					if ($theme_stars) {
					?>
			<ins class="field_title"><?php echo $field['field_description'][$config_language_id]; ?>:&nbsp;</ins>
			<img style="border: 0px;"  title="<?php echo $field['value']; ?>" alt="<?php echo $field['value']; ?>" src="catalog/view/theme/<?php echo $theme_stars; ?>/image/blogstars-<?php echo $field['value']; ?>.png">
			<?php } }
				} else {
				?>
			<?php if (isset($field['field_description'][$config_language_id])) { ?>
			<ins class="field_title"><?php echo $field['field_description'][$config_language_id]; ?>:&nbsp;</ins><ins class="field_text"><?php echo $field['value']; ?></ins>
			<?php } ?>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php
		}
	  }
?>
</div>