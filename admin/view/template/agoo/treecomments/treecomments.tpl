<?php
foreach ($ascp_widgets as $list_num=>$list) {
?>
<div id="list<?php echo $list_num;?>" style="padding-left: 200px;">
<style>
.pro-<?php echo $list_num;?> {display: none;
}
</style>
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
      <br><a onclick="$('.help').toggle(); return false; " class="mbutton button_blue" style="margin-top: 7px;"><?php echo $language->get('button_help'); ?></a>
      <a onclick="$('.pro-<?php echo $list_num;?>').toggle(); return false; " class="mbutton button_blue" style="margin-top: 7px; margin-left: 7px;"><?php echo $language->get('button_pro'); ?></a>
	 </div>

    </td>
    </tr>

	 <?php foreach ($languages as $lang) { ?>
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
	<td>
			<?php echo $language->get('entry_template_comments'); ?>

		</td>

			<td>
				<input type="text" class="template" name="ascp_widgets[<?php echo $list_num; ?>][template]" value="<?php if (isset($list['template'])) echo $list['template']; ?>" size="60" />

				<?php if ($list['type'] == 'treecomments' ) { ?>
				<input type="hidden" name="tpath" value="widgets/treecomments">
			    <?php } ?>

				<?php if ($list['type'] == 'forms' ) { ?>
				<input type="hidden" name="tpath" value="widgets/forms">
			    <?php } ?>


			</td>

	</tr>


	<tr>
	<td>
			<?php echo $language->get('entry_template_comment'); ?>

		</td>

			<td>
				<input type="text" class="template" name="ascp_widgets[<?php echo $list_num; ?>][blog_template_comment]" value="<?php if (isset($list['blog_template_comment'])) echo $list['blog_template_comment']; ?>" size="60" />

				<?php if ($list['type'] == 'treecomments' ) { ?>
				<input type="hidden" name="tpath" value="module/treecomments">
			    <?php } ?>

				<?php if ($list['type'] == 'forms' ) { ?>
				<input type="hidden" name="tpath" value="module/treecomments">
			    <?php } ?>



			</td>

	</tr>



		    <tr  class="pro-<?php echo $list_num;?>">
		     <td class="left"><?php echo $language->get('entry_langfile'); ?></td>
		     <td class="left">
		      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][langfile]" value="<?php  if (isset($list['langfile'])) echo $list['langfile']; ?>" size="50" />
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

          <tr  class="pro-<?php echo $list_num;?>">
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




	<tr  class="pro-<?php echo $list_num;?>">
			<td>
			<?php echo $language->get('entry_blog_num_comments'); ?>

		</td>

			<td>
				<input type="text" name="ascp_widgets[<?php echo $list_num; ?>][number_comments]" value="<?php  if (isset( $list['number_comments'])) echo $list['number_comments']; ?>" size="3" />
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
            <td><?php echo $language->get('entry_type_id'); ?></td>
           <td>
           <select name="ascp_widgets[<?php echo $list_num; ?>][type_id]">
			<?php foreach ($comment_type as $type_comment) {  ?>
	           <option value="<?php echo $type_comment['type_id']; ?>" <?php if (isset($list['type_id']) && $list['type_id'] == $type_comment['type_id']) { ?> selected="selected" <?php } ?>><?php echo $type_comment['type_id'].".".$type_comment['title'][$config_language_id]; ?></option>
            <?php } ?>
            </select>
            </td>
          </tr>




            <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_comment_status'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][status]">
                  <?php if (isset($list['status']) && $list['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>




            <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_comment_status_reg'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][status_reg]">
                  <?php if (isset( $list['status_reg']) &&  $list['status_reg']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr>
              <td><?php echo $language->get('entry_comment_status_now'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][status_now]">
                  <?php if (isset($list['status_now']) && $list['status_now']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr>
              <td><?php echo $language->get('entry_karma'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][karma]">
                  <?php if (isset($list['karma']) && $list['karma']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_karma_reg'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][karma_reg]">
                  <?php if (isset($list['karma_reg']) && $list['karma_reg']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

		    <tr  class="pro-<?php echo $list_num;?>">
		     <td class="left"><?php echo $language->get('entry_comment_rating_num'); ?></td>
		     <td class="left">
		      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][rating_num]" value="<?php  if (isset($list['rating_num'])) echo $list['rating_num']; ?>" size="3" />
		     </td>
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



			<tr>
				<td>
				<?php echo $language->get('entry_comments_email'); ?>

				</td>

				<td>
					<input type="text" name="ascp_widgets[<?php echo $list_num; ?>][comments_email]" value="<?php  if (isset( $list['comments_email'])) echo $list['comments_email']; else echo $config_email; ?>" style="width: 90%;" />
				</td>

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


 	<tr  class="pro-<?php echo $list_num;?>">
 		<td>
			<?php echo $language->get('entry_order'); ?>
		</td>
		<td>
         <select id="ascp_widgets_<?php echo $list_num; ?>_order"  name="ascp_widgets[<?php echo $list_num; ?>][order]">
           <option value="sort"  <?php if (isset($list['order']) &&  $list['order']=='sort')  { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_sort'); ?></option>
           <option value="date"  <?php if (isset( $list['order']) &&  $list['order']=='date')  { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_date'); ?></option>
           <option value="rating" <?php if (isset( $list['order']) &&  $list['order']=='rating') { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_rating'); ?></option>
            <option value="rate" <?php if (isset( $list['order']) &&  $list['order']=='rate') { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_rate'); ?></option>
         </select>
		</td>
	</tr>



	<tr  class="pro-<?php echo $list_num;?>">
 		<td>
			<?php echo $language->get('entry_order_ad'); ?>
		</td>
		<td>
         <select id="ascp_widgets_<?php echo $list_num; ?>_order_ad"  name="ascp_widgets[<?php echo $list_num; ?>][order_ad]">
           <option value="desc"  <?php if (isset( $list['order_ad']) &&  $list['order_ad']=='desc') { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_desc'); ?></option>
           <option value="asc"   <?php if (isset( $list['order_ad']) &&  $list['order_ad']=='asc')  { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_asc'); ?></option>
        </select>
		</td>
      </tr>

            <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_comment_must'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][comment_must]">
                  <?php if (isset($list['comment_must']) && $list['comment_must'] || !isset($list['comment_must'])) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>





          <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_rating'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][rating]">
                  <?php if (isset($list['rating']) && $list['rating']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

          <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_rating_must'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][rating_must]">
                  <?php if (isset($list['rating_must']) && $list['rating_must']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


          <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_visual_rating'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][visual_rating]">
                  <?php if (isset($list['visual_rating']) && $list['visual_rating']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

          <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_help_view'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][help_view]">
                  <?php if (isset($list['help_view']) && $list['help_view']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>



            <tr>
              <td><?php echo $language->get('entry_fields_view'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][fields_view]">
                  <?php if (isset($list['fields_view']) && $list['fields_view']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_view_captcha'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][view_captcha]">
                  <?php if (isset($list['view_captcha']) && $list['view_captcha']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_comment_signer'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][signer]">
                  <?php if (isset($list['signer']) && $list['signer'] || !isset($list['signer'])) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

            <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_comment_signer_answer'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][signer_answer]">
                  <?php if (isset($list['signer_answer']) && $list['signer_answer'] || !isset($list['signer_answer'])) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


            <tr  class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_stat_status'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][stat_status]">
                  <?php if (isset($list['stat_status']) && $list['stat_status'] || !isset($list['stat_status'])) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>



          <tr class="pro-<?php echo $list_num;?>">
              <td><?php echo $language->get('entry_visual_editor'); ?></td>
              <td><select name="ascp_widgets[<?php echo $list_num; ?>][visual_editor]">
                  <?php if (isset($list['visual_editor']) && $list['visual_editor']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


		    <tr  class="pro-<?php echo $list_num;?>">
		     <td class="left"><?php echo $language->get('entry_bbwidth'); ?></td>
		     <td class="left">
		      <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][bbwidth]" value="<?php if (isset($list['bbwidth'])) echo $list['bbwidth']; ?>" size="3" />
		      </td>
		    </tr>


		    <tr  class="pro-<?php echo $list_num;?>">
		     <td class="left"><?php echo $language->get('entry_record'); ?></td>
		     <td class="left">
			  <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][record]" value="<?php if (isset($list['record'])) echo $list['record']; ?>" style="width: 80%;">
			  <input type="text" name="ascp_widgets[<?php echo $list_num; ?>][recordid]" value="<?php if (isset($list['recordid']) ) echo $list['recordid']; ?>" style="width: 10%; display:none;">
		      </td>
		    </tr>


		    <tr  class="pro-<?php echo $list_num;?>">
		     <td class="left"><?php echo $language->get('entry_handler'); ?></td>
		     <td class="left">
		      <textarea style="width: 96%;" name="ascp_widgets[<?php echo $list_num; ?>][handler]"><?php  if (isset($list['handler'])) echo $list['handler']; ?></textarea>
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
       <td colspan="2">


   <table class="mytable" id="table_fields_<?php echo $list_num;?>" >
     <thead>
      <tr>
      <td class="left" style=""><div style="width: 100%"><?php echo $language->get('entry_name_field'); ?></div></td>
       <td style="width: 200px;"><?php echo $language->get('text_action'); ?></td>
      </tr>

     </thead>
        <?php
          $fields_row = 0;
        ?>

      <?php



       if (isset($list['addfields']) && !empty($list['addfields'])) {
        foreach ($list['addfields'] as $num_field => $field) {

         while (!isset($list['addfields'][$fields_row])) {
          $fields_row++;
         }

        if (isset($field['name']) &&  $field['name']!='') {
        	$field['field_name'] = $field['name'];
        }

        if (isset($field['sort_order']) &&  $field['sort_order']!='') {
        	$field['field_order'] =$field['sort_order'];
        }

        if (isset($field['title']) &&  $field['title']!='') {
        	$field['field_description'] =$field['title'];
        }

        ?>
          <tr id="field-row-<?php echo $list_num;?>-<?php echo $num_field; ?>">

            <td class="left">

	        <select name="ascp_widgets[<?php echo $list_num; ?>][addfields][<?php echo $num_field; ?>][field_name]">
            <?php foreach ($fields_info as $f_i) { ?>
           	<option value="<?php echo $f_i['field_name']; ?>"   <?php if (isset( $list['addfields'][$num_field]['field_name']) &&  $list['addfields'][$num_field]['field_name']==$f_i['field_name'])  { echo 'selected="selected"'; } ?>><?php echo $f_i['field_name']; ?> -> <?php echo $f_i['field_description']; ?></option>
            <?php } ?>
            </select>

            </td>

            <td class="left">
            <div style="width: 100px;">
             <a onclick="$('#field-row-<?php echo $list_num;?>-<?php echo $num_field; ?>').remove();" class="mbutton button_purple"><?php echo $language->get('button_remove');?></a>
            </div>
           </td>

      </tr>



        <?php
        }
        } else  {
        ?>
       <td class="left"><div style="width: 100%">&nbsp;</div></td>
       <td style="width: 200px;"><div style="width: 100%">&nbsp;</div></td>
        <?php
        }

        ?>
        <tfoot>
          <tr>
            <td colspan="1"></td>
            <td class="left"><a id="add_f-<?php echo $list_num;?>"  class="markbutton"><?php echo $language->get('text_action_add_field'); ?></a></td>
          </tr>
        </tfoot>
      </table>
     <a href="<?php echo $url_fields; ?>" target="_blank" class="markbutton"><div style="float: left;"><img src="view/image/blog-rec-m.png"  style="" ></div><div><?php echo $language->get('entry_fields'); ?></div></a>

      </td>
       </tr>

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

<?php }   ?>


            <?php if (isset($fields_info) && !empty($fields_info)) {
             foreach ($fields_info as $f_i) { ?>
            <div style="display:none;" id="f_i_<?php echo $list_num; ?>_<?php echo $f_i['field_name']; ?>">
            <?php echo $f_i['field_description']; ?>
            </div>
            <?php
             }
            } ?>



<script>
var afields_row_<?php echo $list_num;?> = Array();

<?php
if (isset($list['addfields'])) {
 foreach ($list['addfields'] as $indx => $module) {
?>
afields_row_<?php echo $list_num;?>.push(<?php echo $indx; ?>);
<?php
 }
}
?>
var num_field =<?php echo $fields_row; ?>;


function addfields() {var aindex = -1;
	for(i=0; i<afields_row_<?php echo $list_num;?>.length; i++) {
	 flg = jQuery.inArray(i, afields_row_<?php echo $list_num;?>);
	 if (flg == -1) {
	  aindex = i;
	 }
	}
	if (aindex == -1) {
	  aindex = afields_row_<?php echo $list_num;?>.length;
	}
	num_field = aindex;
	afields_row_<?php echo $list_num;?>.push(aindex);




addfields_<?php echo $list_num;?> = '<tr>';


addfields_<?php echo $list_num;?>+= '            <td class="right">';


addfields_<?php echo $list_num;?>+= '	        <select name="ascp_widgets[<?php echo $list_num; ?>][addfields]['+num_field +'][field_name]">';

<?php foreach ($fields_info as $f_i) { ?>
f_i = $('#f_i_<?php echo $list_num; ?>_<?php echo $f_i['field_name']; ?>').text();
addfields_<?php echo $list_num;?>+= '          	<option value="<?php echo $f_i['field_name']; ?>"><?php echo $f_i['field_name']; ?> -\> '+f_i+'</option>';
<?php } ?>

addfields_<?php echo $list_num;?>+= '            </select>';


addfields_<?php echo $list_num;?>+= '            </td>';


addfields_<?php echo $list_num;?>+= '            <td class="left">';
addfields_<?php echo $list_num;?>+= '            <div style="float:left; width: 100px;">';
addfields_<?php echo $list_num;?>+= '             <a onclick="$(\'#field-row-<?php echo $list_num;?>-' + num_field + '\').remove();" class="mbutton button_purple"><?php echo $language->get('button_remove');?></a>';
addfields_<?php echo $list_num;?>+= '           </div>';
addfields_<?php echo $list_num;?>+= '    </td>';
addfields_<?php echo $list_num;?>+= ' </tr>';

html_<?php echo $list_num;?>  = '<tbody id="field-row-<?php echo $list_num;?>-' + num_field + '">' + addfields_<?php echo $list_num;?> + '</tbody>';



$('#table_fields_<?php echo $list_num;?> tfoot').before(html_<?php echo $list_num;?>);

num_field++;

fields_auto();

}

$('#add_f-<?php echo $list_num;?>').bind('click',{ }, addfields);
</script>

<script type="text/javascript">
$('input[name=\'ascp_widgets[<?php echo $list_num; ?>][record]\']').autocomplete({
	'source': function(request, response) {         	<?php
         	if (SCP_VERSION < 2) {
         	?>
         	 var filter_name = request.term;
         	<?php
         	} else {
         	?>
         	 var filter_name = request;
         	<?php
         	}
         	?>
		$.ajax({
			url: 'index.php?route=catalog/record/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(filter_name),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.record_id
					}
				}));
			}
		});
	},

	'select': function(event, ui) {
         	<?php
         	if (SCP_VERSION < 2) {
         	?>
         	 var  veli = ui.item.value;
         	 var  labi = ui.item.label;
         	<?php
         	} else {
         	?>
         	 var veli = event['value'];
         	 var labi = event['label'];
         	<?php
         	}
         	?>

		$('input[name=\'ascp_widgets[<?php echo $list_num; ?>][record]\']').val(labi);
		$('input[name=\'ascp_widgets[<?php echo $list_num; ?>][recordid]\']').val(veli);

		return false;
	}
});

</script>

<script>
if ($.isFunction($.fn.on)) {
	$(document).on('blur','input[name=\'ascp_widgets[<?php echo $list_num; ?>][record]\']',  function() {

        if ($('input[name=\'ascp_widgets[<?php echo $list_num; ?>][record]\']').val()=='') {
         $('input[name=\'ascp_widgets[<?php echo $list_num; ?>][recordid]\']').val('');
         }

		return false;
	});

} else {

	$('input[name=\'ascp_widgets[<?php echo $list_num; ?>][record]\']').live('blur',  function() {
        if ($('input[name=\'ascp_widgets[<?php echo $list_num; ?>][record]\']').val()=='') {
         $('input[name=\'ascp_widgets[<?php echo $list_num; ?>][recordid]\']').val('');
         }

		return false;
	});

}
</script>









