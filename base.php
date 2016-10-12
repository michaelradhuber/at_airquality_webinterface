<?php
/*
 * This project is to read out and display Upper-Austrian air-quality and pollution data from public air-quality measuring stations.
 * Copyright Michael Radhuber, Dria Profiling www.dria-profiling.com. All rights reserved.
 */
 
 
 // Config vars are located in:
 require_once('config.php');
 require_once('class.base.php');

// Initialize JSON class - don't forget to pass global var $cachetime
$json = new json($cachetime);
$alert = new alert($cachetime);

//Show overall alert level and warnings - call objects not methods to save on CPU!!
$alert_monitor = $alert->alert_monitor;
$alert_message = $alert->overall_alert_message;
$individual_alert_message = $alert->individual_alert_message;
$class_warnings = $json->warning;
$last_update = "{$json->json_time($json->json_last_update)[0]},&nbsp{$json->json_time($json->json_last_update)[1]}";
$index = $alert->overall_index;

//Create print outputs for values
//replace dot with comma func
function dot_comma ($value) {
    $value = round($value, 2);
    $value = strval($value);
    $value = str_replace('.', ',', $value);
    return $value;
}


//Warning function
function warning() {
            global $class_warnings;
            global $warning_box;
        if ($class_warnings != NULL) {
            $warning = "{$warning_box[1]}{$class_warnings}{$warning_box[2]}";
            return $warning;
        }
    }

//Map values to variables
$PM25 = dot_comma($json->json_latest['PM25kont']['messwert']);
$PM25_time = "{$json->json_time($json->json_latest['PM25kont']['zeitpunkt'])[0]},&nbsp{$json->json_time($json->json_latest['PM25kont']['zeitpunkt'])[1]}";
$PM25_unit = $json->json_latest['PM25kont']['einheit'];
$PM25_icon = $json->json_icon('PM25kont');
$PM25_color = $alert->individual_alert_color['PM25kont']['bg-color'];
$PM25_image = $alert->individual_alert_color['PM25kont']['images'];

$PM10 = dot_comma($json->json_latest['PM10kont']['messwert']);
$PM10_time = "{$json->json_time($json->json_latest['PM10kont']['zeitpunkt'])[0]},&nbsp{$json->json_time($json->json_latest['PM10kont']['zeitpunkt'])[1]}";
$PM10_unit = $json->json_latest['PM10kont']['einheit'];
$PM10_icon = $json->json_icon('PM10kont');
$PM10_color = $alert->individual_alert_color['PM10kont']['bg-color'];
$PM10_image = $alert->individual_alert_color['PM10kont']['images'];

$SO2 = dot_comma($json->json_latest['SO2']['messwert']);
$SO2_time = "{$json->json_time($json->json_latest['SO2']['zeitpunkt'])[0]},&nbsp{$json->json_time($json->json_latest['SO2']['zeitpunkt'])[1]}";
$SO2_unit = $json->json_latest['SO2']['einheit'];
$SO2_icon = $json->json_icon('SO2');
$SO2_color = $alert->individual_alert_color['SO2']['bg-color'];
$SO2_image = $alert->individual_alert_color['SO2']['images'];

$NO2 = dot_comma($json->json_latest['NO2']['messwert']);
$NO2_time = "{$json->json_time($json->json_latest['NO2']['zeitpunkt'])[0]},&nbsp{$json->json_time($json->json_latest['NO2']['zeitpunkt'])[1]}";
$NO2_unit = $json->json_latest['NO2']['einheit'];
$NO2_icon = $json->json_icon('NO2');
$NO2_color = $alert->individual_alert_color['NO2']['bg-color'];
$NO2_image = $alert->individual_alert_color['NO2']['images'];

$O3 = dot_comma($json->json_latest['O3']['messwert']);
$O3_time = "{$json->json_time($json->json_latest['O3']['zeitpunkt'])[0]},&nbsp{$json->json_time($json->json_latest['O3']['zeitpunkt'])[1]}";
$O3_unit = $json->json_latest['O3']['einheit'];
$O3_icon = $json->json_icon('O3');
$O3_color = $alert->individual_alert_color['O3']['bg-color'];
$O3_image = $alert->individual_alert_color['O3']['images'];

//var_dump($alert->individual_alert_color);

//var_dump($json->json_trend('3600000'));

//print_r($json->json_trend);
/*
var_dump($alert->overall_alert_message());
var_dump($alert->overall_alert());
var_dump($alert->alert_monitor());
var_dump($json->json_timespam(86000000));
var_dump($json->wincache);
var_dump($json->newcache);
*/
//var_dump($json->json_trend(3600000));
 
//var_dump($json->json_latest);

//var_dump($json->json_trend);
//var_dump($alert->alert_level());
//var_dump($alert->overall_index);
//var_dump($alert->individual_alert_message());
//var_dump($individual_alert_message);
//var_dump($json->wincache);
//var_dump($json->cachefile('S173'));
//var_dump($json->wincache);
//var_dump($alert->overall_alert_color());
?>