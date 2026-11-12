<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

function displayEntityWithActions($data, $entity, $actions)
{
    global $g_pargs;
    $defn = $g_pargs['nsEntitydef']->getDefinition($entity);
    if(isset($actions))
        $g_pargs['pdf']->ezText("\n<b>" . $defn['title'] . " Actions : </b>". $actions);
    else// for safe objects kinds, if actions are not there
        $g_pargs['pdf']->ezText("\n<b>" . $defn['title'] . " : </b>");
    if($data != null)
    {
        if(is_array($data))
        {
            displayEntity($data, $entity);
        }
        else
             $g_pargs['pdf']->ezText($data); // just display the text passed , like xml validation
    }
    else
    { // to display no entity message
        displayEntity($data, $entity);
    }
}

function displayEntity($data, $entity)
{
    global $g_pargs;
    $defn = $g_pargs['nsEntitydef']->getDefinition($entity);
    if(!isset($defn))
        return;
    if(!isset($data) or sizeof($data) <= 0)
    {
        if(isset($defn['NoEntityMsg']))
            $g_pargs['pdf']->ezText("\n" . $defn['NoEntityMsg'], $g_pargs['fontSize']);
    }
    else
    {
        if(isset($defn['map']))
            mapValuesToPDF($data, $defn['map']);
        if(isset($defn['title']))
                $g_pargs['pdf']->ezText( "\n<b><i>" . $defn['title'] . "</i></b>\n", $g_pargs['fontSize']);
        $tableOptions = array("xPos"=>"70","xOrientation"=>"right","fontSize"=> $g_pargs['fontSize'],
                            "shadeCol" => array(0.9686,0.9686,0.9686), // shadeCol as per Monitorying page
                            "cols"=> (isset( $defn['col_options'])?  $defn['col_options'] : array()));
        if(isset($defn['table_width']))
            $tableOptions["width"] = $defn['table_width'];
        $g_pargs['pdf']->ezTable($data, $defn['cols'], '', $tableOptions);
//        $pdf->ezText( "\n",9);
    }
}

function mapValuesToPDF(&$data, $map)
{
    if(!isset($map))
        return $data;
    foreach ($data as &$entity)
    {
        $entityElements = array_keys($map);
        foreach ($entityElements as $entityElement)
        {
            $mapForEntity = $map[$entityElement];
            if(!isset($entity[$entityElement]))
                continue;
            if(!is_array($entity[$entityElement])) // normal case
            {
                if(isset($mapForEntity[$entity[$entityElement]]))
                    $entity[$entityElement] = $mapForEntity[$entity[$entityElement]];
            }
            else //  used in safe object action ["block", "remove"] will become "Block Remove".
            {
                $consolidatedValue = "";
                foreach ($entity[$entityElement] as $arrayElement)
                {
                    if(isset($mapForEntity[$arrayElement]))
                        $consolidatedValue .= $mapForEntity[$arrayElement] . " ";
                }
                $entity[$entityElement] = $consolidatedValue;
            }
        }
    }
}
?>