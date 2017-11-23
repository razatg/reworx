<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="homeController">
<header>
	<div class="container  grid-center">
	<nav id="navdektop" class="mainMenu">
	<ul id="idmenu">
	<li><a href="#">How it Works</a></li>
    <li><a href="#">Blog</a></li>
    <li><a href="#">About us</a></li>
    <li><?php 
			if(!empty($_SESSION['member']['email'])){?>
				<a ng-click="logout()">Logout</a>
			<?php } 
			else
			{?>
			<a ng-click="togglePopup('show')">Login</a>
			<?php }
			?>
		</li>
	</ul>
</nav>
	</div>
</header>	
<div class="homebanner">
	<img src="newui/images/2X1.png" alt="" style="background:url(newui/images/home-slider.jpg) no-repeat;" width="100%">
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
                    
                    <h4>Anna Deynah</h4>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <!--Content-->
                    <p><i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur quae quaerat ad velit ab. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore cum accusamus eveniet molestias voluptatum inventore laboriosam labore sit, aspernatur praesentium iste impedit quidem dolor veniam.</p>   
                </div>

            </div>
            <!--First slide-->

            <!--Second slide-->
            <div class="item">

                <div class="testimonial">
                     
                    <h4>Anna Deynah</h4>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <!--Content-->
                    <p><i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur quae quaerat ad velit ab. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore cum accusamus eveniet molestias voluptatum inventore laboriosam labore sit, aspernatur praesentium iste impedit quidem dolor veniam.</p>   
                </div>

            </div>
            <!--Second slide-->

            <!--Third slide-->
            <div class="item">

                <div class="testimonial">
                     
                    <h4>Anna Deynah</h4>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <i class="glyphicon glyphicon-star"></i>
                    <!--Content-->
                    <p><i class="fa fa-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur quae quaerat ad velit ab. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore cum accusamus eveniet molestias voluptatum inventore laboriosam labore sit, aspernatur praesentium iste impedit quidem dolor veniam.</p>   
                </div>

            </div>
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
          <p>Search easily from Qulified Network of your own Employees</p>
        </div>
        <div class="col-lg-4 col-sm-4 text-center mb-4">
          <div class="work_digit">2</div>
          <p>Have your employee's Reach outto their network with ease</p>
        </div>
        <div class="col-lg-4 col-sm-4 text-center mb-4">
          <div class="work_digit">3</div>
          <p> Track the referral process end to end and reward your employees</p>
        </div>
        </div>
    </div>
</div>



<?php include_once('login.php');?>
</div>
<script>
trackingApp.registerCtrl('homeController',function($scope,$http, $location, $timeout, $element)
{
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
				$scope.errorEmailMsg = 'Please enter valid company email address.';
				return false;
			}
			else if($scope.register.email && ($scope.register.email.match(/yahoo/i) || $scope.register.email.match(/gmail/i) || $scope.register.email.match(/rediff/i) || $scope.register.email.match(/aol/i) || $scope.register.email.match(/msn/i) || $scope.register.email.match(/live/i) || $scope.register.email.match(/hotmail/i)))
			{
				$scope.errorEmailMsg =  'Company email not valid.';
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
				$scope.errorEmailMsg = 'Please enter company email address.';
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

