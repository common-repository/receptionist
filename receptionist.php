<?php
/*
Plugin Name: Receptionist
Plugin URI: http://www.achill-online.net/wordpress-plugins/receptionist
Description: This Plugins grabs the referral google search string and displays a "Can We help you?" Box  
Version: 0.2
Author: Chris B. Kerndter, based on a Idea by Gordon Murray (Murrion Software)
Author URI: http://www.achill-online.net/

/*  Copyright 2009  Chris B. kerndter  (email : chris@achill-online.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$notify = false; // if you want to get notified every time a google referred visitor comes to your site, put this to true
$recipient = ""; // put your email address here, this is used for notification
$subject = "Receptionist 0.2 - "; //Subject of notification.

$referer = ""; //just leave blank
$query = ""; //just leave blank

function get_query_details_from_referer() {
	
	global $referer;
	global $query;
	global $notify;
	
	$urlinfo = parse_url($_SERVER['HTTP_REFERER']);
	if (strpos($urlinfo['host'], "google") !== false && $urlinfo['query'] != "") {
		$referer = $_SERVER['HTTP_REFERER'];
		//echo "<!--"; print_r($_SERVER); echo "-->";
		$tmp = explode("&", $urlinfo['query']);
		foreach ($tmp as $element) {
			$tmp2 = explode("=", $element);
			$urlinfo['querydetails'][$tmp2[0]] = $tmp2[1];
		}
		$query = str_replace("+", " ", $urlinfo['querydetails']['q']);
		if ($notify) {
			sendmail($query, $referer);
		}
		echo "<link rel='stylesheet' type='text/css' href='/wp-content/plugins/receptionist/receptionist.css'>";
	}
}

function show_box() {

	global $referer;
	global $query;
	
	if ($query != "") {
		echo '<div class="footer">
		<form name="receptionist" action="/wp-content/plugins/receptionist/sendmail.php" method="post">
		<strong>Can I help? </strong> <input onClick=\'this.value="";\' name="message" type="text" 
		value="You\'re searching for \''.$query.'\'? If you leave a message with contact details I\'ll email you back" size="85" maxlength="255">
		<input onClick=\'this.value="";\' name="email" type="text" value="Don\'t forget your eMail-Address!" size="20" maxlength="255">
		<input type="hidden" name="referer" value="'. $referer .'" />
		<input type="hidden" name="query" value="'. $query .'" />
		<input name="send" type="submit" value="Send" />
		</form>
		</div>';
	}
}

function sendmail($query, $referer) {
	
	global $recipient;
	global $subject;
	
	$to = $recipient;
	$subject .= '"'.$query.'"';
	$message = $referer;
	$header = 'From: automailer@achill-online.net' . "\r\n";
	$header .= 'X-Mailer: PHP/' . phpversion() . "\r\n"; 
	mail($to, $subject, $message, $header);
}

add_action('wp_head', 'get_query_details_from_referer', 10, 0);
add_action('wp_footer', 'show_box', 10, 0);
?>
