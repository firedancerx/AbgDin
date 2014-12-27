<?php
//Page_BeforeInitialize @1-CC84FABD
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $shop; //Compatibility
//End Page_BeforeInitialize

//Custom Code @7-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
$Redirect = "shopbuy.php";
//End Custom Code

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
