Maldives Weather Wrapper
========================

This script fetches Weather Information from Maldives Meteorology website and displays in 
json format.

Requirements
------------
- [PHP Simple HTML Dom Parser](http://simplehtmldom.sourceforge.net/)

Basic Usage
-----------
    <?php
    $dom = new simple_html_dom();
    $Weather = new MvWeather($dom);
    
    // Get Weather Summary
    print_r($Weather->getSummary());
    
    // Get Weather Details for a Location
    print_r($Weather->getDetails('Gan'));
    
Using Example File
------------------
For your convinience I have added an example program (index.php) which returns a json feed.
- To get weather summary: index.php
- To get weather details for a location: index.php?location=Male

Demonstration
-------------
- Weather Summary: http://demo.aliharis.net/php-maldives-weather-wrapper/
- Weather Details for Location: http://demo.aliharis.net/php-maldives-weather-wrapper/?location=Hanimaadhoo

To-do
----
- Caching
