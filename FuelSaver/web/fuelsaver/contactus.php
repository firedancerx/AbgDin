<?php
//Include Common Files @1-D58594E5
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "contactus.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordcontactus1 { //contactus1 Class @8-D57C6D9F

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

//Class_Initialize Event @8-8E2E21BD
    function clsRecordcontactus1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record contactus1/Error";
        $this->DataSource = new clscontactus1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "contactus1";
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
            $this->ContactDate = new clsControl(ccsTextBox, "ContactDate", "Contact Date", ccsDate, array("GeneralDate"), CCGetRequestParam("ContactDate", $Method, NULL), $this);
            $this->ContactDate->Required = true;
            $this->ContactTime = new clsControl(ccsTextBox, "ContactTime", "Contact Time", ccsDate, array("ShortDate"), CCGetRequestParam("ContactTime", $Method, NULL), $this);
            $this->ContactTime->Required = true;
            $this->ContactName = new clsControl(ccsTextBox, "ContactName", "Contact Name", ccsText, "", CCGetRequestParam("ContactName", $Method, NULL), $this);
            $this->ContactName->Required = true;
            $this->ContactEmail = new clsControl(ccsTextBox, "ContactEmail", "Contact Email", ccsText, "", CCGetRequestParam("ContactEmail", $Method, NULL), $this);
            $this->ContactPhone = new clsControl(ccsTextBox, "ContactPhone", "Contact Phone", ccsText, "", CCGetRequestParam("ContactPhone", $Method, NULL), $this);
            $this->ContactUser = new clsControl(ccsListBox, "ContactUser", "Contact User", ccsInteger, "", CCGetRequestParam("ContactUser", $Method, NULL), $this);
            $this->ContactUser->DSType = dsTable;
            $this->ContactUser->DataSource = new clsDBFuelSaver();
            $this->ContactUser->ds = & $this->ContactUser->DataSource;
            $this->ContactUser->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->ContactUser->BoundColumn, $this->ContactUser->TextColumn, $this->ContactUser->DBFormat) = array("Id", "UserId", "");
            $this->ContactSubject = new clsControl(ccsTextBox, "ContactSubject", "Contact Subject", ccsText, "", CCGetRequestParam("ContactSubject", $Method, NULL), $this);
            $this->ContactSubject->Required = true;
            $this->ContactContent = new clsControl(ccsTextArea, "ContactContent", "Contact Content", ccsText, "", CCGetRequestParam("ContactContent", $Method, NULL), $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->ContactDate->Value) && !strlen($this->ContactDate->Value) && $this->ContactDate->Value !== false)
                    $this->ContactDate->SetValue(time());
                if(!is_array($this->ContactTime->Value) && !strlen($this->ContactTime->Value) && $this->ContactTime->Value !== false)
                    $this->ContactTime->SetValue(time());
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

//Validate Method @8-7CFCA9EB
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        if(strlen($this->ContactEmail->GetText()) && !preg_match ("/^[\w\.-]{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]+$/", $this->ContactEmail->GetText())) {
            $this->ContactEmail->Errors->addError($CCSLocales->GetText("CCS_MaskValidation", "Contact Email"));
        }
        $Validation = ($this->ContactDate->Validate() && $Validation);
        $Validation = ($this->ContactTime->Validate() && $Validation);
        $Validation = ($this->ContactName->Validate() && $Validation);
        $Validation = ($this->ContactEmail->Validate() && $Validation);
        $Validation = ($this->ContactPhone->Validate() && $Validation);
        $Validation = ($this->ContactUser->Validate() && $Validation);
        $Validation = ($this->ContactSubject->Validate() && $Validation);
        $Validation = ($this->ContactContent->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->ContactDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactTime->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactName->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactEmail->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactPhone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactUser->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactSubject->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactContent->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @8-3954AE82
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->ContactDate->Errors->Count());
        $errors = ($errors || $this->ContactTime->Errors->Count());
        $errors = ($errors || $this->ContactName->Errors->Count());
        $errors = ($errors || $this->ContactEmail->Errors->Count());
        $errors = ($errors || $this->ContactPhone->Errors->Count());
        $errors = ($errors || $this->ContactUser->Errors->Count());
        $errors = ($errors || $this->ContactSubject->Errors->Count());
        $errors = ($errors || $this->ContactContent->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @8-62657C49
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
        $Redirect = "contactusthankyou.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Cancel") {
            $Redirect = "index.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                $Redirect = "contactusthankyou.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @8-ABD80450
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->ContactDate->SetValue($this->ContactDate->GetValue(true));
        $this->DataSource->ContactTime->SetValue($this->ContactTime->GetValue(true));
        $this->DataSource->ContactName->SetValue($this->ContactName->GetValue(true));
        $this->DataSource->ContactEmail->SetValue($this->ContactEmail->GetValue(true));
        $this->DataSource->ContactPhone->SetValue($this->ContactPhone->GetValue(true));
        $this->DataSource->ContactUser->SetValue($this->ContactUser->GetValue(true));
        $this->DataSource->ContactSubject->SetValue($this->ContactSubject->GetValue(true));
        $this->DataSource->ContactContent->SetValue($this->ContactContent->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//Show Method @8-E1D2C522
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

        $this->ContactUser->Prepare();

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
                    $this->ContactDate->SetValue($this->DataSource->ContactDate->GetValue());
                    $this->ContactTime->SetValue($this->DataSource->ContactTime->GetValue());
                    $this->ContactName->SetValue($this->DataSource->ContactName->GetValue());
                    $this->ContactEmail->SetValue($this->DataSource->ContactEmail->GetValue());
                    $this->ContactPhone->SetValue($this->DataSource->ContactPhone->GetValue());
                    $this->ContactUser->SetValue($this->DataSource->ContactUser->GetValue());
                    $this->ContactSubject->SetValue($this->DataSource->ContactSubject->GetValue());
                    $this->ContactContent->SetValue($this->DataSource->ContactContent->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->ContactDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactTime->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactName->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactEmail->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactPhone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactUser->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactSubject->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactContent->Errors->ToString());
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
        $this->ContactDate->Show();
        $this->ContactTime->Show();
        $this->ContactName->Show();
        $this->ContactEmail->Show();
        $this->ContactPhone->Show();
        $this->ContactUser->Show();
        $this->ContactSubject->Show();
        $this->ContactContent->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End contactus1 Class @8-FCB6E20C

class clscontactus1DataSource extends clsDBFuelSaver {  //contactus1DataSource Class @8-3A2B4628

//DataSource Variables @8-69663F53
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
    public $ContactDate;
    public $ContactTime;
    public $ContactName;
    public $ContactEmail;
    public $ContactPhone;
    public $ContactUser;
    public $ContactSubject;
    public $ContactContent;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-26690933
    function clscontactus1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record contactus1/Error";
        $this->Initialize();
        $this->ContactDate = new clsField("ContactDate", ccsDate, $this->DateFormat);
        
        $this->ContactTime = new clsField("ContactTime", ccsDate, $this->DateFormat);
        
        $this->ContactName = new clsField("ContactName", ccsText, "");
        
        $this->ContactEmail = new clsField("ContactEmail", ccsText, "");
        
        $this->ContactPhone = new clsField("ContactPhone", ccsText, "");
        
        $this->ContactUser = new clsField("ContactUser", ccsInteger, "");
        
        $this->ContactSubject = new clsField("ContactSubject", ccsText, "");
        
        $this->ContactContent = new clsField("ContactContent", ccsText, "");
        

        $this->InsertFields["ContactDate"] = array("Name" => "ContactDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactTime"] = array("Name" => "ContactTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactName"] = array("Name" => "ContactName", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactEmail"] = array("Name" => "ContactEmail", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactPhone"] = array("Name" => "ContactPhone", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactUser"] = array("Name" => "ContactUser", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactSubject"] = array("Name" => "ContactSubject", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactContent"] = array("Name" => "ContactContent", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
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

//Open Method @8-CCEC5A24
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM contactus {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @8-349330C8
    function SetValues()
    {
        $this->ContactDate->SetDBValue(trim($this->f("ContactDate")));
        $this->ContactTime->SetDBValue(trim($this->f("ContactTime")));
        $this->ContactName->SetDBValue($this->f("ContactName"));
        $this->ContactEmail->SetDBValue($this->f("ContactEmail"));
        $this->ContactPhone->SetDBValue($this->f("ContactPhone"));
        $this->ContactUser->SetDBValue(trim($this->f("ContactUser")));
        $this->ContactSubject->SetDBValue($this->f("ContactSubject"));
        $this->ContactContent->SetDBValue($this->f("ContactContent"));
    }
//End SetValues Method

//Insert Method @8-CFC3985B
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["ContactDate"]["Value"] = $this->ContactDate->GetDBValue(true);
        $this->InsertFields["ContactTime"]["Value"] = $this->ContactTime->GetDBValue(true);
        $this->InsertFields["ContactName"]["Value"] = $this->ContactName->GetDBValue(true);
        $this->InsertFields["ContactEmail"]["Value"] = $this->ContactEmail->GetDBValue(true);
        $this->InsertFields["ContactPhone"]["Value"] = $this->ContactPhone->GetDBValue(true);
        $this->InsertFields["ContactUser"]["Value"] = $this->ContactUser->GetDBValue(true);
        $this->InsertFields["ContactSubject"]["Value"] = $this->ContactSubject->GetDBValue(true);
        $this->InsertFields["ContactContent"]["Value"] = $this->ContactContent->GetDBValue(true);
        $this->SQL = CCBuildInsert("contactus", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

} //End contactus1DataSource Class @8-FCB6E20C

//Include Page implementation @25-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Initialize Page @1-01C2C9AD
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
$TemplateFileName = "contactus.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.datepicker.js|js/jquery/datepicker/ccs-date-timepicker.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-7F8EB93B
include_once("./contactus_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-6629CB1C
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
$contactus1 = new clsRecordcontactus1("", $MainPage);
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
$MainPage->contactus1 = & $contactus1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$Content->AddComponent("contactus1", $contactus1);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$contactus1->Initialize();
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

//Execute Components @1-3844C586
$MasterPage->Operations();
$MenuIncludablePage->Operations();
$headerIncludablePage->Operations();
$contactus1->Operation();
//End Execute Components

//Go to destination page @1-9F96971B
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($contactus1);
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

//Unload Page @1-691D02C6
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($contactus1);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($Tpl);
//End Unload Page


?>
