<?php
	require_once 'MCAPI.class.php';
	
	$apikey = '###';
	$listId = '###';
	$email = $_POST['email'];
	
	$merge_vars = array('FNAME'=>$fname,'LNAME'=>$lname);

	$api = new MCAPI($apikey);
	
	// By default this sends a confirmation email - you will not see new members
	// until the link contained in it is clicked!
	
	$retval = $api->listSubscribe( $listId, $email, $merge_vars );
	
	if ($api->errorCode){
		header("HTTP/1.0 500 Internal Server Error");
	}
?>