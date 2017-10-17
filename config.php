<?php

/* Database values for db_lib.php */
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'fc_tweets';

/*  MySQL time zone setting to normalize dates  */
$time_zone = 'Asia/Kolkata';

$fc_tweets_user_values = get_option('fctweet_key');

/* OAuth tokens for oauth_lib.php */
$consumer_key = $fc_tweets_user_values['consumer_key'];
$consumer_secret = $fc_tweets_user_values['consumer_secret_key'];
$user_token = $fc_tweets_user_values['user_token_key'];
$user_secret = $fc_tweets_user_values['user_secret_key'];
?>
