<div class="row-fluid">
  <div class="span8">
    <div class="box-heading">
      <h1>Smush.it</h1>
    </div>
    <div class="box-content">
        <div class="box-heading" style="margin-bottom:15px;">
			<div class="box-minibox">Smushed Images<div class="number" id="smushedNumber"><?php echo !empty($smushit_data['smushed_images_count']) ? $smushit_data['smushed_images_count'] : 0;?></div></div>         
			<div class="box-minibox">Total Images<div class="number" id="totalImages"><?php echo !empty($smushit_data['total_images']) ? $smushit_data['total_images'] : 'N/A';?></div></div>         
			<div class="box-minibox">Kilobytes saved<div class="number" id="kbSaved"><?php echo !empty($smushit_data['kb_saved']) ? $smushit_data['kb_saved'] : 0;?> KB</div></div>         
			<div class="box-minibox">Last smushed<div class="number" id="lastSmushTimestamp"><?php echo !empty($smushit_data['last_smush_timestamp']) ? date('D, j M Y H:i:s', $smushit_data['last_smush_timestamp']) : 'N/A';?></div></div>         
        </div>
         <div class="smushingResult"></div>
        <button type="button" class="btn btn-large btn-primary smushItButton">Smush My Cached Images</button>
        <div class="empty-smush-div"></div>
        <div class="smush-log">
            <div class="smush-log-entries">
            </div>
        </div>
    </div>
  </div>
  <div class="span4">
    <div class="box-heading">
      <h1><i class="icon-info-sign"></i>What is Smush.it?</h1>
    </div>
    <div class="box-content" style="min-height:100px; line-height:20px;"> 
    <p>Smush.it is an image optimization service by <a target="_blank" href="http://developer.yahoo.com/yslow/smushit/">Yahoo!</a></p>
    <p>Smush.it uses optimization techniques specific to image format to remove unnecessary bytes from image files. It is a "lossless" tool, which means it optimizes the images without changing their look or visual quality. After Smush.it runs on a web page it reports how many bytes would be saved by optimizing the page's images. </p>
    <p>You can chech the Smush.it <a target="_blank" href="http://developer.yahoo.com/yslow/smushit/faq.html">Frequently Asked Questions</a> or take a look at <a target="_blank" href="http://www.smushit.com/ysmush.it/">their the hosted service</a>.</p>
    </div>
    <div class="box-heading">
      <h1>Options</h1>
    </div>
    <div class="box-content" style="min-height: 150px;">
        <table class="form">
          <tr>
            <td style="vertical-align:top;">Smush On-Demand<span class="help">Requires activated Page Cache. If enabled, your images will be smushed while your page cache is created. Your first-time page load when the cache is being created may be slower, since the images will be compressed on-the-fly by the SmushIt servers.</span></td>
            <td style="vertical-align:top;">
            <select name="Nitro[SmushIt][OnDemand]">
                <option value="yes" <?php echo( (!empty($data['Nitro']['SmushIt']['OnDemand']) && $data['Nitro']['SmushIt']['OnDemand'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
                <option value="no" <?php echo (empty($data['Nitro']['SmushIt']['OnDemand']) || $data['Nitro']['SmushIt']['OnDemand'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            </select>
            </td>
          </tr>
        </table>
    </div>
  </div>
</div>


<script>
var smush_interval;
var refreshSmushAjax;

var smushLog = $('.smush-log-entries');
	smushLog.parent().hide();

var refresh_smush_data = function() {
	if (typeof refreshSmushAjax != 'undefined') refreshSmushAjax.abort();
	refreshSmushAjax = $.ajax({
		url: 'index.php?route=tool/nitro/getsmushprogress&&token=<?php echo $_GET['token'] ?>',
		dataType: 'json',
		cache: false,
		success: function(data) {
			var extra_html = '';
			for (x in data.smushed_files) {
				extra_html += data.smushed_files[x].file + ' <b>(' + data.smushed_files[x].percent + '% saved)</b><br><br>';
			}
			smushLog.parent().show();
			smushLog.append(extra_html).slideDown();
			$('.smush-log').css({width: $('.empty-smush-div').width() + 'px'}).animate({
				scrollTop: smushLog.outerHeight()
			}, 1000);
			
			$('#smushedNumber').html(data.smushed_images_count);
			$('#kbSaved').html(data.kb_saved);
			$('#lastSmushTimestamp').html(formatTimestamp(data.last_smush_timestamp));
		}
	});
}

var formatTimestamp = function (timestamp) {
	if (timestamp == 0) return 'N/A';
	
	var weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
	var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var dateObj = new Date(timestamp * 1000);
	return weekDays[dateObj.getDay()] + ', ' + dateObj.getDate() + ' ' + months[dateObj.getMonth()] + ' ' + dateObj.getFullYear() + ' ' + dateObj.getHours() + ':' + dateObj.getMinutes() + ':' + dateObj.getSeconds();
}

$('.smushItButton').click(function() {
	smush_interval = setInterval(refresh_smush_data, 2000);
	
	if ($(this).hasClass('cancel-smush')) {
		$.ajax({
			url: 'index.php?route=tool/nitro/stopsmushing&&token=<?php echo $_GET['token'] ?>',
			success: function() {
				$('.smushItButton').text('Canceling...');
			}
		});
	} else {
		$(this).text('Cancel').addClass('btn-inverse').addClass('cancel-smush');
		$('.smushingResult').html('<div class="smushingDiv"><img src="../catalog/view/theme/default/image/loading.gif" /> Smushing...</div>').load('index.php?route=tool/nitro/smushimages&&token=<?php echo $_GET['token'] ?>', function() {
			clearInterval(smush_interval);
			refresh_smush_data();
			$('.smushItButton').text('Smush My Cached Images').removeClass('btn-inverse').removeClass('cancel-smush');
		});
	}
});
</script>

<style>
.smushingDiv {
	padding: 10px;
}
</style>