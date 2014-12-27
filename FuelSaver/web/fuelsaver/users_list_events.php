<?php
//BindEvents Method @1-04C69594
function BindEvents()
{
    global $users;
    $users->Navigator->CCSEvents["BeforeShow"] = "users_Navigator_BeforeShow";
}
//End BindEvents Method

//users_Navigator_BeforeShow @69-7EB217D9
function users_Navigator_BeforeShow(& $sender)
{
    $users_Navigator_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $users; //Compatibility
//End users_Navigator_BeforeShow

//Hide-Show Component @70-0DB41530
    $Parameter1 = $Container->DataSource->PageCount();
    $Parameter2 = 2;
    if (((is_array($Parameter1) || strlen($Parameter1)) && (is_array($Parameter2) || strlen($Parameter2))) && 0 >  CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Component->Visible = false;
//End Hide-Show Component

//Close users_Navigator_BeforeShow @69-6A33A9C5
    return $users_Navigator_BeforeShow;
}
//End Close users_Navigator_BeforeShow


?>
