<?php include_once('header.php');
require_once('config.php');
//Define timespan by POST method.
if(isset($_POST['timespan'])) {
    $timespan = $_POST['timespan'];
} else {
    $timespan = 7;
}
?>
<h4>Luftgüte Protokoll</h4>
<br><br>
<h5 align= center>Darzustellenden Zeitraum in Tagen auswählen: <span id="range"><?php echo $timespan;?></span></h5>
<form action="" method="post">
<input type="range" name="timespan" min="7" max="182" value="<?php echo $timespan; ?>" step="7" onchange="showValue(this.value)" style="width: 80%; margin-left: 9%; margin-right: 9%;"/>
<script type="text/javascript">
function showValue(newValue)
{
    document.getElementById("range").innerHTML=newValue;
}
</script>
<p align=center><input type="submit" name="submit" value="Diagramm neu laden"></p>
</form>
<br><br>
<?php

// Create connection
$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname);
// Check connection
if ($conn->connect_error) {
    die("MySQL Verbindung fehlgeschlagen: " . $conn->connect_error);
}
//////////////////////Read in data

if ($timespan <= 28) {
    //This query produces hourly averages    
    $query = "SELECT AVG(alertindex), warningtime, alertlevel, id
              FROM statuslog
              GROUP BY DATE(warningtime), HOUR(warningtime)
              ORDER BY id DESC;
              ";
} elseif ($timespan > 28) {
    //This query produces daily max-min values
        $query = "SELECT MAX(alertindex), MIN(alertindex), warningtime, alertlevel, id
              FROM statuslog
              GROUP BY DATE(warningtime)
              ORDER BY id DESC;
              ";
}


         $queryresult = $conn->query($query);

$dataarray = array(); 
 while ($row = $queryresult->fetch_array(MYSQLI_ASSOC)) {
     $dataarray[] = $row;
 }
 //var_dump($dataarray);
 //die;
 
 $alertdata = array();
 $commaseparated = array();
 foreach ($dataarray as $key=>$value) {
     if ((time() - strtotime($dataarray[$key]['warningtime'])) / 86400 < $timespan) {    //calculate only rows for timespan indicated!          
         $temparray[$key]['warningtime'] = strtotime($dataarray[$key]['warningtime']);
         //output time as days from now
         $temparray[$key]['warningtime'] = (time() - $temparray[$key]['warningtime']) / 86400;
         if ($timespan <= 28) {
            $temparray[$key]['alertindex'] = $dataarray[$key]['AVG(alertindex)'];
         } elseif ($timespan > 28) {
            $temparray[$key]['max_alertindex'] = $dataarray[$key]['MAX(alertindex)'];
            $temparray[$key]['min_alertindex'] = $dataarray[$key]['MIN(alertindex)']; 
         }   
         $strdata = implode(', ', $temparray[$key]);
         $alertdata[$key] = "[{$strdata}]";
     }
 }
 

 
$commaseparated = implode(", ", $alertdata);
mysqli_free_result($queryresult);

//Set axis ticks
if ($timespan <= 28) {
        $ticks = implode(", ",range(0, ($timespan-1)));
} elseif ($timespan <= 56) {
        $ticks = implode(", ",range(0, ($timespan-1), 4));
} elseif ($timespan <= 105) {
        $ticks = implode(", ",range(0, ($timespan-1), 4));
} else {
        $ticks = implode(", ",range(0, ($timespan-1), 14));
}


?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawLogScales);

function drawLogScales() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      <?php if ($timespan <=28) { ?>
            data.addColumn('number', 'Luftgüteindex (Stündlicher Mittelwert)');
      <?php } elseif ($timespan >28) { ?>
            data.addColumn('number', 'Luftgüteindex (Maximumwert des Tages)');
            data.addColumn('number', 'Luftgüteindex (Minimumwert des Tages)');
      <?php } ?>

      data.addRows([ <?php echo $commaseparated; ?>
      ]);

       var options = {
        'legend':'top',
        'lineWidth':'2',
        hAxis: {
          title: 'Tage ab jetzt',
          ticks: [<?php echo $ticks;?>],
          logScale: false
        },
        vAxis: {
          title: 'Schadstoffbelastung (Index)',
          logScale: false,
          ticks: [{v:0, f:''},{v:50, f:'Moderat'}, {v:100, f:'Erhöht'}, {v:150, f:'Ungesund'}, {v:200, f:'Sehr ungesund'}, {v:300, f:'Gefährlich'}]
        },
        colors: ['#E55934', '#0066ff']
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>
 
  <div id="chart_div" style="max-width: 100%; height: 500px;"></div>  



<?php include_once('/footer.php'); ?>
    

