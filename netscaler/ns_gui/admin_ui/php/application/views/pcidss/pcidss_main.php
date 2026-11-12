<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

include_once("pcidss_common.php");

global $g_pargs;
$g_pargs = $global_pargs;
$pdf = $g_pargs['pdf'];
$nsEntitydef = $g_pargs['nsEntitydef'];
$fontSize = $g_pargs['fontSize'];

//-----------------------------------------------------------
// load up the document content
$data=file(APPPATH. "views/pcidss/pcidss_main_content.txt");
$textOptions = array('justification'=>'full');
$collecting=0;
$code='';

foreach ($data as $line){
  // go through each line, showing it as required, if it is surrounded by '<>' then
  // assume that it is a title
  $line=chop($line);
  if (strlen($line) && $line[0]=='#'){
    // comment, or new page request
    switch($line){
      case '#NP':
        $pdf->ezNewPage();
        break;
      case '#X':
        $collecting=1;
        break;
      case '#x':
        $pdf->saveState();
        eval($code);
        $pdf->restoreState();
        $code='';
        $collecting=0;
        break;
    }
  } else if ($collecting){
    $code.=$line;
  } else if (((strlen($line)>1 && $line[1]=='<') ) && $line[strlen($line)-1]=='>' && is_numeric($line[0])) {
    // then this is a title
    switch($line[0]){
      case '1':
        $tmp = substr($line,2,strlen($line)-3);
        $pdf->ezText($tmp,12,array('justification'=>'left'));
        break;
      default:
        break;
    }
  } else {
      $origLeft = null;
      while(strlen($line)>1 && $line[0]=="\t")
      {
          if(!isset($textOptions))
            $textOptions= array('left'=>20);
          else
          {
                if(!isset($textOptions['left']))
                    $textOptions['left'] = 20;
                else
                {
                    if(!isset($origLeft))
                        $origLeft = $textOptions['left'];
                    if(is_numeric($textOptions['left']))
                        $textOptions['left'] += 20;
                    else
                        $textOptions['left'] = 20;
                }
          }
          $line = substr($line,1);
      }
    // then this is just text
    // the ezpdf function will take care of all of the wrapping etc.
    $pdf->ezText($line,$fontSize,$textOptions);
    if(!isset($origLeft))
        $textOptions['left'] = $origLeft;
    else
        $textOptions['left'] = null;

  }
}
?>