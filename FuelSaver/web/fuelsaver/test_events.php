<?php
//BindEvents Method @1-0749079C
function BindEvents()
{
    global $sales;
    $sales->SalesDate->CCSEvents["BeforeShow"] = "sales_SalesDate_BeforeShow";
}
//End BindEvents Method

//sales_SalesDate_BeforeShow @15-C74FEFC5
function sales_SalesDate_BeforeShow(& $sender)
{
    $sales_SalesDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $sales; //Compatibility
//End sales_SalesDate_BeforeShow

//Close sales_SalesDate_BeforeShow @15-3E26F3B1
    return $sales_SalesDate_BeforeShow;
}
//End Close sales_SalesDate_BeforeShow


?>
