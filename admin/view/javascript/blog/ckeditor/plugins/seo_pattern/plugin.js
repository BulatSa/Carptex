CKEDITOR.plugins.add('seo_pattern', {
  init : function(editor) {
	//alert(editor.config.CPBI_name);
	var self = this;
	
	$.each(PSBdat.patterns, function(key, val){
		var settingInfo_status = false;
		if(val.settingInfo){
			var settingInfo_text = val.settingInfo[editor.config.CPBI_name] ? val.settingInfo[editor.config.CPBI_name] : val.settingInfo.all;
			if(settingInfo_text != ''){
				settingInfo_status = true;
			}
		}

		var label = val['name'];
		if(settingInfo_status){
			label += '</br>Possible additional setting:' + settingInfo_text;
		}
		
		editor.ui.addButton(key, {
			label : label,
			icon: self.path + 'icons/icon-' + key + '.png',
			click: function(){
				editor.focus();
				editor.insertHtml(' !' + key + ' ');
			}
		});
	
	});
  }
});

