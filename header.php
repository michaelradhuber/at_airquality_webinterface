<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="de-AT">


<title>L&Uuml;S - Luftg&uuml;te &Uuml;berwachung Steyregg</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo $base_url; ?>css/w3.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>css/custom.css">
<style>
.error {color: #FF0000;}
body {background-color:lightgrey;}
</style>
<meta name="author" content="LÜS by DRIA Profiling, www.dria-profiling.com">
<meta name="description" content="LÜS ist das erste österreichische Warnsystem für Luftverschmutzung - exklusiv für den Raum 
Steyregg. Mit dem LÜS sehen Sie jederzeit die aktuelle Schadstoffbelastung der Luft und erhalten Sie sofort nach Überschreitung der 
Schwellenwerte im Raum Steyregg eine Nachricht auf Ihr Handy oder Ihren PC gesendet. Und das kostenlos!">
<meta property="og:title" content="LÜS - Luftgüte Warnsystem Steyregg" />
<meta property="og:description" content="Wir sind stolz darauf den Steyreggbot, 
das erste österreichische Warnsystem für Luftverschmutzung präsentieren zu können - exklusiv für den Raum 
Steyregg. Mit dem LÜS sehen Sie jederzeit die aktuelle Schadstoffbelastung der Luft und erhalten Sie sofort nach Überschreitung der 
Schwellenwerte im Raum Steyregg eine Nachricht auf 
Ihr Handy oder Ihren PC gesendet. Und das kostenlos!" />
<meta property="og:image" content=  http://luft.steyregg.com/icons/logos/BPS_STE.png" />
<meta property="og:url" content="http://luft.steyregg.com" />
<meta property="fb:profile_id" content="1051887621541107" />
<link rel="apple-touch-icon" sizes="57x57" href="/icons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/icons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/icons/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/icons/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/icons/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/icons/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/icons/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
<link rel="manifest" href="/icons/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/icons/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-77344569-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>

<div class="w3-container w3-border w3-round w3-leftbar container-bg container-float-middle" style="max-width: 1068px; margin-top: 10px;">
<a href="http://luft.steyregg.com"><img src="icons/logos/wappen.png" alt="Logo LÜS" height="50" style="float:left; padding-top: 10px; padding-right:10px;"></a><h1 align=center>L&Uuml;S - Luftg&uuml;te &Uuml;berwachung Steyregg</h1>
<ul class="w3-navbar w3-border w3-border w3-border-black container-bottom-margin w3-round-large">
  <li><a href="index.php">Aktuelle Luftg&uuml;te</a></li>
  <li><a href="registration.php">Anmeldung Warnsystem</a></li>
  <li><a href="charts.php">Luftgüte Rückblick</a></li>
  <li><a href="method.php">Methodik</a></li>
    <li><a href="imprint.php">Impressum</a></li>
</ul>
<div class="w3-row">
    