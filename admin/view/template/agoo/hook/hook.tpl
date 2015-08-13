<?php
foreach ($ascp_widgets as $list_num=>$list) {
?>
<div id="list<?php echo $list_num;?>"  style="padding-left: 200px;">
<form id="form<?php echo $list_num;?>" class="ascp_widgets_form">

 <input type="hidden" name="ascp_widgets[<?php echo $list_num; ?>][remove]" class="hremove" value="">

  <input type="hidden" name="ascp_widgets[<?php echo $list_num; ?>][type]" value="<?php if (isset($list['type'])) echo $list['type']; else echo 'blogs'; ?>">


<table style="width: 90%;">
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
			<div style="float: left;">
				<input type="text" name="ascp_widgets[<?php echo $list_num; ?>][title_list_latest][<?php echo $lang['language_id']; ?>]" value="<?php if (isset($list['title_list_latest'][$lang['language_id']])) echo $list['title_list_latest'][$lang['language_id']]; ?>" size="60" />
				</div>
				<div style="float: left; margin-left: 3px;">
				<img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" />
				</div>
			</td>

	</tr>
   <?php } ?>

    <tr>
    <td class="left"><?php echo $language->get('entry_id_widget'); ?></td>
     <td class="left">
 		<?php echo $id;?>
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



</table>
</form>
</div>
<?php }  ?>