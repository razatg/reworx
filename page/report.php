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

<div class="bodypan">
	<div class="container">
	<center  ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
    <div ng-if="!showLoder" class="row">
		<section>
        <div class="wizard">
                <ul class="nav nav-wizard">
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
                <div class="row mb-15">
                	<div class="col-md-3 col-sm-4 col-xs-12"><select class="form-control mb-0"><option>Show Open Positions Only</option></select></div>
                	<div class="col-md-3 col-sm-4 col-xs-12 pull-right"><select class="form-control mb-0"><option>Show Open Positions Only</option></select></div>
                </div>
            <form>
            <div class="tab-content">
                    <div class="tab-pane active" id="step1"> 
                         <div class="table-responsive">
                          <table class="table table-report">
                            <thead>
                              <tr>
                                <th width="15%">Job Position</th>
                                <th width="15%">Candidates</th>
                                <th width="25%">Connections</th>
                                <th width="17%">Status</th>
                                <th width="18%">Action</th>
                                <th width="10%">Hired</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr ng-repeat="item in reportList">
                                <td><span class="report_title">{{item.job_position}}</span></td>
                                <td colspan="5">
                                	<table class="table">
                                    	<tr ng-repeat="data in item.userList">
                                        	<td width="18%"><img src="{{data.pic}}" width="30px" class="report_img_icon"/> {{data.name}}</td>
                                            <td  width="30%"><span ng-repeat="list in data.connectedUsers">{{list.first_name}} {{list.first_name}} </span></td>
                                            <td  width="20%">{{data.status}}</td>
                                            <td  width="22%"><a href="#">Send Remider</a><br><a href="#">Mark Assist</a></td>
                                            <td  width="17%"><input type="checkbox"></td>
                                        </tr>
                                    </table>
                                </td>
                                 
                              </tr>
                            </tbody>
                          </table>
                        </div> 
                    </div> 
                </div>
                 
            </form>
            <button type="button" class="btn btn-primary">Continue</button>
            <br><br>
        </div>
    </section>
   </div>
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
			$scope.reportList = response.data;
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
