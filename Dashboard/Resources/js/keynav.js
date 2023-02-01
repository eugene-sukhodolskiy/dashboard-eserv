class KeyNav{
	constructor(hotkey){
		this.hotkey = hotkey;
		this.selectPos;
		this.currentSide;
		this.projects = $('.project');
		this.lim = {x: 3, y: 3}; // n - 1
		this.projectsInLine = [
			{width: 768, count: 1},
			{width: 992, count: 2},
			{width: 1200, count: 3},
			{width: 1900, count: 4},
			{width: 50000, count: 6}
		];

		this.initKeyMap();

		const self = this;
		self.calcActualityLimits();
		$('window').on('resize', function(){
			self.calcActualityLimits();
		});

		this.resetSelectPos();
	}

	calcActualityLimits(){
		const w = $(window).width();
		this.lim.x = this.projectsInLine.filter(i => i.width > w)[0].count - 1;
		this.lim.y = Math.ceil(this.getVisibleProjects().length / (this.lim.x + 1)) - 1;
	}

	resetSelectPos(){
		this.selectPos = {
			x: 0,
			y: 0
		};

		this.renderSelectedProject();
	}

	renderSelectedProject(){
		const projects = this.getVisibleProjects();
		$(this.projects).removeClass('selected');
		const n = this.selectPos.y * (this.lim.x+1) + this.selectPos.x;
		const selectedElements = $(projects[n]);
		selectedElements.addClass('selected');
	}

	initKeyMap(){
		const self = this;

		this.hotkey.bind(['arrowRight'], keys => {
			self.moveSelect('right');
		});

		this.hotkey.bind(['arrowLeft'], keys => {
			self.moveSelect('left');
		});

		this.hotkey.bind(['arrowUp'], keys => {
			self.moveSelect('up');
		});

		this.hotkey.bind(['arrowDown'], keys => {
			self.moveSelect('down');
		});
	}

	moveSelect(side){
		this.currentSide = side;
		switch(side){
			case 'left': 
				if(this.selectPos.x) {
					this.selectPos.x--;
				}else{
					this.selectPos.x = this.selectPos.y ? this.lim.x : 0;
					this.selectPos.y = this.selectPos.y ? this.selectPos.y - 1 : 0;
				}
				break;
			case 'right': 
				if(this.selectPos.x < this.lim.x) {
					this.selectPos.x++;
				}else{
					this.selectPos.y = this.selectPos.y < this.lim.y ? this.selectPos.y + 1 : 0;
					this.selectPos.x = 0;
				}
				break;
			case 'up': 
				this.selectPos.y = this.selectPos.y ? this.selectPos.y - 1 : 0;
				break;
			case 'down': 
				this.selectPos.y = this.selectPos.y < this.lim.y ? this.selectPos.y + 1 : 0;
				break;
		}

		this.renderSelectedProject();
	}

	getVisibleProjects(){
		return this.projects.filter((i, project) => $(project).is(':visible'));
	}
}