<?php
/**
 * TimThumb by Ben Gillbanks and Mark Maunder
 * Based on work done by Tim McDaniels and Darren Hoyt
 * http://code.google.com/p/timthumb/
 * 
 * GNU General Public License, version 2
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * Examples and documentation available on the project homepage
 * http://www.binarymoon.co.uk/projects/timthumb/
 */

/*
    -----TimThumb CONFIGURATION-----
    You can either edit the configuration variables manually here, or you can 
    create a file called timthumb-config.php and define variables you want
    to customize in there. It will automatically be loaded by timthumb.
    This will save you having to re-edit these variables everytime you download
    a new version of timthumb.

*/

/**
 * Edited for internal use on 16/03/2012 by Dudkin Oleg
 */

ini_set( 'memory_limit', '512M' );
 
class w_timthumb {
    public $src = "";
    public $is404 = false;
    public $docRoot = "";
    public $lastURLError = false;
    public $localImage = "";
    public $localImageMTime = 0;
    public $url = false;
    public $myHost = "";
    public $isURL = false;
    public $cachefile = '';
    public $errors = array();
    public $toDeletes = array();
    public $cacheDirectory = '';
    public $startTime = 0;
    public $lastBenchTime = 0;
    public $cropTop = false;
    public $salt = "";
    public $fileCacheVersion = 1; //Generally if timthumb.php is modifed (upgraded) then the salt changes and all cache files are recreated. This is a backup mechanism to force regen.
    public $filePrependSecurityBlock = "<?php die('Execution denied!'); //"; //Designed to have three letter mime type, space, question mark and greater than symbol appended. 6 bytes total.
    public static $curlDataWritten = 0;
    public static $curlFH = false;

    public function processImage($localImage, $newFile, $new_width = 100, $new_height = 100, $zoom_crop = 1, $quality = 100, $canvas_color = 'ffffff', $align = 'c' ) {
        $args = func_get_args();
        $sData = getimagesize($localImage);
        $origType = $sData[2];
        $mimeType = $sData['mime'];

        if(! preg_match('/^image\/(?:gif|jpg|jpeg|png)$/i', $mimeType)){
            return false;
        }

        if (!function_exists ('imagecreatetruecolor')) {
            return false;
        }

        // open the existing image
        $image = $this->openImage ($mimeType, $localImage);
        if ($image === false) {  return false;  }

        // Get original width and height
        $width = imagesx ($image);
        $height = imagesy ($image);
        $origin_x = 0;
        $origin_y = 0;

        // generate new w/h if not provided
        if ($new_width && !$new_height) {
            $new_height = floor ($height * ($new_width / $width));
        } else if ($new_height && !$new_width) {
            $new_width = floor ($width * ($new_height / $height));
        }

        // scale down and add borders
        if ($zoom_crop == 3) {

            $final_height = $height * ($new_width / $width);

            if ($final_height > $new_height) {
                $new_width = $width * ($new_height / $height);
            } else {
                $new_height = $final_height;
            }

        }

        // create a new true color image
        $canvas = imagecreatetruecolor ($new_width, $new_height);
        imagealphablending ($canvas, false);

        if (strlen ($canvas_color) < 6) {
            $canvas_color = 'ffffff';
        }

        $canvas_color_R = hexdec (substr ($canvas_color, 0, 2));
        $canvas_color_G = hexdec (substr ($canvas_color, 2, 2));
        $canvas_color_B = hexdec (substr ($canvas_color, 2, 2));

        // Create a new transparent color for image
        $color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 127);

        // Completely fill the background of the new image with allocated color.
        imagefill ($canvas, 0, 0, $color);

        // scale down and add borders
        if ($zoom_crop == 2) {

            $final_height = $height * ($new_width / $width);

            if ($final_height > $new_height) {

                $origin_x = $new_width / 2;
                $new_width = $width * ($new_height / $height);
                $origin_x = round ($origin_x - ($new_width / 2));

            } else {

                $origin_y = $new_height / 2;
                $new_height = $final_height;
                $origin_y = round ($origin_y - ($new_height / 2));

            }

        }

        // Restore transparency blending
        imagesavealpha ($canvas, true);

        if ($zoom_crop > 0) {

            $src_x = $src_y = 0;
            $src_w = $width;
            $src_h = $height;

            $cmp_x = $width / $new_width;
            $cmp_y = $height / $new_height;

            // calculate x or y coordinate and width or height of source
            if ($cmp_x > $cmp_y) {

                $src_w = round ($width / $cmp_x * $cmp_y);
                $src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

            } else if ($cmp_y > $cmp_x) {

                $src_h = round ($height / $cmp_y * $cmp_x);
                $src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

            }

            // positional cropping!
            if ($align) {
                if (strpos ($align, 't') !== false) {
                    $src_y = 0;
                }
                if (strpos ($align, 'b') !== false) {
                    $src_y = $height - $src_h;
                }
                if (strpos ($align, 'l') !== false) {
                    $src_x = 0;
                }
                if (strpos ($align, 'r') !== false) {
                    $src_x = $width - $src_w;
                }
            }

            imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);

        } else {

            // copy and resize part of an image with resampling
            imagecopyresampled ($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        }

         //Straight from Wordpress core code. Reduces filesize by up to 70% for PNG's
        if ( (IMAGETYPE_PNG == $origType || IMAGETYPE_GIF == $origType) && function_exists('imageistruecolor') && !imageistruecolor( $image ) && imagecolortransparent( $image ) > 0 ){
            imagetruecolortopalette( $canvas, false, imagecolorstotal( $image ) );
        }

        $imgType = "";
        
        if(preg_match('/^image\/(?:jpg|jpeg)$/i', $mimeType)){ 
            $imgType = 'jpg';
            imagejpeg($canvas, $newFile, $quality); 
        } else if(preg_match('/^image\/png$/i', $mimeType)){ 
            $imgType = 'png';
            imagepng($canvas, $newFile, floor($quality * 0.09));
        } else if(preg_match('/^image\/gif$/i', $mimeType)){
            $imgType = 'gif';
            imagegif($canvas, $newFile);
        } else {
            return false;
        }

        imagedestroy($canvas);
        imagedestroy($image);
        return true;
    }

    public function openImage($mimeType, $src){
        switch ($mimeType) {
            case 'image/jpg': //This isn't a valid mime type so we should probably remove it
            case 'image/jpeg':
                $image = imagecreatefromjpeg ($src);
                break;

            case 'image/png':
                $image = imagecreatefrompng ($src);
                break;

            case 'image/gif':
                $image = imagecreatefromgif ($src);
                break;
        }

        return $image;
    }

    public function getMimeType($file){
        $info = getimagesize($file);
        if(is_array($info) && $info['mime']){
            return $info['mime'];
        }
        return '';
    }

    public static function returnBytes($size_str){
        switch (substr ($size_str, -1))
        {
            case 'M': case 'm': return (int)$size_str * 1048576;
            case 'K': case 'k': return (int)$size_str * 1024;
            case 'G': case 'g': return (int)$size_str * 1073741824;
            default: return $size_str;
        }
    }
    public function getURL($url, $tempfile){
        //$url = preg_replace('/ /', '%20', $url);
        if( strpos($url, '/wp-content/' ) !== false ) $url = preg_replace( '#http://(www\.)?swissphone\.com#', ABSPATH, $url );
        $img = @file_get_contents ($url);
        if($img === false){
            return false;
        }
        if(! @file_put_contents($tempfile, $img)){
            return false;
        }
        return true;
    
    }

    public static function curlWrite($h, $d){
        fwrite(self::$curlFH, $d);
        self::$curlDataWritten += strlen($d);
        if(self::$curlDataWritten > MAX_FILE_SIZE){
            return 0;
        } else {
            return strlen($d);
        }
    }

}