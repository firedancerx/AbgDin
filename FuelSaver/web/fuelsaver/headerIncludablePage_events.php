<?php
// //Events @1-F81417CB

//headerincludablepage_NewRecord1_Label1_BeforeShow @8-B2FA2F0B
function headerincludablepage_NewRecord1_Label1_BeforeShow(& $sender)
{
    $headerincludablepage_NewRecord1_Label1_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $headerincludablepage; //Compatibility
//End headerincludablepage_NewRecord1_Label1_BeforeShow

//Retrieve Value for Control @9-0B7819EA
    $Container->Label1->SetValue(CCGetSession("UserLogin"));
//End Retrieve Value for Control

//Close headerincludablepage_NewRecord1_Label1_BeforeShow @8-5F06CB73
    return $headerincludablepage_NewRecord1_Label1_BeforeShow;
}
//End Close headerincludablepage_NewRecord1_Label1_BeforeShow

//headerincludablepage_BeforeShow @1-E01C526A
function headerincludablepage_BeforeShow(& $sender)
{
    $headerincludablepage_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $headerincludablepage; //Compatibility
//End headerincludablepage_BeforeShow

//Custom Code @12-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
global $headerincludablepage;
if(CCGetUserID())
{
	$headerincludablepage->NewRecord1->loginlnk->Visible=false;
}
else
{
	$headerincludablepage->NewRecord1->logoutlnk->Visible=false;
}
//End Custom Code

//Close headerincludablepage_BeforeShow @1-2CDCE022
    return $headerincludablepage_BeforeShow;
}
//End Close headerincludablepage_BeforeShow


?>
