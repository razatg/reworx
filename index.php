<?php 
include_once('config-ini.php');
?>
<html ng-app="trackingApp" lang="en" xmlns="http://www.w3.org/1999/xhtml">
	<head>	
		<title>Home</title>	
		<base href="<?php echo ANGULAR_ROUTE;?>/index.php">   
		<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />	
		 <link rel="stylesheet" href="newui/css/bootstrap.min.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<link href="newui/css/style.css?v=<?php echo time();?>" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="newui/js/jquery.min.js"></script>
		<script type="text/javascript" src="newui/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="newui/js/angular.min.js"></script>
		<script type="text/javascript" src="newui/js/angular-sanitize.js"></script>
		<script type="text/javascript" src="newui/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="newui/js/angular-route.js"></script>
		<script type="text/javascript" src="newui/js/config.js?v=<?php echo time();?>"></script>
	</head>
	<body class="{{currentUrl=='/'?'bg':''}} {{currentUrl=='/uploadcsv'?'bgHome':''}}">
		 <div ng-view></div>
		<footer ng-show="currentUrl" id="footerDiv" style="display:none;">
			<div class="container grid-center">
			<div class="row">
			  <div class="col-sm-6 col-md-6">
			  <div class="col-md-4 col-sm-4">
			  <h6>Quick Links</h6>
			  <ul>
				<li><a href="#howitwork">How it Works</a></li> 
				<li><a href="#homebanner">Start a Free Trial</a></li>
				<li><a ng-click="togglePopup('show')">Login</a></li>
			  </ul>
			  </div>
			  <div class="col-md-4 col-sm-4">
			  <h6>Write to us at</h6>
			  <ul>
				<li><a href="mailto:rg@refhireable.com">rg@refhireable.com</a></li>
			  </ul>
			  </div>
			  <div class="col-md-4 col-sm-4">
			  <h6>Our Location</h6>
			  <ul>
				<li>D-37, Sector 6<br>Noida - 201301, India<br>Call Us: 9810329329</li>
			  </ul>
			  </div>
			  </div>
				<div class="col-sm-6 text-center col-md-6">
				<p class="text-right">©2017 Refhireable. All rights reserved.</p>
			  </div>
			</div>
			</div>
			</footer>

	</body>
<script>
$(document).ready(function(){
    setTimeout(function(){ 
		$('.carousel-control.left').click(function() {
		  $('#carousel-generic').carousel('prev');
		});

		$('.carousel-control.right').click(function() {
		  $('#carousel-generic').carousel('next');
		});
 }, 3000);
 
  setTimeout(function(){  
	  $('#footerDiv').show(); 
  }, 1000);
	})
</script>	
<script type="text/javascript" src="newui/js/jquery.meanmenu.js"></script>
</html>
