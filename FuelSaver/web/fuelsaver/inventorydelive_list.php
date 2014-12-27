<?php
//Include Common Files @1-59560B48
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "inventorydelive_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridinventorydeliveries { //inventorydeliveries class @8-380C1C01

//Variables @8-2EAD70E2

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
    public $Sorter_Id;
    public $Sorter_DeliveryOrderNo;
    public $Sorter_DeliveryDate;
    public $Sorter_DeliveryTime;
    public $Sorter_UserId;
    public $Sorter_SalesRemarks;
    public $Sorter_DeliveryRemarks;
    public $Sorter_IsActive;
//End Variables

//Class_Initialize Event @8-6691E468
    function clsGridinventorydeliveries($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "inventorydeliveries";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid inventorydeliveries";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsinventorydeliveriesDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<BR>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("inventorydeliveriesOrder", "");
        $this->SorterDirection = CCGetParam("inventorydeliveriesDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "inventorydelive_maint.php";
        $this->DeliveryOrderNo = new clsControl(ccsLabel, "DeliveryOrderNo", "DeliveryOrderNo", ccsInteger, "", CCGetRequestParam("DeliveryOrderNo", ccsGet, NULL), $this);
        $this->DeliveryDate = new clsControl(ccsLabel, "DeliveryDate", "DeliveryDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("DeliveryDate", ccsGet, NULL), $this);
        $this->DeliveryTime = new clsControl(ccsLabel, "DeliveryTime", "DeliveryTime", ccsDate, $DefaultDateFormat, CCGetRequestParam("DeliveryTime", ccsGet, NULL), $this);
        $this->UserId = new clsControl(ccsLabel, "UserId", "UserId", ccsText, "", CCGetRequestParam("UserId", ccsGet, NULL), $this);
        $this->SalesRemarks = new clsControl(ccsLabel, "SalesRemarks", "SalesRemarks", ccsText, "", CCGetRequestParam("SalesRemarks", ccsGet, NULL), $this);
        $this->DeliveryRemarks = new clsControl(ccsLabel, "DeliveryRemarks", "DeliveryRemarks", ccsText, "", CCGetRequestParam("DeliveryRemarks", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->inventorydeliveries_Insert = new clsControl(ccsLink, "inventorydeliveries_Insert", "inventorydeliveries_Insert", ccsText, "", CCGetRequestParam("inventorydeliveries_Insert", ccsGet, NULL), $this);
        $this->inventorydeliveries_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->inventorydeliveries_Insert->Page = "inventorydelive_maint.php";
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_DeliveryOrderNo = new clsSorter($this->ComponentName, "Sorter_DeliveryOrderNo", $FileName, $this);
        $this->Sorter_DeliveryDate = new clsSorter($this->ComponentName, "Sorter_DeliveryDate", $FileName, $this);
        $this->Sorter_DeliveryTime = new clsSorter($this->ComponentName, "Sorter_DeliveryTime", $FileName, $this);
        $this->Sorter_UserId = new clsSorter($this->ComponentName, "Sorter_UserId", $FileName, $this);
        $this->Sorter_SalesRemarks = new clsSorter($this->ComponentName, "Sorter_SalesRemarks", $FileName, $this);
        $this->Sorter_DeliveryRemarks = new clsSorter($this->ComponentName, "Sorter_DeliveryRemarks", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
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

//Show Method @8-7916012E
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_DeliveryBy"] = CCGetFromGet("s_DeliveryBy", NULL);
        $this->DataSource->Parameters["urls_SalesId"] = CCGetFromGet("s_SalesId", NULL);
        $this->DataSource->Parameters["urls_DeliveryRemarks"] = CCGetFromGet("s_DeliveryRemarks", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["Id"] = $this->Id->Visible;
            $this->ControlsVisible["DeliveryOrderNo"] = $this->DeliveryOrderNo->Visible;
            $this->ControlsVisible["DeliveryDate"] = $this->DeliveryDate->Visible;
            $this->ControlsVisible["DeliveryTime"] = $this->DeliveryTime->Visible;
            $this->ControlsVisible["UserId"] = $this->UserId->Visible;
            $this->ControlsVisible["SalesRemarks"] = $this->SalesRemarks->Visible;
            $this->ControlsVisible["DeliveryRemarks"] = $this->DeliveryRemarks->Visible;
            $this->ControlsVisible["IsActive"] = $this->IsActive->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->Id->SetValue($this->DataSource->Id->GetValue());
                $this->Id->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("inventorydeliveries_Id"));
                $this->DeliveryOrderNo->SetValue($this->DataSource->DeliveryOrderNo->GetValue());
                $this->DeliveryDate->SetValue($this->DataSource->DeliveryDate->GetValue());
                $this->DeliveryTime->SetValue($this->DataSource->DeliveryTime->GetValue());
                $this->UserId->SetValue($this->DataSource->UserId->GetValue());
                $this->SalesRemarks->SetValue($this->DataSource->SalesRemarks->GetValue());
                $this->DeliveryRemarks->SetValue($this->DataSource->DeliveryRemarks->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->DeliveryOrderNo->Show();
                $this->DeliveryDate->Show();
                $this->DeliveryTime->Show();
                $this->UserId->Show();
                $this->SalesRemarks->Show();
                $this->DeliveryRemarks->Show();
                $this->IsActive->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if (($this->Navigator->TotalPages <= 1 && $this->Navigator->PageNumber == 1) || $this->Navigator->PageSize == "") {
            $this->Navigator->Visible = false;
        }
        $this->inventorydeliveries_Insert->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_DeliveryOrderNo->Show();
        $this->Sorter_DeliveryDate->Show();
        $this->Sorter_DeliveryTime->Show();
        $this->Sorter_UserId->Show();
        $this->Sorter_SalesRemarks->Show();
        $this->Sorter_DeliveryRemarks->Show();
        $this->Sorter_IsActive->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @8-0C323632
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DeliveryOrderNo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DeliveryDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DeliveryTime->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesRemarks->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DeliveryRemarks->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End inventorydeliveries Class @8-FCB6E20C

class clsinventorydeliveriesDataSource extends clsDBFuelSaver {  //inventorydeliveriesDataSource Class @8-CD406F65

//DataSource Variables @8-3B50A184
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $DeliveryOrderNo;
    public $DeliveryDate;
    public $DeliveryTime;
    public $UserId;
    public $SalesRemarks;
    public $DeliveryRemarks;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-961E72EE
    function clsinventorydeliveriesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid inventorydeliveries";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->DeliveryOrderNo = new clsField("DeliveryOrderNo", ccsInteger, "");
        
        $this->DeliveryDate = new clsField("DeliveryDate", ccsDate, $this->DateFormat);
        
        $this->DeliveryTime = new clsField("DeliveryTime", ccsDate, $this->DateFormat);
        
        $this->UserId = new clsField("UserId", ccsText, "");
        
        $this->SalesRemarks = new clsField("SalesRemarks", ccsText, "");
        
        $this->DeliveryRemarks = new clsField("DeliveryRemarks", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @8-861F405C
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("inventorydeliveries.Id", ""), 
            "Sorter_DeliveryOrderNo" => array("DeliveryOrderNo", ""), 
            "Sorter_DeliveryDate" => array("DeliveryDate", ""), 
            "Sorter_DeliveryTime" => array("DeliveryTime", ""), 
            "Sorter_UserId" => array("UserId", ""), 
            "Sorter_SalesRemarks" => array("SalesRemarks", ""), 
            "Sorter_DeliveryRemarks" => array("DeliveryRemarks", ""), 
            "Sorter_IsActive" => array("inventorydeliveries.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @8-3A041DB1
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_DeliveryBy", ccsInteger, "", "", $this->Parameters["urls_DeliveryBy"], "", false);
        $this->wp->AddParameter("2", "urls_SalesId", ccsInteger, "", "", $this->Parameters["urls_SalesId"], "", false);
        $this->wp->AddParameter("3", "urls_DeliveryRemarks", ccsText, "", "", $this->Parameters["urls_DeliveryRemarks"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "inventorydeliveries.DeliveryBy", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "inventorydeliveries.SalesId", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "inventorydeliveries.DeliveryRemarks", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @8-943AA8FB
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM (inventorydeliveries LEFT JOIN users ON\n\n" .
        "inventorydeliveries.DeliveryBy = users.Id) LEFT JOIN sales ON\n\n" .
        "inventorydeliveries.SalesId = sales.Id";
        $this->SQL = "SELECT inventorydeliveries.Id AS inventorydeliveries_Id, DeliveryOrderNo, DeliveryDate, DeliveryTime, UserId, SalesRemarks, DeliveryRemarks,\n\n" .
        "inventorydeliveries.IsActive AS inventorydeliveries_IsActive \n\n" .
        "FROM (inventorydeliveries LEFT JOIN users ON\n\n" .
        "inventorydeliveries.DeliveryBy = users.Id) LEFT JOIN sales ON\n\n" .
        "inventorydeliveries.SalesId = sales.Id {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @8-FA22C351
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("inventorydeliveries_Id")));
        $this->DeliveryOrderNo->SetDBValue(trim($this->f("DeliveryOrderNo")));
        $this->DeliveryDate->SetDBValue(trim($this->f("DeliveryDate")));
        $this->DeliveryTime->SetDBValue(trim($this->f("DeliveryTime")));
        $this->UserId->SetDBValue($this->f("UserId"));
        $this->SalesRemarks->SetDBValue($this->f("SalesRemarks"));
        $this->DeliveryRemarks->SetDBValue($this->f("DeliveryRemarks"));
        $this->IsActive->SetDBValue(trim($this->f("inventorydeliveries_IsActive")));
    }
//End SetValues Method

} //End inventorydeliveriesDataSource Class @8-FCB6E20C

class clsRecordinventorydeliveriesSearch { //inventorydeliveriesSearch Class @2-B7F8F190

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

//Class_Initialize Event @2-CCCB8DBE
    function clsRecordinventorydeliveriesSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record inventorydeliveriesSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "inventorydeliveriesSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_DeliveryBy = new clsControl(ccsListBox, "s_DeliveryBy", "Delivery By", ccsInteger, "", CCGetRequestParam("s_DeliveryBy", $Method, NULL), $this);
            $this->s_DeliveryBy->DSType = dsTable;
            $this->s_DeliveryBy->DataSource = new clsDBFuelSaver();
            $this->s_DeliveryBy->ds = & $this->s_DeliveryBy->DataSource;
            $this->s_DeliveryBy->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->s_DeliveryBy->BoundColumn, $this->s_DeliveryBy->TextColumn, $this->s_DeliveryBy->DBFormat) = array("Id", "UserId", "");
            $this->s_SalesId = new clsControl(ccsListBox, "s_SalesId", "Sales Id", ccsInteger, "", CCGetRequestParam("s_SalesId", $Method, NULL), $this);
            $this->s_SalesId->DSType = dsTable;
            $this->s_SalesId->DataSource = new clsDBFuelSaver();
            $this->s_SalesId->ds = & $this->s_SalesId->DataSource;
            $this->s_SalesId->DataSource->SQL = "SELECT * \n" .
"FROM sales {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesId->BoundColumn, $this->s_SalesId->TextColumn, $this->s_SalesId->DBFormat) = array("Id", "SalesRemarks", "");
            $this->s_DeliveryRemarks = new clsControl(ccsTextBox, "s_DeliveryRemarks", "Delivery Remarks", ccsText, "", CCGetRequestParam("s_DeliveryRemarks", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @2-B43E580D
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_DeliveryBy->Validate() && $Validation);
        $Validation = ($this->s_SalesId->Validate() && $Validation);
        $Validation = ($this->s_DeliveryRemarks->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_DeliveryBy->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_SalesId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_DeliveryRemarks->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-D7BC0EA7
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_DeliveryBy->Errors->Count());
        $errors = ($errors || $this->s_SalesId->Errors->Count());
        $errors = ($errors || $this->s_DeliveryRemarks->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-DFCD28F3
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
        $Redirect = "inventorydelive_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "inventorydelive_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-7755D8D9
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

        $this->s_DeliveryBy->Prepare();
        $this->s_SalesId->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_DeliveryBy->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_SalesId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_DeliveryRemarks->Errors->ToString());
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
        $this->s_DeliveryBy->Show();
        $this->s_SalesId->Show();
        $this->s_DeliveryRemarks->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End inventorydeliveriesSearch Class @2-FCB6E20C

//Include Page implementation @51-07AA2166
include_once(RelativePath . "/MenuIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-2E696DF6
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
$TemplateFileName = "inventorydelive_list.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-2F453E31
include_once("./inventorydelive_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-8047C29B
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
$inventorydeliveries = new clsGridinventorydeliveries("", $MainPage);
$inventorydeliveriesSearch = new clsRecordinventorydeliveriesSearch("", $MainPage);
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
$MainPage->inventorydeliveries = & $inventorydeliveries;
$MainPage->inventorydeliveriesSearch = & $inventorydeliveriesSearch;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("inventorydeliveries", $inventorydeliveries);
$Content->AddComponent("inventorydeliveriesSearch", $inventorydeliveriesSearch);
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

//Execute Components @1-D54AE9A9
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$inventorydeliveriesSearch->Operation();
//End Execute Components

//Go to destination page @1-BD63B182
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($inventorydeliveries);
    unset($inventorydeliveriesSearch);
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

//Unload Page @1-ACD52A20
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($inventorydeliveries);
unset($inventorydeliveriesSearch);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
