<?php
require_once('../config.php');
require_once(dirname(__FILE__) . '/../telegram/telegram.functions.php');
require_once('../class.base.php');
set_time_limit(10000);
$wincache = extension_loaded('wincache');
$alertmessage = NULL;


// Create connection
$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname);
// Check connection
if ($conn->connect_error) {
    die("MySQL Verbindung fehlgeschlagen: " . $conn->connect_error);
}

 
// Initialize JSON class - don't forget to pass global var $cachetime=0
$json = new json(0);
$alert = new alert(0);


//////////////////////Check for last warning

$query = "SELECT * 
          FROM statuslog 
          ORDER BY id DESC
          LIMIT 10;
         ";
         
$warningquery = $conn->query($query);

$warningarray = array();
if ($warningquery->num_rows > 0) {
      //Get array values from MySQL
      while ($row = $warningquery->fetch_array(MYSQLI_ASSOC)) {
          $warningarray[] = $row;
      }


      //Get previous alertlevel
       $previous_alert_level = $warningarray[0]['alertlevel'];

      
       mysqli_free_result($warningquery);
}

////////////////Get all users

$query = "SELECT * 
          FROM steyreggbot
         ";
         
$userquery = $conn->query($query);

$userarray = array();
if ($userquery->num_rows > 0) {
      //Get array values from MySQL
      while ($row = $userquery->fetch_array(MYSQLI_ASSOC)) {
          $userarray[] = $row;
      }
      
mysqli_free_result($userquery);
}


  

//Initiate warning procedure
$alertlevel = $alert->overall_alert;

if ($alertlevel >= 2 && $previous_alert_level < $alertlevel) {
    //Set alertmessage (mysql entry)
    $alertmessage = 1;
    //Select wording    
    if ($alertlevel == 2) {
        $word = "Hinweis";
    }
    elseif ($alertlevel == 3) {
        $word = "**Warnung**";
    }
    elseif ($alertlevel >= 4) {
        $word = "***Alarmmeldung***";
    }


    foreach ($userarray as $key => $value) {
        if ($alertlevel == 2 && $value['senspers'] == 0) {
            // Do nothing for people who opted out from level 2
        } else {
            //Send message
        $chat_id = $value['chatid'];
        $senspers = $value['senspers'];
        $last_update = "{$json->json_time($json->json_last_update)[0]}, {$json->json_time($json->json_last_update)[1]}";
   
        $newalertmessage1 = "<b>LÜS - Luftgüte {$word} für den Raum Steyregg von {$last_update}:
Die folgende Warnstufe wurde erreicht: {$overall_alert_box[$alertlevel]['text']}</b>

Mehr Informationen zur aktuellen Schadstoffbelastung finden Sie auf der Homepage des LÜS: {$base_url}

Sollte sich die Luftqualität weiter verschlechtern erhalten Sie erneut eine Warnung.

Freundliche Grüsse von Ihrem LÜS Team. LÜS - ein Projekt der <i>Bürgerplattform Steyregg.</i>";
        
        //Send message to all users
        apiRequestJson("sendMessage", array(
                                           'chat_id' => $chat_id, 
                                           "text" => $newalertmessage1, 
                                           'parse_mode' => 'HTML',
                                           'reply_markup' => array(
                                                            'keyboard' => array(array('/help')),
                                                            'one_time_keyboard' => TRUE,
                                                            'resize_keyboard' => TRUE,
                                                            ),
                                           )
                                );

       } //Close inner IF
       //Set max execution time up
       set_time_limit(1);
       (time_nanosleep(0, 100000000));
    } //Close foreach 
   
} //Close IF

//Write index level to statuslog table (only if Land OÖ server responds to request)
$currenttime = ($json->json_last_update / 1000);
$currenttime = date('Y-m-d H:i:s', $currenttime);

if ($json->warning == NULL) {
 $query = "INSERT INTO statuslog (alertlevel, alertindex, warningtime, alertmessage)
              VALUES ('{$alert->overall_alert}','{$alert->overall_index}', '{$currenttime}', '{$alertmessage}')
             ";
    
    if ($conn->query($query) === TRUE) {
        echo "New status record created successfully";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
} else {
     sleep(30);
     $query = "INSERT INTO statuslog (alertlevel, alertindex, warningtime, alertmessage)
              VALUES ('{$alert->overall_alert}','{$alert->overall_index}', '{$currenttime}', '{$alertmessage}')
             ";
    
    if ($conn->query($query) === TRUE) {
        echo "New status record created successfully";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
        die; //If the server still doesn't respond die and restart with the next cron.
    }
}

//Close MYSQL CONN
$conn->close();


?>