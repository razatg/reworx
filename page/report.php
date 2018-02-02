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
    </ul>
  </div>
  </div>
</nav>
</header>

<div class="bodypan" ng-style="{'min-height':divHeight()}">
	<div class="container">
	<center ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
    <div ng-show="!showLoder" class="row">
		<section>
        <div class="wizard">
               <!-- <ul class="nav nav-wizard">
                    <li class="active">
                        <a href="#step1" data-toggle="tab"><span>{{userReportCount.employee}}</span>Employees</a>
                    </li> 
                    <li class="disabled">
                        <a href="#step3" data-toggle="tab"><span>{{userReportCount.totalProfile}}</span>Potential Base</a>
                    </li>
                     <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>{{userReportCount.selectedCandidate}}</span>Candidates Selected</a>
                    </li>
                    <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>{{userReportCount.referRequest}}</span>Referrals Requested</a>
                    </li>
                    <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>{{userReportCount.emailSent}}</span>Emails Sent</a>
                    </li>
                    <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>{{userReportCount.emailClicked}}</span>Email Link Clicked</a>
                    </li>
                    <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>{{userReportCount.hired}}</span>Hired</a>
                    </li> 
                </ul>
               --> 
                <div class="row mb-15">
                	<div class="col-md-3 col-sm-4 col-xs-12"></div>
                	<div ng-init="reportType=15" class="col-md-3 col-sm-4 col-xs-12 pull-right">
						<select ng-model="reportType" ng-change="getReport(reportType)" class="form-control mb-0">
							<option value="15">Last 15 days</option>
							<option value="1">Yesterday</option>
                       </select>
                    </div>
                    </div>
            <form>
            <div ng-show="reportList.length>0" class="tab-content">
                    <div class="tab-pane active" id="step1"> 
                         <div class="table-responsive">
							 
                          <table class="table table-report">
                            <thead>
                              <tr>
                                <th width="15%">Job Position</th>
                                <th width="16%">Candidates</th>
                                <th width="21%">Employees</th>
                                <th width="13%">Status</th>
                                <th width="12%">Action</th>
                                <th width="10%">Hired</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr ng-repeat="item in reportList">
                                <td><span class="report_title">{{item.job_position}}</span></td>
                                <td colspan="5">
                                	<table class="table">
                                    	<tr ng-repeat="data in item.userList">
                                        	<td width="22%"><img src="{{data.pic}}" width="30px" class="report_img_icon"/> {{data.name}}</td>
                                            <td  width="30%"><span ng-repeat="list in data.connectedUsers">{{list.first_name}} {{list.last_name}} </span></td>
                                            <td  width="20%">{{data.status}}</td>
                                            <td  width="18%">
												<a ng-show="data.action=='Search Again'"  href="<?php echo ANGULAR_ROUTE;?>/search">{{data.action}}</a>
												<a ng-show="data.action=='Send Reminder' || data.action=='Reminder Sent'"  ng-click="sendReminder(data.UID,data.addedOn,$parent.$index,$index)">{{data.action}}</a>
												<a ng-show="data.action=='-'" href="javascript:void(0);">{{data.action}}</a>
											</td>
                                            <td ng-click="openHireModal(data.UID,data.addedOn)"  width="17%"><input type="checkbox"></td>
                                        </tr>
                                    </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        
                        </div> 
                    </div> 
                </div>
              <center ng-show="reportList.length==0">You will see a report here. Once your shortlist and ask for referrals from your team.</center>   
            </form>
            <br><br>
        </div>
    </section>
    <div data-backdrop="static" class="modal fade moldelRnz" id="myModalRefuse" role="dialog">
		   <div class="modal-dialog modal-md">
			  <!-- Modal content-->
			  <div class="modal-content">
				 <div class="modal-header">
					<button type="button"  class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><b>Confirm</b> </h4>
				 </div>
				 <div class="modal-body">
					<span>Please confirm the Hire</span>
				 </div>
				 <div class="modal-footer">
					<button type="button" ng-click="markedHired()" class="btn btn-lg btn-default">OK</button>
					<button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
				 </div>
			  </div>
		   </div>
		</div>
   </div>
</div>
</div>
<?php include_once('Analytics.php');?>
<script>
trackingApp.registerCtrl('reportController',function($scope,$http, $location, $timeout, $element)
{
    $scope.checkSession = '<?php echo !empty($_SESSION['member']['userType'])?$_SESSION['member']['userType']:"";?>';
	if(!$scope.checkSession)
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/';
	}
	$scope.sendReminder = function(UID,uniqueId,parentIndex,index)
	{
		$scope['reportList'][parentIndex]['userList'][index]['action'] = 'Reminder Sent';	
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/send-reminder-mail-from-recruiter.php';
		$http.post(absUrl,{UID:UID,uniqueId:uniqueId}).success(function(response)
		{
			
		})
	}
    $scope.showLoder = false;
	$scope.getReport = function(reportType)
	{
		$scope.showLoder = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/reports.php';
		$http.post(absUrl,{reportType:reportType}).success(function(response)
		{
			$scope.reportList = response.data;
			$scope.userReportCount = response.userReportCount;
			$scope.showLoder = false;
			
		})
	}
	$scope.getReport(15);
   $scope.openHireModal = function(UID,addedOn)
   {
	   $scope.seletedUID = UID;
	   $scope.addedOn = addedOn;
	   $('#myModalRefuse').modal('show');
   }
 
   $scope.markedHired = function()
   {
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/remove-from-refer-list.php';
		$http.post(absUrl,{UID:$scope.seletedUID,addedOn:$scope.addedOn,type:'hired'}).success(function(response)
		{
			$('#myModalRefuse').modal('hide');
			$scope.getReport(15);
		})
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
