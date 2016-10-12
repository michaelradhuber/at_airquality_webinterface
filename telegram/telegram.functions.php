<?php

require_once('telegram.messages.php');
require_once(dirname(__FILE__) . '/../config.php');

//Set error log
ini_set("log_errors", 1);
ini_set("error_log", dirname(__FILE__) . '/log.txt');

//Define token
define('BOT_TOKEN', '182158434:AAEsrrsLJa1XX6FpTviusxgoLXZb5rqbp64');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');


/*
 * Webhook part
 */
 
define('WEBHOOK_URL', 'https://luft.steyregg.com:8443/webhook.telegram.php');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log ("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log ("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    echo ("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log ("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log ("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log ("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log ("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);
  $post = array('file_contents'=>'@'.'X:\inetpub\steyreggluft\telegram\2_luft.steyregg.com.crt');
  
  $handle = curl_init($url);
  
  if ($method == 'setWebhook') {
      error_log ("Webhook mode");
      curl_setopt($handle, CURLOPT_POST,1);
      curl_setopt($handle, CURLOPT_POSTFIELDS, $post);
    }
 

  curl_setopt($handle, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
  curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  
  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log ("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log ("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
  curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE);
  curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, TRUE);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}
/*
// Helper for Uploading file using CURL
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
                . ($postname ? : basename($filename))
                . ($mimetype ? ";type=$mimetype" : '');
    }
}
 * 
 */

function sendPhoto ($parameters) {

    $parameters["method"] = 'sendPhoto';
    
    $handle = curl_init(); 
    curl_setopt($handle, CURLOPT_HTTPHEADER, array(
        "Content-Type:multipart/form-data"
    ));
    curl_setopt($handle, CURLOPT_URL, API_URL); 
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($handle, CURLOPT_POSTFIELDS, $parameters);
    curl_setopt($handle, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, TRUE);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60); 

    return exec_curl_request($handle);
}

function processMessage($message) {
  global $route;
  global $custom;
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['from']['first_name'])){
    $first_name = $message['from']['first_name'];
  } else {
    $first_name = NULL;
  }
  if (isset($message['from']['last_name'])){
    $last_name = $message['from']['last_name'];
  } else {
      $last_name = NULL;
  }
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    
   //Initiate special function section
   if ($text == "/start") {
        // Send photo
        //$caption_7 = $custom[7]['message'];
        $markup_7 = json_encode($custom[7]['reply_markup']);
        sendPhoto(array(
                    'photo' => "@".'X:\inetpub\steyreggluft\icons\logos\BPS_STE.png',
                    'chat_id' => $chat_id,
                    'caption' => $custom[7]['message'],
                    'reply_markup' => $markup_7
                    )
                    );

   } elseif ($text == "/luftan") {
           //Ask for sensitive group status    
           apiRequestJson("sendMessage", array(
                            'chat_id' => $chat_id, 
                            "text" => $custom[5]['message'], 
                            'reply_markup' => $custom[5]['reply_markup'],
                            )
                            );   
   } elseif ($text == "/Sensibel" || $text == "/Unsensibel")   {    
      luftanmeldung($chat_id, $first_name, $last_name, $text);
   } elseif ($text == "/luftab") {
      luftabmeldung($chat_id, $first_name, $last_name);
   
   // Else initiate message find and match procedure - simply return messages without special functions
   } else {
        //Execute message only function    
        messageonly($text, $chat_id);

        } //Closing IF loop               
    } //closing if isset
} //closing function

function messageonly ($text, $chat_id) {
    global $route;
    global $custom;
    
    foreach ($route as $key=>$val){
            
        //Find and match reply
        if ($text == $val['text'] && $val['request'] == 'json') {
                  apiRequestJson("sendMessage", array(
                                                    'chat_id' => $chat_id, 
                                                    "text" => $val['message'], 
                                                    'reply_markup' => $val['reply_markup'],
                                                    )
                                );
                  $found = 1;
                  break;
            
        } elseif ($text == $val['text'] && $val['request'] == 'message') {
                  apiRequest("sendMessage", array(
                                                'chat_id' => $chat_id, 
                                                "text" => $val['message']
                                                )
                            );
                  $found = 1;
                  break;
        } elseif ($text == $val['text'] && $val['request'] == 'webhook') {
                  apiRequestWebhook("sendMessage", array(
                                                    'chat_id' => $chat_id, 
                                                    "reply_to_message_id" => $message_id, 
                                                    "text" => $val['message'],
                                                    )
                                  );
                  $found = 1;
                  break;

        //If nothing is found return flag 0
        } else {
                  //Do nothing and go on to next sub-array for checking   
               }
        } //closing foreach loop
        
        //Catch all if command was not found
        if (!isset($found)) {
                              apiRequestJson("sendMessage", array(
                                                    'chat_id' => $chat_id, 
                                                    "text" => $custom[6]['message'], 
                                                    'reply_markup' => $custom[6]['reply_markup'],
                                                    ));
                 }
  
} //Closing func

function senstivestatus () {
    global $custom;
    global $route;
    
    //Ask for sensitive group status    
    apiRequestJson("sendMessage", array(
                            'chat_id' => $chat_id, 
                            "text" => $custom[5]['message'], 
                            'reply_markup' => $custom[5]['reply_markup'],
                            )
                            );
}

function luftanmeldung ($chat_id, $first_name, $last_name, $passed_text) {
                global $mysql_servername;
                global $mysql_username;
                global $mysql_password;
                global $mysql_dbname;
                global $route;
                global $custom;
                
                //Set Sensitive status
                if ($passed_text == "/Sensibel") {
                    $senspers = 1;
                } else {
                    $senspers = 0;
                }
                
                
                // Create connection
                $conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname);
                // Check connection
                if ($conn->connect_error) {
                    die(error_log("MySQL Verbindung fehlgeschlagen: " . $conn->connect_error));
                }
                //Prepare already registered chat_id
                $query = "SELECT chatid
                          FROM steyreggbot";
                $result = $conn->query($query);
                if ($mysqli->connect_error) {
                    error_log('MYSQL Connect Error: ' . $mysqli->connect_error);
                    die;
                }
                $chatarray = array();
                if ($result->num_rows > 0) {
                // output data of each row - mail
                while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                     $chatarray[] = $row['chatid'];
                     }
                }
                mysqli_free_result($result);
                //Check if chat_id already registered

                if (in_array($chat_id, $chatarray) == TRUE) {
                    //Send message and tell user that she's already registered
                    apiRequestJson("sendMessage", array(
                                                'chat_id' => $chat_id, 
                                                "text" => $custom[1]['message'],
                                                "reply_markup" => $custom[1]['reply_markup']
                                                ));
                //If not in array write chat_id to SQL
                } elseif (!in_array($chat_id, $chatarray)) {
                    $query = "INSERT INTO steyreggbot (chatid, firstname, lastname, senspers)
                              VALUES ('$chat_id', '$first_name', '$last_name', '$senspers')"; 
                    $result = $conn->query($query);
                    if ($mysqli->connect_error) {
                         error_log('MYSQL Connect Error: ' . $mysqli->connect_error);
                         die;
                    }
                 
                    if ($result === TRUE) { 
                   
                        apiRequestJson("sendMessage", array(
                                                'chat_id' => $chat_id, 
                                                "text" => $custom[0]['message'],
                                                ));
                    
                   
                    } elseif ($result === FALSE) {
                        error_log('Ein Fehler ist beim Schreiben des SQL Datensatzes im Zuge der Registrierung aufgetreten.' . $conn->error);
                        apiRequestJson("sendMessage", array(
                                                'chat_id' => $chat_id, 
                                                "text" => $custom[2]['message'],
                                                ));
                    }
                }
                $conn->close();
    
}

function luftabmeldung ($chat_id, $first_name, $last_name) {
                global $mysql_servername;
                global $mysql_username;
                global $mysql_password;
                global $mysql_dbname;
                global $route;
                global $custom;
                
                // Create connection
                $conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("MySQL Verbindung fehlgeschlagen: " . $conn->connect_error);
                }
                //Prepare already registered chat_id
                $query = "SELECT chatid
                          FROM steyreggbot";
                $result = $conn->query($query);
                if ($mysqli->connect_error) {
                    error_log('MYSQL Connect Error: ' . $mysqli->connect_error);
                    die;
                }
                $chatarray = array();
                if ($result->num_rows > 0) {
                // output data of each row - mail
                while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                     $chatarray[] = $row['chatid'];
                     }
                }
                mysqli_free_result($result);
                //Check if chat_id already registered
                if (!in_array($chat_id, $chatarray)) {
                    //Send message and tell user that she's already unsubscribed
                    apiRequestJson("sendMessage", array(
                                                'chat_id' => $chat_id, 
                                                "text" => $custom[3]['message'],
                                                "reply_markup" => $custom[3]['reply_markup']
                                                ));
                //If in array delete chat_id from SQL
                } elseif (in_array($chat_id, $chatarray)) {
                    $query = "DELETE FROM steyreggbot
                              WHERE chatid=$chat_id"; 
                    $result = $conn->query($query);
                    if ($mysqli->connect_error) {
                        error_log('MYSQL Connect Error: ' . $mysqli->connect_error);
                        die;
                    }
                    if ($result === TRUE) {
                        apiRequestJson("sendMessage", array(
                                                'chat_id' => $chat_id, 
                                                "text" => $custom[4]['message'],
                                                ));
                    } else {
                        error_log('Ein Fehler ist beim Löschen des SQL Datensatzes im Zuge der Abmeldung aufgetreten.');
                        apiRequestJson("sendMessage", array(
                                                'chat_id' => $chat_id, 
                                                "text" => $custom[2]['message'],
                                                ));
                    }
                }
                $conn->close();
    
}

?>