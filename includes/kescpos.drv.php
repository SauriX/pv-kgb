<?php
      // ===============================
      //         KEscPos  v1.3         =
      // Kovu's ESC/POS Printer Driver =
      // ===============================
      //  (c) Markus Fumagalli "Kovu"  =
      //    http://www.terrasco.net    =
      //          May 13 2013          =
      // ===============================
      //     You may edit, use and     =
      //    redistribute this file,    =
      //    but leave the original     =
      //    copyright info intact!     =
      // ===============================
      // Changelog:
      // 
      // - Jan 05 2010: v1.0 first release
      // - Dec 11 2010: v1.1 added LPT ports for Windows
      // - Jan 24 2013: v1.2 function font() updated for compatibility with PHP 5.3+
      //                thanks to Alejandro S�nchez for pointing this out!
      // - May 13 2013: v1.3 added network support
      //                this is a mod by Andrea Rincon Ray!
      
      // Standard Printer Setups
      // =======================
      
      $KEscPos_printer = array(
         
         // TM-U220B, AFU
         // =============
         
         'TM-U220B AFU' => array(
            
            /* Printer Setup */
            
                 'width' => 40,
             'translate' => true,
            'p_char_set' => false,
            
            /* Printer Capabilities */
            
                 'color' => true,
               'barcode' => false,
                   'cut' => true,
                  'logo' => false,
                'drawer' => true,
            'fontselect' => true,
               'reverse' => true,
            
            /* Communication Setup */
            
                  'baud' => 9600,
                'parity' => 'N',
                  'data' => 8,
                  'stop' => 1,
                   'xon' => 'off',
            
            /* Character Translation Setup */
            
            't_char_set' => 'ch-latin-2',
                  
                  'find' => array('�',      '�',      '�',      '�',      '�',      '�' ,
                                  '�',      '�',      '�',      '�',      '�',      '�' ,
                                  '�',      '�',      '�',      '�',      '�',      '�' , '�', '�'),
            
                  'repl' => array(chr(142), chr(153), chr(154), chr(132), chr(148), chr(129),
                                  chr(133), chr(131), chr(135), chr(130), chr(138), chr(136),
                                  chr(137), chr(139), chr(140), chr(141), chr(147), chr(149), chr(164), chr(165))
         ),
         
         // TM-T88IV, AFU
         // =============
         
         'TM-T88IV AFU' => array(
            
            /* Printer Setup */
            
                 'width' => 40,
             'translate' => true,
            'p_char_set' => false,
            
            /* Printer Capabilities */
            
                 'color' => false,
               'barcode' => true,
                   'cut' => true,
                  'logo' => true,
                'drawer' => true,
            'fontselect' => true,
               'reverse' => false,
            
            /* Communication Setup */
            
                  'baud' => 9600,
                'parity' => 'N',
                  'data' => 8,
                  'stop' => 1,
                   'xon' => 'off',
            
            /* Character Translation Setup */
            
            't_char_set' => 'ch-latin-2',
            
                  'find' => array('�',      '�',      '�',      '�',      '�',      '�' ,
                                  '�',      '�',      '�',      '�',      '�',      '�' ,
                                  '�',      '�',      '�',      '�',      '�',      '�' , '�', '�'),
            
                  'repl' => array(chr(142), chr(153), chr(154), chr(132), chr(148), chr(129),
                                  chr(133), chr(131), chr(135), chr(130), chr(138), chr(136),
                                  chr(137), chr(139), chr(140), chr(141), chr(147), chr(149), chr(164), chr(165))
         )
      );
      
      
      // KByte
      // =====
      
      if(!function_exists('kchr')){
         function kbyte($val){
            return pack('C', $val);
         }
      }
      
      
      // KEscPos Class
      // =============
      
      class KEscPos{
         
         // Prepare KEscPos Object
         // ======================
         
         /* Printer Driver Settings */
           private $os;                    // operating system
         protected $printer;               // printer name or port
           private $output;                // output mode
           private $handle;                // printer- / file- handle
           private $width;                 // output width (characters)
           private $translate;             // use character translation
           private $printer_char_set;      // printer character set
         
         /* Data */
         protected $document;              // printer document
         protected $translator_char_set;   // translator character set name
         protected $translate_find;        // find those characters (array)
         protected $translate_replace;     // and replace with these (array)
         
         /* Printer Capability Settings */
           private $pcp_color;             // can print colors
           private $pcp_barcode;           // can print bar codes
           private $pcp_cut;               // can cut paper
           private $pcp_logo;              // can print logo
           private $pcp_drawer;            // has drawer kick
           private $pcp_fontselect;        // has font selection
           private $pcp_reverse;           // can reverse feed
         
         /* Port Settings */
         protected $baud;
         protected $parity;
         protected $data;
         protected $stop;
         protected $xon;
         
         
         // Constructor
         // ===========
         
         function __construct($printer_setup = false, $printer_name = false, $reset = true, $com_setup = true){
            if(substr(php_uname(), 0, 7) == "Windows") $this -> os = "windows";
            else $this -> os = "unix";
            
            $this -> printer = 'COM1';
            $this -> output = 'port';
            $this -> handle = false;
            $this -> document = false;
            
            // Load Printer Setup
            // ==================
            
            $this -> LoadSetup($printer_setup);
            
            // Open Printer?
            // =============
            
            if($printer_name == true) $this -> Open($printer_name, $reset, $com_setup);
         }
         
         
         // Error Message
         // =============
         
         private function error($message){
            echo "KEscPos ERROR: $message\n";
         }
         
         
         // Use Character Translation
         // =========================
         
         function UseTranslation($use = true){
            if($use == true) $this -> translate = true;
            else $this -> translate = false;
         }
         
         
         // Character Translation Setup
         // ===========================
         
         function TranslationSetup($name, $find, $replace){
            if(!is_array($find) && !is_array($replace)){
               $this -> error('$find and $replace must be arrays!');
               return false;
            }
            
            $this -> translate_find = $find;
            $this -> translate_replace = $replace;
            $this -> translator_char_set = $name;
            return true;
         }
         
         
         // Select Printer-Character-Table
         // ==============================
         
         function PrinterCharacterTable($char_table){
            if($this -> handle != false){
               $this -> error('Character tables of opened printers can\'t be altered.');
               return false;
            }elseif(!is_numeric($char_table)){
               $this -> error('Value to select character table must be a number!');
               return false;
            }
            
            $this -> printer_char_set = $char_table;
            return true;
         }
         
         
         // Set Width (Characters)
         // ======================
         
         function SetWidth($width){
            if(!is_numeric($width)){
               $this -> error('Width must be a number.');
               return false;
            }
            
            $this -> width = $width;
            return true;
         }
         
         
         // Capability Setup
         // ================
         
         function CapSetup($set_color = false, $set_barcode = false, $set_cut = false, $set_logo = false, $set_drawer = false, $set_fontselect = false, $set_reverse = false){
            $this -> pcp_color = $set_color;
            $this -> pcp_barcode = $set_barcode;
            $this -> pcp_cut = $set_cut;
            $this -> pcp_logo = $set_logo;
            $this -> pcp_drawer = $set_drawer;
            $this -> pcp_fontselect = $set_fontselect;
            $this -> pcp_reverse = $set_reverse;
         }
         
         
         // Communication Setup
         // ===================
         
         function ComSetup($set_baud = false, $set_parity = false, $set_data = false, $set_stop = false, $set_xon = false){
            if($this -> handle != false){
               $this -> error('Communication settings of opened devices can\'t be altered.');
               return false;
            }
            
            if($set_baud != false)   $this -> baud = $set_baud;
            if($set_parity != false) $this -> parity = $set_parity;
            if($set_data != false)   $this -> data = $set_data;
            if($set_stop != false)   $this -> stop = $set_stop;
            if($set_xon != false)    $this -> xon = $set_xon;
            return true;
         }
         
         
         // Load Printer Setup
         // ==================
         
         function LoadSetup($printer = false){
            global $KEscPos_printer;
            
            if($printer == false){
               $printer = 'DEFAULT';
               $KEscPos_printer['DEFAULT'] = array(
                                                   'width' => 40,
                                                   'translate' => true,
                                                   'p_char_set' => false,
                                                   'color' => false,
                                                   'barcode' => false,
                                                   'cut' => false,
                                                   'logo' => false,
                                                   'drawer' => false,
                                                   'fontselect' => false,
                                                   'reverse' => false,
                                                   'baud' => 9600,
                                                   'parity' => 'N',
                                                   'data' => 8,
                                                   'stop' => 1,
                                                   'xon' => 'off',
                                                   't_char_set' => false,
                                                   'find' => false,
                                                   'repl' => false,
                                                  );
            }
            
            if(!isset($KEscPos_printer[$printer])){
               $this -> error('Unknown printer setup.');
               return false;
            }
            
            $this -> width = $KEscPos_printer[$printer]['width'];
            $this -> translate = $KEscPos_printer[$printer]['translate'];
            $this -> printer_char_set = $KEscPos_printer[$printer]['p_char_set'];
            
            $this -> pcp_color = $KEscPos_printer[$printer]['color'];
            $this -> pcp_barcode = $KEscPos_printer[$printer]['barcode'];
            $this -> pcp_cut = $KEscPos_printer[$printer]['cut'];
            $this -> pcp_logo = $KEscPos_printer[$printer]['logo'];
            $this -> pcp_drawer = $KEscPos_printer[$printer]['drawer'];
            $this -> pcp_fontselect = $KEscPos_printer[$printer]['fontselect'];
            $this -> pcp_reverse = $KEscPos_printer[$printer]['reverse'];
            
            $this -> baud = $KEscPos_printer[$printer]['baud'];
            $this -> parity = $KEscPos_printer[$printer]['parity'];
            $this -> data = $KEscPos_printer[$printer]['data'];
            $this -> stop = $KEscPos_printer[$printer]['stop'];
            $this -> xon = $KEscPos_printer[$printer]['xon'];
            /*
            if($KEscPos_printer[$printer]['find'] != false && $KEscPos_printer[$printer]['repl'] != false){
               if($KEscPos_printer[$printer]['t_char_set'] == false) $KEscPos_printer[$printer]['t_char_set'] = $printer;
              */ 
               $this -> TranslationSetup('utf-8', $KEscPos_printer[$printer]['find'], $KEscPos_printer[$printer]['repl']);
           // }
            
            return true;
         }
         
         
         // Get Settings
         // ============
         
         function GetSettings($setting = false, $echo = false){
            $settings = array();
            
            $settings['os'] = $this -> os;
            $settings['printer'] = $this -> printer;
            $settings['output'] = $this -> output;
            
            $settings['width'] = $this -> width;
            $settings['translate'] = $this -> translate;
            $settings['p_char_set'] = $this -> printer_char_set;
            $settings['t_char_set'] = $this -> translator_char_set;
            
            $settings['color'] = $this -> pcp_color; 
            $settings['barcode'] = $this -> pcp_barcode;
            $settings['cut'] = $this -> pcp_cut; 
            $settings['logo'] = $this -> pcp_logo; 
            $settings['drawer'] = $this -> pcp_drawer;
            $settings['fontselect'] = $this -> pcp_fontselect;
            $settings['reverse'] = $this -> pcp_reverse;
            
            $settings['baud'] = $this -> baud;
            $settings['parity'] = $this -> parity;
            $settings['data'] = $this -> data; 
            $settings['stop'] = $this -> stop;
            $settings['xon'] = $this -> xon;
            
            if($echo == true){
               if($setting == false){
                  foreach($settings as $name => $value){
                     if($value === false) $text = 'false';
                     elseif($value === true) $text = 'true';
                     else $text = $value;
                     echo sprintf("%' 12s", $name) . ": $text\n";
                  }
                  return true;
               }else{
                  if(isset($settings[$setting])){
                     echo "$setting: " . $settings[$setting] . "\n";
                     return true;
                  }else{
                     $this -> error('There\'s no such setting.');
                     return false;
                  }
               }
            }else{
               if($setting == false){
                  return $settings;
               }else{
                  if(isset($settings[$setting])){
                     return $settings[$setting];
                  }else{
                     $this -> error('There\'s no such setting.');
                     return false;
                  }
               }
            }
         }
         
         
         // Open
         // ====
         
         function Open($printer_name, $reset = true, $com_setup = true){
            
            // Network Support by Andrea Rincon Ray, May 13th 2013
            // ===================================================
            
            $printer_port = explode(":", $printer_name);
            
            // Check Opening / Output Method
            // ================================
            // network mod: May 13 2013
            // - replaced switch($printer_name)
            //   with switch($printer_port[0])
            // - added case "ip"
            
            switch($printer_port[0]){
               case "PRN":
               case "COM1":
               case "COM2":
               case "LPT1":
               case "LPT2":
                  if($this -> os == 'unix'){
                     $this -> error('Invalid Unix port.');
                     return false;
                  }
                  $this -> output = 'port';
                  break;
               
               case "ttyS0":
               case "ttyS1":
               case "ttyS2":
               case "lp0":
               case "lp1":
               case "lp2":
               case "usb/lp0":
               case "usb/lp1":
               case "usb/lp2":
                  if($this -> os == 'windows'){
                     $this -> error('Invalid Windows port.');
                     return false;
                  }
                  $this -> output = 'port';
                  break;
               
               case "ip":
				  $this -> output = 'network';
				  break;
               
               default:
                  $this -> output = 'printer_system';
            }
            
            $this -> printer = $printer_name;
            
            // Open Printer
            // ============
            
            if($this -> output == 'printer_system'){
               
               if($this -> os == 'windows'){
                  
                  // Open With Windows Printer Functions
                  // ===================================
                  
                  if(!extension_loaded('printer')){
                     $this -> error('PHP\'s windows printer extension must be loaded.');
                     return false;
                  }
                  
                  $this -> handle = printer_open($this -> printer);
                  
                  if($this -> handle == false){
                     $this -> error('Printer couldn\'t be opened.');
                     return false;
                  }
                  
                  printer_set_option($this -> handle, PRINTER_MODE, "raw");
                  
               }else{
                  
                  // Send Document to CUPS lpr later
                  // ===============================
                  
                  $this -> handle = true;
               }
            
			}elseif($this -> output == 'network'){
               
               // Network Support by Andrea Rincon Ray, May 13th 2013
               // ===================================================
               
	           $this -> handle = fsockopen($printer_port[1], 9100, $errno, $errstr, 10);

			   if($this -> handle == false){
			       $this -> error('Printer couldn\'t be opened to :' . $printer_port[1] . " because " . $errstr);
			       return false;
			   }
            
            }else{
               
               if($this -> output == 'port' && $this -> os == 'windows'){
                  
                  // Open Port DOS / Windows
                  // =======================
                  
                  if($this -> printer == "PRN"){
                     $device = $this -> printer;
                  }else{
                     if($com_setup == true){
                        
                        // Communications Setup
                        // =================================================================
                        // shell_exec("mode com1: BAUD=9600 PARITY=N data=8 stop=1 xon=off")
                        
                        shell_exec("mode " . strtolower($this -> handle) . ": BAUD=" . $this -> baud . " PARITY=" . $this -> parity . " data=" . $this -> data . " stop=" . $this -> stop . " xon=" . $this -> xon);
                     }
                     
                     $device = $this -> printer;
                  }
                  
                  $this -> handle = fopen($device, 'w');
                  
                  if($this -> handle == false){
                     $this -> error('Printer couldn\'t be opened.');
                     return false;
                  }
                  
               }elseif($this -> output == 'port' && $this -> os == 'unix'){
                  
                  // Open Serial Port Unix
                  // =====================
                  
                  if($com_setup == true){
                     
                     // Communications Setup
                     // ====================================================
                     // system("stty 9600 cs8 -parenb -cstopb < /dev/ttyS0")
                     
                     $udevice = ' < /dev/' . $this -> printer;
                     $ubaud = $this -> baud;
                     if($this -> data == 8) $udata = ' cs' . $this -> data; // number of data bits
                     if($this -> parity == 'N') $uparity = ' -parenb';      // no parity bit
                     else $uparity = ' parenb';                             // parity bit
                     if($this -> stop == 1) $ustop = ' -cstopb';            // one stop bit
                     else $ustop = ' cstopb';                               // two stop bits
                     if($this -> xon == 'on') $uxon = ' ixon';              // activate XON
                     else $uxon = ' -ixon';                                 // deactivate XON
                     
                     system("stty " . $ubaud . $udata . $uparity . $ustop . $udevice);
                  }
                  
                  $device = "/dev/" . $this -> printer;
                  $this -> handle = fopen($device, 'w');
                  
                  if($this -> handle == false){
                     $this -> error('Printer couldn\'t be opened.');
                     return false;
                  }
               }else{
                  return false;
               }
            }
            
            // Re-Initialize printer (reset buffer)?
            // =====================================
            
            if($reset == true) $esc_pos_command = kbyte(27) . "@";
            else $esc_pos_command = "";
            
            // Select ESC/POS Character Code Table?
            // ====================================
            
            if($this -> printer_char_set !== false) $esc_pos_command .= kbyte(27) . "t" . kbyte($this -> printer_char_set);
            
            // Append to Document
            // ==================
            
            if(isset($esc_pos_command)) $this -> document = $esc_pos_command;
            return true;
         }
         
         
         // Append to Document
         // ==================
         
         protected function Append($string){
            if($this -> handle == false) return false;
            if($this -> document === false) $this -> document = $string;
            else $this -> document .= $string;
            return true;
         }
         
         
         // Select Color
         // ============
         // $color: integer (color id 0 or 1)
         
         function Color($color = 0){
            if($this -> handle == false) return false;
            if($this -> pcp_color != true) return false;
            
            switch($color){
               case 1:
                  $select = kbyte(49);
                  break;
               
               default:
                  $select = kbyte(48);
            }
            
            return $this -> Append(kbyte(27) . "r" . $select);
         }
         
         
         // Double Strike
         // =============
         // $double_strike: bool
         
         function Double($double_strike = false){
            if($this -> handle == false) return false;
            
            switch($double_strike){
               case true:
                  $set = kbyte(49);
                  break;
               
               default:
                  $set = kbyte(48);
            }
            
            return $this -> Append(kbyte(27) . "G" . $set);
         }
         
         
         // Emphasize
         // =========
         // $select: bool
         
         function Emphasize($select = false){
            if($this -> handle == false) return false;
            
            switch($select){
               case true:
                  $set = kbyte(49);
                  break;
               
               default:
                  $set = kbyte(48);
            }
            
            return $this -> Append(kbyte(27) . "E" . $set);
         }
         
         
         // Underline
         // =========
         // $underline: bool
         
         function Underline($underline = false){
            if($this -> handle == false) return false;
            
            switch($underline){
               case true:
                  $set = kbyte(49);
                  break;
               
               default:
                  $set = kbyte(48);
            }
            
            return $this -> Append(kbyte(27) . "-" . $set);
         }
         
         
         // Invert Black/White
         // ==================
         // $side: integer   (0 <=> 1)
         
         function InvertBW($side = 0){
            if($this -> handle == false) return false;
            if($side > 1 OR $side < 0) $side = 0;
            
            return $this -> Append(kbyte(29) . "B" . kbyte($side));
         }
         
         
         // Upside Down Printing
         // ==================================
         // (c) 2009 by 'smacedo' on my forum,
         // modified by 'Kovu' 01.04.2010.
         // Thanks for your contribution!
         // ==================================
         // $side: integer   (0 <=> 1)
         
         function UpsideDown($side = 0){
            if($this -> handle == false) return false;
            if($side > 1 OR $side < 0) $side = 0;
            
            return $this -> Append(kbyte(27) . "{" . kbyte($side));
         }
         
         
         // Character Size
         // ==================================
         // (c) 2009 by 'smacedo' on my forum,
         // modified by 'Kovu' 01.04.2010.
         // Thanks for your contribution!
         // ==================================
         // $size: integer   (0 <=> 255)
         
         function Size($size = 0){
            if($this -> handle == false) return false;
            if($size < 0) $size = 0;
            elseif($size > 255) $size = 255;
            
            return $this -> Append(kbyte(29) . "!" . kbyte($size));
         }
         
         
         // Select Font
         // =======================================
         // $font: string   (type character A/B/*C)
         // =======================================
         // Update & Bugfix: Jan 24 2013
         // - replaced eregi() with preg_match()
         //   for compatibility with PHP 5.3+
         // - statement originally couldn't execute
         //   because of eregi subject $font
         //   instead of $this -> printer
         
         function Font($font = "B"){
            if($this -> handle == false) return false;
            if($this -> pcp_fontselect != true) return false;
            if(preg_match("/TM-U220/i", $this -> printer) && $font == "C") $font = "B";
            
            switch($font){
               case "A":
                  $set = kbyte(48);
                  break;
               
               case "C":
                  $set = kbyte(50);
                  break;
               
               default:
                   $set = kbyte(49);
            }
            
            return $this -> Append(kbyte(27) . "M" . $set);
         }
         
         
         // Select Character Height
         // =======================
         // $size: string   (double / normal)
         
         function Height($size = "double"){
            if($this -> handle == false) return false;
            
            switch($size){
               case "double":
                  $set = kbyte(16);
                  break;
               
               default:
                  $set = kbyte(0);
            }
            
            return $this -> Append(kbyte(27) . "!" . $set);
         }
         
         
         // Select Character Width
         // ======================
         // $size: string   (double / normal)
         
         function Width($size = "double"){
            if($this -> handle == false) return false;
            
            switch($size){
               case "double":
                  $set = kbyte(32);
                  break;
               
               default:
                  $set = kbyte(0);
            }
            
            return $this -> Append(kbyte(27) . "!" . $set);
         }
         
         
         // Align
         // =====
         // $position: string (left/center/right)
         
         function Align($position = "left"){
            if($this -> handle == false) return false;
            
            switch($position){
               case "right":
                  $set = kbyte(50);
                  break;
               
               case "center":
                  $set = kbyte(49);
                  break;
               
               default:
                  $set = kbyte(48);
            }
            
            return $this -> Append(kbyte(27) . "a" . $set);
         }
         
         
         // XFeed
         // =====
         // NOTE: could lead to unexpected behavior!
         // =====
         // $lines: integer (lines to feed)
         
         function XFeed($lines = 1){
            if($this -> handle == false) return false;
            
            if($lines > 1) $feed = kbyte(2);
            else $feed = kbyte($lines);
            
            return $this -> Append(kbyte(27) . "d" . $feed);
         }
         
         
         // XFeed Reverse
         // =============
         // NOTE: could lead to unexpected behavior!
         // =============
         // $lines: integer (lines to feed)
         
         function XrFeed($lines = 1){
            if($this -> handle == false) return false;
            if($this -> pcp_reverse != true) return false;
            
            if($lines > 1) $feed = kbyte(2);
            else $feed = kbyte($lines);
            
            return $this -> Append(kbyte(27) . "e" . $feed);
         }
         
         
         // Cut
         // ===
         // $lines: integer (lines to feed before cutting) optional
         
         function Cut($lines = false){
            if($this -> handle == false) return false;
            if($this -> pcp_cut != true) return false;
            
            if($lines != false) $feed = kbyte($lines);
            else $feed = kbyte(0);
            
            return $this -> Append(kbyte(29) . "V" . kbyte(65) . $feed);
         }
         
         
         // Drawer Kick
         // ===========
         // $pin: 2, 5 or 'both'
         
         function Drawer($pin = 2){
            if($this -> handle == false) return false;
            if($this -> pcp_drawer != true) return false;
            
            if($pin == 'both'){
               $this -> Drawer(2);
               return $this -> Drawer(5);
            }else{
               if($pin == 5) $pin = kbyte(49);
               else $pin = kbyte(48);
               
               return $this -> Append(kbyte(27) . "p" . $pin . kbyte(49) . kbyte(50));
            }
         }
         
         
         // Translate
         // =========
         // $string: string (text data)
         
         protected function Translate($string){
            //if($this -> translate != true) return $string;
            
            if(!is_array($this -> translate_find) OR !is_array($this -> translate_replace)){
               $this -> error('Translation isn\'t set up correctly.');
               return $string;
            }
            
            return str_replace($this -> translate_find, $this -> translate_replace, $string);
         }
         
         
         // Add a Line
         // ==========
         // $string: string (print data)
         // $feed: bool     (feed after printing line)
         
         function Line($string, $feed = true){
            if($this -> handle == false) return false;
            if($this -> pcp_drawer != true) return false;
            
            $esc_pos_command = $this -> Translate(utf8_decode($string));
    
            if($feed == true) $esc_pos_command .= kbyte(10);
            
            return $this -> Append($esc_pos_command);
         }
         
         
         // NewLine
         // =======
         
         function NL(){
            if($this -> handle == false) return false;
            return $this -> Append(kbyte(10));
         }
         
         
         // Print From File
         // ================================
         // (c) 2009 by 'solis' on my forum,
         // modified by 'Kovu' 01.04.2010.
         // Thanks for your contribution!
         // ================================
         // $size: integer   (0 <=> 255)
         
         function File($path, $textmode = true){
            if($this -> handle == false) return false;
            
            if(!file_exists($path)){
               $this -> error('There\'s no such file!');
               return false;
            }
            
            if($textmode != true){
               
               // Send The Entire File
               // ====================
               
               if(!$content = file_get_contents($path)){
                  $this -> error('File couldn\'t be opened.');
                  return false;
               }
               
               return $this -> Append($content . kbyte(10));
               
            }else{
               
               // Send as Text-Lines
               // ==================
               
               if(!$lines = file($path, FILE_IGNORE_NEW_LINES)){
                  $this -> error('File couldn\'t be opened.');
                  return false;
               }
               
               foreach($lines as $line) $this -> Line($line, true);
               return true;
            }
         }
         
         
         // Barcode Setup
         // =============
         // $height: integer 1 <=> 255
         //  $width: integer 1 <=> 3
         //   $text: string 'none', 'above', 'below', 'both'
         //   $font: integer 0 <=> 1
         
         function BarcodeSetup($width = false, $height = false, $text = false, $font = false){
            if($this -> handle == false) return false;
            if($this -> pcp_barcode != true) return false;
            
            if($width !== false && is_numeric($width)){
               $this -> Append(kbyte(29) . "w" . kbyte($width));
            }
            
            if($height !== false && is_numeric($height)){
               $this -> Append(kbyte(29) . "h" . kbyte($height));
            }
            
            if($text !== false){
               if($text == 'none') $text = 0;
               if($text == 'above') $text = 1;
               if($text == 'below') $text = 2;
               if($text == 'both') $text = 3;
               $this -> Append(kbyte(29) . "H" . kbyte($text));
            }
            
            if($font !== false && is_numeric($font)){
               $this -> Append(kbyte(29) . "f" . kbyte($font));
            }
            
            return true;
         }
         
         
         // Barcode
         // =======
         //  $system: string (system)
         // $barcode: string (corresponding number of characters)
         
         function Barcode($barcode, $system = 'UPC-A', $n_chars = '0'){
            if($this -> handle == false) return false;
            if($this -> pcp_barcode != true) return false;
            $system = strtoupper($system);
            
            switch($system){
               case 'UPC-A':
                  $m = kbyte(65);
                  if($n_chars > 12) $n = kbyte(12);
                  elseif($n_chars < 11) $n = kbyte(11);
                  else $n = kbyte($n_chars);
                  break;
               
               case 'UPC-E':
                  $m = kbyte(66);
                  if($n_chars > 12) $n = kbyte(12);
                  elseif($n_chars < 11) $n = kbyte(11);
                  else $n = kbyte($n_chars);
                  break;
               
               case 'JAN13':
               case 'EAN13':
                  $m = kbyte(67);
                  if($n_chars > 13) $n = kbyte(13);
                  elseif($n_chars < 12) $n = kbyte(12);
                  else $n = kbyte($n_chars);
                  break;
               
               case 'JAN8':
               case 'EAN8':
                  $m = kbyte(68);
                  if($n_chars > 8) $n = kbyte(8);
                  elseif($n_chars < 7) $n = kbyte(7);
                  else $n = kbyte($n_chars);
                  break;
               
               case 'CODE39':
                  $m = kbyte(69);
                  if($n_chars > 255) $n = kbyte(255);
                  elseif($n_chars < 1) $n = kbyte(1);
                  else $n = kbyte($n_chars);
                  break;
               
               case 'ITF':
                  $m = kbyte(70);
                  if($n_chars > 255) $n = kbyte(255);
                  elseif($n_chars < 1) $n = kbyte(1);
                  else $n = kbyte($n_chars);
                  break;
               
               case 'CODABAR':
               case 'NW7':
                  $m = kbyte(71);
                  if($n_chars > 255) $n = kbyte(255);
                  elseif($n_chars < 1) $n = kbyte(1);
                  else $n = kbyte($n_chars);
                  break;
               
               case 'CODE93':
                  $m = kbyte(72);
                  if($n_chars > 255) $n = kbyte(255);
                  elseif($n_chars < 1) $n = kbyte(1);
                  else $n = kbyte($n_chars);
                  break;
               
               case 'CODE128':
                  $m = kbyte(72);
                  if($n_chars > 255) $n = kbyte(255);
                  elseif($n_chars < 2) $n = kbyte(2);
                  else $n = kbyte($n_chars);
                  break;
               
               default:
                  $this -> error('Unknown barcode system.');
                  return false;
            }
            
            $this -> Append(kbyte(29) . "k" . $m . $n . $barcode);
            
            return true;
         }
         
         
         // Send & Print Image
         // ==================
         // $bit_array: BitArrayGtk / BitArrayGD object
         //
         // or
         //
         // $path:      string (image path [*.png, *.jpg, *.bmp])
         
         function Image($bit_array = false, $path = false){
            if($this -> handle == false) return false;
            if($bit_array == false && $path == false) return false;
            if($this -> pcp_logo != true) return false;
            
            if($bit_array == false){
               
               // Check File
               // ==========
             
              if(!file_exists($path)){
                  
                  $this -> error('Invalid file path!');
                  return false;
               }
               
               if(class_exists('BitArrayGtk')){
                  $bit_array = new BitArrayGtk($path);
               }elseif(class_exists('BitArrayGD')){
                  $bit_array = new BitArrayGD($path);
               }else{
                  $this -> error('Please load the PHP-GTK or the GD module.');
                  return false;
               }
            }
            
            // Check Bit-Array!
            // ================
            
            if($bit_array -> width == false OR $bit_array -> height == false OR
               !is_array($bit_array -> dots)){
               
               $this -> error('Invalid bit-array data!');
               return false;
            }
            
            // Prepare For Printing Image
            // ==========================
            
            $this -> Output();                                      # empty buffer
            $this -> Append(kbyte(27) . "3" . kbyte(24));           # 24 dot line spacing
            
            // Calculate Low and High bytes [width = nL + (nH * 256)]
            // ======================================================
            
            $nhigh = substr($bit_array -> width / 256, 0, 1);
            $nlow = $bit_array -> width - ($nhigh * 256);
            
            // Process 24-bit-wise
            // ===================
            
            $offset = '0';
            
            while($offset < $bit_array -> height){                  # walk through lines
               $this -> Append(kbyte(27) . "*" . kbyte(33));        # 24 dot double density
               $this -> Append(kbyte($nlow) . kbyte($nhigh));       # low byte and high byte
               
               for($x = 0; $x < $bit_array -> width; ++$x){         # walk through columns
                  for($k = 0; $k < 3; ++$k){                        # 24 dots = 24 bits = 3 bytes ($k)
                     $byte = 0;                                     # start a byte
                     
                     for($b = 0; $b < 8; ++$b){                     # 1 byte = 8 bits ($b)
                        $y = ((($offset / 8) + $k) * 8) + $b;       # calculate $y position
                        $i = ($y * $bit_array -> width) + $x;       # calculate pixel position
                        
                        // check if bit exists, if not, zero it
                        // ====================================
                        
                        if(isset($bit_array -> dots[$i])) $bit = $bit_array -> dots[$i];
                        else $bit = '0';
                        
                        $byte |= $bit << (7 - $b);                  # shift bit and record byte
                     }
                     
                     $this -> Append(kbyte($byte));                 # attach the byte
                  }
               }
               
               $offset += 24;
               $this -> Append(kbyte(10));                          # line feed
            }
            
            // Finish
            // ======
            
            $this -> Append(kbyte(27) . "2");                       # reset line spacing
            $this -> Output();                                      # send to printer
         }
         
         
         // Print Buffer
         // ============
         
         function Output(){
            if($this -> handle == false) return false;
            if($this -> document == false) return false;
            
            if($this -> output == 'printer_system'){
               
               // Print Through Printer System
               // ============================
               
               if($this -> os == "windows"){
                  
                  // Windows
                  // =======
                                
                  if(printer_write($this -> handle, $this -> document) == false){
                     $this -> error('Couldn\'t write to the printer.');
                     return false;
                  }
                  
               }else{
                  
                  // Unix, CUPS
                  // ==========
            
                  $command = 'lpr -P "' . $this -> printer . '" -o raw ';
                  $pipe = popen("$command" , 'w');
                  
                  if(!$pipe){
                     $this -> error('Pipe failed.');
                     return false;
                  }
                  
                  fputs($pipe, $this -> document);
                  pclose($pipe);
               }
               
            }else{
               
               // Print On Port Directly
               // ======================
               
               if(fwrite($this -> handle, $this -> document) == false){
                  $this -> error('Couldn\'t write to the printer.');
                  return false;
               }
            }
            
            $this -> document = false;
            return true;
         }
         
         
         // Close Printer
         // =====================================
         // network mod: May 13 2013
         // - added 'network' to fclose statement
         
         function Close($print = true){
            if($this -> handle == false) return false;
            if($print == true) $this -> Output();
            
            if($this -> output == 'printer_system' && $this -> os == 'windows'){
               printer_close($this -> handle);
            }elseif($this -> output == 'port' || $this -> output == 'network'){
               fclose($this -> handle);
            }
            
            $this -> handle = false;
         }
         
         
         // Destructor
         // ==========
         
         function __destruct(){
            $this -> Close(false);
         }
      }
      
      
      // Create Bit Array (GTK-Version)
      // =================================
      // This class uses PHP-GTK's image 
      // manipulation functions to produce
      // a bit array that can be converted
      // into Epson's bit image standard.
      
      if(class_exists('gtk')){
         class BitArrayGtk{
            
            public $dots;
            public $width;
            public $height;
            
            function __construct($path){
               if(!file_exists($path)){
                  $this -> width = false;
                  $this -> height = false;
                  $this -> dots = false;
               }else{
                  $pixbuf = GdkPixbuf::new_from_file($path);
                  $this -> width = $pixbuf -> get_width();
                  $this -> height = $pixbuf -> get_height();
                  $this -> dots = array();
                  
                  // Check & Store Pixels
                  // ====================
                  
                  if($this -> width > 575) $this -> width = 575; # crop image if needed
                  
                  for($hi = 0; $hi < $this -> height; $hi++){
                     for($wi = 0; $wi < $this -> width; $wi++){
                        $pixel = $pixbuf -> get_pixel($wi, $hi);
                        
                        if($pixel !== false){
                           $r = ($pixel & 0xff000000) >> 24;
                           $g = ($pixel & 0x00ff0000) >> 16;
                           $b = ($pixel & 0x0000ff00) >> 8;
                           $a = ($pixel & 0x000000ff);
                           
                           if($r < 0) $r = 256 + $r;
                           
                           // White composes itself of R255, G255 and B255.
                           // I'm using 200 to avoid problems with JPEG
                           // compressed images. Everything with a lower value
                           // will be interpreted as black (normally R0, G0, B0).
                           
                           if($r > 200 && $g > 200 && $b > 200) $this -> dots[] = '0';
                           else $this -> dots[] = '1';
                        }
                     }
                  }
               }
            }
         }
      }
      
      
      // Create Bit Array (GD-Version)
      // =================================
      // This class uses PHP's GD image 
      // manipulation functions to produce
      // a bit array that can be converted
      // into Epson's bit image standard.
      
      if(extension_loaded('gd')){
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
      }
      
?>