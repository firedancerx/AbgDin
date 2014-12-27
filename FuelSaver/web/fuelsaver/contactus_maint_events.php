<?php
//BindEvents Method @1-39F767F0
function BindEvents()
{
    global $contactus;
    $contactus->ContactDate->CCSEvents["BeforeShow"] = "contactus_ContactDate_BeforeShow";
    $contactus->ContactTime->CCSEvents["BeforeShow"] = "contactus_ContactTime_BeforeShow";
    $contactus->ContactReplyDate->CCSEvents["BeforeShow"] = "contactus_ContactReplyDate_BeforeShow";
    $contactus->ContactReplyTime->CCSEvents["BeforeShow"] = "contactus_ContactReplyTime_BeforeShow";
}
//End BindEvents Method

//contactus_ContactDate_BeforeShow @8-53E3B3A1
function contactus_ContactDate_BeforeShow(& $sender)
{
    $contactus_ContactDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $contactus; //Compatibility
//End contactus_ContactDate_BeforeShow

//Close contactus_ContactDate_BeforeShow @8-D5205911
    return $contactus_ContactDate_BeforeShow;
}
//End Close contactus_ContactDate_BeforeShow

//contactus_ContactTime_BeforeShow @10-963F7B9F
function contactus_ContactTime_BeforeShow(& $sender)
{
    $contactus_ContactTime_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $contactus; //Compatibility
//End contactus_ContactTime_BeforeShow

//Close contactus_ContactTime_BeforeShow @10-8372D703
    return $contactus_ContactTime_BeforeShow;
}
//End Close contactus_ContactTime_BeforeShow

//contactus_ContactReplyDate_BeforeShow @18-5E0BF2AC
function contactus_ContactReplyDate_BeforeShow(& $sender)
{
    $contactus_ContactReplyDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $contactus; //Compatibility
//End contactus_ContactReplyDate_BeforeShow

//Close contactus_ContactReplyDate_BeforeShow @18-67E60051
    return $contactus_ContactReplyDate_BeforeShow;
}
//End Close contactus_ContactReplyDate_BeforeShow

//contactus_ContactReplyTime_BeforeShow @20-780B8AB0
function contactus_ContactReplyTime_BeforeShow(& $sender)
{
    $contactus_ContactReplyTime_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $contactus; //Compatibility
//End contactus_ContactReplyTime_BeforeShow

//Close contactus_ContactReplyTime_BeforeShow @20-31B48E43
    return $contactus_ContactReplyTime_BeforeShow;
}
//End Close contactus_ContactReplyTime_BeforeShow


?>
