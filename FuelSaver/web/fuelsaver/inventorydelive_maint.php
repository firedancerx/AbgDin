<?php
//Include Common Files @1-BB0D3073
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "inventorydelive_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordinventorydeliveries { //inventorydeliveries Class @2-CD5C77FA

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

//Class_Initialize Event @2-C4D7373B
    function clsRecordinventorydeliveries($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record inventorydeliveries/Error";
        $this->DataSource = new clsinventorydeliveriesDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "inventorydeliveries";
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
            $this->DeliveryOrderNo = new clsControl(ccsTextBox, "DeliveryOrderNo", "Delivery Order No", ccsInteger, "", CCGetRequestParam("DeliveryOrderNo", $Method, NULL), $this);
            $this->DeliveryDate = new clsControl(ccsTextBox, "DeliveryDate", "Delivery Date", ccsDate, array("ShortDate"), CCGetRequestParam("DeliveryDate", $Method, NULL), $this);
            $this->DeliveryDate->Required = true;
            $this->DeliveryTime = new clsControl(ccsTextBox, "DeliveryTime", "Delivery Time", ccsDate, array("ShortDate"), CCGetRequestParam("DeliveryTime", $Method, NULL), $this);
            $this->DeliveryTime->Required = true;
            $this->DeliveryBy = new clsControl(ccsListBox, "DeliveryBy", "Delivery By", ccsInteger, "", CCGetRequestParam("DeliveryBy", $Method, NULL), $this);
            $this->DeliveryBy->DSType = dsTable;
            $this->DeliveryBy->DataSource = new clsDBFuelSaver();
            $this->DeliveryBy->ds = & $this->DeliveryBy->DataSource;
            $this->DeliveryBy->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->DeliveryBy->BoundColumn, $this->DeliveryBy->TextColumn, $this->DeliveryBy->DBFormat) = array("Id", "UserId", "");
            $this->DeliveryBy->Required = true;
            $this->SalesId = new clsControl(ccsListBox, "SalesId", "Sales Id", ccsInteger, "", CCGetRequestParam("SalesId", $Method, NULL), $this);
            $this->SalesId->DSType = dsTable;
            $this->SalesId->DataSource = new clsDBFuelSaver();
            $this->SalesId->ds = & $this->SalesId->DataSource;
            $this->SalesId->DataSource->SQL = "SELECT * \n" .
"FROM sales {SQL_Where} {SQL_OrderBy}";
            list($this->SalesId->BoundColumn, $this->SalesId->TextColumn, $this->SalesId->DBFormat) = array("Id", "SalesRemarks", "");
            $this->DeliveryRemarks = new clsControl(ccsTextBox, "DeliveryRemarks", "Delivery Remarks", ccsText, "", CCGetRequestParam("DeliveryRemarks", $Method, NULL), $this);
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

//Validate Method @2-FB414F08
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->Id->Validate() && $Validation);
        $Validation = ($this->DeliveryOrderNo->Validate() && $Validation);
        $Validation = ($this->DeliveryDate->Validate() && $Validation);
        $Validation = ($this->DeliveryTime->Validate() && $Validation);
        $Validation = ($this->DeliveryBy->Validate() && $Validation);
        $Validation = ($this->SalesId->Validate() && $Validation);
        $Validation = ($this->DeliveryRemarks->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->Id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryOrderNo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryTime->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryBy->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->DeliveryRemarks->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-1477F0F5
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Id->Errors->Count());
        $errors = ($errors || $this->DeliveryOrderNo->Errors->Count());
        $errors = ($errors || $this->DeliveryDate->Errors->Count());
        $errors = ($errors || $this->DeliveryTime->Errors->Count());
        $errors = ($errors || $this->DeliveryBy->Errors->Count());
        $errors = ($errors || $this->SalesId->Errors->Count());
        $errors = ($errors || $this->DeliveryRemarks->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-45A62A26
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
        $Redirect = "inventorydelive_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @2-990AF2D2
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->DeliveryOrderNo->SetValue($this->DeliveryOrderNo->GetValue(true));
        $this->DataSource->DeliveryDate->SetValue($this->DeliveryDate->GetValue(true));
        $this->DataSource->DeliveryTime->SetValue($this->DeliveryTime->GetValue(true));
        $this->DataSource->DeliveryBy->SetValue($this->DeliveryBy->GetValue(true));
        $this->DataSource->SalesId->SetValue($this->SalesId->GetValue(true));
        $this->DataSource->DeliveryRemarks->SetValue($this->DeliveryRemarks->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-C13B6DDB
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->DeliveryOrderNo->SetValue($this->DeliveryOrderNo->GetValue(true));
        $this->DataSource->DeliveryDate->SetValue($this->DeliveryDate->GetValue(true));
        $this->DataSource->DeliveryTime->SetValue($this->DeliveryTime->GetValue(true));
        $this->DataSource->DeliveryBy->SetValue($this->DeliveryBy->GetValue(true));
        $this->DataSource->SalesId->SetValue($this->SalesId->GetValue(true));
        $this->DataSource->DeliveryRemarks->SetValue($this->DeliveryRemarks->GetValue(true));
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

//Show Method @2-072382E5
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

        $this->DeliveryBy->Prepare();
        $this->SalesId->Prepare();

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
                    $this->DeliveryOrderNo->SetValue($this->DataSource->DeliveryOrderNo->GetValue());
                    $this->DeliveryDate->SetValue($this->DataSource->DeliveryDate->GetValue());
                    $this->DeliveryTime->SetValue($this->DataSource->DeliveryTime->GetValue());
                    $this->DeliveryBy->SetValue($this->DataSource->DeliveryBy->GetValue());
                    $this->SalesId->SetValue($this->DataSource->SalesId->GetValue());
                    $this->DeliveryRemarks->SetValue($this->DataSource->DeliveryRemarks->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->Id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryOrderNo->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryTime->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryBy->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DeliveryRemarks->Errors->ToString());
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
        $this->DeliveryOrderNo->Show();
        $this->DeliveryDate->Show();
        $this->DeliveryTime->Show();
        $this->DeliveryBy->Show();
        $this->SalesId->Show();
        $this->DeliveryRemarks->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End inventorydeliveries Class @2-FCB6E20C

class clsinventorydeliveriesDataSource extends clsDBFuelSaver {  //inventorydeliveriesDataSource Class @2-CD406F65

//DataSource Variables @2-879703B8
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
    public $DeliveryOrderNo;
    public $DeliveryDate;
    public $DeliveryTime;
    public $DeliveryBy;
    public $SalesId;
    public $DeliveryRemarks;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-8C877642
    function clsinventorydeliveriesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record inventorydeliveries/Error";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->DeliveryOrderNo = new clsField("DeliveryOrderNo", ccsInteger, "");
        
        $this->DeliveryDate = new clsField("DeliveryDate", ccsDate, $this->DateFormat);
        
        $this->DeliveryTime = new clsField("DeliveryTime", ccsDate, $this->DateFormat);
        
        $this->DeliveryBy = new clsField("DeliveryBy", ccsInteger, "");
        
        $this->SalesId = new clsField("SalesId", ccsInteger, "");
        
        $this->DeliveryRemarks = new clsField("DeliveryRemarks", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["DeliveryOrderNo"] = array("Name" => "DeliveryOrderNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["DeliveryDate"] = array("Name" => "DeliveryDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["DeliveryTime"] = array("Name" => "DeliveryTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["DeliveryBy"] = array("Name" => "DeliveryBy", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesId"] = array("Name" => "SalesId", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["DeliveryRemarks"] = array("Name" => "DeliveryRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["DeliveryOrderNo"] = array("Name" => "DeliveryOrderNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["DeliveryDate"] = array("Name" => "DeliveryDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["DeliveryTime"] = array("Name" => "DeliveryTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["DeliveryBy"] = array("Name" => "DeliveryBy", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesId"] = array("Name" => "SalesId", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["DeliveryRemarks"] = array("Name" => "DeliveryRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
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

//Open Method @2-84EDD01A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM inventorydeliveries {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-5E875B01
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->DeliveryOrderNo->SetDBValue(trim($this->f("DeliveryOrderNo")));
        $this->DeliveryDate->SetDBValue(trim($this->f("DeliveryDate")));
        $this->DeliveryTime->SetDBValue(trim($this->f("DeliveryTime")));
        $this->DeliveryBy->SetDBValue(trim($this->f("DeliveryBy")));
        $this->SalesId->SetDBValue(trim($this->f("SalesId")));
        $this->DeliveryRemarks->SetDBValue($this->f("DeliveryRemarks"));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @2-F3E34B19
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->InsertFields["DeliveryOrderNo"]["Value"] = $this->DeliveryOrderNo->GetDBValue(true);
        $this->InsertFields["DeliveryDate"]["Value"] = $this->DeliveryDate->GetDBValue(true);
        $this->InsertFields["DeliveryTime"]["Value"] = $this->DeliveryTime->GetDBValue(true);
        $this->InsertFields["DeliveryBy"]["Value"] = $this->DeliveryBy->GetDBValue(true);
        $this->InsertFields["SalesId"]["Value"] = $this->SalesId->GetDBValue(true);
        $this->InsertFields["DeliveryRemarks"]["Value"] = $this->DeliveryRemarks->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("inventorydeliveries", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-950770D4
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->UpdateFields["DeliveryOrderNo"]["Value"] = $this->DeliveryOrderNo->GetDBValue(true);
        $this->UpdateFields["DeliveryDate"]["Value"] = $this->DeliveryDate->GetDBValue(true);
        $this->UpdateFields["DeliveryTime"]["Value"] = $this->DeliveryTime->GetDBValue(true);
        $this->UpdateFields["DeliveryBy"]["Value"] = $this->DeliveryBy->GetDBValue(true);
        $this->UpdateFields["SalesId"]["Value"] = $this->SalesId->GetDBValue(true);
        $this->UpdateFields["DeliveryRemarks"]["Value"] = $this->DeliveryRemarks->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildUpdate("inventorydeliveries", $this->UpdateFields, $this);
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

//Delete Method @2-20C9E31C
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM inventorydeliveries";
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

} //End inventorydeliveriesDataSource Class @2-FCB6E20C

//Include Page implementation @23-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Include Page implementation @24-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-AC6B43EB
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
$TemplateFileName = "inventorydelive_maint.html";
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

//Include events file @1-F88CD7C7
include_once("./inventorydelive_maint_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-FDF79F2B
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
$inventorydeliveries = new clsRecordinventorydeliveries("", $MainPage);
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
$MainPage->inventorydeliveries = & $inventorydeliveries;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("inventorydeliveries", $inventorydeliveries);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$inventorydeliveries->Initialize();
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

//Execute Components @1-D38971BE
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$inventorydeliveries->Operation();
//End Execute Components

//Go to destination page @1-F1739DE0
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($inventorydeliveries);
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

//Unload Page @1-1D49C592
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($inventorydeliveries);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
