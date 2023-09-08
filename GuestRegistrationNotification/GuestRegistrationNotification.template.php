<?php

function template_grn_above() {
    
	global $modSettings;
			
	$notification = (!empty($modSettings['guest_notify_css'])) ? '<div style="padding: 1em;margin-bottom: 1em;' . $modSettings['guest_notify_css'] . '">' : '<div style="padding: 1em;border: 1px solid #cc3344;color: #000;background-color: #ffe4e9;margin-bottom: 1em;">';
	$notification .= (!empty($modSettings['guest_notify_img_path'])) ? '<img style="float: left; width: 2ex; font-size: 2em; color: red; padding-right: 5px;" src="' . $modSettings['guest_notify_img_path'] . '" title="" />' : '<p style="padding: 0;margin: 0;float: left;width: 1em;font-size: 1.5em;color:red;">!!</p>';
	$notification .= (!empty($modSettings['guest_notify_css_title'])) ? '<h3 style="padding: 0;margin: 0;' . $modSettings['guest_notify_css_title'] . '">' . $modSettings['guest_notify_title'] . '</h3>' : '<h3 style="padding: 0;margin: 0;font-size: 1.1em;text-decoration: underline;">' . $modSettings['guest_notify_title'] . '</h3>';
	$notification .= '<p style="margin: 1em 0 0 0;">' . parse_bbc($modSettings['guest_notify_contents']) . '</p></div>';
	echo $notification;
}

function template_grn_below() {
}

?>