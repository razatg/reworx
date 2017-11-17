<?php 
include_once('../config-ini.php');
?>
<div data-ng-controller="referController">
<header>
	<div class="grid-center">
	<div id="navmobile">
		<nav>
			<ul>
				<li><a href="#">Analytics</a></li>
				<li><a href="#">Tracking</a></li>
				<li><a href="#">Connect Your Networks</a></li>
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
	 <center ng-if="showLoder"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
		<div ng-if="!showLoder" class="grid-center">
			 <h1 class="pageheding">Please review shortlisted for the positions below:</h1>
			 <h6 ng-bind-html="recruiterList.job_title"></h6>
			 <p class="par">{{recruiterList.message_employee}},</p>
			 <p class="par"><strong>Bonus ${{recruiterList.referral_amount}}</strong></p>
		</div>
	<!--Section: Testimonials v.2-->
	<section ng-show="!showLoder" class="text-center grid-center">
		<!--Carousel Wrapper-->
	   <div id="carousel-generic" class="testimonial-carousel slide" data-ride="carousel">
			<!--Slides-->
			<div class="carousel-inner" role="listbox">
				<!--First slide-->
				<div  ng-repeat="data in referListArr" class="item {{$index==0?'active':''}}">
					<div class="testimonial">
						<!--Avatar-->
						<div class="avatar">
							<img ng-src="{{data.pic_phy}}" class="rounded-circle img-fluid" alt="{{data.name}}"/>
						</div>
						<h4 style="color:#7e7e7e">{{data.name}}</h4>
						<!--Content-->
						<p><i class="fa fa-quote-left"></i> 
						<span ng-if="data.experience[0].designation && data.experience[0].company" ng-bind-html="data.experience[0].designation+' at '+ data.experience[0].company"></span> 
						</p>   
						<h6><i></i>{{data.area}}</h6>
						<div class="social_icon">
							<a href="{{data.profile_url}}"><img src="newui/images/linkden.png"></a>
							<a href="mailto:{{data.email}}"><img src="newui/images/mail.png"></a>
						</div>
						<div class="btn-container">
							<a href="#" ng-click="getEmpDetail(data)"  class="btn btn-success">REFER</a>
							<a href="#" class="btn btn-success">NOT A FIT</a>
							<a href="#" class="btn btn-success">DON'T KNOW</a>
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
	$scope.requestForm = {};
	$scope.showLoder = false;
	$scope.getlist = function()
	{
		$scope.showLoder = true;
		var absUrl = '<?php echo ANGULAR_ROUTE; ?>/api/get-refer-list.php';
		$http.post(absUrl).success(function(response)
		{
			$scope.showLoder = false;
			$scope.referListArr = response.data;
			$scope.totalCount = response.totalCount;
			$scope.recruiterList = response.recruiterList;
		})
	}
	$scope.getlist();
	
	$scope.getEmpDetail = function(tmp)
	{
		$('#myModal').modal('show');
		$scope.requestForm.subject_to_employee =  $scope.recruiterList.subject_to_employee.replace('[SHORTLISTED]', tmp.name);
		var empMsg =  $scope.recruiterList.message_to_employee.replace('[SHORTLISTED]', tmp.name);
		empMsg =  empMsg.replace(' [OPENJOBPOSITIONTITLE]',$scope.recruiterList.job_title);
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


