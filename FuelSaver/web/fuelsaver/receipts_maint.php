<?php
//Include Common Files @1-CC9325B9
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "receipts_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordreceipts { //receipts Class @2-8C823F0F

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

//Class_Initialize Event @2-B136201C
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
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        $this->Visible = (CCSecurityAccessCheck("") == "success");
        if($this->Visible)
        {
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
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->Id = new clsControl(ccsTextBox, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", $Method, NULL), $this);
            $this->Id->Required = true;
            $this->TempReceiptNo = new clsControl(ccsTextBox, "TempReceiptNo", "Temp Receipt No", ccsInteger, "", CCGetRequestParam("TempReceiptNo", $Method, NULL), $this);
            $this->TempReceiptNo->Required = true;
            $this->ReceiptNo = new clsControl(ccsTextBox, "ReceiptNo", "Receipt No", ccsInteger, "", CCGetRequestParam("ReceiptNo", $Method, NULL), $this);
            $this->ReceiptNo->Required = true;
            $this->ReceiptDate = new clsControl(ccsTextBox, "ReceiptDate", "Receipt Date", ccsDate, array("ShortDate"), CCGetRequestParam("ReceiptDate", $Method, NULL), $this);
            $this->ReceiptDate->Required = true;
            $this->ReceiptTime = new clsControl(ccsTextBox, "ReceiptTime", "Receipt Time", ccsDate, array("ShortDate"), CCGetRequestParam("ReceiptTime", $Method, NULL), $this);
            $this->ReceiptTime->Required = true;
            $this->SalesOrder = new clsControl(ccsListBox, "SalesOrder", "Sales Order", ccsInteger, "", CCGetRequestParam("SalesOrder", $Method, NULL), $this);
            $this->SalesOrder->DSType = dsTable;
            $this->SalesOrder->DataSource = new clsDBFuelSaver();
            $this->SalesOrder->ds = & $this->SalesOrder->DataSource;
            $this->SalesOrder->DataSource->SQL = "SELECT * \n" .
"FROM sales {SQL_Where} {SQL_OrderBy}";
            list($this->SalesOrder->BoundColumn, $this->SalesOrder->TextColumn, $this->SalesOrder->DBFormat) = array("Id", "SalesRemarks", "");
            $this->SalesOrder->Required = true;
            $this->ReceiptAmount = new clsControl(ccsTextBox, "ReceiptAmount", "Receipt Amount", ccsSingle, "", CCGetRequestParam("ReceiptAmount", $Method, NULL), $this);
            $this->ReceiptAmount->Required = true;
            $this->ReferenceId = new clsControl(ccsTextBox, "ReferenceId", "Reference Id", ccsText, "", CCGetRequestParam("ReferenceId", $Method, NULL), $this);
            $this->VerifiedAmount = new clsControl(ccsTextBox, "VerifiedAmount", "Verified Amount", ccsSingle, "", CCGetRequestParam("VerifiedAmount", $Method, NULL), $this);
            $this->VerifiedBy = new clsControl(ccsListBox, "VerifiedBy", "Verified By", ccsInteger, "", CCGetRequestParam("VerifiedBy", $Method, NULL), $this);
            $this->VerifiedBy->DSType = dsTable;
            $this->VerifiedBy->DataSource = new clsDBFuelSaver();
            $this->VerifiedBy->ds = & $this->VerifiedBy->DataSource;
            $this->VerifiedBy->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->VerifiedBy->BoundColumn, $this->VerifiedBy->TextColumn, $this->VerifiedBy->DBFormat) = array("Id", "UserId", "");
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

//Validate Method @2-5F311BA0
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->Id->Validate() && $Validation);
        $Validation = ($this->TempReceiptNo->Validate() && $Validation);
        $Validation = ($this->ReceiptNo->Validate() && $Validation);
        $Validation = ($this->ReceiptDate->Validate() && $Validation);
        $Validation = ($this->ReceiptTime->Validate() && $Validation);
        $Validation = ($this->SalesOrder->Validate() && $Validation);
        $Validation = ($this->ReceiptAmount->Validate() && $Validation);
        $Validation = ($this->ReferenceId->Validate() && $Validation);
        $Validation = ($this->VerifiedAmount->Validate() && $Validation);
        $Validation = ($this->VerifiedBy->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->Id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->TempReceiptNo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReceiptNo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReceiptDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReceiptTime->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesOrder->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReceiptAmount->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReferenceId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->VerifiedAmount->Errors->Count() == 0);
        $Validation =  $Validation && ($this->VerifiedBy->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-1D9EA652
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Id->Errors->Count());
        $errors = ($errors || $this->TempReceiptNo->Errors->Count());
        $errors = ($errors || $this->ReceiptNo->Errors->Count());
        $errors = ($errors || $this->ReceiptDate->Errors->Count());
        $errors = ($errors || $this->ReceiptTime->Errors->Count());
        $errors = ($errors || $this->SalesOrder->Errors->Count());
        $errors = ($errors || $this->ReceiptAmount->Errors->Count());
        $errors = ($errors || $this->ReferenceId->Errors->Count());
        $errors = ($errors || $this->VerifiedAmount->Errors->Count());
        $errors = ($errors || $this->VerifiedBy->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-0B268602
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
        $Redirect = "receipts_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @2-B3AF7048
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->TempReceiptNo->SetValue($this->TempReceiptNo->GetValue(true));
        $this->DataSource->ReceiptNo->SetValue($this->ReceiptNo->GetValue(true));
        $this->DataSource->ReceiptDate->SetValue($this->ReceiptDate->GetValue(true));
        $this->DataSource->ReceiptTime->SetValue($this->ReceiptTime->GetValue(true));
        $this->DataSource->SalesOrder->SetValue($this->SalesOrder->GetValue(true));
        $this->DataSource->ReceiptAmount->SetValue($this->ReceiptAmount->GetValue(true));
        $this->DataSource->ReferenceId->SetValue($this->ReferenceId->GetValue(true));
        $this->DataSource->VerifiedAmount->SetValue($this->VerifiedAmount->GetValue(true));
        $this->DataSource->VerifiedBy->SetValue($this->VerifiedBy->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-DF80AECE
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->TempReceiptNo->SetValue($this->TempReceiptNo->GetValue(true));
        $this->DataSource->ReceiptNo->SetValue($this->ReceiptNo->GetValue(true));
        $this->DataSource->ReceiptDate->SetValue($this->ReceiptDate->GetValue(true));
        $this->DataSource->ReceiptTime->SetValue($this->ReceiptTime->GetValue(true));
        $this->DataSource->SalesOrder->SetValue($this->SalesOrder->GetValue(true));
        $this->DataSource->ReceiptAmount->SetValue($this->ReceiptAmount->GetValue(true));
        $this->DataSource->ReferenceId->SetValue($this->ReferenceId->GetValue(true));
        $this->DataSource->VerifiedAmount->SetValue($this->VerifiedAmount->GetValue(true));
        $this->DataSource->VerifiedBy->SetValue($this->VerifiedBy->GetValue(true));
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

//Show Method @2-64632F6E
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
        $this->VerifiedBy->Prepare();

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
                    $this->TempReceiptNo->SetValue($this->DataSource->TempReceiptNo->GetValue());
                    $this->ReceiptNo->SetValue($this->DataSource->ReceiptNo->GetValue());
                    $this->ReceiptDate->SetValue($this->DataSource->ReceiptDate->GetValue());
                    $this->ReceiptTime->SetValue($this->DataSource->ReceiptTime->GetValue());
                    $this->SalesOrder->SetValue($this->DataSource->SalesOrder->GetValue());
                    $this->ReceiptAmount->SetValue($this->DataSource->ReceiptAmount->GetValue());
                    $this->ReferenceId->SetValue($this->DataSource->ReferenceId->GetValue());
                    $this->VerifiedAmount->SetValue($this->DataSource->VerifiedAmount->GetValue());
                    $this->VerifiedBy->SetValue($this->DataSource->VerifiedBy->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->Id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->TempReceiptNo->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReceiptNo->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReceiptDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReceiptTime->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesOrder->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReceiptAmount->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReferenceId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->VerifiedAmount->Errors->ToString());
            $Error = ComposeStrings($Error, $this->VerifiedBy->Errors->ToString());
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
        $this->TempReceiptNo->Show();
        $this->ReceiptNo->Show();
        $this->ReceiptDate->Show();
        $this->ReceiptTime->Show();
        $this->SalesOrder->Show();
        $this->ReceiptAmount->Show();
        $this->ReferenceId->Show();
        $this->VerifiedAmount->Show();
        $this->VerifiedBy->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End receipts Class @2-FCB6E20C

class clsreceiptsDataSource extends clsDBFuelSaver {  //receiptsDataSource Class @2-AA038C14

//DataSource Variables @2-FDC46C8A
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
    public $TempReceiptNo;
    public $ReceiptNo;
    public $ReceiptDate;
    public $ReceiptTime;
    public $SalesOrder;
    public $ReceiptAmount;
    public $ReferenceId;
    public $VerifiedAmount;
    public $VerifiedBy;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-6E3B0792
    function clsreceiptsDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record receipts/Error";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->TempReceiptNo = new clsField("TempReceiptNo", ccsInteger, "");
        
        $this->ReceiptNo = new clsField("ReceiptNo", ccsInteger, "");
        
        $this->ReceiptDate = new clsField("ReceiptDate", ccsDate, $this->DateFormat);
        
        $this->ReceiptTime = new clsField("ReceiptTime", ccsDate, $this->DateFormat);
        
        $this->SalesOrder = new clsField("SalesOrder", ccsInteger, "");
        
        $this->ReceiptAmount = new clsField("ReceiptAmount", ccsSingle, "");
        
        $this->ReferenceId = new clsField("ReferenceId", ccsText, "");
        
        $this->VerifiedAmount = new clsField("VerifiedAmount", ccsSingle, "");
        
        $this->VerifiedBy = new clsField("VerifiedBy", ccsInteger, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["TempReceiptNo"] = array("Name" => "TempReceiptNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ReceiptNo"] = array("Name" => "ReceiptNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ReceiptDate"] = array("Name" => "ReceiptDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["ReceiptTime"] = array("Name" => "ReceiptTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesOrder"] = array("Name" => "SalesOrder", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ReceiptAmount"] = array("Name" => "ReceiptAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["ReferenceId"] = array("Name" => "ReferenceId", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["VerifiedAmount"] = array("Name" => "VerifiedAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["VerifiedBy"] = array("Name" => "VerifiedBy", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["TempReceiptNo"] = array("Name" => "TempReceiptNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ReceiptNo"] = array("Name" => "ReceiptNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ReceiptDate"] = array("Name" => "ReceiptDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["ReceiptTime"] = array("Name" => "ReceiptTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesOrder"] = array("Name" => "SalesOrder", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ReceiptAmount"] = array("Name" => "ReceiptAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["ReferenceId"] = array("Name" => "ReferenceId", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["VerifiedAmount"] = array("Name" => "VerifiedAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["VerifiedBy"] = array("Name" => "VerifiedBy", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
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

//Open Method @2-078594BC
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

//SetValues Method @2-7F133AE9
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->TempReceiptNo->SetDBValue(trim($this->f("TempReceiptNo")));
        $this->ReceiptNo->SetDBValue(trim($this->f("ReceiptNo")));
        $this->ReceiptDate->SetDBValue(trim($this->f("ReceiptDate")));
        $this->ReceiptTime->SetDBValue(trim($this->f("ReceiptTime")));
        $this->SalesOrder->SetDBValue(trim($this->f("SalesOrder")));
        $this->ReceiptAmount->SetDBValue(trim($this->f("ReceiptAmount")));
        $this->ReferenceId->SetDBValue($this->f("ReferenceId"));
        $this->VerifiedAmount->SetDBValue(trim($this->f("VerifiedAmount")));
        $this->VerifiedBy->SetDBValue(trim($this->f("VerifiedBy")));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @2-E3C630F7
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->InsertFields["TempReceiptNo"]["Value"] = $this->TempReceiptNo->GetDBValue(true);
        $this->InsertFields["ReceiptNo"]["Value"] = $this->ReceiptNo->GetDBValue(true);
        $this->InsertFields["ReceiptDate"]["Value"] = $this->ReceiptDate->GetDBValue(true);
        $this->InsertFields["ReceiptTime"]["Value"] = $this->ReceiptTime->GetDBValue(true);
        $this->InsertFields["SalesOrder"]["Value"] = $this->SalesOrder->GetDBValue(true);
        $this->InsertFields["ReceiptAmount"]["Value"] = $this->ReceiptAmount->GetDBValue(true);
        $this->InsertFields["ReferenceId"]["Value"] = $this->ReferenceId->GetDBValue(true);
        $this->InsertFields["VerifiedAmount"]["Value"] = $this->VerifiedAmount->GetDBValue(true);
        $this->InsertFields["VerifiedBy"]["Value"] = $this->VerifiedBy->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("receipts", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-EB163049
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->UpdateFields["TempReceiptNo"]["Value"] = $this->TempReceiptNo->GetDBValue(true);
        $this->UpdateFields["ReceiptNo"]["Value"] = $this->ReceiptNo->GetDBValue(true);
        $this->UpdateFields["ReceiptDate"]["Value"] = $this->ReceiptDate->GetDBValue(true);
        $this->UpdateFields["ReceiptTime"]["Value"] = $this->ReceiptTime->GetDBValue(true);
        $this->UpdateFields["SalesOrder"]["Value"] = $this->SalesOrder->GetDBValue(true);
        $this->UpdateFields["ReceiptAmount"]["Value"] = $this->ReceiptAmount->GetDBValue(true);
        $this->UpdateFields["ReferenceId"]["Value"] = $this->ReferenceId->GetDBValue(true);
        $this->UpdateFields["VerifiedAmount"]["Value"] = $this->VerifiedAmount->GetDBValue(true);
        $this->UpdateFields["VerifiedBy"]["Value"] = $this->VerifiedBy->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildUpdate("receipts", $this->UpdateFields, $this);
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

//Delete Method @2-CDE00867
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM receipts";
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

} //End receiptsDataSource Class @2-FCB6E20C

//Include Page implementation @26-07AA2166
include_once(RelativePath . "/MenuIncludablePage.php");
//End Include Page implementation

//Include Page implementation @27-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-8E6465FB
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
$TemplateFileName = "receipts_maint.html";
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

//Include events file @1-3DEC4E1F
include_once("./receipts_maint_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-F262CFA5
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
$MainPage->receipts = & $receipts;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("receipts", $receipts);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
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

//Execute Components @1-6C02F1AE
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$receipts->Operation();
//End Execute Components

//Go to destination page @1-48B92591
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($receipts);
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

//Unload Page @1-58FC5FBA
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($receipts);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
