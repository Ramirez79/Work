<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH. 'controllers/pcidss/cezpdf.php');

class Creport extends Cezpdf
{
    function Creport($p,$o)
    {
      $this->Cezpdf($p,$o);
    }

    function colorit($info)
    {
        if ($info['status'] == "start")
        {
            $r=0;$g=0;$b=0;
            eval($info['p']);
            $this->setColor($r, $g, $b);
        }
        else
        {
            $this->setColor(0,0,0);
        }
    }
}
?>