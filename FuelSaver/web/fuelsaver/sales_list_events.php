<?php
//BindEvents Method @1-51D0AEB1
function BindEvents()
{
    global $sales;
    $sales->Navigator->CCSEvents["BeforeShow"] = "sales_Navigator_BeforeShow";
}
//End BindEvents Method

//sales_Navigator_BeforeShow @52-C8DFAC4F
function sales_Navigator_BeforeShow(& $sender)
{
    $sales_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $sales; //Compatibility
//End sales_Navigator_BeforeShow

//Hide-Show Component @53-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close sales_Navigator_BeforeShow @52-C8906AB1
    return $sales_Navigator_BeforeShow;
}
//End Close sales_Navigator_BeforeShow


?>
