=== WP HTML Rotator Plugin ===
Contributors: Ariel Coppes
Tags: html, rotator, shortcode
Requires at least: 3.1
Tested up to: 3.1.3
Stable tag: 0.1.3

== Description ==

Schedule HTML sections to be shown or not based on a date range using a simple shortcode.

Right now the plug-in is enabled by using the rotator shortcode. For example, To specify we want to show a specific piece of HTML from 9am to 5pm you can use the following code:

[rotator start="9am" end="5pm"]
<div> 
   Some custom HTML.
</div>
[/rotator]

Where start and end parameters are using the date format specified in the <a href="http://www.php.net/manual/en/datetime.formats.php">php manual</a>, so you can specify more specific dates like showing the HTML section all Tuesday between 10am and 11am, etc. Read the Other Notes section for more information.

Note: the hour range is server based time and the default timezone is UTC, in other words, it depends on the configured time of the server, you can set that in <a href="http://en.support.wordpress.com/settings/general-settings/">general settings</a> at the admin console.

There is a page available at "Settings" -> "Schedule generator" in the wp admin console with a basic form which lets you generate the short code configuring different values like start and end times, among others.

== Usage ==

Right now the plug-in is enabled by using the rotator shortcode. For example, To specify we want to show a specific piece of HTML from 9am to 5pm you can use the following code:

[rotator start="9am" end="5pm"]
<div> 
   Some custom HTML.
</div>
[/rotator]

That will show the contents only if the user access the blog post or page in the specified hour range.

Currently, it supports the following parameters:

- start: a date to specify the start of the hour range, required.
- end: a date to specify the end of the hour range, required.
- visible: false to hide the html section, by default true.
- inverse: to specify if we want to show the html section outside the our range, by default false.

Both start and end are using the date format specified in the <a href="http://www.php.net/manual/en/datetime.formats.php">php manual</a>. For example, we can specify the timezone of the hour range:

[rotator start="9am -0600" end="5pm -0600"]...[/rotator]

Note: Using GMT notation doesn't work in some installations, not sure which php version is required for that to work.

Note: the hour range is server based time and the default timezone is UTC, in other words, it depends on the configured time of the server, you can set that in <a href="http://en.support.wordpress.com/settings/general-settings/">general settings</a> at the admin console.

There is a page available at "Settings" -> "Schedule generator" in the wp admin console with a basic form which lets you generate the short code configuring different values like start and end times, among others.

== Configuration == 

The first lines of the plugin contains some global variables to configure the plugin, for example, if you want to disable the generator from the admin -> settings page, among other configurations.

- WP_HTML_ROTATOR_DEBUG: defines if the plugin echoes debug information.
- WP_HTML_ROTATOR_ENABLE_WIDGETS: enables the shortcode to work on text widgets.
- WP_HTML_ROTATOR_ENABLE_GENERATOR: enable the shortcode generator to be shown in the admin panel or not.

== Changelog ==

0.1.3

- Changed to use the WordPress timezone configured in <a href="http://en.support.wordpress.com/settings/general-settings/">general settings</a> as the base timezone instead the default from php based on this <a href="http://wordpress.org/support/topic/using-php-timezone?replies=1">WordPress blog post</a>.

- Changed name Rotator Generator to Schedule Generator

- Server time is shown in the Schedule Generator 

0.1.2

- Added more detail in the Description page.

0.1.x

- Added the shortcode with the default parameters.

