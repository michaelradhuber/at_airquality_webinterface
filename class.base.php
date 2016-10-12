<?php

//Load config vars
require_once('config.php');

/*
 * ********* Class json: will cache and read in json file from external server
 */    

 class json {
	//Define vars for constructor, concatenate only works there...	
	private $cachetime;
    public $wincache;
    public $json_latest;
    public $josn_last_update;
    public $json_trend;
    public $newcache;
    public $warning;
	
	//Constructor
	public function __construct($cachetime) {
		 global $trendspan;    
		 $this->cachetime = $cachetime;
         $this->warning == NULL;
         $this->wincache = extension_loaded('wincache'); //CACHE NOT WORKING IN APTANA!
         $this->json_latest = $this->json_latest();
         $this->json_last_update = $this->json_last_update();
         $this->json_trend = $this->json_trend($trendspan);
	}
	
	private function json_url($station){
		//Calculate timespan based on 36hrs interval for sufficient data in array:
		$interval = rawurlencode(date("Y-m-d H:i" ,(time() - 129600)));    
            
		$str1 = 'http://www2.land-oberoesterreich.gv.at/imm/jaxrs/messwerte/json';
		$str2 = '?stationcode=';
		$str3 = $station;
        $str4 = '&datvon=';
        $str5 = $interval;
		
		return "{$str1}{$str2}{$str3}{$str4}{$str5}";
	}
	
    //Path for JSON cachefile
	public function cachefile ($station){
		$str1 = __DIR__;
		$str2 = '/cache/';
		$str3 = $station;
		$str4 = '_cache.txt';
		

	return "{$str1}{$str2}{$str3}{$str4}";
	}
	
	
	//Define cache function for JSON file - lives longer:
	// Will write: json cache file and set new wincache entry
	private function json_cache ($station) {
		if (file_exists($this->cachefile($station)) && filemtime($this->cachefile($station)) > time() - $this->cachetime) {
		}
		else {
			// Load and make json cache file
			$json_raw = @file_get_contents($this->json_url($station));
			if ($json_raw === FALSE) {
				 $this->warning = 'Fehler beim Abrufen der aktuellen Daten vom Server des Landes Oberoesterreich. 
				 Der Server antwortet gerade nicht. Lade Daten aus Cache.';  
			}
			else {
				//Save as cachefile - both in wincache and normally - normal file is also fallback mechanism for server not responding
				    if ($this->wincache === TRUE) {
				    wincache_ucache_set($station, $json_raw, $this->cachetime);
                    }
				    file_put_contents($this->cachefile($station), $json_raw);
			// Set flagvar
			$this->newcache = TRUE;
            }
		}
	}	
	
	//Get values from JSON cache file and return them
	private function json_get ($station) {
		    
		//If file cached in wincache load from wincache    
		if ($this->wincache === TRUE && wincache_ucache_exists($station) === TRUE){    
		$json_raw = wincache_ucache_get($station); 
        }
        
        //Else call cache function and read in file from server or locally   
        else {
		//Read in file from URL or local cache file
		$this->json_cache($station);
		//Read in cache file and json_decode it
        $json_raw = file_get_contents($this->cachefile($station));
        }
        
        //Decode JSON array
		$json_values = json_decode($json_raw, true)['messwerte'];
		
		//Return array
		return $json_values;		  
		}

		
	//Filter array values by mean-type and component, returns filtered array
	public function filter_json ($station, $average, $component) {
    	$json_values = $this->json_get($station);
    	$filtered_array = array();     
    	for ($i = 0; $i < count($json_values); $i++){
        	if($json_values[$i]['station'] == $station  && $json_values[$i]['mittelwert'] == $average && $json_values[$i]['komponente'] == $component){
        		$filtered_array[] = $json_values[$i];
			}
		}
        
       //Strencode comma to dot and convert string to float
       foreach ($filtered_array as $key=>$value){
           $measure = $filtered_array[$key]['messwert'];
           $measure = str_replace(',', '.', $measure); // Replace comma with dot
           $measure = floatval($measure); // String to float conversion
           $filtered_array[$key]['messwert'] = $measure;
       }
       
       //Convert mg/m3 to µg/m3
       foreach ($filtered_array as $key=>$value){
           if (strpos($filtered_array[$key]['einheit'], 'mg') !== FALSE) {
               $filtered_array[$key]['messwert'] = ($filtered_array[$key]['messwert']*1000);
               $filtered_array[$key]['einheit'] = 'ug/m3';
           }
       }
       
       //Sort array, latest time values first
       foreach ($filtered_array as $key=>$value){
           $timeval[$key] = $filtered_array[$key]['zeitpunkt'];
       }
       array_multisort($timeval, SORT_DESC, $filtered_array);
       return $filtered_array;
	}
	
	
	//Returns array with "latest"  arrays from json input based on timestamp **filtered by mean-type and component**!
	// This is the only and main function that also returns valid timestamps for latest available data.
	public function json_latest () {         
                //Compute
            	    
                 //Global loop:    
                 global $SeSt;
                 global $front_page_mean; 
                
                $json_latest = array();    
                    
                foreach ($SeSt as $content_key => $content) {
                
                    $station = $SeSt[$content_key]['station'];
                    $average = $front_page_mean;
                    $component = $content_key;   
            		$filtered_array = $this->filter_json($station, $average, $component);
            		//Get timestamps from filtered array into one array
            		$time_values = array();
            		for ($i = 0; $i < count($filtered_array); $i++){	
            			$time_values[$i] = $filtered_array[$i]['zeitpunkt'];
            		}
            		//Get latest timestamp-key from array
            		$latest_key = array_keys($time_values, max($time_values))[0];
            		//define and return latest array by timestamp
            		$json_latest[$content_key] = $filtered_array[$latest_key];
                    }
       
		//Return array
		return $json_latest;
	}

public function json_last_update () {
    $timearray = $this->json_latest;
    $timeval = array();
    foreach ($timearray as $key=>$content) {
        $timeval[] = $content['zeitpunkt'];
    }
    return max($timeval);
}
    

	//Return trend variables - $timespan for trend duration in seconds!
	//Timespan starts from latest available value, not from time()!
	//Caution, it uses average values from config. So 1hr time interval seems most appropriate for comparisons.
	public function json_trend ($timespan){
         
         //Global loop:    
         global $SeSt;
         global $front_page_mean;  
        
        $trend_array = array();    
            
		foreach ($SeSt as $content_key => $content) {

            $station = $SeSt[$content_key]['station'];
            $average = $front_page_mean;
            $component = $content_key;
    		$filtered_array = $this->filter_json($station, $average, $component);
    		
    		//Get last -- highest timestamp value from filtered array into new array called time_values:
    		$time_values = array();
    		for ($i = 0; $i < count($filtered_array); $i++){	
    			$time_values[$i] = $filtered_array[$i]['zeitpunkt'];
    		}
    		//Get latest timestamp-key from array
    		$latest_key = array_keys($time_values, max($time_values))[0];
    		//define and return latest value by timestamp
    		$last_timestamp = $filtered_array[$latest_key]['zeitpunkt'];
    		
    		//Get first timestamp from reduced filtered array - create new array called trend_values with only timestamps
    		// and read out first timestamp:
    		$trend_values = array();
    		for ($i = 0; $i < count($filtered_array); $i++){
    				if ($filtered_array[$i]['zeitpunkt'] >= $last_timestamp - $timespan) {
    					$trend_values[$i] = $filtered_array[$i]['zeitpunkt'];
    				}
    		}
    		$first_key = array_keys($trend_values, min($trend_values))[0];
    		$first_timestamp = $filtered_array[$first_key]['zeitpunkt'];
    		
    		//Get values
    		$first_value = $filtered_array[$first_key]['messwert'];
    		$last_value = $filtered_array[$latest_key]['messwert'];
    		
    		//Calculate trend, 5% change on both ends is thrown as stable
    		$difference = $first_value - $last_value;
    		$margin = $first_value * 0.05;
    		if(abs($difference) > abs($margin)){
    			if ($difference > 0){
    				$trendvar = '--';
    			}
    			elseif(($difference < 0)){
    				$trendvar = '++';
    			}
    			else {
    				$trendvar = '==';
    			}
    		}
    		else{
    			$trendvar = '==';
    		}
    			
    		
    		//Return  array
    		$trend_array[$content_key] = array('first_timestamp' => $first_timestamp, 
    		                                  'last_timestamp' => $last_timestamp, 
    		                                  'first_value' => $first_value, 
    		                                  'last_value '=> $last_value, 
    		                                  'trendvar' => $trendvar,
                                              );
	       }     
    return $trend_array;
	}
    
    // Trend icon function
    public function json_icon ($component) {
        global $icon;
        if ($this->json_trend[$component]['trendvar'] == '++') {
            return $icon['++'];
        }
        elseif ($this->json_trend[$component]['trendvar'] == '==') {
            return $icon['=='];
        }
        if ($this->json_trend[$component]['trendvar'] == '--') {
            return $icon['--'];
        }
        } 
	
    //This function will convert the JSON timestamps to PHP timestamps and format them nicely, return them in an array:
    public function json_time ($json_timestamp) {
        $days = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
        $day = date("w");
        $weekday = $days[$day];
        
        $summertime = date('I');
        $php_timestamp = $json_timestamp / 1000;
        //Server time conversion from CET to CEST!
        if ($summertime == '1') {
            $php_timestamp = $php_timestamp + 3600;
        }
        $formatted_time = date("H:i", $php_timestamp);
        $formatted_date = date("d.m.Y" ,$php_timestamp);
        
        return array(
        0 => $weekday,
        1 => $formatted_time,
        2 => $formatted_date,
        );
    }
    
    
}   

class alert extends json {
        
    private $json;  //Referencing: 1 Create reference var    
    public $alert_level;
    public $overall_alert;
    public $overall_index;
    public $alert_monitor;
    public $overall_alert_message;
    public $individual_alert_message;
    public $individual_alert_color;
    
    public function __construct() {
        global $cachetime;    
        $json = new json($cachetime); // 2. Instantiate other class  
        $this->json = $json;    // 3. Assign other class to local object (variable). Call by $this->$json->object ...
        $this->alert_level = $this->alert_level();
        $this->overall_alert = $this->overall_alert();
        $this->overall_index = $this->overall_index();
        $this->alert_monitor = $this->alert_monitor();
        $this->overall_alert_message = $this->overall_alert_message();
        $this->individual_alert_message = $this->individual_alert_message();
        $this->individual_alert_color = $this->individual_alert_color();
    }
    
    //Return timespan array with values only (see below comment) for calculating excess values etc.
    //Timespan starts from latest available value, not from time()! is ordered by timestamp SORT_DESC (filter_json)
    protected function json_timespan ($station, $average, $component, $timespan){
        $filtered_array = $this->json->filter_json($station, $average, $component);
        //print_r($filtered_array);
        //Get last -- highest timestamp value from filtered array:
        $time_values = array();
        for ($i = 0; $i < count($filtered_array); $i++){    
            $time_values[$i] = $filtered_array[$i]['zeitpunkt'];
        }
        //Get latest timestamp-key from array
        $latest_key = array_keys($time_values, max($time_values))[0];
        //define and return latest value by timestamp
        $last_timestamp = $filtered_array[$latest_key]['zeitpunkt'];
        
        //Create new array according to timespan given:
        $timespan_array = array();
        for ($i = 0; $i < count($filtered_array); $i++){
                if ($filtered_array[$i]['zeitpunkt'] >= $last_timestamp - $timespan) {
                    $timespan_array[$i] = $filtered_array[$i]['messwert']; // Change array key here for other fields.
                }
        }
        return $timespan_array;
    }

    //Normal Averageing of timespan array -  function 
    protected function average_array ($station, $average, $component, $timespan) {
        $timespan_array = $this->json_timespan($station, $average, $component, $timespan);
        $average = array_sum($timespan_array) / count($timespan_array);
        return $average;
    }
    
    //NOWCAST weighting of timespan arrays
    protected function nowcast_array ($station, $average, $component, $timespan) {
        $timespan_array = $this->json_timespan($station, $average, $component, $timespan);
        //Calculate NOWCAST parameters - array is already sorted in filter_json method
        $omega_star = min($timespan_array) / max($timespan_array);
        if ($omega_star > 0.5) {
            $omega = $omega_star;
        } elseif ($omega_star <= 0.5) {
            $omega = 0.5;
        }
        //Calculate enumerator
        $enumerator = 0;
        $i=0;
        foreach ($timespan_array as $key=>$content) {
            $addfactor = $timespan_array[$key] * pow($omega, $i);
            $i = $i + 1;
            $enumerator = $enumerator + $addfactor;
        }
        //calculate denominator
        $denominator = 0;
        $i = 0;
        foreach ($timespan_array as $key=>$content) {
            $addfactor = pow($omega, $i);
            $denominator = $denominator + $addfactor;
            $i = $i + 1;
        }
        //Divide and return
        $nowcast = $enumerator / $denominator;
        return $nowcast;
    }   

//This function will calculate alert levels for every single component and return them as array:
protected function alert_level () {   
    global $SeSt;
    global $threshold;  
    global $index_array;  
    $alert_array = array();
    
    foreach ($threshold as $key=>$value) {
        //Get latest value for component
        $average = $threshold[$key]['average'];
        $component = $threshold[$key]['component'];
        $timespan = $threshold[$key]['time'];
        $station = $SeSt[$component]['station'];
        
        $latest_array = $this->json->json_latest;
        //$current_value = $latest_array[$threshold[$key][$component]]['messwert'];
        //print_r($latest_array);
        $time = $latest_array[$component]['zeitpunkt'];
        
        //compute normal average or nowcast average
        if ($threshold[$key]['nowcast'] == TRUE) {
            $alertval = $this->nowcast_array($station, $average, $component, $timespan);
        } elseif ($threshold[$key]['nowcast'] == FALSE) {
            $alertval = $this->average_array($station, $average, $component, $timespan); 
        }

       
       //Get first value in array not NULL: CHECKED AND WORKING OK
        for ($i = 1; $i <= 5; $i++) {
            if ($value[$i] != NULL) {
                $first_key = $i;
                break;
                }
        }

       
        // Loop over inner arrays - *********central part for setting alert level!**********************
        // Careful - this loop is a bit tricky, it goes from highest to lowest.
        for ($i = 5; $i > 0; $i--) {
            if ($value[$i] != NULL && $alertval >= $value[$i]) {    
                $alert_level = $i;
                break; // Don't forget to break here!
            } elseif ($i == $first_key && $alertval < $value[$i]) {
                $alert_level = 0;
            }
            }
            
        //If the loop yields no result due to NULL at the beginning    
        if (!isset($alert_level)) {
            $alert_level = NULL;
        }
        //Compute alert index according to EPA formula
        $next_alert_level = $alert_level + 1;
        // Case alert level at least one and next level set
        if ($alert_level > 0  && $value[$alert_level] != NULL && isset($value[$next_alert_level]) && $value[$next_alert_level] != NULL) {       
            $enumerator = ($index_array[$next_alert_level] - 1)  - $index_array[$alert_level];
            $denominator = ($value[$next_alert_level]) - $value[$alert_level];
            $mult_factor = $alertval - $value[$alert_level];
            $index = (($enumerator/$denominator)*$mult_factor)+ $index_array[$alert_level];
        //Case alert level 0 and next level set
        } elseif ($alert_level == 0 && $value[$next_alert_level] != NULL) {
            $enumerator = $index_array[$next_alert_level] - 1;
            $denominator = $value[$next_alert_level]; 
            $mult_factor = $alertval;
            $index = (($enumerator/$denominator)*$mult_factor);
        //Case alert level not 0, this level set, and next level not set - IMPUTE MISSING THRESHOLDS
        } elseif ($alert_level != NULL  && $value[$alert_level] != NULL && $value[$alert_level] > 0 && (!isset($value[$next_alert_level]) || $value[$next_alert_level] == NULL)) {
            $quotient = $value[$alert_level] / $index_array[$alert_level]; //How many index points for each pollution unit above thresold
            $basepoints =  $index_array[$alert_level];  //How many index points at threshold
            $index = ($alertval - $value[$alert_level])*$quotient + $basepoints;
        //Case alert level is zero and next level not set - DO NOTHING
        } elseif ($alert_level == 0 && (!isset($value[$next_alert_level]) || $value[$next_alert_level] == NULL)) {
            // We don't want to do this by definition of our threshold data as it leads to big distortions between the alert level and the index value...    
            //$index = $alertval / $value[$first_key] * $index_array[$first_key];
        } else {
            $index = NULL; //Houston, we have a problem
        }
        //Round index value to 0 comma
        if ($index != NULL) {
            $index = round($index, 0);
        }
        
      
        //Write component-specific alert_level with key to array - Key is same as in $threshold variable.
        $alert_array[$key] = array(
            'component' => $component,
            'alert' => $alert_level,
            'index' => $index,
            'time' => $time,
            );

       }    
       return $alert_array;  
}

// Compute overall alert level:
public function overall_alert () {
    global $threshold;    
    $alert_array = $this->alert_level;
    //Compute
    $reduced_alert_array = array();
    foreach ($threshold as $content_key => $content) {
        $reduced_alert_array[] =  $alert_array[$content_key]['alert']; 
        }
    $overall_alert_level = max($reduced_alert_array);
    //Return
    return $overall_alert_level;
}

// Compute overall alert index:
public function overall_index () {
    global $threshold;    
    $alert_array = $this->alert_level;
    //Compute
    $reduced_alert_array = array();
    foreach ($threshold as $content_key => $content) {
        $reduced_alert_array[] =  $alert_array[$content_key]['index']; 
        }
    $overall_alert_level = max($reduced_alert_array);
    //Return
    return $overall_alert_level;
}

public function alert_monitor () {
    global $overall_alert_box;
    $alert_monitor = $this->overall_alert; // Call object, not method, to save on CPU
    $alert_array = array();
    //Compute 
    switch ($alert_monitor) {
        case 0: 
            $alert_array = $overall_alert_box[0];
            break;
        case 1:
            $alert_array = $overall_alert_box[1];
            break;
        case 2:
            $alert_array = $overall_alert_box[2];
            break;
        case 3:
            $alert_array = $overall_alert_box[3];
            break;
        case 4:
            $alert_array = $overall_alert_box[4];
            break;
        case 5:
            $alert_array = $overall_alert_box[5];
            break;        
    }
    return $alert_array;
    

}

public function overall_alert_message () {
    $overall_alert = $this->overall_alert;

    //Compute
    global $overall_alert_message;    
    switch ($overall_alert) {
        case 0: 
            $alert_message = $overall_alert_message[0];
            break;
        case 1: 
            $alert_message = $overall_alert_message[1];
            break;
        case 2: 
            $alert_message = $overall_alert_message[2];
            break;
        case 3: 
            $alert_message = $overall_alert_message[3];
            break;
        case 4: 
            $alert_message = $overall_alert_message[4];
            break;
        case 5: 
            $alert_message = $overall_alert_message[5];
            break;
    }
    return $alert_message;
}

public function individual_alert_color () {
    $alert_level = $this->alert_level;
    global $overall_alert_box;
    
     //Sort alert array
        foreach ($alert_level as $content_key => $content) {
            $sort_row[$content_key] = $content['alert'];
            $second_row[$content_key] = $content['time'];
        }
        array_multisort($sort_row, SORT_DESC, $alert_level);
        //Drop duplicate values alert array
        $reduced_alert_level = array();
        //Outer loop
        foreach ($alert_level as $key => $value) {
                $duplicate = FALSE;    
                //Start inner loop and check for duplicates
                foreach ($reduced_alert_level as $subkey => $subvalue) {
                    if ($value['component'] == $subvalue['component']) {
                        $duplicate = TRUE;
                        break;
                    }
                } //End inner loop
            if ($duplicate == FALSE) {
                $reduced_alert_level[] = $value;
            }
        }
        
        //Assign colors
        $colorbox = array();
        foreach ($reduced_alert_level as $key => $content) {
            $component_alert = $content['alert'];
            $component = $content['component'];
            $colorbox[$component] = array (
                'bg-color' => $overall_alert_box[$component_alert]['color'],
                'images' => $overall_alert_box[$component_alert]['images'],
                );
        } 
        
        //RETURN
        return $colorbox;
}



//Output individual alert messages
public function individual_alert_message () {
        $alert_level = $this->alert_level;
        $alert_color = $this->individual_alert_color();
        global $individual_messages;
        
        //Sort alert array
        foreach ($alert_level as $content_key => $content) {
            $sort_row[$content_key] = $content['alert'];
            $second_row[$content_key] = $content['time'];
        }
        array_multisort($sort_row, SORT_DESC, $alert_level);
        
        //Drop duplicate values alert array
        $reduced_alert_level = array();
        //Outer loop
        foreach ($alert_level as $key => $value) {
                $duplicate = FALSE;    
                //Start inner loop and check for duplicates
                foreach ($reduced_alert_level as $subkey => $subvalue) {
                    if ($value['component'] == $subvalue['component']) {
                        $duplicate = TRUE;
                        break;
                    }
                } //End inner loop
            if ($duplicate == FALSE) {
                $reduced_alert_level[] = $value;
            }
        }
        //Loop over reduced alert array and produce alert messages in HTML
        $alert_array = array();
        foreach ($reduced_alert_level as $content_key => $content) {
            if ($content['alert'] == 2) {  // Don't forget to reset back
                $component_code = $content['component'];
                $component = $individual_messages['component'][$component_code];
                $color = $alert_color[$content['component']]['bg-color'];
                $div = '</p></div>';
                $alert_array[$content_key] = "{$individual_messages['style']['start']}{$color}
                {$individual_messages['style']['end']}{$individual_messages['message']['2']}{$component}
                {$individual_messages['message']['end']}{$div}";
            }
            elseif ($content['alert'] >= 3) {
                $component_code = $content['component'];
                $component = $individual_messages['component'][$component_code];
                $color = $alert_color[$content['component']]['bg-color'];
                $div = '</p></div>';
                $alert_array[$content_key] = "{$individual_messages['style']['start']}{$color}
                {$individual_messages['style']['end']}{$individual_messages['message']['3']}{$component}
                {$individual_messages['message']['end']}{$div}";
            }
        
        }
        //return complete alert array as HTML
        $individual_alert_message = '';
        foreach ($alert_array as $content_key => $content) {
        $individual_alert_message = "{$individual_alert_message}{$content}"; //append new message to existing messages
        }
        return $individual_alert_message;
    }

    
}
    
?>