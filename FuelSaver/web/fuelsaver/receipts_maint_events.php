<?php
//BindEvents Method @1-84A843C1
function BindEvents()
{
    global $receipts;
    $receipts->ReceiptDate->CCSEvents["BeforeShow"] = "receipts_ReceiptDate_BeforeShow";
    $receipts->ReceiptTime->CCSEvents["BeforeShow"] = "receipts_ReceiptTime_BeforeShow";
}
//End BindEvents Method

//receipts_ReceiptDate_BeforeShow @10-BD7711EF
function receipts_ReceiptDate_BeforeShow(& $sender)
{
    $receipts_ReceiptDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $receipts; //Compatibility
//End receipts_ReceiptDate_BeforeShow

//Close receipts_ReceiptDate_BeforeShow @10-3B15A768
    return $receipts_ReceiptDate_BeforeShow;
}
//End Close receipts_ReceiptDate_BeforeShow

//receipts_ReceiptTime_BeforeShow @12-746758F8
function receipts_ReceiptTime_BeforeShow(& $sender)
{
    $receipts_ReceiptTime_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $receipts; //Compatibility
//End receipts_ReceiptTime_BeforeShow

//Close receipts_ReceiptTime_BeforeShow @12-6D47297A
    return $receipts_ReceiptTime_BeforeShow;
}
//End Close receipts_ReceiptTime_BeforeShow


?>
