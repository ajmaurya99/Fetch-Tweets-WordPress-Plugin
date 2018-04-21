# FC 3 Column Tweet Display

* Contributors: [Ajay Maurya] (https://github.com/ajmaurya99/), [Flying Cursor] (https://github.com/Flying-Cursor-Interactive)

* License: [GPL v2 or later] ( https://www.gnu.org/licenses/gpl-2.0.html) Version: 1.0.0

Use this Plugin to fetch tweets from a particular handle, Also saves the tweets into the database.

## Description

This Plugin is used to display the tweets from any particular Twitter handle.
First the tweets are fetched and saved in tnto the database in the descencing order ie the latest tweet comes on the top.
After that you can select how many tweets you want to display on the front end.

There are options to select from 1 column display to 3 columns display same for the rows 1 row display to 3 rows display
The plugin uses jquery cycle2 plugin as a slider to display the tweets in the font end.

## Installation

* Currently this plugin is not available at the WordPress Plugins repo. You have to download the plugins zip file from the github repo [Github link for plugin] (https://github.com/ajmaurya99/Fetch-Tweets-WordPress-Plugin)

* Install the plugin from the ‘Plugins’ section in your dashboard (Go to Plugins > Add New > Upload the zip file of the plugin).

* Alternatively, you can download the plugin from the repository. Unzip it and upload it to the plugins folder of your WordPress installation (wp-content/plugins/ directory of your WordPress installation).

* Activate it through the ‘Plugins’ section.

## Built With

* [140dev] (http://140dev.com/) 140dev Twitter API
* [jQuery Cycle2] (http://jquery.malsup.com/cycle2/) jQuery Cycle 2 Plugin for looping of the tweets.
* [Bootstrap 3] (http://getbootstrap.com/docs/3.3/) Bootstrap 3 for making the grid layout

## File structure

* [test_oauth.php] (https://github.com/ajmaurya99/Fetch-Tweets-WordPress-Plugin/blob/master/test_oauth.php) This file is read by WordPress to generate the plugin information in the plugin admin area. This file also includes all of the dependencies used by the plugin, registers the activation and deactivation functions, and defines a function that starts the plugin.

* [config.php] (https://github.com/ajmaurya99/Fetch-Tweets-WordPress-Plugin/blob/master/config.php) Config file contains all the database configuration and the twitter key and tokens.

* [db_lib.php] (https://github.com/ajmaurya99/Fetch-Tweets-WordPress-Plugin/blob/master/db_lib.php) Performs all the database operations. 

## Getting Twitter Kers and Tokens

* Visit  Twitter Application Management (https://apps.twitter.com/)
* Click on Create New App
* Fill all the deails of the application
* After creating the application, Visit "Keys and Access Tokens tab".
* Copy Consumer Key (API Key), Consumer Secret (API Secret), Access Token,Access Token Secret.
* Paste all the keys into your Plugins settings page.

## Changelog

1.0.0
First Release

## Important
I would say that this plugin is still in a developement phase, i am working on improving the perfomance of it.
Not recomended installing on websites with huge traffic.



