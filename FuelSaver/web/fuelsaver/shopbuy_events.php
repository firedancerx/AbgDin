<?php
//BindEvents Method @1-661C258A
function BindEvents()
{
    global $sales;
    $sales->SalesDate->CCSEvents["BeforeShow"] = "sales_SalesDate_BeforeShow";
    $sales->SalesUser->CCSEvents["BeforeShow"] = "sales_SalesUser_BeforeShow";
    $sales->SalesOffice->CCSEvents["BeforeShow"] = "sales_SalesOffice_BeforeShow";
}
//End BindEvents Method

//sales_SalesDate_BeforeShow @13-C74FEFC5
function sales_SalesDate_BeforeShow(& $sender)
{
    $sales_SalesDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $sales; //Compatibility
//End sales_SalesDate_BeforeShow

//Close sales_SalesDate_BeforeShow @13-3E26F3B1
    return $sales_SalesDate_BeforeShow;
}
//End Close sales_SalesDate_BeforeShow

//sales_SalesUser_BeforeShow @15-A10DE521
function sales_SalesUser_BeforeShow(& $sender)
{
    $sales_SalesUser_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $sales; //Compatibility
//End sales_SalesUser_BeforeShow

//Custom Code @27-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
$sales->SalesUser->SetValue(CCGetUserID());
//End Custom Code

//Close sales_SalesUser_BeforeShow @15-31B06879
    return $sales_SalesUser_BeforeShow;
}
//End Close sales_SalesUser_BeforeShow

//sales_SalesOffice_BeforeShow @23-E87BD744
function sales_SalesOffice_BeforeShow(& $sender)
{
    $sales_SalesOffice_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $sales; //Compatibility
//End sales_SalesOffice_BeforeShow

//Custom Code @26-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------

	global $salesOffice;
	global $DBFuelSaver;
	// $DBConnection1->ToSQL(CCGetUserID(),ccsInteger)
	$salesOffice = CCDLookUp("salesoffice","usersalesoffice","id=" . CCGetUserID(), $DBFuelSaver );
	$sales->SalesOffice->SetValue($salesOffice);
//End Custom Code

//Close sales_SalesOffice_BeforeShow @23-77F62280
    return $sales_SalesOffice_BeforeShow;
}
//End Close sales_SalesOffice_BeforeShow


?>
