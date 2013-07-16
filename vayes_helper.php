<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Vayes HELPER
 * 
 * @author Yahya A. Erturan
 * @copyright Copyright (c) 2013, Yahya A. Erturan
 * @link    http://www.yahyaerturan.com
 * @access public
 */

// ------------------------------------------------------------------------

/**
 * vdebug()
 *
 * Debug Helper
 *
 * Functions to print/dump variables to the screen with CI style formatting 
 * and additional debug data.
 * 
 * Inspired by the works of Joost van Veen[1] and Kevin Wood-Friend[2].
 * [1] http://github.com/joostvanveen/
 * [2] http://github.com/kwoodfriend/
 *
 * @author Yahya ERTURAN <root@yahyaerturan.com>
 * @version 1.0
 * @license https://github.com/yahyaerturan/codeigniter-developers-debug-helper/blob/master/LICENSE MIT License
 * 
 * 
 */
if ( ! function_exists('vdebug'))
{
  /**
   * vdebug()
   * 
   * @param mixed $data
   * @param bool $die FALSE
   * @param bool $add_var_dump FALSE
   * @param bool $add_last_query TRUE
   * @return void
   */
  function vdebug($data, $die = false, $add_var_dump = false, $add_last_query = true)
  {
    $CI = &get_instance();
    $CI->load->library('unit_test');

    $bt = debug_backtrace();
    $src = file($bt[0]["file"]);
    $line = $src[$bt[0]['line'] - 1];
    # Match the function call and the last closing bracket
    preg_match('#' . __FUNCTION__ . '\((.+)\)#', $line, $match);
    $max = strlen($match[1]);
    $varname = NULL;
    $c = 0;
    for ($i = 0; $i < $max; $i++)
    {
      if ($match[1]{$i} == "(" ) $c++;
      elseif ($match[1]{$i} == ")" ) $c--;
      if ($c < 0) break;
      $varname .= $match[1]{$i};
    }
    
    if(is_object($data))     $message = 'Variable holds an OBJECT';
    elseif(is_array($data))  $message = 'Variable holds an ARRAY';
    elseif(is_string($data)) $message = 'Variable holds a  STRING';
    elseif(is_int($data))    $message = 'Variable holds a  INTEGER';
    elseif(is_true($data))   $message = 'Variable holds a  TRUE BOOLEAN';
    elseif(is_false($data))  $message = 'Variable holds a  FALSE BOOLEAN';
    elseif(is_null($data))   $message = 'Variable is NULL';
    elseif(is_float($data))  $message = 'Variable is FLOAT';
    else                     $message = 'N/A';
    
    $output  = '<div style="clear:both;"></div>';
    $output .= '<meta charset="UTF-8" />';
    $output .= '<style>::selection{background-color:#E13300!important;color:#fff}::moz-selection{background-color:#E13300!important;color:#fff}::webkit-selection{background-color:#E13300!important;color:#fff}div.debugbody{background-color:#fff;margin:40px;font:9px/12px normal;font-family:Arial,Helvetica,sans-serif;color:#4F5155;min-width:500px}a.debughref{color:#039;background-color:transparent;font-weight:400}h1.debugheader{color:#444;background-color:transparent;border-bottom:1px solid #D0D0D0;font-size:12px;line-height:14px;font-weight:700;margin:0 0 14px;padding:14px 15px 10px;font-family:Consolas}code.debugcode{font-family:Consolas,Monaco,Courier New,Courier,monospace;font-size:12px;background-color:#f9f9f9;border:1px solid #D0D0D0;color:#002166;display:block;margin:10px 0;padding:5px 10px 15px}pre.debugpre{display:block;padding:0;margin:0;color:#002166;font:12px/14px normal;font-family:Consolas,Monaco,Courier New,Courier,monospace;background:0;border:0}div.debugcontent{margin:0 15px}p.debugp{margin:0;padding:0}.debugitalic{font-style:italic}.debutextR{text-align:right;margin-bottom:0;margin-top:0}.debugbold{font-weight:700}p.debugfooter{text-align:right;font-size:11px;border-top:1px solid #D0D0D0;line-height:32px;padding:0 10px;margin:20px 0 0}div.debugcontainer{margin:10px;border:1px solid #D0D0D0;-webkit-box-shadow:0 0 8px #D0D0D0}code.debug p{padding:0;margin:0;width:100%;text-align:right;font-weight:700;text-transform:uppercase;border-bottom:1px dotted #CCC;clear:right}code.debug span{float:left;font-style:italic;color:#CCC}</style>';
    $output .= '<div class="debugbody"><div class="debugcontainer">';
    $output .= '<h1 class="debugheader">'.$varname.'</h1>';
    $output .= '<div class="debugcontent">';
    $output .= '<code class="debugcode"><p class="debugp debugbold debutextR">:: Variable Type</p>' . $message . '</code>';
    if($add_last_query)
    {
      if($CI->db->last_query())
      {
        $output .= '<code class="debugcode"><p class="debugp debugbold debutextR">:: $CI->db->last_query()</p>';
        $output .= $CI->db->last_query();
        $output .= '</code>';
      }
    }
    
    $output .= '<code class="debugcode"><p class="debugp debugbold debutextR">:: print_r</p><pre class="debugpre">';
    ob_start();
    print_r($data);
    $output .= trim(ob_get_clean());
    $output .= '</pre></code>';
    
    if($add_var_dump)
    {
      $output .= '<code class="debugcode"><p class="debugp debugbold debutextR">:: var_dump</p><pre class="debugpre">';
      ob_start();
      var_dump($data);
      $vardump = trim(ob_get_clean());
      $vardump = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $vardump);
      $output .=  $vardump;
      $output .= '</pre></code>';
    }
    
    $output .= '</div><p class="debugfooter">Vayes Debug Helper © Yahya A. Erturan</p></div></div>';
    $output .= '<div style="clear:both;"></div>';

    if (PHP_SAPI == 'cli')
    {
      echo $varname . ' = ' . PHP_EOL . $output . PHP_EOL . PHP_EOL;
      return;
    }

    echo $output;
    if ($die)
    {
      exit;
    }
  }
}


// ------------------------------------------------------------------------
// ------------------------------------------------------------------------
// ------------------------------------------------------------------------
// ------------------------------------------------------------------------
// ------------------------------------------------------------------------

/* End of file vayes_helper.php */
/* Location: ./application/helpers/vayes_helper.php */
