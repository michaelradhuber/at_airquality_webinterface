<?php


require_once(dirname(__FILE__) . '/telegram/telegram.functions.php');



if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => (isset($argv[1]) && $argv[1] == 'delete') ? '' : WEBHOOK_URL));
  exit;
}


$content = file_get_contents("php://input");
$update = json_decode($content, true);
//file_put_contents((dirname(__FILE__) . '/telegram/log_messages.txt'), $content, FILE_APPEND);



if (!$update) {
  // receive wrong update, must not happen
  error_log ("Update received was empty.");
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}

?>