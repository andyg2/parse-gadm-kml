<?php 

$level = 1; // just set this
$file_part = 'gadm36_PHL_';  // and this

// these should match up to the GDAM files from https://gadm.org/download_country_v3.html
// e.g. gadm36_PHL_1.kml (extracted via 7-zip from gadm36_PHL_1.kmz)


// end config




$root_directory_name = 'cords_'.$level;

$split_at_level = 'NAME_'.$level;

$file = file_get_contents($file_part.$level.'.kml');

// get rid of occational asterisks in the location names
$file = str_replace('*','',$file);

$parts = explode('</Placemark>', $file);
$data=[];
$last_NAME_1 = false;

foreach($parts as $i => $part){
  $lines = explode("\n", $part);
  $record=[];
  foreach($lines as $line){
    $line = trim($line);
    if(strpos($line,'SimpleData')!==false){
      $key = substr($line,18,6);
      $val = strip_tags($line);
      $record[$key] = $val;
    }
    
    if(strpos($line,'MultiGeometry')!==false){
      $line = strip_tags($line);
      $coords = explode(' ',$line);
      foreach($coords as $coord){
        $geo = explode(',',$coord);
        $record['outline'][] = ['lat'=>floatval($geo[1]),'lng'=>floatval($geo[0])];
      }
    }
    
    // we have all the data we need and the geographic region has changed so dump the data to a file.
    // 
    if(isset($record['outline']) && $last_NAME_1!=$record[$split_at_level] && !empty($data)){
      $filename = false;
      $output = [];
      
      // figire out what type of file we have (with NAME_3, NAME_2 OR NAME_1 entries)
      
      if($last_NAME_3){
        
        foreach($data as $place){
          $output[$last_NAME_3] = $place['outline'];
        }


        // directory structure
        $filename = $last_NAME_1.'/'.$last_NAME_2.'/'.$last_NAME_3.'.json';
        
      }elseif($last_NAME_2){

        foreach($data as $place){
          $output[$last_NAME_2] = $place['outline'];
        }
        
        
        // directory structure
        $filename = $last_NAME_1.'/'.$last_NAME_2.'.json';
        
      }elseif($last_NAME_1){
        
        foreach($data as $place){
          $output = $place['outline'];
        }

        $filename = $last_NAME_1.'.json';
      }
      
      
      // mroe directory structure
      
      $path = './'.$root_directory_name.'/'.$filename;
      

      // create if not exists
      $new_dir = dirname($path);
      if(!is_dir($new_dir)){
        mkdir($new_dir, 0755, true);
      }
      
      // write output file
      file_put_contents($path, json_encode($output, JSON_NUMERIC_CHECK));


      // set the last_values
      $last_NAME_1 = isset($record['NAME_1']) ? trim($record['NAME_1'],'.') : false;
      $last_NAME_2 = isset($record['NAME_2']) ? trim($record['NAME_2'],'.') : false;
      $last_NAME_3 = isset($record['NAME_3']) ? trim($record['NAME_3'],'.') : false;
      
      // empty data to free up ram
      $data = [];
    }
    
    // first loop, set the last_values and nothing more
    if(!$i && isset($record['NAME_1'])){ // set $last_NAME_1,2,3 on first iteration
      $last_NAME_1 = isset($record['NAME_1']) ? trim($record['NAME_1'],'.') : false;
      $last_NAME_2 = isset($record['NAME_2']) ? trim($record['NAME_2'],'.') : false;
      $last_NAME_3 = isset($record['NAME_3']) ? trim($record['NAME_3'],'.') : false;
    }
  }
  $data[] = $record;
  
}

