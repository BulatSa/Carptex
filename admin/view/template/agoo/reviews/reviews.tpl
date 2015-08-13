<?php
foreach ($ascp_widgets as $list_num=>$list) { ?>
<div id="list<?php echo $list_num;?>" style="padding-left: 200px;">
<form class="ascp_widgets_form">
<input type="hidden" name="ascp_widgets[<?php echo $list_num; ?>][remove]" class="hremove" value="">

  <input type="hidden" name="ascp_widgets[<?php echo $list_num; ?>][type]" value="<?php if (isset($list['type'])) echo $list['type']; else echo 'blogs'; ?>">


<table>
    <tr>

    <td colspan="2">
	 <div class="buttons">

 	 <a onclick="
 	 //ascp_widgets_num--;
 	 $('input[name=\'ascp_widgets[<?php echo $list_num; ?>][remove]\']').val('remove');
	 $('.blog_save_new').html('<?php echo $button_save; ?> ('+$('.hremove[value!=\'remove\']').length+')' );
 	 //$('#mytabs<?php echo $list_num;?>').hide();
 	 //$('#mytabs a').tabs();
 	 $('#amytabs<?php echo $list_num;?>').addClass('remove');
 	 $('#amytabs<?php echo $list_num;?>').hide();
 	 $('#mytabs<?php echo $list_num;?>').hide();
 	 return false; " class="mbutton button_purple"><?php echo $language->get('button_remove'); ?></a>


<a onclick="
      ascp_widgets_num++;
      type_what = '<?php echo $list['type']; ?>';
 		$.ajax({
					url: 'index.php?route=module/blog/ajax_list&token=<?php echo $token; ?>',
					type: 'post',
					data: { type: type_what, list: '<?php echo base64_encode($slist); ?>', num: ascp_widgets_num },
					dataType: 'html',
					beforeSend: function()
					{

					},
					success: function(html) {
						if (html) {
							$('#mytabs').append('<a href=\'#mytabs' + ascp_widgets_num + '\' id=\'amytabs'+ascp_widgets_num+'\'>List-' + ascp_widgets_num + '<\/a>');
							$('#lists').append('<div id=\'mytabs'+ascp_widgets_num+'\'>'+html+'<\/div>');
							$('#mytabs a').tabs();
							$('#amytabs' + ascp_widgets_num).click();
							template_auto();
						}
						$('.mbutton').removeClass('loader');


					}
				});


      return false; " class="mbutton"><?php echo $language->get('button_clone_widget'); ?>: <?php echo $language->get('text_widget_'.$list['type']); ?></a>
     <a onclick="$('.help').toggle(); return false; " class="mbutton button_blue"><?php echo $language->get('button_help'); ?></a>
	 </div>

    </td>
    </tr>	 <?php foreach ($languages as $lang) { ?>
	<tr>
			<td>
			<?php echo $language->get('entry_title_list_latest'); ?> (<?php echo  ($lang['name']); ?>)

		</td>

			<td>

				<input type="text" name="ascp_widgets[<?php echo $list_num; ?>][title_list_latest][<?php echo $lang['language_id']; ?>]" value="<?php if (isset($list['title_list_latest'][$lang['language_id']])) echo $list['title_list_latest'][$lang['language_id']]; ?>" size="60" /><img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" />
			</td>

	</tr>
   <?php } ?>
    <tr>
    <td class="left"><?php echo $language->get('entry_id_widget'); ?></td>
     <td class="left">
 		<?php echo $id;?>
     </td>
    </tr>
    <tr>
     <td class="left"><?php echo $language->get('entry_image_dimension'); ?></td>
     <td class="left">
      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][image][width]" value="<?php  if (isset($list['image']['width'])) echo $list['image']['width']; ?>" size="3" />x
      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][image][height]" value="<?php if (isset($list['image']['height'])) echo $list['image']['height']; ?>" size="3" />
     </td>
    </tr>

          <tr>
              <td><?php echo $language->get('entry_image_status'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][image_status]">
                  <?php if (isset($list['image_status']) && $list['image_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

               <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_image_adaptive_status'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][image_adaptive_status]">
                  <?php if (isset($list['image_adaptive_status']) && $list['image_adaptive_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['image_adaptive_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['image_adaptive_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

	<tr>
			<td>
			<?php echo $language->get('entry_template'); ?>

		</td>

			<td>
				<input type="text" class="template" name="ascp_widgets[<?php echo $list_num; ?>][template]" value="<?php if (isset($list['template'])) echo $list['template']; ?>" size="60" />
				<input type="hidden" name="tpath" value="widgets/reviews">
			</td>

	</tr>

    <tr>
     <td class="left"><?php echo $language->get('entry_block_reviews_width'); ?></td>
     <td class="left">
      <input type="text" size="6" name="ascp_widgets[<?php echo $list_num; ?>][block_reviews_width]" id="ascp_widgets_block_reviews_width_<?php echo $list_num; ?>" value="<?php  if (isset($list['block_reviews_width'])) echo $list['block_reviews_width']; ?>" size="50" />

                 <div>
                 <?php echo $language->get('entry_block_reviews_width_templates'); ?>
                 </div>

	               <div>
					<select id="select_block_reviews_width_<?php echo $list_num; ?>">

	                 <?php  if (!isset($list['block_reviews_width'])) {$list['block_reviews_width'] = ''; } ?>
	                 <option value="<?php echo $list['block_reviews_width']; ?>"><?php echo $language->get('entry_current_value'); ?></option>

                      <?php foreach ($block_reviews_width_templates as $block_reviews_width_name => $block_reviews_width_value) { ?>
	                   <option value="<?php echo $block_reviews_width_value; ?>"><?php echo $block_reviews_width_name; ?></option>
                       <?php } ?>
	                 </select>
	                 </div>
						<script>
						$( '#select_block_reviews_width_<?php echo $list_num; ?>' )
						.change(function () {
						var str = '';
						$( '#select_block_reviews_width_<?php echo $list_num; ?> option:selected' ).each(function() {
						str = $(this).val();
						});

						$( '#ascp_widgets_block_reviews_width_<?php echo $list_num; ?>' ).val( str );

						})
						.change();
						</script>



     </td>
    </tr>




		    <tr>
		     <td class="left"><?php echo $language->get('entry_anchor'); ?></td>
		     <td class="left">
		      <textarea style="width: 96%;" id="ascp_widgets_<?php echo $list_num; ?>_anchor" name="ascp_widgets[<?php echo $list_num; ?>][anchor]"><?php  if (isset($list['anchor'])) echo $list['anchor']; ?></textarea>

				 <?php if (isset($list['anchor_templates']) && is_array($list['anchor_templates']) && !empty($list['anchor_templates'])) { ?>
                 <div>
                 <?php echo $language->get('entry_anchor_templates'); ?>
                 </div>

	               <div>
					<select name="ascp_widgets[<?php echo $list_num; ?>][anchor_templates]" id="ascp_widgets_<?php echo $list_num; ?>_anchor_templates">

	                 <?php  if (!isset($list['anchor'])) { $list['anchor'] = ''; } ?>
	                 <option value="<?php echo $list['anchor']; ?>"><?php echo $language->get('entry_anchor_value'); ?></option>

	                 <?php foreach ($list['anchor_templates'] as $anchor_name => $anchor_template) { ?>
	                   <option value="<?php echo $anchor_template; ?>"><?php echo $anchor_name; ?></option>
	                  <?php } ?>

	                 </select>
	                 </div>
						<script>
						$( '#ascp_widgets_<?php echo $list_num; ?>_anchor_templates' )
						.change(function () {
						var str = '';
						$( '#ascp_widgets_<?php echo $list_num; ?>_anchor_templates option:selected' ).each(function() {
						str = $(this).val();
						});

						$( '#ascp_widgets_<?php echo $list_num; ?>_anchor' ).html( str );

						})
						.change();
						</script>

                 <?php } ?>

		     </td>
		    </tr>

            <tr>
              <td><?php echo $language->get('entry_comment_status_language'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][status_language]">
                  <?php if (isset($list['status_language']) && $list['status_language']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


          <tr>
              <td><?php echo $language->get('entry_widget_cached'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][cached]">
                  <?php if (isset($list['cached']) && $list['cached']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

	<tr>
			<td>
			<?php echo $language->get('entry_number_per_widget'); ?>

		</td>

			<td>
				<input type="text" name="ascp_widgets[<?php echo $list_num; ?>][number_per_widget]" value="<?php  if (isset( $list['number_per_widget'])) echo $list['number_per_widget']; ?>" size="3" />
			</td>

	</tr>


          <tr>
              <td><?php echo $language->get('entry_pagination'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][pagination]">
                  <?php if (isset($list['pagination']) && $list['pagination']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>



    <tr>
     <td class="left"><?php echo $language->get('entry_blog_num_desc'); ?></td>
     <td class="left">
      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][desc_symbols]" value="<?php  if (isset( $list['desc_symbols'])) echo $list['desc_symbols']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $language->get('entry_blog_num_desc_words'); ?></td>
     <td class="left">
      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][desc_words]" value="<?php  if (isset( $list['desc_words'])) echo $list['desc_words']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $language->get('entry_blog_num_desc_pred'); ?></td>
     <td class="left">
      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][desc_pred]" value="<?php  if (isset( $list['desc_pred'])) echo $list['desc_pred']; ?>" size="3" />
     </td>
    </tr>








 	<tr>
 		<td>
			<?php echo $language->get('entry_order'); ?>
		</td>
		<td>
         <select id="ascp_widgets_<?php echo $list_num; ?>_sort"  name="ascp_widgets[<?php echo $list_num; ?>][sort]">
           <option value="latest"  <?php if (isset( $list['sort']) &&  $list['sort']=='latest')  { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_latest'); ?></option>
           <option value="sort"  <?php if (isset( $list['sort']) &&  $list['sort']=='sort')  { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_sort'); ?></option>
           <option value="popular" <?php if (isset( $list['sort']) &&  $list['sort']=='popular') { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_popular'); ?></option>
           <option value="rating" <?php if (isset( $list['sort']) &&  $list['sort']=='rating') { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_rating'); ?></option>
           <option value="comments" <?php if (isset( $list['sort']) &&  $list['sort']=='comments') { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_comments'); ?></option>

         </select>
		</td>
	</tr>

 	<tr>
 		<td>
			<?php echo $language->get('entry_order_ad'); ?>
		</td>
		<td>
         <select id="ascp_widgets_<?php echo $list_num; ?>_order"  name="ascp_widgets[<?php echo $list_num; ?>][order]">
           <option value="desc"  <?php if (isset( $list['order']) &&  $list['order']=='desc') { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_desc'); ?></option>
           <option value="asc"   <?php if (isset( $list['order']) &&  $list['order']=='asc')  { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_asc'); ?></option>
        </select>
		</td>
	</tr>




              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_category_status'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][category_status]">
                  <?php if (isset($list['category_status']) && $list['category_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['category_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['category_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_view_record'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][record_status]">
                  <?php if (isset($list['record_status']) && $list['record_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['record_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['record_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


<!-- ************************ -->

              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_view_date'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][date_status]">
                  <?php if (isset($list['date_status']) && $list['date_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['date_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['date_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_view_comments'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][comments_status]">
                  <?php if (isset($list['comments_status']) && $list['comments_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['comments_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['comments_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>



          <tr>
              <td><?php echo $language->get('entry_view_viewed'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][viewed_status]">
                  <?php if (isset($list['viewed_status']) && $list['viewed_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_view_rating'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][rating_status]">
                  <?php if (isset($list['rating_status']) && $list['rating_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['rating_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['rating_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

<!--
              <tr>
              <td><?php echo $language->get('entry_view_rate'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][view_rate]">
                  <?php if (isset($list['view_rate']) && $list['view_rate']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
-->


              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_view_author'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][author_status]">
                  <?php if (isset($list['author_status']) && $list['author_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['author_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['author_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_manufacturer_status'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][manufacturer_status]">
                  <?php if (isset($list['manufacturer_status']) && $list['manufacturer_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['manufacturer_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['manufacturer_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_karma_status'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][karma_status]">
                  <?php if (isset($list['karma_status']) && $list['karma_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['karma_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['karma_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_avatar_status'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][avatar_status]">
                  <?php if (isset($list['avatar_status']) && $list['avatar_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['avatar_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['avatar_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>



		    <tr  class="pro-<?php echo $list_num;?>">
		     <td class="left"><?php echo $language->get('entry_avatar_dimension'); ?></td>
		     <td class="left">
		      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][avatar_width]" value="<?php if (isset($list['avatar_width'])) echo $list['avatar_width']; ?>" size="3" />x
		      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][avatar_height]" value="<?php if (isset($list['avatar_width'])) echo $list['avatar_height']; ?>" size="3" />
		     </td>
		    </tr>


              <tr class="pro-<?php echo $list_num;?>">
              <td class="left"><?php echo $language->get('entry_buyer_status'); ?></td>
              <td class="left"><select name="ascp_widgets[<?php echo $list_num; ?>][buyer_status]">
                  <?php if (isset($list['buyer_status']) && $list['buyer_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['buyer_status'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($list['buyer_status'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>



			<tr  class="pro-<?php echo $list_num;?>">
				<td>
				<?php echo $language->get('entry_admin_name'); ?>

				</td>

				<td>
					<input type="text" name="ascp_widgets[<?php echo $list_num; ?>][admin_name]" value="<?php  if (isset( $list['admin_name'])) echo $list['admin_name']; ?>" style="width: 90%;" />
				</td>

			</tr>


			<tr  class="pro-<?php echo $list_num;?>">
			 <td>
				<?php echo $language->get('entry_admin_color'); ?>
			 </td>
			 <td>
				<input type="text" name="ascp_widgets[<?php echo $list_num; ?>][admin_color]" class="colorpicker"  value="<?php  if (isset( $list['admin_color'])) echo $list['admin_color']; ?>" size="6" />

				<style>
				input.colorpicker {
					border-radius: 3px;
					padding: 4px;
					border-left:30px solid #FFF;
				}
				.margintop5 {
				margin-top: 5px;
				}

				</style>
				<script>
					$('.colorpicker').each(function(index) {

				 		 $(this).attr('id', 'colorpicker_'+index );
				         var colorpicker = new Array();
				         colorpicker[index] = $('#colorpicker_'+index).val();
				        $('#colorpicker_'+index).css('border-left-color', colorpicker[index]);

						$('#colorpicker_'+index).colpick({
							layout:'rgbhex',
							submit:0,
							color: colorpicker[index],
							onChange:function(hsb,hex,rgb,el,bySetColor) {
								if(!bySetColor) {
								  $(el).val('#'+hex);
								  $('#colorpicker_'+index).val('#'+hex );
								}
								$(el).css('border-left-color','#'+hex);
								$(this+' .colpick_current_color').css('background-color', colorpicker[index] );
								$('.colpick_current_color').css('display', 'visible' );

							}
						}).keyup(function(){
							$(this).colpickSetColor(this.value);
						});

					});
				</script>
			 </td>
			</tr>





<!-- ************************ -->





			 <tr>
              <td><?php echo $language->get('entry_blog'); ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $blog) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (isset($list['blogs']) && in_array($blog['blog_id'], $list['blogs'])) { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][blogs][]" value="<?php echo $blog['blog_id']; ?>" checked="checked" />
                    <?php echo $blog['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][blogs][]" value="<?php echo $blog['blog_id']; ?>" />
                    <?php echo $blog['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').prop('checked', true);" class="nohref"><?php echo $language->get('text_select_all'); ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="nohref"><?php echo $language->get('text_unselect_all'); ?></a></td>
            </tr>


			 <tr>
              <td><?php echo $language->get('entry_categories'); ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($cat as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (isset($list['categories']) && in_array($category['category_id'], $list['categories'])) { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][categories][]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                    <?php echo $category['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][categories][]" value="<?php echo $category['category_id']; ?>" />
                    <?php echo $category['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').prop('checked', true);" class="nohref"><?php echo $language->get('text_select_all'); ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="nohref"><?php echo $language->get('text_unselect_all'); ?></a></td>
            </tr>


   <?php foreach ($languages as $lang) { ?>
	<tr class="pro-<?php echo $list_num;?>">
		<td class="left">
			<?php echo $language->get('entry_title_further'); ?> <br>(<?php echo $lang['name']; ?>)
		</td>
			<td>
				<div style="float: left;">
				<textarea name="ascp_widgets[<?php echo $list_num; ?>][further][<?php echo $lang['language_id']; ?>]" rows="3" cols="50" ><?php if (isset($list['further'][$lang['language_id']])) { echo $list['further'][$lang['language_id']]; } else { echo ''; } ?></textarea>
				</div>
				<div style="float: left; margin-left: 3px;">
				<img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" ><br>
               </div>
			</td>

	</tr>
   <?php } ?>


	<tr class="pro-<?php echo $list_num;?>">
		<td class="left">
			<?php echo $language->get('entry_box_begin'); ?>
		</td>
			<td>
				<div style="float: left;">
				<textarea id="ascp_widgets_<?php echo $list_num; ?>_box_begin"  name="ascp_widgets[<?php echo $list_num; ?>][box_begin]" rows="3" cols="50" ><?php if (isset($list['box_begin'])) { echo $list['box_begin']; } else { echo ''; } ?></textarea>

				 <?php if (isset($list['box_begin_templates']) && is_array($list['box_begin_templates']) && !empty($list['box_begin_templates'])) { ?>
                 <div>
                 <?php echo $language->get('entry_box_begin_templates'); ?>
                 </div>

	               <div>
					<select name="ascp_widgets[<?php echo $list_num; ?>][box_begin_templates]" id="ascp_widgets_<?php echo $list_num; ?>_box_begin_templates">

	                 <?php  if (!isset($list['box_begin'])) { $list['box_begin'] = ''; } ?>
	                 <option value='<?php echo str_replace("'", '"', $list['box_begin']);  ?>'><?php echo $language->get('entry_box_begin_value'); ?></option>

	                 <?php foreach ($list['box_begin_templates'] as $box_begin_name => $box_begin_template) { ?>
	                   <option value='<?php echo str_replace("'", '"', $box_begin_template); ?>'><?php echo $box_begin_name; ?></option>
	                  <?php } ?>

	                 </select>
	                 </div>
						<script>
						$( '#ascp_widgets_<?php echo $list_num; ?>_box_begin_templates' )
						.change(function () {
						var str = '';
						$( '#ascp_widgets_<?php echo $list_num; ?>_box_begin_templates option:selected' ).each(function() {
						str = $(this).val();
						});

						$( '#ascp_widgets_<?php echo $list_num; ?>_box_begin' ).html( str );

						})
						.change();
						</script>

                 <?php } ?>


				</div>
			</td>
	</tr>

	<tr class="pro-<?php echo $list_num;?>">
		<td class="left">
			<?php echo $language->get('entry_box_end'); ?>
		</td>
			<td>
				<div style="float: left;">
				<textarea id="ascp_widgets_<?php echo $list_num; ?>_box_end" name="ascp_widgets[<?php echo $list_num; ?>][box_end]" rows="3" cols="50" ><?php if (isset($list['box_end'])) { echo $list['box_end']; } else { echo ''; } ?></textarea>

				 <?php if (isset($list['box_end_templates']) && is_array($list['box_end_templates']) && !empty($list['box_end_templates'])) { ?>
                 <div>
                 <?php echo $language->get('entry_box_end_templates'); ?>
                 </div>

	               <div>
					<select name="ascp_widgets[<?php echo $list_num; ?>][box_end_templates]" id="ascp_widgets_<?php echo $list_num; ?>_box_end_templates">

	                 <?php  if (!isset($list['box_end'])) { $list['box_end'] = ''; } ?>
	                 <option value='<?php echo str_replace("'", '"', $list['box_end']);  ?>'><?php echo $language->get('entry_box_end_value'); ?></option>

	                 <?php foreach ($list['box_end_templates'] as $box_end_name => $box_end_template) { ?>
	                   <option value='<?php echo str_replace("'", '"', $box_end_template); ?>'><?php echo $box_end_name; ?></option>
	                  <?php } ?>

	                 </select>
	                 </div>
						<script>
						$( '#ascp_widgets_<?php echo $list_num; ?>_box_end_templates' )
						.change(function () {
						var str = '';
						$( '#ascp_widgets_<?php echo $list_num; ?>_box_end_templates option:selected' ).each(function() {
						str = $(this).val();
						});

						$( '#ascp_widgets_<?php echo $list_num; ?>_box_end' ).html( str );

						})
						.change();
						</script>

                 <?php } ?>
				</div>
			</td>
	</tr>






            <tr class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_store'); ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (!isset($list['store']) || in_array(0, $list['store'])) { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][store][]" value="0" checked="checked" />
                    <?php echo $language->get('text_default_store'); ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][store][]" value="0" />
                    <?php echo $language->get('text_default_store'); ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (isset($list['store']) && in_array($store['store_id'], $list['store'])) { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][store][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][store][]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>

            <tr class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_customer_groups'); ?></td>
              <td><div class="scrollbox">
                  <?php if (!isset($list['customer_groups'])) { ?>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][customer_groups][]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                    <?php echo $customer_group['name']; ?>
                    </div>
                  <?php } ?>

                  <?php } else { ?>

                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (isset($list['customer_groups']) && in_array($customer_group['customer_group_id'], $list['customer_groups'])) { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][customer_groups][]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                    <?php echo $customer_group['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ascp_widgets[<?php echo $list_num; ?>][customer_groups][]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                    <?php echo $customer_group['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>

                  <?php } ?>


                </div></td>
            </tr>

            <tr>
              <td><?php echo $language->get('entry_doc_ready'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][doc_ready]">
                  <?php if (isset($list['doc_ready']) && $list['doc_ready']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($list['doc_ready'])) { echo  'selected="selected"'; }  ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (!isset($list['doc_ready'])) {  } else { echo  'selected="selected"'; }  ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

              <tr>
              <td><?php echo $language->get('entry_ajax'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][ajax]">
                  <?php if (isset($list['ajax']) && $list['ajax']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if ((isset($list['ajax']) && !$list['ajax']) || !isset($list['ajax'])) { echo  'selected="selected"'; } ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>




		    <tr>
		     <td class="left"><?php echo $language->get('entry_reserved'); ?></td>
		     <td class="left">
		      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][reserved]" value="<?php  if (isset($list['reserved'])) echo $list['reserved']; ?>" size="30" />
		     </td>
		    </tr>

</table>
</form>
</div>
  <?php }

//echo $this->request->post['list']."<br>";
  ?>
</div>