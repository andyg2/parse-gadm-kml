# Parse gadm.org KML Parse to Google Maps Polygon

For this example I downloaded the Philippines KMZ level 3 file from here: https://gadm.org/download_country_v3.html

I used [7-Zip](https://www.7-zip.org/download.html) to extract the `gadm36_PHL_3.kml` file to produce `gadm36_PHL_3.kml`.

On line 8 of parse.php I entered the filename:
  > `$file = file_get_contents('gadm36_PHL_3.kml');`

On line 6 of parse.php I instructed the wscript to split the output each time the NAME_1 (municipality) changes:
  > `$split_at_level = 'NAME_1';`

[Coordinates to Map overlay Polygon](https://jsfiddle.net/andyg2/0t1gwnja/22/)


  
