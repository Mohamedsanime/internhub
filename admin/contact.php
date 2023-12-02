<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>InternHub | Contact-us Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/dist/css/style2.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  
    <script>
                function locate(){
            navigator.geolocation.getCurrentPosition(initMap,fail);
        }
        function fail(){
            alert("failed, may not be supported");
        }

    </script>
</head>

<body class="hold-transition register-page" onload="locate()">
<div class="register-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="../../index2.html" class="h1"><b>INTERN</b>HUB</a>
        </div>
        <div class="card-body">
            <p class="h3">Contact Us</p>

            <form method="POST" id="contactForm" name="contactForm" class="contactForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label" for="name">Full Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-md-6"> 
                        <div class="form-group">
                            <label class="label" for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="label" for="subject">Subject</label>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="label" for="#">Message</label>
                            <textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Message"></textarea>
                        </div>
        

                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="submit" value="Send Message" class="btn btn-primary">
                            <div class="submitting"></div>
                        </div>
                    </div>

                </div> 
        

            </form>

            <div class="row">
                <div class="col-md-25">
                    <div class="col-md-5 d-flex align-items-stretch">
                        <div id="map" style="width:2000px; height:300px;"> 	</div>
                        <script>
                            function initMap() {
                                latt=position.coords.latitude;
                                long=position.coords.longitude;
                                var demomap = {lat: 35.1457, lng: 33.9071};
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    zoom: 14,
                                    center: demomap
                                    });
                                var marker = new google.maps.Marker({
                                    position: demomap,
                                    map: map
                                    });
                            }
                        </script>
                        <script async defer
                            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfwo7C7-WLO8GU-bc6WmvqmsF8FKipzuE&callback=initMap">
                        </script>
                    </div>
                </div>
                <div class="col-md-4">
					<div class="dbox w-100 text-center">
			        	<div class="icon d-flex align-items-center justify-content-center">
			        		<span class="fa fa-map-marker"></span>
			        	</div>
			        	<div class="text">
				            <p><span>Address:</span> 99628, Famagusta, North Cyprus
                                via Mersin 10 Turkey </p>
				        </div>
			        </div>
				</div>
                <div class="col-md-3">
					<div class="dbox w-100 text-center">
			        	<div class="icon d-flex align-items-center justify-content-center">
			        		<span class="fa fa-phone"></span>
			        	</div>
			        	<div class="text">
				            <p><span>Phone:</span> <a href="tel://1234567920">+90 392 630 11 11</a></p>
				        </div>
                    </div>
                </div>
                <div class="col-md-3">
					<div class="dbox w-100 text-center">
			        	<div class="icon d-flex align-items-center justify-content-center">
			        		<span class="fa fa-paper-plane"></span>
			        	</div>
			        	<div class="text">
				            <p><span>Email:</span> <a href="mailto:info@yoursite.com">info@emu.edu.tr</a></p>
				        </div>
			        </div>
				</div>
            </div>
      
        </div>
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
<script src="assets/dist/js/jquery.min.js"></script>
  <script src="assets/dist/js/popper.js"></script>
  <script src="assets/dist/js/bootstrap.min.js"></script>
  <script src="assets/dist/js/jquery.validate.min.js"></script>
  <script src="https://maps.app.goo.gl/AJm79MpXyhoLwqQh7"></script>
  <script src="assets/dist/js/google-map.js"></script>
  <script src="assets/dist/js/main.js"></script>
</body>
</html>
