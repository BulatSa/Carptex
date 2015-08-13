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
   <a href="#" class="mbutton blog_save"><?php echo $button_save." (".count($modules).")"; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton nohref"><?php echo $button_cancel; ?></a>
</div>

<div style="clear: both; line-height: 1px; font-size: 1px;"></div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<script type="text/javascript">
function delayer(){
    window.location = 'index.php?route=module/blog/schemes&token=<?php echo $token; ?>';
}
</script>





  <div id="tab-general">


    <?php
     /*
       print_r("<PRE>");
       print_r($modules);
        print_r($ascp_widgets);
        print_r("</PRE>");
       */
        ?>

   <table class="mytable" id="module" style="width: 100%; ">
     <thead>
      <tr>
       <td class="left" style="min-width: 20%;"><?php echo $entry_layout; ?></td>
       <td class="left"><?php echo $entry_position; ?></td>
       <td class="left"><?php echo $entry_status; ?></td>
       <td class="right"> <?php echo $language->get('type_list'); ?></td>
       <td class="right"><?php echo $entry_sort_order; ?></td>
       <td style="width: 200px;"><?php echo $language->get('text_action'); ?></td>
      </tr>
     </thead>
        <?php
          $module_row = 0;
        ?>
        <?php foreach ($modules as $module)
        {
         while (!isset($modules[$module_row])) {          $module_row++;
         }
        ?>


         <tr class="module-row<?php echo $module_row; ?>" >

   	           <td style="border-top: 1px solid #DDEFD9;"><?php echo $language->get('entry_url_schemes'); ?>
                <?php echo $language->get('entry_url_template'); ?>
				<select name="blog_module[<?php echo $module_row; ?>][url_template]">
                  <?php if (isset($module['url_template']) && $module['url_template']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>



   	           </td>
	            <td class="left" colspan="5" style="border-top: 1px solid #DDEFD9;">
	         	 <input type="text" name="blog_module[<?php echo $module_row; ?>][url]" value="<?php if (isset($module['url'])) echo trim($module['url']); ?>" style="width:100%" />
	           </td>
         </tr>
           <tr class="module-row<?php echo $module_row; ?>" style="display: none;" >
           <td>&nbsp;</td>
           </tr>
          <tr class="module-row<?php echo $module_row; ?>">








            <td class="left"><!-- <?php echo $module_row; ?>&nbsp; -->

            <!--
            <select name="blog_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
                -->

				<div>
				<div class="scrollbox" style="width: auto; height: 100px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($layouts as $layout) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php
                    if (isset($module['layout_id']) && !is_array($module['layout_id'])) {                     	$module_array = Array();
                     	$module_array[] = $module['layout_id'];
                     	$module['layout_id'] = $module_array;
                    } else {                    $module_array[] = Array();
                    }

                    if ((isset($module['layout_id']) && is_array($module['layout_id']) ) && in_array($layout['layout_id'], $module['layout_id'])) { ?>
                    <input type="checkbox" name="blog_module[<?php echo $module_row; ?>][layout_id][]" value="<?php echo $layout['layout_id']; ?>" checked="checked" />
                    <?php echo $layout['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="blog_module[<?php echo $module_row; ?>][layout_id][]" value="<?php echo $layout['layout_id']; ?>" />
                    <?php echo $layout['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>

                <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true); return false;" class="nohref"><?php echo $language->get('text_select_all'); ?></a> / <br><a onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="nohref"><?php echo $language->get('text_unselect_all'); ?></a>

                </div>


                <div style="color: #777; font-size: 12px; line-height: 14px; ">
                <?php foreach ($layouts as $layout) { ?>
                    <?php
                    if (isset($module['layout_id']) && !is_array($module['layout_id'])) {
                     $module_array = Array();
                     $module_array[] = $module['layout_id'];
                     $module['layout_id'] = $module_array;
                    } else {                    	$module_array[] = Array();
                    }

                    if ((isset($module['layout_id']) && is_array($module['layout_id']) ) && in_array($layout['layout_id'], $module['layout_id'])) { ?>
                     <?php echo $layout['name']."<br>"; ?>
                    <?php } ?>



                <?php } ?>
                </div>

              </td>

            <td class="left"><select name="blog_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>


            <td class="left"><select name="blog_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>

              <td class="left">
               <select name="blog_module[<?php echo $module_row; ?>][what]">
           	<?php
				foreach ($ascp_widgets as $num =>$list) {
                   // echo $module['what']."->".$list['type']."<br>";
					if (isset($list['title_list_latest'][ $config->get('config_language_id')]) &&  $list['title_list_latest'][ $config->get('config_language_id')]!='')
					{
				     $title=$list['title_list_latest'][ $config->get('config_language_id')];
					}
					else
					{
					 $title="List-".$num;
					}


		    ?>
                <?php if ($module['what']==$num) { ?>
                <option value="<?php echo $num; ?>" selected="selected"><?php echo $title; ?></option>
                <?php } else { ?>
                <option value="<?php echo $num; ?>"><?php echo $title; ?></option>
                <?php } ?>

              <?php
              }
              ?>

              </select>
              </td>


            <td class="right"><input type="text" name="blog_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left">
            <?php if ($module['what']!='hook' ) {
            $button_class ='mbutton button_purple';
			}
            else
            {           	 $button_class ='markbuttono';
           	}             ?>
           <div style="float:left; width: 100px;">
             <a onclick="$('.module-row<?php echo $module_row; ?>').remove();" class="<?php echo $button_class; ?> nohref"><?php echo $button_remove; ?></a>
           </div>

             <?php if ($button_class =='markbuttono') {

             ?>
             <div style="float:left;  width: 50%;">
             <?php
             //echo $language->get('hook_not_delete');
             ?>
             </div>
             <?php
            }
            ?>


          </td>
         </tr>

        <?php
         $module_row++;
        }
        ?>
        <tfoot>
          <tr>
            <td colspan="5"></td>
            <td class="left"><a onclick="addModule();" class="markbutton nohref"><?php echo $button_add_module; ?></a></td>
          </tr>
        </tfoot>
      </table>

    </div>


 <div style="clear: both; line-height: 1px; font-size: 1px;"></div>
    <div class="buttons right" style="margin-top: 20px;float: right;"><a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton nohref"><?php echo $button_cancel; ?></a></div>
</form>
  </div>
</div>


<script type="text/javascript">
var amodule_row = Array();

<?php
 foreach ($modules as $indx => $module) {
?>
amodule_row.push(<?php echo $indx; ?>);
<?php
}
?>
var module_row = <?php echo $module_row; ?>;

function addModule() {	var aindex = -1;
	for(i=0; i<amodule_row.length; i++) {	 flg = jQuery.inArray(i, amodule_row);
	 if (flg == -1) {	  aindex = i;
	 }
	}
	if (aindex == -1) {
	  aindex = amodule_row.length;
	}
	module_row = aindex;
	amodule_row.push(aindex);

	html  = '<tbody class="module-row' + module_row + '">';

    html += '       <tr>';
    html += '       <td><?php echo $language->get('entry_url_schemes'); ?>';
    html += '            <?php echo $language->get('entry_url_template'); ?>';
	html += '			<select name="blog_module[' + module_row + '][url_template]">';
    html += '              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
    html += '              <option value="1"><?php echo $text_enabled; ?></option>';
    html += '            </select>';
    html += '		</td>';
    html += '        <td class="left" colspan="5">';
    html += '     	 <input type="text" name="blog_module[' + module_row + '][url]" value="" style="width:100%" />';
    html += '       </td>';
    html += '       </tr>';

	html += '  <tr>';




	html += '    <td class="left">';



	html += '<div class="scrollbox" style="width: auto; height: 100px;">';
    html += '<?php $class = 'odd'; ?>';
             <?php foreach ($layouts as $layout) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
   html += '               <div class="<?php echo $class; ?>">';
   html += '               <input type="checkbox" name="blog_module[' + module_row + '][layout_id][]" value="<?php echo $layout['layout_id']; ?>" />';
   html += '                 <?php echo $layout['name']; ?>';
   html += '               </div>';
             <?php } ?>
   html += '             </div>';
   html += '             <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', true);" class="nohref"><?php echo $language->get('text_select_all'); ?></a> / <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', false);" class="nohref"><?php echo $language->get('text_unselect_all'); ?></a></td>';






	html += '    </td>';
	html += '    <td class="left"><select name="blog_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="blog_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';


	html += '    <td class="left"><select name="blog_module[' + module_row + '][what]">';

    <?php
	if (count($ascp_widgets)>0) {
  	 foreach ($ascp_widgets as $num =>$list) {
					if (isset($list['title_list_latest'][ $config->get('config_language_id')]) &&  $list['title_list_latest'][ $config->get('config_language_id')]!='')
					{
				     $title=$list['title_list_latest'][ $config->get('config_language_id')];
					}
					else
					{
					 $title="List-".$num;
					}

		    ?>
	html += '        <option value="<?php echo $num; ?>"><?php echo $title; ?></option>';
	<?php
	 }
	}
	 ?>


	html += '    </select></td>';





	html += '    <td class="right"><input type="text" name="blog_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'.module-row' + module_row + '\').remove();" class="mbutton button_purple nohref"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#module tfoot').before(html);

	module_row++;
}
</script>
	<script type="text/javascript">

	 form_submit = function() {
		$('#form').submit();
		return false;
	}
	$('.blog_save').bind('click', form_submit);
	</script>

</div>
</div>
</div>

<?php echo $footer; ?>
</div>