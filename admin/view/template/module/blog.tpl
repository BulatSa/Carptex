<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="scp_grad" style="overflow: hidden;">
    <div style="float:left; margin-top: 10px;" >
    	<img src="view/image/blog-icon.png" style="height: 21px; margin-left: 10px; " >
    </div>

<div style="margin-left: 10px; float:left; margin-top: 10px;  color: #777;">
<ins style="color: #00A3D9; padding-top: 17px; text-shadow: 0 2px 1px #FFFFFF; padding-left: 3px;  font-weight: normal;  text-decoration: none; ">
<?php echo strip_tags($heading_title); ?>
</ins> ver.: <?php echo $blog_version; ?>
</div>

    <div class="scp_grad_green" style=" height: 40px; float:right; ">
      <div style="color: #555;margin-top: 2px; line-height: 18px; margin-left: 9px; margin-right: 9px; overflow: hidden;"><?php echo $language->get('heading_dev'); ?></div>
    </div>

</div>

  <div class="page-header">
    <div class="container-fluid">

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>


<div id="content1" style="border: none;">

<div style="clear: both; line-height: 1px; font-size: 1px;"></div>


<?php if ($error_warning) { ?>
    <div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php } ?>

<?php if ($success) { ?>
    <div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php } ?>


<div class="box1">

<div class="content">

<?php echo $agoo_menu; ?>

<div style="margin:5px; float:right;">
   <a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton nohref"><?php echo $button_cancel; ?></a>
</div>

<div style="clear: both; width:100%; overflow: hidden; line-height: 1px; font-size: 1px;"></div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<script type="text/javascript">
function delayer(){
    window.location = 'index.php?route=module/blog&token=<?php echo $token; ?>';
}
</script>

 <div id="tabs" class="htabs"><a href="#tab-options"><?php echo $language->get('tab_options'); ?></a>
  <a href="#tab-color"><?php echo $language->get('tab_color'); ?></a>
  <a href="#tab-avatar"><?php echo $language->get('entry_avatar'); ?></a>
  <a href="#tab-install"><?php echo $language->get('entry_install_update'); ?></a>
  <a href="#tab-scripts"><?php echo $language->get('entry_scripts'); ?></a>
  <a href="#tab-fields"><?php echo $language->get('entry_service'); ?></a>
  <a href="#tab-faq"><?php echo $language->get('entry_about'); ?> / <?php echo $language->get('entry_faq'); ?></a>
 </div>
<div style="clear: both; width:100%; overflow: hidden; line-height: 1px; font-size: 1px;"></div>

<div id="tab-options">

  <?php echo $text_new_version; ?>

   <table class="mynotable" style="margin-bottom:20px; background: white; vertical-align: center;">

    <tr>
     <td class="left"><?php echo $entry_small_dim; ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[blog_small][width]" value="<?php if (isset($ascp_settings['blog_small']['width'])) echo $ascp_settings['blog_small']['width']; ?>" size="3" />x
      <input type="text" name="ascp_settings[blog_small][height]" value="<?php if (isset($ascp_settings['blog_small']['height'])) echo $ascp_settings['blog_small']['height']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_big_dim; ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[blog_big][width]" value="<?php  if (isset($ascp_settings['blog_big']['width'])) echo $ascp_settings['blog_big']['width']; ?>" size="3" />x
      <input type="text" name="ascp_settings[blog_big][height]" value="<?php if (isset($ascp_settings['blog_big']['height'])) echo $ascp_settings['blog_big']['height']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_records; ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[blog_num_records]" value="<?php  if (isset($ascp_settings['blog_num_records'])) echo $ascp_settings['blog_num_records']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_comments; ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[blog_num_comments]" value="<?php  if (isset($ascp_settings['blog_num_comments'])) echo $ascp_settings['blog_num_comments']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_desc; ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[blog_num_desc]" value="<?php  if (isset($ascp_settings['blog_num_desc'])) echo $ascp_settings['blog_num_desc']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_desc_words; ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[blog_num_desc_words]" value="<?php  if (isset($ascp_settings['blog_num_desc_words'])) echo $ascp_settings['blog_num_desc_words']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $entry_blog_num_desc_pred; ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[blog_num_desc_pred]" value="<?php  if (isset($ascp_settings['blog_num_desc_pred'])) echo $ascp_settings['blog_num_desc_pred']; ?>" size="3" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $language->get('entry_format_date'); ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[format_date]" value="<?php  if (isset($ascp_settings['format_date'])) echo $ascp_settings['format_date']; else echo $language->get('text_date'); ?>" size="11" />
     </td>
    </tr>

    <tr>
     <td class="left"><?php echo $language->get('entry_format_hours'); ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[format_hours]" value="<?php  if (isset($ascp_settings['format_hours'])) echo $ascp_settings['format_hours']; else  echo $language->get('text_hours'); ?>" size="11" />
     </td>
    </tr>

          <tr>
              <td><?php echo $language->get('entry_format_time'); ?></td>
              <td><select name="ascp_settings[format_time]">
                  <?php if (isset($ascp_settings['format_time']) && $ascp_settings['format_time']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1" <?php if (!isset($ascp_settings['format_time'])) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if (isset($ascp_settings['format_time'])) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


            <tr>
 			 <td><?php echo $language->get('entry_complete_status'); ?>
 			  <?php foreach ($order_statuses as $order_status) { ?>

				 <?php if (isset($ascp_settings['complete_status']) && in_array($order_status['order_status_id'], $ascp_settings['complete_status'])) { ?>
                    <div class="color_green"><?php echo $order_status['name']; ?></div>
                 <?php } ?>


 			  <?php } ?>
 			 </td>
              <td>
               <div class="scrollbox">
                  <?php  $class = 'even'; ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (isset($ascp_settings['complete_status']) && in_array($order_status['order_status_id'], $ascp_settings['complete_status'])) { ?>
                    <input type="checkbox" name="ascp_settings[complete_status][]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                    <?php echo $order_status['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="ascp_settings[complete_status][]" value="<?php echo $order_status['order_status_id']; ?>" />
                    <?php echo $order_status['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
               </td>
            </tr>




          <tr>
            <td><?php echo $language->get('entry_blog_search'); ?></td>
            <td><select name="ascp_settings[blog_search]">
             	<option value="0"></option>

			<?php foreach ($categories as $cat) {  ?>
              	<option value="<?php echo $cat['blog_id']; ?>" <?php if (isset($ascp_settings['blog_search']) && $ascp_settings['blog_search'] == $cat['blog_id']) { ?> selected="selected" <?php } ?>><?php echo $cat['name']; ?></option>
            <?php } ?>
              </select></td>
          </tr>



   <!--
 	<tr>
 		<td>
			<?php echo $language->get('entry_order_ad'); ?>
		</td>
		<td>
         <select id="ascp_settings_order_ad"  name="ascp_settings[order_ad]">
           <option value="desc"  <?php if (isset( $ascp_settings['order_ad']) &&  $ascp_settings['order_ad']=='desc') { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_desc'); ?></option>
           <option value="asc"   <?php if (isset( $ascp_settings['order_ad']) &&  $ascp_settings['order_ad']=='asc')  { echo 'selected="selected"'; } ?>><?php echo $language->get('text_what_asc'); ?></option>
        </select>
		</td>
	</tr>
         -->
          <tr>
              <td><?php echo $language->get('entry_category_status'); ?></td>
              <td><select name="ascp_settings[category_status]">
                  <?php if ( isset($ascp_settings['category_status']) && $ascp_settings['category_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

          <tr>
              <td><?php echo $language->get('entry_cache_widgets'); ?></td>
              <td><select name="ascp_settings[cache_widgets]">
                  <?php if (isset($ascp_settings['cache_widgets']) && $ascp_settings['cache_widgets']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          <tr>

          <tr>
              <td><?php echo $language->get('entry_cache_pages'); ?></td>
              <td><select name="ascp_settings[cache_pages]">
                  <?php if (isset($ascp_settings['cache_pages']) && $ascp_settings['cache_pages']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          <tr>

          <!--
           <td><?php echo $language->get('entry_review_visual'); ?></td>
              <td><select name="ascp_settings[review_visual]">
                  <?php if (isset($ascp_settings['review_visual']) && $ascp_settings['review_visual']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			-->
			 <input type="hidden" name="ascp_settings[review_visual]" value="0" />



          <tr>
              <td><?php echo $language->get('entry_resize'); ?></td>
              <td><select name="ascp_settings[blog_resize]">
                  <?php if (isset($ascp_settings['blog_resize']) && $ascp_settings['blog_resize']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>

          <tr>
              <td><?php echo $language->get('entry_og'); ?></td>
              <td><select name="ascp_settings[og]">
                  <?php if (isset($ascp_settings['og']) && $ascp_settings['og']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>


        <!--
          <tr>
              <td><?php echo $language->get('entry_layout_url_status'); ?></td>
              <td><select name="ascp_settings[layout_url_status]">
                  <?php if (isset($ascp_settings['layout_url_status']) && $ascp_settings['layout_url_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            -->


    <tr>
     <td class="left"><?php echo $language->get('entry_end_url_record'); ?></td>
     <td class="left">
      <input type="text" class="template" name="ascp_settings[end_url_record]" value="<?php  if (isset($ascp_settings['end_url_record'])) echo $ascp_settings['end_url_record']; ?>" size="20" />
     </td>
    </tr>

          <tr>
              <td><?php echo $language->get('entry_comp_url'); ?></td>
              <td><select name="ascp_comp_url">
                  <?php if (isset($ascp_comp_url) && $ascp_comp_url) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>




    <tr>
     <td class="left"><?php echo $language->get('entry_get_pagination');  ?></td>
     <td class="left">
      <input type="text" class="template" name="ascp_settings[get_pagination]" value="<?php  if (isset($ascp_settings['get_pagination'])) echo $ascp_settings['get_pagination']; ?>" size="20" />
     </td>
    </tr>




            <tr>
            <td><?php echo $language->get('entry_colorbox_theme'); ?></td>
              <td>
               <select name="ascp_settings[colorbox_theme]">
           	<?php
				foreach ($colorbox_theme as $num =>$list) {
		    ?>
                <?php if (isset($ascp_settings['colorbox_theme']) && $ascp_settings['colorbox_theme']==$list) { ?>
                <option value="<?php echo $list; ?>" selected="selected"><?php echo $list; ?></option>
                <?php } else { ?>
                <option value="<?php echo $list; ?>"><?php echo $list; ?></option>
                <?php } ?>

              <?php
              }
              ?>
              </select>
              </td>
              </tr>


	 <?php foreach ($languages as $lang) { ?>
	<tr>
		<td class="left">
			<?php echo $language->get('entry_title_further'); ?> (<?php echo $lang['name']; ?>)
		</td>
			<td>
				<div style="float: left;">
				<textarea name="ascp_settings[further][<?php echo $lang['language_id']; ?>]" rows="3" cols="50" ><?php if (isset($ascp_settings['further'][$lang['language_id']])) { echo $ascp_settings['further'][$lang['language_id']]; } else { echo '&rarr;'; } ?></textarea>
				</div>
				<div style="float: left; margin-left: 3px;">
				<img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" ><br>
               </div>
			</td>

	</tr>
   <?php } ?>

	<tr>
		<td class="left">
			<?php echo $language->get('entry_box_begin'); ?>
		</td>
			<td>
				<div style="float: left;">
				<textarea name="ascp_settings[box_begin]" rows="3" cols="50" ><?php if (isset($ascp_settings['box_begin'])) { echo $ascp_settings['box_begin']; } else { echo ''; } ?></textarea>
				</div>
			</td>
	</tr>

	<tr>
		<td class="left">
			<?php echo $language->get('entry_box_end'); ?>
		</td>
			<td>
				<div style="float: left;">
				<textarea name="ascp_settings[box_end]" rows="3" cols="50" ><?php if (isset($ascp_settings['box_end'])) { echo $ascp_settings['box_end']; } else { echo ''; } ?></textarea>
				</div>
			</td>
	</tr>



	<tr>
		<td class="left">
			<?php echo $language->get('entry_box_share_record'); ?>
		</td>
			<td>
				<div style="float: left;">
				<textarea name="ascp_settings[box_share]" rows="3" cols="50" ><?php if (isset($ascp_settings['box_share'])) { echo $ascp_settings['box_share']; } else { echo ''; } ?></textarea>
				</div>
			</td>
	</tr>

	<tr>
		<td class="left">
			<?php echo $language->get('entry_box_share_blog'); ?>
		</td>
			<td>
				<div style="float: left;">
				<textarea name="ascp_settings[box_share_list]" rows="3" cols="50" ><?php if (isset($ascp_settings['box_share_list'])) { echo $ascp_settings['box_share_list']; } else { echo ''; } ?></textarea>
				</div>
			</td>
	</tr>



	<tr>
		<td class="left">
			<?php echo $language->get('entry_comment_types'); ?>
		</td>
			<td>
				<div style="float: left;">

   <table id="comment_types" class="list">
	   <thead>
             <tr>
                <td class="left"><?php echo $language->get('entry_id'); ?></td>
                <td><?php echo $language->get('entry_title'); ?></td>
                <td></td>
             </tr>

      </thead>

      <?php if (isset($ascp_settings['comment_type']) && !empty($ascp_settings['comment_type'])) { ?>
      <?php foreach ($ascp_settings['comment_type'] as $comment_type_id => $comment_type) { ?>
      <?php $comment_type_row = $comment_type_id; ?>
      <tbody id="comment_type_row<?php echo $comment_type_row; ?>">
          <tr>
               <td class="left">
				<input type="text" name="ascp_settings[comment_type][<?php echo $comment_type_id; ?>][type_id]" value="<?php if (isset($comment_type['type_id'])) echo $comment_type['type_id']; ?>" size="3">
               </td>

				<td class="right">
				 <?php foreach ($languages as $lang) { ?>
					<div style="margin-bottom: 3px;">
					<input type="text" name="ascp_settings[comment_type][<?php echo $comment_type_id; ?>][title][<?php echo $lang['language_id']; ?>]" value="<?php if (isset($comment_type['title'][$lang['language_id']])) echo $comment_type['title'][$lang['language_id']]; ?>" style="width: 300px;"><img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" >
					</div>
                 <?php } ?>
				</td>

                <td class="left"><a onclick="$('#comment_type_row<?php echo $comment_type_row; ?>').remove();" class="markbutton button_purple nohref"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>

            <?php } ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addCommentType();" class="markbutton nohref"><?php echo $language->get('entry_add_comment_type'); ?></a></td>
              </tr>
            </tfoot>
          </table>





				</div>
			</td>
	</tr>



    <tr>
     <td></td>
     <td></td>
    </tr>
   </table>





  </div>




<div id="tab-scripts">

    <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_script_reviews; ?>',
			dataType: 'html',
			type: 'POST',
			data: {
                layout_route: 'record/record'
            },
			beforeSend: function()
			{
               $('#create_scripts').html('<?php echo $language->get('text_loading_main'); ?>');
			},
			success: function(json) {
				$('#create_scripts').html(json);
				//setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#create_scripts').html('Error');
			}
		}); return false;" class="markbuttono" style=""><?php echo $language->get('text_url_script_reviews_records'); ?></a>


		<div style="height: 5px;line-height: 5px;">&nbsp;</div>


    <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_script_reviews; ?>',
			dataType: 'html',
			type: 'POST',
			data: {
                layout_route: 'product/product'
            },
			beforeSend: function()
			{
               $('#create_scripts').html('<?php echo $language->get('text_loading_main'); ?>');
			},
			success: function(json) {
				$('#create_scripts').html(json);
				//setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#create_scripts').html('Error');
			}
		}); return false;" class="markbuttono" style=""><?php echo $language->get('text_url_script_reviews_products'); ?></a>








<div id="create_scripts" style="color: green; font-weight: bold;"></div>

</div>



<div id="tab-color">
 <div>
 <?php
 // echo $language->get('css_help');
 ?>
 </div>
<div>
	<table class="mynotable" style="vertical-align: center;">
	<tbody>

	<tr>
			     <td class="left"><?php echo $language->get('css_user'); ?></td>
			     <td class="left">
						<div>
							<textarea name="ascp_settings[css][css]" rows="9" cols="40" ><?php if (isset($ascp_settings['css']['css'])) { echo $ascp_settings['css']['css']; } else { echo ''; } ?></textarea>
						</div>
			     </td>
	</tr>


	<tr>
	            <td class="left"><?php echo $language->get('entry_css_dir'); ?></td>
	            <td class="left">
		            <select name="ascp_settings[css_dir]">
		           	<?php  foreach ($css_dir as $num =>$list) { ?>
		                <?php if (isset($ascp_settings['css_dir']) && $ascp_settings['css_dir']==$list) { ?>
		                <option value="<?php echo $list; ?>" selected="selected"><?php echo $language->get( $list.'_css_dir'); ?></option>
		                <?php } else { ?>
		                <option value="<?php echo $list; ?>"><?php echo $language->get( $list.'_css_dir'); ?></option>
		                <?php } ?>

		              <?php
		              }
		              ?>
		              </select>
	              </td>
	</tr>


	<tr>
		<td colspan="2">
		<table class="table_width_100">
				<tr>
		        	<td colspan="2" class="entry_td"><?php echo $language->get('css_background_body'); ?></td>
		        	<td></td>
		      	</tr>

				<tr>
					<td class="left td_width"><?php echo $language->get('css_record-content'); ?></td>
					<td class="left">
						<div>
							<input type="text" name="ascp_settings[css][record-content]" class="colorpicker" size="6" value="<?php if (isset($ascp_settings['css']['record-content'])) echo $ascp_settings['css']['record-content']; ?>" />
						</div>
					</td>
				</tr>

	  			<tr>
	     			<td class="left td_width"><?php echo $language->get('css_blog-content'); ?></td>
	     			<td class="left">
						<div>
							<input type="text" name="ascp_settings[css][blog-content]" class="colorpicker" size="6" value="<?php if (isset($ascp_settings['css']['blog-content'])) echo $ascp_settings['css']['blog-content']; ?>" />
						</div>
	     			</td>
	    		</tr>
		</table>
		</td>
		<td>
		</td>
	</tr>


	<tr>
		<td colspan="2">
		<table class="table_width_100">
				<tr>
	            	<td colspan="2" class="entry_td"><?php echo $language->get('css_ascp-list-title'); ?></td>
	            	<td></td>
	      		</tr>

	  			<tr>
	     			<td class="left td_width"><?php echo $language->get('css_ascp-list-title-color'); ?></td>
	     			<td class="left">
						<div>
							<input type="text" name="ascp_settings[css][ascp-list-title-color]" class="colorpicker" size="6" value="<?php if (isset($ascp_settings['css']['ascp-list-title-color'])) echo $ascp_settings['css']['ascp-list-title-color']; ?>" />
						</div>
	     			</td>
	  			</tr>

	            <tr>
	            	<td class="left td_width"><?php echo $language->get('css_ascp-list-title-size'); ?></td>
	              	<td>
		            	<select name="ascp_settings[css][ascp-list-title-size]">
		           		<?php  foreach ($css_font_size as $num =>$list) { ?>
		                <?php if (isset($ascp_settings['css']['ascp-list-title-size']) && $ascp_settings['css']['ascp-list-title-size']==$list) { ?>
		                <option value="<?php echo $list; ?>" selected="selected"><?php echo $list; ?></option>
		                <?php } else { ?>
		                <option value="<?php echo $list; ?>"><?php echo $list; ?></option>
		                <?php } ?>

		              	<?php
		              	}
		              	?>
		              	</select>
	     			</td>
	     		</tr>

	            <tr>
	            <td class="left td_width"><?php echo $language->get('css_ascp-list-title-line'); ?></td>
	              <td>
		               <select name="ascp_settings[css][ascp-list-title-line]">
			           	<?php  foreach ($css_font_size as $num =>$list) { ?>
			                <?php if (isset($ascp_settings['css']['ascp-list-title-line']) && $ascp_settings['css']['ascp-list-title-line']==$list) { ?>
			                <option value="<?php echo $list; ?>" selected="selected"><?php echo $list; ?></option>
			                <?php } else { ?>
			                <option value="<?php echo $list; ?>"><?php echo $list; ?></option>
			                <?php } ?>

			              <?php
			              }
			              ?>
		              </select>
	              	</td>
	              </tr>

	            <tr>
	            	<td class="left td_width"><?php echo $language->get('css_ascp-list-title-decoration'); ?></td>
	              	<td>
		               <select name="ascp_settings[css][ascp-list-title-decoration]">
			           	<?php  foreach ($css_text_decoration as $num =>$list) { ?>
			                <?php if (isset($ascp_settings['css']['ascp-list-title-decoration']) && $ascp_settings['css']['ascp-list-title-decoration']==$list) { ?>
			                <option value="<?php echo $list; ?>" selected="selected"><?php echo $list; ?></option>
			                <?php } else { ?>
			                <option value="<?php echo $list; ?>"><?php echo $list; ?></option>
			                <?php } ?>

			              <?php
			              }
			              ?>
		              </select>
	              	</td>
	            </tr>

	            <tr>
	            	<td class="left td_width"><?php echo $language->get('css_ascp-list-title-weight'); ?></td>
	              	<td>
		               <select name="ascp_settings[css][ascp-list-title-weight]">
			           	<?php  foreach ($css_font_weight as $num =>$list) { ?>
			                <?php if (isset($ascp_settings['css']['ascp-list-title-weight']) && $ascp_settings['css']['ascp-list-title-weight']==$list) { ?>
			                <option value="<?php echo $list; ?>" selected="selected"><?php echo $list; ?></option>
			                <?php } else { ?>
			                <option value="<?php echo $list; ?>"><?php echo $list; ?></option>
			                <?php } ?>

			              <?php
			              }
			              ?>
		              </select>
	              	</td>
	            </tr>



	      </table>
		</td>
		<td>
		</td>
	</tr>



	</tbody>
	</table>
</div>




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

</div>






<div id="tab-fields">

<div>
<a href="<?php echo $url_fields; ?>" class="markbutton"  style="clear:both;"><div><img src="view/image/blog-rec-m.png"  style="" ></div>
<div style=""><?php echo $language->get('entry_fields'); ?></div></a>
</div>
<div>
<a href="<?php echo $url_adapter; ?>" class="markbutton"  style="clear:both;"><div><img src="view/image/agootemplates-options-m.png"  style="" ></div>
<div style=""><?php echo $language->get('entry_adapter'); ?></div></a>
</div>

</div>
<!--
<div id="tab-about">

<?php
//echo $language->get('text_about');
?>

</div>
-->

<div id="tab-faq">
<?php echo $language->get('text_faq'); ?>



<div>
    <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_delete_all_settings; ?>',
			dataType: 'html',
			beforeSend: function()
			{
               $('#id_delete_all_settings').html('<?php echo $language->get('text_loading_main'); ?>');
			},
			success: function(json) {
				$('#id_delete_all_settings').html(json);
				setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#id_delete_all_settings').html('error');
			}
		}); return false;" class="markbuttono" style=""><?php echo $url_delete_all_settings_text; ?></a>
</div>


<div id="id_delete_all_settings"></div>




</div>


<div id="tab-avatar">
 <table class="mynotable" style="margin-bottom:20px; background: white; vertical-align: center;">
  <tr>
     <td class="left"><?php echo $language->get('entry_avatar_dimension'); ?></td>
     <td class="left">
      <input type="text" name="ascp_settings[avatar_width]" value="<?php if (isset($ascp_settings['avatar_width'])) echo $ascp_settings['avatar_width']; ?>" size="3" />x
      <input type="text" name="ascp_settings[avatar_height]" value="<?php if (isset($ascp_settings['avatar_width'])) echo $ascp_settings['avatar_height']; ?>" size="3" />
     </td>
    </tr>


    <tr>
      <td><?php echo $language->get('entry_avatar_default'); ?></td>
      <td valign="top"><div class="image form-group" data-toggle="image">
      <?php if (SCP_VERSION > 1) { ?>
      <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
       <?php } ?>
      <img src="<?php echo $avatar_default; ?>" alt="" id="thumb" data-placeholder="<?php echo $no_image; ?>"/>
      <?php if (SCP_VERSION > 1) { ?>
      </a>
      <?php } ?>
        <input type="hidden" name="ascp_settings[avatar_default]" value="<?php  if (isset($ascp_settings['avatar_default'])) echo $ascp_settings['avatar_default']; ?>" id="image" />
        <br>
       <?php if (SCP_VERSION < 2) { ?>
	      <a onclick="image_upload('image', 'thumb');"><?php echo $language->get('text_browse'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $language->get('text_clear'); ?></a>
       <?php } ?>
        </div>

        </td>
    </tr>


    <tr>
      <td><?php echo $language->get('entry_avatar_admin'); ?></td>
      <td valign="top"><div class="image form-group" data-toggle="image">
      <?php if (SCP_VERSION > 1) { ?>
      <a href="" id="thumb-admin" data-toggle="image" class="img-thumbnail">
       <?php } ?>
      <img src="<?php echo $avatar_admin; ?>" alt="" id="thumb_admin" data-placeholder="<?php echo $no_image; ?>" />
      <?php if (SCP_VERSION > 1) { ?>
      </a>
      <?php } ?>
        <input type="hidden" name="ascp_settings[avatar_admin]" value="<?php  if (isset($ascp_settings['avatar_admin'])) echo $ascp_settings['avatar_admin']; ?>" id="image_admin" />
        <br>
       <?php if (SCP_VERSION < 2) { ?>
	      <a onclick="image_upload('image_admin', 'thumb_admin');"><?php echo $language->get('text_browse'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_admin').attr('src', '<?php echo $no_image; ?>'); $('#thumb_admin').attr('value', '');"><?php echo $language->get('text_clear'); ?></a>
       <?php } ?>
        </div>

        </td>
    </tr>


    <tr>
      <td><?php echo $language->get('entry_avatar_buyproduct'); ?></td>
      <td valign="top"><div class="image form-group" data-toggle="image">
      <?php if (SCP_VERSION > 1) { ?>
      <a href="" id="thumb-buyproduct" data-toggle="image" class="img-thumbnail">
       <?php } ?>
      <img src="<?php echo $avatar_buyproduct; ?>" alt="" id="thumb_buyproduct" data-placeholder="<?php echo $no_image; ?>" />
      <?php if (SCP_VERSION > 1) { ?>
      </a>
      <?php } ?>
        <input type="hidden" name="ascp_settings[avatar_buyproduct]" value="<?php  if (isset($ascp_settings['avatar_buyproduct'])) echo $ascp_settings['avatar_buyproduct']; ?>" id="image_buyproduct" />
        <br>
       <?php if (SCP_VERSION < 2) { ?>
       <a onclick="image_upload('image_buyproduct', 'thumb_buyproduct');"><?php echo $language->get('text_browse'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_buyproduct').attr('src', '<?php echo $no_image; ?>'); $('#image_buyproduct').attr('value', '');"><?php echo $language->get('text_clear'); ?></a></div></td>
        <?php } ?>
        </div>

        </td>
    </tr>


    <tr>
      <td><?php echo $language->get('entry_avatar_buy'); ?></td>
      <td valign="top"><div class="image form-group" data-toggle="image">
      <?php if (SCP_VERSION > 1) { ?>
      <a href="" id="thumb-buy" data-toggle="image" class="img-thumbnail">
       <?php } ?>
      <img src="<?php echo $avatar_buy; ?>" alt="" id="thumb_buy" data-placeholder="<?php echo $no_image; ?>" />
      <?php if (SCP_VERSION > 1) { ?>
      </a>
      <?php } ?>
         <input type="hidden" name="ascp_settings[avatar_buy]" value="<?php  if (isset($ascp_settings['avatar_buy'])) echo $ascp_settings['avatar_buy']; ?>" id="image_buy" />
       <br>
       <?php if (SCP_VERSION < 2) { ?>
        <a onclick="image_upload('image_buy', 'thumb_buy');"><?php echo $language->get('text_browse'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_buy').attr('src', '<?php echo $no_image; ?>'); $('#image_buy').attr('value', '');"><?php echo $language->get('text_clear'); ?></a></div></td>
        <?php } ?>
        </div>

        </td>
    </tr>



    <tr>
      <td><?php echo $language->get('entry_avatar_reg'); ?></td>
      <td valign="top"><div class="image form-group" data-toggle="image">
      <?php if (SCP_VERSION > 1) { ?>
      <a href="" id="thumb-reg" data-toggle="image" class="img-thumbnail">
       <?php } ?>
      <img src="<?php echo $avatar_reg; ?>" alt="" id="thumb_reg" data-placeholder="<?php echo $no_image; ?>" />
      <?php if (SCP_VERSION > 1) { ?>
      </a>
      <?php } ?>
         <input type="hidden" name="ascp_settings[avatar_reg]" value="<?php  if (isset($ascp_settings['avatar_reg'])) echo $ascp_settings['avatar_reg']; ?>" id="image_reg" />
       <br>
       <?php if (SCP_VERSION < 2) { ?>
        <a onclick="image_upload('image_reg', 'thumb_reg');"><?php echo $language->get('text_browse'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_reg').attr('src', '<?php echo $no_image; ?>'); $('#image_reg').attr('value', '');"><?php echo $language->get('text_clear'); ?></a></div></td>
        <?php } ?>
        </div>

        </td>
    </tr>
   </table>
</div>





<div id="tab-install">

<?php
//echo $language->get('text_upgrade_5_6');
?>
<div style="margin-bottom: 5px;">
    <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_create; ?>',
			dataType: 'html',
			beforeSend: function()
			{
               $('#create_tables').html('<?php echo $language->get('text_loading_main'); ?>');
			},
			success: function(json) {
				$('#create_tables').html(json);
				setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#create_tables').html('error');
			}
		}); return false;" class="markbuttono" style=""><?php echo $url_create_text; ?></a>
</div>
<div>
    <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_delete; ?>',
			dataType: 'html',
			beforeSend: function()
			{
               $('#create_tables').html('<?php echo $language->get('text_loading_main'); ?>');
			},
			success: function(json) {
				$('#create_tables').html(json);
				setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#create_tables').html('error');
			}
		}); return false;" class="markbuttono" style=""><?php echo $url_delete_text; ?></a>
</div>
<div class="margintop5px">
<a href="<?php echo $url_adapter; ?>" class="markbutton"  style="clear:both;"><div><img src="view/image/agootemplates-options-m.png"  style="" ></div>
<div style=""><?php echo $language->get('entry_adapter'); ?></div></a>
</div>

<div id="create_tables" style="color: green; font-weight: bold;"></div>
<?php if (isset($text_update) && $text_update!='' ) { ?>
<div style="font-size: 18px; color: red;"><?php echo $text_update; ?></div>
<?php } ?>

</div>


 </div>

 </form>
</div>
<script type="text/javascript">
	 form_submit = function() {
		$('#form').submit();
		return false;
	}
	$('.blog_save').bind('click', form_submit);
</script>

<script type="text/javascript">
	$('#tabs a').tabs();
</script>

<script type="text/javascript">
function image_upload(field, thumb) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: '<?php echo $language->get('text_image_manager'); ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
</script>


<script type="text/javascript">

var array_type_row = Array();
array_type_row.push(0);
<?php
 foreach ($ascp_settings['comment_type'] as $indx => $comment_type) {
?>
array_type_row.push(<?php echo $indx; ?>);
<?php
}
?>

var comment_type_row = <?php echo $comment_type_row + 1; ?>;

function addCommentType() {
	var aindex = -1;
	for(i = 0; i < array_type_row.length; i++) {
	 flg = jQuery.inArray(i, array_type_row);
	 if (flg == -1) {
	  aindex = i;
	 }
	}
	if (aindex == -1) {
	  aindex = array_type_row.length;
	}
	comment_type_row = aindex;
	array_type_row.push(aindex);

    html  = '<tbody id="comment_type_row' + comment_type_row + '">';
	html += '  <tr>';
    html += '  <td class="left">';
	html += ' 	<input type="text" name="ascp_settings[comment_type]['+ comment_type_row +'][type_id]" value="'+ comment_type_row +'" size="3">';
    html += '  </td>';

 	html += '  <td class="right">';
 	<?php foreach ($languages as $lang) { ?>

	html += '	<div style="margin-bottom: 3px;">';
	html += '		<input type="text" name="ascp_settings[comment_type]['+ comment_type_row +'][title][<?php echo $lang['language_id']; ?>]" value="" style="width: 300px;"><img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" >';
	html += '	</div>';

	<?php } ?>
    html += '  </td>';
    html += '  <td class="left"><a onclick="$(\'#comment_type_row'+comment_type_row+'\').remove(); array_type_row.remove(comment_type_row);" class="markbutton button_purple nohref"><?php echo $button_remove; ?></a></td>';




	html += '  </tr>';
	html += '</tbody>';

	$('#comment_types tfoot').before(html);

	comment_type_row++;
}
</script>






</div>

</div>
</div>

<?php echo $footer; ?>
</div>