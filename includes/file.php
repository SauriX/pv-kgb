<?

class BitArrayGD{
            
    public $dots;
    public $width;
    public $height;
    
    function __construct($path){
       if(!file_exists($path)){
          $this -> width = false;
          $this -> height = false;
          $this -> dots = false;
       }else{
          list($width, $height, $type, $attr) = getimagesize($path);
          $continue = true;
          
          switch($type){
             case IMG_JPG:
                $im = imagecreatefromjpeg($path);
                break;
                
             case IMG_PNG:
                $im = imagecreatefrompng($path);
                break;
             
             case IMG_WBMP:
                $im = imagecreatefromwbmp($path);
                break;
             
             default:
                echo "ERROR: Invalid image file type.\n";
                $this -> width = false;
                $this -> height = false;
                $this -> dots = false;
                $continue = false;
          }
          
          if($continue == true){
             $this -> width = $width;
             $this -> height = $height;
             $this -> dots = array();
             
             // Check & Store Pixels
             // ====================
          
             if($this -> width > 575) $this -> width = 575; # crop image
             
             for($hi = 0; $hi < $this -> height; $hi++){
                for($wi = 0; $wi < $this -> width; $wi++){
                   $pixel = imagecolorat($im, $wi, $hi);
                   $rgb = imagecolorsforindex($im, $pixel);
                   
                   if($rgb['red'] < 0) $rgb['red'] = 256 + $rgb['red'];
                   
                   // White composes itself of R255, G255 and B255.
                   // I'm using 200 to avoid problems with JPEG
                   // compressed images. Everything with a lower value
                   // will be interpreted as black (normally R0, G0, B0).
                   
                   if($rgb['red'] > 200 && $rgb['green'] > 200 && $rgb['blue'] > 200) $this -> dots[] = '0';
                   else $this -> dots[] = '1';
                }
             }
          }
       }
    }
 }
 $path="temp/logo.jpg";
$hola= file_exists($path);

echo($hola);

$bit_array = new BitArrayGD($path);

print_r($bit_array);