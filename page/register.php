<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="registerController">
<header>
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
  </div>
  </div>
</nav>
</header>
<div class="bodypan">
	<div class="search_container">
		<div class="container">
			<h1>Recruiter Registration</h1>
			<span ng-if="errorMsg" style="color:red;" ng-bind-html="errorMsg"></span>
			<span ng-if="successMsg" style="color:green;" ng-bind-html="successMsg"></span>	
			<div class="field_row_register">
				<input type="email" name="email" ng-model="register.email" placeholder="Email" class="form-control">
			</div>
			<div class="field_row_register">
				  <input type="password" name="password" ng-model="register.password" placeholder="Password" class="form-control">	
			</div>
			<div class="field_row_register">
				<input type="text" name="company_name" ng-model="register.company_name" placeholder="Comapny Name" class="form-control">
			</div>
			<div class="field_row_register">
				<input type="text" name="contact_person" ng-model="register.contact_person" placeholder="Contact Person" class="form-control">
			</div>
			<div class="field_row_register">
				<input type="text" name="designation" ng-model="register.designation" placeholder="Desigation" class="form-control">
			</div>
			<div class="field_row_register">
				<input type="text" name="office_address" ng-model="register.office_address" placeholder="Office Address" class="form-control">
			</div>
			<div class="field_row_register">
				<input type="text" name="mobile_number" ng-model="register.mobile_number" placeholder="Mobile Number" class="form-control">
			</div>
		   <div class="field_row_register">	
            <div class="network_btn">
				<a data-ng-click="userRegister();">Register</a>
			</div>
			</div>		
		</div>
	</div>
<script>
trackingApp.registerCtrl('registerController',function($scope,$http, $location, $timeout, $element)
{
	$scope.register = {};
	$scope.userRegister = function()
	{
		if($scope.register.email && $scope.register.password && $scope.register.company_name && $scope.register.contact_person && $scope.register.designation && $scope.register.office_address && $scope.register.mobile_number)
		{
			var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/save-user.php';
			$http.post(absUrl,{data:$scope.register}).success(function(response)
			{
				if(response.status=='success')
				{
					$scope.successMsg = response.msg;
				}
				else
				{
					$scope.errorMsg = response.msg;
				}
			})
		}
		else
		{
			if(!$scope.register.email)
			{
				$scope.errorMsg = 'Please enter email id';
			}
			else if(!$scope.register.password)
			{
				$scope.errorMsg = 'Please enter password';
			}
			else if(!$scope.register.company_name)
			{
				$scope.errorMsg = 'Please enter company name';
			}
			else if(!$scope.register.contact_person)
			{
				$scope.errorMsg = 'Please enter contact person name';
			}
			else if(!$scope.register.designation)
			{
				$scope.errorMsg = 'Please enter designation';
			}
			else if(!$scope.register.office_address)
			{
				$scope.errorMsg = 'Please enter office address';
			}
			else if(!$scope.register.mobile_number)
			{
				$scope.errorMsg = 'Please enter mobile number';
			}
			return false;
			
		}
	}
})
</script>
