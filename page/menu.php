<?php 
ini_set('display_errors', 0);
include_once('../config-ini.php');?>
<?php
 if($_SESSION['member']['userType']==='employee' &&  $_SESSION['member']['connectionUploaded'])
{?>
    <li><a href="employee-dashboard">Home</a></li>
   <li><a href="refer/<?php echo $_SESSION['member']['UID'];?>">Refer</a></li>
   <li><a href="user-report">Report</a></li>
  
<?php }

 if($_SESSION['member']['userType']==='recruiter')
{?>
   <li><a href="search">Search</a></li>
   <li><a href="report">Report</a></li>
<?php }
?>
	
