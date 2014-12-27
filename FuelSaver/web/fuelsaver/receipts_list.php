<?php
//Include Common Files @1-C0735749
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "receipts_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridreceipts { //receipts class @8-BCE0997D

//Variables @8-5D1AB82F

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
    public $Sorter_TempReceiptNo;
    public $Sorter_ReceiptNo;
    public $Sorter_ReceiptDate;
    public $Sorter_ReceiptTime;
    public $Sorter_SalesRemarks;
    public $Sorter_ReceiptAmount;
    public $Sorter_ReferenceId;
    public $Sorter_VerifiedAmount;
    public $Sorter_UserId;
    public $Sorter_IsActive;
//End Variables

//Class_Initialize Event @8-589C6194
    function clsGridreceipts($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "receipts";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid receipts";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsreceiptsDataSource($this);
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
        $this->SorterName = CCGetParam("receiptsOrder", "");
        $this->SorterDirection = CCGetParam("receiptsDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "receipts_maint.php";
        $this->TempReceiptNo = new clsControl(ccsLabel, "TempReceiptNo", "TempReceiptNo", ccsInteger, "", CCGetRequestParam("TempReceiptNo", ccsGet, NULL), $this);
        $this->ReceiptNo = new clsControl(ccsLabel, "ReceiptNo", "ReceiptNo", ccsInteger, "", CCGetRequestParam("ReceiptNo", ccsGet, NULL), $this);
        $this->ReceiptDate = new clsControl(ccsLabel, "ReceiptDate", "ReceiptDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("ReceiptDate", ccsGet, NULL), $this);
        $this->ReceiptTime = new clsControl(ccsLabel, "ReceiptTime", "ReceiptTime", ccsDate, $DefaultDateFormat, CCGetRequestParam("ReceiptTime", ccsGet, NULL), $this);
        $this->SalesRemarks = new clsControl(ccsLabel, "SalesRemarks", "SalesRemarks", ccsText, "", CCGetRequestParam("SalesRemarks", ccsGet, NULL), $this);
        $this->ReceiptAmount = new clsControl(ccsLabel, "ReceiptAmount", "ReceiptAmount", ccsSingle, "", CCGetRequestParam("ReceiptAmount", ccsGet, NULL), $this);
        $this->ReferenceId = new clsControl(ccsLabel, "ReferenceId", "ReferenceId", ccsText, "", CCGetRequestParam("ReferenceId", ccsGet, NULL), $this);
        $this->VerifiedAmount = new clsControl(ccsLabel, "VerifiedAmount", "VerifiedAmount", ccsSingle, "", CCGetRequestParam("VerifiedAmount", ccsGet, NULL), $this);
        $this->UserId = new clsControl(ccsLabel, "UserId", "UserId", ccsText, "", CCGetRequestParam("UserId", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->receipts_Insert = new clsControl(ccsLink, "receipts_Insert", "receipts_Insert", ccsText, "", CCGetRequestParam("receipts_Insert", ccsGet, NULL), $this);
        $this->receipts_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->receipts_Insert->Page = "receipts_maint.php";
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_TempReceiptNo = new clsSorter($this->ComponentName, "Sorter_TempReceiptNo", $FileName, $this);
        $this->Sorter_ReceiptNo = new clsSorter($this->ComponentName, "Sorter_ReceiptNo", $FileName, $this);
        $this->Sorter_ReceiptDate = new clsSorter($this->ComponentName, "Sorter_ReceiptDate", $FileName, $this);
        $this->Sorter_ReceiptTime = new clsSorter($this->ComponentName, "Sorter_ReceiptTime", $FileName, $this);
        $this->Sorter_SalesRemarks = new clsSorter($this->ComponentName, "Sorter_SalesRemarks", $FileName, $this);
        $this->Sorter_ReceiptAmount = new clsSorter($this->ComponentName, "Sorter_ReceiptAmount", $FileName, $this);
        $this->Sorter_ReferenceId = new clsSorter($this->ComponentName, "Sorter_ReferenceId", $FileName, $this);
        $this->Sorter_VerifiedAmount = new clsSorter($this->ComponentName, "Sorter_VerifiedAmount", $FileName, $this);
        $this->Sorter_UserId = new clsSorter($this->ComponentName, "Sorter_UserId", $FileName, $this);
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

//Show Method @8-37CF3FC3
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_SalesOrder"] = CCGetFromGet("s_SalesOrder", NULL);
        $this->DataSource->Parameters["urls_VerifiedBy"] = CCGetFromGet("s_VerifiedBy", NULL);
        $this->DataSource->Parameters["urls_ReferenceId"] = CCGetFromGet("s_ReferenceId", NULL);

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
            $this->ControlsVisible["TempReceiptNo"] = $this->TempReceiptNo->Visible;
            $this->ControlsVisible["ReceiptNo"] = $this->ReceiptNo->Visible;
            $this->ControlsVisible["ReceiptDate"] = $this->ReceiptDate->Visible;
            $this->ControlsVisible["ReceiptTime"] = $this->ReceiptTime->Visible;
            $this->ControlsVisible["SalesRemarks"] = $this->SalesRemarks->Visible;
            $this->ControlsVisible["ReceiptAmount"] = $this->ReceiptAmount->Visible;
            $this->ControlsVisible["ReferenceId"] = $this->ReferenceId->Visible;
            $this->ControlsVisible["VerifiedAmount"] = $this->VerifiedAmount->Visible;
            $this->ControlsVisible["UserId"] = $this->UserId->Visible;
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
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("receipts_Id"));
                $this->TempReceiptNo->SetValue($this->DataSource->TempReceiptNo->GetValue());
                $this->ReceiptNo->SetValue($this->DataSource->ReceiptNo->GetValue());
                $this->ReceiptDate->SetValue($this->DataSource->ReceiptDate->GetValue());
                $this->ReceiptTime->SetValue($this->DataSource->ReceiptTime->GetValue());
                $this->SalesRemarks->SetValue($this->DataSource->SalesRemarks->GetValue());
                $this->ReceiptAmount->SetValue($this->DataSource->ReceiptAmount->GetValue());
                $this->ReferenceId->SetValue($this->DataSource->ReferenceId->GetValue());
                $this->VerifiedAmount->SetValue($this->DataSource->VerifiedAmount->GetValue());
                $this->UserId->SetValue($this->DataSource->UserId->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->TempReceiptNo->Show();
                $this->ReceiptNo->Show();
                $this->ReceiptDate->Show();
                $this->ReceiptTime->Show();
                $this->SalesRemarks->Show();
                $this->ReceiptAmount->Show();
                $this->ReferenceId->Show();
                $this->VerifiedAmount->Show();
                $this->UserId->Show();
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
        $this->receipts_Insert->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_TempReceiptNo->Show();
        $this->Sorter_ReceiptNo->Show();
        $this->Sorter_ReceiptDate->Show();
        $this->Sorter_ReceiptTime->Show();
        $this->Sorter_SalesRemarks->Show();
        $this->Sorter_ReceiptAmount->Show();
        $this->Sorter_ReferenceId->Show();
        $this->Sorter_VerifiedAmount->Show();
        $this->Sorter_UserId->Show();
        $this->Sorter_IsActive->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @8-1ACAE637
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->TempReceiptNo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReceiptNo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReceiptDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReceiptTime->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesRemarks->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReceiptAmount->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReferenceId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->VerifiedAmount->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End receipts Class @8-FCB6E20C

class clsreceiptsDataSource extends clsDBFuelSaver {  //receiptsDataSource Class @8-AA038C14

//DataSource Variables @8-AF577509
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $TempReceiptNo;
    public $ReceiptNo;
    public $ReceiptDate;
    public $ReceiptTime;
    public $SalesRemarks;
    public $ReceiptAmount;
    public $ReferenceId;
    public $VerifiedAmount;
    public $UserId;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @8-8FD8A57A
    function clsreceiptsDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid receipts";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->TempReceiptNo = new clsField("TempReceiptNo", ccsInteger, "");
        
        $this->ReceiptNo = new clsField("ReceiptNo", ccsInteger, "");
        
        $this->ReceiptDate = new clsField("ReceiptDate", ccsDate, $this->DateFormat);
        
        $this->ReceiptTime = new clsField("ReceiptTime", ccsDate, $this->DateFormat);
        
        $this->SalesRemarks = new clsField("SalesRemarks", ccsText, "");
        
        $this->ReceiptAmount = new clsField("ReceiptAmount", ccsSingle, "");
        
        $this->ReferenceId = new clsField("ReferenceId", ccsText, "");
        
        $this->VerifiedAmount = new clsField("VerifiedAmount", ccsSingle, "");
        
        $this->UserId = new clsField("UserId", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @8-846D205A
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("receipts.Id", ""), 
            "Sorter_TempReceiptNo" => array("TempReceiptNo", ""), 
            "Sorter_ReceiptNo" => array("ReceiptNo", ""), 
            "Sorter_ReceiptDate" => array("ReceiptDate", ""), 
            "Sorter_ReceiptTime" => array("ReceiptTime", ""), 
            "Sorter_SalesRemarks" => array("SalesRemarks", ""), 
            "Sorter_ReceiptAmount" => array("ReceiptAmount", ""), 
            "Sorter_ReferenceId" => array("ReferenceId", ""), 
            "Sorter_VerifiedAmount" => array("VerifiedAmount", ""), 
            "Sorter_UserId" => array("UserId", ""), 
            "Sorter_IsActive" => array("receipts.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @8-2CF15259
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_SalesOrder", ccsInteger, "", "", $this->Parameters["urls_SalesOrder"], "", false);
        $this->wp->AddParameter("2", "urls_VerifiedBy", ccsInteger, "", "", $this->Parameters["urls_VerifiedBy"], "", false);
        $this->wp->AddParameter("3", "urls_ReferenceId", ccsText, "", "", $this->Parameters["urls_ReferenceId"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "receipts.SalesOrder", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "receipts.VerifiedBy", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "receipts.ReferenceId", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]);
    }
//End Prepare Method

//Open Method @8-A47DBF91
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM (receipts LEFT JOIN sales ON\n\n" .
        "receipts.SalesOrder = sales.Id) LEFT JOIN users ON\n\n" .
        "receipts.VerifiedBy = users.Id";
        $this->SQL = "SELECT receipts.Id AS receipts_Id, TempReceiptNo, ReceiptNo, ReceiptDate, ReceiptTime, SalesRemarks, ReceiptAmount, ReferenceId, VerifiedAmount,\n\n" .
        "UserId, receipts.IsActive AS receipts_IsActive \n\n" .
        "FROM (receipts LEFT JOIN sales ON\n\n" .
        "receipts.SalesOrder = sales.Id) LEFT JOIN users ON\n\n" .
        "receipts.VerifiedBy = users.Id {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @8-7CBD3B50
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("receipts_Id")));
        $this->TempReceiptNo->SetDBValue(trim($this->f("TempReceiptNo")));
        $this->ReceiptNo->SetDBValue(trim($this->f("ReceiptNo")));
        $this->ReceiptDate->SetDBValue(trim($this->f("ReceiptDate")));
        $this->ReceiptTime->SetDBValue(trim($this->f("ReceiptTime")));
        $this->SalesRemarks->SetDBValue($this->f("SalesRemarks"));
        $this->ReceiptAmount->SetDBValue(trim($this->f("ReceiptAmount")));
        $this->ReferenceId->SetDBValue($this->f("ReferenceId"));
        $this->VerifiedAmount->SetDBValue(trim($this->f("VerifiedAmount")));
        $this->UserId->SetDBValue($this->f("UserId"));
        $this->IsActive->SetDBValue(trim($this->f("receipts_IsActive")));
    }
//End SetValues Method

} //End receiptsDataSource Class @8-FCB6E20C

class clsRecordreceiptsSearch { //receiptsSearch Class @2-2DF743EF

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

//Class_Initialize Event @2-4BFCACD1
    function clsRecordreceiptsSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record receiptsSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "receiptsSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_SalesOrder = new clsControl(ccsListBox, "s_SalesOrder", "Sales Order", ccsInteger, "", CCGetRequestParam("s_SalesOrder", $Method, NULL), $this);
            $this->s_SalesOrder->DSType = dsTable;
            $this->s_SalesOrder->DataSource = new clsDBFuelSaver();
            $this->s_SalesOrder->ds = & $this->s_SalesOrder->DataSource;
            $this->s_SalesOrder->DataSource->SQL = "SELECT * \n" .
"FROM sales {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesOrder->BoundColumn, $this->s_SalesOrder->TextColumn, $this->s_SalesOrder->DBFormat) = array("Id", "SalesRemarks", "");
            $this->s_ReferenceId = new clsControl(ccsTextBox, "s_ReferenceId", "Reference Id", ccsText, "", CCGetRequestParam("s_ReferenceId", $Method, NULL), $this);
            $this->s_VerifiedBy = new clsControl(ccsListBox, "s_VerifiedBy", "Verified By", ccsInteger, "", CCGetRequestParam("s_VerifiedBy", $Method, NULL), $this);
            $this->s_VerifiedBy->DSType = dsTable;
            $this->s_VerifiedBy->DataSource = new clsDBFuelSaver();
            $this->s_VerifiedBy->ds = & $this->s_VerifiedBy->DataSource;
            $this->s_VerifiedBy->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->s_VerifiedBy->BoundColumn, $this->s_VerifiedBy->TextColumn, $this->s_VerifiedBy->DBFormat) = array("Id", "UserId", "");
        }
    }
//End Class_Initialize Event

//Validate Method @2-CD9E02E1
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_SalesOrder->Validate() && $Validation);
        $Validation = ($this->s_ReferenceId->Validate() && $Validation);
        $Validation = ($this->s_VerifiedBy->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_SalesOrder->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ReferenceId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_VerifiedBy->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-B3F35D7E
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_SalesOrder->Errors->Count());
        $errors = ($errors || $this->s_ReferenceId->Errors->Count());
        $errors = ($errors || $this->s_VerifiedBy->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-7A0DBFD3
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
        $Redirect = "receipts_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "receipts_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-49315DC9
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

        $this->s_SalesOrder->Prepare();
        $this->s_VerifiedBy->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_SalesOrder->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ReferenceId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_VerifiedBy->Errors->ToString());
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
        $this->s_SalesOrder->Show();
        $this->s_ReferenceId->Show();
        $this->s_VerifiedBy->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End receiptsSearch Class @2-FCB6E20C

//Include Page implementation @60-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Include Page implementation @7-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-B0750E39
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
$TemplateFileName = "receipts_list.html";
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

//Include events file @1-F5E75889
include_once("./receipts_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-C3444D2B
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
$receipts = new clsGridreceipts("", $MainPage);
$receiptsSearch = new clsRecordreceiptsSearch("", $MainPage);
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
$MainPage->receipts = & $receipts;
$MainPage->receiptsSearch = & $receiptsSearch;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("receipts", $receipts);
$Content->AddComponent("receiptsSearch", $receiptsSearch);
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

//Execute Components @1-9320D3B7
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$receiptsSearch->Operation();
//End Execute Components

//Go to destination page @1-BBD141F2
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($receipts);
    unset($receiptsSearch);
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

//Unload Page @1-E9A4C7A2
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($receipts);
unset($receiptsSearch);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
