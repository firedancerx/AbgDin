<?php
//Include Common Files @1-9CA69E36
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "sales_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridsales { //sales class @8-A4AFBA19

//Variables @8-21F0D092

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
    public $Sorter_SalesOrderNo;
    public $Sorter_SalesDate;
    public $Sorter_SalesTime;
    public $Sorter_UserId;
    public $Sorter_InventoryItem;
    public $Sorter_SalesQuantity;
    public $Sorter_SalesPrice;
    public $Sorter_SalesValue;
    public $Sorter_SalesRemarks;
    public $Sorter_IsActive;
//End Variables

//Class_Initialize Event @8-9E4E0E40
    function clsGridsales($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "sales";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid sales";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clssalesDataSource($this);
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
        $this->SorterName = CCGetParam("salesOrder", "");
        $this->SorterDirection = CCGetParam("salesDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "sales_maint.php";
        $this->SalesOrderNo = new clsControl(ccsLabel, "SalesOrderNo", "SalesOrderNo", ccsInteger, "", CCGetRequestParam("SalesOrderNo", ccsGet, NULL), $this);
        $this->SalesDate = new clsControl(ccsLabel, "SalesDate", "SalesDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("SalesDate", ccsGet, NULL), $this);
        $this->SalesTime = new clsControl(ccsLabel, "SalesTime", "SalesTime", ccsDate, $DefaultDateFormat, CCGetRequestParam("SalesTime", ccsGet, NULL), $this);
        $this->UserId = new clsControl(ccsLabel, "UserId", "UserId", ccsText, "", CCGetRequestParam("UserId", ccsGet, NULL), $this);
        $this->InventoryItem = new clsControl(ccsLabel, "InventoryItem", "InventoryItem", ccsText, "", CCGetRequestParam("InventoryItem", ccsGet, NULL), $this);
        $this->SalesQuantity = new clsControl(ccsLabel, "SalesQuantity", "SalesQuantity", ccsSingle, "", CCGetRequestParam("SalesQuantity", ccsGet, NULL), $this);
        $this->SalesPrice = new clsControl(ccsLabel, "SalesPrice", "SalesPrice", ccsSingle, "", CCGetRequestParam("SalesPrice", ccsGet, NULL), $this);
        $this->SalesValue = new clsControl(ccsLabel, "SalesValue", "SalesValue", ccsSingle, "", CCGetRequestParam("SalesValue", ccsGet, NULL), $this);
        $this->SalesRemarks = new clsControl(ccsLabel, "SalesRemarks", "SalesRemarks", ccsText, "", CCGetRequestParam("SalesRemarks", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->sales_Insert = new clsControl(ccsLink, "sales_Insert", "sales_Insert", ccsText, "", CCGetRequestParam("sales_Insert", ccsGet, NULL), $this);
        $this->sales_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->sales_Insert->Page = "sales_maint.php";
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_SalesOrderNo = new clsSorter($this->ComponentName, "Sorter_SalesOrderNo", $FileName, $this);
        $this->Sorter_SalesDate = new clsSorter($this->ComponentName, "Sorter_SalesDate", $FileName, $this);
        $this->Sorter_SalesTime = new clsSorter($this->ComponentName, "Sorter_SalesTime", $FileName, $this);
        $this->Sorter_UserId = new clsSorter($this->ComponentName, "Sorter_UserId", $FileName, $this);
        $this->Sorter_InventoryItem = new clsSorter($this->ComponentName, "Sorter_InventoryItem", $FileName, $this);
        $this->Sorter_SalesQuantity = new clsSorter($this->ComponentName, "Sorter_SalesQuantity", $FileName, $this);
        $this->Sorter_SalesPrice = new clsSorter($this->ComponentName, "Sorter_SalesPrice", $FileName, $this);
        $this->Sorter_SalesValue = new clsSorter($this->ComponentName, "Sorter_SalesValue", $FileName, $this);
        $this->Sorter_SalesRemarks = new clsSorter($this->ComponentName, "Sorter_SalesRemarks", $FileName, $this);
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

//Show Method @8-CFB45910
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_SalesUser"] = CCGetFromGet("s_SalesUser", NULL);
        $this->DataSource->Parameters["urls_SalesItem"] = CCGetFromGet("s_SalesItem", NULL);
        $this->DataSource->Parameters["urls_SalesRemarks"] = CCGetFromGet("s_SalesRemarks", NULL);

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
            $this->ControlsVisible["SalesOrderNo"] = $this->SalesOrderNo->Visible;
            $this->ControlsVisible["SalesDate"] = $this->SalesDate->Visible;
            $this->ControlsVisible["SalesTime"] = $this->SalesTime->Visible;
            $this->ControlsVisible["UserId"] = $this->UserId->Visible;
            $this->ControlsVisible["InventoryItem"] = $this->InventoryItem->Visible;
            $this->ControlsVisible["SalesQuantity"] = $this->SalesQuantity->Visible;
            $this->ControlsVisible["SalesPrice"] = $this->SalesPrice->Visible;
            $this->ControlsVisible["SalesValue"] = $this->SalesValue->Visible;
            $this->ControlsVisible["SalesRemarks"] = $this->SalesRemarks->Visible;
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
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("sales_Id"));
                $this->SalesOrderNo->SetValue($this->DataSource->SalesOrderNo->GetValue());
                $this->SalesDate->SetValue($this->DataSource->SalesDate->GetValue());
                $this->SalesTime->SetValue($this->DataSource->SalesTime->GetValue());
                $this->UserId->SetValue($this->DataSource->UserId->GetValue());
                $this->InventoryItem->SetValue($this->DataSource->InventoryItem->GetValue());
                $this->SalesQuantity->SetValue($this->DataSource->SalesQuantity->GetValue());
                $this->SalesPrice->SetValue($this->DataSource->SalesPrice->GetValue());
                $this->SalesValue->SetValue($this->DataSource->SalesValue->GetValue());
                $this->SalesRemarks->SetValue($this->DataSource->SalesRemarks->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->SalesOrderNo->Show();
                $this->SalesDate->Show();
                $this->SalesTime->Show();
                $this->UserId->Show();
                $this->InventoryItem->Show();
                $this->SalesQuantity->Show();
                $this->SalesPrice->Show();
                $this->SalesValue->Show();
                $this->SalesRemarks->Show();
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
        $this->sales_Insert->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_SalesOrderNo->Show();
        $this->Sorter_SalesDate->Show();
        $this->Sorter_SalesTime->Show();
        $this->Sorter_UserId->Show();
        $this->Sorter_InventoryItem->Show();
        $this->Sorter_SalesQuantity->Show();
        $this->Sorter_SalesPrice->Show();
        $this->Sorter_SalesValue->Show();
        $this->Sorter_SalesRemarks->Show();
        $this->Sorter_IsActive->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @8-788605FD
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesOrderNo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesTime->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->InventoryItem->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesQuantity->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesPrice->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesValue->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesRemarks->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End sales Class @8-FCB6E20C

class clssalesDataSource extends clsDBFuelSaver {  //salesDataSource Class @8-78DAF30D

//DataSource Variables @8-D7D90B1A
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $SalesOrderNo;
    public $SalesDate;
    public $SalesTime;
    public $UserId;
    public $InventoryItem;
    public $SalesQuantity;
    public $SalesPrice;
    public $SalesValue;
    public $SalesRemarks;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-0DCB25A8
    function clssalesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid sales";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->SalesOrderNo = new clsField("SalesOrderNo", ccsInteger, "");
        
        $this->SalesDate = new clsField("SalesDate", ccsDate, $this->DateFormat);
        
        $this->SalesTime = new clsField("SalesTime", ccsDate, $this->DateFormat);
        
        $this->UserId = new clsField("UserId", ccsText, "");
        
        $this->InventoryItem = new clsField("InventoryItem", ccsText, "");
        
        $this->SalesQuantity = new clsField("SalesQuantity", ccsSingle, "");
        
        $this->SalesPrice = new clsField("SalesPrice", ccsSingle, "");
        
        $this->SalesValue = new clsField("SalesValue", ccsSingle, "");
        
        $this->SalesRemarks = new clsField("SalesRemarks", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @8-AE7382EB
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("sales.Id", ""), 
            "Sorter_SalesOrderNo" => array("SalesOrderNo", ""), 
            "Sorter_SalesDate" => array("SalesDate", ""), 
            "Sorter_SalesTime" => array("SalesTime", ""), 
            "Sorter_UserId" => array("UserId", ""), 
            "Sorter_InventoryItem" => array("InventoryItem", ""), 
            "Sorter_SalesQuantity" => array("SalesQuantity", ""), 
            "Sorter_SalesPrice" => array("SalesPrice", ""), 
            "Sorter_SalesValue" => array("SalesValue", ""), 
            "Sorter_SalesRemarks" => array("SalesRemarks", ""), 
            "Sorter_IsActive" => array("sales.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @8-B05428FA
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_SalesUser", ccsInteger, "", "", $this->Parameters["urls_SalesUser"], "", false);
        $this->wp->AddParameter("2", "urls_SalesItem", ccsInteger, "", "", $this->Parameters["urls_SalesItem"], "", false);
        $this->wp->AddParameter("3", "urls_SalesRemarks", ccsText, "", "", $this->Parameters["urls_SalesRemarks"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "sales.SalesUser", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "sales.SalesItem", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "sales.SalesRemarks", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @8-547AB43C
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM (sales LEFT JOIN users ON\n\n" .
        "sales.SalesUser = users.Id) LEFT JOIN inventoryitem ON\n\n" .
        "sales.SalesItem = inventoryitem.Id";
        $this->SQL = "SELECT sales.Id AS sales_Id, SalesOrderNo, SalesDate, SalesTime, UserId, InventoryItem, SalesQuantity, SalesPrice, SalesValue, SalesRemarks,\n\n" .
        "sales.IsActive AS sales_IsActive \n\n" .
        "FROM (sales LEFT JOIN users ON\n\n" .
        "sales.SalesUser = users.Id) LEFT JOIN inventoryitem ON\n\n" .
        "sales.SalesItem = inventoryitem.Id {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @8-E04F99E6
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("sales_Id")));
        $this->SalesOrderNo->SetDBValue(trim($this->f("SalesOrderNo")));
        $this->SalesDate->SetDBValue(trim($this->f("SalesDate")));
        $this->SalesTime->SetDBValue(trim($this->f("SalesTime")));
        $this->UserId->SetDBValue($this->f("UserId"));
        $this->InventoryItem->SetDBValue($this->f("InventoryItem"));
        $this->SalesQuantity->SetDBValue(trim($this->f("SalesQuantity")));
        $this->SalesPrice->SetDBValue(trim($this->f("SalesPrice")));
        $this->SalesValue->SetDBValue(trim($this->f("SalesValue")));
        $this->SalesRemarks->SetDBValue($this->f("SalesRemarks"));
        $this->IsActive->SetDBValue(trim($this->f("sales_IsActive")));
    }
//End SetValues Method

} //End salesDataSource Class @8-FCB6E20C

class clsRecordsalesSearch { //salesSearch Class @2-E9EC5A25

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

//Class_Initialize Event @2-9199F22C
    function clsRecordsalesSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record salesSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "salesSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_SalesUser = new clsControl(ccsListBox, "s_SalesUser", "Sales User", ccsInteger, "", CCGetRequestParam("s_SalesUser", $Method, NULL), $this);
            $this->s_SalesUser->DSType = dsTable;
            $this->s_SalesUser->DataSource = new clsDBFuelSaver();
            $this->s_SalesUser->ds = & $this->s_SalesUser->DataSource;
            $this->s_SalesUser->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesUser->BoundColumn, $this->s_SalesUser->TextColumn, $this->s_SalesUser->DBFormat) = array("Id", "UserId", "");
            $this->s_SalesItem = new clsControl(ccsListBox, "s_SalesItem", "Sales Item", ccsInteger, "", CCGetRequestParam("s_SalesItem", $Method, NULL), $this);
            $this->s_SalesItem->DSType = dsTable;
            $this->s_SalesItem->DataSource = new clsDBFuelSaver();
            $this->s_SalesItem->ds = & $this->s_SalesItem->DataSource;
            $this->s_SalesItem->DataSource->SQL = "SELECT * \n" .
"FROM inventoryitem {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesItem->BoundColumn, $this->s_SalesItem->TextColumn, $this->s_SalesItem->DBFormat) = array("Id", "InventoryItem", "");
            $this->s_SalesRemarks = new clsControl(ccsTextBox, "s_SalesRemarks", "Sales Remarks", ccsText, "", CCGetRequestParam("s_SalesRemarks", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @2-DB8D8920
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_SalesUser->Validate() && $Validation);
        $Validation = ($this->s_SalesItem->Validate() && $Validation);
        $Validation = ($this->s_SalesRemarks->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_SalesUser->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_SalesItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_SalesRemarks->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-F683EA0E
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_SalesUser->Errors->Count());
        $errors = ($errors || $this->s_SalesItem->Errors->Count());
        $errors = ($errors || $this->s_SalesRemarks->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-99E1E52D
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
        $Redirect = "sales_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "sales_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-D8A4D8D6
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

        $this->s_SalesUser->Prepare();
        $this->s_SalesItem->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_SalesUser->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_SalesItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_SalesRemarks->Errors->ToString());
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
        $this->s_SalesUser->Show();
        $this->s_SalesItem->Show();
        $this->s_SalesRemarks->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End salesSearch Class @2-FCB6E20C

//Include Page implementation @60-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Include Page implementation @7-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-530E7DC2
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
$TemplateFileName = "sales_list.html";
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

//Include events file @1-C3998CE7
include_once("./sales_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-546786A7
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
$sales = new clsGridsales("", $MainPage);
$salesSearch = new clsRecordsalesSearch("", $MainPage);
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
$MainPage->sales = & $sales;
$MainPage->salesSearch = & $salesSearch;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("sales", $sales);
$Content->AddComponent("salesSearch", $salesSearch);
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

//Execute Components @1-7C77200D
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$salesSearch->Operation();
//End Execute Components

//Go to destination page @1-A4048EBC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($sales);
    unset($salesSearch);
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

//Unload Page @1-80FC5058
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($sales);
unset($salesSearch);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
