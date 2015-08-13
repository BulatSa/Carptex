<div class="row-fluid">
	<div class="span7">
    <div class="box-heading"><h1>Amazon CloudFront/S3 CDN Service</h1></div>
    <table class="form cdnpanetable">
      <tr>
        <td>Amazon CloudFront/S3 CDN Service</td>
        <td>
        <select name="Nitro[CDNAmazon][Enabled]" class="NitroCDNAmazon">
            <option value="no" <?php echo (empty($data['Nitro']['CDNAmazon']['Enabled']) || $data['Nitro']['CDNAmazon']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['CDNAmazon']['Enabled']) && $data['Nitro']['CDNAmazon']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled</option>
        </select>
        <div class="cdn-error amazon-error">You cannot use Amazon CDN with the Generic CDN or Rackspace CDN. Please enable only one.</div>
        </td>
      </tr>
      <tr class="CDNAmazon-tabbable-parent">
        <td>Amazon CloudFront/S3 CDN HTTP URL<span class="help">This is the non-SSL URL of your Amazon CloudFront/S3 CDN server</span></td>
        <td>
            <input type="text" name="Nitro[CDNAmazon][HttpUrl]" value="<?php echo(!empty($data['Nitro']['CDNAmazon']['HttpUrl'])) ? $data['Nitro']['CDNAmazon']['HttpUrl'] : ''?>" />
        </td>
      </tr>
      <tr class="CDNAmazon-tabbable-parent">
        <td>Amazon CloudFront/S3 CDN HTTPS URL<span class="help">This is the SSL URL of your Amazon CloudFront/S3 CDN server</span></td>
        <td>
            <input type="text" name="Nitro[CDNAmazon][HttpsUrl]" value="<?php echo(!empty($data['Nitro']['CDNAmazon']['HttpsUrl'])) ? $data['Nitro']['CDNAmazon']['HttpsUrl'] : ''?>" />
        </td>
      </tr>
      <tr class="CDNAmazon-tabbable-parent">
        <td>Serve Images from this CDN<span class="help">Your images will be served from your store web server if you have not synced them with the CDN via the uploader on the right.</span></td>
        <td>
        <select name="Nitro[CDNAmazon][ServeImages]">
            <option value="no" <?php echo (empty($data['Nitro']['CDNAmazon']['ServeImages']) || $data['Nitro']['CDNAmazon']['ServeImages'] == 'no') ? 'selected=selected' : ''?>>No</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['CDNAmazon']['ServeImages']) && $data['Nitro']['CDNAmazon']['ServeImages'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
        </select>
        </td>
      </tr>
      <tr class="CDNAmazon-tabbable-parent">
        <td>Serve CSS from this CDN<span class="help">Your CSS files will be served from your store web server if you have not synced them with the CDN via the uploader on the right.</span></td>
        <td>
        <select name="Nitro[CDNAmazon][ServeCSS]">
            <option value="no" <?php echo (empty($data['Nitro']['CDNAmazon']['ServeCSS']) || $data['Nitro']['CDNAmazon']['ServeCSS'] == 'no') ? 'selected=selected' : ''?>>No</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['CDNAmazon']['ServeCSS']) && $data['Nitro']['CDNAmazon']['ServeCSS'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
        </select>
        </td>
      </tr>
      <tr class="CDNAmazon-tabbable-parent">
        <td>Serve JavaScript from this CDN<span class="help">Your JavaScript files will be served from your store web server if you have not synced them with the CDN via the uploader on the right.</span></td>
        <td>
        <select name="Nitro[CDNAmazon][ServeJavaScript]">
            <option value="no" <?php echo (empty($data['Nitro']['CDNAmazon']['ServeJavaScript']) || $data['Nitro']['CDNAmazon']['ServeJavaScript'] == 'no') ? 'selected=selected' : ''?>>No</option>
            <option value="yes" <?php echo( (!empty($data['Nitro']['CDNAmazon']['ServeJavaScript']) && $data['Nitro']['CDNAmazon']['ServeJavaScript'] == 'yes')) ? 'selected=selected' : ''?>>Yes</option>
        </select>
        </td>
      </tr>
    </table>
    </div>
    <div class="span5">
    	<div class="CDNAmazon-tabbable-parent">
            <div class="box-heading"><h1><i class="icon-globe"></i>Synchronize with Amazon CloudFront/S3 CDN</h1></div>
            <div class="box-content" style="min-height:100px; line-height:20px;">
            	<div class="amazon-alert-info alert alert-info"><?php echo !empty($data['Nitro']['CDNAmazon']['LastUpload']) ? 'Last synchronization was on ' . $data['Nitro']['CDNAmazon']['LastUpload'] : 'No synchornization has been carried out yet.'; ?></div>
                <input type="hidden" name="Nitro[CDNAmazon][LastUpload]" value="<?php echo !empty($data['Nitro']['CDNAmazon']['LastUpload']) ? $data['Nitro']['CDNAmazon']['LastUpload'] : ''; ?>" />
            	<div class="amazon-alert alert alert-error"></div>
                <div class="amazon-alert alert alert-success"></div>
                <table class="form">
                  <tr>
                    <td>Access Key ID<span class="help">Your Amazon S3 Access Key ID. Obtained from the <a target="_blank" href="https://console.aws.amazon.com/iam/#users">Amazon IAM Console</a></span></td>
                    <td>
                    <input type="text" name="Nitro[CDNAmazon][AccessKeyID]" value="<?php echo !empty($data['Nitro']['CDNAmazon']['AccessKeyID']) ? $data['Nitro']['CDNAmazon']['AccessKeyID'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Secret Access Key<span class="help">Your Amazon S3 Secret Access Key. Obtained along with your Access Key ID.</span></td>
                    <td>
                    <input type="text" name="Nitro[CDNAmazon][SecretAccessKey]" value="<?php echo !empty($data['Nitro']['CDNAmazon']['SecretAccessKey']) ? $data['Nitro']['CDNAmazon']['SecretAccessKey'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td>Bucket Name<span class="help">Your Amazon S3 bucket name. It should already exist and be linked with your CloudFront URL.</span></td>
                    <td>
                    <input type="text" name="Nitro[CDNAmazon][Bucket]" value="<?php echo !empty($data['Nitro']['CDNAmazon']['Bucket']) ? $data['Nitro']['CDNAmazon']['Bucket'] : ''; ?>" />
                    </td>
                  </tr>
                  <tr>
                  	<td colspan="2">
                    	<label class="amazon-upload-label"><input type="checkbox" value="1" name="Nitro[CDNAmazon][SyncImages]" <?php if (!empty($data['Nitro']['CDNAmazon']['SyncImages'])) echo 'checked="checked"'; ?>/> Upload all local images to Amazon CloudFront/S3 CDN</label>
                        <label class="amazon-upload-label"><input type="checkbox" value="1" name="Nitro[CDNAmazon][SyncCSS]" <?php if (!empty($data['Nitro']['CDNAmazon']['SyncCSS'])) echo 'checked="checked"'; ?>/> Upload all local CSS files to Amazon CloudFront/S3 CDN</label>
                        <label class="amazon-upload-label"><input type="checkbox" value="1" name="Nitro[CDNAmazon][SyncJavaScript]" <?php if (!empty($data['Nitro']['CDNAmazon']['SyncJavaScript'])) echo 'checked="checked"'; ?>/> Upload all local JavaScript files to Amazon CloudFront/S3 CDN</label>
                    </td>
                  </tr>
                  <tr>
                  	<td colspan="2">
                    	<a class="amazon-upload btn btn-success"><i class="icon-circle-arrow-up icon-white"></i> <span class="amazon-button-text">Upload to CDN Server</span></a>
                        <a class="amazon-cancel btn btn-inverse"><i class="icon-remove icon-white"></i> <span class="amazon-cancel-text">Abort</span></a>
                        <div class="progress active amazon-progress">
                          <div class="bar-success" style="width: 0%;"></div>
                        </div>
                      
                        <div class="empty-amazon-div"></div>
                        <div class="amazon-log">
                        </div>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
        <div class="box-heading"><h1><i class="icon-info-sign"></i>Amazon CloudFront/S3 CDN</h1></div>
        <div class="box-content" style="min-height:100px; line-height:20px;">
        	<p>Amazon CloudFront is a web service for content delivery. Requests for your content are automatically routed to the nearest edge location, so content is delivered with the best possible performance. Amazon CloudFront is optimized to work with Amazon Simple Storage Service (Amazon S3).</p>
        	<p>There are no contracts or monthly commitments for using Amazon CloudFront – you pay only for as much or as little content as you actually deliver through the service.</p>
        	<p>Learn about Amazon CloudFront's latest features on the <a href="http://aws.amazon.com/cloudfront/whats-new" target="_blank">Amazon CloudFront What's New page</a>.</p>
        </div>
    </div>
</div>

<style>
.CDNAmazon {
		
}

</style>

<script type="text/javascript">

$(window).load(function() {
	var showCDNForm = function() {
		if ($('.NitroCDNAmazon').val() == 'yes') {
			$('.CDNAmazon-tabbable-parent').fadeIn();
		} else {
			$('.CDNAmazon-tabbable-parent').hide();
		}
	}
	
	$('.NitroCDNAmazon').change(function() {
		$('.cdn-error').hide();
		if ($('.NitroCDNRackspace').val() == 'yes' || $('.NitroCDNStandard').val() == 'yes') {
			$('.cdn-error.amazon-error').fadeIn();
			$('.NitroCDNAmazon').val('no');
		} else {
			$('.cdn-error.amazon-error').hide();
			showCDNForm();
		}
	}).trigger('change');
	
	showCDNForm();
	
	var refreshAjax;
	var uploadAmazon;
	var interval;
	var amazonLog = $('.amazon-log');
	var amazon_uploading = false;
	
	var refresh_amazon_data = function(init) {
		if (typeof refreshAjax != 'undefined') refreshAjax.abort();
		if (typeof init != 'undefined') {
			$('.amazon-progress .bar-success').css({width: '0%'});
		}
		refreshAjax = $.ajax({
			url: 'index.php?route=tool/nitro/getamazonprogress&token=' + getURLVar('token') + (typeof init != 'undefined' ? '&init=true' : ''),
			dataType: 'json',
			cache: false,
			success: function(data) {
				if (data == null) return;
				amazonLog.html(data.message);
				$('.amazon-progress .bar-success').css({height: '30px'}).animate({width: data.percent + '%'}, 100);
				amazonLog.css({width: $('.empty-amazon-div').width() + 'px'});
			}
		});
	}
	
	$('.amazon-upload').click(function() {
		if (amazon_uploading) return;
		var button = $(this);
		refresh_amazon_data(true);
		uploadAmazon = $.ajax({
			url: 'index.php?route=tool/nitro/saveamazon&token=' + getURLVar('token'),
			type: 'POST',
			cache: false,
			dataType: 'json',
			data: {
				accessKeyID: $('input[name="Nitro[CDNAmazon][AccessKeyID]"]').val(),
				secretAccessKey: $('input[name="Nitro[CDNAmazon][SecretAccessKey]"]').val(),
				bucket: $('input[name="Nitro[CDNAmazon][Bucket]"]').val(),
				syncImages: $('input[name="Nitro[CDNAmazon][SyncImages]"]').is(':checked'),
				syncCSS: $('input[name="Nitro[CDNAmazon][SyncCSS]"]').is(':checked'),
				syncJavaScript: $('input[name="Nitro[CDNAmazon][SyncJavaScript]"]').is(':checked')
			},
			beforeSend: function() {
				button.attr('disabled','disabled');
				$('.amazon-button-text').text('Uploading...');
				$('.amazon-cancel-text').removeAttr('disabled');
				amazonLog.empty();
				$('.amazon-alert').hide().empty();
				interval = setInterval(refresh_amazon_data, 1000);
				amazon_uploading = true;
			},
			success: function(data) {
				if (typeof data.success != 'undefined') {
					$('.amazon-alert.alert-success').html(data.success).slideDown();
				}
				if (typeof data.error != 'undefined') {
					$('.amazon-alert.alert-error').html(data.error).slideDown();
				}
				if (typeof data.upload_time != 'undefined') {
					$('.amazon-alert-info.alert-info').html('Last synchronization was on ' + data.upload_time);
					$('input[name="Nitro[CDNAmazon][LastUpload]"]').val(data.upload_time);
				}
			},
			complete: function() {
				button.removeAttr('disabled');
				$('.amazon-cancel-text').attr('disabled','disabled');
				$('.amazon-button-text').text('Upload to CDN Server');
				clearInterval(interval);
				refresh_amazon_data();
				amazon_uploading = false;
			}
		});
	});
	
	$('.amazon-cancel').click(function() {
		if (!amazon_uploading) return;
		$.ajax({
			url: 'index.php?route=tool/nitro/cancelamazon&token=' + getURLVar('token'),
			cache: false
		});
	});
	
	$('.amazon-cancel-text').attr('disabled','disabled');
});

</script>