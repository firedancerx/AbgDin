<?php
//Include Common Files @1-7B063B85
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "inventoryitem_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridinventoryitem { //inventoryitem class @8-E0FB3542

//Variables @8-096439BA

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
    public $Sorter_InventoryCategory1;
    public $Sorter_IsActive;
//End Variables

//Class_Initialize Event @8-9E6DA6D9
    function clsGridinventoryitem($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "inventoryitem";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid inventoryitem";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsinventoryitemDataSource($this);
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
        $this->SorterName = CCGetParam("inventoryitemOrder", "");
        $this->SorterDirection = CCGetParam("inventoryitemDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "inventoryitem_maint.php";
        $this->InventoryItem1 = new clsControl(ccsLabel, "InventoryItem1", "InventoryItem1", ccsText, "", CCGetRequestParam("InventoryItem1", ccsGet, NULL), $this);
        $this->InventoryDescription = new clsControl(ccsLabel, "InventoryDescription", "InventoryDescription", ccsText, "", CCGetRequestParam("InventoryDescription", ccsGet, NULL), $this);
        $this->InventoryCategory1 = new clsControl(ccsLabel, "InventoryCategory1", "InventoryCategory1", ccsText, "", CCGetRequestParam("InventoryCategory1", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->inventoryitem_Insert = new clsControl(ccsLink, "inventoryitem_Insert", "inventoryitem_Insert", ccsText, "", CCGetRequestParam("inventoryitem_Insert", ccsGet, NULL), $this);
        $this->inventoryitem_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->inventoryitem_Insert->Page = "inventoryitem_maint.php";
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_InventoryItem = new clsSorter($this->ComponentName, "Sorter_InventoryItem", $FileName, $this);
        $this->Sorter_InventoryDescription = new clsSorter($this->ComponentName, "Sorter_InventoryDescription", $FileName, $this);
        $this->Sorter_InventoryCategory1 = new clsSorter($this->ComponentName, "Sorter_InventoryCategory1", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @8-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @8-680F8AD3
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_InventoryCategory"] = CCGetFromGet("s_InventoryCategory", NULL);
        $this->DataSource->Parameters["urls_InventoryItem"] = CCGetFromGet("s_InventoryItem", NULL);
        $this->DataSource->Parameters["urls_InventoryDescription"] = CCGetFromGet("s_InventoryDescription", NULL);

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
            $this->ControlsVisible["InventoryItem1"] = $this->InventoryItem1->Visible;
            $this->ControlsVisible["InventoryDescription"] = $this->InventoryDescription->Visible;
            $this->ControlsVisible["InventoryCategory1"] = $this->InventoryCategory1->Visible;
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
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("inventoryitem_Id"));
                $this->InventoryItem1->SetValue($this->DataSource->InventoryItem1->GetValue());
                $this->InventoryDescription->SetValue($this->DataSource->InventoryDescription->GetValue());
                $this->InventoryCategory1->SetValue($this->DataSource->InventoryCategory1->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->InventoryItem1->Show();
                $this->InventoryDescription->Show();
                $this->InventoryCategory1->Show();
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
        $this->inventoryitem_Insert->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_InventoryItem->Show();
        $this->Sorter_InventoryDescription->Show();
        $this->Sorter_InventoryCategory1->Show();
        $this->Sorter_IsActive->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @8-B1605705
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryItem1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryDescription->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryCategory1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End inventoryitem Class @8-FCB6E20C

class clsinventoryitemDataSource extends clsDBFuelSaver {  //inventoryitemDataSource Class @8-B83AE026

//DataSource Variables @8-3BB8CD1E
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $InventoryItem1;
    public $InventoryDescription;
    public $InventoryCategory1;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-64E2662C
    function clsinventoryitemDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid inventoryitem";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->InventoryItem1 = new clsField("InventoryItem1", ccsText, "");
        
        $this->InventoryDescription = new clsField("InventoryDescription", ccsText, "");
        
        $this->InventoryCategory1 = new clsField("InventoryCategory1", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @8-CEDA5EC6
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("inventoryitem.Id", ""), 
            "Sorter_InventoryItem" => array("InventoryItem", ""), 
            "Sorter_InventoryDescription" => array("InventoryDescription", ""), 
            "Sorter_InventoryCategory1" => array("inventorycategory.InventoryCategory", ""), 
            "Sorter_IsActive" => array("inventoryitem.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @8-26B2F3EC
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_InventoryCategory", ccsInteger, "", "", $this->Parameters["urls_InventoryCategory"], "", false);
        $this->wp->AddParameter("2", "urls_InventoryItem", ccsText, "", "", $this->Parameters["urls_InventoryItem"], "", false);
        $this->wp->AddParameter("3", "urls_InventoryDescription", ccsText, "", "", $this->Parameters["urls_InventoryDescription"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "inventoryitem.InventoryCategory", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "inventoryitem.InventoryItem", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "inventoryitem.InventoryDescription", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @8-0CC7E451
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM inventoryitem LEFT JOIN inventorycategory ON\n\n" .
        "inventoryitem.InventoryCategory = inventorycategory.Id";
        $this->SQL = "SELECT inventoryitem.Id AS inventoryitem_Id, InventoryItem, InventoryDescription, inventorycategory.InventoryCategory AS inventorycategory_InventoryCategory,\n\n" .
        "inventoryitem.IsActive AS inventoryitem_IsActive \n\n" .
        "FROM inventoryitem LEFT JOIN inventorycategory ON\n\n" .
        "inventoryitem.InventoryCategory = inventorycategory.Id {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @8-F4C8B8CE
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("inventoryitem_Id")));
        $this->InventoryItem1->SetDBValue($this->f("InventoryItem"));
        $this->InventoryDescription->SetDBValue($this->f("InventoryDescription"));
        $this->InventoryCategory1->SetDBValue($this->f("inventorycategory_InventoryCategory"));
        $this->IsActive->SetDBValue(trim($this->f("inventoryitem_IsActive")));
    }
//End SetValues Method

} //End inventoryitemDataSource Class @8-FCB6E20C

class clsRecordinventoryitemSearch { //inventoryitemSearch Class @2-762BF455

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

//Class_Initialize Event @2-7FB6C003
    function clsRecordinventoryitemSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record inventoryitemSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "inventoryitemSearch";
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
            $this->s_InventoryDescription = new clsControl(ccsTextBox, "s_InventoryDescription", "Inventory Description", ccsText, "", CCGetRequestParam("s_InventoryDescription", $Method, NULL), $this);
            $this->s_InventoryCategory = new clsControl(ccsListBox, "s_InventoryCategory", "Inventory Category", ccsInteger, "", CCGetRequestParam("s_InventoryCategory", $Method, NULL), $this);
            $this->s_InventoryCategory->DSType = dsTable;
            $this->s_InventoryCategory->DataSource = new clsDBFuelSaver();
            $this->s_InventoryCategory->ds = & $this->s_InventoryCategory->DataSource;
            $this->s_InventoryCategory->DataSource->SQL = "SELECT * \n" .
"FROM inventorycategory {SQL_Where} {SQL_OrderBy}";
            list($this->s_InventoryCategory->BoundColumn, $this->s_InventoryCategory->TextColumn, $this->s_InventoryCategory->DBFormat) = array("Id", "InventoryCategory", "");
        }
    }
//End Class_Initialize Event

//Validate Method @2-6A0A8124
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_InventoryItem->Validate() && $Validation);
        $Validation = ($this->s_InventoryDescription->Validate() && $Validation);
        $Validation = ($this->s_InventoryCategory->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_InventoryItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_InventoryDescription->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_InventoryCategory->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-ACE6B235
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_InventoryItem->Errors->Count());
        $errors = ($errors || $this->s_InventoryDescription->Errors->Count());
        $errors = ($errors || $this->s_InventoryCategory->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-4DD73D5E
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
        $Redirect = "inventoryitem_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "inventoryitem_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-FA45C385
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

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_InventoryItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_InventoryDescription->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_InventoryCategory->Errors->ToString());
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
        $this->s_InventoryDescription->Show();
        $this->s_InventoryCategory->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End inventoryitemSearch Class @2-FCB6E20C

//Include Page implementation @40-07AA2166
include_once(RelativePath . "/MenuIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-9AC071FD
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
$TemplateFileName = "inventoryitem_list.html";
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

//Include events file @1-102806C2
include_once("./inventoryitem_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-E75C35CE
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
$inventoryitem = new clsGridinventoryitem("", $MainPage);
$inventoryitemSearch = new clsRecordinventoryitemSearch("", $MainPage);
$Menu = new clsPanel("Menu", $MainPage);
$Menu->PlaceholderName = "Menu";
$MenuIncludablePage = new clsMenuIncludablePage("", "MenuIncludablePage", $MainPage);
$MenuIncludablePage->Initialize();
$Sidebar1 = new clsPanel("Sidebar1", $MainPage);
$Sidebar1->PlaceholderName = "Sidebar1";
$HeaderSidebar = new clsPanel("HeaderSidebar", $MainPage);
$HeaderSidebar->PlaceholderName = "HeaderSidebar";
$headerIncludablePage = new clsheaderIncludablePage("", "headerIncludablePage", $MainPage);
$headerIncludablePage->Initialize();
$MainPage->Head = & $Head;
$MainPage->Content = & $Content;
$MainPage->inventoryitem = & $inventoryitem;
$MainPage->inventoryitemSearch = & $inventoryitemSearch;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("inventoryitem", $inventoryitem);
$Content->AddComponent("inventoryitemSearch", $inventoryitemSearch);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
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

//Execute Components @1-51BB40C7
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$inventoryitemSearch->Operation();
//End Execute Components

//Go to destination page @1-BE252179
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($inventoryitem);
    unset($inventoryitemSearch);
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

//Unload Page @1-FA41D6C0
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($inventoryitem);
unset($inventoryitemSearch);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
