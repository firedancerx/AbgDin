<?php
//Include Common Files @1-742AC96F
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "users_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordusers { //users Class @2-9BE1AF6F

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

//Class_Initialize Event @2-7E7250F2
    function clsRecordusers($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record users/Error";
        $this->DataSource = new clsusersDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "users";
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
            $this->UserId = new clsControl(ccsTextBox, "UserId", "User Id", ccsText, "", CCGetRequestParam("UserId", $Method, NULL), $this);
            $this->UserId->Required = true;
            $this->UserPassword = new clsControl(ccsTextBox, "UserPassword", "User Password", ccsText, "", CCGetRequestParam("UserPassword", $Method, NULL), $this);
            $this->UserFullName = new clsControl(ccsTextBox, "UserFullName", "User Full Name", ccsText, "", CCGetRequestParam("UserFullName", $Method, NULL), $this);
            $this->UserFullName->Required = true;
            $this->UserRole = new clsControl(ccsListBox, "UserRole", "User Role", ccsInteger, "", CCGetRequestParam("UserRole", $Method, NULL), $this);
            $this->UserRole->DSType = dsTable;
            $this->UserRole->DataSource = new clsDBFuelSaver();
            $this->UserRole->ds = & $this->UserRole->DataSource;
            $this->UserRole->DataSource->SQL = "SELECT * \n" .
"FROM userroles {SQL_Where} {SQL_OrderBy}";
            list($this->UserRole->BoundColumn, $this->UserRole->TextColumn, $this->UserRole->DBFormat) = array("Id", "UserRole", "");
            $this->UserRole->Required = true;
            $this->UserEmail = new clsControl(ccsTextBox, "UserEmail", "User Email", ccsText, "", CCGetRequestParam("UserEmail", $Method, NULL), $this);
            $this->UserTelephone = new clsControl(ccsTextBox, "UserTelephone", "User Telephone", ccsText, "", CCGetRequestParam("UserTelephone", $Method, NULL), $this);
            $this->UserAddress1 = new clsControl(ccsTextBox, "UserAddress1", "User Address1", ccsText, "", CCGetRequestParam("UserAddress1", $Method, NULL), $this);
            $this->UserAddress2 = new clsControl(ccsTextBox, "UserAddress2", "User Address2", ccsText, "", CCGetRequestParam("UserAddress2", $Method, NULL), $this);
            $this->UserAddress3 = new clsControl(ccsTextBox, "UserAddress3", "User Address3", ccsText, "", CCGetRequestParam("UserAddress3", $Method, NULL), $this);
            $this->UserTown = new clsControl(ccsTextBox, "UserTown", "User Town", ccsText, "", CCGetRequestParam("UserTown", $Method, NULL), $this);
            $this->UserState = new clsControl(ccsListBox, "UserState", "User State", ccsInteger, "", CCGetRequestParam("UserState", $Method, NULL), $this);
            $this->UserState->DSType = dsTable;
            $this->UserState->DataSource = new clsDBFuelSaver();
            $this->UserState->ds = & $this->UserState->DataSource;
            $this->UserState->DataSource->SQL = "SELECT * \n" .
"FROM states {SQL_Where} {SQL_OrderBy}";
            list($this->UserState->BoundColumn, $this->UserState->TextColumn, $this->UserState->DBFormat) = array("Id", "State", "");
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

//Validate Method @2-C972583A
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->Id->Validate() && $Validation);
        $Validation = ($this->UserId->Validate() && $Validation);
        $Validation = ($this->UserPassword->Validate() && $Validation);
        $Validation = ($this->UserFullName->Validate() && $Validation);
        $Validation = ($this->UserRole->Validate() && $Validation);
        $Validation = ($this->UserEmail->Validate() && $Validation);
        $Validation = ($this->UserTelephone->Validate() && $Validation);
        $Validation = ($this->UserAddress1->Validate() && $Validation);
        $Validation = ($this->UserAddress2->Validate() && $Validation);
        $Validation = ($this->UserAddress3->Validate() && $Validation);
        $Validation = ($this->UserTown->Validate() && $Validation);
        $Validation = ($this->UserState->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->Id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserPassword->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserFullName->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserRole->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserEmail->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserTelephone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserAddress1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserAddress2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserAddress3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserTown->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserState->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-25B0900B
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Id->Errors->Count());
        $errors = ($errors || $this->UserId->Errors->Count());
        $errors = ($errors || $this->UserPassword->Errors->Count());
        $errors = ($errors || $this->UserFullName->Errors->Count());
        $errors = ($errors || $this->UserRole->Errors->Count());
        $errors = ($errors || $this->UserEmail->Errors->Count());
        $errors = ($errors || $this->UserTelephone->Errors->Count());
        $errors = ($errors || $this->UserAddress1->Errors->Count());
        $errors = ($errors || $this->UserAddress2->Errors->Count());
        $errors = ($errors || $this->UserAddress3->Errors->Count());
        $errors = ($errors || $this->UserTown->Errors->Count());
        $errors = ($errors || $this->UserState->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-0BAF16D6
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
        $Redirect = "users_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @2-2ED531B0
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->UserId->SetValue($this->UserId->GetValue(true));
        $this->DataSource->UserPassword->SetValue($this->UserPassword->GetValue(true));
        $this->DataSource->UserFullName->SetValue($this->UserFullName->GetValue(true));
        $this->DataSource->UserRole->SetValue($this->UserRole->GetValue(true));
        $this->DataSource->UserEmail->SetValue($this->UserEmail->GetValue(true));
        $this->DataSource->UserTelephone->SetValue($this->UserTelephone->GetValue(true));
        $this->DataSource->UserAddress1->SetValue($this->UserAddress1->GetValue(true));
        $this->DataSource->UserAddress2->SetValue($this->UserAddress2->GetValue(true));
        $this->DataSource->UserAddress3->SetValue($this->UserAddress3->GetValue(true));
        $this->DataSource->UserTown->SetValue($this->UserTown->GetValue(true));
        $this->DataSource->UserState->SetValue($this->UserState->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-09241256
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->UserId->SetValue($this->UserId->GetValue(true));
        $this->DataSource->UserPassword->SetValue($this->UserPassword->GetValue(true));
        $this->DataSource->UserFullName->SetValue($this->UserFullName->GetValue(true));
        $this->DataSource->UserRole->SetValue($this->UserRole->GetValue(true));
        $this->DataSource->UserEmail->SetValue($this->UserEmail->GetValue(true));
        $this->DataSource->UserTelephone->SetValue($this->UserTelephone->GetValue(true));
        $this->DataSource->UserAddress1->SetValue($this->UserAddress1->GetValue(true));
        $this->DataSource->UserAddress2->SetValue($this->UserAddress2->GetValue(true));
        $this->DataSource->UserAddress3->SetValue($this->UserAddress3->GetValue(true));
        $this->DataSource->UserTown->SetValue($this->UserTown->GetValue(true));
        $this->DataSource->UserState->SetValue($this->UserState->GetValue(true));
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

//Show Method @2-85A74CCD
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

        $this->UserRole->Prepare();
        $this->UserState->Prepare();

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
                    $this->UserId->SetValue($this->DataSource->UserId->GetValue());
                    $this->UserPassword->SetValue($this->DataSource->UserPassword->GetValue());
                    $this->UserFullName->SetValue($this->DataSource->UserFullName->GetValue());
                    $this->UserRole->SetValue($this->DataSource->UserRole->GetValue());
                    $this->UserEmail->SetValue($this->DataSource->UserEmail->GetValue());
                    $this->UserTelephone->SetValue($this->DataSource->UserTelephone->GetValue());
                    $this->UserAddress1->SetValue($this->DataSource->UserAddress1->GetValue());
                    $this->UserAddress2->SetValue($this->DataSource->UserAddress2->GetValue());
                    $this->UserAddress3->SetValue($this->DataSource->UserAddress3->GetValue());
                    $this->UserTown->SetValue($this->DataSource->UserTown->GetValue());
                    $this->UserState->SetValue($this->DataSource->UserState->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->Id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserPassword->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserFullName->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserRole->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserEmail->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserTelephone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserAddress1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserAddress2->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserAddress3->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserTown->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserState->Errors->ToString());
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
        $this->UserId->Show();
        $this->UserPassword->Show();
        $this->UserFullName->Show();
        $this->UserRole->Show();
        $this->UserEmail->Show();
        $this->UserTelephone->Show();
        $this->UserAddress1->Show();
        $this->UserAddress2->Show();
        $this->UserAddress3->Show();
        $this->UserTown->Show();
        $this->UserState->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End users Class @2-FCB6E20C

class clsusersDataSource extends clsDBFuelSaver {  //usersDataSource Class @2-032B5816

//DataSource Variables @2-40A52A6A
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
    public $UserId;
    public $UserPassword;
    public $UserFullName;
    public $UserRole;
    public $UserEmail;
    public $UserTelephone;
    public $UserAddress1;
    public $UserAddress2;
    public $UserAddress3;
    public $UserTown;
    public $UserState;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-6A3AF3B0
    function clsusersDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record users/Error";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->UserId = new clsField("UserId", ccsText, "");
        
        $this->UserPassword = new clsField("UserPassword", ccsText, "");
        
        $this->UserFullName = new clsField("UserFullName", ccsText, "");
        
        $this->UserRole = new clsField("UserRole", ccsInteger, "");
        
        $this->UserEmail = new clsField("UserEmail", ccsText, "");
        
        $this->UserTelephone = new clsField("UserTelephone", ccsText, "");
        
        $this->UserAddress1 = new clsField("UserAddress1", ccsText, "");
        
        $this->UserAddress2 = new clsField("UserAddress2", ccsText, "");
        
        $this->UserAddress3 = new clsField("UserAddress3", ccsText, "");
        
        $this->UserTown = new clsField("UserTown", ccsText, "");
        
        $this->UserState = new clsField("UserState", ccsInteger, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["UserId"] = array("Name" => "UserId", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserPassword"] = array("Name" => "UserPassword", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserFullName"] = array("Name" => "UserFullName", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserRole"] = array("Name" => "UserRole", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["UserEmail"] = array("Name" => "UserEmail", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserTelephone"] = array("Name" => "UserTelephone", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserAddress1"] = array("Name" => "UserAddress1", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserAddress2"] = array("Name" => "UserAddress2", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserAddress3"] = array("Name" => "UserAddress3", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserTown"] = array("Name" => "UserTown", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["UserState"] = array("Name" => "UserState", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserId"] = array("Name" => "UserId", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserPassword"] = array("Name" => "UserPassword", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserFullName"] = array("Name" => "UserFullName", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserRole"] = array("Name" => "UserRole", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserEmail"] = array("Name" => "UserEmail", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserTelephone"] = array("Name" => "UserTelephone", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserAddress1"] = array("Name" => "UserAddress1", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserAddress2"] = array("Name" => "UserAddress2", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserAddress3"] = array("Name" => "UserAddress3", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserTown"] = array("Name" => "UserTown", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserState"] = array("Name" => "UserState", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
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

//Open Method @2-B071412E
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM users {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-4E385AF5
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->UserId->SetDBValue($this->f("UserId"));
        $this->UserPassword->SetDBValue($this->f("UserPassword"));
        $this->UserFullName->SetDBValue($this->f("UserFullName"));
        $this->UserRole->SetDBValue(trim($this->f("UserRole")));
        $this->UserEmail->SetDBValue($this->f("UserEmail"));
        $this->UserTelephone->SetDBValue($this->f("UserTelephone"));
        $this->UserAddress1->SetDBValue($this->f("UserAddress1"));
        $this->UserAddress2->SetDBValue($this->f("UserAddress2"));
        $this->UserAddress3->SetDBValue($this->f("UserAddress3"));
        $this->UserTown->SetDBValue($this->f("UserTown"));
        $this->UserState->SetDBValue(trim($this->f("UserState")));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @2-60FF19EB
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->InsertFields["UserId"]["Value"] = $this->UserId->GetDBValue(true);
        $this->InsertFields["UserPassword"]["Value"] = $this->UserPassword->GetDBValue(true);
        $this->InsertFields["UserFullName"]["Value"] = $this->UserFullName->GetDBValue(true);
        $this->InsertFields["UserRole"]["Value"] = $this->UserRole->GetDBValue(true);
        $this->InsertFields["UserEmail"]["Value"] = $this->UserEmail->GetDBValue(true);
        $this->InsertFields["UserTelephone"]["Value"] = $this->UserTelephone->GetDBValue(true);
        $this->InsertFields["UserAddress1"]["Value"] = $this->UserAddress1->GetDBValue(true);
        $this->InsertFields["UserAddress2"]["Value"] = $this->UserAddress2->GetDBValue(true);
        $this->InsertFields["UserAddress3"]["Value"] = $this->UserAddress3->GetDBValue(true);
        $this->InsertFields["UserTown"]["Value"] = $this->UserTown->GetDBValue(true);
        $this->InsertFields["UserState"]["Value"] = $this->UserState->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("users", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-1EEA0171
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->UpdateFields["UserId"]["Value"] = $this->UserId->GetDBValue(true);
        $this->UpdateFields["UserPassword"]["Value"] = $this->UserPassword->GetDBValue(true);
        $this->UpdateFields["UserFullName"]["Value"] = $this->UserFullName->GetDBValue(true);
        $this->UpdateFields["UserRole"]["Value"] = $this->UserRole->GetDBValue(true);
        $this->UpdateFields["UserEmail"]["Value"] = $this->UserEmail->GetDBValue(true);
        $this->UpdateFields["UserTelephone"]["Value"] = $this->UserTelephone->GetDBValue(true);
        $this->UpdateFields["UserAddress1"]["Value"] = $this->UserAddress1->GetDBValue(true);
        $this->UpdateFields["UserAddress2"]["Value"] = $this->UserAddress2->GetDBValue(true);
        $this->UpdateFields["UserAddress3"]["Value"] = $this->UserAddress3->GetDBValue(true);
        $this->UpdateFields["UserTown"]["Value"] = $this->UserTown->GetDBValue(true);
        $this->UpdateFields["UserState"]["Value"] = $this->UserState->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildUpdate("users", $this->UpdateFields, $this);
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

//Delete Method @2-4AB027F1
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM users";
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

} //End usersDataSource Class @2-FCB6E20C

//Include Page implementation @26-07AA2166
include_once(RelativePath . "/MenuIncludablePage.php");
//End Include Page implementation

//Include Page implementation @27-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-8176188A
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
$TemplateFileName = "users_maint.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-0F4F859F
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
$users = new clsRecordusers("", $MainPage);
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
$MainPage->users = & $users;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("users", $users);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$users->Initialize();
$ScriptIncludes = "";
$SList = explode("|", $Scripts);
foreach ($SList as $Script) {
    if ($Script != "") $ScriptIncludes = $ScriptIncludes . "<script src=\"" . $PathToRoot . $Script . "\" type=\"text/javascript\"></script>\n";
}
$Attributes->SetValue("scriptIncludes", $ScriptIncludes);

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

//Execute Components @1-634B92DC
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$users->Operation();
//End Execute Components

//Go to destination page @1-51B81BF9
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($users);
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

//Unload Page @1-90507693
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($users);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
