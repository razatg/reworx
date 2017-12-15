 <div class="modal fade moldelRnz" data-backdrop="static" id="myModal" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" ng-click="redirectPage()" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Login </h4>
        </div>
			<div class="modal-body">
			<span ng-if="errorLoginMsg" style="color:red;" ng-bind-html="errorLoginMsg"></span>
			<div class="login_form">
				<div class="form-group">
						<label>Email</label>
						<input type="email" name="email" ng-model="userarr.email" placeholder="Enter Email" class="form-control">
				</div>
				<div class="form-group">
						<label>Password</label>
						 <input type="password" name="password" ng-model="userarr.password" placeholder="Password" class="form-control">	
						<!--<p class="text-right"><a href="#" class="fgpass">Forgot Password?</a></p>-->
				</div>
				<div class="form-group text-center">
					<center ng-if="showLoderlogin"><img width="80" src="newui/images/widget-loader-lg-en.gif" alt=""></center>
					<button ng-if="!showLoderlogin" class="button" data-ng-click="userLogin();">Login</button>
				</div>
				<!--<p class="fgpass text-center">Don't have an account? <a href="#">Get in Touch</a></p>-->
			</div>	
		</div>	
    </div>
  </div>

