<?php 
ini_set('display_errors', 1);
include_once('../config-ini.php');
?>
<div data-ng-controller="searchController">
 
<header>
	<div class="container">
	 	<?php include_once('navbar.php');?>
		<div class="search_container">
			<a class=" search_btn"></a>
			<a class=" filter_btn"></a>
		</div>
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
      <li ng-if="addtolistdata>0" class="network_btn"><a href="<?php echo ANGULAR_ROUTE; ?>/request">{{addtolistdata}} &nbsp; Shortlisted </a></li>
      
    </ul>
  </div>
  </div>
</nav>
</header>


<div class="bodypan" ng-style="{'min-height':divHeight()}">
	<div class="search_container">
		<div class="container">
		 
			<div class="field_row">
				
				<div class="fld_col">
					<ul class="search_list_container">
					<li class="search_list" ng-repeat="data in multipleArrList track by $index">
                      <a ng-click="removeFromSearchList($index,'position')" class="glyphicon glyphicon-remove search_list_remove"></a>
					{{data}}
					</li>
					<li><autocomplete name="position" ng-blur="onSelect(position)" ng-keypress="searchEnter($event);"  ng-keyup="fetchData('position')" ng-model="position" options="datas" 
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
						input-class="form-control customCatComplete1"
						clear-input="false">
				</autocomplete></li>
					</ul>	
				</div>
			</div>
			<a class="search_btn" data-ng-click="searchData();"></a>
			<a data-ng-click="showFilter()" class="filter_btn"></a>
		</div>
	</div>			
	<div class="search_container second_container" ng-show="showAdvancedFilter" style="padding:0; padding-bottom:15px;">
		<div class="container">
			<div class="field_row">
				<div class="fld_col customLocation">
					 <autocomplete name="location" ng-keypress="searchEnter($event);"  ng-keyup="fetchData('location')" ng-model="location" options="locationData" 
						place-holder="Search Geo Location"
						on-select="onSelectArea" 
						display-property="area"
						input-class="form-control"
						clear-input="false">
				</autocomplete> 
				</div>
				<div class="fld_col rnz_new_fld">
				<span>Experience</span>
				<select ng-init="total_experience=yearOfExp[0]" ng-model="total_experience" class="form-control" ng-options="item for item in yearOfExp"></select>
			</div>
			</div>
			<a class="btn rnz_new_fld_btn" data-ng-click="searchData();">Search</a>
		</div>
	  </div>			
		<div class="container">
			<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
			 <ul ng-if="!showLoder" class="list-item">
				<li ng-repeat="data in resultList.data">
					<img src="newui/images/1X1.png" style="background:url({{data.pic_phy}})" class="profile"/>
					   <div class="item-row">
						   <div class="detail_info">
								<h1 style="text-align:left;" ng-bind-html="data.name"></h1>
								<h3 ng-if="data.experience[0].designation && data.experience[0].company" ng-bind-html="data.experience[0].designation+' at '+ data.experience[0].company"></h3> 
								<h3 ng-if="!data.experience[0].designation && !data.experience[0].company" ng-bind-html="data.company"></h3>
								<p  class="location" ng-bind-html="data.area"></p>
								
								<div ng-repeat="item in data.connectedUsers" class="relative-pos">
										<div ng-show="$parent.$index==parentIndex && childIndex==$index" class="custom-tooltip">
										<span class="name" ng-bind-html="item.name"></span>
										<span class="occupation" ng-if="item.company" ng-bind-html="item.company"></span>
										<span class="department" ng-if="item.designation" ng-bind-html="item.designation"></span>
									</div>
								 <img ng-mouseleave="showToolTips('-1')" ng-mouseover="showToolTips($index,$parent.$index)" src="newui/images/1X1.png" style="background:url({{item.pic_phy}})" class="profile">
								</div>
						     	<br/><b ng-if="data.featured_skiils.length>0 && multipleArrList.length>0">Featured Skills:</b> <span data-ng-repeat="skill in data.featured_skiils">
								<small ng-if="multipleArrList.length>0" ng-bind-html="highlight(skill, multipleArrList[0])"></small>
								</span>
								<br/><b ng-if="multipleArrList.length>0 && data.title && highlight(data.title, multipleArrList[0])">Title:</b> <small ng-if="multipleArrList.length>0 && data.title" ng-bind-html="highlight(data.title, multipleArrList[0])"> </small>
								<br/> <b ng-if="multipleArrList.length>0 && data.summary && highlight(data.summary, multipleArrList[0])">Summary:</b> <small ng-if="multipleArrList.length>0 && data.summary" ng-bind-html="highlight(data.summary, multipleArrList[0],'summary')"> </small>
								<br/> <b ng-if="data.experience.length>0 && multipleArrList.length>0">Experience :</b>
                                 <span data-ng-repeat="expe in data.experience">
								<small ng-if="multipleArrList.length>0 && expe.designation" ng-bind-html="highlight(expe.designation, multipleArrList[0])"></small>
								</span>	
						     </div>
							<div class="detail_action">
								<a target="_blank" href="{{data.profile_url}}"><img src="newui/images/linkden.png"></a>
								<a href="mailto:{{data.email}}" class=""><img src="newui/images/mail.png"></a>
								<a style="background:{{data.IsEdit==true?'#93ABA4':''}}" class="{{data.IsEdit==true?'btn-res':'btn-res'}}" data-ng-click="addtolist(data.UID,data);">{{data.IsEdit==true?'Remove from list':'Shortlist'}}</a>
						</div>
					</div>
				</li>
				<li ng-show="resultStatus=='failure'">No Results Found. Please try , different keyword or company or keyord company combination</li>
			</ul>
			<div ng-show="totalPageLength>0 && !showLoder" class="pagination">
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
trackingApp.registerCtrl('searchController',function($scope,$http, $location, $timeout, $element,$sce)
{
    
    $scope.highlight = function(text, search) 
    {
		var tmpStr  = text.match(search);
		var newStr = tmpStr[0];
		if(search && text.indexOf(search) !== -1)
		{
			return $sce.trustAsHtml(newStr.replace(new RegExp(search, 'gi'), '<span class="highlightedText">$&</span>'));
		}
		else
		{
			return $sce.trustAsHtml('');
		}
		
	};

	$scope.showToolTips = function(index,parent)
	{
		$scope.childIndex = index;
		$scope.parentIndex = parent;
	}
	
	$scope.resultList = {};
	$scope.multipleArrList = [];
	$scope.multipleCompanyArrList = [];
	$scope.yearOfExp = ['Select Experience'];
	for(var i = 1;i<=50;i++)
	{
		$scope.yearOfExp.push(i);
	}
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
			$scope.location = '';
			$scope.total_experience = 'Select Experience';
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
        if($scope.position)
        {
			$scope.multipleArrList.push($scope.position);
			$scope.position  = '';	
		}
		if($scope.company)
        {
			$scope.multipleCompanyArrList.push($scope.company);
			$scope.company  = '';	
		}
       
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/get-data.php';
		$http.post(absUrl,{total_experience:$scope.total_experience,position:$scope.multipleArrList,company:$scope.multipleCompanyArrList,page:$scope.currentPage,location:$scope.location}).success(function(response)
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
