<?php
header('Cache-Control: no-cache, must-revalidate'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
require_once('/base.php');
?>

<?php include_once('/header.php'); ?>
<?php echo warning(); ?>

<div class="w3-col m7 l8">
<div class="w3-container container-bottom-margin container-right-margin <?php echo $alert_monitor['bordercolor'];?>  w3-leftbar w3-border-<?php echo $alert_monitor['color'];?>">
<p><strong>Hinweise zur aktuellen Luftg&uuml;te:</strong>&nbsp;<?php echo $alert_message;?></p>
</div>
</div>
<div class="w3-col m5 l4">
<div class="w3-container container-round container-bottom-margin <?php echo $alert_monitor['color'];?>"> 
 <h4 align=center>Luftqualität in Steyregg</h4>
 <h2 align=center><strong><?php echo $alert_monitor['text']?></strong></h2>
 <h3 align=center>Luftgüteindex:&nbsp;<?php echo $index; ?></h3>
 <p align=center>Letzter Wert: <?php echo $last_update;?></p>
</div>
</div>
<div class="w3-container"></div>
<div class="w3-container"><?php echo $individual_alert_message;?></div>

<div class="w3-col m6-r l3-r w3-container w3-border w3-border-black container-right-margin container-round container-bottom-margin <?php echo $PM25_color;?>">
<h3 align=center>Feinstaub <br>PM<sub>2,5</sub></h3>

<p align=center><strong>Aktueller Wert:</strong><br><small><?php echo $PM25_time;?></small></p><h3 align=center><?php echo $PM25; echo '&nbsp'; echo $PM25_unit;?></h3>
<p align=center><strong>1h-Trend:</strong><br><img class="<?php echo $PM25_image;?>" src="<?php echo $PM25_icon;?>" alt="Trend" height="40"></p>

</div>


<div class="w3-col m6-r l3-r w3-container w3-border w3-border-black container-right-margin container-round container-bottom-margin <?php echo $PM10_color;?>">
<h3 align=center>Feinstaub <br>PM<sub>10</sub></h3>

<p align=center><strong>Aktueller Wert:</strong><br><small><?php echo $PM10_time;?></small></p><h3 align=center><?php echo $PM10; echo '&nbsp'; echo $PM10_unit;?></h3>
<p align=center><strong>1h-Trend:</strong><br><img class="<?php echo $PM10_image;?>" src="<?php echo $PM10_icon;?>" alt="Trend" height="40"></p>

</div>


<div class="w3-col m6-r l3-r w3-container w3-border w3-border-black container-right-margin container-round container-bottom-margin <?php echo $SO2_color;?>">
<h3 align=center>Schwefeldioxid <br>SO<sub>2</sub></h3>

<p align=center><strong>Aktueller Wert:</strong><br><small><?php echo $SO2_time;?></small></p><h3 align=center><?php echo $SO2; echo '&nbsp'; echo $SO2_unit;?></h3>
<p align=center><strong>1h-Trend:</strong><br><img class="<?php echo $SO2_image;?>" src="<?php echo $SO2_icon;?>" alt="Trend" height="40"></p>

</div>

<div class="w3-col m6-r l3-r w3-container w3-border w3-border-black container-right-margin container-round container-bottom-margin <?php echo $NO2_color;?>">
<h3 align=center>Stickstoffdioxid <br>NO<sub>2</sub></h3>

<p align=center><strong>Aktueller Wert:</strong><br><small><?php echo $NO2_time;?></small></p><h3 align=center><?php echo $NO2; echo '&nbsp'; echo $NO2_unit;?></h3>
<p align=center><strong>1h-Trend:</strong><br><img class="<?php echo $NO2_image;?>" src="<?php echo $NO2_icon;?>" alt="Trend" height="40"></p>

</div>

<div class="w3-col m6-r l3-r w3-container w3-border w3-border-black container-right-margin container-round container-bottom-margin <?php echo $O3_color;?>">
<h3 align=center>Ozon <br>O<sub>3</sub></h3>

<p align=center><strong>Aktueller Wert:</strong><br><small><?php echo $O3_time;?></small></p><h3 align=center><?php echo $O3; echo '&nbsp'; echo $O3_unit;?></h3>
<p align=center><strong>1h-Trend:</strong><br><img class="<?php echo $O3_image;?>" src="<?php echo $O3_icon;?>" alt="Trend" height="40"></p>

</div>


<?php include_once('/footer.php'); ?>
    

