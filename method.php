<?php include_once('/header.php'); ?>
<meta charset="UTF-8">
<h4>Methodik</h4>
<p>Das LÜS wertet die Daten der Luftgütemessstation des Landes Oberösterreich Steyregg-Au in Echtzeit aus. Einzig der Ozonwert (O<sup>3</sup>) 
    wird von der Luftgütemessstation Linz Stadtpark eingespielt, da der Ozonwert in der Station Steyregg-Au nicht erhoben wird. Die Daten 
    werden über die Datenschnittstelle das Landes Oberösterreich (data.ooe.gv.at) in unseren Server eingespielt. Es handelt sich hierbei um ungefilterte Rohdaten
    des Datenservers des Landes Oberöstrerreich, weshalb für die publizierten und berechneten Werte auf dieser Seite keine Gewähr übernommen wird. </p>
<p>Die Auswertung der Daten orientiert sich an den Vorgaben der Weltgesundheitsorganisation (WHO) und der US Umweltbehörde EPA. Die WHO hat zuletzt im Jahr 2005 ein 
    <a href="http://www.who.int/phe/health_topics/outdoorair/outdoorair_aqg/en/" target="blank">Update
        zu den Luftgüte-Richtlinien</a> herausgegeben, in dem auf Grundlage aktueller wissenschaftlicher Erkenntnisse Empfehlungen für Grenzwerte verschiedener
        Komponenten postuliert werden. Alle in dieser Publikation enthaltenen Komponenten werden mittels des LÜS erhoben und ausgewertet.
        Die Empfehlungen der WHO beruhen auf aktuellen wissenschaftlichen Studien und Erkenntnissen, und setzen diese empirischen Erkenntnisse rigoros um.</p>
<p>Die Warnstufen des LÜS widerspiegeln die empfohlenen Richtwerte der WHO. Die Daten werden nach dem <a href="https://en.wikipedia.org/wiki/Nowcast_%28Air_Quality_Index%29">
    Nowcast-System der US-EPA</a> gewichtet (Details <a href="https://www3.epa.gov/airnow/ani/pm25_aqi_reporting_nowcast_overview.pdf">hier</a>). 
    Mit dieser Gewichtung werden die zuletzt gemessenen Luftgüte-Werte aufgewertet, wodurch ein aktuelles Bild der Luftqualität gezeichnet wird. Das gibt uns die
    Möglichkeit, rechtzeitig bei Überschreitungen zu warnen. Weitere Informationen über die Berechnung des Luftgüteindex finden Sie <a href="https://en.wikipedia.org/wiki/Air_quality_index#United_States">hier</a>. Die für das LÜS angewandten Schwellenwerte 
    entnehmen Sie der unten angefügten Tabelle.
</p>
<p>Der Luftgüteindex wird als Funktion auf Grundlage der Schwellenwerte und der aktuellen Schadstoffbelastung berechnet. Da der Index jedoch in Teilbereichen 
    mit einer näherungsweisen Funktion berechnet wird, kann es in Einzelfällen dazu kommen,
    daß der Luftgüteindex geringfügig von der Warnstufe abweicht. </p>
    <table>
        <thead>
  <tr>
    <th><strong>Schadstoff</strong></th>
    <th><strong>Warnstufe 1</strong></th>
    <th><strong>Warnstufe 2</strong></th>
    <th><strong>Warnstufe 3</strong></th>
    <th><strong>Warnstufe 4</strong></th>
    <th><strong>Warnstufe 5</strong></th>
  </tr>
  </thead>
     <tr>
    <td>Luftgüteindex</td>
    <td>51</td>
    <td>101</td>
    <td>151</td>
    <td>201</td>
    <td>301</td>
  </tr>
  <tr>
    <td>PM<sub>2,5</sub> (12h)</td>
    <td>10µg/m<sup>3</sup></td>
    <td>25µg/m<sup>3</sup></td>
    <td>37,5µg/m<sup>3</sup></td>
    <td>50µg/m<sup>3</sup></td>
    <td>75µg/m<sup>3</sup></td>
  </tr>
  <tr>
    <td>PM<sub>10</sub> (12h)</td>
    <td>20µg/m<sup>3</sup></td>
    <td>50µg/m<sup>3</sup></td>
    <td>75µg/m<sup>3</sup></td>
    <td>100µg/m<sup>3</sup></td>
    <td>150µg/m<sup>3</sup></td>
  </tr>
    <tr>
    <td>O<sup>3</sup> (8h)</td>
    <td>70µg/m<sup>3</sup></td>
    <td>100µg/m<sup>3</sup></td>
    <td>160µg/m<sup>3</sup></td>
    <td>200µg/m<sup>3</sup></td>
    <td>240µg/m<sup>3</sup></td>
  </tr>
  <tr>
    <td>SO<sup>2</sup> (12h)</td>
    <td>&nbsp;</td>
    <td>20µg/m<sup>3</sup></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>SO<sup>2</sup> (30min)</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>500µg/m<sup>3</sup></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>NO<sup>2</sup> (12h)</td>
    <td>40µg/m<sup>3</sup></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      <tr>
    <td>NO<sup>2</sup> (1h)</td>
    <td>&nbsp;</td>
    <td>200µg/m<sup>3</sup></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>
<small>Tabelle: Warnstufen des LÜS nach Schadstoffkonzentration. Angegebene Werte sind jeweils untere Schwellenwerte.</small>

<p>Das LÜS steht im Eigentum der DRIA Profiling, die sich alle Rechte daran vorbehält. Es wurde so entwickelt, daß es sich jederzeit auch für andere Orte/Stationen anwenden lässt. Wenn Sie Interesse
   am Erwerb einer Lizenz des LÜS auch für andere Gemeinden haben, <a href="http://www.dria-profiling.com/kontakt.html" target="blank">kontaktieren Sie uns.</a></p>
  

<?php include_once('footer.php'); ?>