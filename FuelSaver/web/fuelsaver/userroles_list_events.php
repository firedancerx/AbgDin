<?php
//BindEvents Method @1-1338E123
function BindEvents()
{
    global $userroles;
    $userroles->Navigator->CCSEvents["BeforeShow"] = "userroles_Navigator_BeforeShow";
}
//End BindEvents Method

//userroles_Navigator_BeforeShow @20-A66C7774
function userroles_Navigator_BeforeShow(& $sender)
{
    $userroles_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $userroles; //Compatibility
//End userroles_Navigator_BeforeShow

//Hide-Show Component @21-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close userroles_Navigator_BeforeShow @20-90B961CB
    return $userroles_Navigator_BeforeShow;
}
//End Close userroles_Navigator_BeforeShow


?>
