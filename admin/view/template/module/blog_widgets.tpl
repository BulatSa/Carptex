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

<div style="margin-right:5px; float:right;">
 <a href="#" class="mbutton blog_save_new"><?php echo $button_save; ?></a><!--<a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a>--><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton"><?php echo $button_cancel; ?></a>

</div>



<div style=" clear: both; line-height: 1px; font-size: 1px;"></div>



<style>
.help {
display: none;
}
</style>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

<div id="debug"></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<script type="text/javascript">
tab = 'amytabs1';

function delayer(){
  window.location = 'index.php?route=module/blog/widgets&tab='+tab+'&token=<?php echo $token; ?>';
}

var myEditor = new Array();

</script>
<?php  if (count($ascp_widgets)>0) { ?>
<div id="widgets_loading" style="width: 100%; height: 24px; line-height: 24px;  background-color: #EEE; margin-bottom: 5px;">&nbsp;</div>
<?php } ?>

<div id="tab-list">
	<div id="lists">
		<div id="mytabs" class="vtabs" style="padding-top: 0px;"><a href="#mytabs_add" style="color: #FFF; background: green; text-align: right;  "><span style="text-align: center; padding: 0px; margin: 0px; font-size: 21px; background-color: #2EC22E; border: none; line-height: 21px; height: 21px; width: 21px; color: #FFF; ">+</span><?php echo $language->get('text_add'); ?></a></div>


<div id="mytabs_add" >
<div style="">
<div style="float: left;">
 <?php  echo $language->get('type_list');   ?>

         <select id="ascp_widgets-what"  name="ascp_widgets-what">
                <?php foreach ($widget_list as $w_l) { ?>
                 <option value="<?php echo $w_l; ?>"><?php echo $language->get('text_widget_'.$w_l); ?></option>
                 <?php } ?>
         </select>
         </div>
      <div class="buttons" style="margin-left: 10px; float: left;"><a onclick="
      ascp_widgets_num++;
      type_what = $('#ascp_widgets-what :selected').val();
      this_block_html = $('#mytabs_add').html();
 		$.ajax({
					url: 'index.php?route=module/blog/ajax_list&token=<?php echo $token; ?>',
					type: 'post',
					async: true,
					data: { type: type_what, num: ascp_widgets_num },
					dataType: 'html',
					beforeSend: function()
					{
                      $('#mytabs_add').html('<?php echo $language->get('text_loading'); ?>');
					},
					success: function(html) {					$('#mytabs_add').html(this_block_html);
						if (html) {
							$('#mytabs').append('<a href=\'#mytabs' + ascp_widgets_num + '\' id=\'amytabs'+ascp_widgets_num+'\'>List-' + ascp_widgets_num + '<\/a>');
							$('#lists').append('<div id=\'mytabs'+ascp_widgets_num+'\'>'+html+'<\/div>');
							$('#mytabs a:visible').tabs();
							$('#amytabs' + ascp_widgets_num).click();
                             template_auto();

							$('.blog_save_new').html('<?php echo $button_save; ?> ('+ $('.hremove[value!=\'remove\']').length +')' );
						}
						$('.mbutton').removeClass('loader');


					}
				});


      return false; " class="mbutton"><?php echo $language->get('button_add_list'); ?></a>
      </div>

 </div>

  </div>

	</div>

	<script type="text/javascript">

	 form_submit = function() {

		$('#form').submit();
		return false;
	}

    </script>


<script>


form_submit_new = function() {

    $('#widgets_loading').html('');
    $('#widgets_loading').show();
    var seq = new Array();
    for (i=0; i<$('.ascp_widgets_form').length; i++) {
        seq.push(i);
    }

    finishCallback = function(){}

    function go(){

        if(seq.length) {          num = seq.shift();

			for(name in CKEDITOR.instances)
			{
				    CKEDITOR.instances[name].destroy()
			}

          kf = $('.ascp_widgets_form')[num];

          //console.log($(kf).html());

            $.ajax({
                url: 'index.php?route=module/blog/ascp_widgets_save&num='+num+'&token=<?php echo $token; ?>',
                type: 'post',
					data: $(kf).serialize(),
					dataType: 'html',
					async: true,
					beforeSend: function() {

					 $('a.mbutton, a.mbuttonr').addClass('loader');
					 $('.blog_save_new').unbind('click');

					},
					success: function(html) {
						old_html = $('#debug').html();
						$('#debug').html(old_html + html);
						if (seq.length==0) {
						 $('a.mbutton, a.mbuttonr').removeClass('loader');
						 $('.blog_save_new').bind('click', form_submit_new);
						 $('#widgets_loading').hide();
						 delayer();
						}
					},
                	complete: function(){					loading_recent = Math.round((100*($('.ascp_widgets_form').length - seq.length))/$('.ascp_widgets_form').length);

                        $('#widgets_loading').html('<div style=\"height: 24px; line-height: 24px; text-align: center; width:'+loading_recent+'%; color: white;background-color: orange;\">'+loading_recent+'%<\/div>');

                    	go();
                	}
            });
        }

        else {finishCallback()}
    }

    go();
    return false;

}

</script>



<script type="text/javascript">

	 form_submit_new_ = function() {

		$('.ascp_widgets_form').each(function(index, value) {

		$.ajax({
					url: 'index.php?route=module/blog/ascp_widgets_save&index='+index+'&all='+$('.ascp_widgets_form').length+'&token=<?php echo $token; ?>',
					type: 'post',
					async: true,
					data: $(this).serialize(),
					dataType: 'html',
					beforeSend: function() {
					 $('a.mbutton, a.mbuttonr').addClass('loader');
					 $('.blog_save_new').unbind('click');

					},
					success: function(html) {						old_html = $('#debug').html();
						$('#debug').html(old_html + html);
						$('a.mbutton, a.mbuttonr').removeClass('loader');
						$('.blog_save_new').bind('click', form_submit_new);
					},
					complete: function(){

                	}
				});





		});

		return false;
	}

    </script>




<?php
if (count($ascp_widgets)>0)
{
	reset($ascp_widgets);


	if (isset($tab)) {
	 $first_key = '#'.$tab;
	} else {	 $first_key = '#amytabs'.key($ascp_widgets);
	}



    $zamena = array ("`", "'", '"', "<", ">");
	$ki=0;
	foreach ($ascp_widgets as $num =>$list) {

	$ki++;
	$slist = serialize($list);

	if (isset($list['title_list_latest'][ $config->get('config_language_id')]) &&  $list['title_list_latest'][ $config->get('config_language_id')]!='')
	{
     $title= str_replace($zamena,"", $list['title_list_latest'][ $config->get('config_language_id')]);	}
	else
	{	 $title="List-".$num;
	}


	?>
	<script type="text/javascript">

	var ascp_widgets_num=<?php echo $num; ?>;
	$('#mytabs').append('<a href=\"#mytabs<?php echo $num; ?>\" id=\"amytabs<?php echo $num; ?>\" class=\"tableft tabclick\"><?php echo $title; ?><\/a>');

    var progress_num = 0;
    var allcount = <?php echo (count($ascp_widgets)); ?>;
		$.ajax({
					url: 'index.php?route=module/blog/ajax_list&token=<?php echo $token; ?>',
					type: 'post',
					async: true,
					data: { list: '<?php echo base64_encode($slist); ?>', num: '<?php echo $num; ?>' },
					dataType: 'html',
					beforeSend: function() {
					 $('a.mbutton').addClass('loader');
					 $('.blog_save').unbind('click');
					// $('.blog_save_new').unbind('click');

					},
					success: function(html) {
						if (html) {							$('#lists').append('<div id=\"mytabs<?php echo $num; ?>\" class=\"tabcontent\">'+html+'<\/div>');

							$('#mytabs a').tabs();

                          	$("#amytabs<?php echo $num; ?>").on("click", function() {
							tab = $(this).prop('id');
							});

							$('<?php echo $first_key; ?>').click();
                             template_auto();

							$('.blog_save_new').html('<?php echo $button_save; ?> ('+$('.hremove[value!=\'remove\']').length+')' );

						}
						<?php if (count($ascp_widgets)<=$ki) {  ?>
						$('a.mbutton').removeClass('loader');
						$('.blog_save').bind('click', form_submit);
						//$('.blog_save_new').bind('click', form_submit_new);
						$('#widgets_loading').hide();
						<?php } ?>

						<?php
						$loading_recent = round((100*$num)/count($ascp_widgets));
						?>
						progress_num++;
						loading_recent = Math.round((100*progress_num)/allcount);

                        $('#widgets_loading').html('<div style=\"height: 24px; line-height: 24px; text-align: center; width:'+loading_recent+'%; color: white;background-color: #00C600;\">'+loading_recent+'%<\/div>');



					}
				});

		</script>
	<?php

    }

	}
	else
	{     ?>
     	<script type="text/javascript">
	var ascp_widgets_num=0;
        </script>
     <?php
	} ?>

</div>
</div>

    </form>
     <div style="clear: both; line-height: 1px; font-size: 1px;"></div>
      <div class="buttons right" style="margin-top: 20px;float: right;">
    <a href="#" class="mbutton blog_save_new"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton"><?php echo $button_cancel; ?></a>

      </div>

  </div>

  </div>
</div>

<script>
template_auto = function() {	$('.template').each(function() {

		var e = this;
		var iname = $(e).prop('name');
		var path  = $(e).nextAll('input:first').prop('value');

		$(e).autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=module/blog/autocomplete_template&path='+path+'&token=<?php echo $token; ?>',
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item.name + ' -> '+ path,
								value: item.name
							}
						}));
					}
				});

			},
			'select': function(event, ui) {

         	<?php
         	if (SCP_VERSION < 2) {
         	?>
         	 var veli = ui.item.value;
         	<?php
         	} else {
         	?>
         		var veli = event['value'];
         	<?php
         	}
         	?>
			$('input[name=\''+ iname +'\']').val(veli);
			return false;
			}
		});

	});
}
</script>



<script type="text/javascript">
    $('#mytabs a').tabs();
	$('.blog_save').bind('click', form_submit);
    $('.blog_save_new').bind('click', form_submit_new);

 </script>


</div>
</div>
<?php echo $footer; ?>
</div>