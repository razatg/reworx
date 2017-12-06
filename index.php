<?php 
include_once('config-ini.php');
?>
<html ng-app="trackingApp" lang="en" xmlns="http://www.w3.org/1999/xhtml">
	<head>	
		<title>Home</title>	
		<base href="<?php echo ANGULAR_ROUTE;?>/index.php">   
		<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />	
		 <link rel="stylesheet" href="newui/css/bootstrap.min.css">
		  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<link href="newui/css/style.css?v=<?php echo time();?>" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="newui/js/jquery.min.js"></script>
		<script type="text/javascript" src="newui/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="newui/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="newui/js/angular.min.js"></script>
		<script type="text/javascript" src="newui/js/angular-sanitize.js"></script>
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
				<li><a href="#">How it Works</a></li>
				<li><a href="#">About Us</a></li>
				<li><a href="#">Start a Free Trial</a></li>
				<li><a href="#">Login</a></li>
			  </ul>
			  </div>
			  <div class="col-md-4 col-sm-4">
			  <h6>Write to us at</h6>
			  <ul>
				<li><a href="#">abc@xyz.com</a></li>
			  </ul>
			  </div>
			  <div class="col-md-4 col-sm-4">
			  <h6>Our Location</h6>
			  <ul>
				<li>Lorem ipsum doler sit amet issimply dummy text..</li>
			  </ul>
			  </div>
			  </div>
				<div class="col-sm-6 text-center col-md-6">
				<p class="text-right">© 2017 TheReferralWorks. All rights reserved.</p>
			  </div>
			</div>
			</div>
			</footer>

	</body>
<script>
$(document).ready(function(){
	 setTimeout(function(){  
		  $('#footerDiv').show(); 
	 }, 100);
   setTimeout(function(){ 
		$('.carousel-control.left').click(function() {
		  $('#carousel-generic').carousel('prev');
		});

		$('.carousel-control.right').click(function() {
		  $('#carousel-generic').carousel('next');
		});
 }, 3000);
	})
</script>	
<script type="text/javascript" src="newui/js/jquery.meanmenu.js"></script>
</html>
