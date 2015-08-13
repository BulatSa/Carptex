<?php
  function utf8_substr_replace($str, $repl, $start , $length = NULL ) {
      preg_match_all('/./us', $str, $ar);
      preg_match_all('/./us', $repl, $rar);
      if( $length === NULL ) {
          $length = utf8_strlen($str);
      }
      array_splice( $ar[0], $start, $length, $rar[0] );
      return join('',$ar[0]);
  }


 function utf8_preg_match_all(
        $ps_pattern,
        $ps_subject,
        &$pa_matches,
        $pn_flags = PREG_PATTERN_ORDER,
        $pn_offset = 0,
        $ps_encoding = 'UTF-8'
    ) {

        // WARNING! - All this function does is to correct offsets, nothing else:
        //(code is independent of PREG_PATTER_ORDER / PREG_SET_ORDER)

       // if (is_null($ps_encoding)) $ps_encoding = mb_internal_encoding();

        $pn_offset = strlen(utf8_substr($ps_subject, 0, $pn_offset, $ps_encoding));
        $ret = preg_match_all($ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset);

        if ($ret && ($pn_flags & PREG_OFFSET_CAPTURE)){

            foreach($pa_matches as &$ha_match)
                foreach($ha_match as &$ha_match)
                if (isset($ha_match[1]))
                    	$ha_match[1] = utf8_strlen(substr($ps_subject, 0, $ha_match[1]), $ps_encoding);
                    }

        return $ret;

    }

function is_utf8($string) {
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



?>