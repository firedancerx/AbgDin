<?php
//BindEvents Method @1-B9FE0880
function BindEvents()
{
    global $sales;
    $sales->SalesDate->CCSEvents["BeforeShow"] = "sales_SalesDate_BeforeShow";
    $sales->SalesTime->CCSEvents["BeforeShow"] = "sales_SalesTime_BeforeShow";
}
//End BindEvents Method

//sales_SalesDate_BeforeShow @9-C74FEFC5
function sales_SalesDate_BeforeShow(& $sender)
{
    $sales_SalesDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $sales; //Compatibility
//End sales_SalesDate_BeforeShow

//Close sales_SalesDate_BeforeShow @9-3E26F3B1
    return $sales_SalesDate_BeforeShow;
}
//End Close sales_SalesDate_BeforeShow

//sales_SalesTime_BeforeShow @11-1FF184F4
function sales_SalesTime_BeforeShow(& $sender)
{
    $sales_SalesTime_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $sales; //Compatibility
//End sales_SalesTime_BeforeShow

//Close sales_SalesTime_BeforeShow @11-68747DA3
    return $sales_SalesTime_BeforeShow;
}
//End Close sales_SalesTime_BeforeShow


?>
