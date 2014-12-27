<?php
//Include Common Files @1-B40C5B85
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "production_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordproduction { //production Class @2-FAB9AB10

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

//Class_Initialize Event @2-CE2E413F
    function clsRecordproduction($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record production/Error";
        $this->DataSource = new clsproductionDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "production";
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
            $this->Id = new clsControl(ccsTextBox, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", $Method, NULL), $this);
            $this->Id->Required = true;
            $this->ProductionDate = new clsControl(ccsTextBox, "ProductionDate", "Production Date", ccsDate, array("ShortDate"), CCGetRequestParam("ProductionDate", $Method, NULL), $this);
            $this->ProductionDate->Required = true;
            $this->ProductionItem = new clsControl(ccsListBox, "ProductionItem", "Production Item", ccsInteger, "", CCGetRequestParam("ProductionItem", $Method, NULL), $this);
            $this->ProductionItem->DSType = dsTable;
            $this->ProductionItem->DataSource = new clsDBFuelSaver();
            $this->ProductionItem->ds = & $this->ProductionItem->DataSource;
            $this->ProductionItem->DataSource->SQL = "SELECT * \n" .
"FROM inventoryitem {SQL_Where} {SQL_OrderBy}";
            list($this->ProductionItem->BoundColumn, $this->ProductionItem->TextColumn, $this->ProductionItem->DBFormat) = array("Id", "InventoryItem", "");
            $this->ProductionItem->Required = true;
            $this->ProductionQty = new clsControl(ccsTextBox, "ProductionQty", "Production Qty", ccsInteger, "", CCGetRequestParam("ProductionQty", $Method, NULL), $this);
            $this->ProductionQty->Required = true;
            $this->ProductionRemarks = new clsControl(ccsTextBox, "ProductionRemarks", "Production Remarks", ccsText, "", CCGetRequestParam("ProductionRemarks", $Method, NULL), $this);
            $this->IsActive = new clsControl(ccsTextBox, "IsActive", "Is Active", ccsInteger, "", CCGetRequestParam("IsActive", $Method, NULL), $this);
            $this->IsActive->Required = true;
        }
    }
//End Class_Initialize Event

//Initialize Method @2-4F76030F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlId"] = CCGetFromGet("Id", NULL);
    }
//End Initialize Method

//Validate Method @2-D84AD949
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->Id->Validate() && $Validation);
        $Validation = ($this->ProductionDate->Validate() && $Validation);
        $Validation = ($this->ProductionItem->Validate() && $Validation);
        $Validation = ($this->ProductionQty->Validate() && $Validation);
        $Validation = ($this->ProductionRemarks->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->Id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ProductionDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ProductionItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ProductionQty->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ProductionRemarks->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-E305F398
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Id->Errors->Count());
        $errors = ($errors || $this->ProductionDate->Errors->Count());
        $errors = ($errors || $this->ProductionItem->Errors->Count());
        $errors = ($errors || $this->ProductionQty->Errors->Count());
        $errors = ($errors || $this->ProductionRemarks->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-DEEDD466
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
            }
        }
        $Redirect = "production_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
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

//InsertRow Method @2-D67D8873
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->ProductionDate->SetValue($this->ProductionDate->GetValue(true));
        $this->DataSource->ProductionItem->SetValue($this->ProductionItem->GetValue(true));
        $this->DataSource->ProductionQty->SetValue($this->ProductionQty->GetValue(true));
        $this->DataSource->ProductionRemarks->SetValue($this->ProductionRemarks->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-D3A27144
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->ProductionDate->SetValue($this->ProductionDate->GetValue(true));
        $this->DataSource->ProductionItem->SetValue($this->ProductionItem->GetValue(true));
        $this->DataSource->ProductionQty->SetValue($this->ProductionQty->GetValue(true));
        $this->DataSource->ProductionRemarks->SetValue($this->ProductionRemarks->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @2-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @2-C99888F3
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

        $this->ProductionItem->Prepare();

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
                    $this->Id->SetValue($this->DataSource->Id->GetValue());
                    $this->ProductionDate->SetValue($this->DataSource->ProductionDate->GetValue());
                    $this->ProductionItem->SetValue($this->DataSource->ProductionItem->GetValue());
                    $this->ProductionQty->SetValue($this->DataSource->ProductionQty->GetValue());
                    $this->ProductionRemarks->SetValue($this->DataSource->ProductionRemarks->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->Id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ProductionDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ProductionItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ProductionQty->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ProductionRemarks->Errors->ToString());
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
        $this->Id->Show();
        $this->ProductionDate->Show();
        $this->ProductionItem->Show();
        $this->ProductionQty->Show();
        $this->ProductionRemarks->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End production Class @2-FCB6E20C

class clsproductionDataSource extends clsDBFuelSaver {  //productionDataSource Class @2-4B30FB6F

//DataSource Variables @2-E726EB47
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
    public $Id;
    public $ProductionDate;
    public $ProductionItem;
    public $ProductionQty;
    public $ProductionRemarks;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-309D7F99
    function clsproductionDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record production/Error";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->ProductionDate = new clsField("ProductionDate", ccsDate, $this->DateFormat);
        
        $this->ProductionItem = new clsField("ProductionItem", ccsInteger, "");
        
        $this->ProductionQty = new clsField("ProductionQty", ccsInteger, "");
        
        $this->ProductionRemarks = new clsField("ProductionRemarks", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ProductionDate"] = array("Name" => "ProductionDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["ProductionItem"] = array("Name" => "ProductionItem", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ProductionQty"] = array("Name" => "ProductionQty", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ProductionRemarks"] = array("Name" => "ProductionRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ProductionDate"] = array("Name" => "ProductionDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["ProductionItem"] = array("Name" => "ProductionItem", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ProductionQty"] = array("Name" => "ProductionQty", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ProductionRemarks"] = array("Name" => "ProductionRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-F755E9A7
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

//Open Method @2-83AD2C07
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM production {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-DF071E81
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->ProductionDate->SetDBValue(trim($this->f("ProductionDate")));
        $this->ProductionItem->SetDBValue(trim($this->f("ProductionItem")));
        $this->ProductionQty->SetDBValue(trim($this->f("ProductionQty")));
        $this->ProductionRemarks->SetDBValue($this->f("ProductionRemarks"));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @2-71A2E55D
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->InsertFields["ProductionDate"]["Value"] = $this->ProductionDate->GetDBValue(true);
        $this->InsertFields["ProductionItem"]["Value"] = $this->ProductionItem->GetDBValue(true);
        $this->InsertFields["ProductionQty"]["Value"] = $this->ProductionQty->GetDBValue(true);
        $this->InsertFields["ProductionRemarks"]["Value"] = $this->ProductionRemarks->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("production", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-CC234C97
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->UpdateFields["ProductionDate"]["Value"] = $this->ProductionDate->GetDBValue(true);
        $this->UpdateFields["ProductionItem"]["Value"] = $this->ProductionItem->GetDBValue(true);
        $this->UpdateFields["ProductionQty"]["Value"] = $this->ProductionQty->GetDBValue(true);
        $this->UpdateFields["ProductionRemarks"]["Value"] = $this->ProductionRemarks->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildUpdate("production", $this->UpdateFields, $this);
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

//Delete Method @2-170AE8F0
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM production";
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

} //End productionDataSource Class @2-FCB6E20C

//Include Page implementation @20-07AA2166
include_once(RelativePath . "/MenuIncludablePage.php");
//End Include Page implementation

//Include Page implementation @21-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-2813B382
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
$TemplateFileName = "production_maint.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.datepicker.js|js/jquery/datepicker/ccs-date-timepicker.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-F94BE9A2
include_once("./production_maint_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-67212455
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
$production = new clsRecordproduction("", $MainPage);
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
$MainPage->production = & $production;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("production", $production);
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

//Execute Components @1-B8B67AB4
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$production->Operation();
//End Execute Components

//Go to destination page @1-63BEC332
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($production);
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

//Unload Page @1-3F8ADD4C
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($production);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
