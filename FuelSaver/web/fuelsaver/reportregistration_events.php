<?php
//BindEvents Method @1-47D7B3F7
function BindEvents()
{
    global $reportregistrations;
    global $Panel2;
    global $Content;
    global $CCSEvents;
    $reportregistrations->reportregistrations_TotalRecords->CCSEvents["BeforeShow"] = "reportregistrations_reportregistrations_TotalRecords_BeforeShow";
    $Panel2->CCSEvents["BeforeShow"] = "Panel2_BeforeShow";
    $Content->CCSEvents["BeforeShow"] = "Content_BeforeShow";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
    $CCSEvents["BeforeOutput"] = "Page_BeforeOutput";
    $CCSEvents["BeforeUnload"] = "Page_BeforeUnload";
}
//End BindEvents Method

//reportregistrations_reportregistrations_TotalRecords_BeforeShow @39-36EF5908
function reportregistrations_reportregistrations_TotalRecords_BeforeShow(& $sender)
{
    $reportregistrations_reportregistrations_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $reportregistrations; //Compatibility
//End reportregistrations_reportregistrations_TotalRecords_BeforeShow

//Retrieve number of records @40-ABE656B4
    $Component->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close reportregistrations_reportregistrations_TotalRecords_BeforeShow @39-A58462B9
    return $reportregistrations_reportregistrations_TotalRecords_BeforeShow;
}
//End Close reportregistrations_reportregistrations_TotalRecords_BeforeShow

//Panel2_BeforeShow @101-96696C3D
function Panel2_BeforeShow(& $sender)
{
    $Panel2_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Panel2; //Compatibility
//End Panel2_BeforeShow

//Close Panel2_BeforeShow @101-AE7F9FB3
    return $Panel2_BeforeShow;
}
//End Close Panel2_BeforeShow

//Content_BeforeShow @8-88732AB8
function Content_BeforeShow(& $sender)
{
    $Content_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Content; //Compatibility
//End Content_BeforeShow

//ContentUpdatePanel1 Page BeforeShow @132-C5D55425
    global $CCSFormFilter;
    if ($CCSFormFilter == "Content") {
        $Component->BlockPrefix = "";
        $Component->BlockSuffix = "";
    } else {
        $Component->BlockPrefix = "<div id=\"Content\">";
        $Component->BlockSuffix = "</div>";
    }
//End ContentUpdatePanel1 Page BeforeShow

//Close Content_BeforeShow @8-FD7AA192
    return $Content_BeforeShow;
}
//End Close Content_BeforeShow

//Page_BeforeInitialize @1-A2E58856
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $reportregistration; //Compatibility
//End Page_BeforeInitialize

//ContentUpdatePanel1 PageBeforeInitialize @132-838C1F38
    if (CCGetFromGet("FormFilter") == "Content" && CCGetFromGet("IsParamsEncoded") != "true") {
        global $TemplateEncoding, $CCSIsParamsEncoded;
        CCConvertDataArrays("UTF-8", $TemplateEncoding);
        $CCSIsParamsEncoded = true;
    }
//End ContentUpdatePanel1 PageBeforeInitialize

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize

//Page_AfterInitialize @1-9A48EFC4
function Page_AfterInitialize(& $sender)
{
    $Page_AfterInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $reportregistration; //Compatibility
//End Page_AfterInitialize

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize

//Page_BeforeShow @1-E8896AED
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $reportregistration; //Compatibility
//End Page_BeforeShow

//ContentUpdatePanel1 Page BeforeShow @132-918281E7
    global $CCSFormFilter;
    if (CCGetFromGet("FormFilter") == "Content") {
        $CCSFormFilter = CCGetFromGet("FormFilter");
        unset($_GET["FormFilter"]);
        if (isset($_GET["IsParamsEncoded"])) unset($_GET["IsParamsEncoded"]);
    }
//End ContentUpdatePanel1 Page BeforeShow

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

//Page_BeforeOutput @1-F906F02C
function Page_BeforeOutput(& $sender)
{
    $Page_BeforeOutput = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $reportregistration; //Compatibility
//End Page_BeforeOutput

//ContentUpdatePanel1 PageBeforeOutput @132-92FAD365
    global $CCSFormFilter, $Tpl, $main_block;
    if ($CCSFormFilter == "Content") {
        $main_block = $_SERVER["REQUEST_URI"] . "|" . $Tpl->getvar("/Panel Content");
    }
//End ContentUpdatePanel1 PageBeforeOutput

//Close Page_BeforeOutput @1-8964C188
    return $Page_BeforeOutput;
}
//End Close Page_BeforeOutput

//Page_BeforeUnload @1-0E3904AA
function Page_BeforeUnload(& $sender)
{
    $Page_BeforeUnload = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $reportregistration; //Compatibility
//End Page_BeforeUnload

//ContentUpdatePanel1 PageBeforeUnload @132-0040E683
    global $Redirect, $CCSFormFilter, $CCSIsParamsEncoded;
    if ($Redirect && $CCSFormFilter == "Content") {
        if ($CCSIsParamsEncoded) $Redirect = CCAddParam($Redirect, "IsParamsEncoded", "true");
        $Redirect = CCAddParam($Redirect, "FormFilter", $CCSFormFilter);
    }
//End ContentUpdatePanel1 PageBeforeUnload

//Close Page_BeforeUnload @1-CFAEC742
    return $Page_BeforeUnload;
}
//End Close Page_BeforeUnload
?>
