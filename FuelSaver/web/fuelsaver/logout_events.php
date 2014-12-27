<?php
//BindEvents Method @1-D40060DD
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//Page_BeforeShow @1-80240166
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $logout; //Compatibility
//End Page_BeforeShow

//Logout @10-27AC102E
    CCLogoutUser();
    CCSetCookie("FuelSaverLogin", "");
//End Logout

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow


?>
