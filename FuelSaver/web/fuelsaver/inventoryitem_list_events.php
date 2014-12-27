<?php
//BindEvents Method @1-B2CD6E16
function BindEvents()
{
    global $inventoryitem;
    $inventoryitem->Navigator->CCSEvents["BeforeShow"] = "inventoryitem_Navigator_BeforeShow";
}
//End BindEvents Method

//inventoryitem_Navigator_BeforeShow @32-0CC915DD
function inventoryitem_Navigator_BeforeShow(& $sender)
{
    $inventoryitem_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $inventoryitem; //Compatibility
//End inventoryitem_Navigator_BeforeShow

//Hide-Show Component @33-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close inventoryitem_Navigator_BeforeShow @32-6D5B4D53
    return $inventoryitem_Navigator_BeforeShow;
}
//End Close inventoryitem_Navigator_BeforeShow


?>
