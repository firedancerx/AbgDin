<?php
//BindEvents Method @1-493CAFFD
function BindEvents()
{
    global $Label1;
    $Label1->CCSEvents["BeforeShow"] = "Label1_BeforeShow";
}
//End BindEvents Method

//Label1_BeforeShow @8-62EBFD0A
function Label1_BeforeShow(& $sender)
{
    $Label1_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Label1; //Compatibility
//End Label1_BeforeShow

//Retrieve Value for Control @9-1CD4E1B7
    $Container->Label1->SetValue(CCGetSession("UserID"));
//End Retrieve Value for Control

//Close Label1_BeforeShow @8-B48DF954
    return $Label1_BeforeShow;
}
//End Close Label1_BeforeShow


?>
