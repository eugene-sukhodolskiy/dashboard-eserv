class ProjectControl{
	constructor(){
		this.initChangeVisibility();
	}

	initChangeVisibility(){
		const self = this;
		$('button[data-change-visibility]').each(function(){
			if($(this).hasClass('init-change-visibility')){
				return ;
			}
			$(this).addClass('init-change-visibility');

			$(this).on('click', function(){
				let visibilityFlag = $(this).attr('data-change-visibility');
				self.changeVisibility($(this).attr('data-project-name'), visibilityFlag);
			});
		});
	}

	changeVisibility(projectName, visibilityFlag){
		const requestUrl = `/visibility/${projectName}/${visibilityFlag}`;
		$.get(requestUrl, resp => {
			if(visibilityFlag == 'true'){
				document.location = document.location;
			}else{
				$('.global-popup-bg').trigger('click');
				setTimeout(() => {
					$(`[data-name="${projectName}"]`).parent().remove();
				}, 500);
			}
		});
	}
}