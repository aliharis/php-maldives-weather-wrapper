<?php

/**
 * Fetch Remote Page
 */
function fetchPage($base) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($curl, CURLOPT_URL, $base);
	curl_setopt($curl, CURLOPT_REFERER, $base);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$str = curl_exec($curl);
	curl_close($curl);

	return $str;
}

/**
 * Find extended weather information 
 * @param (String) location
 * @return (Array) weather_data
 */
$weatherDetails = function($location) {

	$html = new simple_html_dom();
	$html->load(fetchPage('http://www.meteorology.gov.mv/met/index.php?action=refresh
		&location=true&hold='.$location.'&base=tepx&bind=446d0b7c5f8bd34762b02d28ca6dacc3'));

	$data = array();

	// Fetch the required information
	$data['last_updated'] = str_replace('Last Updated on : ', '', 
		$html->find('#General_pane div div div', 0)->plaintext);
	$data['sunrise']  = $html->find('#General_pane div div div', 6)->plaintext;
	$data['sunset']   = $html->find('#General_pane div div div', 8)->plaintext;
	$data['moonrise'] = $html->find('#General_pane div div div', 10)->plaintext;
	$data['moonset']  = $html->find('#General_pane div div div', 12)->plaintext;
	$data['humidity'] = $html->find('#General_pane div div div', 14)->plaintext;
	$data['rainfall'] = $html->find('#General_pane div div div', 16)->plaintext;
	$data['wind']     = $html->find('#General_pane div div div', 18)->plaintext;
	$data['sunrise']  = $html->find('#General_pane div div div', 20)->plaintext;
	$data['temperature'] = $html->find('#General_pane div div div span', 0)->plaintext;
	$tmp = explode(" ", $html->find('#General_pane div div div span', 1)->plaintext);
	$data['high'] = $tmp[1];
	$data['low'] = $tmp[4];
	$data['location'] = $html->find('#General_pane div div div span', 2)->plaintext;
	$data['condition'] = $html->find('#General_pane div div div span', 3)->plaintext;
	$data['image'] = 'http://www.meteorology.gov.mv/met/'.
		$html->find('#General_pane div div div div img', 0)->getAttribute('src');

	// Sort the array keys by alphabetical
	ksort($data);
	return $data;
};


$weatherSummary = function($locations) {
	$html = new simple_html_dom();
	$html->load(fetchPage('http://www.meteorology.gov.mv/met/index.php'));
	
	$data = array();

	foreach ($html->find('.m4-navigation2_sub') as $k => $loc) {
		$data[$locations[$k]][] = $loc->find('p', 0)->plaintext;
		$data[$locations[$k]][] = 'http://www.meteorology.gov.mv/met/'.
			$html->find('.m4-navigation2_sub p img', 1)->getAttribute('src');
		$data[$locations[$k]][] = $loc->find('p', 2)->plaintext;
		$data[$locations[$k]][] = $loc->find('p', 3)->plaintext;
	}

	return $data;
};

