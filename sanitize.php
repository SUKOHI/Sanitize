<?php

class Sanitize {

	public function get($str) {

		if(is_array($str)) {

			return array_map(array($this, 'get'), $str);

		}

		if(ini_get('magic_quotes_gpc') == '1') {
				
			$str = stripslashes($str);
				
		}

		$return = htmlspecialchars($str, ENT_QUOTES);

		if(preg_match_all('!(&[a-zA-Z]+;|&#?[0-9]+;)!', $str, $matches)) {

			$return = preg_replace_callback('|&[A-Z]+;|', array($this, 'getLowerCase'), $return);
			$matches = array_unique($matches[1]);

			foreach($matches as $replacement) {

				$target = '&amp;'. substr($replacement, 1);
				$return = str_replace($target, $replacement, $return);

			}

		}

		return $return;

	}

	public function restore($str) {

		if(is_array($str)) {

			return array_map(array($this, 'restore'), $str);

		}

		$targets = array('&amp;', '&quot;', '&#039;', '&apos;', '&lt;', '&gt;');
		$replacements = array('&', '"', '\'', '\'', '<', '>');
		return str_replace($targets, $replacements, $str);

	}

	private function getLowerCase($str) {

		return strtolower($str[0]);

	}

}
/*** Example

require 'sanitize.php';

$sanitize = new Sanitize();
echo $sanitize->get($str_or_array);
echo $sanitize->restore($str_or_array);

***/
