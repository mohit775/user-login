<?php

//fetching JSON URL to get the number of last comic updated in server
$json1 = file_get_contents('https://xkcd.com/info.0.json');
$json_array1 = json_decode($json1, true);
$num_limit = $json_array1['num'];
//creating random value for generating random comic everytime
$random = rand(1, $num_limit);

//fetching random comic (JSON URL) using random value
$json = file_get_contents("https://xkcd.com/$random/info.0.json");
$json_array = json_decode($json, true);

//stoting data like image URL, image title, image description in variables
$img = $json_array['img'];
$desc = $json_array['alt'];
$title = $json_array['title'];

//printing the comic with details
echo '<h1>$title</h1>';
echo '<br /><br />';
echo '<img src="'.$img.'" height="50%" width="50%">';
echo '<br /><br /><br />';
echo '<h3>$desc</h3>';

?>