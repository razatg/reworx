<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="searchController">
 
<header class="sr_rnz_container">
	<div class="container">
	 	<?php include('navbar.php');?>
		<div class="search_container">
			<a class="search_btn" ng-click="showSearchBox('search')"></a>
		 
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
      <?php include('navbar.php');?>
      <li ng-if="addtolistdata>0" class="network_btn"><a href="<?php echo ANGULAR_ROUTE; ?>/request">{{addtolistdata}} &nbsp; Shortlisted </a></li>
      
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
					<input id="positionId" style="height:45px!important" ng-model="position" placeholder="Enter Position (e.g Java Developer) or Skill (eg Java) sepereated by comma" class="form-control">
				</div>
				<div class="fld_col">
				<input id="companyId" style="height:45px!important" ng-model="company" placeholder="Enter Company name sepereated by comma (,)" class="form-control">
				</div>
			</div>
			<a class="search_btn" data-ng-click="currentPage=0;searchData()"></a>
			<a data-ng-click="showFilter()" class="filter_btn"></a>
		</div>
	</div>			
	
	<div class="search_container second_container" ng-show="showAdvancedFilter" style="padding:0; padding-bottom:15px;">
		<div class="container">
			<div class="field_row">
				<div class="fld_col">
					<input style="height:45px!important" id="locationId" ng-model="location" placeholder="Search Geo Location" class="form-control">
				</div>
				<div class="fld_col rnz_new_fld">
				<span>Experience</span>
				<select ng-init="total_experience=yearOfExp[0]" ng-model="total_experience" class="form-control" ng-options="item for item in yearOfExp"></select>
			</div>
			</div>
			<a class="btn rnz_new_fld_btn" data-ng-click="currentPage=0;searchData()">Search</a>
		</div>
	  </div>			
	
		<div class="container">
			<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
			 <ul ng-show="!showLoder" class="list-item search-list">
				<li ng-repeat="data in resultList.data">
					<img src="newui/images/1X1.png" style="background:url({{data.pic_phy}})" class="profile"/>
					   <div class="item-row">
						   <div class="detail_info">
								<h1 style="text-align:left;" ng-bind-html="data.name"></h1>
								<h3 ng-if="data.experience[0].designation && data.experience[0].company" ng-bind-html="data.experience[0].designation+' at '+ data.experience[0].company | to_trusted"></h3> 
								<h3 ng-if="!data.experience[0].designation && !data.experience[0].company" ng-bind-html="data.company | to_trusted">
								</h3>
							  
								<ul  ng-if="data.isSortlisted>0 || data.isMarkGood>0" class="short_list">
									<li ng-if="data.isSortlisted>0"><i class="fa fa-info"></i> Shortlisted Before</li>
									<li  ng-if="data.isMarkGood>0"><i class="fa fa-thumbs-up"></i>Top Talent</li>
						       </ul>
								<p  class="location" ng-bind-html="data.area | to_trusted"></p>
								<div ng-repeat="item in data.connectedUsers" class="relative-pos">
										<div ng-show="$parent.$index==parentIndex && childIndex==$index" class="custom-tooltip">
										<span class="name" ng-bind-html="item.first_name+' '+item.last_name"></span>
										<span class="occupation"><?php echo isset($_SESSION['member']['company_name'])?$_SESSION['member']['company_name']:"";?>
										</span>
										<span class="department" ng-if="item.position" ng-bind-html="item.position"></span>
									</div>
								 <img ng-mouseleave="showToolTips('-1')" ng-mouseover="showToolTips($index,$parent.$index)" src="newui/images/1X1.png" style="background:url('newui/images/user.png')" class="profile">
								</div>
								<ul class="match-keyword">
									<li ng-show="isSearch==true && data.title"><strong>Title: </strong>
										<span ng-bind-html="data.title | to_trusted"></span>
									</li>
									<li ng-show="isSearch==true && data.summary">
										<strong>Summary: </strong><span ng-bind-html="data.summary | to_trusted"></span></li>
									<li ng-show="data.featured_skiils && isSearch==true">
										<strong>Skills: </strong>
										<span ng-bind-html="data.featured_skiils | to_trusted"></span>
									</li>
									<li ng-show="data.experience && isSearch==true">
										<strong>Experience: </strong>
										<span ng-bind-html="data.experience | to_trusted"></span>
									</li>
								</ul>
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
<?php include_once('Analytics.php');?> 
</div>
<style type="text/css">
			.angular-highlight {
				background-color:	yellow;
				font-weight:		bold;
			}
		</style>
<script>
trackingApp.registerCtrl('searchController',function($scope,$http, $location, $timeout, $element,$sce)
{
	
	$scope.showToolTips = function(index,parent)
	{
		$scope.childIndex = index;
		$scope.parentIndex = parent;
	}
	$scope.isSearch = false;
	$scope.mainSearch = '';
	$scope.showSearchBox = function(type)
	{
		if(type=='search')
		{
			$scope.mainSearch = ($scope.mainSearch=='show')?'hide':'show';
		}
		else
		{
			$scope.filterSearch = ($scope.filterSearch=='show')?'hide':'show';
		}
		
	}
	
	$scope.resultList = {};
	$scope.yearOfExp = ['Select Experience'];
	for(var i = 1;i<=50;i++)
	{
		$scope.yearOfExp.push(i);
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
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/get-data.php';
		$http.post(absUrl,{total_experience:$scope.total_experience,position:$scope.position,company:$scope.company,page:$scope.currentPage,location:$scope.location}).success(function(response)
		{
			$scope.resultList = response;
			$scope.resultStatus = response.status;
			$("html, body").animate({ scrollTop: 60 }, 600);
			$scope.showLoder = false;
			$scope.isSearch = response.isSearch;
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

	$scope.selectedData = null;
	$scope.resultStatus = '';
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
	$scope.autoCompleteCustom = function(id,type)
	{
		$("#"+id)
		 // don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).autocomplete( "instance" ).menu.active ) {
				event.preventDefault();
				}
		})
		.autocomplete({
				minLength: 0,
				source: function( request, response ) 
				{
					  if(type=='location')
					  {
						  var keyterm = request.term; 
					  }
					  else
					  {
						   var keyterm = extractLast( request.term );
					  }
					  if(keyterm.length>3)
					  {	
						  var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/autosuggestion.php';
						  $http.post(absUrl,{keywords:keyterm,type:type}).success(function(projectResponse)
							{
							response($.ui.autocomplete.filter(projectResponse.data,keyterm));
						 })
					}
				},
				//    source:projects,    
				focus: function() 
				{
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) 
				{
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					if(type=='location')
					  {
						  this.value = terms.join("");
						  $scope[type] = this.value;
					  }
					  else
					  {
						   this.value = terms.join( ", " );
						   $scope[type] = this.value;
					  }	
					
					return false;
			}
		});
	}
	
})
function split( val ) 
{
	return val.split( /,\s*/ );
}
function extractLast( term ) 
{
	return split( term ).pop();
}
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
