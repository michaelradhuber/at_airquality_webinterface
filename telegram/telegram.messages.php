<?php
//Possible request values: json, message, webhook

$route = array(
    //Help command
    0 => array (
        'request' => 'json',
        'text' => '/help',
        'message' => 'Der Steyreggbot unterstützt im Moment folgende Kommandos:
/start - Sitzung beginnen     
/luftan - Anmeldung zum Luftgütewarnsystem
/luftab - Abmeldung vom Luftgütewarnsystem
/help - Hilfeseite anzeigen',
        'reply_markup' => array(
                            'keyboard' => array(array('/start', '/luftan', '/luftab', '/help')),
                            'one_time_keyboard' => TRUE,
                            'resize_keyboard' => TRUE,
                            ),
    )   

);

$custom = array(

    // ANMELDEN - Confirmation
    0 => array (
        'request' => 'message',
        'message' => 'So einfach wars - wir haben Ihre Anmeldung erfolgreich verarbeitet. Sie erhalten von uns nun automatisierte Luftgütewarnungen im Fall von Überschreitungen der Schwellenwerte. http://luft.steyregg.com',
    ),
    
    // ANMELDEN - Confirmation Issues
    1 => array (
        'request' => 'json',
        'message' => 'Ihr Gerät wurde bereits registriert. Sollten Sie Probleme beim Empfang der Meldungen haben, kontaktieren Sie uns bitte unter nachrichten@steyregg.com',
        'reply_markup' => array(
                            'keyboard' => array(array('/help')),
                            'one_time_keyboard' => TRUE,
                            'resize_keyboard' => TRUE,
                            ),
    ),
    
        // ANMELDEN - Confirmation Issues
    2 => array (
        'request' => 'message',
        'message' => 'Beim Bearbeiten der Daten ist ein Fehler aufgetreten. Bitte kontaktieren Sie uns unter nachrichten@steyregg.com',
    ),
    
    // ABMELDEN - Confirmation Issues
    3 => array (
        'request' => 'json',
        'message' => 'Ihr Gerät wurde bereits abgemeldet. Im Fall von Problemen kontaktieren Sie uns bitte unter nachrichten@steyregg.com',
        'reply_markup' => array(
                            'keyboard' => array(array('/help')),
                            'one_time_keyboard' => TRUE,
                            'resize_keyboard' => TRUE,
                            ),
    ),
    
    // ABMELDEN - Confirmation
    4 => array (
        'request' => 'message',
        'message' => 'Ihr Gerät wurde erfolgreich vom Luftgütewarnsystem abgemeldet.',
    ),
    
    // Member of sensitive group?
    5 => array (
        'request' => 'json',
        'message' => 'Möchten Sie auch Hinweismeldungen für Mitglieder sensibler Personengruppen (Asthmatiker, Kinder) erhalten? Falls Ja, erhalten Sie in Summe mehr Meldungen von unserem System, da die Schwellenwerte für sensible Meldungen niedriger liegen.

Ja: Klicken Sie auf /Sensibel
Nein: Klicken Sie auf /Unsensibel
',
        'reply_markup' => array(
                            'keyboard' => array(array('/Sensibel', '/Unsensibel')),
                            'one_time_keyboard' => TRUE,
                            'resize_keyboard' => TRUE,
                            ),
    ),
    
    //HELP
    6 => array (
        'request' => 'json',
        'message' => 'Das von Ihnen eingegebene Kommando ist mir nicht bekannt. Bitte überprüfen Sie die Schreibweise und versuchen Sie es noch einmal. Um Hilfe zu erhalten, schreiben Sie /help',
        'reply_markup' => array(
                            'keyboard' => array(array('/help')),
                            'one_time_keyboard' => TRUE,
                            'resize_keyboard' => TRUE,
                            ),
    ),
    
        ///START
    7 => array (
        'request' => 'json',
        'text' => '/start',
        'message' => 'Willkommen zum Steyreggbot, dem Luft-Warnsystem Steyreggs. Um sich für das Luft-Warnsystem anzumelden klicken Sie bitte einfach auf /luftan',
        'reply_markup' => array(
                            'keyboard' => array(array('/luftan', '/help')),
                            'one_time_keyboard' => TRUE,
                            'resize_keyboard' => TRUE,
                            ),
    ),
    


);

?>