<?php

/**
 * Maldives Weather Information
 * @author Ali Haris (http://github.com/aliharis)
 */

header("Content-type: application/json;charset=utf-8");

require 'simple_html_dom.php';
require 'functions.php';

/** Handle the request */
$locations = array('Male', 'Kadhdhoo', 'Kaadedhdhoo', 'Hanimaadhoo', 'Gan');
$request = (!empty($_GET['location'])) ? $_GET['location'] : '';

if (in_array($request, $locations)):
	echo json_encode($weatherDetails($request));

else:
	echo json_encode($weatherSummary($locations));	 
endif;


