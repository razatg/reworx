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
	<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
    <div ng-show="!showLoder" class="row">
		<section>
        <div class="wizard">
              <!--  <ul class="nav nav-wizard">
                    <li class="active">
                        <a href="#step1" data-toggle="tab"><span>10</span>Employees</a>
                    </li> 
                    <li class="disabled">
                        <a href="#step3" data-toggle="tab"><span>15,000</span>Potential Base</a>
                    </li>
                     <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>50</span>Candidates Selected</a>
                    </li>
                    <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>125</span>Referrals Requested</a>
                    </li>
                    <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>100</span>Emails Sent</a>
                    </li>
                    <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>10</span>Email Link Clicked</a>
                    </li>
                    <li class="disabled">
                        <a href="#step4" data-toggle="tab"><span>2</span>Hired</a>
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
                                <th width="25%">Job Position</th>
                                <th width="25%">Connections</th>
                                <th width="25%">Status</th>
                                <th width="25%">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr ng-repeat="item in reportList">
                                <td><span class="report_title">{{item.job_position}}</span></td>
                                <td colspan="5">
                                	<table class="table">
                                    	<tr ng-repeat="data in item.userList">
                                        	<td width="30%"><img src="{{data.pic}}" width="30px" class="report_img_icon"/> {{data.name}}</td>
                                            <td  width="25%">{{data.status}}</td>
                                            <td  width="30%">
												<a ng-if="data.action=='Send Referral'" ng-click="gotoReferPage(data.UID,data.addedOn)" href="javascript:void(0);">{{data.action}}</a>
												<a ng-if="data.action=='Send Reminder' || data.action=='Reminder Sent'"  ng-click="sendReminder(data.UID,data.addedOn,$parent.$index,$index)">{{data.action}}</a>
												<a ng-if="data.action=='Call May Be'" href="{{data.profile_url}}">{{data.action}}</a>
												<a ng-if="data.action=='-'" href="javascript:void(0);">{{data.action}}</a>
											</td>
                                        </tr>
                                    </table>
                                </td>
                                 
                              </tr>
                            </tbody>
                          </table>
                        </div> 
                    </div> 
                </div>
                <center ng-show="reportList.length==0">You will see a report here. Once your company HR/Recruitment finds somebody suitable that you know</center>   
            </form>
            <br><br>
        </div>
    </section>
   </div>
</div>
</div>
<script>
trackingApp.registerCtrl('reportController',function($scope,$http, $location, $timeout, $element)
{
    $scope.checkSession = '<?php echo !empty($_SESSION['member']['userType'])?$_SESSION['member']['userType']:"";?>';
	if(!$scope.checkSession)
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/';
	}
	
	$scope.gotoReferPage = function(UID,uniqueId,index)
	{
		setCookie('UID',UID,1);
		setCookie('uniqueId',uniqueId,1);
		window.location.href =  '<?php echo ANGULAR_ROUTE;?>/refer/<?php echo $_SESSION['member']['UID'];?>';
	}
	
	$scope.sendReminder = function(UID,uniqueId,parentIndex,index)
	{
		$scope['reportList'][parentIndex]['userList'][index]['action'] = 'Reminder Sent';	
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/send-reminder-mail-to-employee.php';
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
	
	
})
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
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
