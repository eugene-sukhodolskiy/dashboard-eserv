class Color{
	constructor(imgTag, callback){
		this.src = $(imgTag).attr('src');
		this.callback = callback;
		this.toCanvas(this.src);
	}

	toCanvas(src){
		var canvas = document.getElementById('service-canv');
		var context = canvas.getContext('2d');
		var imgObj = new Image();
		imgObj.crossOrigin = 'anonymous';
		imgObj.src = "/?url=" + src.replace('/?url=', '');
		var x = 0;
		var y = 0;
		let self = this;
		imgObj.onload = function(){
			canvas.width = imgObj.width;
			canvas.height = imgObj.height;
			context.drawImage(imgObj, 10, 10);
			let imgData = context.getImageData(x, y, canvas.width, canvas.height);
			let pixels = [];
			for(let i=0; i<imgData.data.length; i += 4){
				pixels.push([
					imgData.data[i], 
					imgData.data[i+1], 
					imgData.data[i+2]
				]);
			}

			let c1 = 0;
			let c2 = 0;
			let c3 = 0;
			for(let i=0; i<pixels.length; i++){
				c1 += pixels[i][0];
				c2 += pixels[i][1];
				c3 += pixels[i][2];
			}

			let color = [
				Math.floor(c1 / pixels.length), 
				Math.floor(c2 / pixels.length), 
				Math.floor(c3 / pixels.length)
			];

			self.color = color;
			self.callback(color);
		}
	}
}