<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/dist/css/style2.css">
</head>
<body>
<h2>Contact Us</h2>
<div class = "formdiv">
<form action="" method="post" class="formcls" novalidate="novalidate">
    <p><label> Your Name (required)<br>
        <input name="your-name" value="" type="text"></label></p>
    <p><label> Your Email (required)<br>
        <input name="your-email" value="" type="email"></label></p>
    <p><label> Subject<br>
        <input name="your-subject" value="" type="text"></label></p>
    <p><label> Your Message<br>
        <textarea name="your-message" cols="20" rows="5" ></textarea></label></p>
    <p><input id="submitbutton" value="Send" type="submit"></p>
    <address> Address: Sukhna Lake, Sector 1, Chandigarh</address>
</div>
<div id="map"></div>
    <script>
      function initMap() {
        var demomap = {lat: 30.7421379, lng: 76.8187557};
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
</body>
</html>