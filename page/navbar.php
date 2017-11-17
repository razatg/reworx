<a href="#" class="logo"><img src="newui/images/logo.png" alt=""></a>
<?php 
if(!empty($_SESSION['member']['email'])){?>
<div class="logout_container">
	<img src="newui/images/user.png" alt="" style="background:url(newui/images/logout.png) no-repeat 0 0/cover" width="40px"><a ng-click="logout()">Logout</a>
</div>
<?php } 
else
{?>
<div class="logout_container">
	<span><a ng-click="togglePopup('show')">Login</a></span>
</div>
<?php }
?>
