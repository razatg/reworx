<?php 
ini_set('display_errors', 0);
include_once('../config-ini.php');?>
<?php
 if($_SESSION['member']['userType']==='employee' &&  $_SESSION['member']['connectionUploaded'])
{?>
    <li><a href="employee-dashboard">Your Connections</a></li>
    <li><a href="refer/<?php echo $_SESSION['member']['UID'];?>">Referrals</a></li>
    <li><a href="user-report">Report & Actions</a></li>
    <li><a href="user-list">List</a></li>
  
<?php }

 if($_SESSION['member']['userType']==='recruiter')
{?>
   <li><a href="search">Search & Shorlist</a></li>
   <li><a href="report">Report & Actions</a></li>
<?php }
?>
	
