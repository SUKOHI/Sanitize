<?php

class Sanitize {
	
	public function get($str) {
	
		if(is_array($str)) {
	
			return array_map(array($this, 'get'), $str);
	
		} else {
	
			if(ini_get('magic_quotes_gpc') == '1') $str = stripslashes($str);
	
			$return = htmlspecialchars($str, ENT_QUOTES);
	
			if(preg_match_all('!(&[a-zA-Z]+;|&#?[0-9]+;)!', $str, $matches)) {
	
				$return = preg_replace_callback('|&[A-Z]+;|', array($this, 'getLower'), $return);
				$matches = array_unique($matches[1]);
	
				foreach($matches as $replacement) {
	
					$target = '&amp;'. substr($replacement, 1);
					$return = str_replace($target, $replacement, $return);
	
				}
	
			}
	
			return $return;
	
		}
	
	}
	
	public function getLower($str) {
	
		return strtolower($str[0]);
	
	}
	
}
/*** Example

	require 'sanitize.php';
	
	$sanitize = new Sanitize();
	echo $sanitize->get($str_or_array);

***/
