<?php
// //Events @1-F81417CB

//userincudable_BeforeShow @1-E9015A44
function userincudable_BeforeShow(& $sender)
{
    $userincudable_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $userincudable; //Compatibility
//End userincudable_BeforeShow

//Hide-Show Component @2-B1E938E8
    $Parameter1 = CCGetSession("UserID");
    $Parameter2 = Null0;
    if (0 == CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Container->Button1->Visible = false;
//End Hide-Show Component

//Hide-Show Component @3-D42121AB
    $Parameter1 = CCGetSession("UserID");
    $Parameter2 = 0;
    if (0 == CCCompareValues($Parameter1, $Parameter2, ccsInteger))
        $Container->Button2->Visible = false;
//End Hide-Show Component

//Close userincudable_BeforeShow @1-5520F343
    return $userincudable_BeforeShow;
}
//End Close userincudable_BeforeShow


?>
