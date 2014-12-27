<?php
//Include Common Files @1-58C4C901
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "production_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridproduction { //production class @7-D80586DB

//Variables @7-82D9B407

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
    public $Sorter_ProductionDate;
    public $Sorter_InventoryItem;
    public $Sorter_ProductionQty;
    public $Sorter_ProductionRemarks;
    public $Sorter_IsActive;
//End Variables

//Class_Initialize Event @7-E6AA6642
    function clsGridproduction($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "production";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid production";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsproductionDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<BR>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("productionOrder", "");
        $this->SorterDirection = CCGetParam("productionDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "production_maint.php";
        $this->ProductionDate = new clsControl(ccsLabel, "ProductionDate", "ProductionDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("ProductionDate", ccsGet, NULL), $this);
        $this->InventoryItem = new clsControl(ccsLabel, "InventoryItem", "InventoryItem", ccsText, "", CCGetRequestParam("InventoryItem", ccsGet, NULL), $this);
        $this->ProductionQty = new clsControl(ccsLabel, "ProductionQty", "ProductionQty", ccsInteger, "", CCGetRequestParam("ProductionQty", ccsGet, NULL), $this);
        $this->ProductionRemarks = new clsControl(ccsLabel, "ProductionRemarks", "ProductionRemarks", ccsText, "", CCGetRequestParam("ProductionRemarks", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->production_Insert = new clsControl(ccsLink, "production_Insert", "production_Insert", ccsText, "", CCGetRequestParam("production_Insert", ccsGet, NULL), $this);
        $this->production_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->production_Insert->Page = "production_maint.php";
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_ProductionDate = new clsSorter($this->ComponentName, "Sorter_ProductionDate", $FileName, $this);
        $this->Sorter_InventoryItem = new clsSorter($this->ComponentName, "Sorter_InventoryItem", $FileName, $this);
        $this->Sorter_ProductionQty = new clsSorter($this->ComponentName, "Sorter_ProductionQty", $FileName, $this);
        $this->Sorter_ProductionRemarks = new clsSorter($this->ComponentName, "Sorter_ProductionRemarks", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @7-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @7-D3F0755A
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_ProductionItem"] = CCGetFromGet("s_ProductionItem", NULL);
        $this->DataSource->Parameters["urls_ProductionRemarks"] = CCGetFromGet("s_ProductionRemarks", NULL);

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
            $this->ControlsVisible["ProductionDate"] = $this->ProductionDate->Visible;
            $this->ControlsVisible["InventoryItem"] = $this->InventoryItem->Visible;
            $this->ControlsVisible["ProductionQty"] = $this->ProductionQty->Visible;
            $this->ControlsVisible["ProductionRemarks"] = $this->ProductionRemarks->Visible;
            $this->ControlsVisible["IsActive"] = $this->IsActive->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->Id->SetValue($this->DataSource->Id->GetValue());
                $this->Id->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("production_Id"));
                $this->ProductionDate->SetValue($this->DataSource->ProductionDate->GetValue());
                $this->InventoryItem->SetValue($this->DataSource->InventoryItem->GetValue());
                $this->ProductionQty->SetValue($this->DataSource->ProductionQty->GetValue());
                $this->ProductionRemarks->SetValue($this->DataSource->ProductionRemarks->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->ProductionDate->Show();
                $this->InventoryItem->Show();
                $this->ProductionQty->Show();
                $this->ProductionRemarks->Show();
                $this->IsActive->Show();
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
        $this->production_Insert->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_ProductionDate->Show();
        $this->Sorter_InventoryItem->Show();
        $this->Sorter_ProductionQty->Show();
        $this->Sorter_ProductionRemarks->Show();
        $this->Sorter_IsActive->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @7-B400D274
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ProductionDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryItem->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ProductionQty->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ProductionRemarks->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End production Class @7-FCB6E20C

class clsproductionDataSource extends clsDBFuelSaver {  //productionDataSource Class @7-4B30FB6F

//DataSource Variables @7-8AF67D83
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $ProductionDate;
    public $InventoryItem;
    public $ProductionQty;
    public $ProductionRemarks;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @7-72196EBD
    function clsproductionDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid production";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->ProductionDate = new clsField("ProductionDate", ccsDate, $this->DateFormat);
        
        $this->InventoryItem = new clsField("InventoryItem", ccsText, "");
        
        $this->ProductionQty = new clsField("ProductionQty", ccsInteger, "");
        
        $this->ProductionRemarks = new clsField("ProductionRemarks", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @7-9F129B9A
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("production.Id", ""), 
            "Sorter_ProductionDate" => array("ProductionDate", ""), 
            "Sorter_InventoryItem" => array("InventoryItem", ""), 
            "Sorter_ProductionQty" => array("ProductionQty", ""), 
            "Sorter_ProductionRemarks" => array("ProductionRemarks", ""), 
            "Sorter_IsActive" => array("production.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @7-EFE8B4CD
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_ProductionItem", ccsInteger, "", "", $this->Parameters["urls_ProductionItem"], "", false);
        $this->wp->AddParameter("2", "urls_ProductionRemarks", ccsText, "", "", $this->Parameters["urls_ProductionRemarks"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "production.ProductionItem", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "production.ProductionRemarks", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @7-6E947D77
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM production LEFT JOIN inventoryitem ON\n\n" .
        "production.ProductionItem = inventoryitem.Id";
        $this->SQL = "SELECT production.Id AS production_Id, ProductionDate, InventoryItem, ProductionQty, ProductionRemarks, production.IsActive AS production_IsActive \n\n" .
        "FROM production LEFT JOIN inventoryitem ON\n\n" .
        "production.ProductionItem = inventoryitem.Id {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @7-DC5F3706
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("production_Id")));
        $this->ProductionDate->SetDBValue(trim($this->f("ProductionDate")));
        $this->InventoryItem->SetDBValue($this->f("InventoryItem"));
        $this->ProductionQty->SetDBValue(trim($this->f("ProductionQty")));
        $this->ProductionRemarks->SetDBValue($this->f("ProductionRemarks"));
        $this->IsActive->SetDBValue(trim($this->f("production_IsActive")));
    }
//End SetValues Method

} //End productionDataSource Class @7-FCB6E20C

class clsRecordproductionSearch { //productionSearch Class @2-8CA71933

//Variables @2-9E315808

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

//Class_Initialize Event @2-36674466
    function clsRecordproductionSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record productionSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "productionSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_ProductionItem = new clsControl(ccsListBox, "s_ProductionItem", "Production Item", ccsInteger, "", CCGetRequestParam("s_ProductionItem", $Method, NULL), $this);
            $this->s_ProductionItem->DSType = dsTable;
            $this->s_ProductionItem->DataSource = new clsDBFuelSaver();
            $this->s_ProductionItem->ds = & $this->s_ProductionItem->DataSource;
            $this->s_ProductionItem->DataSource->SQL = "SELECT * \n" .
"FROM inventoryitem {SQL_Where} {SQL_OrderBy}";
            list($this->s_ProductionItem->BoundColumn, $this->s_ProductionItem->TextColumn, $this->s_ProductionItem->DBFormat) = array("Id", "InventoryItem", "");
            $this->s_ProductionRemarks = new clsControl(ccsTextBox, "s_ProductionRemarks", "Production Remarks", ccsText, "", CCGetRequestParam("s_ProductionRemarks", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @2-256CC4DC
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_ProductionItem->Validate() && $Validation);
        $Validation = ($this->s_ProductionRemarks->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_ProductionItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ProductionRemarks->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-2726DC91
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_ProductionItem->Errors->Count());
        $errors = ($errors || $this->s_ProductionRemarks->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-388757A6
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
        $Redirect = "production_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "production_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-7B78B86E
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

        $this->s_ProductionItem->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_ProductionItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ProductionRemarks->Errors->ToString());
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
        $this->s_ProductionItem->Show();
        $this->s_ProductionRemarks->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End productionSearch Class @2-FCB6E20C

//Include Page implementation @41-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Include Page implementation @42-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-93F643D1
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
$TemplateFileName = "production_list.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-330A5D70
include_once("./production_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-CF7D26C1
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
$production = new clsGridproduction("", $MainPage);
$productionSearch = new clsRecordproductionSearch("", $MainPage);
$Menu = new clsPanel("Menu", $MainPage);
$Menu->PlaceholderName = "Menu";
$MenuIncludablePage = new clsmenuincludablepage("", "MenuIncludablePage", $MainPage);
$MenuIncludablePage->Initialize();
$Sidebar1 = new clsPanel("Sidebar1", $MainPage);
$Sidebar1->PlaceholderName = "Sidebar1";
$HeaderSidebar = new clsPanel("HeaderSidebar", $MainPage);
$HeaderSidebar->PlaceholderName = "HeaderSidebar";
$headerIncludablePage = new clsheaderIncludablePage("", "headerIncludablePage", $MainPage);
$headerIncludablePage->Initialize();
$MainPage->Head = & $Head;
$MainPage->Content = & $Content;
$MainPage->production = & $production;
$MainPage->productionSearch = & $productionSearch;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("production", $production);
$Content->AddComponent("productionSearch", $productionSearch);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$production->Initialize();
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

//Execute Components @1-EFCB37E8
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$productionSearch->Operation();
//End Execute Components

//Go to destination page @1-2A74E1A3
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($production);
    unset($productionSearch);
    $MenuIncludablePage->Class_Terminate();
    unset($MenuIncludablePage);
    $headerIncludablePage->Class_Terminate();
    unset($headerIncludablePage);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-6235915D
$Head->Show();
$Content->Show();
$Menu->Show();
$Sidebar1->Show();
$HeaderSidebar->Show();
$MasterPage->Tpl->SetVar("Head", $Tpl->GetVar("Panel Head"));
$MasterPage->Tpl->SetVar("Content", $Tpl->GetVar("Panel Content"));
$MasterPage->Tpl->SetVar("Menu", $Tpl->GetVar("Panel Menu"));
$MasterPage->Tpl->SetVar("Sidebar1", $Tpl->GetVar("Panel Sidebar1"));
$MasterPage->Tpl->SetVar("HeaderSidebar", $Tpl->GetVar("Panel HeaderSidebar"));
$MasterPage->Show();
if (!isset($main_block)) $main_block = $MasterPage->HTML;
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-A8EB4016
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($production);
unset($productionSearch);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
