<?php
//Include Common Files @1-6AF343F1
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "contactus_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordcontactus { //contactus Class @2-854FA3F1

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

//Class_Initialize Event @2-B1D6ADA0
    function clsRecordcontactus($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record contactus/Error";
        $this->DataSource = new clscontactusDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "contactus";
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
            $this->ContactDate = new clsControl(ccsTextBox, "ContactDate", "Contact Date", ccsDate, array("ShortDate"), CCGetRequestParam("ContactDate", $Method, NULL), $this);
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
            $this->ContactContent = new clsControl(ccsTextBox, "ContactContent", "Contact Content", ccsText, "", CCGetRequestParam("ContactContent", $Method, NULL), $this);
            $this->ContactReplyDate = new clsControl(ccsTextBox, "ContactReplyDate", "Contact Reply Date", ccsDate, array("ShortDate"), CCGetRequestParam("ContactReplyDate", $Method, NULL), $this);
            $this->ContactReplyTime = new clsControl(ccsTextBox, "ContactReplyTime", "Contact Reply Time", ccsDate, array("ShortDate"), CCGetRequestParam("ContactReplyTime", $Method, NULL), $this);
            $this->ContactReplyBy = new clsControl(ccsListBox, "ContactReplyBy", "Contact Reply By", ccsInteger, "", CCGetRequestParam("ContactReplyBy", $Method, NULL), $this);
            $this->ContactReplyBy->DSType = dsTable;
            $this->ContactReplyBy->DataSource = new clsDBFuelSaver();
            $this->ContactReplyBy->ds = & $this->ContactReplyBy->DataSource;
            $this->ContactReplyBy->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->ContactReplyBy->BoundColumn, $this->ContactReplyBy->TextColumn, $this->ContactReplyBy->DBFormat) = array("Id", "UserId", "");
            $this->ContactReplyContent = new clsControl(ccsTextBox, "ContactReplyContent", "Contact Reply Content", ccsText, "", CCGetRequestParam("ContactReplyContent", $Method, NULL), $this);
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

//Validate Method @2-D395E71F
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->Id->Validate() && $Validation);
        $Validation = ($this->ContactDate->Validate() && $Validation);
        $Validation = ($this->ContactTime->Validate() && $Validation);
        $Validation = ($this->ContactName->Validate() && $Validation);
        $Validation = ($this->ContactEmail->Validate() && $Validation);
        $Validation = ($this->ContactPhone->Validate() && $Validation);
        $Validation = ($this->ContactUser->Validate() && $Validation);
        $Validation = ($this->ContactSubject->Validate() && $Validation);
        $Validation = ($this->ContactContent->Validate() && $Validation);
        $Validation = ($this->ContactReplyDate->Validate() && $Validation);
        $Validation = ($this->ContactReplyTime->Validate() && $Validation);
        $Validation = ($this->ContactReplyBy->Validate() && $Validation);
        $Validation = ($this->ContactReplyContent->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->Id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactTime->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactName->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactEmail->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactPhone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactUser->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactSubject->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactContent->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactReplyDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactReplyTime->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactReplyBy->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ContactReplyContent->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-49AEA8B2
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Id->Errors->Count());
        $errors = ($errors || $this->ContactDate->Errors->Count());
        $errors = ($errors || $this->ContactTime->Errors->Count());
        $errors = ($errors || $this->ContactName->Errors->Count());
        $errors = ($errors || $this->ContactEmail->Errors->Count());
        $errors = ($errors || $this->ContactPhone->Errors->Count());
        $errors = ($errors || $this->ContactUser->Errors->Count());
        $errors = ($errors || $this->ContactSubject->Errors->Count());
        $errors = ($errors || $this->ContactContent->Errors->Count());
        $errors = ($errors || $this->ContactReplyDate->Errors->Count());
        $errors = ($errors || $this->ContactReplyTime->Errors->Count());
        $errors = ($errors || $this->ContactReplyBy->Errors->Count());
        $errors = ($errors || $this->ContactReplyContent->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-B4CE52C1
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
        $Redirect = "contactus_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @2-AF4BA84C
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->ContactDate->SetValue($this->ContactDate->GetValue(true));
        $this->DataSource->ContactTime->SetValue($this->ContactTime->GetValue(true));
        $this->DataSource->ContactName->SetValue($this->ContactName->GetValue(true));
        $this->DataSource->ContactEmail->SetValue($this->ContactEmail->GetValue(true));
        $this->DataSource->ContactPhone->SetValue($this->ContactPhone->GetValue(true));
        $this->DataSource->ContactUser->SetValue($this->ContactUser->GetValue(true));
        $this->DataSource->ContactSubject->SetValue($this->ContactSubject->GetValue(true));
        $this->DataSource->ContactContent->SetValue($this->ContactContent->GetValue(true));
        $this->DataSource->ContactReplyDate->SetValue($this->ContactReplyDate->GetValue(true));
        $this->DataSource->ContactReplyTime->SetValue($this->ContactReplyTime->GetValue(true));
        $this->DataSource->ContactReplyBy->SetValue($this->ContactReplyBy->GetValue(true));
        $this->DataSource->ContactReplyContent->SetValue($this->ContactReplyContent->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-AD5B2D10
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->ContactDate->SetValue($this->ContactDate->GetValue(true));
        $this->DataSource->ContactTime->SetValue($this->ContactTime->GetValue(true));
        $this->DataSource->ContactName->SetValue($this->ContactName->GetValue(true));
        $this->DataSource->ContactEmail->SetValue($this->ContactEmail->GetValue(true));
        $this->DataSource->ContactPhone->SetValue($this->ContactPhone->GetValue(true));
        $this->DataSource->ContactUser->SetValue($this->ContactUser->GetValue(true));
        $this->DataSource->ContactSubject->SetValue($this->ContactSubject->GetValue(true));
        $this->DataSource->ContactContent->SetValue($this->ContactContent->GetValue(true));
        $this->DataSource->ContactReplyDate->SetValue($this->ContactReplyDate->GetValue(true));
        $this->DataSource->ContactReplyTime->SetValue($this->ContactReplyTime->GetValue(true));
        $this->DataSource->ContactReplyBy->SetValue($this->ContactReplyBy->GetValue(true));
        $this->DataSource->ContactReplyContent->SetValue($this->ContactReplyContent->GetValue(true));
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

//Show Method @2-9C71305C
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
        $this->ContactReplyBy->Prepare();

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
                    $this->ContactDate->SetValue($this->DataSource->ContactDate->GetValue());
                    $this->ContactTime->SetValue($this->DataSource->ContactTime->GetValue());
                    $this->ContactName->SetValue($this->DataSource->ContactName->GetValue());
                    $this->ContactEmail->SetValue($this->DataSource->ContactEmail->GetValue());
                    $this->ContactPhone->SetValue($this->DataSource->ContactPhone->GetValue());
                    $this->ContactUser->SetValue($this->DataSource->ContactUser->GetValue());
                    $this->ContactSubject->SetValue($this->DataSource->ContactSubject->GetValue());
                    $this->ContactContent->SetValue($this->DataSource->ContactContent->GetValue());
                    $this->ContactReplyDate->SetValue($this->DataSource->ContactReplyDate->GetValue());
                    $this->ContactReplyTime->SetValue($this->DataSource->ContactReplyTime->GetValue());
                    $this->ContactReplyBy->SetValue($this->DataSource->ContactReplyBy->GetValue());
                    $this->ContactReplyContent->SetValue($this->DataSource->ContactReplyContent->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->Id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactTime->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactName->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactEmail->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactPhone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactUser->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactSubject->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactContent->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactReplyDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactReplyTime->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactReplyBy->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ContactReplyContent->Errors->ToString());
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
        $this->ContactDate->Show();
        $this->ContactTime->Show();
        $this->ContactName->Show();
        $this->ContactEmail->Show();
        $this->ContactPhone->Show();
        $this->ContactUser->Show();
        $this->ContactSubject->Show();
        $this->ContactContent->Show();
        $this->ContactReplyDate->Show();
        $this->ContactReplyTime->Show();
        $this->ContactReplyBy->Show();
        $this->ContactReplyContent->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End contactus Class @2-FCB6E20C

class clscontactusDataSource extends clsDBFuelSaver {  //contactusDataSource Class @2-7982D766

//DataSource Variables @2-A5EB43BA
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
    public $ContactDate;
    public $ContactTime;
    public $ContactName;
    public $ContactEmail;
    public $ContactPhone;
    public $ContactUser;
    public $ContactSubject;
    public $ContactContent;
    public $ContactReplyDate;
    public $ContactReplyTime;
    public $ContactReplyBy;
    public $ContactReplyContent;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-0575745C
    function clscontactusDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record contactus/Error";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->ContactDate = new clsField("ContactDate", ccsDate, $this->DateFormat);
        
        $this->ContactTime = new clsField("ContactTime", ccsDate, $this->DateFormat);
        
        $this->ContactName = new clsField("ContactName", ccsText, "");
        
        $this->ContactEmail = new clsField("ContactEmail", ccsText, "");
        
        $this->ContactPhone = new clsField("ContactPhone", ccsText, "");
        
        $this->ContactUser = new clsField("ContactUser", ccsInteger, "");
        
        $this->ContactSubject = new clsField("ContactSubject", ccsText, "");
        
        $this->ContactContent = new clsField("ContactContent", ccsText, "");
        
        $this->ContactReplyDate = new clsField("ContactReplyDate", ccsDate, $this->DateFormat);
        
        $this->ContactReplyTime = new clsField("ContactReplyTime", ccsDate, $this->DateFormat);
        
        $this->ContactReplyBy = new clsField("ContactReplyBy", ccsInteger, "");
        
        $this->ContactReplyContent = new clsField("ContactReplyContent", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactDate"] = array("Name" => "ContactDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactTime"] = array("Name" => "ContactTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactName"] = array("Name" => "ContactName", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactEmail"] = array("Name" => "ContactEmail", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactPhone"] = array("Name" => "ContactPhone", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactUser"] = array("Name" => "ContactUser", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactSubject"] = array("Name" => "ContactSubject", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactContent"] = array("Name" => "ContactContent", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactReplyDate"] = array("Name" => "ContactReplyDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactReplyTime"] = array("Name" => "ContactReplyTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactReplyBy"] = array("Name" => "ContactReplyBy", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ContactReplyContent"] = array("Name" => "ContactReplyContent", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactDate"] = array("Name" => "ContactDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactTime"] = array("Name" => "ContactTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactName"] = array("Name" => "ContactName", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactEmail"] = array("Name" => "ContactEmail", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactPhone"] = array("Name" => "ContactPhone", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactUser"] = array("Name" => "ContactUser", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactSubject"] = array("Name" => "ContactSubject", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactContent"] = array("Name" => "ContactContent", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactReplyDate"] = array("Name" => "ContactReplyDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactReplyTime"] = array("Name" => "ContactReplyTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactReplyBy"] = array("Name" => "ContactReplyBy", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ContactReplyContent"] = array("Name" => "ContactReplyContent", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
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

//Open Method @2-CCEC5A24
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

//SetValues Method @2-D0504F78
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->ContactDate->SetDBValue(trim($this->f("ContactDate")));
        $this->ContactTime->SetDBValue(trim($this->f("ContactTime")));
        $this->ContactName->SetDBValue($this->f("ContactName"));
        $this->ContactEmail->SetDBValue($this->f("ContactEmail"));
        $this->ContactPhone->SetDBValue($this->f("ContactPhone"));
        $this->ContactUser->SetDBValue(trim($this->f("ContactUser")));
        $this->ContactSubject->SetDBValue($this->f("ContactSubject"));
        $this->ContactContent->SetDBValue($this->f("ContactContent"));
        $this->ContactReplyDate->SetDBValue(trim($this->f("ContactReplyDate")));
        $this->ContactReplyTime->SetDBValue(trim($this->f("ContactReplyTime")));
        $this->ContactReplyBy->SetDBValue(trim($this->f("ContactReplyBy")));
        $this->ContactReplyContent->SetDBValue($this->f("ContactReplyContent"));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @2-052FE507
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->InsertFields["ContactDate"]["Value"] = $this->ContactDate->GetDBValue(true);
        $this->InsertFields["ContactTime"]["Value"] = $this->ContactTime->GetDBValue(true);
        $this->InsertFields["ContactName"]["Value"] = $this->ContactName->GetDBValue(true);
        $this->InsertFields["ContactEmail"]["Value"] = $this->ContactEmail->GetDBValue(true);
        $this->InsertFields["ContactPhone"]["Value"] = $this->ContactPhone->GetDBValue(true);
        $this->InsertFields["ContactUser"]["Value"] = $this->ContactUser->GetDBValue(true);
        $this->InsertFields["ContactSubject"]["Value"] = $this->ContactSubject->GetDBValue(true);
        $this->InsertFields["ContactContent"]["Value"] = $this->ContactContent->GetDBValue(true);
        $this->InsertFields["ContactReplyDate"]["Value"] = $this->ContactReplyDate->GetDBValue(true);
        $this->InsertFields["ContactReplyTime"]["Value"] = $this->ContactReplyTime->GetDBValue(true);
        $this->InsertFields["ContactReplyBy"]["Value"] = $this->ContactReplyBy->GetDBValue(true);
        $this->InsertFields["ContactReplyContent"]["Value"] = $this->ContactReplyContent->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("contactus", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-E8B051D3
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->UpdateFields["ContactDate"]["Value"] = $this->ContactDate->GetDBValue(true);
        $this->UpdateFields["ContactTime"]["Value"] = $this->ContactTime->GetDBValue(true);
        $this->UpdateFields["ContactName"]["Value"] = $this->ContactName->GetDBValue(true);
        $this->UpdateFields["ContactEmail"]["Value"] = $this->ContactEmail->GetDBValue(true);
        $this->UpdateFields["ContactPhone"]["Value"] = $this->ContactPhone->GetDBValue(true);
        $this->UpdateFields["ContactUser"]["Value"] = $this->ContactUser->GetDBValue(true);
        $this->UpdateFields["ContactSubject"]["Value"] = $this->ContactSubject->GetDBValue(true);
        $this->UpdateFields["ContactContent"]["Value"] = $this->ContactContent->GetDBValue(true);
        $this->UpdateFields["ContactReplyDate"]["Value"] = $this->ContactReplyDate->GetDBValue(true);
        $this->UpdateFields["ContactReplyTime"]["Value"] = $this->ContactReplyTime->GetDBValue(true);
        $this->UpdateFields["ContactReplyBy"]["Value"] = $this->ContactReplyBy->GetDBValue(true);
        $this->UpdateFields["ContactReplyContent"]["Value"] = $this->ContactReplyContent->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildUpdate("contactus", $this->UpdateFields, $this);
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

//Delete Method @2-3F1E29A6
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM contactus";
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

} //End contactusDataSource Class @2-FCB6E20C

//Include Page implementation @30-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Include Page implementation @31-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-0B504865
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
$TemplateFileName = "contactus_maint.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.datepicker.js|js/jquery/datepicker/ccs-date-timepicker.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-943F7E2F
include_once("./contactus_maint_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-9FA8143C
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
$contactus = new clsRecordcontactus("", $MainPage);
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
$MainPage->contactus = & $contactus;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("contactus", $contactus);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$contactus->Initialize();
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

//Execute Components @1-05269245
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$contactus->Operation();
//End Execute Components

//Go to destination page @1-5ABAB4E3
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($contactus);
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

//Unload Page @1-360936E6
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($contactus);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
