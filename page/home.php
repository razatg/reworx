<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="homeController">
<header>
	<div class="container">
	 	<?php include_once('navbar.php');?>
	</div>
    <nav class="navbar navbar-toggleable-sm navbar-light bg-faded" style="display:none">
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

<div class="homebanner"> 
    <img src="newui/images/2X1.png" class="header_bg" alt=""/>
    <div class="banner">
    <div class="container grid-center">
	<div class="freetrailForm">
			<span ng-if="errorMsg" class="warning-error" ng-bind-html="errorMsg"></span>
		<h2>Hire the best talent from your Employees' Network</h2>
		<form>
			<input type="text" ng-model="register.company_name" placeholder="Name"/>
			<span ng-if="errorNameMsg" class="warning-error"  ng-bind-html="errorNameMsg"></span>
			<input type="text" ng-model="register.email" placeholder="Work Email"/>
			<span ng-if="errorEmailMsg" class="warning-error" ng-bind-html="errorEmailMsg"></span>
			<input type="text" ng-model="register.mobile_number" placeholder="Mobile No"/>
			<span ng-if="errorMobileMsg" class="warning-error" ng-bind-html="errorMobileMsg"></span>
			<h2  ng-if="successMsg"  style="color:#fff;font-size:21px;" ng-bind-html="successMsg"></h2>
              <div style="margin-top:20px;">
			<button ng-if="!showLodermail && !successMsg" data-ng-click="userRegister();" class="trailBtn" href="#">
				Start Free Trial
				</button></div>
			<center  ng-if="showLodermail"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
		</form>
	</div>
    </div>
    </div>
</div>
<div class=" testimonial-second">
<section class="container text-center grid-center">
 

    <!--Carousel Wrapper-->
   <div id="carousel-generic" class="testimonial-carousel  slide" data-ride="carousel">
		<h4>Testimonial</h4>
        <!--Slides-->
         <div class="carousel-inner-hidden">
        <div class="carousel-inner" role="listbox">
       
            <!--First slide-->
            <div class="item active">

                <div class="testimonial">
                    
                    <h4>Neha Tyagi, AppyPie</h4>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <!--Content-->
                    <p><i class="fa fa-quote-left"></i> We suddenly had database of 1000s of relevant profiles from networks of our team and work process to automate and manage referrals at scale.. This has helped us both reduce the time to close new positions and the most obvious benefit has been cost, as we are spending a fraction of what we did, per position.
</p>   
                </div>

            </div>
            <!--First slide-->

            <!--Second slide-->
             
            <!--Second slide-->

            <!--Third slide-->
             
            <!--Third slide-->
			</div>
        </div>
        <!--Slides-->

        

    </div>
    <!--Carousel Wrapper-->

</section>
</div>

<div class="client_wrapper">
	<div class="container grid-center text-center">
    	<h4>Clients</h4>
        <img src="newui/images/client_logo.png">
    </div>
</div>

<div class="howitwork">
	<div class="container grid-center text-center">
    <h4>How it Works</h4>
    	<div class="row">
          <div class="col-lg-4 col-sm-4 text-center mb-4">
          <div class="work_digit">1</div>
          <p>Search easily from Qualified Network of your own Employees.</p>
        </div>
        <div class="col-lg-4 col-sm-4 text-center mb-4">
          <div class="work_digit">2</div>
          <p>Have your employee's Reach out to their network with ease.</p>
        </div>
        <div class="col-lg-4 col-sm-4 text-center mb-4">
          <div class="work_digit">3</div>
          <p>Track the referral process end to end and reward your employees.</p>
        </div>
        </div>
    </div>
</div>



<?php include_once('login.php');?>
</div>
<script>
trackingApp.registerCtrl('homeController',function($scope,$http, $location, $timeout, $element,$rootScope)
{
	if($rootScope.currentUrl=='/login')
	{
		$('#myModal').modal('show');
	}
	$scope.register = {};
	$scope.checkSession = '<?php echo !empty($_SESSION['member']['userType'])?$_SESSION['member']['userType']:" ";?>';
	$scope.employeeName = '<?php echo !empty($_SESSION['member']['first_name'])?$_SESSION['member']['first_name']:" ";?>';
	if($scope.checkSession=='employee')
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/employee-dashboard';
	}
	else if($scope.checkSession=='recruiter')
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/search';
	}
	$scope.showLodermail = false;
	$scope.userRegister = function()
	{
		$scope.errorMsg = '';
		$scope.errorEmailMsg = '';
		$scope.errorMobileMsg = '';
		$scope.errorNameMsg = '';
		$scope.successMsg = '';
		if($scope.register.email && $scope.register.company_name && $scope.register.mobile_number)
		{
			var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,20}$/i);
			var phonePattern = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,8}$/im;
			
			
		    if($scope.register.email && !pattern.test($scope.register.email))
			{
				$scope.errorEmailMsg = 'Please enter your valid Work Email.';
				return false;
			}
			else if($scope.register.email && ($scope.register.email.match(/yahoo/i) || $scope.register.email.match(/gmail/i) || $scope.register.email.match(/rediff/i) || $scope.register.email.match(/aol/i) || $scope.register.email.match(/msn/i) || $scope.register.email.match(/live/i) || $scope.register.email.match(/hotmail/i)))
			{
				$scope.errorEmailMsg =  'Please enter your Work Email instead of Company email not valid.';
				return false;
			}
			if($scope.register.mobile_number && !phonePattern.test($scope.register.mobile_number))
			{
				$scope.errorMobileMsg = 'Please enter valid mobile number.';
				return false;
			}
			$scope.showLodermail = true;
			var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/save-user.php';
			$http.post(absUrl,{data:$scope.register}).success(function(response)
			{
				if(response.status=='success')
				{
					$scope.successMsg = response.msg;
					$scope.register = {};
				}
				else
				{
					$scope.errorMsg = response.msg;
				}
				$scope.showLodermail = false;
			})
		}
		else
		{
			if(!$scope.register.email)
			{
				$scope.errorEmailMsg = 'Please enter your Work Email.';
			}
			if(!$scope.register.company_name)
			{
				$scope.errorNameMsg = 'Please enter company name.';
			}
			if(!$scope.register.mobile_number)
			{
				$scope.errorMobileMsg = 'Please enter mobile number.';
			}
			return false;
			
		}
	}

});	
</script>

