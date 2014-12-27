<?php
//Include Common Files @1-ADABEAEF
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "statesadd.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

//Include Page implementation @27-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

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

//Class_Initialize Event @8-C3A30CA8
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

        $this->EmptyRows = 1;
        $this->InsertAllowed = true;
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
        $this->Id = new clsControl(ccsLabel, "Id", "Id", ccsInteger, "", NULL, $this);
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
        $this->IsActive = new clsControl(ccsCheckBox, "IsActive", "IsActive", ccsInteger, "", NULL, $this);
        $this->IsActive->CheckedValue = $this->IsActive->GetParsedValue(1);
        $this->IsActive->UncheckedValue = $this->IsActive->GetParsedValue(0);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
        $this->Button_Submit = new clsButton("Button_Submit", $Method, $this);
        $this->Cancel = new clsButton("Cancel", $Method, $this);
    }
//End Class_Initialize Event

//Initialize Method @8-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);

    }
//End Initialize Method

//GetFormParameters Method @8-6C73572E
    function GetFormParameters()
    {
        for($RowNumber = 1; $RowNumber <= $this->TotalRows; $RowNumber++)
        {
            $this->FormParameters["State"][$RowNumber] = CCGetFromPost("State_" . $RowNumber, NULL);
            $this->FormParameters["Country"][$RowNumber] = CCGetFromPost("Country_" . $RowNumber, NULL);
            $this->FormParameters["SalesOffice"][$RowNumber] = CCGetFromPost("SalesOffice_" . $RowNumber, NULL);
            $this->FormParameters["IsActive"][$RowNumber] = CCGetFromPost("IsActive_" . $RowNumber, NULL);
        }
    }
//End GetFormParameters Method

//Validate Method @8-39FEA0CE
    function Validate()
    {
        $Validation = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);

        for($this->RowNumber = 1; $this->RowNumber <= $this->TotalRows; $this->RowNumber++)
        {
            $this->DataSource->CachedColumns[""] = $this->CachedColumns[""][$this->RowNumber];
            $this->DataSource->CurrentRow = $this->RowNumber;
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

//ValidateRow Method @8-69CBF123
    function ValidateRow()
    {
        global $CCSLocales;
        $this->State->Validate();
        $this->Country->Validate();
        $this->SalesOffice->Validate();
        $this->IsActive->Validate();
        $this->RowErrors = new clsErrors();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidateRow", $this);
        $errors = "";
        $errors = ComposeStrings($errors, $this->State->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Country->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesOffice->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $this->State->Errors->Clear();
        $this->Country->Errors->Clear();
        $this->SalesOffice->Errors->Clear();
        $this->IsActive->Errors->Clear();
        $errors = ComposeStrings($errors, $this->RowErrors->ToString());
        $this->RowsErrors[$this->RowNumber] = $errors;
        return $errors != "" ? 0 : 1;
    }
//End ValidateRow Method

//CheckInsert Method @8-131C4242
    function CheckInsert()
    {
        $filed = false;
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

//Operation Method @8-87A67BE6
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
            } else {
                $Redirect = "states.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
            }
        } else if($this->PressedButton == "Cancel") {
            if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick", $this->Cancel)) {
                $Redirect = "";
            } else {
                $Redirect = "states.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//UpdateGrid Method @8-8D38BC16
    function UpdateGrid()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSubmit", $this);
        if(!$this->Validate()) return;
        $Validation = true;
        for($this->RowNumber = 1; $this->RowNumber <= $this->TotalRows; $this->RowNumber++)
        {
            $this->DataSource->CachedColumns[""] = $this->CachedColumns[""][$this->RowNumber];
            $this->DataSource->CurrentRow = $this->RowNumber;
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

//InsertRow Method @8-EEB93CE9
    function InsertRow()
    {
        if(!$this->InsertAllowed) return false;
        $this->DataSource->Id->SetValue($this->Id->GetValue(true));
        $this->DataSource->State->SetValue($this->State->GetValue(true));
        $this->DataSource->Country->SetValue($this->Country->GetValue(true));
        $this->DataSource->SalesOffice->SetValue($this->SalesOffice->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $errors = "";
        if($this->DataSource->Errors->Count() > 0) {
            $errors = $this->DataSource->Errors->ToString();
            $this->RowsErrors[$this->RowNumber] = $errors;
            $this->DataSource->Errors->Clear();
        }
        return (($this->Errors->Count() == 0) && !strlen($errors));
    }
//End InsertRow Method

//FormScript Method @8-CA8AF4F6
    function FormScript($TotalRows)
    {
        $script = "";
        $script .= "\n<script language=\"JavaScript\" type=\"text/javascript\">\n<!--\n";
        $script .= "var states1Elements;\n";
        $script .= "var states1EmptyRows = 1;\n";
        $script .= "var " . $this->ComponentName . "StateID = 0;\n";
        $script .= "var " . $this->ComponentName . "CountryID = 1;\n";
        $script .= "var " . $this->ComponentName . "SalesOfficeID = 2;\n";
        $script .= "var " . $this->ComponentName . "IsActiveID = 3;\n";
        $script .= "\nfunction initstates1Elements() {\n";
        $script .= "\tvar ED = document.forms[\"states1\"];\n";
        $script .= "\tstates1Elements = new Array (\n";
        for($i = 1; $i <= $TotalRows; $i++) {
            $script .= "\t\tnew Array(" . "ED.State_" . $i . ", " . "ED.Country_" . $i . ", " . "ED.SalesOffice_" . $i . ", " . "ED.IsActive_" . $i . ")";
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

//Show Method @8-A1701893
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
                    $this->Id->SetText("");
                    $this->Id->SetValue($this->DataSource->Id->GetValue());
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
                    $this->IsActive->SetValue(true);
                } else {
                    $this->Id->SetText("");
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

//DataSource Variables @8-B77386AA
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $CountSQL;
    public $wp;
    public $AllParametersSet;

    public $CachedColumns;
    public $CurrentRow;
    public $InsertFields = array();

    // Datasource fields
    public $Id;
    public $State;
    public $Country;
    public $SalesOffice;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-AD06DE5B
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
        

        $this->InsertFields["State"] = array("Name" => "State", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["Country"] = array("Name" => "Country", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesOffice"] = array("Name" => "SalesOffice", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
    }
//End DataSourceClass_Initialize Event

//SetOrder Method @8-717C4CD9
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "State";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("states.Id", ""), 
            "Sorter_State" => array("State", ""), 
            "Sorter_Country" => array("states.Country", ""), 
            "Sorter_SalesOffice" => array("states.SalesOffice", ""), 
            "Sorter_IsActive" => array("states.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @8-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
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

//Insert Method @8-9A9474BC
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["State"]["Value"] = $this->State->GetDBValue(true);
        $this->InsertFields["Country"]["Value"] = $this->Country->GetDBValue(true);
        $this->InsertFields["SalesOffice"]["Value"] = $this->SalesOffice->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("states", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

} //End states1DataSource Class @8-FCB6E20C

//Initialize Page @1-4D5D8C30
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
$TemplateFileName = "statesadd.html";
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

//Initialize Objects @1-89CE7DEF
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
$HeaderSidebar = new clsPanel("HeaderSidebar", $MainPage);
$HeaderSidebar->PlaceholderName = "HeaderSidebar";
$headerIncludablePage = new clsheaderIncludablePage("", "headerIncludablePage", $MainPage);
$headerIncludablePage->Initialize();
$Menu = new clsPanel("Menu", $MainPage);
$Menu->PlaceholderName = "Menu";
$MenuIncludablePage = new clsmenuincludablepage("", "MenuIncludablePage", $MainPage);
$MenuIncludablePage->Initialize();
$Content = new clsPanel("Content", $MainPage);
$Content->PlaceholderName = "Content";
$states1 = new clsEditableGridstates1("", $MainPage);
$MainPage->Head = & $Head;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Content = & $Content;
$MainPage->states1 = & $states1;
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$Content->AddComponent("states1", $states1);
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

//Execute Components @1-27FECD51
$MasterPage->Operations();
$states1->Operation();
$MenuIncludablePage->Operations();
$headerIncludablePage->Operations();
//End Execute Components

//Go to destination page @1-488C2545
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    $headerIncludablePage->Class_Terminate();
    unset($headerIncludablePage);
    $MenuIncludablePage->Class_Terminate();
    unset($MenuIncludablePage);
    unset($states1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-4A759B3A
$Head->Show();
$HeaderSidebar->Show();
$Menu->Show();
$Content->Show();
$MasterPage->Tpl->SetVar("Head", $Tpl->GetVar("Panel Head"));
$MasterPage->Tpl->SetVar("HeaderSidebar", $Tpl->GetVar("Panel HeaderSidebar"));
$MasterPage->Tpl->SetVar("Menu", $Tpl->GetVar("Panel Menu"));
$MasterPage->Tpl->SetVar("Content", $Tpl->GetVar("Panel Content"));
$MasterPage->Show();
if (!isset($main_block)) $main_block = $MasterPage->HTML;
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-D1CAD96F
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($states1);
unset($Tpl);
//End Unload Page


?>
