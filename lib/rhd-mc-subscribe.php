<?php
	require_once 'MCAPI.class.php';

	$apikey = '281c12c1a2874ba0069a629211123782-us12';
	$listId = 'b1d0d80c77';
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
