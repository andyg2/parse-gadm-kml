# Parse gadm.org KML to Google Maps Polygon

For this example I downloaded the Philippines KMZ level 3 file from here: https://gadm.org/download_country_v3.html

I used [7-Zip](https://www.7-zip.org/download.html) to extract the `gadm36_PHL_3.kml` file to produce `gadm36_PHL_3.kml`.

On line 3 of parse.php - The level I want to generate (based on the KML level):
  > `$level = 3;`

On line 4 of parse.php - The first part of the filename you want to parse
  > `$file_part = 'gadm36_PHL_';`

This will parse the entries of `gadm36_PHL_3.kml` into the `cords_3` directory structure in this repo.

[Coordinates to Map overlay Polygon](https://jsfiddle.net/andyg2/0t1gwnja/25/)


  
