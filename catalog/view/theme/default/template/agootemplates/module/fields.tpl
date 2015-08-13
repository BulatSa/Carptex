<?php if (isset($fields) && !empty($fields)) {	?>
<div class="marginbottom5">
<?php if (!$fields_view) { ?>
	<a href="#" class="hrefajax" onclick="$('.addfields').toggle(); return false;"><?php echo $language->get('entry_addfields_begin');  ?><ins class="lowercase"><?php
		$i=0;
		foreach   ($fields as $af_name => $field) {
			$i++;
		if (isset($field['field_description'][$config_language_id])) {
		echo str_replace('?','',$field['field_description'][$config_language_id]);
		if (count($fields)!=$i) echo ", ";
		}
		}
		?></ins></a>

<?php } ?>

</div>
<div class="addfields" style="<?php if (!$fields_view) echo 'display: none;'; ?>">
	<div class="width100">
		<?php
       // print_r('<PRE>');
	   //print_r($fields);
       // print_r('</PRE>');
			foreach ($fields as $af_name =>$field) {
			?>
		<div style="margin-bottom:5px;">
			<?php
				if (isset($field['field'][$config_language_id]['field_class']) && $field['field'][$config_language_id]['field_class']!='') {
					$class = $field['field'][$config_language_id]['field_class'];
				} else {
					$class = '';
				}


				if ($class!='') {
				$field_class = $class;
				} else {
				$field_class = 'blog-record';
				}


				if (isset($field['field_must']) && $field['field_must']=="1") {
				 $field_class.=' borderleft3pxred';
				}


				if (isset($field['field'][$config_language_id]['field_template_in']) && $field['field'][$config_language_id]['field_template_in']!='') {

				 $field_html = $field['field'][$config_language_id]['field_template_in'];
				 $field_text ='';

							if (isset($field['field_type']) && $field['field_type']=='rating') {

								if (isset($settings_widget['visual_rating']) && $settings_widget['visual_rating']) {

						            $field_text = '
                                     <div style="height: 25px;">
									    <input type="hidden" name="af['.$field['field_name'].']" value="">
									    <input type="radio" class="visual_star" name="af['.$field['field_name'].']" alt="#8c0000"  value="1" >
									    <input type="radio" class="visual_star" name="af['.$field['field_name'].']" alt="#8c4500"  value="2" >
									    <input type="radio" class="visual_star" name="af['.$field['field_name'].']" alt="#b6b300"  value="3" >
									    <input type="radio" class="visual_star" name="af['.$field['field_name'].']" alt="#698c00"  value="4" >
									    <input type="radio" class="visual_star" name="af['.$field['field_name'].']" alt="#008c00"  value="5" >
									   <span id="hover-test" ></span>
                                      </div>
									';

							} else {
										$field_text  = '
										<span><ins class="color_bad">'.$language->get('entry_bad').'</ins></span>&nbsp;
										    <input type="hidden" name="af['.$field['field_name'].']" value="">
										    <input type="radio"  name="af['.$field['field_name'].']" value="1">
										    <ins class="blog-ins_rating" style="">1</ins>
										    <input type="radio"  name="af['.$field['field_name'].']" value="2">
										    <ins class="blog-ins_rating" >2</ins>
										    <input type="radio"  name="af['.$field['field_name'].']" value="3">
										    <ins class="blog-ins_rating" >3</ins>
										    <input type="radio"  name="af['.$field['field_name'].']" value="4">
										    <ins class="blog-ins_rating" >4</ins>
										    <input type="radio"  name="af['.$field['field_name'].']" value="5">
										    <ins class="blog-ins_rating" >5</ins>
										   &nbsp;&nbsp; <span><ins  class="color_good">'.$language->get('entry_good').'</ins></span>
										';

								}
							}



				if (isset($field['field'][$config_language_id]['field_class']) && $field['field'][$config_language_id]['field_class']!='') {
				$class = $field['field'][$config_language_id]['field_class'];
				} else {
				$class = '';
				}

				if ($class!='') {
				$field_class = $class;
				} else {
				$field_class = 'blog-record';
				}
				if ($field['field_must']=="1") {
				 $field_class.=' borderleft3pxred';
				}

				if (isset($field['field_type']) && $field['field_type']=='text') {
				$field_text = '<input type="text" name="af['.$field['field_name'].']" class="'.$field_class.'">';
			}

			if ($class!='') {
			$field_class = $class;
			} else {
			$field_class = 'blog-record-textarea';
			}

			if ($field['field_must']=="1") {
			$field_class.=' borderleft3pxred';
			}
			if (isset($field['field_type']) && $field['field_type']=='textarea') {
			$field_text = '<textarea name="af['.$field['field_name'].']" cols="40" rows="1" class="'.$field_class.'"></textarea>';
			}


			if (isset($field['field_type']) && $field['field_type']=='select') {
					if ((isset($field['field_type']) && $field['field_type']=='select') || !isset($field['field_type'])) {
						if (isset($field['field_value'][$config_language_id]) && $field['field_value'][$config_language_id]!='') {

							if ($class!='') {
									$field_class = $class;
							} else {
									$field_class = 'blog-record-select';
							}

							if (isset($field['field_must']) && $field['field_must']=="1") {
									$field_class.=' borderleft3pxred';
							}

							$select_array = explode('|', (string)$field['field_value'][$config_language_id]);
			                $select_text ='';
			                 foreach ($select_array as $num => $select_value) {
				                 $select_value = html_entity_decode($select_value, ENT_QUOTES, 'UTF-8');
				                 $select_text.= '<option>'.$select_value.'</option>';
							 }

                			$field_text = '<select name="af['.$field['field_name'].']" class="'.$field_class.'">'.$select_text.'</select>';

				  		}
				 	}
			}


			$field_html = str_replace('{FIELD}', $field_text, $field_html);

			if ($field['field_image']!='') {
			$field_html = str_replace('{IMAGE}', '<img src="'.$this->getHttpImage().$field['field_image'].'" title="'.$field['field_description'][$config_language_id].'" alt="'.$field['field_description'][$config_language_id].'">', $field_html);
			} else {
			$field_html = str_replace('{IMAGE}', '',$field_html);
			}




			if ($field['field_description'][$config_language_id]!='') {
			$field_html = str_replace('{DESCRIPTION}',$field['field_description'][$config_language_id], $field_html);
			} else {
			$field_html = str_replace('{DESCRIPTION}', '',$field_html);
			}

			if (isset($field['field_must']) && $field['field_must'])  {
			$field_html = str_replace('{REQUIRE}', '<span class="blog_require '.$class.'">*</span>', $field_html);
			} else {
			$field_html = str_replace('{REQUIRE}', '',$field_html);
			}



			?>
			<?php echo html_entity_decode($field_html, ENT_QUOTES, 'UTF-8') ; ?>

			<?php } else { 	?>

			<?php
				if (isset($field['field_image']) && $field['field_image']!='') {
			?>
			<div style="float:left;" class="marginright5">
				<img src="<?php echo $http_image.$field['field_image']; ?>" title="<?php echo $field['field_description'][$config_language_id]; ?>" alt="<?php echo $field['field_description'][$config_language_id]; ?>">
			</div>
			<?php  }  ?>
			<div>
				<?php
					if (!isset($field['field_public'])) $field['field_public'] = true;

					if(!$field['field_public']) {
					$field['field_description'][$config_language_id].=$language->get('text_unpublic');
					}
					?>
				<b><ins class="color_entry_name"><?php echo $field['field_description'][$config_language_id]; ?></ins></b>
				<?php
					if (isset($field['field_must']) && $field['field_must']) {
					?>
				<span class="blog_require">*</span>
				<?php
					}
					?>
				<br>

				<?php
					if ((isset($field['field_type']) && $field['field_type']=='textarea') || !isset($field['field_type'])) {
						if ($class!='') {
								$field_class = $class;
							} else {
								$field_class = 'blog-record-textarea';
							}
							if (isset($field['field_must']) && $field['field_must']=="1") {
								$field_class.=' borderleft3pxred';
							}
				?>
				<textarea name="af[<?php echo $field['field_name']; ?>]" cols="40" rows="1" class="<?php echo $field_class; ?>"></textarea>
				<?php } ?>

				<?php
					if ((isset($field['field_type']) && $field['field_type']=='select') || !isset($field['field_type'])) {
						if (isset($field['field_value'][$config_language_id]) && $field['field_value'][$config_language_id]!='') {

						if ($class!='') {
								$field_class = $class;
							} else {
								$field_class = 'blog-record-select';
							}
							if (isset($field['field_must']) && $field['field_must']=="1") {
								$field_class.=' borderleft3pxred';
							}

							$select_array = explode('|', (string)$field['field_value'][$config_language_id]);
 				?>
				<select name="af[<?php echo $field['field_name']; ?>]" class="<?php echo $field_class; ?>">
                <?php foreach ($select_array as $num => $select_value) {
                 $select_value = html_entity_decode($select_value, ENT_QUOTES, 'UTF-8');
                ?>
                  <option><?php echo $select_value; ?></option>
				<?php } ?>
				</select>

				<?php } } ?>

				<?php
					if (isset($field['field_type']) && $field['field_type']=='text') {
						if ($class!='') {
							$field_class = $class;
							} else {
							$field_class = 'blog-record';
							}
					if ($field['field_must']=="1") {
					$field_class.=' borderleft3pxred';
					}
					?>
				<input type="text" name="af[<?php echo $field['field_name']; ?>]" class="<?php echo $field_class; ?>">
				<?php
					}
					?>
				<?php
					if (isset($field['field_type']) && $field['field_type']=='rating') {
					?>
				<?php if (isset($settings_widget['visual_rating']) && $settings_widget['visual_rating']) { ?>
				<div style="height: 25px;">
					<input type="hidden" name="af[<?php echo $field['field_name']; ?>]" value="">
					<input type="radio" class="visual_star" name="af[<?php echo $field['field_name']; ?>]" alt="#8c0000" title="<?php echo $language->get('entry_bad'); ?> 1" value="1" >
					<input type="radio" class="visual_star" name="af[<?php echo $field['field_name']; ?>]" alt="#8c4500" title="<?php echo $language->get('entry_bad'); ?> 2" value="2" >
					<input type="radio" class="visual_star" name="af[<?php echo $field['field_name']; ?>]" alt="#b6b300" title="<?php echo $language->get('entry_bad'); ?> 3" value="3" >
					<input type="radio" class="visual_star" name="af[<?php echo $field['field_name']; ?>]" alt="#698c00" title="<?php echo $language->get('entry_good'); ?> 4" value="4" >
					<input type="radio" class="visual_star" name="af[<?php echo $field['field_name']; ?>]" alt="#008c00" title="<?php echo $language->get('entry_good'); ?> 5" value="5" >
					<div class="floatleft"  style="padding-top: 5px; "><b><ins class="color_entry_name marginleft10"><span id="hover-test" ></span></ins></b></div>
					<div  class="bordernone overflowhidden width100  clearboth lineheight1"></div>
				</div>
				<?php } else { ?>
				<span><ins class="color_bad"><?php echo $language->get('entry_bad'); ?></ins></span>&nbsp;
				<input type="hidden" name="af[<?php echo $field['field_name']; ?>]" value="">
				<input type="radio"  name="af[<?php echo $field['field_name']; ?>]" value="1">
				<ins class="blog-ins_rating" style="">1</ins>
				<input type="radio"  name="af[<?php echo $field['field_name']; ?>]" value="2">
				<ins class="blog-ins_rating" >2</ins>
				<input type="radio"  name="af[<?php echo $field['field_name']; ?>]" value="3">
				<ins class="blog-ins_rating" >3</ins>
				<input type="radio"  name="af[<?php echo $field['field_name']; ?>]" value="4">
				<ins class="blog-ins_rating" >4</ins>
				<input type="radio"  name="af[<?php echo $field['field_name']; ?>]" value="5">
				<ins class="blog-ins_rating" >5</ins>
				&nbsp;&nbsp; <span><ins  class="color_good"><?php echo $language->get('entry_good'); ?></ins></span>
				<?php
					}
					}
					?>
			</div>
			<?php } ?>
		</div>
		<?php
			}
			?>
	</div>
</div>
<?php  } ?>