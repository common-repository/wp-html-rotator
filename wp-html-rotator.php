<?php

/*
Plugin Name: WP HTML Rotator
Plugin URI: http://wordpress.org/extend/plugins/wp-html-rotator
Description: Schedule HTML sections to be shown or not based on a date range.
Version: 0.1.3
Author: Ariel Coppes and Jason Caluori
Author URI: http://careers.stackoverflow.com/arielcoppes
*/

// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

$WP_HTML_ROTATOR_DEBUG = FALSE;
$WP_HTML_ROTATOR_ENABLE_WIDGETS = TRUE;
$WP_HTML_ROTATOR_ENABLE_GENERATOR = TRUE;

function html_rotation_section($atts, $content = null) {

	global $WP_HTML_ROTATOR_DEBUG;

	// defines the default function parameter values
	$default_atts = array( 
		'visible' => "true",
		'inverse' => null,  
		'start' => null,
		'end' => null,
	);

	// merges the default parameters with the current parameters
	$values = shortcode_atts($default_atts, $atts);

	$visible = $values['visible'];
	$inverse = $values['inverse'];
	$start = $values['start'];
	$end = $values['end'];

	if ($visible != "true")
		return null;

	if ($start == null)
		return "start attribute not provided";

	if ($end == null)
		return "end attribute not provided";

	$gofs = get_option( 'gmt_offset' ); // get WordPress offset in hours
	$tz = date_default_timezone_get(); // get current PHP timezone

	date_default_timezone_set('Etc/GMT'.(($gofs < 0)?'+':'').-$gofs); // set the PHP timezone to match WordPress

	$start_time = strtotime($start);
	$end_time = strtotime($end);

	if ($start_time == -1 or $start_time == FALSE) {
		date_default_timezone_set($tz); // set the PHP timezone back the way it was
		if ($WP_HTML_ROTATOR_DEBUG)
			return "Wrong start attribute"."<br/>";
		return null;
	}

	if ($end_time == -1 or $end_time == FALSE) {
		date_default_timezone_set($tz); // set the PHP timezone back the way it was
		if ($WP_HTML_ROTATOR_DEBUG == TRUE)
			return "Wrong end attribute"."<br/>";
		return null;
	}

	$current_time = time();

	if ($WP_HTML_ROTATOR_DEBUG) {
		echo "current_time: ".date('D, d M Y H:i:s T', $current_time)."<br/>";
		echo "start_time: ".date('D, d M Y H:i:s T', $start_time)."<br/>";
		echo "end_time: ".date('D, d M Y H:i:s T', $end_time)."<br/>";
	}

	if ($inverse == null or $inverse != 'true') {
		if ($current_time < $start_time) {
			date_default_timezone_set($tz); // set the PHP timezone back the way it was
			if ($WP_HTML_ROTATOR_DEBUG)
				return "current time < start time"."<br/>";
			return null;
		}

		if ($current_time > $end_time) {
			date_default_timezone_set($tz); // set the PHP timezone back the way it was
			if ($WP_HTML_ROTATOR_DEBUG)
				return "current time > end time"."<br/>";
			return null;
		}
	} else {
		if ($current_time >= $start_time and $current_time <= $end_time) {
			date_default_timezone_set($tz); // set the PHP timezone back the way it was
			if ($WP_HTML_ROTATOR_DEBUG)
				return "current time >= start time and current time <= end time"."<br/>";
			return null;
		}

	}

	date_default_timezone_set($tz); // set the PHP timezone back the way it was
	return $content;
}

add_shortcode('rotator', 'html_rotation_section');

if ($WP_HTML_ROTATOR_ENABLE_WIDGETS) {
	add_filter( 'widget_text', 'shortcode_unautop');
	add_filter( 'widget_text', 'do_shortcode');
}

// Configuration

if ($WP_HTML_ROTATOR_ENABLE_GENERATOR) {

	add_action('admin_menu', 'html_rotator_menu');

	function html_rotator_menu() {
		add_options_page('HTML Rotator - Schedule shortcode generator', 'Schedule generator', 'manage_options', 'wp-html-rotator-identifier', 'html_rotator_generator');
	}

	function html_rotator_generator() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		$gofs = get_option( 'gmt_offset' ); // get WordPress offset in hours
		$tz = date_default_timezone_get(); // get current PHP timezone

		date_default_timezone_set('Etc/GMT'.(($gofs < 0)?'+':'').-$gofs); // set the PHP timezone to match WordPress

		$current_time = date('Y-m-d g:ia T', time());

		date_default_timezone_set($tz); // set the PHP timezone back the way it was

		?>

		<script type="text/javascript"> 
			function generate_rotator_code() {
				var generatorForm = document.forms[0];

				var timezone = generatorForm.elements["timezone"].value;
				var start = generatorForm.elements["start"].value;
				var end = generatorForm.elements["end"].value;
				var inverse = generatorForm.elements["inverse"].checked;

				var element = document.getElementById('result');
				element.innerHTML = '[rotator ';
				element.innerHTML += 'start="' + start + ' ' + timezone + '" ';
				element.innerHTML += 'end="' + end + ' ' + timezone + '" '; 
		
				if (inverse == true)
					element.innerHTML += 'inverse="true"';

				element.innerHTML += ']' + '<br>';
				element.innerHTML += 'contents...' + '<br>';;
				element.innerHTML += '[/rotator]'+ '<br>';
				return false;
			}
		</script>

		<div id="wp-html-rotator-generator">

		<div class="current-time">
			WordPress server time: <? echo $current_time; ?>
		</div>
		<hr />

		<form>

		Timezone:
		<select name="timezone">
		  <option value="+1400">UTC+14</option>
		  <option value="+1300">UTC+13</option>
		  <option value="+1200">UTC+12</option>
		  <option value="+1100">UTC+11</option>
		  <option value="+1000">UTC+10</option>
		  <option value="+0900">UTC+09</option>
		  <option value="+0800">UTC+08</option>
		  <option value="+0700">UTC+07</option>
		  <option value="+0600">UTC+06</option>
		  <option value="+0500">UTC+05</option>
		  <option value="+0400">UTC+04</option>
		  <option value="+0300">UTC+03</option>
		  <option value="+0200">UTC+02</option>
		  <option value="+0100">UTC+01</option>
		  <option selected="selected" value="">UTC</option>
		  <option value="-0100">UTC-01</option>
		  <option value="-0200">UTC-02</option>
		  <option value="-0300">UTC-03</option>
		  <option value="-0400">UTC-04</option>
		  <option value="-0500">UTC-05</option>
		  <option value="-0600">UTC-06</option>
		  <option value="-0700">UTC-07</option>
		  <option value="-0800">UTC-08</option>
		  <option value="-0900">UTC-09</option>
		  <option value="-1000">UTC-10</option>
		  <option value="-1100">UTC-11</option>
		  <option value="-1200">UTC-12</option>
		</select> 

		Start:
		<select name="start">
		  <option>1am</option>
		  <option>2am</option>
		  <option>3am</option>
		  <option>4am</option>
		  <option>5am</option>
		  <option>6am</option>
		  <option>7am</option>
		  <option>8am</option>
		  <option>9am</option>
		  <option>10am</option>
		  <option>11am</option>
		  <option>12pm</option>
		  <option>1pm</option>
		  <option>2pm</option>
		  <option>3pm</option>
		  <option>4pm</option>
		  <option>5pm</option>
		  <option>6pm</option>
		  <option>7pm</option>
		  <option>8pm</option>
		  <option>9pm</option>
		  <option>10pm</option>
		  <option>11pm</option>
		  <option>12am</option>
		</select> 

		End:
		<select name="end">
		  <option>1am</option>
		  <option>2am</option>
		  <option>3am</option>
		  <option>4am</option>
		  <option>5am</option>
		  <option>6am</option>
		  <option>7am</option>
		  <option>8am</option>
		  <option>9am</option>
		  <option>10am</option>
		  <option>11am</option>
		  <option>12pm</option>
		  <option>1pm</option>
		  <option>2pm</option>
		  <option>3pm</option>
		  <option>4pm</option>
		  <option>5pm</option>
		  <option>6pm</option>
		  <option>7pm</option>
		  <option>8pm</option>
		  <option>9pm</option>
		  <option>10pm</option>
		  <option>11pm</option>
		  <option>12am</option>
		</select>

		Inverse:

		<input type="checkbox" name="inverse" /> <br/>
		<br/>

		<input type="submit" value="Generate" onclick="generate_rotator_code(); return false;">

		</form>

		</div>

		<div id="result"></div>

		<?php

	}

}

?>
