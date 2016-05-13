<?php
	require_once 'MCAPI.class.php';

	$apikey = 'c1290d5d8ecabd97a628443935bff7ba-us11';
	$listId = '6f2202f3cc';
	$email = $_POST['email'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];

	if ( $fname ) $merge_vars['FNAME'] = $fname;
	if ( $lname ) $merge_vars['LNAME'] = $lname;

	$api = new MCAPI($apikey);

	// By default this sends a confirmation email - you will not see new members
	// until the link contained in it is clicked!

	$retval = $api->listSubscribe( $listId, $email, $merge_vars );

	if ($api->errorCode){
		header("HTTP/1.0 500 Internal Server Error");
	}
?>