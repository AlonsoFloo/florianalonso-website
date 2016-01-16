<?php
// Note : 
// Flalo Certified : ?color=AAAAAA&text_bottom=APPROUVED&text_top=Flalo
function imagettftextarc($image, $size, $angle, $x, $y, $r, $color, $fontfile, $text, $dir=false){
    $sbox=imagettfbbox($size, 0, $fontfile, ' ');
    $sbox=($sbox[2]-$sbox[0])*0.3;
    $angle=$angle*M_PI/180;
    foreach(preg_split('//u', $text) AS $t){
        $px=$x+$r*cos($angle);
        $py=$y+$r*sin($angle);
        $dirangle=(360-(M_PI/2+$angle)*180/M_PI+($dir?180:0))%360;
        imagettftext($image, $size, $dirangle, $px, $py, $color, $fontfile, $t);
        $box=imagettfbbox($size, 0, $fontfile, $t);
        $dx=$box[2]-$box[0];
        $da=abs(asin(($dx+$sbox)/$r));
        if($dir){
            $angle-=$da;
        }else{
            $angle+=$da;
        }
    }
}
function imagettftextarc_lastAngle($image, $size, $angle, $x, $y, $r, $color, $fontfile, $text, $dir=false){
    $sbox=imagettfbbox($size, 0, $fontfile, ' ');
    $sbox=($sbox[2]-$sbox[0])*0.3;
    $angle=$angle*M_PI/180;
	$da = 0;
    foreach(preg_split('//u', $text) AS $t){
        $px=$x+$r*cos($angle);
        $py=$y+$r*sin($angle);
        $dirangle=(360-(M_PI/2+$angle)*180/M_PI+($dir?180:0))%360;
        $box=imagettfbbox($size, 0, $fontfile, $t);
        $dx=$box[2]-$box[0];
        $da=abs(asin(($dx+$sbox)/$r));
        if($dir){
            $angle-=$da;
        }else{
            $angle+=$da;
        }
    }
	$angle = $dir ? $angle+=$da : $angle-=$da;
	return $angle*180/M_PI ;
}


// Text
$text_top = isset($_GET["text_top"]) ? $_GET["text_top"] : "";
$text_bottom = isset($_GET["text_bottom"]) ? $_GET["text_bottom"] : "";


//  color
$color = isset($_GET["color"]) ? $_GET["color"] : "000000";
$color_red = strlen($color) == 6 ? hexdec($color[0] . '' . $color[1]) : "0";
$color_green = strlen($color) == 6 ? hexdec($color[2] . '' . $color[3]) : "0";
$color_blue = strlen($color) == 6 ? hexdec($color[4] . '' . $color[5]) : "0";


// Settings
$like_image_path = dirname(__FILE__) . '/assets/like.png';
$text_font = dirname(__FILE__) . '/assets/beamweapon.ttf';
$text_font_size = 20;

$radius_max = 300;
$thickness = 4;
$padding = 10;
$padding_text = 80;

// Calculating
$text_top_font_box = imagettfbbox($text_font_size, 0, $text_font, $text_top);
$text_top_width = abs($text_top_font_box[4] - $text_top_font_box[0]);
$text_top_height = abs($text_top_font_box[5] - $text_top_font_box[1]);

$text_bottom_font_box = imagettfbbox($text_font_size, 0, $text_font, $text_bottom);
$text_bottom_width = abs($text_bottom_font_box[4] - $text_bottom_font_box[0]);
$text_bottom_height = abs($text_bottom_font_box[5] - $text_bottom_font_box[1]);

$width = $radius_max + $padding;
$height = $width;
$centerX = $width / 2;
$centerY = $height / 2;

$radius_secondcircle = $radius_max - ($padding + $thickness);
$radius_text_top = ($radius_secondcircle - ($thickness * 2) - $text_top_height - ($padding_text / 2)) / 2;
$radius_text_bottom = ($radius_secondcircle - ($thickness * 2) - $text_top_height - ($padding_text / 2)) / 2 + $padding;
$radius_thirdcircle = $radius_secondcircle - ($text_top_height + $padding_text + $thickness);
$radius_fourthcircle = $radius_thirdcircle - ($padding + $thickness);

// Creating
$image_ressources = imagecreatetruecolor($width, $height);
imagesetthickness($image_ressources, $thickness);

// Color
$color_ressource = imagecolorallocate($image_ressources, $color_red, $color_green, $color_blue);
$color_black_ressource = imagecolorallocate($image_ressources, 0, 0, 0);

// Calculating Text possition
$start_angle_top = -90;
$end_angle_top = imagettftextarc_lastAngle($image_ressources, $text_font_size, $start_angle_top, $centerX, $centerY + 5, $radius_text_top, $color_black_ressource, $text_font, $text_top, false);
$start_angle_top = $start_angle_top - (($end_angle_top - $start_angle_top) / 2);


$start_angle_bottom = 90;
$end_angle_top = imagettftextarc_lastAngle($image_ressources, $text_font_size, $start_angle_bottom, $centerX, $centerY + 5, $radius_text_bottom, $color_black_ressource, $text_font, $text_bottom, true);
$start_angle_bottom = $start_angle_bottom + (($start_angle_bottom - $end_angle_top) / 2);


// Alpha
# find unique color
do {
	$r = rand(0, 255);
	$g = rand(0, 255);
	$b = rand(0, 255);
}
while (imagecolorexact($image_ressources, 255, 255, 255) < 0);
$alphacolor = imagecolorallocatealpha($image_ressources, 255, 255, 255, 127);
imagefill($image_ressources, 0, 0, $alphacolor);
imagecolortransparent($image_ressources, $alphacolor);


// Circles
imagearc($image_ressources, $centerX, $centerY, $radius_max, $radius_max,  0, 359.9, $color_black_ressource);
imagearc($image_ressources, $centerX, $centerY, $radius_secondcircle, $radius_secondcircle,  0, 359.9, $color_black_ressource);

imagearc($image_ressources, $centerX, $centerY, $radius_thirdcircle, $radius_thirdcircle,  0, 359.9, $color_black_ressource);
imagearc($image_ressources, $centerX, $centerY, $radius_fourthcircle, $radius_fourthcircle,  0, 359.9, $color_black_ressource);

//Text
imagettftextarc($image_ressources, $text_font_size, $start_angle_top, $centerX, $centerY + 5, $radius_text_top, $color_black_ressource, $text_font, $text_top, false);

imagettftextarc($image_ressources, $text_font_size, $start_angle_bottom, $centerX, $centerY + 5, $radius_text_bottom, $color_black_ressource, $text_font, $text_bottom, true);


// Center Image
$like_image = imagecreatefrompng($like_image_path);
imagealphablending($like_image, false);
imagesavealpha($like_image, true);
$like_image_curent_size = imagesx($like_image);
$like_new_size = 85;
$like_pos_x = $centerX - ($like_new_size / 2);
$like_pos_y = $centerY - ($like_new_size / 2);

imagecopyresampled($image_ressources, $like_image, $like_pos_x, $like_pos_y, 0, 0, $like_new_size, $like_new_size, $like_image_curent_size, $like_image_curent_size);


imagefilter($image_ressources, IMG_FILTER_CONTRAST, 5);
imagefilter($image_ressources, IMG_FILTER_COLORIZE, $color_red, $color_green, $color_blue);



$day_tocache = 7;
$seconds_tocache = $day_tocache * 86400;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_tocache) . " GMT";
header("Expires: ". $ts);
header("Pragma: cache");
header("Cache-Control: max-age=".$seconds_tocache);
header("User-Cache-Control: max-age=".$seconds_tocache);
header("Content-Type: image/png");
imagealphablending($image_ressources, false);
imagesavealpha($image_ressources, true);
imagepng($image_ressources);
imagedestroy($image_ressources);