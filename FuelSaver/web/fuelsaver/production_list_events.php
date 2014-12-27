<?php
//BindEvents Method @1-31018F40
function BindEvents()
{
    global $production;
    $production->Navigator->CCSEvents["BeforeShow"] = "production_Navigator_BeforeShow";
}
//End BindEvents Method

//production_Navigator_BeforeShow @33-C0179F13
function production_Navigator_BeforeShow(& $sender)
{
    $production_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $production; //Compatibility
//End production_Navigator_BeforeShow

//Hide-Show Component @34-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close production_Navigator_BeforeShow @33-114A44E1
    return $production_Navigator_BeforeShow;
}
//End Close production_Navigator_BeforeShow


?>
