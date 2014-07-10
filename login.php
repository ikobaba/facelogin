<? include("header.php"); ?>

      <!-- Example row of columns -->
      <div class="jumbotron">
     
<?php

if ($_POST["dataimg"]) {
?>
<h2>Comparing the face provided with the face enrolled</h2>
<?
//Save login face to $facestorage."faces_tmp
$email=filter_var($_POST["email"],FILTER_VALIDATE_EMAIL);
$_POST["dataimg"]=str_replace("data:image/png;base64,","",$_POST["dataimg"]);

$_POST["dataimg"]= base64_decode($_POST["dataimg"]);
file_put_contents($facestorage."faces_tmp/".md5($email),$_POST["dataimg"]);


$dst_x = 0;   // X-coordinate of destination point. 
$dst_y = 0;   // Y --coordinate of destination point. 
$array1["x"] = $_POST[x]; // Crop Start X position in original image
$array1["y"]= $_POST[y]; // Crop Srart Y position in original image
$array1["width"]= $_POST[w]; // Thumb width
$array1["height"] = $_POST[h]; // Thumb height
$src_w = $src_x + $dst_w; // $src_x + $dst_w Crop end X position in original image
$src_h = src_y + $dst_h; // $src_y + $dst_h Crop end Y position in original image

 
// Create image instances
$src = imagecreatefrompng($facestorage."faces_tmp/".md5($email));
$dest = imagecreatetruecolor(intval($array1["width"])-10, intval($array1["height"])-10 ) or die("<h3 style='color:red'>No image was provided. Did you allow access to your camera as shown below?</h3><br><img src=allow.png>"); 
 
// Copy
imagecopy($dest, $src, 0, 0,$array1["x"]+5, $array1["y"]+5, $array1["width"], $array1["height"]);



//

imagepng($dest, $facestorage."faces_tmp/cropped-".md5($email).".png");
//imagegd($dest);

$facelogin=$facestorage."faces_tmp/cropped-".md5($email).".png" ;
$facereg=$facestorage."faces/cropped-".md5($email).".png" ;

//echo "Comparing LoginFace <img src='data:image/png;base64,".base64_encode(file_get_contents($facelogin))."' > with RegisteredFace <img src='data:image/png;base64,".base64_encode(file_get_contents($facereg))."' ><br> ";
$exec=shell_exec("br -algorithm FaceRecognition -compare $facelogin  $facereg ");
$faceloginsrc=" src='data:image/png;base64,".base64_encode(file_get_contents($facelogin))."'";
$faceregsrc=" src='data:image/png;base64,".base64_encode(file_get_contents($facereg))."'";


?>

<div class="bs-example">
    <div class="row">
      <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <img   <?=$faceloginsrc?> style="height: 200px;  display: block;">
          <div class="caption">
            <h3>Image you logged in with</h3>
			<p>Try to submit a face of the same gender and age :) </p>
                     </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <img   <?=$faceregsrc?> style="height: 200px;  display: block;">
          <div class="caption">
            <h3>Image you registered</h3>
			<p>If you decide to use  in production, never show this image !</p>
          
          </div>
        </div>
      </div>
  
  
   <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
          <h1>Results</h1>
          <div class="caption">
             
			<p><?


//echo "Exec res: ".$exec."<hr>";
  $resultpr=$exec*100;
  unlink($facelogin);
  echo "<hr>";
  echo "Result matched @ <b>".$resultpr."%</b>";
  
  if ($resultpr>90) { echo "<br><b style='color:green'><span class='glyphicon glyphicon-ok'></span> Valid user, log him in</b>"; } else {
  
  echo "<br><b style='color:red'><span class='glyphicon glyphicon-remove'></span>Invalid user, do not log him in</b> <br>  <a href=login.php class='btn btn-default' >Try again</a> ";
  }
  
  
  ?></p>
          
          </div>
        </div>
      </div>
	  
	  
    </div>
  </div>
  
  
<?
  
 

} else { 
?>
<h1>Login</h1>
<div class="control-group">
<h2>1. Enter your email address</h2>
<div class="controls"><form action=login.php method=post id=submit >		
<input type=hidden id=x name=x>
<input type=hidden id=y name=y>
<input type=hidden id=w name=w>
<input type=hidden id=h name=h>
<input type=hidden id=dataimg name=dataimg>
<input type="text" class="input-xlarge" id="email" name=email  placeholder="Email address" >
</form>
</div>
</div>
<h2>2. Position your face</h2><p id="info">Please allow access to your camera!<br>See the warning on the top of this window.<br><img src=allow.png></p>
          
         
		<canvas id="output"></canvas> 
		<p>Make sure your face is inside the blue rectangle<br>Make sure your face is correctly detected!</p>
		<script src="ccv.js"></script>
		<script src="face.js"></script>
		 <p><a class="btn btn-lg btn-success" href="#" role="button" onClick="capture()">Login</a></p>
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
       <? } ?>
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