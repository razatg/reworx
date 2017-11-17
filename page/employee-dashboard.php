<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="empController">
<header>
	<div class="grid-center">
	<div id="navmobile">
		<nav>
			<ul>
			    <li class="active"><a href="#">Home</a></li>
				<li><a href="#">Analytics</a></li>
				<li><a href="#">Tracking</a></li>
				<?php
				 if($_SESSION['member']['userType']==='employee')
				{?>
				   <li><a href="refer/<?php echo $_SESSION['member']['UID'];?>">Refer</a></li>
			  <?php }
			  ?>
			</ul>
		</nav>
	</div>
		<!-- Nav Mobile -->
		<?php include_once('navbar.php');?>
		<div class="search_container">
			<a class=" search_btn"></a>
			<a class=" filter_btn"></a>
		</div>
	</div>
	<!-- Nav Mobile -->
	<!-- Nav Desktop -->
	<nav id="navdektop" class="main_menu">
		<ul id="idmenu">
			<li class="active"><a href="#">Home</a></li>
			<li><a href="#">Analytics</a></li>
			<li><a href="#">Tracking</a></li>
			<?php
				 if($_SESSION['member']['userType']==='employee')
				{?>
				   <li><a href="refer/<?php echo $_SESSION['member']['UID'];?>">Refer</a></li>
			  <?php }
			  ?>
		</ul>
	</nav>
	<!-- Nav Desktop -->
</header>
<div class="bodypan" ng-style="{'min-height':divHeight()}">
	<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
	<div ng-if="!showLoder" class="grid-center">
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
		$http.post(absUrl,{page:$scope.currentPage}).success(function(response)
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
    background-color: #54c7b1;
    color: #fff;
}
button:disabled {
    background: #ddd;
    color: #999;
}
</style>
