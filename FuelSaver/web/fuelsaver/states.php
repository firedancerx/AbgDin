<?php
//Include Common Files @1-1B24C61C
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "states.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsRecordstatesSearch { //statesSearch Class @32-1DAABA11

//Variables @32-9E315808

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

//Class_Initialize Event @32-8912D791
    function clsRecordstatesSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record statesSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "statesSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_Id = new clsControl(ccsHidden, "s_Id", "Id", ccsInteger, "", CCGetRequestParam("s_Id", $Method, NULL), $this);
            $this->s_State = new clsControl(ccsTextBox, "s_State", "State", ccsText, "", CCGetRequestParam("s_State", $Method, NULL), $this);
            $this->s_Country = new clsControl(ccsListBox, "s_Country", "Country", ccsInteger, "", CCGetRequestParam("s_Country", $Method, NULL), $this);
            $this->s_Country->DSType = dsTable;
            $this->s_Country->DataSource = new clsDBFuelSaver();
            $this->s_Country->ds = & $this->s_Country->DataSource;
            $this->s_Country->DataSource->SQL = "SELECT * \n" .
"FROM countries {SQL_Where} {SQL_OrderBy}";
            list($this->s_Country->BoundColumn, $this->s_Country->TextColumn, $this->s_Country->DBFormat) = array("Id", "Country", "");
            $this->s_SalesOffice = new clsControl(ccsListBox, "s_SalesOffice", "Sales Office", ccsInteger, "", CCGetRequestParam("s_SalesOffice", $Method, NULL), $this);
            $this->s_SalesOffice->DSType = dsTable;
            $this->s_SalesOffice->DataSource = new clsDBFuelSaver();
            $this->s_SalesOffice->ds = & $this->s_SalesOffice->DataSource;
            $this->s_SalesOffice->DataSource->SQL = "SELECT * \n" .
"FROM salesoffices {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesOffice->BoundColumn, $this->s_SalesOffice->TextColumn, $this->s_SalesOffice->DBFormat) = array("Id", "SalesOffice", "");
            $this->s_IsActive = new clsControl(ccsCheckBox, "s_IsActive", "s_IsActive", ccsInteger, "", CCGetRequestParam("s_IsActive", $Method, NULL), $this);
            $this->s_IsActive->CheckedValue = $this->s_IsActive->GetParsedValue(1);
            $this->s_IsActive->UncheckedValue = $this->s_IsActive->GetParsedValue(0);
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_IsActive->Value) && !strlen($this->s_IsActive->Value) && $this->s_IsActive->Value !== false)
                    $this->s_IsActive->SetValue(false);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @32-9A6A279B
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_Id->Validate() && $Validation);
        $Validation = ($this->s_State->Validate() && $Validation);
        $Validation = ($this->s_Country->Validate() && $Validation);
        $Validation = ($this->s_SalesOffice->Validate() && $Validation);
        $Validation = ($this->s_IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_Id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_State->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_Country->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_SalesOffice->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @32-F0F047AB
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_Id->Errors->Count());
        $errors = ($errors || $this->s_State->Errors->Count());
        $errors = ($errors || $this->s_Country->Errors->Count());
        $errors = ($errors || $this->s_SalesOffice->Errors->Count());
        $errors = ($errors || $this->s_IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @32-61ADC6BF
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
        $Redirect = "states.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "states.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @32-7FB758C3
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

        $this->s_Country->Prepare();
        $this->s_SalesOffice->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_Id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_State->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_Country->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_SalesOffice->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_IsActive->Errors->ToString());
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
        $this->s_Id->Show();
        $this->s_State->Show();
        $this->s_Country->Show();
        $this->s_SalesOffice->Show();
        $this->s_IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End statesSearch Class @32-FCB6E20C

class clsEditableGridstates1 { //states1 Class @8-E8D16E0F

//Variables @8-82C9E984

    // Public variables
    public $ComponentType = "EditableGrid";
    public $ComponentName;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormParameters;
    public $FormState;
    public $FormEnctype;
    public $CachedColumns;
    public $TotalRows;
    public $UpdatedRows;
    public $EmptyRows;
    public $Visible;
    public $RowsErrors;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode;
    public $ValidatingControls;
    public $Controls;
    public $ControlsErrors;
    public $RowNumber;
    public $Attributes;

    // Class variables
    public $Sorter_Id;
    public $Sorter_State;
    public $Sorter_Country;
    public $Sorter_SalesOffice;
    public $Sorter_IsActive;
//End Variables

//Class_Initialize Event @8-4F5CBCFC
    function clsEditableGridstates1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "EditableGrid states1/Error";
        $this->ControlsErrors = array();
        $this->ComponentName = "states1";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->CachedColumns[""][0] = "";
        $this->DataSource = new clsstates1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: EditableGrid " . $this->ComponentName . "<BR>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->EmptyRows = 0;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if(!$this->Visible) return;

        $CCSForm = CCGetFromGet("ccsForm", "");
        $this->FormEnctype = "application/x-www-form-urlencoded";
        $this->FormSubmitted = ($CCSForm == $this->ComponentName);
        if($this->FormSubmitted) {
            $this->FormState = CCGetFromPost("FormState", "");
            $this->SetFormState($this->FormState);
        } else {
            $this->FormState = "";
        }
        $Method = $this->FormSubmitted ? ccsPost : ccsGet;

        $this->SorterName = CCGetParam("states1Order", "");
        $this->SorterDirection = CCGetParam("states1Dir", "");

        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_State = new clsSorter($this->ComponentName, "Sorter_State", $FileName, $this);
        $this->Sorter_Country = new clsSorter($this->ComponentName, "Sorter_Country", $FileName, $this);
        $this->Sorter_SalesOffice = new clsSorter($this->ComponentName, "Sorter_SalesOffice", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Id = new clsControl(ccsTextBox, "Id", "Id", ccsInteger, "", NULL, $this);
        $this->State = new clsControl(ccsTextBox, "State", "State", ccsText, "", NULL, $this);
        $this->State->Required = true;
        $this->Country = new clsControl(ccsListBox, "Country", "Country", ccsInteger, "", NULL, $this);
        $this->Country->DSType = dsTable;
        $this->Country->DataSource = new clsDBFuelSaver();
        $this->Country->ds = & $this->Country->DataSource;
        $this->Country->DataSource->SQL = "SELECT * \n" .
"FROM countries {SQL_Where} {SQL_OrderBy}";
        list($this->Country->BoundColumn, $this->Country->TextColumn, $this->Country->DBFormat) = array("Id", "Country", "");
        $this->Country->Required = true;
        $this->SalesOffice = new clsControl(ccsListBox, "SalesOffice", "Sales Office", ccsInteger, "", NULL, $this);
        $this->SalesOffice->DSType = dsTable;
        $this->SalesOffice->DataSource = new clsDBFuelSaver();
        $this->SalesOffice->ds = & $this->SalesOffice->DataSource;
        $this->SalesOffice->DataSource->SQL = "SELECT * \n" .
"FROM salesoffices {SQL_Where} {SQL_OrderBy}";
        list($this->SalesOffice->BoundColumn, $this->SalesOffice->TextColumn, $this->SalesOffice->DBFormat) = array("Id", "SalesOffice", "");
        $this->SalesOffice->Required = true;
        $this->IsActive = new clsControl(ccsTextBox, "IsActive", "Is Active", ccsInteger, "", NULL, $this);
        $this->IsActive->Required = true;
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
        $this->Button_Submit = new clsButton("Button_Submit", $Method, $this);
        $this->Cancel = new clsButton("Cancel", $Method, $this);
    }
//End Class_Initialize Event

//Initialize Method @8-02F1873E
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);

        $this->DataSource->Parameters["urls_Id"] = CCGetFromGet("s_Id", NULL);
        $this->DataSource->Parameters["urls_State"] = CCGetFromGet("s_State", NULL);
        $this->DataSource->Parameters["urls_Country"] = CCGetFromGet("s_Country", NULL);
        $this->DataSource->Parameters["urls_SalesOffice"] = CCGetFromGet("s_SalesOffice", NULL);
        $this->DataSource->Parameters["urls_IsActive"] = CCGetFromGet("s_IsActive", NULL);
    }
//End Initialize Method

//GetFormParameters Method @8-B5C3BBFB
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["Id"][$RowNumber] = CCGetFromPost("Id_" . $RowNumber, NULL);
            $this->FormParameters["State"][$RowNumber] = CCGetFromPost("State_" . $RowNumber, NULL);
            $this->FormParameters["Country"][$RowNumber] = CCGetFromPost("Country_" . $RowNumber, NULL);
            $this->FormParameters["SalesOffice"][$RowNumber] = CCGetFromPost("SalesOffice_" . $RowNumber, NULL);
            $this->FormParameters["IsActive"][$RowNumber] = CCGetFromPost("IsActive_" . $RowNumber, NULL);
        }
    }
//End GetFormParameters Method

//Validate Method @8-96B22D8B
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);

        for($this->RowNumber = 1; $this->RowNumber <= $this->TotalRows; $this->RowNumber++)
        {
            $this->DataSource->CachedColumns[""] = $this->CachedColumns[""][$this->RowNumber];
            $this->DataSource->CurrentRow = $this->RowNumber;
            $this->Id->SetText($this->FormParameters["Id"][$this->RowNumber], $this->RowNumber);
            $this->State->SetText($this->FormParameters["State"][$this->RowNumber], $this->RowNumber);
            $this->Country->SetText($this->FormParameters["Country"][$this->RowNumber], $this->RowNumber);
            $this->SalesOffice->SetText($this->FormParameters["SalesOffice"][$this->RowNumber], $this->RowNumber);
            $this->IsActive->SetText($this->FormParameters["IsActive"][$this->RowNumber], $this->RowNumber);
            if ($this->UpdatedRows >= $this->RowNumber) {
                $Validation = ($this->ValidateRow($this->RowNumber) && $Validation);
            }
            else if($this->CheckInsert())
            {
                $Validation = ($this->ValidateRow() && $Validation);
            }
        }
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//ValidateRow Method @8-1D1024B3
    function ValidateRow()
    {
        global $CCSLocales;
        $this->Id->Validate();
        $this->State->Validate();
        $this->Country->Validate();
        $this->SalesOffice->Validate();
        $this->IsActive->Validate();
        $this->RowErrors = new clsErrors();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidateRow", $this);
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->State->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Country->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesOffice->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $this->Id->Errors->Clear();
        $this->State->Errors->Clear();
        $this->Country->Errors->Clear();
        $this->SalesOffice->Errors->Clear();
        $this->IsActive->Errors->Clear();
        $errors = ComposeStrings($errors, $this->RowErrors->ToString());
        $this->RowsErrors[$this->RowNumber] = $errors;
        return $errors != "" ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @8-4562EA0F
    function CheckInsert()
    {
        $filed = false;
        $filed = ($filed || (is_array($this->FormParameters["Id"][$this->RowNumber]) && count($this->FormParameters["Id"][$this->RowNumber])) || strlen($this->FormParameters["Id"][$this->RowNumber]));
        $filed = ($filed || (is_array($this->FormParameters["State"][$this->RowNumber]) && count($this->FormParameters["State"][$this->RowNumber])) || strlen($this->FormParameters["State"][$this->RowNumber]));
        $filed = ($filed || (is_array($this->FormParameters["Country"][$this->RowNumber]) && count($this->FormParameters["Country"][$this->RowNumber])) || strlen($this->FormParameters["Country"][$this->RowNumber]));
        $filed = ($filed || (is_array($this->FormParameters["SalesOffice"][$this->RowNumber]) && count($this->FormParameters["SalesOffice"][$this->RowNumber])) || strlen($this->FormParameters["SalesOffice"][$this->RowNumber]));
        $filed = ($filed || (is_array($this->FormParameters["IsActive"][$this->RowNumber]) && count($this->FormParameters["IsActive"][$this->RowNumber])) || strlen($this->FormParameters["IsActive"][$this->RowNumber]));
        return $filed;
    }
//End CheckInsert Method

//CheckErrors Method @8-F5A3B433
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @8-6B923CC2
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted)
            return;

        $this->GetFormParameters();
        $this->PressedButton = "Button_Submit";
        if($this->Button_Submit->Pressed) {
            $this->PressedButton = "Button_Submit";
        } else if($this->Cancel->Pressed) {
            $this->PressedButton = "Cancel";
        }

        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Submit") {
            if(!CCGetEvent($this->Button_Submit->CCSEvents, "OnClick", $this->Button_Submit) || !$this->UpdateGrid()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick", $this->Cancel)) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//UpdateGrid Method @8-D5E6C0CC
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit", $this);
        if(!$this->Validate()) return;
        $Validation = true;
        for($this->RowNumber = 1; $this->RowNumber <= $this->TotalRows; $this->RowNumber++)
        {
            $this->DataSource->CachedColumns[""] = $this->CachedColumns[""][$this->RowNumber];
            $this->DataSource->CurrentRow = $this->RowNumber;
            $this->Id->SetText($this->FormParameters["Id"][$this->RowNumber], $this->RowNumber);
            $this->State->SetText($this->FormParameters["State"][$this->RowNumber], $this->RowNumber);
            $this->Country->SetText($this->FormParameters["Country"][$this->RowNumber], $this->RowNumber);
            $this->SalesOffice->SetText($this->FormParameters["SalesOffice"][$this->RowNumber], $this->RowNumber);
            $this->IsActive->SetText($this->FormParameters["IsActive"][$this->RowNumber], $this->RowNumber);
            if ($this->UpdatedRows >= $this->RowNumber) {
                if($this->UpdateAllowed) { $Validation = ($this->UpdateRow() && $Validation); }
            }
            else if($this->CheckInsert() && $this->InsertAllowed)
            {
                $Validation = ($Validation && $this->InsertRow());
            }
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterSubmit", $this);
        if ($this->Errors->Count() == 0 && $Validation){
            $this->DataSource->close();
            return true;
        }
        return false;
    }
//End UpdateGrid Method

//UpdateRow Method @8-8B5B8217
    function UpdateRow()
    {
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->State->SetValue($this->State->GetValue(true));
        $this->DataSource->Country->SetValue($this->Country->GetValue(true));
        $this->DataSource->SalesOffice->SetValue($this->SalesOffice->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Update();
        $errors = "";
        if($this->DataSource->Errors->Count() > 0) {
            $errors = $this->DataSource->Errors->ToString();
            $this->RowsErrors[$this->RowNumber] = $errors;
            $this->DataSource->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End UpdateRow Method

//FormScript Method @8-421A3322
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\" type=\"text/javascript\">\n<!--\n";
        $script .= "var states1Elements;\n";
        $script .= "var states1EmptyRows = 0;\n";
        $script .= "var " . $this->ComponentName . "IdID = 0;\n";
        $script .= "var " . $this->ComponentName . "StateID = 1;\n";
        $script .= "var " . $this->ComponentName . "CountryID = 2;\n";
        $script .= "var " . $this->ComponentName . "SalesOfficeID = 3;\n";
        $script .= "var " . $this->ComponentName . "IsActiveID = 4;\n";
        $script .= "\nfunction initstates1Elements() {\n";
        $script .= "\tvar ED = document.forms[\"states1\"];\n";
        $script .= "\tstates1Elements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.Id_" . $i . ", " . "ED.State_" . $i . ", " . "ED.Country_" . $i . ", " . "ED.SalesOffice_" . $i . ", " . "ED.IsActive_" . $i . ")";
            if($i != $TotalRows) $script .= ",\n";
        }
        $script .= ");\n";
        $script .= "}\n";
        $script .= "\n//-->\n</script>";
        return $script;
    }
//End FormScript Method

//SetFormState Method @8-2C052262
    function SetFormState($FormState)
    {
        if(strlen($FormState)) {
            $FormState = str_replace("\\\\", "\\" . ord("\\"), $FormState);
            $FormState = str_replace("\\;", "\\" . ord(";"), $FormState);
            $pieces = explode(";", $FormState);
            $this->UpdatedRows = $pieces[0];
            $this->EmptyRows   = $pieces[1];
            $this->TotalRows = $this->UpdatedRows + $this->EmptyRows;
            $RowNumber = 0;
            for($i = 2; $i < sizeof($pieces); $i = $i + 1)  {
                $piece = $pieces[$i + 0];
                $piece = str_replace("\\" . ord("\\"), "\\", $piece);
                $piece = str_replace("\\" . ord(";"), ";", $piece);
                $this->CachedColumns[""][$RowNumber] = $piece;
                $RowNumber++;
            }

            if(!$RowNumber) { $RowNumber = 1; }
            for($i = 1; $i <= $this->EmptyRows; $i++) {
                $this->CachedColumns[""][$RowNumber] = "";
                $RowNumber++;
            }
        }
    }
//End SetFormState Method

//GetFormState Method @8-240E8836
    function GetFormState($NonEmptyRows)
    {
        if(!$this->FormSubmitted) {
            $this->FormState  = $NonEmptyRows . ";";
            $this->FormState .= $this->InsertAllowed ? $this->EmptyRows : "0";
            if($NonEmptyRows) {
                for($i = 0; $i <= $NonEmptyRows; $i++) {
                    $this->FormState .= ";" . str_replace(";", "\\;", str_replace("\\", "\\\\", $this->CachedColumns[""][$i]));
                }
            }
        }
        return $this->FormState;
    }
//End GetFormState Method

//Show Method @8-7AB7C41B
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $FileName;
        global $CCSLocales;
        global $CCSUseAmp;
        $Error = "";

        if(!$this->Visible) { return; }

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->Country->Prepare();
        $this->SalesOffice->Prepare();

        $this->DataSource->open();
        $is_next_record = ($this->ReadAllowed && $this->DataSource->next_record());
        $this->IsEmpty = ! $is_next_record;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) { return; }

        $this->Attributes->Show();
        $this->Button_Submit->Visible = $this->Button_Submit->Visible && ($this->InsertAllowed || $this->UpdateAllowed || $this->DeleteAllowed);
        $ParentPath = $Tpl->block_path;
        $EditableGridPath = $ParentPath . "/EditableGrid " . $this->ComponentName;
        $EditableGridRowPath = $ParentPath . "/EditableGrid " . $this->ComponentName . "/Row";
        $Tpl->block_path = $EditableGridRowPath;
        $this->RowNumber = 0;
        $NonEmptyRows = 0;
        $EmptyRowsLeft = $this->EmptyRows;
        $this->ControlsVisible["Id"] = $this->Id->Visible;
        $this->ControlsVisible["State"] = $this->State->Visible;
        $this->ControlsVisible["Country"] = $this->Country->Visible;
        $this->ControlsVisible["SalesOffice"] = $this->SalesOffice->Visible;
        $this->ControlsVisible["IsActive"] = $this->IsActive->Visible;
        if ($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed)) {
            do {
                $this->RowNumber++;
                if($is_next_record) {
                    $NonEmptyRows++;
                    $this->DataSource->SetValues();
                }
                if (!($this->FormSubmitted) && $is_next_record) {
                    $this->CachedColumns[""][$this->RowNumber] = $this->DataSource->CachedColumns[""];
                    $this->Id->SetValue($this->DataSource->Id->GetValue());
                    $this->State->SetValue($this->DataSource->State->GetValue());
                    $this->Country->SetValue($this->DataSource->Country->GetValue());
                    $this->SalesOffice->SetValue($this->DataSource->SalesOffice->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                } elseif ($this->FormSubmitted && $is_next_record) {
                    $this->Id->SetText($this->FormParameters["Id"][$this->RowNumber], $this->RowNumber);
                    $this->State->SetText($this->FormParameters["State"][$this->RowNumber], $this->RowNumber);
                    $this->Country->SetText($this->FormParameters["Country"][$this->RowNumber], $this->RowNumber);
                    $this->SalesOffice->SetText($this->FormParameters["SalesOffice"][$this->RowNumber], $this->RowNumber);
                    $this->IsActive->SetText($this->FormParameters["IsActive"][$this->RowNumber], $this->RowNumber);
                } elseif (!$this->FormSubmitted) {
                    $this->CachedColumns[""][$this->RowNumber] = "";
                    $this->Id->SetText("");
                    $this->State->SetText("");
                    $this->Country->SetText("");
                    $this->SalesOffice->SetText("");
                    $this->IsActive->SetText("");
                } else {
                    $this->Id->SetText($this->FormParameters["Id"][$this->RowNumber], $this->RowNumber);
                    $this->State->SetText($this->FormParameters["State"][$this->RowNumber], $this->RowNumber);
                    $this->Country->SetText($this->FormParameters["Country"][$this->RowNumber], $this->RowNumber);
                    $this->SalesOffice->SetText($this->FormParameters["SalesOffice"][$this->RowNumber], $this->RowNumber);
                    $this->IsActive->SetText($this->FormParameters["IsActive"][$this->RowNumber], $this->RowNumber);
                }
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show($this->RowNumber);
                $this->State->Show($this->RowNumber);
                $this->Country->Show($this->RowNumber);
                $this->SalesOffice->Show($this->RowNumber);
                $this->IsActive->Show($this->RowNumber);
                if (isset($this->RowsErrors[$this->RowNumber]) && ($this->RowsErrors[$this->RowNumber] != "")) {
                    $Tpl->setblockvar("RowError", "");
                    $Tpl->setvar("Error", $this->RowsErrors[$this->RowNumber]);
                    $this->Attributes->Show();
                    $Tpl->parse("RowError", false);
                } else {
                    $Tpl->setblockvar("RowError", "");
                }
                $Tpl->setvar("FormScript", $this->FormScript($this->RowNumber));
                $Tpl->parse();
                if ($is_next_record) {
                    if ($this->FormSubmitted) {
                        $is_next_record = $this->RowNumber < $this->UpdatedRows;
                        if (($this->DataSource->CachedColumns[""] == $this->CachedColumns[""][$this->RowNumber])) {
                            if ($this->ReadAllowed) $this->DataSource->next_record();
                        }
                    }else{
                        $is_next_record = ($this->RowNumber < $this->PageSize) &&  $this->ReadAllowed && $this->DataSource->next_record();
                    }
                } else { 
                    $EmptyRowsLeft--;
                }
            } while($is_next_record || ($EmptyRowsLeft && $this->InsertAllowed));
        } else {
            $Tpl->block_path = $EditableGridPath;
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $Tpl->block_path = $EditableGridPath;
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if (($this->Navigator->TotalPages <= 1 && $this->Navigator->PageNumber == 1) || $this->Navigator->PageSize == "") {
            $this->Navigator->Visible = false;
        }
        $this->Sorter_Id->Show();
        $this->Sorter_State->Show();
        $this->Sorter_Country->Show();
        $this->Sorter_SalesOffice->Show();
        $this->Sorter_IsActive->Show();
        $this->Navigator->Show();
        $this->Button_Submit->Show();
        $this->Cancel->Show();

        if($this->CheckErrors()) {
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        if (!$CCSUseAmp) {
            $Tpl->SetVar("HTMLFormProperties", "method=\"POST\" action=\"" . $this->HTMLFormAction . "\" name=\"" . $this->ComponentName . "\"");
        } else {
            $Tpl->SetVar("HTMLFormProperties", "method=\"post\" action=\"" . str_replace("&", "&amp;", $this->HTMLFormAction) . "\" id=\"" . $this->ComponentName . "\"");
        }
        $Tpl->SetVar("FormState", CCToHTML($this->GetFormState($NonEmptyRows)));
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End states1 Class @8-FCB6E20C

class clsstates1DataSource extends clsDBFuelSaver {  //states1DataSource Class @8-684987C0

//DataSource Variables @8-EF08FAE2
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $UpdateParameters;
    public $CountSQL;
    public $wp;
    public $AllParametersSet;

    public $CachedColumns;
    public $CurrentRow;
    public $UpdateFields = array();

    // Datasource fields
    public $Id;
    public $State;
    public $Country;
    public $SalesOffice;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-814E5604
    function clsstates1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "EditableGrid states1/Error";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->State = new clsField("State", ccsText, "");
        
        $this->Country = new clsField("Country", ccsInteger, "");
        
        $this->SalesOffice = new clsField("SalesOffice", ccsInteger, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->UpdateFields["State"] = array("Name" => "State", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["Country"] = array("Name" => "Country", "Value" => "", "DataType" => ccsInteger);
        $this->UpdateFields["SalesOffice"] = array("Name" => "SalesOffice", "Value" => "", "DataType" => ccsInteger);
        $this->UpdateFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
    }
//End DataSourceClass_Initialize Event

//SetOrder Method @8-82E4599A
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("states.Id", ""), 
            "Sorter_State" => array("State", ""), 
            "Sorter_Country" => array("states.Country", ""), 
            "Sorter_SalesOffice" => array("states.SalesOffice", ""), 
            "Sorter_IsActive" => array("states.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @8-B83CCAAB
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_Id", ccsInteger, "", "", $this->Parameters["urls_Id"], "", false);
        $this->wp->AddParameter("2", "urls_State", ccsText, "", "", $this->Parameters["urls_State"], "", false);
        $this->wp->AddParameter("3", "urls_Country", ccsInteger, "", "", $this->Parameters["urls_Country"], "", false);
        $this->wp->AddParameter("4", "urls_SalesOffice", ccsInteger, "", "", $this->Parameters["urls_SalesOffice"], "", false);
        $this->wp->AddParameter("5", "urls_IsActive", ccsInteger, "", "", $this->Parameters["urls_IsActive"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "Id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "State", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "Country", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "SalesOffice", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsInteger),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "IsActive", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]), 
             $this->wp->Criterion[5]);
    }
//End Prepare Method

//Open Method @8-08C319F8
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM states";
        $this->SQL = "SELECT * \n\n" .
        "FROM states {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @8-4CAA2D40
    function SetValues()
    {
        $this->CachedColumns[""] = $this->f("");
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->State->SetDBValue($this->f("State"));
        $this->Country->SetDBValue(trim($this->f("Country")));
        $this->SalesOffice->SetDBValue(trim($this->f("SalesOffice")));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Update Method @8-4A58FD22
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->cp["State"] = new clsSQLParameter("ctrlState", ccsText, "", "", $this->State->GetValue(true), NULL, false, $this->ErrorBlock);
        $this->cp["Country"] = new clsSQLParameter("ctrlCountry", ccsInteger, "", "", $this->Country->GetValue(true), "", false, $this->ErrorBlock);
        $this->cp["SalesOffice"] = new clsSQLParameter("ctrlSalesOffice", ccsInteger, "", "", $this->SalesOffice->GetValue(true), "", false, $this->ErrorBlock);
        $this->cp["IsActive"] = new clsSQLParameter("ctrlIsActive", ccsInteger, "", "", $this->IsActive->GetValue(true), "", false, $this->ErrorBlock);
        $wp = new clsSQLParameters($this->ErrorBlock);
        $wp->AddParameter("1", "ctrlId", ccsInteger, "", "", $this->Id->GetValue(true), "", false);
        if(!$wp->AllParamsSet()) {
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        }
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        if (!is_null($this->cp["State"]->GetValue()) and !strlen($this->cp["State"]->GetText()) and !is_bool($this->cp["State"]->GetValue())) 
            $this->cp["State"]->SetValue($this->State->GetValue(true));
        if (!is_null($this->cp["Country"]->GetValue()) and !strlen($this->cp["Country"]->GetText()) and !is_bool($this->cp["Country"]->GetValue())) 
            $this->cp["Country"]->SetValue($this->Country->GetValue(true));
        if (!is_null($this->cp["SalesOffice"]->GetValue()) and !strlen($this->cp["SalesOffice"]->GetText()) and !is_bool($this->cp["SalesOffice"]->GetValue())) 
            $this->cp["SalesOffice"]->SetValue($this->SalesOffice->GetValue(true));
        if (!is_null($this->cp["IsActive"]->GetValue()) and !strlen($this->cp["IsActive"]->GetText()) and !is_bool($this->cp["IsActive"]->GetValue())) 
            $this->cp["IsActive"]->SetValue($this->IsActive->GetValue(true));
        $wp->Criterion[1] = $wp->Operation(opEqual, "Id", $wp->GetDBValue("1"), $this->ToSQL($wp->GetDBValue("1"), ccsInteger),false);
        $Where = 
             $wp->Criterion[1];
        $this->UpdateFields["State"]["Value"] = $this->cp["State"]->GetDBValue(true);
        $this->UpdateFields["Country"]["Value"] = $this->cp["Country"]->GetDBValue(true);
        $this->UpdateFields["SalesOffice"]["Value"] = $this->cp["SalesOffice"]->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->cp["IsActive"]->GetDBValue(true);
        $this->SQL = CCBuildUpdate("states", $this->UpdateFields, $this);
        $this->SQL .= strlen($Where) ? " WHERE " . $Where : $Where;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

} //End states1DataSource Class @8-FCB6E20C

//Include Page implementation @50-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Initialize Page @1-0F92F161
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
$TemplateFileName = "states.html";
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

//Initialize Objects @1-33FB45E0
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
$statesSearch = new clsRecordstatesSearch("", $MainPage);
$states1 = new clsEditableGridstates1("", $MainPage);
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
$MainPage->statesSearch = & $statesSearch;
$MainPage->states1 = & $states1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$Content->AddComponent("statesSearch", $statesSearch);
$Content->AddComponent("states1", $states1);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$states1->Initialize();
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

//Execute Components @1-33F13DE2
$MasterPage->Operations();
$MenuIncludablePage->Operations();
$headerIncludablePage->Operations();
$states1->Operation();
$statesSearch->Operation();
//End Execute Components

//Go to destination page @1-0DB6B818
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($statesSearch);
    unset($states1);
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

//Unload Page @1-27A43A71
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($statesSearch);
unset($states1);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($Tpl);
//End Unload Page


?>
