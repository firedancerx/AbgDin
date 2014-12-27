<?php
//Include Common Files @1-AEF6BDA2
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "products.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridproductlist { //productlist class @9-7AE1B5A3

//Variables @9-B15BA3CB

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
    public $Sorter_Id;
    public $Sorter_InventoryItem;
    public $Sorter_InventoryDescription;
    public $Sorter_InventoryCategory;
    public $Sorter_InventoryCategoryDescription;
    public $Sorter_IsActive;
    public $Sorter_unitprice;
//End Variables

//Class_Initialize Event @9-D7218812
    function clsGridproductlist($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "productlist";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid productlist";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsproductlistDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<BR>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("productlistOrder", "");
        $this->SorterDirection = CCGetParam("productlistDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "";
        $this->InventoryItem = new clsControl(ccsLabel, "InventoryItem", "InventoryItem", ccsText, "", CCGetRequestParam("InventoryItem", ccsGet, NULL), $this);
        $this->InventoryDescription = new clsControl(ccsLabel, "InventoryDescription", "InventoryDescription", ccsText, "", CCGetRequestParam("InventoryDescription", ccsGet, NULL), $this);
        $this->InventoryCategory = new clsControl(ccsLabel, "InventoryCategory", "InventoryCategory", ccsInteger, "", CCGetRequestParam("InventoryCategory", ccsGet, NULL), $this);
        $this->InventoryCategoryDescription = new clsControl(ccsLabel, "InventoryCategoryDescription", "InventoryCategoryDescription", ccsText, "", CCGetRequestParam("InventoryCategoryDescription", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->unitprice = new clsControl(ccsLabel, "unitprice", "unitprice", ccsSingle, "", CCGetRequestParam("unitprice", ccsGet, NULL), $this);
        $this->productlist_Insert = new clsControl(ccsLink, "productlist_Insert", "productlist_Insert", ccsText, "", CCGetRequestParam("productlist_Insert", ccsGet, NULL), $this);
        $this->productlist_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->productlist_Insert->Page = "products.php";
        $this->productlist_TotalRecords = new clsControl(ccsLabel, "productlist_TotalRecords", "productlist_TotalRecords", ccsText, "", CCGetRequestParam("productlist_TotalRecords", ccsGet, NULL), $this);
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_InventoryItem = new clsSorter($this->ComponentName, "Sorter_InventoryItem", $FileName, $this);
        $this->Sorter_InventoryDescription = new clsSorter($this->ComponentName, "Sorter_InventoryDescription", $FileName, $this);
        $this->Sorter_InventoryCategory = new clsSorter($this->ComponentName, "Sorter_InventoryCategory", $FileName, $this);
        $this->Sorter_InventoryCategoryDescription = new clsSorter($this->ComponentName, "Sorter_InventoryCategoryDescription", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Sorter_unitprice = new clsSorter($this->ComponentName, "Sorter_unitprice", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @9-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @9-FD0A852F
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_InventoryItem"] = CCGetFromGet("s_InventoryItem", NULL);
        $this->DataSource->Parameters["urls_InventoryCategory"] = CCGetFromGet("s_InventoryCategory", NULL);
        $this->DataSource->Parameters["urls_IsActive"] = CCGetFromGet("s_IsActive", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["Id"] = $this->Id->Visible;
            $this->ControlsVisible["InventoryItem"] = $this->InventoryItem->Visible;
            $this->ControlsVisible["InventoryDescription"] = $this->InventoryDescription->Visible;
            $this->ControlsVisible["InventoryCategory"] = $this->InventoryCategory->Visible;
            $this->ControlsVisible["InventoryCategoryDescription"] = $this->InventoryCategoryDescription->Visible;
            $this->ControlsVisible["IsActive"] = $this->IsActive->Visible;
            $this->ControlsVisible["unitprice"] = $this->unitprice->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->Id->SetValue($this->DataSource->Id->GetValue());
                $this->Id->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("Id"));
                $this->InventoryItem->SetValue($this->DataSource->InventoryItem->GetValue());
                $this->InventoryDescription->SetValue($this->DataSource->InventoryDescription->GetValue());
                $this->InventoryCategory->SetValue($this->DataSource->InventoryCategory->GetValue());
                $this->InventoryCategoryDescription->SetValue($this->DataSource->InventoryCategoryDescription->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->unitprice->SetValue($this->DataSource->unitprice->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->InventoryItem->Show();
                $this->InventoryDescription->Show();
                $this->InventoryCategory->Show();
                $this->InventoryCategoryDescription->Show();
                $this->IsActive->Show();
                $this->unitprice->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if (($this->Navigator->TotalPages <= 1 && $this->Navigator->PageNumber == 1) || $this->Navigator->PageSize == "") {
            $this->Navigator->Visible = false;
        }
        $this->productlist_Insert->Show();
        $this->productlist_TotalRecords->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_InventoryItem->Show();
        $this->Sorter_InventoryDescription->Show();
        $this->Sorter_InventoryCategory->Show();
        $this->Sorter_InventoryCategoryDescription->Show();
        $this->Sorter_IsActive->Show();
        $this->Sorter_unitprice->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @9-7F393C13
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryItem->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryDescription->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryCategory->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryCategoryDescription->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->unitprice->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End productlist Class @9-FCB6E20C

class clsproductlistDataSource extends clsDBFuelSaver {  //productlistDataSource Class @9-729AE818

//DataSource Variables @9-61FCD313
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $InventoryItem;
    public $InventoryDescription;
    public $InventoryCategory;
    public $InventoryCategoryDescription;
    public $IsActive;
    public $unitprice;
//End DataSource Variables

//DataSourceClass_Initialize Event @9-17EA030F
    function clsproductlistDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid productlist";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->InventoryItem = new clsField("InventoryItem", ccsText, "");
        
        $this->InventoryDescription = new clsField("InventoryDescription", ccsText, "");
        
        $this->InventoryCategory = new clsField("InventoryCategory", ccsInteger, "");
        
        $this->InventoryCategoryDescription = new clsField("InventoryCategoryDescription", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        
        $this->unitprice = new clsField("unitprice", ccsSingle, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @9-16FD88D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("Id", ""), 
            "Sorter_InventoryItem" => array("InventoryItem", ""), 
            "Sorter_InventoryDescription" => array("InventoryDescription", ""), 
            "Sorter_InventoryCategory" => array("InventoryCategory", ""), 
            "Sorter_InventoryCategoryDescription" => array("InventoryCategoryDescription", ""), 
            "Sorter_IsActive" => array("IsActive", ""), 
            "Sorter_unitprice" => array("unitprice", "")));
    }
//End SetOrder Method

//Prepare Method @9-50960523
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_InventoryItem", ccsText, "", "", $this->Parameters["urls_InventoryItem"], "", false);
        $this->wp->AddParameter("2", "urls_InventoryCategory", ccsInteger, "", "", $this->Parameters["urls_InventoryCategory"], "", false);
        $this->wp->AddParameter("3", "urls_IsActive", ccsInteger, "", "", $this->Parameters["urls_IsActive"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "InventoryItem", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "InventoryCategory", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "IsActive", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @9-59F8DC59
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM productlist";
        $this->SQL = "SELECT Id, InventoryItem, InventoryDescription, InventoryCategory, InventoryCategoryDescription, IsActive, unitprice \n\n" .
        "FROM productlist {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @9-3E426CFD
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->InventoryItem->SetDBValue($this->f("InventoryItem"));
        $this->InventoryDescription->SetDBValue($this->f("InventoryDescription"));
        $this->InventoryCategory->SetDBValue(trim($this->f("InventoryCategory")));
        $this->InventoryCategoryDescription->SetDBValue($this->f("InventoryCategoryDescription"));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
        $this->unitprice->SetDBValue(trim($this->f("unitprice")));
    }
//End SetValues Method

} //End productlistDataSource Class @9-FCB6E20C

class clsRecordproductlistSearch { //productlistSearch Class @44-8A8132FF

//Variables @44-9E315808

    // Public variables
    public $ComponentType = "Record";
    public $ComponentName;
    public $Parent;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormEnctype;
    public $Visible;
    public $IsEmpty;

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode      = false;
    public $ds;
    public $DataSource;
    public $ValidatingControls;
    public $Controls;
    public $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @44-0D0BFE6C
    function clsRecordproductlistSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record productlistSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "productlistSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_InventoryItem = new clsControl(ccsTextBox, "s_InventoryItem", "Inventory Item", ccsText, "", CCGetRequestParam("s_InventoryItem", $Method, NULL), $this);
            $this->s_InventoryCategory = new clsControl(ccsListBox, "s_InventoryCategory", "Inventory Category", ccsInteger, "", CCGetRequestParam("s_InventoryCategory", $Method, NULL), $this);
            $this->s_InventoryCategory->DSType = dsTable;
            $this->s_InventoryCategory->DataSource = new clsDBFuelSaver();
            $this->s_InventoryCategory->ds = & $this->s_InventoryCategory->DataSource;
            $this->s_InventoryCategory->DataSource->SQL = "SELECT * \n" .
"FROM inventorycategory {SQL_Where} {SQL_OrderBy}";
            list($this->s_InventoryCategory->BoundColumn, $this->s_InventoryCategory->TextColumn, $this->s_InventoryCategory->DBFormat) = array("Id", "InventoryCategory", "");
            $this->s_IsActive = new clsControl(ccsTextBox, "s_IsActive", "Is Active", ccsInteger, "", CCGetRequestParam("s_IsActive", $Method, NULL), $this);
            $this->productlistPageSize = new clsControl(ccsListBox, "productlistPageSize", "productlistPageSize", ccsText, "", CCGetRequestParam("productlistPageSize", $Method, NULL), $this);
            $this->productlistPageSize->DSType = dsListOfValues;
            $this->productlistPageSize->Values = array(array("", "Select Value"), array("5", "5"), array("10", "10"), array("25", "25"), array("100", "100"));
        }
    }
//End Class_Initialize Event

//Validate Method @44-CBA5DD5D
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_InventoryItem->Validate() && $Validation);
        $Validation = ($this->s_InventoryCategory->Validate() && $Validation);
        $Validation = ($this->s_IsActive->Validate() && $Validation);
        $Validation = ($this->productlistPageSize->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_InventoryItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_InventoryCategory->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_IsActive->Errors->Count() == 0);
        $Validation =  $Validation && ($this->productlistPageSize->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @44-92B1B36C
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_InventoryItem->Errors->Count());
        $errors = ($errors || $this->s_InventoryCategory->Errors->Count());
        $errors = ($errors || $this->s_IsActive->Errors->Count());
        $errors = ($errors || $this->productlistPageSize->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @44-D0CC4FA1
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if($this->Button_DoSearch->Pressed) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "products.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "products.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @44-BCEEC946
    function Show()
    {
        global $CCSUseAmp;
        $Tpl = CCGetTemplate($this);
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->s_InventoryCategory->Prepare();
        $this->productlistPageSize->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_InventoryItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_InventoryCategory->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_IsActive->Errors->ToString());
            $Error = ComposeStrings($Error, $this->productlistPageSize->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_DoSearch->Show();
        $this->s_InventoryItem->Show();
        $this->s_InventoryCategory->Show();
        $this->s_IsActive->Show();
        $this->productlistPageSize->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End productlistSearch Class @44-FCB6E20C

class clsRecordinventoryitem { //inventoryitem Class @52-9FFCE989

//Variables @52-9E315808

    // Public variables
    public $ComponentType = "Record";
    public $ComponentName;
    public $Parent;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormEnctype;
    public $Visible;
    public $IsEmpty;

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode      = false;
    public $ds;
    public $DataSource;
    public $ValidatingControls;
    public $Controls;
    public $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @52-99969B78
    function clsRecordinventoryitem($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record inventoryitem/Error";
        $this->DataSource = new clsinventoryitemDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "inventoryitem";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = new clsButton("Button_Cancel", $Method, $this);
            $this->InventoryItem1 = new clsControl(ccsTextBox, "InventoryItem1", "Inventory Item", ccsText, "", CCGetRequestParam("InventoryItem1", $Method, NULL), $this);
            $this->InventoryItem1->Required = true;
            $this->InventoryDescription = new clsControl(ccsTextArea, "InventoryDescription", "Inventory Description", ccsText, "", CCGetRequestParam("InventoryDescription", $Method, NULL), $this);
            $this->InventoryCategory = new clsControl(ccsListBox, "InventoryCategory", "Inventory Category", ccsInteger, "", CCGetRequestParam("InventoryCategory", $Method, NULL), $this);
            $this->InventoryCategory->DSType = dsTable;
            $this->InventoryCategory->DataSource = new clsDBFuelSaver();
            $this->InventoryCategory->ds = & $this->InventoryCategory->DataSource;
            $this->InventoryCategory->DataSource->SQL = "SELECT * \n" .
"FROM inventorycategory {SQL_Where} {SQL_OrderBy}";
            list($this->InventoryCategory->BoundColumn, $this->InventoryCategory->TextColumn, $this->InventoryCategory->DBFormat) = array("Id", "InventoryCategory", "");
            $this->InventoryCategory->Required = true;
            $this->unitprice = new clsControl(ccsTextBox, "unitprice", "Unit Price", ccsSingle, "", CCGetRequestParam("unitprice", $Method, NULL), $this);
            $this->unitprice->Required = true;
            $this->IsActive = new clsControl(ccsCheckBox, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", $Method, NULL), $this);
            $this->IsActive->CheckedValue = $this->IsActive->GetParsedValue(1);
            $this->IsActive->UncheckedValue = $this->IsActive->GetParsedValue(0);
            if(!$this->FormSubmitted) {
                if(!is_array($this->IsActive->Value) && !strlen($this->IsActive->Value) && $this->IsActive->Value !== false)
                    $this->IsActive->SetValue(false);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @52-4F76030F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlId"] = CCGetFromGet("Id", NULL);
    }
//End Initialize Method

//Validate Method @52-7F97FBA7
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->InventoryItem1->Validate() && $Validation);
        $Validation = ($this->InventoryDescription->Validate() && $Validation);
        $Validation = ($this->InventoryCategory->Validate() && $Validation);
        $Validation = ($this->unitprice->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->InventoryItem1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->InventoryDescription->Errors->Count() == 0);
        $Validation =  $Validation && ($this->InventoryCategory->Errors->Count() == 0);
        $Validation =  $Validation && ($this->unitprice->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @52-153056BD
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->InventoryItem1->Errors->Count());
        $errors = ($errors || $this->InventoryDescription->Errors->Count());
        $errors = ($errors || $this->InventoryCategory->Errors->Count());
        $errors = ($errors || $this->unitprice->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @52-288F0419
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Delete->Pressed) {
                $this->PressedButton = "Button_Delete";
            } else if($this->Button_Cancel->Pressed) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//InsertRow Method @52-39D58576
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->InventoryItem1->SetValue($this->InventoryItem1->GetValue(true));
        $this->DataSource->InventoryDescription->SetValue($this->InventoryDescription->GetValue(true));
        $this->DataSource->InventoryCategory->SetValue($this->InventoryCategory->GetValue(true));
        $this->DataSource->unitprice->SetValue($this->unitprice->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @52-7047EAE4
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->InventoryItem1->SetValue($this->InventoryItem1->GetValue(true));
        $this->DataSource->InventoryDescription->SetValue($this->InventoryDescription->GetValue(true));
        $this->DataSource->InventoryCategory->SetValue($this->InventoryCategory->GetValue(true));
        $this->DataSource->unitprice->SetValue($this->unitprice->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @52-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @52-DCADA3FD
    function Show()
    {
        global $CCSUseAmp;
        $Tpl = CCGetTemplate($this);
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->InventoryCategory->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->InventoryItem1->SetValue($this->DataSource->InventoryItem1->GetValue());
                    $this->InventoryDescription->SetValue($this->DataSource->InventoryDescription->GetValue());
                    $this->InventoryCategory->SetValue($this->DataSource->InventoryCategory->GetValue());
                    $this->unitprice->SetValue($this->DataSource->unitprice->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->InventoryItem1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->InventoryDescription->Errors->ToString());
            $Error = ComposeStrings($Error, $this->InventoryCategory->Errors->ToString());
            $Error = ComposeStrings($Error, $this->unitprice->Errors->ToString());
            $Error = ComposeStrings($Error, $this->IsActive->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->InventoryItem1->Show();
        $this->InventoryDescription->Show();
        $this->InventoryCategory->Show();
        $this->unitprice->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End inventoryitem Class @52-FCB6E20C

class clsinventoryitemDataSource extends clsDBFuelSaver {  //inventoryitemDataSource Class @52-B83AE026

//DataSource Variables @52-516C5249
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $UpdateParameters;
    public $DeleteParameters;
    public $wp;
    public $AllParametersSet;

    public $InsertFields = array();
    public $UpdateFields = array();

    // Datasource fields
    public $InventoryItem1;
    public $InventoryDescription;
    public $InventoryCategory;
    public $unitprice;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @52-77CBD54E
    function clsinventoryitemDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record inventoryitem/Error";
        $this->Initialize();
        $this->InventoryItem1 = new clsField("InventoryItem1", ccsText, "");
        
        $this->InventoryDescription = new clsField("InventoryDescription", ccsText, "");
        
        $this->InventoryCategory = new clsField("InventoryCategory", ccsInteger, "");
        
        $this->unitprice = new clsField("unitprice", ccsSingle, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["InventoryItem"] = array("Name" => "InventoryItem", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["InventoryDescription"] = array("Name" => "InventoryDescription", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["InventoryCategory"] = array("Name" => "InventoryCategory", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["unitprice"] = array("Name" => "unitprice", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
        $this->UpdateFields["InventoryItem"] = array("Name" => "InventoryItem", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["InventoryDescription"] = array("Name" => "InventoryDescription", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["InventoryCategory"] = array("Name" => "InventoryCategory", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["unitprice"] = array("Name" => "unitprice", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @52-F755E9A7
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlId", ccsInteger, "", "", $this->Parameters["urlId"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "Id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @52-2E59A835
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM inventoryitem {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @52-5BD3F1D9
    function SetValues()
    {
        $this->InventoryItem1->SetDBValue($this->f("InventoryItem"));
        $this->InventoryDescription->SetDBValue($this->f("InventoryDescription"));
        $this->InventoryCategory->SetDBValue(trim($this->f("InventoryCategory")));
        $this->unitprice->SetDBValue(trim($this->f("unitprice")));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @52-C1C845A3
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["InventoryItem"]["Value"] = $this->InventoryItem1->GetDBValue(true);
        $this->InsertFields["InventoryDescription"]["Value"] = $this->InventoryDescription->GetDBValue(true);
        $this->InsertFields["InventoryCategory"]["Value"] = $this->InventoryCategory->GetDBValue(true);
        $this->InsertFields["unitprice"]["Value"] = $this->unitprice->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("inventoryitem", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @52-2A62D101
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["InventoryItem"]["Value"] = $this->InventoryItem1->GetDBValue(true);
        $this->UpdateFields["InventoryDescription"]["Value"] = $this->InventoryDescription->GetDBValue(true);
        $this->UpdateFields["InventoryCategory"]["Value"] = $this->InventoryCategory->GetDBValue(true);
        $this->UpdateFields["unitprice"]["Value"] = $this->unitprice->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildUpdate("inventoryitem", $this->UpdateFields, $this);
        $this->SQL .= strlen($this->Where) ? " WHERE " . $this->Where : $this->Where;
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @52-D164B767
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM inventoryitem";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End inventoryitemDataSource Class @52-FCB6E20C

//Include Page implementation @125-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-0FF451CE
include_once(RelativePath . "/./menuincludablepage.php");
//End Include Page implementation

//Initialize Page @1-59E41B02
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";
$PathToCurrentMasterPage = "";
$TemplatePathValue = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";
$MasterPage = null;
$TemplateSource = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "products.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.position.js|js/jquery/ui/jquery.ui.menu.js|js/jquery/ui/jquery.ui.autocomplete.js|js/jquery/autocomplete/ccs-autocomplete.js|js/jquery/ui/jquery.ui.mouse.js|js/jquery/ui/jquery.ui.draggable.js|js/jquery/ui/jquery.ui.resizable.js|js/jquery/ui/jquery.ui.button.js|js/jquery/ui/jquery.ui.dialog.js|js/jquery/dialog/ccs-dialog.js|js/jquery/updatepanel/ccs-update-panel.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-7A9001EA
include_once("./products_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-F66C885A
$DBFuelSaver = new clsDBFuelSaver();
$MainPage->Connections["FuelSaver"] = & $DBFuelSaver;
$Attributes = new clsAttributes("page:");
$Attributes->SetValue("pathToRoot", $PathToRoot);
$MainPage->Attributes = & $Attributes;

// Controls
$MasterPage = new clsMasterPage("/Designs/" . $CCProjectDesign . "/", "MasterPage", $MainPage);
$MasterPage->Attributes = $Attributes;
$MasterPage->Initialize();
$Head = new clsPanel("Head", $MainPage);
$Head->PlaceholderName = "Head";
$Content = new clsPanel("Content", $MainPage);
$Content->PlaceholderName = "Content";
$Panel1 = new clsPanel("Panel1", $MainPage);
$Panel1->GenerateDiv = true;
$Panel1->PanelId = "ContentPanel1";
$productlist = new clsGridproductlist("", $MainPage);
$productlistSearch = new clsRecordproductlistSearch("", $MainPage);
$Panel2 = new clsPanel("Panel2", $MainPage);
$Panel2->GenerateDiv = true;
$Panel2->PanelId = "ContentPanel1Panel2";
$inventoryitem = new clsRecordinventoryitem("", $MainPage);
$HeaderSidebar = new clsPanel("HeaderSidebar", $MainPage);
$HeaderSidebar->PlaceholderName = "HeaderSidebar";
$headerIncludablePage = new clsheaderIncludablePage("", "headerIncludablePage", $MainPage);
$headerIncludablePage->Initialize();
$Menu = new clsPanel("Menu", $MainPage);
$Menu->PlaceholderName = "Menu";
$MenuIncludablePage = new clsmenuincludablepage("./", "MenuIncludablePage", $MainPage);
$MenuIncludablePage->Initialize();
$MainPage->Head = & $Head;
$MainPage->Content = & $Content;
$MainPage->Panel1 = & $Panel1;
$MainPage->productlist = & $productlist;
$MainPage->productlistSearch = & $productlistSearch;
$MainPage->Panel2 = & $Panel2;
$MainPage->inventoryitem = & $inventoryitem;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$Content->AddComponent("Panel1", $Panel1);
$Panel1->AddComponent("productlist", $productlist);
$Panel1->AddComponent("productlistSearch", $productlistSearch);
$Panel1->AddComponent("Panel2", $Panel2);
$Panel2->AddComponent("inventoryitem", $inventoryitem);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$productlist->Initialize();
$inventoryitem->Initialize();
$ScriptIncludes = "";
$SList = explode("|", $Scripts);
foreach ($SList as $Script) {
    if ($Script != "") $ScriptIncludes = $ScriptIncludes . "<script src=\"" . $PathToRoot . $Script . "\" type=\"text/javascript\"></script>\n";
}
$Attributes->SetValue("scriptIncludes", $ScriptIncludes);

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-380C9A2B
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
if (strlen($TemplateSource)) {
    $Tpl->LoadTemplateFromStr($TemplateSource, $BlockToParse, "CP1252");
} else {
    $Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252");
}
$Tpl->SetVar("CCS_PathToRoot", $PathToRoot);
$Tpl->SetVar("CCS_PathToMasterPage", RelativePath . $PathToCurrentMasterPage);
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-2DD99BA1
$MasterPage->Operations();
$MenuIncludablePage->Operations();
$headerIncludablePage->Operations();
$inventoryitem->Operation();
$productlistSearch->Operation();
//End Execute Components

//Go to destination page @1-7A0990E3
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($productlist);
    unset($productlistSearch);
    unset($inventoryitem);
    $headerIncludablePage->Class_Terminate();
    unset($headerIncludablePage);
    $MenuIncludablePage->Class_Terminate();
    unset($MenuIncludablePage);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-BB236964
$Head->Show();
$Content->Show();
$HeaderSidebar->Show();
$Menu->Show();
$MasterPage->Tpl->SetVar("Head", $Tpl->GetVar("Panel Head"));
$MasterPage->Tpl->SetVar("Content", $Tpl->GetVar("Panel Content"));
$MasterPage->Tpl->SetVar("HeaderSidebar", $Tpl->GetVar("Panel HeaderSidebar"));
$MasterPage->Tpl->SetVar("Menu", $Tpl->GetVar("Panel Menu"));
$MasterPage->Show();
if (!isset($main_block)) $main_block = $MasterPage->HTML;
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-425057D1
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($productlist);
unset($productlistSearch);
unset($inventoryitem);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($Tpl);
//End Unload Page


?>
