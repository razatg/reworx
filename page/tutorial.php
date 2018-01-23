<?php 
include_once('../config-ini.php');
?>
<div>
<header>
	<div class="container">
	 	<?php include_once('navbar.php');?>
	</div>
    <nav class="navbar navbar-toggleable-sm navbar-light bg-faded">
      <div class="container">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarNav">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
  <div class="collapse navbar-collapse main_menu" id="navbarNav">
    <ul class="navbar-nav">
      <?php include('menu.php');?>
    </ul>
  </div>
  </div>
</nav>
</header>
	<div class="container">
	<div id="myCarousel" class=" carousel slide" data-ride="carousel">
	<div class="row">
	<div class="col-md-8">
	<h2 class="pd">Follow these easy to follow steps</h2>
	</div>
	<div class="col-md-4 text-right">
	  <a class="pd" href="#myCarousel" data-slide="prev" style="margin-right:20px;"> 
		<span>Previous</span>
	  </a>
	  <a class="pd" href="#myCarousel" data-slide="next"> 
		<span>Next</span>
	  </a></div>
	</div>
	  <!-- Indicators -->
	  <ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
			<li data-target="#myCarousel" data-slide-to="2"></li>
			<li data-target="#myCarousel" data-slide-to="3"></li>
			<li data-target="#myCarousel" data-slide-to="5"></li>
			<li data-target="#myCarousel" data-slide-to="6"></li>
			<li data-target="#myCarousel" data-slide-to="7"></li>
			<li data-target="#myCarousel" data-slide-to="8"></li>
	  </ol>
	  <!-- Wrapper for slides -->
	  <div class="carousel-inner">
		<div class="item active">
		<div class="screen-content">
			Go To: <a href="#" target="_blank">https://www.linkedin.com/psettings/member-data</a>. You might have to Login in case you are not logged in to your LinkedIn account
		</div>
		  <img src="newui/images/goToURL-1.jpg" alt="">
		</div>
		<div class="item">
			 <div class="screen-content">
			As only your Public data of connections is required just select the Pick and Choose Option with Connections only.
		</div>
		  <img src="newui/images/slectOptions-2.jpg" alt="">
		</div>

		<div class="item">
		 <div class="screen-content">
			LinkedIn might have you re enter your password.
		</div>
		  <img src="newui/images/addPassword-3.jpg" alt="">
		</div>
		
		<div class="item">
		 <div class="screen-content">
			Once you add your Password LinkedIn will display Request Pending
		</div>
		  <img src="newui/images/requestPending-4.jpg" alt="">
		</div>
		
		<div class="item">
		 
		<div class="screen-content">
			Generally if you Reload this page, your Public Data will be ready. So try Reloading this page.

		</div>
		<img src="newui/images/reloadThePage-5.jpg" alt="">
		</div>
		
		<div class="item">
		
		<div class="screen-content">
			Your Public Data is now ready to be stored on your Computer.
		</div>
		<img src="newui/images/downloadArchive-6.jpg" alt="">
		</div>
		
		<div class="item">
		 
		<div class="screen-content">
			Store your data on your computer, I have used the Desktop above
		</div>
		<img src="newui/images/saveToComputer-7.jpg" alt="">
		</div>
		
		<div class="item">
		 
		<div class="screen-content">
			Your Public data has just one file Connections.csv that has publically available data of your contacts. You can now go ahead and upload this File.
		</div>
		<img src="newui/images/connectionsCsv.jpg" alt="">
		</div>
		
		<div class="item">
		<div class="screen-content">
			Above is Sample of of your Public Connections data looks like.
		</div>
		<img src="newui/images/csvContent.jpg" alt="">
		</div>
	  </div>

	  <!-- Left and right controls -->
	</div>
	</div>
</div>
<?php include_once('Analytics.php');?>
 <script>
 $('#myCarousel').carousel({
    interval: false
}); 
var checkSession = '<?php echo !empty($_SESSION['member']['userType'])?$_SESSION['member']['userType']:" ";?>';
if(checkSession=='recruiter')
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/employee-dashboard';
	}
 </script>
