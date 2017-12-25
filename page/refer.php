<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="referController">
 

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
	 <center ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
	<!--Section: Testimonials v.2-->
	<section ng-show="!showLoder" class="text-center container">
		<!--Carousel Wrapper-->
	   <div ng-show="resultStatus=='success'" id="carousel-generic" class="testimonial-carousel slide" data-ride="carousel">
			<!--Slides-->
			<div class="carousel-inner" role="listbox">
				<!--First slide-->
				<div  ng-repeat="data in referListArr" class="item {{$index==currentSelectedProfile?'active':''}}">
					<div ng-if="!showLoder" class="container  text-left">
					 <h1 class="pageheding text-left">Please review shortlisted for the positions below:</h1>
					 <h6  ng-if="data.recruiterMsg.job_title"> <strong>Job Title :</strong>  {{data.recruiterMsg.job_title}} </h6>
					 <p  ng-if="data.recruiterMsg.job_position_url" class="par"> <strong>Job Description :</strong>  {{data.recruiterMsg.job_position_url}}</p>
					 <p ng-if="data.recruiterMsg.referral_amount" class="par"><strong>Bonus :  {{data.recruiterMsg.currency}} {{data.recruiterMsg.referral_amount}}</strong></p>
				</div>
					
					<div class="testimonial">
						<!--Avatar-->
						<div class="avatar">
							<img ng-src="{{data.profile[0].pic_phy}}" class="rounded-circle img-fluid" alt="{{data.profile[0].name}}"/>
						</div>
						<h4 style="color:#7e7e7e">{{data.profile[0].name}}</h4>
						<!--Content-->
						<p><i class="fa fa-quote-left"></i> 
						<span ng-if="data.profile[0].experience[0].designation && data.profile[0].experience[0].company" ng-bind-html="data.profile[0].experience[0].designation+' at '+ data.profile[0].experience[0].company"></span> 
						</p>   
						<h6><i></i>{{data.profile[0].area}}</h6>
						<div class="social_icon">
							<a href="{{data.profile[0].profile_url}}"><img src="newui/images/linkden.png"></a>
							<a href="mailto:{{data.profile[0].email}}"><img src="newui/images/mail.png"></a>
						</div>
						<div class="btn-container">
							<a href="#" ng-click="getEmpDetail(data)"  class="btn btn-success">REFER</a>
							<a href="#" ng-click="notInterested(data,'notFit')" class="btn btn-success">NOT A FIT</a>
							<a href="#" ng-click="notInterested(data,'donotknow')" class="btn btn-success">DON'T KNOW</a>
						</div>
						<small class="text-center">{{$index+1}} out of {{totalCount}} reviewed</small>
					</div>
				</div>
			</div>
			<!--Slides-->
			<!--Controls-->
			<a class="carousel-item-prev left carousel-control"  data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> 
			<a class="carousel-item-prev right carousel-control" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a> 
			<!--Controls-->
		</div>
		<!--Carousel Wrapper-->
	 <div style="margin-top:20px;" ng-show="resultStatus=='failure'">You will see whom to refer here. Once your company HR/Recruitment finds somebody suitable that you know.</li>
	</section>
	<!--Section: Testimonials v.2-->
	</div>
	
	<div class="modal fade moldelRnz" id="myModal" role="dialog">
		   <div class="modal-dialog modal-lg">
			  <!-- Modal content-->
			  <div class="modal-content">
				 <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><b>Refer</b> </h4>
					<span ng-if="errorMsg" style="color:red;" ng-bind-html="errorMsg"></span>
				 </div>
				 <div class="modal-body">
					<form>
					   <div class="panel-group">
						  <div class="panel panel-default">
							 <h4 class="panel-title">
								<a class="accordion-toggle"><span></span>Refer your Connection</a>
							 </h4>
							 <div id="collapseOne" class="panel-collapse">
								<div class="panel-body">
								   <div class="form-group">
									  <label>Subject for Employee</label>
									  <input type="text" name="subject_to_employee" ng-model="requestForm.subject_to_employee" class="form-control" placeholder="Subject"> 
								   </div>
								   <div class="form-group margin-none">
									  <label>Message for Employee</label>
									  <textarea class="form-control margin-none" name="message_to_employee" ng-model="requestForm.message_to_employee" rows="3" placeholder="Message"></textarea>
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
	
	<div class="modal fade moldelRnz" id="myModalRefuse" role="dialog">
		   <div class="modal-dialog modal-md">
			  <!-- Modal content-->
			  <div class="modal-content">
				 <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><b>Please Confirm</b> </h4>
					<span ng-if="errorMsg" style="color:red;" ng-bind-html="errorMsg"></span>
				 </div>
				 <div class="modal-body">
					<span>We will store your choice, no communication will be done with this connection on your behalf</span>
				 </div>
				 <div class="modal-footer">
					<button ng-if="!showLoderRefuse" type="button" class="btn btn-lg btn-default" data-dismiss="modal">Close</button>
					<center  ng-if="showLoderRefuse"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
					<button  ng-if="!showLoderRefuse" type="submit" ng-click="removeFromReferList(selectedUID,selectedType)" class="btn btn-lg btn-success">Ok</button>
				 </div>
			  </div>
		   </div>
		</div>

</div>
<script>
trackingApp.registerCtrl('referController',function($scope,$http, $location, $timeout, $element)
{
	$scope.checkSession = '<?php echo !empty($_SESSION['member']['userType'])?$_SESSION['member']['userType']:"";?>';
	$scope.employeeName = '<?php echo !empty($_SESSION['member']['first_name'])?$_SESSION['member']['first_name']:"";?>';
	if(!$scope.checkSession || $scope.checkSession!='employee')
	{
		window.location.href =  '<?php echo ANGULAR_ROUTE; ?>/search';
	}
	$scope.selectedUID = '';
	$scope.resultStatus = '';
	$scope.notInterested = function(tmp,type)
	{
		$('#myModalRefuse').modal('show');
		$scope.selectedUID  = tmp.profile[0].UID;
		$scope.selectedType  = type;
		$scope.selectedDate = tmp.time;
		
	}
	$scope.currentSelectedProfile = 0;
	$scope.selectedProfileIndex = function(arr)
 	{
 		var selectedProfileUID = '<?php echo !empty($_SESSION['member']['selectedProfile'])?$_SESSION['member']['selectedProfile']:"";?>';
      	var uniqueID = '<?php echo !empty($_SESSION['member']['uniqueID'])?$_SESSION['member']['uniqueID']:"";?>';
 	  	if(arr.length>0)
 		{
 			for(var i =0;i<arr.length;i++)
 			{
 				if(arr[i].time==uniqueID && arr[i].profile[0].UID==selectedProfileUID)
 				{
 					$scope.currentSelectedProfile = i;
 				}
 			}
 		}
 	}
	$scope.showLoderRefuse = false;
	$scope.removeFromReferList = function(UID,type)
	{
		$scope.showLoderRefuse = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/remove-from-refer-list.php';
		$http.post(absUrl,{UID:UID,addedOn:$scope.selectedDate,type:type}).success(function(response)
		{
			$('#myModalRefuse').modal('hide');
			$scope.showLoderRefuse = false;
			$scope.getlist();
		})
	}
	$scope.requestForm = {};
	$scope.showLoder = false;
	$scope.getlist = function()
	{
		$scope.showLoder = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/get-refer-list.php';
		$http.post(absUrl).success(function(response)
		{
			$scope.resultStatus = response.status;
			$scope.showLoder = false;
			$scope.referListArr = response.data;
			$scope.selectedProfileIndex(response.data);
			$scope.totalCount = response.totalCount;
			$scope.recruiterList = response.recruiterList;
		})
	}
	$scope.getlist();
	
	$scope.getEmpDetail = function(tmp)
	{
		$('#myModal').modal('show');
		$scope.requestForm.subject_to_employee =  tmp.recruiterMsg.subject_to_employee.replace('[SHORTLISTED]', tmp.profile[0].name);
		var empMsg =  tmp.recruiterMsg.message_to_employee.replace('[SHORTLISTED]', tmp.profile[0].name);
		empMsg =  empMsg.replace(' [OPENJOBPOSITIONTITLE]',tmp.recruiterMsg.job_title);
		$scope.requestForm.message_to_employee  =  empMsg.replace('[EMPLOYEE]',$scope.employeeName);
		$scope.requestForm.employeeDetail = tmp;
	}
	
	$scope.showLodermail = false;
	$scope.submitForm = function()
	{
		if($scope.requestForm.subject_to_employee && $scope.requestForm.message_to_employee)
		{
			$scope.errorMsg = '';
			$scope.showLodermail = true;
			var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/send-mail-to-employee.php';
			$http.post(absUrl,{formdata:$scope.requestForm}).success(function(response)
			{
				if(response)
				{
					$timeout(function()
					{
						$scope.getlist();
						$scope.showLodermail = false;
						$('#myModal').modal('hide');	
					},100)
				}
			})
		}
		else
		{
			if(!$scope.requestForm.subject_to_employee)
			{
				$scope.errorMsg = 'Please enter suggestion for employee';
			}
			else if(!$scope.requestForm.message_to_employee)
			{
				$scope.errorMsg = 'Please enter suggestion for employee';
			}
			return false;
		}
	}
})
</script>


