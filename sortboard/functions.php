<?php

function queryString($str,$val) {
	$queryString = array();
	$queryString = $_GET;
	$queryString[$str] = $val;
	$queryString = "?".htmlspecialchars(http_build_query($queryString),ENT_QUOTES);		
	return $queryString;
}

?>