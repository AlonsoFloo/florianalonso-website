<?php
// Note : 
// Flalo Certified : ?bk_color=264d00&text_color=ff3399&title_color=DADADA&text=Certified&title=Flalo

// Text
$text = isset($_GET["text"]) ? $_GET["text"] : "";
$title = isset($_GET["title"]) ? $_GET["title"] : "";

// Background color
$bk_color = isset($_GET["bk_color"]) ? $_GET["bk_color"] : "000000";
$bk_color_red = strlen($bk_color) == 6 ? hexdec($bk_color[0] . '' . $bk_color[1]) : "0";
$bk_color_green = strlen($bk_color) == 6 ? hexdec($bk_color[2] . '' . $bk_color[3]) : "0";
$bk_color_blue = strlen($bk_color) == 6 ? hexdec($bk_color[4] . '' . $bk_color[5]) : "0";

// text color
$text_color = isset($_GET["text_color"]) ? $_GET["text_color"] : "000000";
$text_color_red = strlen($text_color) == 6 ? hexdec($text_color[0] . '' . $text_color[1]) : "0";
$text_color_green = strlen($text_color) == 6 ? hexdec($text_color[2] . '' . $text_color[3]) : "0";
$text_color_blue = strlen($text_color) == 6 ? hexdec($text_color[4] . '' . $text_color[5]) : "0";

// title color
$title_color = isset($_GET["title_color"]) ? $_GET["title_color"] : "000000";
$title_color_red = strlen($title_color) == 6 ? hexdec($title_color[0] . '' . $title_color[1]) : "0";
$title_color_green = strlen($title_color) == 6 ? hexdec($title_color[2] . '' . $title_color[3]) : "0";
$title_color_blue = strlen($title_color) == 6 ? hexdec($title_color[4] . '' . $title_color[5]) : "0";



// Settings
$text_font = dirname(__FILE__) . '/assets/old_stamper.ttf';
$title_font = dirname(__FILE__) . '/assets/beamweapon.ttf';
$radius = 15;



// Calculating
$margin_top = 10;
$margin_bottom = 20;
$margin_left = 50;
$margin_right = $margin_left;
$text_font_size = 30;
$text_font_box = imagettfbbox($text_font_size, 0, $text_font, $text);
$text_width = abs($text_font_box[4] - $text_font_box[0]);
$text_bug_padding = -8;
$text_height = abs($text_font_box[5] - $text_font_box[1]) + $text_bug_padding;
$width = ($margin_left + $margin_right) + $text_width;


$title_font_size = 12;
$title_font_box = imagettfbbox($title_font_size, 0, $title_font, $title);
$title_width = abs($title_font_box[4] - $title_font_box[0]);
$title_height = abs($title_font_box[5] - $title_font_box[1]);
$title_margin_left = ($width / 2) - ($title_width / 2);
$title_margin_top = $margin_top + $title_height;

$text_image_space_top = 15;
$text_margin_top = $title_margin_top + $text_image_space_top + $text_height ;
$height = $text_margin_top + $margin_bottom;


// Creating
$image_ressources = imagecreatetruecolor($width, $height);
$bk_color_ressource = imagecolorallocate($image_ressources, $bk_color_red, $bk_color_green, $bk_color_blue);
$text_color_ressource = imagecolorallocate($image_ressources, $text_color_red, $text_color_green, $text_color_blue);
$title_color_ressource = imagecolorallocate($image_ressources, $title_color_red, $title_color_green, $title_color_blue);

// apply a radius
# find unique color
do {
	$r = rand(0, 255);
	$g = rand(0, 255);
	$b = rand(0, 255);
}
while (imagecolorexact($image_ressources, $r, $g, $b) < 0);

$alphacolor = imagecolorallocatealpha($image_ressources, $r, $g, $b, 127);
imagefill($image_ressources, 0, 0, $alphacolor);
imagecolortransparent($image_ressources, $alphacolor);

// apply the background 
// imagefill($image_ressources, 0, 0, $bk_color_ressource); // without corner
// Draw the middle cross shape of the rectangle
imagefilledrectangle($image_ressources, 0, $radius, $width, $height-$radius, $bk_color_ressource);
imagefilledrectangle($image_ressources, $radius, 0, $width-$radius, $height, $bk_color_ressource);
    $dia = $radius*2;
// Now fill in the rounded corners
imagefilledellipse($image_ressources, $radius, $radius, $dia, $dia, $bk_color_ressource);
imagefilledellipse($image_ressources, $radius, $height-$radius, $dia, $dia, $bk_color_ressource);
imagefilledellipse($image_ressources, $width-$radius, $height-$radius, $dia, $dia, $bk_color_ressource);
imagefilledellipse($image_ressources, $width-$radius, $radius, $dia, $dia, $bk_color_ressource);




// Add the Title
imagettftext($image_ressources, $title_font_size, 0, $title_margin_left, $title_margin_top, $title_color_ressource, $title_font, $title);
// Add the text
imagettftext($image_ressources, $text_font_size, 0, $margin_left, $text_margin_top, $text_color_ressource, $text_font, "[" . $text . "]");

imagefilter($image_ressources, IMG_FILTER_CONTRAST, 5);




$day_to_cache = 7;
$seconds_to_cache = $day_to_cache * 86400;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: ". $ts);
header("Pragma: cache");
header("Cache-Control: max-age=".$seconds_to_cache);
header("User-Cache-Control: max-age=".$seconds_to_cache);
header("Content-Type: image/png");
imagealphablending($image_ressources, false);
imagesavealpha($image_ressources, true);
imagepng($image_ressources);
imagedestroy($image_ressources);