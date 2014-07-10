<? include("header.php"); ?>
 

      <!-- Example row of columns -->
      <div class="jumbotron">
     


<div class="control-group">
<h1>Registration</h1>
<h2>1. Enter your email address</h2>
<div class="controls"><form action=register.php method=post id=submit >		
<input type=hidden id=x name=x>
<input type=hidden id=y name=y>
<input type=hidden id=w name=w>
<input type=hidden id=h name=h>
<input type=hidden id=dataimg name=dataimg>
<input type="text" class="input-xlarge" id="email" name=email  placeholder="Email address" >
</form>
</div>
</div>
<h2>2. Position your face</h2><p id="info">Please allow access to your camera! <br>See the warning on the top of this window.<br><img src=allow.png></p>
          
         
		<canvas id="output"></canvas> 
		<p>Make sure your face is inside the blue rectangle<br>Make sure your face is correctly detected!</p>
		<script src="ccv.js"></script>
		<script src="face.js"></script>
		 <p><a class="btn btn-lg btn-success" href="#" role="button" onClick="capture()">Complete registration</a></p>
		<script>

// requestAnimationFrame shim
(function() {
	var i = 0,
		lastTime = 0,
		vendors = ['ms', 'moz', 'webkit', 'o'];
	
	while (i < vendors.length && !window.requestAnimationFrame) {
		window.requestAnimationFrame = window[vendors[i] + 'RequestAnimationFrame'];
		i++;
	}
	
	if (!window.requestAnimationFrame) {
		window.requestAnimationFrame = function(callback, element) {
			var currTime = new Date().getTime(),
				timeToCall = Math.max(0, 1000 / 60 - currTime + lastTime),
				id = setTimeout(function() { callback(currTime + timeToCall); }, timeToCall);
			
			lastTime = currTime + timeToCall;
			return id;
		};
	}
}());

var App = {
	start: function(stream) {
		App.video.addEventListener('canplay', function() {
			App.video.removeEventListener('canplay');
			setTimeout(function() {
				App.video.play();
				App.canvas.style.display = 'inline';
				App.info.style.display = 'none';
				App.canvas.width = App.video.videoWidth;
				App.canvas.height = App.video.videoHeight;
				App.backCanvas.width = App.video.videoWidth / 4;
				App.backCanvas.height = App.video.videoHeight / 4;
				App.backContext = App.backCanvas.getContext('2d');
			
				var w = 300 / 4 * 0.8,
					h = 270 / 4 * 0.8;
			
				App.comp = [{
					x: (App.video.videoWidth / 4 - w) / 2,
					y: (App.video.videoHeight / 4 - h) / 2,
					width: w, 
					height: h,
				}];
			
				App.drawToCanvas();
			}, 500);
		}, true);
		
		var domURL = window.URL || window.webkitURL;
		App.video.src = domURL ? domURL.createObjectURL(stream) : stream;
	},
	denied: function() {
		App.info.innerHTML = 'Camera access denied!<br>Please reload and try again.';
	},
	error: function(e) {
		if (e) {
			console.error(e);
		}
		App.info.innerHTML = 'Please go to about:flags in Google Chrome and enable the &quot;MediaStream&quot; flag.';
	},
	drawToCanvas: function() {
		requestAnimationFrame(App.drawToCanvas);
		
		var video = App.video,
			ctx = App.context,
			backCtx = App.backContext,
			m = 4,
			w = 4,
			i,
			comp;
		
		ctx.drawImage(video, 0, 0, App.canvas.width, App.canvas.height);
		
		backCtx.drawImage(video, 0, 0, App.backCanvas.width, App.backCanvas.height);
		
		comp = ccv.detect_objects(App.ccv = App.ccv || {
			canvas: App.backCanvas,
			cascade: cascade,
			interval: 4,
			min_neighbors: 1
		});
		
		if (comp.length) {
			App.comp = comp;
		}
		
		 
		
		for (i = App.comp.length; i--; ) {
			ctx.drawImage(App.glasses, (App.comp[i].x - w / 2) * m, (App.comp[i].y - w / 2) * m, (App.comp[i].width + w) * m, (App.comp[i].height + w) * m);
			
			document.getElementById('x').value=(App.comp[i].x - w / 2) * m;
			document.getElementById('y').value=(App.comp[i].y - w / 2) * m;
			document.getElementById('w').value=(App.comp[i].width + w) * m;
			document.getElementById('h').value=(App.comp[i].height + w) * m;
		 
			
		}
	}
};

App.glasses = new Image();
App.glasses.src = 'glasses.png';

App.init = function() {
	App.video = document.createElement('video');
	App.backCanvas = document.createElement('canvas');
	App.canvas = document.querySelector('#output');
	App.canvas.style.display = 'none';
	App.context = App.canvas.getContext('2d');
	App.info = document.querySelector('#info');
	
	navigator.getUserMedia_ = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
	
	try {
		navigator.getUserMedia_({
			video: true,
			audio: false
		}, App.start, App.denied);
	} catch (e) {
		try {
			navigator.getUserMedia_('video', App.start, App.denied);
		} catch (e) {
			App.error(e);
		}
	}
	
	App.video.loop = App.video.muted = true;
	App.video.load();
};

App.init();

		function capture() {
		var email = document.getElementById("email").value;
		if (validateEmail(email) ) {
		 var canvas = document.getElementById("output");
		var img    = canvas.toDataURL();
//location.href='register.php?image='+img+'&x='+document.getElementById("y").value+'&y='+document.getElementById("y").value+'&w='+document.getElementById("w").value+'&h='+document.getElementById("h").value;

document.getElementById("dataimg").value=img;

document.getElementById("submit").submit();
return false;

} else {

alert("wrong email");
}

		
		}
		
		
		function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

		
		</script>

		  </p>
       
       </div>
    

  <!-- Jumbotron -->
     

	  
     
 <div class="jumbotron">
        
       
      </div>
      <!-- Site footer -->
      <div class="footer">
        <p>© Company 2014</p>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  

</body></html>