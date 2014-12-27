<?php
//BindEvents Method @1-9F9FC5A4
function BindEvents()
{
    global $contactus;
    $contactus->Navigator->CCSEvents["BeforeShow"] = "contactus_Navigator_BeforeShow";
}
//End BindEvents Method

//contactus_Navigator_BeforeShow @71-42A7F727
function contactus_Navigator_BeforeShow(& $sender)
{
    $contactus_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $contactus; //Compatibility
//End contactus_Navigator_BeforeShow

//Hide-Show Component @72-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close contactus_Navigator_BeforeShow @71-96E9A1D3
    return $contactus_Navigator_BeforeShow;
}
//End Close contactus_Navigator_BeforeShow


?>
