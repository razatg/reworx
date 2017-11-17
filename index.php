<?php 
include_once('config-ini.php');
?>
<html ng-app="trackingApp" lang="en" xmlns="http://www.w3.org/1999/xhtml">
	<head>	
		<title>Home</title>	
		<base href="<?php echo ANGULAR_ROUTE;?>/index.php">   
		<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />	
		 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<link href="newui/css/style.css?v=<?php echo time();?>" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="newui/js/jquery.min.js"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="newui/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="newui/js/angular.min.js"></script>
		<script type="text/javascript" src="newui/js/angular-sanitize.js"></script>
		<script type="text/javascript" src="newui/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="newui/js/angular-route.js"></script>
		<script type="text/javascript" src="newui/js/config.js?v=<?php echo time();?>"></script>
		<link rel="stylesheet" href="newui/css/simple-autocomplete.css">
	   <script type="text/javascript" src="newui/js/simple-autocomplete.js"></script>
	</head>
	<body class="{{currentUrl=='/'?'bg':''}} {{currentUrl=='/uploadcsv'?'bgHome':''}}">
		 <div ng-view></div>
		<footer>
			<p>
				© 2017 TheReferralWorks. All rights reserved.
			</p>
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
	})
</script>	
<script type="text/javascript" src="newui/js/jquery.meanmenu.js"></script>
</html>
