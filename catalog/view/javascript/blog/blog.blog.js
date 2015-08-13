/*
 * Showmore records plugin for opencart
 * Copyright (c) 2015 Shvarev Ruslan ruslan.shv@gmail.com
 * Copyright (c) 2015 http://opencartadmin.com
 */
function records_more() {
	    var $next = $('.pagination div.links b').next('a');
	    if ($next.length == 0) {
	        $next = $('.pagination li.active').next('li').find('a');
	        if ($next.length == 0) {
	        	return;
	        }
	    }
	    $('#records_more a').append('<ins class="loading"><img src="catalog/view/theme/default/image/aloading16.png" alt=""></ins>');
	    $.get($next.attr('href'), function (data) {
	        $data = $(data);
	    	var $container = $('.seocmspro_content_main .record_columns');
	        $container.append($data.find('.seocmspro_content_main .record_columns > *'));
            $('.loading').remove();

            $('div.pagination').html($data.find('div.pagination > *'));
            $('#records_more').html($data.find('#records_more > *'))

		    var $next_load = $('.pagination div.links b').next('a');

		    if ($next_load.length == 0) {
		        $('#records_more').hide();

		        $next_load = $('ul.pagination li.active').next('li').find('a');
		        if ($next_load.length == 0) {
		        } else {		        	 $('#records_more').show();
		        }
		    }

	        $data.filter('script').each(function () {
	            if ((this.text || this.textContent || this.innerHTML).indexOf("document.write") >= 0) {
	                return;
	            }
	            $.globalEval(this.text || this.textContent || this.innerHTML || '');
	        });

	    }, "html");
	    return false;
}

function records_grid(width)
{
if (typeof(width) == "undefined") { width = '100%'; }
	$('.column_width_').css('width', width);
	localStorage.setItem('records_grid', width);
	if (width == '100%') {		$('#ascp_list').prop('class', 'ascp_list_grid ascp_list_active');
		$('#ascp_grid').prop('class', 'ascp_list_grid ascp_grid');
	} else {		$('#ascp_grid').prop('class', 'ascp_list_grid ascp_grid_active');
		$('#ascp_list').prop('class', 'ascp_list_grid ascp_list');
	}
	return false;
}

$(document).ready(function(){
	if (localStorage.getItem('records_grid') == '100%') {
		records_grid();
	}
});