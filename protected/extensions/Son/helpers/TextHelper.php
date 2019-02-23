<?php
class TextHelper {
	public static function getShortDescription($text,$charsLimit=100){
	    if (strlen($text) > $charsLimit)
	    {
	        $new_text = substr($text, 0, $charsLimit);
	        $new_text = trim($new_text);
	        return $new_text . "...";
	    }
	    else
	    {
	    	return $text;
	    }
	}
}