<?php

/**
 * SCRAPCHECK HELPER FUNCTIONS
 *
 */

function sc_IssetValue($var) {
	if (isset($var) && $var !='') return $var;
	return '';
}

function sc_IssetInt($var) {
	if (isset($var)) {
		if ($var != '') {
			$var = str_replace(',', '', $var);
			$var = intval($var);
			return $var;
		}
	}
	return '';
}


function sc_ExceptionHandler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("sc_ExceptionHandler");
