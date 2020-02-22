<?php

function scripturecloud_version_check()
	{
	$x = "scripturecloud_version";
	if (get_option($x) != scripturecloud_version())		// if versions don't match...
		{
		scripturecloud_update_all();			// reload everything
		delete_option($x);
		add_option($x, scripturecloud_version());		// update version no
		}
	}

function scripturecloud_update_all()
	{
	$r = get_posts( array('numberposts'=>1000,'post_status' => 'publish') );
	foreach($r as $post)
		scripturecloud_update_post($post->ID, $post->post_content);

	
	}

function scripturecloud_update_post($id,$content='#')
	{
//print "<br/>scripturecloud_update_post($id,$content)";
	if ($content=='#')
		{
		$post = get_post($id);
		$content = $post->post_content;
		unset($post);
		}

	$new_refs = scripturecloud_verse($content);					// Generate new verse list
	if ($new_refs=="") $new_refs = "None";


	delete_post_meta($id, "scripturecloud_ref");
	add_post_meta($id, "scripturecloud_ref", $new_refs);				// store it on the post

//	delete_post_meta($id, "scripturecloud_ver");
//	add_post_meta($id, "scripturecloud_ver", scripturecloud_version());		// store version
	}

function scripturecloud_getrefs()
	{
	$final = array();
	$r = get_posts( array('numberposts'=>1000,'post_status' => 'publish') );
	foreach($r as $post)
		{
		
//		$postver = get_post_meta($post->ID, "scripturecloud_ver", true);			// check verse list version, if needed
		$postref = get_post_meta($post->ID, "scripturecloud_ref", true);			// check for previously generated verse list

//		if ($postver != scripturecloud_version()) $postref = "";				// invalidate older verse lists, if needed

		if ($postref == "None") continue;

		if ($postref == "") 
			scripturecloud_update_post($post->ID, $post->post_content);

		foreach(explode(scripturecloud_sep(),$postref) as $ref)							// process each verse
			{
			if ($ref=="") continue;
			if (isset($final[$ref]))
				$final[$ref] = $final[$ref] + 1; 
			else 
				$final[$ref] = 1;
			}
		}
	return $final;
	}

function scripturecloud_show($opts) {
	$s = "";

	$defaults = scripturecloud_defaults();
	$refs = scripturecloud_getrefs();
	if (count($refs)==0) return "";
//print_r($refs);
//return;

//print_r($opts);
	$imin = $opts['min'];
	$imax = $opts['max'];
	$largest = $opts['largest'];
	$smallest = $opts['smallest'];
//print "<br/>imin=$imin";

	$max = max(array_values($refs));
	$min = min(array_values($refs));  // usually 1, the way it goes...

	$s .= "<div class='wrap'>\n";
	foreach($refs as $a=>$b)
		{
		if ($imin!=0 && $b<$imin) continue;
		if ($imax!=0 && $b>$imax) continue;

		if ($max == $min) 
			$z = 10;	// average size..?  could do ($largest-$smallest)/2
		else
			$z = ($b - $min) / ($max - $min) * ($largest - $smallest) + $smallest;

		$s .= "<span style='font:normal {$z}px/10px Tahoma' title='$b topics'> ";
		$s .= "<a href='/?s=".urlencode("\"$a\"")."'>";
		$s .= "$a";
		$s .= "</a>";
		$s .= "</span>";
		}

	$s .= "</div>\n";
	return $s;
	}

?>