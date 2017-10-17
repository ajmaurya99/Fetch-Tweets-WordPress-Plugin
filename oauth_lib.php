<?php
// General purpose OAuth library for use by 140dev Twitter API tools
// Copyright (c) 2014 Adam Green. All rights reserved. 
// Contact info: http://140dev.com, @140dev, adam@140dev.com
// Released as open source under MIT license

// Create an OAuth connection
function get_connection() {

	// Get OAuth tokens for engagement account
	require('config.php');
	require('tmhOAuth.php');
	
	// Create the connection
	// The OAuth tokens are kept in config.php
	$connection = new tmhOAuth(array(
		  'consumer_key'    => $consumer_key,
		  'consumer_secret' => $consumer_secret,
		  'user_token'      => $user_token,
		  'user_secret'     => $user_secret
	));
			
	return $connection;
}

?>