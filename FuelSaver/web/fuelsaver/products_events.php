<?php
//BindEvents Method @1-91C12737
function BindEvents()
{
    global $productlist;
    global $Panel2;
    global $Panel1;
    global $CCSEvents;
    $productlist->productlist_TotalRecords->CCSEvents["BeforeShow"] = "productlist_productlist_TotalRecords_BeforeShow";
    $Panel2->CCSEvents["BeforeShow"] = "Panel2_BeforeShow";
    $Panel1->CCSEvents["BeforeShow"] = "Panel1_BeforeShow";
    $CCSEvents["AfterInitialize"] = "Page_AfterInitialize";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
    $CCSEvents["BeforeOutput"] = "Page_BeforeOutput";
    $CCSEvents["BeforeUnload"] = "Page_BeforeUnload";
}
//End BindEvents Method

//productlist_productlist_TotalRecords_BeforeShow @23-F336AF05
function productlist_productlist_TotalRecords_BeforeShow(& $sender)
{
    $productlist_productlist_TotalRecords_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $productlist; //Compatibility
//End productlist_productlist_TotalRecords_BeforeShow

//Retrieve number of records @24-ABE656B4
    $Component->SetValue($Container->DataSource->RecordsCount);
//End Retrieve number of records

//Close productlist_productlist_TotalRecords_BeforeShow @23-97FA8DC2
    return $productlist_productlist_TotalRecords_BeforeShow;
}
//End Close productlist_productlist_TotalRecords_BeforeShow

//Panel2_BeforeShow @51-96696C3D
function Panel2_BeforeShow(& $sender)
{
    $Panel2_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Panel2; //Compatibility
//End Panel2_BeforeShow

//Close Panel2_BeforeShow @51-AE7F9FB3
    return $Panel2_BeforeShow;
}
//End Close Panel2_BeforeShow

//Panel1_BeforeShow @8-AAD8AF72
function Panel1_BeforeShow(& $sender)
{
    $Panel1_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Panel1; //Compatibility
//End Panel1_BeforeShow

//ContentPanel1UpdatePanel1 Page BeforeShow @68-6EC41673
    global $CCSFormFilter;
    if ($CCSFormFilter == "ContentPanel1") {
        $Component->BlockPrefix = "";
        $Component->BlockSuffix = "";
    } else {
        $Component->BlockPrefix = "<div id=\"ContentPanel1\">";
        $Component->BlockSuffix = "</div>";
    }
//End ContentPanel1UpdatePanel1 Page BeforeShow

//Close Panel1_BeforeShow @8-D21EBA68
    return $Panel1_BeforeShow;
}
//End Close Panel1_BeforeShow

//Page_BeforeInitialize @1-4093B159
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $products; //Compatibility
//End Page_BeforeInitialize

//ContentPanel1UpdatePanel1 PageBeforeInitialize @68-53A8147E
    if (CCGetFromGet("FormFilter") == "ContentPanel1" && CCGetFromGet("IsParamsEncoded") != "true") {
        global $TemplateEncoding, $CCSIsParamsEncoded;
        CCConvertDataArrays("UTF-8", $TemplateEncoding);
        $CCSIsParamsEncoded = true;
    }
//End ContentPanel1UpdatePanel1 PageBeforeInitialize

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize

//Page_AfterInitialize @1-2F3A6B18
function Page_AfterInitialize(& $sender)
{
    $Page_AfterInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $products; //Compatibility
//End Page_AfterInitialize

//Close Page_AfterInitialize @1-379D319D
    return $Page_AfterInitialize;
}
//End Close Page_AfterInitialize

//Page_BeforeShow @1-E6555CAD
function Page_BeforeShow(& $sender)
{
    $Page_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $products; //Compatibility
//End Page_BeforeShow

//ContentPanel1UpdatePanel1 Page BeforeShow @68-D40DD5AC
    global $CCSFormFilter;
    if (CCGetFromGet("FormFilter") == "ContentPanel1") {
        $CCSFormFilter = CCGetFromGet("FormFilter");
        unset($_GET["FormFilter"]);
        if (isset($_GET["IsParamsEncoded"])) unset($_GET["IsParamsEncoded"]);
    }
//End ContentPanel1UpdatePanel1 Page BeforeShow

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

//Page_BeforeOutput @1-B3C70198
function Page_BeforeOutput(& $sender)
{
    $Page_BeforeOutput = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $products; //Compatibility
//End Page_BeforeOutput

//ContentPanel1UpdatePanel1 PageBeforeOutput @68-7157F368
    global $CCSFormFilter, $Tpl, $main_block;
    if ($CCSFormFilter == "ContentPanel1") {
        $main_block = $_SERVER["REQUEST_URI"] . "|" . $Tpl->getvar("/Panel Content/Panel Panel1");
    }
//End ContentPanel1UpdatePanel1 PageBeforeOutput

//Close Page_BeforeOutput @1-8964C188
    return $Page_BeforeOutput;
}
//End Close Page_BeforeOutput

//Page_BeforeUnload @1-0DD5796F
function Page_BeforeUnload(& $sender)
{
    $Page_BeforeUnload = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $products; //Compatibility
//End Page_BeforeUnload

//ContentPanel1UpdatePanel1 PageBeforeUnload @68-8D13D6DF
    global $Redirect, $CCSFormFilter, $CCSIsParamsEncoded;
    if ($Redirect && $CCSFormFilter == "ContentPanel1") {
        if ($CCSIsParamsEncoded) $Redirect = CCAddParam($Redirect, "IsParamsEncoded", "true");
        $Redirect = CCAddParam($Redirect, "FormFilter", $CCSFormFilter);
    }
//End ContentPanel1UpdatePanel1 PageBeforeUnload

//Close Page_BeforeUnload @1-CFAEC742
    return $Page_BeforeUnload;
}
//End Close Page_BeforeUnload
?>
