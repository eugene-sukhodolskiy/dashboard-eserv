class Hotkey{
	constructor(){
		this.binding = [];
		this.processor();
	}

	/**
	 * Bind action to keys combine
	 * @param  {Array} key 		 Array with code of keys. Example: ["ctrl", "y"]
	 * @param  {Function} action event handler
	 * @return {void}
	 */
	bind(keys, action){
		if(typeof keys != 'object'){
			return console.error("Hotkey.bind", "Argument must be an array");
		}

		if(typeof action != 'function'){
			return console.error("Hotkey.bind", "Argument must be an function");
		}

		for(let i in keys){
			keys[i] = keys[i].toLowerCase();
		}

		this.binding.push({
			keys: keys,
			action: action
		});
	}

	processor(){
		const self = this;

		$(document).on('keypress, keydown, keyup', function(e){
			let keys = [];
			let probableKeys = ['ctrl', 'alt', 'shift', 'meta'];
			for(let probableKey of probableKeys){
				if(e[`${probableKey}Key`]){
					keys.push(probableKey);
				}
			}
			keys.push(e.key.toLowerCase());

			self.handler(keys);
		});
	}

	handler(activeKeys){
		for(let b of this.binding){
			if(b.keys.length != activeKeys.length){
				continue;
			}

			let flag = true;
			for(let key of activeKeys){
				if(b.keys.indexOf(key) < 0){
					flag = false;
					continue;
				}
			}

			if(flag){
				b.action(activeKeys);
			}
		}
	}
}