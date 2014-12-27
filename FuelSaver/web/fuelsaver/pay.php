<?php
//Include Common Files @1-D61A642A
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "pay.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordreceipts { //receipts Class @8-8C823F0F

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

//Class_Initialize Event @8-26668C04
    function clsRecordreceipts($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record receipts/Error";
        $this->DataSource = new clsreceiptsDataSource($this);
        $this->ds = & $this->DataSource;
        $this->Visible = (CCSecurityAccessCheck("1;2;3;4;5") == "success");
        if($this->Visible)
        {
            $this->ReadAllowed = $this->ReadAllowed && CCUserInGroups(CCGetGroupID(), "2;3;4;5");
            $this->InsertAllowed = CCUserInGroups(CCGetGroupID(), "1;5");
            $this->ComponentName = "receipts";
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
            $this->ReceiptDate = new clsControl(ccsTextBox, "ReceiptDate", "Receipt Date", ccsDate, array("dd", "/", "mm", "/", "yyyy", " ", "HH", ":", "nn", ":", "ss"), CCGetRequestParam("ReceiptDate", $Method, NULL), $this);
            $this->ReceiptDate->Required = true;
            $this->SalesOrder = new clsControl(ccsListBox, "SalesOrder", "Sales Order", ccsInteger, "", CCGetRequestParam("SalesOrder", $Method, NULL), $this);
            $this->SalesOrder->DSType = dsTable;
            $this->SalesOrder->DataSource = new clsDBFuelSaver();
            $this->SalesOrder->ds = & $this->SalesOrder->DataSource;
            $this->SalesOrder->DataSource->SQL = "SELECT * \n" .
"FROM reportsales {SQL_Where} {SQL_OrderBy}";
            list($this->SalesOrder->BoundColumn, $this->SalesOrder->TextColumn, $this->SalesOrder->DBFormat) = array("Id", "SalesSummary", "");
            $this->SalesOrder->Required = true;
            $this->ReceiptAmount = new clsControl(ccsTextBox, "ReceiptAmount", "Receipt Amount", ccsSingle, "", CCGetRequestParam("ReceiptAmount", $Method, NULL), $this);
            $this->ReceiptAmount->Required = true;
            $this->ReferenceId = new clsControl(ccsTextBox, "ReferenceId", "Reference Id", ccsText, "", CCGetRequestParam("ReferenceId", $Method, NULL), $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->ReceiptDate->Value) && !strlen($this->ReceiptDate->Value) && $this->ReceiptDate->Value !== false)
                    $this->ReceiptDate->SetValue(time());
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

//Validate Method @8-1A9BFB14
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->ReceiptDate->Validate() && $Validation);
        $Validation = ($this->SalesOrder->Validate() && $Validation);
        $Validation = ($this->ReceiptAmount->Validate() && $Validation);
        $Validation = ($this->ReferenceId->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->ReceiptDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesOrder->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReceiptAmount->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReferenceId->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @8-6B8DB4AE
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->ReceiptDate->Errors->Count());
        $errors = ($errors || $this->SalesOrder->Errors->Count());
        $errors = ($errors || $this->ReceiptAmount->Errors->Count());
        $errors = ($errors || $this->ReferenceId->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @8-CB727567
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
            $Redirect = "paycancel.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert" && $this->InsertAllowed) {
                $Redirect = "paythankyou.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @8-2E3E77A0
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->ReceiptDate->SetValue($this->ReceiptDate->GetValue(true));
        $this->DataSource->SalesOrder->SetValue($this->SalesOrder->GetValue(true));
        $this->DataSource->ReceiptAmount->SetValue($this->ReceiptAmount->GetValue(true));
        $this->DataSource->ReferenceId->SetValue($this->ReferenceId->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//Show Method @8-D1AB7D59
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

        $this->SalesOrder->Prepare();

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
                    $this->ReceiptDate->SetValue($this->DataSource->ReceiptDate->GetValue());
                    $this->SalesOrder->SetValue($this->DataSource->SalesOrder->GetValue());
                    $this->ReceiptAmount->SetValue($this->DataSource->ReceiptAmount->GetValue());
                    $this->ReferenceId->SetValue($this->DataSource->ReferenceId->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->ReceiptDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesOrder->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReceiptAmount->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReferenceId->Errors->ToString());
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
        $this->ReceiptDate->Show();
        $this->SalesOrder->Show();
        $this->ReceiptAmount->Show();
        $this->ReferenceId->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End receipts Class @8-FCB6E20C

class clsreceiptsDataSource extends clsDBFuelSaver {  //receiptsDataSource Class @8-AA038C14

//DataSource Variables @8-12E57485
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
    public $ReceiptDate;
    public $SalesOrder;
    public $ReceiptAmount;
    public $ReferenceId;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-2867D917
    function clsreceiptsDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record receipts/Error";
        $this->Initialize();
        $this->ReceiptDate = new clsField("ReceiptDate", ccsDate, $this->DateFormat);
        
        $this->SalesOrder = new clsField("SalesOrder", ccsInteger, "");
        
        $this->ReceiptAmount = new clsField("ReceiptAmount", ccsSingle, "");
        
        $this->ReferenceId = new clsField("ReferenceId", ccsText, "");
        

        $this->InsertFields["ReceiptDate"] = array("Name" => "ReceiptDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesOrder"] = array("Name" => "SalesOrder", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ReceiptAmount"] = array("Name" => "ReceiptAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["ReferenceId"] = array("Name" => "ReferenceId", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
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

//Open Method @8-078594BC
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM receipts {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @8-C30DDC14
    function SetValues()
    {
        $this->ReceiptDate->SetDBValue(trim($this->f("ReceiptDate")));
        $this->SalesOrder->SetDBValue(trim($this->f("SalesOrder")));
        $this->ReceiptAmount->SetDBValue(trim($this->f("ReceiptAmount")));
        $this->ReferenceId->SetDBValue($this->f("ReferenceId"));
    }
//End SetValues Method

//Insert Method @8-35F97173
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["ReceiptDate"]["Value"] = $this->ReceiptDate->GetDBValue(true);
        $this->InsertFields["SalesOrder"]["Value"] = $this->SalesOrder->GetDBValue(true);
        $this->InsertFields["ReceiptAmount"]["Value"] = $this->ReceiptAmount->GetDBValue(true);
        $this->InsertFields["ReferenceId"]["Value"] = $this->ReferenceId->GetDBValue(true);
        $this->SQL = CCBuildInsert("receipts", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

} //End receiptsDataSource Class @8-FCB6E20C

//Include Page implementation @25-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Initialize Page @1-B05D10EF
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
$TemplateFileName = "pay.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.datepicker.js|js/jquery/datepicker/ccs-date-timepicker.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-69475384
include_once("./pay_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-28DB953C
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
$receipts = new clsRecordreceipts("", $MainPage);
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
$MainPage->receipts = & $receipts;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$Content->AddComponent("receipts", $receipts);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$receipts->Initialize();
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

//Execute Components @1-259C65E7
$MasterPage->Operations();
$MenuIncludablePage->Operations();
$headerIncludablePage->Operations();
$receipts->Operation();
//End Execute Components

//Go to destination page @1-35A60687
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($receipts);
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

//Unload Page @1-F7F3C24B
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($receipts);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($Tpl);
//End Unload Page


?>
