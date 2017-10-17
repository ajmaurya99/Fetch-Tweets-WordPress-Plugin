<?php
/*
  Plugin Name: FC 3 Column Tweet Display.
  Plugin URI: http://flyingcursor.com/
  Description: Displays Tweets in 1-3 Column Format.
  Version: 1.0.0
  Author: Flying Cursor
  Author URI: http://flyingcursor.com/
  License: GPL2
 */


$fc_tweets = array();
add_action('admin_menu', 'fc_tweet');

function fc_tweet() {

    /* create new top-level menu */
    add_menu_page('FC 3 Column Tweet Display', 'FC 3 Column Tweet Display', 'administrator', 'fctweets', 'fc_tweet_setting', get_stylesheet_directory_uri('stylesheet_directory') . "/images/fc-logo.png");
    add_submenu_page('fctweets', 'FC Tweets Settings', 'Settings', 'administrator', 'fc_settings', 'fc_tweet_setting');

    /* call register settings function */
    add_action('admin_init', 'register_fc_tweet_setting');
}

/* Enqueue Styles */

function fc_tweet_style() {

    wp_register_style('fc_tweet_css', plugins_url('/css/fctweet-style.css', __FILE__), false, '1.0.0', 'all');
    wp_enqueue_style('fc_tweet_css');
    if (is_admin()) {
        /* Add the color picker css file */
        wp_enqueue_style('wp-color-picker');

        /*  Include our custom jQuery file with WordPress Color Picker dependency */
        wp_enqueue_script('jquery-cycle2-js', plugins_url('/js/jquery.cycle2.js', __FILE__), array('jquery'), false, true);
        wp_enqueue_script('fc-tweet-js', plugins_url('/js/init.js', __FILE__), array('wp-color-picker'), false, true);
    }
}

add_action('admin_enqueue_scripts', 'fc_tweet_style');

function register_fc_tweet_setting() {
    /* register our settings */
    register_setting('fc_tweet_settings_group', 'fctweet_key');
}

/* Create Db for Plugin */

register_activation_hook(__FILE__, 'pu_create_plugin_tables');

function pu_create_plugin_tables() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'tablename';

    $sql = "CREATE TABLE $table_name (
      id int(11) NOT NULL AUTO_INCREMENT,
      user_tweet varchar(255) DEFAULT NULL,
      date_time datetime DEFAULT NULL,
       UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

/* /.Create Db for Plugin */

function fc_tweet_setting() {
    global $fc_tweets;
    ?>  
    <div class="wrap">
        <div class="fc-tweet-plugin">
            <a href="http://flyingcursor.com/" target="_blank"><?php echo '<img src="' . plugins_url('/fc-3column-tweet-display/images/fc-logo.png', dirname(__FILE__)) . '" > '; ?></a>
            <h2>FC 3Column Tweet Display</h2>
        </div>
        <div class="form">
            <form method="post" action="options.php" method="post">
                <?php settings_fields('fc_tweet_settings_group'); ?>
                <?php do_settings_sections('fc_tweet_settings_group'); ?>
                <?php $fctweet = get_option('fctweet_key', $fc_tweets); ?>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>
                                Key Label
                            </th>
                            <th>
                                Key Value
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Background Color
                            </td>
                            <td>
                                <input type="text" class="color-field" value="fe9810" name="fctweet_key[tweet_bgcolor]">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                No of Tweets Per Column
                            </td>
                            <td>
                                <input type="number" name='fctweet_key[no_tweets]' value='<?php echo $fctweet["no_tweets"]; ?>'  min="1" max="50">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                No Of Rows (Mimimum 1 & Maximum 3)
                            </td>
                            <td>
                                <input type="number" name='fctweet_key[tweet_rows]' value='<?php echo $fctweet["tweet_rows"]; ?>'  min="1" max="3">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                No Of Columns (Mimimum 1 & Maximum 3)
                            </td>
                            <td>
                                <input type="number" name='fctweet_key[tweet_cols]' value='<?php echo $fctweet["tweet_cols"]; ?>'  min="1" max="3">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Consumer Key
                            </td>
                            <td>
                                <input type='text' name='fctweet_key[consumer_key]' value='<?php echo $fctweet["consumer_key"]; ?>'/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Consumer Secret  Key
                            </td>
                            <td>
                                <input type='text' name='fctweet_key[consumer_secret_key]' value='<?php echo $fctweet["consumer_secret_key"]; ?>'/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                User Token Consumer Key
                            </td>

                            <td>
                                <input type='text' name='fctweet_key[user_token_key]' value='<?php echo $fctweet["user_token_key"]; ?>'/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                User Secret Key
                            </td>
                            <td>
                                <input type='text' name='fctweet_key[user_secret_key]' value='<?php echo $fctweet["user_secret_key"]; ?>'/>
                            </td>
                        </tr>
                    </tbody>

                </table>
                <?php submit_button(); ?>
            </form>
        </div>
    </div>
    <?php
}

/* 140 Dev Plugin to get the Twitter feeds */

date_default_timezone_set('Asia/Kolkata');
require('oauth_lib.php');
add_action('wp_footer', 'get_tweets');

function get_tweets() {
    $fc_tweets_user_values = get_option('fctweet_key');
    $tweets_slide = $fc_tweets_user_values['no_tweets'];
    $fctweet_row = $fc_tweets_user_values['tweet_rows'];
    $fctweet_col = $fc_tweets_user_values['tweet_cols'];
    $total_tweet_fetch = $tweets_slide * ($fctweet_row * $fctweet_col);
    echo $total_tweet_fetch;
    global $wpdb;
    $connection = get_connection();
    /* Get Tweets  for this user */
    $connection->request('GET', $connection->url('1.1/statuses/user_timeline'), array('count' => $total_tweet_fetch, 'user_id' => '238045378', 'screen_name' => 'aj_technomad'));
    $tweets = $connection->response['response'];
    $json_tweet = json_decode($tweets, true);
    $get_latest_tweet = $wpdb->get_results("SELECT date_time FROM `wp_tablename` ORDER BY `id` DESC LIMIT 1");
    @$last_date = $get_latest_tweet[0]->date_time;
    $total_tweets_fetch = count($json_tweet) - 1;
    for ($i = $total_tweets_fetch; $i >= 0; $i--) {
        @$text = $json_tweet[$i]['text'];
        @$date = $json_tweet[$i]['created_at'];
        $modified_date = date("Y-m-d H:i:s", strtotime($date));
        if ($modified_date > $last_date) {
            $wpdb->insert("wp_tablename", array('user_tweet' => $text, 'date_time' => $modified_date), array('%s', '%s'));
        }
    }
}

add_action('init', 'display_tweets');

/* /.140 Dev Plugin to get the Twitter feeds */

/* Convert TimeStamp in Hours ago */

function twitter_time($a) {
    /* get current timestamp */
    $b = strtotime("now");
    /* get timestamp when tweet created */
    $c = strtotime($a);
    /* get difference */
    $d = $b - $c;
    /* calculate different time values */
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;

    if (is_numeric($d) && $d > 0) {
        /* if less then 3 seconds */
        if ($d < 3)
            return "right now";
        /* if less then minute */
        if ($d < $minute)
            return floor($d) . " seconds ago";
        /* if less then 2 minutes */
        if ($d < $minute * 2)
            return "about 1 minute ago";
        /* if less then hour */
        if ($d < $hour)
            return floor($d / $minute) . " minutes ago";
        /* if less then 2 hours */
        if ($d < $hour * 2)
            return "about 1 hour ago";
        /* if less then day */
        if ($d < $day)
            return floor(($d / $hour) - 11) . " hours ago";
        /* if more then day, but less then 2 days */
        if ($d > $day && $d < $day * 2)
            return "1 day ago";
        /* if less then year */
        if ($d < $day * 365)
            return $a;
        /* else return more than a year */
        return "over a year ago";
    }
}

/* /.Convert TimeStamp in Hours ago */

/**
 * Replace links in text with html links
 *
 * @param  string $text
 * @return string
 */
function auto_link_text($text) {
    $pattern = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
    $callback = create_function('$matches', '
       $url = array_shift($matches);
       $url_parts = parse_url($url);

       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
       $text = preg_replace("/^www./", "", $text);

       return sprintf(\'<a target="_blank" href="%s">%s</a>\', $url, $text);
   ');

    return preg_replace_callback($pattern, $callback, $text);
}

/* Replace Hashtag with Link */

function auto_link_hashtag($text) {

    $pattern = '/#([a-zA-Z_,"$#@.|])+/';
    $callback = create_function('$matches', '
       $url = array_shift($matches);
       $url_parts = parse_url($url);
       $url = preg_replace("/^#/", "", $url);
       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
        
       return sprintf(\'<a target="_blank" href="https://twitter.com/hashtag/%s/?src=hash">#%s</a>\', $url, $text);
   ');

    return preg_replace_callback($pattern, $callback, $text);
}

/* /.Replace Hashtag with Link */

/* Replace Username  with Link */

function auto_link_username($text) {
    $pattern = '/@([a-zA-Z_,"$#@.|])+/';
    $callback = create_function('$matches', '
       $url = array_shift($matches);
       $url_parts = parse_url($url);
       $url = preg_replace("/^@/", "", $url);
       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
        
       return sprintf(\'<a target="_blank" href="https://twitter.com/%s">@%s</a>\', $url, $text);
   ');

    return preg_replace_callback($pattern, $callback, $text);
}

/* /.Replace Username  with Link */

/* Display Tweet Function */

function display_tweets($c) {
    $fc_tweets_user_values = get_option('fctweet_key');
    $tweets_slide = $fc_tweets_user_values['no_tweets'];
    $total_tweet_fetch = $tweets_slide * ($fctweet_row * $fctweet_col);
    global $wpdb;

    if ($c == 0) {
        $offset = 0;
    } else {
        $offset = $c * $tweets_slide;
    }

    $tweet_content = $wpdb->get_results("SELECT DATE_FORMAT(date_time,  '%b %d' ) AS date_time, user_tweet FROM  `wp_tablename` ORDER BY `id` DESC LIMIT 10 OFFSET $offset");
    $return_tweets = [];
    if (!empty($tweet_content)) {
        for ($j = 0; $j < $tweets_slide; $j++) {
            $return_tweets[$j][0] = $tweet_content[$j]->date_time;
            $return_tweets[$j][1] = $tweet_content[$j]->user_tweet;
        }
        return $return_tweets;
    } else {
        return null;
    }
}

/* /.Display Tweet Function */

/* Display Coulmn and Row Logic  Function */

function display_tweets_row() {
    $fc_tweets_user_values = get_option('fctweet_key');
    $fctweet_row = $fc_tweets_user_values['tweet_rows'];
    $fctweet_col = $fc_tweets_user_values['tweet_cols'];
    if ($fctweet_col == 1) {
        $division = "col-md-12";
    }
    if ($fctweet_col == 2) {
        $division = "col-md-6";
    }
    if ($fctweet_col == 3) {
        $division = "col-md-4";
    }
    ?>
    <?php
    for ($c = 0; $c < $fctweet_col; $c++) {
        $display_tweets = display_tweets($c);
        if ($c == 0) {
            $slide_timeout = 3000;
        } else {
            $slide_timeout = $slide_timeout + 2000;
        }
        ?>
        <div class="cycle-slideshow <?php echo $division; ?>" data-cycle-slides="> div" data-cycle-speed="1000" data-cycle-pause-on-hover="true" data-cycle-fx="fade"  data-cycle-timeout="<?php echo $slide_timeout; ?>">
            <?php
            if (is_array($display_tweets)) {
                $totalCount = count($display_tweets);
//            foreach ($display_tweets as $display_tweet) {
                ?>
                    <?php
                    $k = 0;
                    for ($i = 0; $i < $totalCount / $fctweet_row; $i++) {
                        ?>
                    <div class="cycle-slide">
                                <?php
                                for ($j = 0; $j < $fctweet_row; $j++) {
                                    ?>
                            <div>
                                <p><?php
                                        $link_tweet = auto_link_text($display_tweets[$k][1]);
                                        $hash_tweet = auto_link_hashtag($link_tweet);
                                        $hash_username = auto_link_username($hash_tweet);
                                        echo $hash_username;
                                        ?>
                                    <br/><span><?php
                            if (!empty($hash_username)) {
                                echo twitter_time($display_tweets[$k][0]);
                            }
                            ?></span>
                                </p>

                            </div>
                        <?php
                        $k++;
                    }
                    ?>
                    </div>
                <?php
            }
            ?>
            <?php
//            }
        }
        ?>
        </div>                                                 
        <?php
    }
}

/* /.Display Coulmn and Row Logic  Function */

/* Cron Job */
if (!wp_next_scheduled('get_tweets_hook')) {
    wp_schedule_event(time(), 'hourly', 'get_tweets_hook');
}
add_action('get_tweets_hook', 'get_tweets');
?>

