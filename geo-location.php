<?php
/*
Plugin Name: MaxMind Geo-IP Plugin
Plugin URI: http://google.com/
Version: 0.1
Author: Robert Miner
Description: Simple plugin to get visitor information.  Shortcodes are supported.
Author URI: http://google.com
License: GPL2

Copyright 2013 Robert Miner

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	include( plugin_dir_path( __FILE__ ) . 'geoip.inc');
	include( plugin_dir_path( __FILE__ ) . 'geoipcity.inc');
	$gi = geoip_open(plugin_dir_path( __FILE__ ) . 'GeoIP.dat',GEOIP_STANDARD);
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$record = geoip_record_by_addr($gi , $user_ip);
		// set up the WPGeoLocation class
	
	if (!class_exists("WPGeoLocation")) {
	class WPGeoLocation {
		public $record;
		function WPGeoLocation() { //constructor
		
		}

		function getCountryCode() {
			return $record->country_code;
		}
		function getCountryName() {
			$record->country_name;
		}
		function getCity() {
			print $record->city . "\n";
		}
		function getRegion() {
			return $record->region;
		}
		function getRegionName() {
			return $GEOIP_REGION_NAME[$record->country_code][$record->region];
		}
		function getLatitude() {
			return $record->latitude;
		}
		function getLongitude() {
			return $record->longitude;
		}
		function getPostalCode() {
			return '';
		}

		function getIPAddress() {
			return $_SERVER['REMOTE_ADDR'];
		}

	}
} // end class WPGeoLocation

// initialize the WPGeoLocation class
if (class_exists("WPGeoLocation")) {
    $wp_geolocation = new WPGeoLocation();
	global $geo_countrycode;
	global $geo_countryname;
	global $geo_city;
	global $geo_region;
	global $geo_regionname;
	global $geo_latitude;
	global $geo_longitude;
	global $geo_postalcode;
}

// set up actions and filters
if (isset($wp_geolocation)) {

    add_shortcode('mmjs-countrycode', array(&$wp_geolocation, 'getCountryCode'));
    add_shortcode('mmjs-countryname', array(&$wp_geolocation, 'getCountryName'));
    add_shortcode('mmjs-city', array(&$wp_geolocation, 'getCity'));
    add_shortcode('mmjs-region', array(&$wp_geolocation, 'getRegion'));
    add_shortcode('mmjs-regionname', array(&$wp_geolocation, 'getRegionName'));
    add_shortcode('mmjs-lat', array(&$wp_geolocation, 'getLatitude'));
    add_shortcode('mmjs-long', array(&$wp_geolocation, 'getLongitude'));
    add_shortcode('mmjs-ip', array(&$wp_geolocation, 'getIPAddress'));
    add_shortcode('mmjs-postalcode', array(&$wp_geolocation, 'getPostalCode'));
}
?>