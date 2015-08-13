<?php $reportp = json_decode($widget['pagespeed'],true); ?>
<?php $report = str_replace(array("\r", "\n"), '', $widget['pagespeed']); ?>
<?php $report = stripcslashes($report); ?>
<?php $report = str_replace('"Vary: Accept-Encoding"','Vary: Accept-Encoding',$report); ?>

<script type="text/javascript">
var report = <?php echo $widget['pagespeed']; ?>;
</script>
<table style="width:100%" class="pagespeedMainTable">
<tr>
    <td style="width:50%;vertical-align: top;">
    <div id="g1" class="bigGauge"></div>
    </td>
    <td style="width:50%; vertical-align:top;padding-top:5px;">
    	<h3>Steps you need to take</h3>
        <table class="table stepsToTake">
          <tbody>
            <tr class="<?php echo (!empty($data['Nitro']['PageCache']['Enabled']) && $data['Nitro']['PageCache']['Enabled'] == 'yes') ? 'disabled' : ''; ?>">
              <td>1</td>
              <td>Enable Page Caching</td>
              <td style="width: 100px"><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#pagecache]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['BrowserCache']['Enabled']) && $data['Nitro']['BrowserCache']['Enabled'] == 'yes') ? 'disabled' : ''; ?>">
              <td>2</td>
              <td>Leverage Browser Caching</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#browsercache]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['Compress']['Enabled']) && $data['Nitro']['Compress']['Enabled'] == 'yes') ? 'disabled' : ''; ?>">
              <td>3</td>
              <td>Enable GZIP Compression</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#compression]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['Mini']['CSS']) && $data['Nitro']['Mini']['CSS'] == 'yes' && $data['Nitro']['Mini']['JS'] == 'yes') ? 'disabled' : ''; ?>">
              <td>4</td>
              <td>Minify CSS and JavaScript</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#minification]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['Mini']['HTML']) && $data['Nitro']['Mini']['HTML'] == 'yes') ? 'disabled' : ''; ?>">
              <td>5</td>
              <td>Minify HTML</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#minification]').trigger('click'); });" class="btn btn-small btn-inverse">Setup Now</a></td>
            </tr>
            <tr class="<?php echo (!empty($data['Nitro']['Tips']['CreatedSprites']) && $data['Nitro']['Tips']['CreatedSprites'] == 'yes') ? 'disabled' : ''; ?>">
              <td>6</td>
              <td>Combine images into sprites</td>
              <td><a onclick="$('html, body').animate({ scrollTop: 0 }, 200, function() { $('a[href=#qa]').trigger('click'); });" class="btn btn-small btn-inverse">Learn How</a></td>
            </tr>
          </tbody>
        </table>
    </td>
</tr>
</table>
<?php if (!empty($reportp['score']) && $reportp['score'] > 81): ?>
    <div class="text-greatscore"><span class="label label-success">Great Score</span>&nbsp;&nbsp;<a href="http://www.seochat.com/c/a/search-engine-optimization-help/google-page-speed-score-vs-website-loading-time/" target="_blank">Top-ranking websites</a> in Google have an average score of 80.78 and yours is <strong><?php echo $reportp['score']; ?></strong>!</div>
<?php endif; ?>


<ul class="nav nav-pills gaugeFilterUL" param="performers">
  <li class="active">
    <a href="javascript:void(0)" param="lowperformers">Low Performers</a>
  </li>
  <li>
    <a href="javascript:void(0)" param="mediumperformers">Medium Performers</a>
  </li>
  <li>
    <a href="javascript:void(0)" param="highperformers">High Performers</a>
  </li>
  <li>
    <a href="javascript:void(0)" param="lowimpact">Low Impact</a>
  </li>
  <li>
    <a href="javascript:void(0)" param="mediumimpact">Medium Impact</a>
  </li>
  <li>
    <a href="javascript:void(0)" param="highimpact">High Impact</a>
  </li>
</ul>
<div id="smalGauges" class="smallGauges"></div>
<div class="alert alert-success performersSuccess" style="display:none"></div>

<script type="text/javascript">
// This fixes a firefox issue with gauges
if (jQuery.browser.mozilla) {
	$('base').remove();
}

var gauges = [];

var loadMainGauge = function() {	
	new JustGage({
	  id: "g1", 
	  value: report.score, 
	  min: 0,
	  max: 100,
	  title: "Your Site Google Page Score",
	  label: "points",
	  levelColors: ["#B20000","#FF9326","#6DD900"],
	});
}

var loadSmallGauges = function() { 
	var ruleResults = report.formattedResults.ruleResults;
	var i = 0;
	$.each(ruleResults, function(key, rule) {
		i++;
		$('#smalGauges').append('<div class="smallGauge" id="smallG'+i+'" i="'+i+'" rule="'+rule.localizedRuleName+'" score="'+rule.ruleScore+'" impact="'+rule.ruleImpact+'"></div>');
	});
}

var newGauge = function(e) {
	$(e).attr('style','display:inline-block');
	var title = $(e).attr('rule');
	if (title.length > 25) { 
		title = title.substr(0,26) + '...';
	}
	new JustGage({
	  id: $(e).attr('id'), 
	  value: $(e).attr('score'), 
	  min: 0,
	  max: 100,
	  title: title,
	  labelFontSize: 8,
	  label: "points",
	  levelColors: ["#B20000","#FF9326","#6DD900"],
	});	
}

var filterSmallGauges = function(filterword) {
	var highimpact = 15;
	var lowimpact = 2;
	var highscore = 90;
	var lowscore = 30;
	gaugesArray = $('.smallGauges .smallGauge');
	switch(filterword) {
		case 'lowimpact':
			$.each(gaugesArray, function(i,e) {
				if ($(e).attr('impact') < lowimpact) {
					newGauge(e);
				}
			});
		break;
		case 'mediumimpact':
			$.each(gaugesArray, function(i,e) {
				if ($(e).attr('impact') > lowimpact && $(e).attr('impact') < highimpact) {
					newGauge(e);
				}
			});				
		break;
		case 'highimpact':
			var highImpact = false;
			$.each(gaugesArray, function(i,e) {
				if ($(e).attr('impact') > highimpact) {
					newGauge(e);
					highImpact = true;
				}
			});
			if (highImpact == false) {
				$('.performersSuccess').html('<strong>Perfect!</strong> All high-impact recommendations have been addressed!').attr('style','display:block');	
			}
		break;
		case 'lowperformers':
			var lowPerformers = false;
			$.each(gaugesArray, function(i,e) {
				if ($(e).attr('score') < lowscore) {
					newGauge(e);
					lowPerformers = true;
				}
			});
			if (lowPerformers == false) {
				$('.performersSuccess').html('<strong>Well done!</strong> You have no low performers!').attr('style','display:block');	
			}
		break;
		case 'mediumperformers':
			$.each(gaugesArray, function(i,e) {
				if ($(e).attr('score') > lowscore && $(e).attr('score') < highscore) {
					newGauge(e);
				}
			});				
		break;
		case 'highperformers':
			$.each(gaugesArray, function(i,e) {
				if ($(e).attr('score') > highscore) {
					newGauge(e);
				}
			});

		break;
	}
	

}

$('.gaugeFilterUL a').click(function() {
	$(this).parents('ul').attr('param',$(this).attr('param')).find('li').removeClass('active');
	$(this).parent().addClass('active');
	$('.smallGauges .smallGauge').html('').attr('style','display:none');
	$('.performersSuccess').attr('style','display:none');
	filterSmallGauges($(this).parents('ul').attr('param'));
});

loadMainGauge();
loadSmallGauges();
filterSmallGauges('lowperformers');	

$('.stepsToTake tr.disabled .btn').text('Enabled').removeClass('btn-inverse').addClass('disabled');

</script>

