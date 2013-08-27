<?php
function gaq_link($base_url){
    $ga_cookies = array(        // Prepare Array to hold GA Variables from Cookies
        '__utma' => '' , 
        '__utmb' => '' , 
        '__utmc' => '' , 
        '__utmx' => '' , 
        '__utmz' => '' , 
        '__utmv' => ''
    );

    // Search cookies for appropriate non-empty values
    foreach($ga_cookies as $cookie_name=>$cookie_value){
            $ga_cookies[$cookie_name] = (array_key_exists($cookie_name,$_COOKIE) && strlen(trim($_COOKIE[$cookie_name])) > 0) ? $_COOKIE[$cookie_name] : '';
    }


    $hash_input = '';       // store concatenated values of ga variables
    $query_vars = array();  // store values for building query string
    foreach($url_vars as $cookie_name=>$cookie_value){
        if(strlen(trim($cookie_value)) > 0){
            $hash_input .= $a_val;
            $query_vars[$cookie_name] = $cookie_value;    
        }
    }

    /**
     * Hash function shamelessly pulled from: https://code.google.com/p/gaforflash/
     * https://gaforflash.googlecode.com/svn/trunk/src/com/google/analytics/core/generateHash.as
     * Translated to PHP by Stephen Cotton @ Neptune Web
     */

    $hash      = 1; // hash buffer
    $leftMost7 = 0; // left-most 7 bits
    $pos;           // character position in string
    $current;       // current character in string

    if($hash_input != null && $hash_input != "")
    {
        $hash = 0;
       
        for( $pos = strlen($hash_input) - 1 ; $pos >= 0 ; $pos-- )
        {
            $current   = ord(substr($hash_input, $pos, 1));
            $hash = (($hash << 6) & 0xfffffff) + $current + ($current << 14);
            $leftMost7 = $hash & 0xfe00000;

            if($leftMost7 != 0)
            {
                $hash ^= $leftMost7 >> 21;
            }
        }
    }

    $query_vars['__utmk'] = $hash;  // Add on our hash for our query string

    if(stristr($base_url, '?')) $base_url .= '&'.http_build_query($query_vars);
    else                        $base_url .= '?'.http_build_query($query_vars);
    
    return $base_url;

}

?>