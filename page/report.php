<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="reportController">
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
      <li class="network_btn"><a href="#" data-toggle="modal" data-target="#myModal">Compose and Send Email</a>
    </ul>
  </div>
  </div>
</nav>
</header>

<div class="bodypan" ng-style="{'min-height':divHeight()}">
	<div class="container">
		{{reportList}}
	</div>
	</div>
<script>
trackingApp.registerCtrl('reportController',function($scope,$http, $location, $timeout, $element)
{
    $scope.showLoder = false;
	$scope.getReport = function()
	{
		$scope.showLoder = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/reports.php';
		$http.post(absUrl).success(function(response)
		{
			$scope.reportList = response;
			$scope.showLoder = false;
			
		})
	}
	$scope.getReport();
	
	
})
</script>
<style>
.pagination {
    display: inline-block;
    float: right;
    padding-bottom: 20px;
}

.pagination button {
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    background-color:#0085c8;
    color: #fff;
}
button:disabled {
    background: #ddd;
    color: #999;
}
</style>
