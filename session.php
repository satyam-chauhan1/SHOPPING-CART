<!-- start session  -->
<?php
session_start();
$_SESSION['username'] = 'Satyam';
$_SESSION['user_id'] = '12345';
echo "we have saved session";
?>
