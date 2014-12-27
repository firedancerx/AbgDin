<?php
//BindEvents Method @1-0AFBBB68
function BindEvents()
{
    global $production;
    $production->ProductionDate->CCSEvents["BeforeShow"] = "production_ProductionDate_BeforeShow";
}
//End BindEvents Method

//production_ProductionDate_BeforeShow @8-88E13BFC
function production_ProductionDate_BeforeShow(& $sender)
{
    $production_ProductionDate_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $production; //Compatibility
//End production_ProductionDate_BeforeShow

//Close production_ProductionDate_BeforeShow @8-0046683C
    return $production_ProductionDate_BeforeShow;
}
//End Close production_ProductionDate_BeforeShow


?>
