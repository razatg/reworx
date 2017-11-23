<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="searchController">
<header>
	<div class="grid-center">
	<div id="navmobile">
		<nav>
			<ul>
				<li><a href="#">Analytics</a></li>
				<li><a href="#">Tracking</a></li>
				<li><a href="#">Connect Your Networks</a></li>
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
			<li ng-if="addtolistdata>0" class="network_btn"><a href="<?php echo ANGULAR_ROUTE; ?>/request">{{addtolistdata}} &nbsp; Shortlisted </a></li>
		</ul>
	</nav>
	<!-- Nav Desktop -->
</header>
<div class="bodypan" ng-style="{'min-height':divHeight()}">
	<div class="search_container">
		<div class="grid-center">
			<style>.customCatComplete{
							background: none !important;
							width: 288px !important;
							margin: 0 !important;
							border: none !important;
							outline: 0 !important;
							padding: 0px 0 2px !important;
												}
						.rnz_new_fld span{    width: 100px;
							font-weight: bold;
							float: left;
							display: inline-block;
							padding: 12px 0;}
						.rnz_new_fld select{    width: calc(100% - 100px);
							float: left;
							height: 45px;}
							.rnz_new_fld_btn{    height: 45px;
							float: left;
							background: #000;
							color: #fff5f5;
							width: 115px;
							font-weight: bold;
							padding: 11px 0;
						}
						.rnz_new_fld_btn:hover{color:#fff;}
						.autocomplete-options-dropdown {
							position: absolute;
							top: 10px;
							left: -9.5px;
							border: 1px solid #ccc;
							border-top-color: #d9d9d9;
							box-shadow: 0 2px 4px rgba(0,0,0,0.2);
							-webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.2);
							cursor: default;
							z-index: 1001;
							background: white;
							WIDTH: 104%;
						}
					</style>
			<div class="field_row">
				
				<div class="fld_col">
					<ul class="search_list_container">
					<li class="search_list" ng-repeat="data in multipleArrList track by $index">
                      <a ng-click="removeFromSearchList($index,'position')" class="glyphicon glyphicon-remove search_list_remove"></a>
					{{data}}
					</li>
					<li><autocomplete name="position" ng-keypress="searchEnter($event);"  ng-keyup="fetchData('position')" ng-model="position" options="datas" 
						place-holder="Enter a Position (e.g Java Developer) or Skill (eg Java).."
						on-select="onSelect" 
						display-property="title"
						input-class="form-control customCatComplete"
						clear-input="false">
				</autocomplete></li>
			 
					</ul>
				</div>
				<div class="fld_col">
				<ul class="search_list_container">
					<li class="search_list" ng-repeat="data in multipleCompanyArrList track by $index">
                      <a ng-click="removeFromSearchList($index,'company')" class="glyphicon glyphicon-remove search_list_remove"></a>
					{{data}}
					</li>
					
					<li><autocomplete name="company" ng-keypress="searchEnter($event);"  ng-keyup="fetchData('company')" ng-model="company" options="companyList" 
						place-holder="Company"
						on-select="onSelectComp" 
						display-property="title_comp"
						input-class="form-control customCatComplete"
						clear-input="false">
				</autocomplete></li>
					</ul>	
				</div>
			</div>
			<a class="search_btn" data-ng-click="searchData();"></a>
			<a data-ng-click="showFilter()" class="filter_btn"></a>
		</div>
	</div>			
		
 
		<div class="search_container" ng-show="showAdvancedFilter" style="padding: 0;">
 
		<div class="grid-center">
			<div class="field_row">
				<div class="fld_col">
					
					 <autocomplete name="location" ng-keypress="searchEnter($event);"  ng-keyup="fetchData('location')" ng-model="location" options="locationData" 
						place-holder="Search Geo Location"
						on-select="onSelectArea" 
						display-property="area"
						input-class="form-control"
						clear-input="false">
				</autocomplete> 
			 
				 
					 
					<style>
					
			 
					</style>
				
				</div>
				
				<div class="fld_col rnz_new_fld">
				<span>Experience</span>
				
				<select name="year_of_experience" class="form-control">
				<option> 1 </option>
				<option> 2 </option>
				<option> 3 </option>
				<option> 4 </option>
				<option> 5 </option>
				<option> 6 </option>
				<option> 7 </option>
				</select>
			</div>
			</div>
			<a class="btn rnz_new_fld_btn" data-ng-click="searchData();">Search</a>
		</div>
	</div>			
		
		
		<div class="grid-center">
			<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
			 <ul ng-if="!showLoder" class="list-item">
				<li ng-repeat="data in resultList.data">
					<img src="newui/images/1X1.png" style="background:url({{data.pic_phy}})" class="profile"/>
					   <div class="item-row">
						   <div class="detail_info">
								<h1 style="text-align:left;" ng-bind-html="data.name"></h1>
								<h3 ng-if="data.experience[0].designation && data.experience[0].company" ng-bind-html="data.experience[0].designation+' at '+ data.experience[0].company"></h3> 
								<h3 ng-bind-html="data.designation"></h3>
								<p class="location" ng-bind-html="data.area"></p>
								<!--<img src="newui/images/1X1.png" style="background:url({{data.pic_phy}})" class="profile">-->
								<div ng-repeat="item in data.connectedUsers">
								<img  src="newui/images/1X1.png" style="background:url({{item.pic_phy}})" class="profile">
								</div>
						   </div>
							<div class="detail_action">
								<a  class="" target="_blank" href="{{data.profile_url}}"><img src="newui/images/linkden.png"></a>
								<a href="mailto:{{data.email}}" class=""><img src="newui/images/mail.png"></a>
								<a style="background:{{data.IsEdit==true?'#93ABA4':''}}" class="{{data.IsEdit==true?'btn-res':'btn-res'}}" data-ng-click="addtolist(data.UID,data);">{{data.IsEdit==true?'Remove from list':'Shortlist'}}</a>
						</div>
					</div>
				</li>
				<li ng-show="resultStatus=='failure'">No Results Found. Please try , different keyword or company or keyord company combination</li>
			</ul>
			
			<div ng-show="totalPageLength>0" class="pagination">
			 <button ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1;searchData()">
				<< Previous
			</button>
			<button ng-disabled="currentPage >= totalPageLength/pageSize - 1" ng-click="currentPage=currentPage+1;searchData()">
				Next >>
			</button>
		   </div>	
	  </div>
<?php include_once('login.php');?>	  
</div>
<script>
trackingApp.registerCtrl('searchController',function($scope,$http, $location, $timeout, $element)
{
	$scope.resultList = {};
	$scope.removeFromSearchList = function(index,type)
	{
		if(type=='company')
		{
			$scope.multipleCompanyArrList.splice(index,1);
		}
		else
		{
			$scope.multipleArrList.splice(index,1);
		}
		
	}   
	$scope.showAdvancedFilter = false;
	$scope.showFilter = function()
	{
		if($scope.showAdvancedFilter)
		{
			$scope.showAdvancedFilter = false;
		}
		else
		{
			$scope.showAdvancedFilter = true;
		}
	}
	$scope.currentPage = 0;
	$scope.totalPageLength = 0;
    $scope.pageSize = 10;
    $scope.q = '';
    $scope.showLoder = false;
	$scope.searchData = function()
	{
		$scope.showLoder = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/get-data.php';
		$http.post(absUrl,{position:$scope.position,company:$scope.company,page:$scope.currentPage,location:$scope.location}).success(function(response)
		{
			$scope.resultList = response;
			$scope.resultStatus = response.status;
			 $scope.showLoder = false;
			$scope.totalPageLength = response.totalCount;
		})
	}
	$scope.searchData();
	$scope.searchEnter = function(event)
	{
		if(event.keyCode=='13')
		{
			$scope.searchData();
		}
	};
	$scope.multipleArrList = [];
	$scope.multipleCompanyArrList = [];
	$scope.onSelect = function(selection) 
	{
		$scope.selectedData = selection;
		if($scope.multipleArrList.length>0)
		{
			for(var i=0;i<$scope.multipleArrList.length;i++)
			{
				if($scope.multipleArrList[i].title!=selection.title)
				{
					var movetoList = selection.title;
				}
			}
			
			$scope.multipleArrList.push(movetoList);
		}
		else
		{
			$scope.multipleArrList.push(selection.title);	
		}
		
		$scope.position  = '';
	};
	
	$scope.onSelectComp = function(company)
	{
		$scope.selectedData = company;
		if($scope.multipleCompanyArrList.length>0)
		{
				for(var i=0;i<$scope.multipleCompanyArrList.length;i++)
				{
					if($scope.multipleCompanyArrList[i].title_comp!=company.title_comp)
					{
						var movetoList = company.title_comp;
					}
				}
				
				$scope.multipleCompanyArrList.push(movetoList);
			}
			else
			{
				
				$scope.multipleCompanyArrList.push(company.title_comp);	
			}
			
			$scope.company  = '';	
	}
	
	$scope.onSelectArea = function(area)
	{
		$scope.locationData = area;
	}
	
	$scope.selectedData = null;
	$scope.resultStatus = '';
	$scope.datas = {};
	$scope.companyList = {};
	$scope.fetchData = function(type)
	{
	  if($scope[type].length>3)
	  {    
		 var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/autosuggestion.php';
		 $http.post(absUrl,{keywords:$scope[type],type:type}).success(function(response)
			{
				
				if(response.data.length>0)
				{
					if(type== 'company')
					{
						$scope.companyList = angular.copy(response.data);
					}
					else if(type== 'location')
					{
						$scope.locationData = angular.copy(response.data);
					}
					else
					{
						$scope.datas = angular.copy(response.data);
					//	$scope.skillsList = angular.copy(response.data);
					}
					
				}
			})  
	  }
	}
	$scope.numberOfPages=function()
    {
        return Math.ceil($scope.totalPageLength/$scope.pageSize);                
    }
	$scope.selectedUIDs =[];
    $scope.addtolist = function(ID,data)
	{
		data.IsEdit=!data.IsEdit;
		var tempArray=[];
		var tempArray1=[];
		var isExist=false;
			angular.forEach($scope.selectedUIDs,function(id){
			if(id==ID){
			isExist=true;
			}else{
			tempArray.push(id);
			}
			
			});
			angular.forEach($scope.selectedProfiles,function(id){
			if(id==data){
			isExist=true;
			}else{
			tempArray1.push(id);
			}
			
			});
			if(!isExist)
			{
				tempArray.push(ID);
				tempArray1.push(data);
			}
			
			$scope.selectedUIDs=tempArray;
			$scope.selectedProfiles = tempArray1;
			var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/add-to-list.php';
			$http.post(absUrl,{UIDList:$scope.selectedUIDs}).success(function(response)
			{
			})
			$scope.addtolistdata = $scope.selectedUIDs.length;
	}
	
	$scope.getlist = function()
	{
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/get-list.php';
		$http.post(absUrl).success(function(response)
		{
			 $scope.addtolistdata = response.length;
			 $scope.selectedUIDs  = response;
		})
	}
	$scope.getlist();
	
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
