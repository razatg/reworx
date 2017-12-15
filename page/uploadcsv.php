<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="homeController">
<header>
	<div class="container">
		<?php include_once('navbar.php');?>
	</div>
</header>	
<div class="banner-container">
	<h1><span class="light_green">Get Rewards,</span><span class="green"> by referring your Contacts and Friends.</span><br>
	Upload your LinkedIn Contacts to get started</h1>
	<div  class="linkden_container">
		<p ng-if="errorCsvMsg" style="color:red;" class="warning-error" ng-bind-html="errorCsvMsg"></p>
		<img src="newui/images/linkdenlogo.jpg" alt="">
		<h1>Upload your LinkedIn CSV </h1>
		<?php if(!empty($_SESSION['member']['email'])){?>
			<div class="fileUpload">
			<label class="upload application-btn">
				<input ng-if="!showLoder" type="file" onchange="angular.element(this).scope().setFile(this)">
		    	Upload
			</label>
		  </div>
		<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
		<?php } 
		else
		 {?>
		  <a class="application-btn" ng-click="togglePopup('show')">Upload</a>
		<?php }?>
		<a class="more_btn" target="_blank" href="<?php echo ANGULAR_ROUTE; ?>/tutorial">Learn how..</a>
	</div>
	<h2>You will be providing publically accessible information Only.<br>
	 We will never contact anyone on your behalf</h2>
</div>
<?php include_once('login.php');?>
</div>
<script>
trackingApp.registerCtrl('homeController',function($scope,$http, $location, $timeout, $element)
{
	   $scope.showLoder = false;
	   $scope.setFile = function(element) 
	   {
			$scope.errorCsvMsg = '';
			$scope.showLoder = true;
			$scope.$apply(function($scope) 
			{
				$scope.files = element.files[0];
				var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/csvupload.php';
				var fd = new FormData()
				fd.append("imageName", element.files[0]);
				$http.post(absUrl, fd, 
				{
					transformRequest: angular.identity,
					headers: {
					'Content-Type': undefined
					}
				})
				.success(function(response) 
				{
					 $scope.showLoder = false;
					if(response.status=='success')
					{
						window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/employee-dashboard';
					}
					else if(response.status=='alredyuploaded')
					{
						$scope.errorCsvMsg = 'You have already uploaded your CSV file.'
						window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/employee-dashboard';
					}
					else if(response.status=='not valid')
					{
						$scope.errorCsvMsg = 'Please upload valid CSV and ZIP file.'
					}
					else
					{
						$scope.errorCsvMsg = 'Please upload valid CSV and ZIP file.'
					}

				});
			});
    };
    
    $scope.togglePopup = function(tmp)
    {
		$('#myModal').modal(tmp);
	}

});	
</script>

