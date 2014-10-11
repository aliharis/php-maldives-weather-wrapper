<?php

/**
 * Maldives Weather Information Wrapper
 * Retrives weather information from Dept. of Met
 * @author Ali Haris (www.github.com/aliharis)
 */

header("Content-type: application/json;charset=utf-8");

require 'simple_html_dom.php';
require 'MvWeather.php';

$dom = new simple_html_dom();
$Weather = new MvWeather($dom);

/** Handle the request */
$locations = $Weather->getLocations();
$request = (!empty($_GET['location'])) ? $_GET['location'] : '';

if (in_array($request, $locations)):
	echo json_encode($Weather->getDetails($request));

else:
	echo json_encode($Weather->getSummary());	 
endif;