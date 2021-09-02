<?php 
if(!is_dir('./coords')){
  mkdir('./coords');
}

$split_at_level = 'NAME_1';

$file = file_get_contents('gadm36_PHL_3.kml');
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
        $record['outline'][] = $geo;
      }
    }
    
    // we have all the data we need and the geographic region has changed so dump the data and free up RAM.
    if(isset($record['outline']) && $last_NAME_1!=$record[$split_at_level] && !empty($data)){
      $filename = $record[$split_at_level].'.json';
      file_put_contents('./coords/'.$filename, json_encode($data, JSON_NUMERIC_CHECK));
      $last_NAME_1 = $record[$split_at_level];
      $data = [];
    }
    if(!$i && isset($record[$split_at_level])){ // set $last_NAME_1 on first iteration
      $last_NAME_1 = $record[$split_at_level];
    }
  }
  $data[] = $record;
  
}

