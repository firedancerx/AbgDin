<?php
//BindEvents Method @1-2E11EF79
function BindEvents()
{
    global $receipts;
    $receipts->ReceiptDate->CCSEvents["BeforeShow"] = "receipts_ReceiptDate_BeforeShow";
    $receipts->SalesOrder->ds->CCSEvents["BeforeBuildSelect"] = "receipts_SalesOrder_ds_BeforeBuildSelect";
}
//End BindEvents Method

//receipts_ReceiptDate_BeforeShow @13-BD7711EF
function receipts_ReceiptDate_BeforeShow(& $sender)
{
    $receipts_ReceiptDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $receipts; //Compatibility
//End receipts_ReceiptDate_BeforeShow

//Close receipts_ReceiptDate_BeforeShow @13-3B15A768
    return $receipts_ReceiptDate_BeforeShow;
}
//End Close receipts_ReceiptDate_BeforeShow

//receipts_SalesOrder_ds_BeforeBuildSelect @15-14382A26
function receipts_SalesOrder_ds_BeforeBuildSelect(& $sender)
{
    $receipts_SalesOrder_ds_BeforeBuildSelect = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $receipts; //Compatibility
//End receipts_SalesOrder_ds_BeforeBuildSelect

//Custom Code @19-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
$receipts->SalesOrder->DataSource->Where = " SalesUser = " . CCGetUserID();
//End Custom Code

//Close receipts_SalesOrder_ds_BeforeBuildSelect @15-8F43159A
    return $receipts_SalesOrder_ds_BeforeBuildSelect;
}
//End Close receipts_SalesOrder_ds_BeforeBuildSelect


?>
