<?php
//BindEvents Method @1-CB472A84
function BindEvents()
{
    global $inventorydeliveries;
    $inventorydeliveries->DeliveryDate->CCSEvents["BeforeShow"] = "inventorydeliveries_DeliveryDate_BeforeShow";
    $inventorydeliveries->DeliveryTime->CCSEvents["BeforeShow"] = "inventorydeliveries_DeliveryTime_BeforeShow";
}
//End BindEvents Method

//inventorydeliveries_DeliveryDate_BeforeShow @9-BA0098EB
function inventorydeliveries_DeliveryDate_BeforeShow(& $sender)
{
    $inventorydeliveries_DeliveryDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $inventorydeliveries; //Compatibility
//End inventorydeliveries_DeliveryDate_BeforeShow

//Close inventorydeliveries_DeliveryDate_BeforeShow @9-665302BE
    return $inventorydeliveries_DeliveryDate_BeforeShow;
}
//End Close inventorydeliveries_DeliveryDate_BeforeShow

//inventorydeliveries_DeliveryTime_BeforeShow @11-17F79EBD
function inventorydeliveries_DeliveryTime_BeforeShow(& $sender)
{
    $inventorydeliveries_DeliveryTime_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $inventorydeliveries; //Compatibility
//End inventorydeliveries_DeliveryTime_BeforeShow

//Close inventorydeliveries_DeliveryTime_BeforeShow @11-30018CAC
    return $inventorydeliveries_DeliveryTime_BeforeShow;
}
//End Close inventorydeliveries_DeliveryTime_BeforeShow


?>
