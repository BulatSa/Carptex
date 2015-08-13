<?php

namespace JBBCode\validators;

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'InputValidator.php';

/**
 * An InputValidator for urls. This can be used to make [url] bbcodes secure.
 *
 * @author jbowens
 * @since May 2013
 */
class UrlValidator implements \JBBCode\InputValidator
{

    /**
     * Returns true iff $input is a valid url.
     *
     * @param $input  the string to validate
     */
    public function validate($input)
    {
        if ($this->is_utf8($input)) {
         $valid = html_entity_decode($input, ENT_QUOTES, 'UTF-8');
        } else {
         $valid = filter_var($input, FILTER_VALIDATE_URL);
        }
        return !!$valid;
    }


	public function is_utf8($string) {
	 for ($i=0; $i<strlen($string); $i++) {
	  if (ord($string[$i]) < 0x80) continue;
	  elseif ((ord($string[$i]) & 0xE0) == 0xC0) $n=1;
	  elseif ((ord($string[$i]) & 0xF0) == 0xE0) $n=2;
	  elseif ((ord($string[$i]) & 0xF8) == 0xF0) $n=3;
	  elseif ((ord($string[$i]) & 0xFC) == 0xF8) $n=4;
	  elseif ((ord($string[$i]) & 0xFE) == 0xFC) $n=5;
	  else return false;
	  for ($j=0; $j<$n; $j++) {
	   if ((++$i == strlen($string)) || ((ord($string[$i]) & 0xC0) != 0x80))
	    return false;
	  }
	 }
	 return true;
    }

}
