<?php
//BindEvents Method @1-0ADE37AF
function BindEvents()
{
    global $contactus1;
    $contactus1->ContactDate->CCSEvents["BeforeShow"] = "contactus1_ContactDate_BeforeShow";
    $contactus1->ContactTime->CCSEvents["BeforeShow"] = "contactus1_ContactTime_BeforeShow";
    $contactus1->CCSEvents["BeforeShow"] = "contactus1_BeforeShow";
}
//End BindEvents Method

//contactus1_ContactDate_BeforeShow @13-5B59DF7B
function contactus1_ContactDate_BeforeShow(& $sender)
{
    $contactus1_ContactDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $contactus1; //Compatibility
//End contactus1_ContactDate_BeforeShow

//Close contactus1_ContactDate_BeforeShow @13-B0FF0483
    return $contactus1_ContactDate_BeforeShow;
}
//End Close contactus1_ContactDate_BeforeShow

//contactus1_ContactTime_BeforeShow @15-6BE2DB2F
function contactus1_ContactTime_BeforeShow(& $sender)
{
    $contactus1_ContactTime_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $contactus1; //Compatibility
//End contactus1_ContactTime_BeforeShow

//Close contactus1_ContactTime_BeforeShow @15-E6AD8A91
    return $contactus1_ContactTime_BeforeShow;
}
//End Close contactus1_ContactTime_BeforeShow

//contactus1_BeforeShow @8-5B8F4B22
function contactus1_BeforeShow(& $sender)
{
    $contactus1_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $contactus1; //Compatibility
//End contactus1_BeforeShow

//Custom Code @24-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
$contactus1->ContactUser->SetValue(CCGetUserID());
//End Custom Code

//Close contactus1_BeforeShow @8-07EB7636
    return $contactus1_BeforeShow;
}
//End Close contactus1_BeforeShow


?>
