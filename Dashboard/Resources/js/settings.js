class Settings{
	constructor(){
		const self = this;

		$('.setting-item select').on('change', function(){
			let data = {
				setting_name: $(this).attr('name'),
				value: $(this).val()
			};
			self.save(data);
		});

		$('.settings-list-open').on('click', function(){
			$('.settings-popup-container .popup-mini-content').addClass('show');
			$('.hidden-settings-bg').addClass('show');
		});

		$('.hidden-settings-bg').on('click', function(){
			$(this).removeClass('show');
			$('.settings-popup-container .popup-mini-content').removeClass('show');
		});
	}

	save(data){
		const self = this;
		const urlForSaving = "/dashboard/";
		$.ajax({
			url: urlForSaving,
			data: data,
			method: 'post'
		}).done(function(){
			const methName = 'doSetting_' + data['setting_name'].replace(/-/gi, '_');
			console.log(methName);
			self[methName](data['value']);
		});
	}

	doSetting_color_schema(val){
		const linkTag = $('#link-color-schema');
		const tpl = linkTag.attr('data-template');
		linkTag.attr('href', tpl.replace("{{color-schema}}", val));
	}

	doSetting_project_color_in(val){
		if(val == "border-color"){
			$('.project').each(function(){
				$(this).css({
					"border-color": $(this).css('background-color')
				});
				$(this).css({
					"background-color": 'var(--main-color)'
				});
			});
		}
		if(val == "background-color"){
			$('.project').each(function(){
				$(this).css({
					"background-color": $(this).css('border-color')
				});
				$(this).css({
					"border-color": "transparent"
				});
			})
		}
	}
}