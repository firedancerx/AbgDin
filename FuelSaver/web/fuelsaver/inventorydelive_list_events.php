<?php
//BindEvents Method @1-F1551EEE
function BindEvents()
{
    global $inventorydeliveries;
    $inventorydeliveries->Navigator->CCSEvents["BeforeShow"] = "inventorydeliveries_Navigator_BeforeShow";
}
//End BindEvents Method

//inventorydeliveries_Navigator_BeforeShow @43-01BE975B
function inventorydeliveries_Navigator_BeforeShow(& $sender)
{
    $inventorydeliveries_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $inventorydeliveries; //Compatibility
//End inventorydeliveries_Navigator_BeforeShow

//Hide-Show Component @44-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close inventorydeliveries_Navigator_BeforeShow @43-261B21C0
    return $inventorydeliveries_Navigator_BeforeShow;
}
//End Close inventorydeliveries_Navigator_BeforeShow


?>
