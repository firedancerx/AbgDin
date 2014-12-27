<?php
//Include Common Files @1-0C759CDD
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "sales_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordsales { //sales Class @2-33F972EF

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

//Class_Initialize Event @2-EAAC671A
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
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
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
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->Id = new clsControl(ccsTextBox, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", $Method, NULL), $this);
            $this->Id->Required = true;
            $this->SalesOrderNo = new clsControl(ccsTextBox, "SalesOrderNo", "Sales Order No", ccsInteger, "", CCGetRequestParam("SalesOrderNo", $Method, NULL), $this);
            $this->SalesOrderNo->Required = true;
            $this->SalesDate = new clsControl(ccsTextBox, "SalesDate", "Sales Date", ccsDate, array("ShortDate"), CCGetRequestParam("SalesDate", $Method, NULL), $this);
            $this->SalesDate->Required = true;
            $this->SalesTime = new clsControl(ccsTextBox, "SalesTime", "Sales Time", ccsDate, array("ShortDate"), CCGetRequestParam("SalesTime", $Method, NULL), $this);
            $this->SalesTime->Required = true;
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
            $this->SalesValue = new clsControl(ccsTextBox, "SalesValue", "Sales Value", ccsSingle, "", CCGetRequestParam("SalesValue", $Method, NULL), $this);
            $this->SalesRemarks = new clsControl(ccsTextBox, "SalesRemarks", "Sales Remarks", ccsText, "", CCGetRequestParam("SalesRemarks", $Method, NULL), $this);
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

//Validate Method @2-812DCA1F
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->Id->Validate() && $Validation);
        $Validation = ($this->SalesOrderNo->Validate() && $Validation);
        $Validation = ($this->SalesDate->Validate() && $Validation);
        $Validation = ($this->SalesTime->Validate() && $Validation);
        $Validation = ($this->SalesUser->Validate() && $Validation);
        $Validation = ($this->SalesItem->Validate() && $Validation);
        $Validation = ($this->SalesQuantity->Validate() && $Validation);
        $Validation = ($this->SalesPrice->Validate() && $Validation);
        $Validation = ($this->SalesValue->Validate() && $Validation);
        $Validation = ($this->SalesRemarks->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->Id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesOrderNo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesTime->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesUser->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesQuantity->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesPrice->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesValue->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesRemarks->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-0621EDA1
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Id->Errors->Count());
        $errors = ($errors || $this->SalesOrderNo->Errors->Count());
        $errors = ($errors || $this->SalesDate->Errors->Count());
        $errors = ($errors || $this->SalesTime->Errors->Count());
        $errors = ($errors || $this->SalesUser->Errors->Count());
        $errors = ($errors || $this->SalesItem->Errors->Count());
        $errors = ($errors || $this->SalesQuantity->Errors->Count());
        $errors = ($errors || $this->SalesPrice->Errors->Count());
        $errors = ($errors || $this->SalesValue->Errors->Count());
        $errors = ($errors || $this->SalesRemarks->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-CC57477A
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
        $Redirect = "sales_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
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

//InsertRow Method @2-052DDF2B
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->SalesOrderNo->SetValue($this->SalesOrderNo->GetValue(true));
        $this->DataSource->SalesDate->SetValue($this->SalesDate->GetValue(true));
        $this->DataSource->SalesTime->SetValue($this->SalesTime->GetValue(true));
        $this->DataSource->SalesUser->SetValue($this->SalesUser->GetValue(true));
        $this->DataSource->SalesItem->SetValue($this->SalesItem->GetValue(true));
        $this->DataSource->SalesQuantity->SetValue($this->SalesQuantity->GetValue(true));
        $this->DataSource->SalesPrice->SetValue($this->SalesPrice->GetValue(true));
        $this->DataSource->SalesValue->SetValue($this->SalesValue->GetValue(true));
        $this->DataSource->SalesRemarks->SetValue($this->SalesRemarks->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-F11BE9D0
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->SalesOrderNo->SetValue($this->SalesOrderNo->GetValue(true));
        $this->DataSource->SalesDate->SetValue($this->SalesDate->GetValue(true));
        $this->DataSource->SalesTime->SetValue($this->SalesTime->GetValue(true));
        $this->DataSource->SalesUser->SetValue($this->SalesUser->GetValue(true));
        $this->DataSource->SalesItem->SetValue($this->SalesItem->GetValue(true));
        $this->DataSource->SalesQuantity->SetValue($this->SalesQuantity->GetValue(true));
        $this->DataSource->SalesPrice->SetValue($this->SalesPrice->GetValue(true));
        $this->DataSource->SalesValue->SetValue($this->SalesValue->GetValue(true));
        $this->DataSource->SalesRemarks->SetValue($this->SalesRemarks->GetValue(true));
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

//Show Method @2-128690C0
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
                    $this->SalesOrderNo->SetValue($this->DataSource->SalesOrderNo->GetValue());
                    $this->SalesDate->SetValue($this->DataSource->SalesDate->GetValue());
                    $this->SalesTime->SetValue($this->DataSource->SalesTime->GetValue());
                    $this->SalesUser->SetValue($this->DataSource->SalesUser->GetValue());
                    $this->SalesItem->SetValue($this->DataSource->SalesItem->GetValue());
                    $this->SalesQuantity->SetValue($this->DataSource->SalesQuantity->GetValue());
                    $this->SalesPrice->SetValue($this->DataSource->SalesPrice->GetValue());
                    $this->SalesValue->SetValue($this->DataSource->SalesValue->GetValue());
                    $this->SalesRemarks->SetValue($this->DataSource->SalesRemarks->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->Id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesOrderNo->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesTime->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesUser->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesQuantity->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesPrice->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesValue->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesRemarks->Errors->ToString());
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
        $this->SalesOrderNo->Show();
        $this->SalesDate->Show();
        $this->SalesTime->Show();
        $this->SalesUser->Show();
        $this->SalesItem->Show();
        $this->SalesQuantity->Show();
        $this->SalesPrice->Show();
        $this->SalesValue->Show();
        $this->SalesRemarks->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End sales Class @2-FCB6E20C

class clssalesDataSource extends clsDBFuelSaver {  //salesDataSource Class @2-78DAF30D

//DataSource Variables @2-124AA5FE
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
    public $SalesOrderNo;
    public $SalesDate;
    public $SalesTime;
    public $SalesUser;
    public $SalesItem;
    public $SalesQuantity;
    public $SalesPrice;
    public $SalesValue;
    public $SalesRemarks;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-032ED14C
    function clssalesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record sales/Error";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->SalesOrderNo = new clsField("SalesOrderNo", ccsInteger, "");
        
        $this->SalesDate = new clsField("SalesDate", ccsDate, $this->DateFormat);
        
        $this->SalesTime = new clsField("SalesTime", ccsDate, $this->DateFormat);
        
        $this->SalesUser = new clsField("SalesUser", ccsInteger, "");
        
        $this->SalesItem = new clsField("SalesItem", ccsInteger, "");
        
        $this->SalesQuantity = new clsField("SalesQuantity", ccsSingle, "");
        
        $this->SalesPrice = new clsField("SalesPrice", ccsSingle, "");
        
        $this->SalesValue = new clsField("SalesValue", ccsSingle, "");
        
        $this->SalesRemarks = new clsField("SalesRemarks", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesOrderNo"] = array("Name" => "SalesOrderNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesDate"] = array("Name" => "SalesDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesTime"] = array("Name" => "SalesTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesUser"] = array("Name" => "SalesUser", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesItem"] = array("Name" => "SalesItem", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesQuantity"] = array("Name" => "SalesQuantity", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesPrice"] = array("Name" => "SalesPrice", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesValue"] = array("Name" => "SalesValue", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesRemarks"] = array("Name" => "SalesRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["Id"] = array("Name" => "Id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesOrderNo"] = array("Name" => "SalesOrderNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesDate"] = array("Name" => "SalesDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesTime"] = array("Name" => "SalesTime", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesUser"] = array("Name" => "SalesUser", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesItem"] = array("Name" => "SalesItem", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesQuantity"] = array("Name" => "SalesQuantity", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesPrice"] = array("Name" => "SalesPrice", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesValue"] = array("Name" => "SalesValue", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesRemarks"] = array("Name" => "SalesRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
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

//Open Method @2-F8C585FB
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

//SetValues Method @2-DF49BD3A
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->SalesOrderNo->SetDBValue(trim($this->f("SalesOrderNo")));
        $this->SalesDate->SetDBValue(trim($this->f("SalesDate")));
        $this->SalesTime->SetDBValue(trim($this->f("SalesTime")));
        $this->SalesUser->SetDBValue(trim($this->f("SalesUser")));
        $this->SalesItem->SetDBValue(trim($this->f("SalesItem")));
        $this->SalesQuantity->SetDBValue(trim($this->f("SalesQuantity")));
        $this->SalesPrice->SetDBValue(trim($this->f("SalesPrice")));
        $this->SalesValue->SetDBValue(trim($this->f("SalesValue")));
        $this->SalesRemarks->SetDBValue($this->f("SalesRemarks"));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @2-CE32F38E
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->InsertFields["SalesOrderNo"]["Value"] = $this->SalesOrderNo->GetDBValue(true);
        $this->InsertFields["SalesDate"]["Value"] = $this->SalesDate->GetDBValue(true);
        $this->InsertFields["SalesTime"]["Value"] = $this->SalesTime->GetDBValue(true);
        $this->InsertFields["SalesUser"]["Value"] = $this->SalesUser->GetDBValue(true);
        $this->InsertFields["SalesItem"]["Value"] = $this->SalesItem->GetDBValue(true);
        $this->InsertFields["SalesQuantity"]["Value"] = $this->SalesQuantity->GetDBValue(true);
        $this->InsertFields["SalesPrice"]["Value"] = $this->SalesPrice->GetDBValue(true);
        $this->InsertFields["SalesValue"]["Value"] = $this->SalesValue->GetDBValue(true);
        $this->InsertFields["SalesRemarks"]["Value"] = $this->SalesRemarks->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("sales", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-7157D432
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["Id"]["Value"] = $this->Id->GetDBValue(true);
        $this->UpdateFields["SalesOrderNo"]["Value"] = $this->SalesOrderNo->GetDBValue(true);
        $this->UpdateFields["SalesDate"]["Value"] = $this->SalesDate->GetDBValue(true);
        $this->UpdateFields["SalesTime"]["Value"] = $this->SalesTime->GetDBValue(true);
        $this->UpdateFields["SalesUser"]["Value"] = $this->SalesUser->GetDBValue(true);
        $this->UpdateFields["SalesItem"]["Value"] = $this->SalesItem->GetDBValue(true);
        $this->UpdateFields["SalesQuantity"]["Value"] = $this->SalesQuantity->GetDBValue(true);
        $this->UpdateFields["SalesPrice"]["Value"] = $this->SalesPrice->GetDBValue(true);
        $this->UpdateFields["SalesValue"]["Value"] = $this->SalesValue->GetDBValue(true);
        $this->UpdateFields["SalesRemarks"]["Value"] = $this->SalesRemarks->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildUpdate("sales", $this->UpdateFields, $this);
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

//Delete Method @2-7D696C38
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM sales";
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

} //End salesDataSource Class @2-FCB6E20C

//Include Page implementation @26-07AA2166
include_once(RelativePath . "/MenuIncludablePage.php");
//End Include Page implementation

//Include Page implementation @27-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-7CA3C92B
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
$TemplateFileName = "sales_maint.html";
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

//Include events file @1-97D07C94
include_once("./sales_maint_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-01C326CB
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
$MainPage->sales = & $sales;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("sales", $sales);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
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

//Execute Components @1-AF88EC62
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$sales->Operation();
//End Execute Components

//Go to destination page @1-2309E31B
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($sales);
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

//Unload Page @1-8A9CBB81
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($sales);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
