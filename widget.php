<?php
header('Cache-Control: no-cache, must-revalidate'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
require_once('/base.php');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/custom.css">

<div class="w3-container">

<div class="w3-container container-round container-bottom-margin <?php echo $alert_monitor['color'];?>"> 
 <h4 align=center>Luftqualität in Steyregg</h4>
 <h2 align=center><strong><?php echo $alert_monitor['text']?></strong></h2>
 <h3 align=center>Luftgüteindex:&nbsp;<?php echo $index; ?></h3>
 <p align=center>Letzter Wert: <?php echo $last_update;?></p>
</div>
    
    
</div>