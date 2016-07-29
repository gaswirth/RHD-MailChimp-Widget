<?php
	require_once 'MCAPI.class.php';

	$apikey = '584017f3052d69ce44ebc1253fd176d5-us3';
	$listId = 'f33d7768e5';
	$email = $_POST['email'];
	$fname = ! empty( $_POST['fname'] ) ? $_POST['fname'] : null;
	$lname = ! empty( $_POST['lname'] ) ? $_POST['lname'] : null;

	$merge_vars = array();

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