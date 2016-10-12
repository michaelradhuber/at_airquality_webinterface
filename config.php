<?php
/*
 * This file contains config variables for local setup.
 */
 
 //Set includes
 set_include_path('/PHPMailer');
 
 // Set debugging vars:
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

//Set timezone, locale:
date_default_timezone_set('Europe/Vienna');
setlocale(LC_TIME, 'de_DE.utf8');

//Set base-url, always set final slash /!!
$base_url = 'http://luft.steyregg.com/';

//Set time for caching JSON output from externals erver before re-requesting it in seconds:
$cachetime = 600;

//Set timespan for interval between warning messages, in seconds, 3600 == 1hr
$warningspan = 86400;

//MySQL config:
$mysql_servername = "localhost";
$mysql_username = "steyregg";
$mysql_password = "ko17apr";
$mysql_dbname = "steyregglive";

//Mail config. requires secure (tls) smtp mail. for more see class.mail.com. sends only html mails in utf8.
$mailhost = "mail.steyregg.com";
$mailuser = "nachrichten@steyregg.com";
$mailpass = "zum768mal";
$mailport = "587";
$mailfrommail = "luft@steyregg.com";
$mailfromname = "Luftgüte Mailservice";

//WhatsApp config:

$username = '4367761966628';                      // Telephone number including the country code without '+' or '00'.
$password = 'PocrxqpBmLoY8K9yD6MARfJ1O34=';     // Use registerTool.php or exampleRegister.php to obtain your password
$nickname = 'LÜS - Luftgüte Steyregg. Antworten an diese Nummer werden nicht gelesen!';                          // This is the username (or nickname) displayed by WhatsApp clients.
$debug = true;                                           // Set this to true, to see debug mode.

//Set design for warning box:
$warning_box = array(
    1 => '<div class="w3-container w3-yellow w3-round-large"><h3>Hinweis</h3>',
    2 => '</div>',
    );

// Set component codes from external JSON file, source station for component from JSON array to use. 
// Do not change array keys and amount of components! Keep the order of sub-arrays for passing them to function calls!
// Keys must be the same as in the following array!!!

$SeSt = array(
    //PM25
    'PM25kont' => array(
        'station' => 'S173',
    ),
    
    //PM10
    'PM10kont' => array(
        'station' => 'S173',
    ),
    
    //SO2
    'SO2' => array(
        'station' => 'S173',
    ),
    
    
    //NO2
    'NO2' => array(
        'station' => 'S173',
    ),
    
    
    //O3
    'O3' => array(
        'station' => 'S184',
    ),
);

//For the front page: Which average to use for displaying latest station data
//and calculating trend values: HMW, MW1 or TMW
$front_page_mean = 'HMW';

//Set timespan for trend calcucaions in miliseconds, 3600000 == 1hr
$trendspan = 3600000;

/*
 * Set alert thresholds for different components from 0-3
 * Values below 0 are green
 * Values below 1 are yellow
 * Values below 2 are orange
 * ...
 * Nowcast is supposed to work with MW1 means!
 */
$threshold = array(

'0' => array(
'time' => 43200000, //12hr in javascript time 
'component' => 'PM25kont',
'average' => 'MW1',
'nowcast' => TRUE,
1 => 10, // annual mean
2 => 25, // 24hr mean
3 => 37.5,
4 => 50,
5 => 75,
),

'1' => array(
'time' => 43200000, //12hr in javascript time
'component' => 'PM10kont',
'average' => 'MW1',
'nowcast' => TRUE,
1 => 20, // annual mean
2 => 50, // 24hr mean
3 => 75,
4 => 100,
5 => 150,

),

'2' => array(
'time' => 28800000, //8hr in javascript time 
'component' => 'O3',
'average' => 'MW1',
'nowcast' => TRUE,
1 => 70,
2 => 100,
3 => 160,
4 => 200,
5 => 240,
),

'3' => array(
'time' => 43200000, //12hr in javascript time
'component' => 'SO2',
'average' => 'MW1',
'nowcast' => TRUE,
1 => NULL,
2 => 20,
3 => NULL,
4 => NULL,
5 => NULL,
),

'4' => array(
'time' => 1800000, //30min in javascript time
'component' => 'SO2',
'average' => 'HMW',
'nowcast' => FALSE,
1 => NULL,
2 => NULL,
3 => 500,
4 => NULL,
5 => NULL,
),

'5' => array(
'time' => 43200000, //12hr in javascript time
'component' => 'NO2',
'average' => 'MW1',
'nowcast' => TRUE,
1 => 40, //annual mean
2 => NULL,
3 => NULL,
4 => NULL,
5 => NULL,
),

'6' => array(
'time' => 3600000, //1hr in javascript time 
'component' => 'NO2',
'average' => 'MW1',
'nowcast' => FALSE,
1 => NULL, //annual mean
2 => 200,
3 => NULL,
4 => NULL,
5 => NULL,
),

);

//Alert index computations, EPA AQI Levels
// https://airnow.gov/index.cfm?action=aqibasics.aqi
$index_array = array(
                0 => 0,
                1 => 51,
                2 => 101,
                3 => 151,
                4 => 201,
                5 => 301,
);


// Alert box, CSS settings for front-end
$overall_alert_box = array(
            0 => array( 'color' => 'alertcolor_0',
                        'bordercolor' => 'bordercolor_0',
                        'palecolor' => 'palecolor_0',
                        'text' =>  'Gut',
                        'images' => 'images_0',
            ),
            1 => array( 'color' => 'alertcolor_1',
                        'bordercolor' => 'bordercolor_1',
                        'palecolor' => 'palecolor_1',
                        'text' =>  'Moderat',
                        'images' => 'images_1',
            ),
            2 => array( 'color' => 'alertcolor_2',
                        'bordercolor' => 'bordercolor_2',
                        'palecolor' => 'palecolor_2',
                        'text' =>  'Ungesund für empfindliche Personengruppen',
                        'images' => 'images_2',
            ),
            3 => array( 'color' => 'alertcolor_3',
                        'bordercolor' => 'bordercolor_3',
                        'palecolor' => 'palecolor_3',
                        'text' =>  'Ungesund',
                        'images' => 'images_3',
            ),
            4 => array( 'color' => 'alertcolor_4',
                        'bordercolor' => 'bordercolor_4',
                        'palecolor' => 'palecolor_4',
                        'text' =>  'Sehr ungesund',
                        'images' => 'images_4',
            ),
            5 => array( 'color' => 'alertcolor_5',
                        'bordercolor' => 'bordercolor_5',
                        'palecolor' => 'palecolor_5',
                        'text' =>  'Gefährlich',
                        'images' => 'images_5',
            ), 
);

//Set overall information message
$overall_alert_message = array(
    0 => 'Die Qualität der Luft gilt als zufriedenstellend und die Luftverschmutzung stellt ein geringes oder kein Risiko dar.',
    1 => 'Die Luftqualität ist insgesamt akzeptabel. Bei manchen Schadstoffe besteht jedoch eventuell eine geringe Gesundheitsgefahr für einen kleinen Personenkreis, der sehr empfindlich auf Luftverschmutzung reagiert.<br><br>
          Besonders sensible Personen können überlegen, große oder längerdauernde Anstrengungen im Freien zu reduzieren.',
    2 => 'Bei Mitgliedern von empfindlichen Personengruppen und Kindern können gesundheitliche Auswirkungen auftreten. Die allgemeine Öffentlichkeit ist wahrscheinlich nicht betroffen.<br><br>
          Personen mit Lungenkrankheiten wie Asthma, Kinder, ältere Personen sowie Personen, die im Freien arbeiten, sollten große oder längerdauernde Anstrengungen im Freien reduzieren.',
    3 => 'Erste gesundheitliche Auswirkungen können sich bei allen Personen einstellen. Bei empfindlichen Personengruppen können ernstere gesundheitliche Auswirkungen auftreten.<br><br>
          Personen mit Lungenkrankheiten wie Asthma, Kinder, ältere Personen sowie Personen, die im Freien arbeiten, sollten große oder längerdauernde Anstrengungen im Freien vermeiden. 
          Alle anderen sollten längerdauernde Anstrengungen im Freien begrenzen.',
    4 => 'Gesundheitswarnung aufgrund einer Notfallsituation. Die gesamte Bevölkerung ist voraussichtlich betroffen.<br><br>
          Personen mit Lungenkrankheiten wie Asthma, Kinder, ältere Personen sowie Personen, die im Freien arbeiten, sollten jegliche Anstrengungen im Freien vermeiden. 
          Alle anderen sollten jegliche Anstrengungen im Freien begrenzen.',
    5 => 'Gesundheitsalarm: Jeder muss mit dem Auftreten ernsterer Gesundheitsschäden rechnen. <br><br>Bitte begeben Sie sich ins Innere, schließen Sie Fenster und Türen, und verfolgen Sie laufend die weitere Situation.',    
          );

//Individual alert messages in case of high pollution.
$individual_messages = array(
    'message' => array(
        2 => '<h3>Hinweis</h3> <strong>Erhöhte Schadstoffbelastung: Aktuell werden erhöhte Konzentrationen von ',
        3 => '<h3>Warnung</h3> <strong>Hohe Schadstoffbelastung: Aktuell werden hohe Konzentrationen von ', 
        'end' => " gemessen.</strong>",
        ),
    'style' => array(
        'start' => '<div class= "w3-container ',
        'end' => ' container-bottom-margin container-right-margin w3-round-large"><p>',
        ),
    'component' => array(
        'PM25kont' => 'tief in die Lunge eindringendem Feinstaub (PM <sub>2,5</sub>)',
        'PM10kont' => 'Feinstaub (PM <sub>10</sub>)',
        'SO2' => 'Schwefeldioxid (SO<sub>2</sub>)',
        'NO2' => 'Stickstoffdioxid (NO<sub>2</sub>)',
        'O3' => 'Ozon (O<sub>3</sub>) im Grossraum Linz'
        ),
);

//Define icons for trendvar
$icon = array(
    '++' => $base_url.'icons/arrow/up.png',
    '==' => $base_url.'icons/arrow/constant.png',
    '--' => $base_url.'icons/arrow/down.png',
);



?>