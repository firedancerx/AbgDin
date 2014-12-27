<?php
//Include Common Files @1-2279A8D9
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "shopbuy.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordsales { //sales Class @8-33F972EF

//Variables @8-9E315808

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

//Class_Initialize Event @8-997CF6D4
    function clsRecordsales($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record sales/Error";
        $this->DataSource = new clssalesDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "sales";
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
            $this->Button_Cancel = new clsButton("Button_Cancel", $Method, $this);
            $this->SalesDate = new clsControl(ccsTextBox, "SalesDate", "Sales Date", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("SalesDate", $Method, NULL), $this);
            $this->SalesDate->Required = true;
            $this->SalesUser = new clsControl(ccsListBox, "SalesUser", "Sales User", ccsInteger, "", CCGetRequestParam("SalesUser", $Method, NULL), $this);
            $this->SalesUser->DSType = dsTable;
            $this->SalesUser->DataSource = new clsDBFuelSaver();
            $this->SalesUser->ds = & $this->SalesUser->DataSource;
            $this->SalesUser->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->SalesUser->BoundColumn, $this->SalesUser->TextColumn, $this->SalesUser->DBFormat) = array("Id", "UserId", "");
            $this->SalesItem = new clsControl(ccsListBox, "SalesItem", "Sales Item", ccsInteger, "", CCGetRequestParam("SalesItem", $Method, NULL), $this);
            $this->SalesItem->DSType = dsTable;
            $this->SalesItem->DataSource = new clsDBFuelSaver();
            $this->SalesItem->ds = & $this->SalesItem->DataSource;
            $this->SalesItem->DataSource->SQL = "SELECT * \n" .
"FROM inventoryitem {SQL_Where} {SQL_OrderBy}";
            list($this->SalesItem->BoundColumn, $this->SalesItem->TextColumn, $this->SalesItem->DBFormat) = array("Id", "InventoryItem", "");
            $this->SalesItem->Required = true;
            $this->SalesQuantity = new clsControl(ccsTextBox, "SalesQuantity", "Sales Quantity", ccsSingle, "", CCGetRequestParam("SalesQuantity", $Method, NULL), $this);
            $this->SalesQuantity->Required = true;
            $this->SalesPrice = new clsControl(ccsTextBox, "SalesPrice", "Sales Price", ccsSingle, "", CCGetRequestParam("SalesPrice", $Method, NULL), $this);
            $this->SalesPrice->Required = true;
            $this->SalesValue = new clsControl(ccsTextBox, "SalesValue", "Sales Value", ccsSingle, "", CCGetRequestParam("SalesValue", $Method, NULL), $this);
            $this->SalesValue->Required = true;
            $this->SalesRemarks = new clsControl(ccsTextArea, "SalesRemarks", "Sales Remarks", ccsText, "", CCGetRequestParam("SalesRemarks", $Method, NULL), $this);
            $this->SalesOffice = new clsControl(ccsListBox, "SalesOffice", "Sales Office", ccsInteger, "", CCGetRequestParam("SalesOffice", $Method, NULL), $this);
            $this->SalesOffice->DSType = dsTable;
            $this->SalesOffice->DataSource = new clsDBFuelSaver();
            $this->SalesOffice->ds = & $this->SalesOffice->DataSource;
            $this->SalesOffice->DataSource->SQL = "SELECT * \n" .
"FROM salesoffices {SQL_Where} {SQL_OrderBy}";
            list($this->SalesOffice->BoundColumn, $this->SalesOffice->TextColumn, $this->SalesOffice->DBFormat) = array("Id", "SalesOffice", "");
            $this->SalesOffice->Required = true;
            $this->IsActive = new clsControl(ccsRadioButton, "IsActive", "Is Active", ccsInteger, "", CCGetRequestParam("IsActive", $Method, NULL), $this);
            $this->IsActive->DSType = dsTable;
            $this->IsActive->DataSource = new clsDBFuelSaver();
            $this->IsActive->ds = & $this->IsActive->DataSource;
            $this->IsActive->DataSource->SQL = "SELECT * \n" .
"FROM  {SQL_Where} {SQL_OrderBy}";
            list($this->IsActive->BoundColumn, $this->IsActive->TextColumn, $this->IsActive->DBFormat) = array("", "", "");
            $this->IsActive->Required = true;
            if(!$this->FormSubmitted) {
                if(!is_array($this->SalesDate->Value) && !strlen($this->SalesDate->Value) && $this->SalesDate->Value !== false)
                    $this->SalesDate->SetValue(time());
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @8-4F76030F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlId"] = CCGetFromGet("Id", NULL);
    }
//End Initialize Method

//Validate Method @8-07FB7936
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->SalesDate->Validate() && $Validation);
        $Validation = ($this->SalesUser->Validate() && $Validation);
        $Validation = ($this->SalesItem->Validate() && $Validation);
        $Validation = ($this->SalesQuantity->Validate() && $Validation);
        $Validation = ($this->SalesPrice->Validate() && $Validation);
        $Validation = ($this->SalesValue->Validate() && $Validation);
        $Validation = ($this->SalesRemarks->Validate() && $Validation);
        $Validation = ($this->SalesOffice->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->SalesDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesUser->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesQuantity->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesPrice->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesValue->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesRemarks->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesOffice->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @8-947BF582
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->SalesDate->Errors->Count());
        $errors = ($errors || $this->SalesUser->Errors->Count());
        $errors = ($errors || $this->SalesItem->Errors->Count());
        $errors = ($errors || $this->SalesQuantity->Errors->Count());
        $errors = ($errors || $this->SalesPrice->Errors->Count());
        $errors = ($errors || $this->SalesValue->Errors->Count());
        $errors = ($errors || $this->SalesRemarks->Errors->Count());
        $errors = ($errors || $this->SalesOffice->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @8-DEFDCF33
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
            $this->PressedButton = "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Cancel->Pressed) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
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

//InsertRow Method @8-F0AAB2D2
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->SalesDate->SetValue($this->SalesDate->GetValue(true));
        $this->DataSource->SalesUser->SetValue($this->SalesUser->GetValue(true));
        $this->DataSource->SalesItem->SetValue($this->SalesItem->GetValue(true));
        $this->DataSource->SalesQuantity->SetValue($this->SalesQuantity->GetValue(true));
        $this->DataSource->SalesPrice->SetValue($this->SalesPrice->GetValue(true));
        $this->DataSource->SalesValue->SetValue($this->SalesValue->GetValue(true));
        $this->DataSource->SalesRemarks->SetValue($this->SalesRemarks->GetValue(true));
        $this->DataSource->SalesOffice->SetValue($this->SalesOffice->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//Show Method @8-797CEB3F
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

        $this->SalesUser->Prepare();
        $this->SalesItem->Prepare();
        $this->SalesOffice->Prepare();
        $this->IsActive->Prepare();

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
                    $this->SalesDate->SetValue($this->DataSource->SalesDate->GetValue());
                    $this->SalesUser->SetValue($this->DataSource->SalesUser->GetValue());
                    $this->SalesItem->SetValue($this->DataSource->SalesItem->GetValue());
                    $this->SalesQuantity->SetValue($this->DataSource->SalesQuantity->GetValue());
                    $this->SalesPrice->SetValue($this->DataSource->SalesPrice->GetValue());
                    $this->SalesValue->SetValue($this->DataSource->SalesValue->GetValue());
                    $this->SalesRemarks->SetValue($this->DataSource->SalesRemarks->GetValue());
                    $this->SalesOffice->SetValue($this->DataSource->SalesOffice->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->SalesDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesUser->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesQuantity->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesPrice->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesValue->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesRemarks->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesOffice->Errors->ToString());
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

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Insert->Show();
        $this->Button_Cancel->Show();
        $this->SalesDate->Show();
        $this->SalesUser->Show();
        $this->SalesItem->Show();
        $this->SalesQuantity->Show();
        $this->SalesPrice->Show();
        $this->SalesValue->Show();
        $this->SalesRemarks->Show();
        $this->SalesOffice->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End sales Class @8-FCB6E20C

class clssalesDataSource extends clsDBFuelSaver {  //salesDataSource Class @8-78DAF30D

//DataSource Variables @8-C3FD51AC
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $wp;
    public $AllParametersSet;

    public $InsertFields = array();

    // Datasource fields
    public $SalesDate;
    public $SalesUser;
    public $SalesItem;
    public $SalesQuantity;
    public $SalesPrice;
    public $SalesValue;
    public $SalesRemarks;
    public $SalesOffice;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-619C476B
    function clssalesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record sales/Error";
        $this->Initialize();
        $this->SalesDate = new clsField("SalesDate", ccsDate, $this->DateFormat);
        
        $this->SalesUser = new clsField("SalesUser", ccsInteger, "");
        
        $this->SalesItem = new clsField("SalesItem", ccsInteger, "");
        
        $this->SalesQuantity = new clsField("SalesQuantity", ccsSingle, "");
        
        $this->SalesPrice = new clsField("SalesPrice", ccsSingle, "");
        
        $this->SalesValue = new clsField("SalesValue", ccsSingle, "");
        
        $this->SalesRemarks = new clsField("SalesRemarks", ccsText, "");
        
        $this->SalesOffice = new clsField("SalesOffice", ccsInteger, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["SalesDate"] = array("Name" => "SalesDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesUser"] = array("Name" => "SalesUser", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesItem"] = array("Name" => "SalesItem", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesQuantity"] = array("Name" => "SalesQuantity", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesPrice"] = array("Name" => "SalesPrice", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesValue"] = array("Name" => "SalesValue", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesRemarks"] = array("Name" => "SalesRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesOffice"] = array("Name" => "SalesOffice", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @8-3A045A2A
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlId", ccsText, "", "", $this->Parameters["urlId"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "Id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @8-F8C585FB
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM sales {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @8-3D05F918
    function SetValues()
    {
        $this->SalesDate->SetDBValue(trim($this->f("SalesDate")));
        $this->SalesUser->SetDBValue(trim($this->f("SalesUser")));
        $this->SalesItem->SetDBValue(trim($this->f("SalesItem")));
        $this->SalesQuantity->SetDBValue(trim($this->f("SalesQuantity")));
        $this->SalesPrice->SetDBValue(trim($this->f("SalesPrice")));
        $this->SalesValue->SetDBValue(trim($this->f("SalesValue")));
        $this->SalesRemarks->SetDBValue($this->f("SalesRemarks"));
        $this->SalesOffice->SetDBValue(trim($this->f("SalesOffice")));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @8-4D0B5308
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["SalesDate"]["Value"] = $this->SalesDate->GetDBValue(true);
        $this->InsertFields["SalesUser"]["Value"] = $this->SalesUser->GetDBValue(true);
        $this->InsertFields["SalesItem"]["Value"] = $this->SalesItem->GetDBValue(true);
        $this->InsertFields["SalesQuantity"]["Value"] = $this->SalesQuantity->GetDBValue(true);
        $this->InsertFields["SalesPrice"]["Value"] = $this->SalesPrice->GetDBValue(true);
        $this->InsertFields["SalesValue"]["Value"] = $this->SalesValue->GetDBValue(true);
        $this->InsertFields["SalesRemarks"]["Value"] = $this->SalesRemarks->GetDBValue(true);
        $this->InsertFields["SalesOffice"]["Value"] = $this->SalesOffice->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("sales", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

} //End salesDataSource Class @8-FCB6E20C

//Include Page implementation @28-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation



//Include Page implementation @7-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Initialize Page @1-646695A4
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
$TemplateFileName = "shopbuy.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.datepicker.js|js/jquery/datepicker/ccs-date-timepicker.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Authenticate User @1-872FD3D7
CCSecurityRedirect("", "");
//End Authenticate User

//Include events file @1-F1AC2595
include_once("./shopbuy_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-80514F29
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
$sales = new clsRecordsales("", $MainPage);
$HeaderSidebar = new clsPanel("HeaderSidebar", $MainPage);
$HeaderSidebar->PlaceholderName = "HeaderSidebar";
$headerIncludablePage = new clsheaderIncludablePage("", "headerIncludablePage", $MainPage);
$headerIncludablePage->Initialize();
$Menu = new clsPanel("Menu", $MainPage);
$Menu->PlaceholderName = "Menu";
$MenuIncludablePage = new clsmenuincludablepage("", "MenuIncludablePage", $MainPage);
$MenuIncludablePage->Initialize();
$MainPage->Head = & $Head;
$MainPage->Content = & $Content;
$MainPage->sales = & $sales;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$Content->AddComponent("sales", $sales);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$sales->Initialize();
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

//Execute Components @1-F7DA5C59
$MasterPage->Operations();
$MenuIncludablePage->Operations();
$headerIncludablePage->Operations();
$sales->Operation();
//End Execute Components

//Go to destination page @1-5E16C00D
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($sales);
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

//Unload Page @1-25932670
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($sales);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($Tpl);
//End Unload Page


?>
