<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="searchController">
 

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
      <li class="network_btn"><a href="#"  data-toggle="modal" data-target="#myModal">Compose and Send Email</a>
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
					<a class="btn-res" data-ng-click="removeFromlist($index);">Remove from list</a>

				</div>
				<div class="detail_action" style="">
               		<ul class="add_list_profile">
                        <li ng-repeat="item in data.connectedUsers">
                        	<img src="newui/images/1X1.png" style="background:url('newui/images/user.png')"> 
                            <h2 ng-bind-html="item.first_name+' '+item.last_name"></h2>
                            <p>{{item.position}} at <?php echo isset($_SESSION['member']['company_name'])?$_SESSION['member']['company_name']:"";?></p> 
                            <div class="squaredTwo1">
                                <input type="checkbox" name="connected_{{$index}}" ng-model="data.connectedUsers[$index].IsChecked">
                                <label for="squaredTwo"></label>
                            </div>
                        </li>
                    </ul>
				</div>
			</div>
			</li>
			<li ng-show="resultList.status=='failure'">Great! Search for more Awesome Candidates. <a href="<?php echo ANGULAR_ROUTE; ?>/search">Click Here</a></li>

		</ul>
		
		<div class="modal fade moldelRnz" id="myModal" role="dialog">
		   <div class="modal-dialog modal-lg">
			  <!-- Modal content-->
			  <div class="modal-content">
				 <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><b>Compose and Send Email</b> </h4>
				 </div>
				 <div class="modal-body">
					<form>
					   <div class="form-group">
						  <label for="">Open Job Position Title</label>
						  <input type="text" name="job_title" ng-model="requestForm.job_title" class="form-control" placeholder="Job Title"> 
					   		<span ng-if="errorMsgTitle" style="color:red;"  ng-bind-html="errorMsgTitle"></span>
					   </div>
					   <div class="form-group">
						  <label for="">Open Job Position URL/Description</label>
						  <textarea type="text" name="job_position_url" ng-model="requestForm.job_position_url" class="form-control" placeholder="URL/Description"></textarea> 
					   </div>
					   <div class="form-group">
						  <label>Bonus for Refrel Amount</label>
						  <div style="display:inline-block; width:100%;">
							 <div class="col-20"><input type="text" name="currency" ng-model="requestForm.currency" class="form-control" placeholder="Currency">
							 </div>
							 <div class="col-50"><input type="text" name="referral_amount" ng-model="requestForm.referral_amount" class="form-control" placeholder="Amount">
							 </div>
							 <div class="col-30">Bonus for Refrel Amount?</div>
						  </div>
					   </div>
					   <div class="form-group">
						  <label>Subject for Employee</label>
						  <input type="text" name="subject_employee" ng-model="requestForm.subject_employee" class="form-control" placeholder="Subject"> 
					     <span ng-if="errorMsgSubject" style="color:red;"  ng-bind-html="errorMsgSubject"></span>
					   </div>
					   <div class="form-group">
						  <label>Message for Employee</label>
						  <textarea class="form-control" name="message_employee" ng-model="requestForm.message_employee" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
					     <span ng-if="errorMsgEmp" style="color:red;"  ng-bind-html="errorMsgEmp"></span>
					   </div>
					   <div class="panel-group">
						  <div class="panel panel-default">
							 <h4 class="panel-title">
								<a class="accordion-toggle" ng-click="showpanelView()"><span class="{{showPanel==true?'glyphicon glyphicon-minus':'glyphicon glyphicon-plus'}}"></span>Suggestion for Employees</a>
							 </h4>
							 <div id="collapseOne" ng-show="showPanel" class="panel-collapse">
								<div class="panel-body">
								   <div class="form-group">
									  <label>Subject for Employee</label>
									  <input type="text" name="subject_to_employee" ng-model="requestForm.subject_to_employee" class="form-control" placeholder="Subject"> 
								      <span ng-if="errorEmpSub" style="color:red;"  ng-bind-html="errorEmpSub"></span>
								   </div>
								   <div class="form-group margin-none">
									  <label>Message for Employee</label>
									  <textarea class="form-control margin-none" name="message_to_employee" ng-model="requestForm.message_to_employee" rows="3" placeholder="Message"></textarea>
								      <span ng-if="errorEmpMsg" style="color:red;"  ng-bind-html="errorEmpMsg"></span>
								   </div>
								</div>
							 </div>
						  </div>
					   </div>
					</form>
				 </div>
				 <div class="modal-footer">
					<button ng-if="!showLodermail" type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
					<center  ng-if="showLodermail"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
					<button  ng-if="!showLodermail" type="submit" ng-click="submitForm()" class="btn btn-lg btn-success">Submit</button>
				 </div>
			  </div>
		   </div>
		</div>
	
	</div>
	</div>
<script>
trackingApp.registerCtrl('searchController',function($scope,$http, $location, $timeout, $element)
{
	$scope.resultList = {};
	$scope.requestForm = {};
	$scope.requestForm.subject_employee = 'Refer your Connection [SHORTLISTED]';
	$scope.requestForm.message_employee = 'Hi [EMPLOYEE],\n\
[SHORTLISTED] seems to be a good fit for the open position that we have, [OPENJOBPOSITIONTITLE]. Since [SHORTLISTED] is connected with you on your social network, requesting you to write to him and have him get in touch with me or the HR team.\n\
Please Click here or the Button Below to write to him:\n\
<button>Refer [SHORTLISTED]</button>\n \
Regards,\n\
Team HR';
	$scope.requestForm.subject_to_employee = '[SHORTLISTED], we have an open job position';
	$scope.requestForm.message_to_employee = 'Hi [SHORTLISTED],\n\
We have an open position, [OPENJOBPOSITIONTITLE].\n\
Would you be interested?, for next steps reply to this email the company HR is CCed on this email, If you need any other information, do let me know.\n\
Regards,\n\
[EMPLOYEE]';
	$scope.showPanel = false;
	$scope.showLoder = false;
	$scope.showLodermail = false;
	$scope.submitForm = function()
	{
		if($scope.requestForm.job_title && $scope.requestForm.subject_employee && $scope.requestForm.message_employee)
		{
			$scope.errorEmpMsg,$scope.errorEmpSub,$scope.errorMsgTitle,$scope.errorMsgSubject,$scope.errorMsgTitle,$scope.errorMsg = '';
			$scope.showLodermail = true;
			var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/sendmail.php';
			$http.post(absUrl,{formdata:$scope.requestForm,connection:$scope.resultList}).success(function(response)
			{
				if(response)
				{
					$timeout(function()
					{
						$scope.showLodermail = false;
						$('#myModal').modal('hide');
						window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/search';
					},100)
				}
			})
		}
		else
		{
			if(!$scope.requestForm.job_title)
			{
				$scope.errorMsgTitle = 'Please enter job title';
			}
			else if(!$scope.requestForm.subject_employee)
			{
				$scope.errorMsgSubject = 'Please enter subject for employee';
			}
			else if(!$scope.requestForm.message_employee)
			{
				$scope.errorMsgEmp = 'Please enter message for employee';
			}
			else if(!$scope.requestForm.subject_to_employee)
			{
				$scope.errorEmpSub = 'Please enter suggestion for employee';
			}
			else if(!$scope.requestForm.message_to_employee)
			{
				$scope.errorEmpMsg = 'Please enter suggestion for employee';
			}
			return false;
		}
		
		
	}
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
	$scope.checkSession = '<?php echo !empty($_SESSION['member']['cId'])?$_SESSION['member']['cId']:"";?>';
	if(!$scope.checkSession)
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/search';
	}
    $scope.removeFromlist = function(index)
	{
			$scope.resultList.data.splice(index,1);
			var tempArray=[];
			for(var i=0;i<$scope.resultList.data.length;i++)
			{
				var id = $scope.resultList.data[i]['UID'];
				tempArray.push(id);	
			}
			var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/add-to-list.php';
			$http.post(absUrl,{UIDList:tempArray}).success(function(response)
			{
				if($scope.resultList.data.length<=0)
				{
					$location.path('/search');
				}
			})
	}
	
	
	$scope.currentPage = 0;
	$scope.totalPageLength = 0;
    $scope.pageSize = 10;
    $scope.q = '';
    $scope.showLoder = false;
	$scope.searchData = function()
	{
		$scope.showLoder = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/list-added-data.php';
		$http.post(absUrl,{position:$scope.position,company:$scope.company,page:$scope.currentPage}).success(function(response)
		{
			$scope.resultList = response;
			$scope.showLoder = false;
			$scope.totalPageLength = response.totalCount;
			if($scope.resultList.status=='failure')
			{
				$location.path('/search');
			}
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
	};
	
	$scope.selectedData = null;
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
