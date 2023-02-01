$(document).ready(function(){
  $.ajaxSetup({
  	cache: false
  });

	$('.create-new-project').on('submit', function(e){
		if(!$('.create-project').val().length){
			return e.preventDefault();
		}
	});

	$('.project').on('click', function(e){
		let btnOpenProject = $(this).find('.open-project');
		if(btnOpenProject.hasClass('no-info')){
			btnOpenProject.removeClass('no-info');
		}else{
			$(this).find('.description').addClass('show');
			$('.global-popup-bg').addClass('show');
			$('.close-project-description').addClass('show');
		}
	});

	$('.global-popup-bg, .close-project-description').on('click', function(){
		$('.project .description.show').removeClass('show');
		$('.close-project-description').removeClass('show');
		$('.global-popup-bg').removeClass('show');
	});

	$('.open-project').on('click', function(){
		$(this).addClass('no-info');
	});
	
	$('.project').each(function(){
		const propForProjectColor = SETTINGS['project-color-in'];
		const project = $(this);
		if(project.attr('data-color') != 'undefined'){
			project.css(propForProjectColor, project.attr('data-color'));
		}
	});

	$('img.app-favicon').on('error', function(e){
		const path = $(this).attr('data-favicon-path');
		if(typeof path == 'undefined'){
			return false;
		}
		const src = '/?url=' + path;
		const elms = $('[data-favicon-path="' + path + '"]');
		$(elms).attr('src', src);
		$(elms).removeAttr('data-favicon-path');
		if($(elms).parent().parent().hasClass('project')){
			$(elms).on('load', function(){
				setFavColor(this);
			});
		}
	});

	$('.project > .project-title > img.app-favicon').on('load', function(){
		setFavColor(this);
	});

	$('.project a').on('click', function(){
		const link = $(this);
		let curEl = link;
		// search project element
		while(100){
			curEl = $(curEl.parent());
			if(curEl.hasClass('project')){
				curEl.find('.open-project').addClass('no-info');
				break;
			}
		}
	});

	searchInit();
	hotkeyMap();
	hiddenListControl();
	settings = new Settings();
	projectControl = new ProjectControl();
});

function setFavColor(favImg){
	const propForProjectColor = SETTINGS['project-color-in'];
	const fav = $(favImg);
	const project = $(favImg).parent().parent();
	if(project.attr('data-color') != 'undefined'){
		project.css(propForProjectColor, project.attr('data-color'));
	}else{
		if(project.hasClass('project') && fav.length){
			new Color(fav, function(c){
				let rgba = 'rgba(' + c[0] + ', ' + c[1] + ', ' + c[2] + ', 1)';
				project.css(propForProjectColor, rgba);
			});
		}
	}
}

function hiddenListControl(){
	$('.open-hidden-list').on('click', function(){
		$('.hidden-list').addClass('show');
		$('.hidden-list-bg').addClass('show');
		$.getJSON("/Dashboard/hidden-list.json", function(hiddenProjects){
			$('.hidden-list .loader-spin').hide();
			let html = '';
			for(let project of hiddenProjects){
				project = decodeURI(project);
				html += `<li class="hidden-project">
					<span class="project-name">${project}</span>
					<button class="button make-project-visible" data-project-name="${project}" data-change-visibility="true">Make visible</button>
				</li>`;
			}

			if(!hiddenProjects.length){
				html += `<div class="empty-hidden-list">Empty</div>`
			}
			$('.hidden-list-wrap').html(html);
			projectControl.initChangeVisibility();
		});
	});

	$('.hidden-list-bg').on('click', function(){
		$('.hidden-list').removeClass('show');
		$('.hidden-list-bg').removeClass('show');
	});
}

function searchInit(){
	searchObject = new Search('input.search', '.project', '.status-control');

	$('.project-card-info .tag').on('click', function(e){
		e.preventDefault();
		let projectCard = $(this).parent().parent();
		if($(projectCard).hasClass('project')){
			$(projectCard).find('.open-project').addClass('no-info');
		}else{
			projectCard.parent().find('.open-project').addClass('no-info');
			$('.global-popup-bg').trigger('click');
		}
		let searchString = $(this).html().trim().toLowerCase();
		$('input.search').val(searchString);
		$('.search-cancel').addClass('visible');
		searchObject.search(searchString, 'tags');
	});

	$('input.search').on('input', function(){
		if($('input.search').val().length){
			$('.search-cancel').addClass('visible');
		}else{
			$('.search-cancel').removeClass('visible');
		}
	});

	$('.search-cancel').on('click', function(){
		$('input.search').val('').trigger('input');
	});

	$('.status-control a').on('click', function(e){
		e.preventDefault();
		let status = $(this).attr('data-status');
		$(this).parent().find('a').show();
		$(this).hide();
		$(this).parent().attr('data-status', status);
		searchObject.search($('input.search').val());
	});

	// Fix problem https://github.com/eugene-sukhodolskiy/dashboard/issues/50
	$('input.search').on('keydown', function(e){
		if(e.key == 'ArrowLeft' || 
			e.key == 'ArrowRight' || 
			e.key == 'ArrowDown' || 
			e.key == 'ArrowUp'){
			e.preventDefault();
		}
	});
}

let projectContrastFlag = false;

function hotkeyMap(){
	hotkey = new Hotkey();
	hotkey.bind(['ctrl', 'shift', 'f'], keys => {
		$('.search').trigger('focus');
	});

	hotkey.bind(['ctrl', 'q'], keys => {
		if($('.global-popup-bg').hasClass('show') || $('.popup-mini-bg').hasClass('show')){
			$('.global-popup-bg.show').trigger('click');
			$('.popup-mini-bg.show').trigger('click');
			return ;
		}
		if($('.search').is(':focus') || $('.search').val().length){
			$('.search-cancel').trigger('click');
			$('.search').trigger('blur');
			return ;
		}
	});

	hotkey.bind(['ctrl', 'i'], keys => {
		if(!projectContrastFlag){
			settings.doSetting_project_color_in('background-color');
			projectContrastFlag = true;
		}else{
			settings.doSetting_project_color_in('border-color');
			projectContrastFlag = false;
		}
	});

	hotkey.bind(['enter'], keys => {
		$('.project.selected', 0).trigger('click');
	});

	hotkey.bind(['ctrl', 'enter'], keys => {
		window.open($('.project.selected .open-project', 0).attr('href'));
	});

	keynav = new KeyNav(hotkey);
	searchObject.afterDisplaySearchResult = () => {
		keynav.resetSelectPos();
	}
}

let settings;
let searchObject;
let projectControl;
let hotkey;
let keynav;