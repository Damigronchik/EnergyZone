<?php
$now = date('Y-m-d');
            
$delete_query = "DELETE FROM user_subscriptions WHERE end_date < '$now'";
mysqli_query($link, $delete_query);    
?>