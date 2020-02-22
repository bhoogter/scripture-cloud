<?php
/*
Plugin Name: Scripture Cloud
Version: 1.0.2
Plugin URI: http://www.churchofthebeyond.com/
Description: Scans and pages for scriptures, and displays them in cloud format (like tags, only scripture references auto-detected).
Author: Benjamin Hoogterp
Author URI: http://www.blazingglory.org/
Contributors: bhoogterp
*/

function scripturecloud_version() {return "1.0.2";}
function scripturecloud_sep() {return ";";}


include_once("scripturecloud_scripturizer.php");
include_once("scripturecloud_show.php");


function scripturecloud_defaults()	{
	return	array(
			'title' => 'Scriptures', 
			'min'		=> 0,
			'max'		=> 0,
			'smallest'	=> 5,
			'largest'	=> 40,
			);
	}

// Add our function to the widgets_init hook.
add_action('widgets_init', 'scripturecloud_load');
add_action('save_post', 'scripturecloud_save');

function scripturecloud_load() 	{	include_once("scripturecloud_widget.php"); register_widget('scripturecloud_widget');	}
function scripturecloud_save($id)	{	scripturecloud_update_post($id);	}

?>