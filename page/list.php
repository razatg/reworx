<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="ListController">
 <?php include_once('Analytics.php');?>
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
<div class="bodypan" ng-style="{'min-height':divHeight()}">
	<div class="container">
		<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
		<ul ng-if="!showLoder" class="list-item">
			<li ng-repeat="data in resultList.data">
			<img src="newui/images/1X1.png" style="background:url({{data.pic_phy}})" class="profile"/>
			<div class="item-row">
				<div class="detail_info">
					<h1 ng-bind-html="data.name"></h1>
					<h3 ng-if="data.experience[0].designation && data.experience[0].company" ng-bind-html="data.experience[0].designation+' at '+ data.experience[0].company"></h3> 
					<h3 ng-bind-html="data.designation"></h3>
					<p class="location" ng-bind-html="data.area"></p>
					<a class="btn-res" data-ng-click="removeFromlist(data.UID);">Remove from list</a>
				</div>
			</div>
			</li>
			<li ng-show="resultList.status=='failure'">Great! Search for more Awesome Candidates. <a href="<?php echo ANGULAR_ROUTE; ?>/employee-dashboard">Click Here</a></li>
		</ul>
	</div>
	</div>
<script>
trackingApp.registerCtrl('ListController',function($scope,$http, $location, $timeout, $element)
{
	$scope.checkSession = '<?php echo !empty($_SESSION['member']['userType'])?$_SESSION['member']['userType']:"";?>';
	if(!$scope.checkSession || $scope.checkSession!='employee')
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/';
	}
	
	$scope.resultList = {};
	$scope.showpanelView = function () 
    { 
		if($scope.showPanel)
		{
		  $scope.showPanel	= false;
		}
		else
		{
			$scope.showPanel	= true;
		}
    }
     $scope.removeFromlist = function(ID)
	 {
	
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/mark-as-good.php';
		$http.post(absUrl,{UID:ID,type:false}).success(function(response)
		{
			$scope.searchData();
		})
	 }
	
	$scope.currentPage = 0;
	$scope.totalPageLength = 0;
    $scope.pageSize = 10;
    $scope.q = '';
    $scope.showLoder = false;
	$scope.searchData = function(type)
	{
		$scope.showLoder = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/list-user-data.php';
		$http.post(absUrl,{position:$scope.position,company:$scope.company,page:$scope.currentPage}).success(function(response)
		{
			$scope.resultList = response;
			$scope.showLoder = false;
			$scope.totalPageLength = response.totalCount;
		})
	}
	$scope.searchData('get');
	$scope.resultStatus = '';
	$scope.datas = {};
	$scope.companyList = {};
	$scope.numberOfPages=function()
    {
        return Math.ceil($scope.totalPageLength/$scope.pageSize);                
    }
	
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
