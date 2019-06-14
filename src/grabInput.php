<?php
// Check if the form is submitted
$myInput = $_POST['myInput'];
// display the results
echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<title>F5 DevOps Day!</title>';
echo '</head>';
echo '<body style="background-color: #42F4EB;">';
echo '<h1 style="font-family: Courier; color: #434347; font-size: 46px; text-align: center;">';
echo 'Your input was: ' . $myInput . '';
echo '</h1>';
echo '<h2 style="font-family: Courier; color: #434347; font-size: 26px; text-align: center;"><a href="javascript:history.back();">[Go Back]</a></h2>';
echo '</body>';
echo '</html>';

exit;
