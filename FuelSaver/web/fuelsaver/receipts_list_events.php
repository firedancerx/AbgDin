<?php
//BindEvents Method @1-883EC92B
function BindEvents()
{
    global $receipts;
    $receipts->Navigator->CCSEvents["BeforeShow"] = "receipts_Navigator_BeforeShow";
}
//End BindEvents Method

//receipts_Navigator_BeforeShow @52-13768E0E
function receipts_Navigator_BeforeShow(& $sender)
{
    $receipts_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $receipts; //Compatibility
//End receipts_Navigator_BeforeShow

//Hide-Show Component @53-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close receipts_Navigator_BeforeShow @52-391E9C73
    return $receipts_Navigator_BeforeShow;
}
//End Close receipts_Navigator_BeforeShow


?>
