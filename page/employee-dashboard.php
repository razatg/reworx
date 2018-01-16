<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="empController">
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
	<div class="search_container {{mainSearch}}">
		<div class="container">
			<div class="field_row">
				<div class="fld_col">
					<input  style="height:45px!important" ng-model="name" placeholder="Enter Name" class="form-control">
				</div>
				<div class="fld_col">
				<input id="companyId" style="height:45px!important" ng-model="company" placeholder="Enter Company name sepereated by comma (,)" class="form-control">
				</div>
			</div>
			<a class="search_btn" data-ng-click="currentPage=0;searchData()"></a>
		</div>
	</div>			
	
	<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
	<div ng-show="!showLoder" class="container">
			<ul class="list-item">
				<li ng-repeat="data in resultList.data">
					<img src="newui/images/1X1.png" style="background:url({{data.pic_phy}})" class="profile"/>
					   <div class="item-row" style="margin-bottom:30px;">
						   <div class="detail_info">
								<h1 style="text-align:left;" ng-bind-html="data.name"></h1>
								<h3 ng-if="data.experience[0].designation && data.experience[0].company" ng-bind-html="data.experience[0].designation+' at '+ data.experience[0].company"></h3> 
								<h3 ng-bind-html="data.designation"></h3>
								<p class="location" ng-bind-html="data.area"></p>
						   </div>
							<div class="detail_action">
								<a  class="detail_action_employee" target="_blank" href="{{data.profile_url}}"><img src="newui/images/linkden.png"></a>
								<a href="mailto:{{data.email}}" class="detail_action_employee"><img src="newui/images/mail.png"></a>
						</div>
					</div>
				</li>
				<li ng-if="resultStatus=='failure'">Please Check back after 24-72 hrs. We are reviewing your contacts</li>
			</ul>
			<div ng-show="totalPageLength>0" class="pagination">
			 <button ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1;searchData()">
				<< Previous
			</button>
			<button ng-disabled="currentPage >= totalPageLength/pageSize - 1" ng-click="currentPage=currentPage+1;searchData()">
				Next >>
			</button>
	  </div>
<?php include_once('login.php');?>	  
</div>
<script>
trackingApp.registerCtrl('empController',function($scope,$http, $location, $timeout, $element)
{
	$scope.checkSession = '<?php echo !empty($_SESSION['member']['userType'])?$_SESSION['member']['userType']:"";?>';
	if(!$scope.checkSession || $scope.checkSession!='employee')
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/';
	}
	$scope.resultList = {};
	$scope.currentPage = 0;
	$scope.totalPageLength = 0;
    $scope.pageSize = 10;
    $scope.q = '';
    $scope.showLoder = false;
	$scope.searchData = function()
	{
		$scope.showLoder = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/employee-data.php';
		$http.post(absUrl,{page:$scope.currentPage,company:$scope.company,name:$scope.name}).success(function(response)
		{
			$scope.resultList = response;
			$scope.resultStatus = response.status;
			$scope.totalPageLength = response.totalCount;
			$scope.showLoder = false;
		})
	}
	$scope.searchData();
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
    background-color: #0085c8;
    color: #fff;
}
button:disabled {
    background: #ddd;
    color: #999;
}
</style>
