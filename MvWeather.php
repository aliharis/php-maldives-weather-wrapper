<?php

class MvWeather {

	private $locations = array('Male', 'Kadhdhoo', 'Kaadedhdhoo', 'Hanimaadhoo', 'Gan');
	private $dom;

	public function __construct(simple_html_dom $dom) {
		$this->dom = $dom;
	}

	public function getSummary() {
		$this->dom->load($this->fetchPage('http://www.meteorology.gov.mv/met/index.php'));

		$data = array();

		foreach ($this->dom->find('.m4-navigation2_sub') as $k => $loc) {
			$data[$this->locations[$k]][] = $loc->find('p', 0)->plaintext;
			$data[$this->locations[$k]][] = 'http://www.meteorology.gov.mv/met/'.
				$this->dom->find('.m4-navigation2_sub p img', 1)->getAttribute('src');
			$data[$this->locations[$k]][] = $loc->find('p', 2)->plaintext;
			$data[$this->locations[$k]][] = $loc->find('p', 3)->plaintext;
		}

		return $data;
	}

	public function getDetails($location) {
		$this->dom->load($this->fetchPage('http://www.meteorology.gov.mv/met/index.php?hold='.$location));

		$data = array();

		// Fetch the required information
		$data['last_updated'] = str_replace('Last Updated on : ', '', 
			$this->dom->find('#General_pane div div div', 0)->plaintext);
		$data['sunrise']  = $this->dom->find('#General_pane div div div', 6)->plaintext;
		$data['sunset']   = $this->dom->find('#General_pane div div div', 8)->plaintext;
		$data['moonrise'] = $this->dom->find('#General_pane div div div', 10)->plaintext;
		$data['moonset']  = $this->dom->find('#General_pane div div div', 12)->plaintext;
		$data['humidity'] = $this->dom->find('#General_pane div div div', 14)->plaintext;
		$data['rainfall'] = $this->dom->find('#General_pane div div div', 16)->plaintext;
		$data['wind']     = $this->dom->find('#General_pane div div div', 18)->plaintext;
		$data['sunrise']  = $this->dom->find('#General_pane div div div', 20)->plaintext;
		$data['temperature'] = $this->dom->find('#General_pane div div div span', 0)->plaintext;
		$tmp = explode(" ", $this->dom->find('#General_pane div div div span', 1)->plaintext);
		$data['high'] = $tmp[1];
		$data['low'] = $tmp[4];
		$data['location'] = $this->dom->find('#General_pane div div div span', 2)->plaintext;
		$data['condition'] = $this->dom->find('#General_pane div div div span', 3)->plaintext;
		$data['image'] = 'http://www.meteorology.gov.mv/met/'.
			$this->dom->find('#General_pane div div div div img', 0)->getAttribute('src');

		ksort($data);	// Sort the array keys alphabetically
		return $data;
	}

	public function getLocations() {
		return $this->locations;
	}

	private function fetchPage($base) {
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

}