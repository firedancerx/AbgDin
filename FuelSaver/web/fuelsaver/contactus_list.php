<?php
//Include Common Files @1-1C4278EC
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "contactus_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridcontactus { //contactus class @13-3B74D147

//Variables @13-B6190B65

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
    public $Sorter_ContactDate;
    public $Sorter_ContactTime;
    public $Sorter_ContactName;
    public $Sorter_ContactEmail;
    public $Sorter_ContactPhone;
    public $Sorter_ContactUser;
    public $Sorter_ContactSubject;
    public $Sorter_ContactContent;
    public $Sorter_ContactReplyDate;
    public $Sorter_ContactReplyTime;
    public $Sorter_ContactReplyBy;
    public $Sorter_ContactReplyContent;
    public $Sorter_IsActive;
//End Variables

//Class_Initialize Event @13-A19A4176
    function clsGridcontactus($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "contactus";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid contactus";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clscontactusDataSource($this);
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
        $this->SorterName = CCGetParam("contactusOrder", "");
        $this->SorterDirection = CCGetParam("contactusDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "contactus_maint.php";
        $this->ContactDate = new clsControl(ccsLabel, "ContactDate", "ContactDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("ContactDate", ccsGet, NULL), $this);
        $this->ContactTime = new clsControl(ccsLabel, "ContactTime", "ContactTime", ccsDate, $DefaultDateFormat, CCGetRequestParam("ContactTime", ccsGet, NULL), $this);
        $this->ContactName = new clsControl(ccsLabel, "ContactName", "ContactName", ccsText, "", CCGetRequestParam("ContactName", ccsGet, NULL), $this);
        $this->ContactEmail = new clsControl(ccsLabel, "ContactEmail", "ContactEmail", ccsText, "", CCGetRequestParam("ContactEmail", ccsGet, NULL), $this);
        $this->ContactPhone = new clsControl(ccsLabel, "ContactPhone", "ContactPhone", ccsText, "", CCGetRequestParam("ContactPhone", ccsGet, NULL), $this);
        $this->ContactUser = new clsControl(ccsLabel, "ContactUser", "ContactUser", ccsText, "", CCGetRequestParam("ContactUser", ccsGet, NULL), $this);
        $this->ContactSubject = new clsControl(ccsLabel, "ContactSubject", "ContactSubject", ccsText, "", CCGetRequestParam("ContactSubject", ccsGet, NULL), $this);
        $this->ContactContent = new clsControl(ccsLabel, "ContactContent", "ContactContent", ccsText, "", CCGetRequestParam("ContactContent", ccsGet, NULL), $this);
        $this->ContactReplyDate = new clsControl(ccsLabel, "ContactReplyDate", "ContactReplyDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("ContactReplyDate", ccsGet, NULL), $this);
        $this->ContactReplyTime = new clsControl(ccsLabel, "ContactReplyTime", "ContactReplyTime", ccsDate, $DefaultDateFormat, CCGetRequestParam("ContactReplyTime", ccsGet, NULL), $this);
        $this->ContactReplyBy = new clsControl(ccsLabel, "ContactReplyBy", "ContactReplyBy", ccsText, "", CCGetRequestParam("ContactReplyBy", ccsGet, NULL), $this);
        $this->ContactReplyContent = new clsControl(ccsLabel, "ContactReplyContent", "ContactReplyContent", ccsText, "", CCGetRequestParam("ContactReplyContent", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->contactus_Insert = new clsControl(ccsLink, "contactus_Insert", "contactus_Insert", ccsText, "", CCGetRequestParam("contactus_Insert", ccsGet, NULL), $this);
        $this->contactus_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->contactus_Insert->Page = "contactus_maint.php";
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_ContactDate = new clsSorter($this->ComponentName, "Sorter_ContactDate", $FileName, $this);
        $this->Sorter_ContactTime = new clsSorter($this->ComponentName, "Sorter_ContactTime", $FileName, $this);
        $this->Sorter_ContactName = new clsSorter($this->ComponentName, "Sorter_ContactName", $FileName, $this);
        $this->Sorter_ContactEmail = new clsSorter($this->ComponentName, "Sorter_ContactEmail", $FileName, $this);
        $this->Sorter_ContactPhone = new clsSorter($this->ComponentName, "Sorter_ContactPhone", $FileName, $this);
        $this->Sorter_ContactUser = new clsSorter($this->ComponentName, "Sorter_ContactUser", $FileName, $this);
        $this->Sorter_ContactSubject = new clsSorter($this->ComponentName, "Sorter_ContactSubject", $FileName, $this);
        $this->Sorter_ContactContent = new clsSorter($this->ComponentName, "Sorter_ContactContent", $FileName, $this);
        $this->Sorter_ContactReplyDate = new clsSorter($this->ComponentName, "Sorter_ContactReplyDate", $FileName, $this);
        $this->Sorter_ContactReplyTime = new clsSorter($this->ComponentName, "Sorter_ContactReplyTime", $FileName, $this);
        $this->Sorter_ContactReplyBy = new clsSorter($this->ComponentName, "Sorter_ContactReplyBy", $FileName, $this);
        $this->Sorter_ContactReplyContent = new clsSorter($this->ComponentName, "Sorter_ContactReplyContent", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @13-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @13-45B03EF8
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_ContactUser"] = CCGetFromGet("s_ContactUser", NULL);
        $this->DataSource->Parameters["urls_ContactReplyBy"] = CCGetFromGet("s_ContactReplyBy", NULL);
        $this->DataSource->Parameters["urls_ContactName"] = CCGetFromGet("s_ContactName", NULL);
        $this->DataSource->Parameters["urls_ContactEmail"] = CCGetFromGet("s_ContactEmail", NULL);
        $this->DataSource->Parameters["urls_ContactPhone"] = CCGetFromGet("s_ContactPhone", NULL);
        $this->DataSource->Parameters["urls_ContactSubject"] = CCGetFromGet("s_ContactSubject", NULL);
        $this->DataSource->Parameters["urls_ContactContent"] = CCGetFromGet("s_ContactContent", NULL);
        $this->DataSource->Parameters["urls_ContactReplyContent"] = CCGetFromGet("s_ContactReplyContent", NULL);

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
            $this->ControlsVisible["ContactDate"] = $this->ContactDate->Visible;
            $this->ControlsVisible["ContactTime"] = $this->ContactTime->Visible;
            $this->ControlsVisible["ContactName"] = $this->ContactName->Visible;
            $this->ControlsVisible["ContactEmail"] = $this->ContactEmail->Visible;
            $this->ControlsVisible["ContactPhone"] = $this->ContactPhone->Visible;
            $this->ControlsVisible["ContactUser"] = $this->ContactUser->Visible;
            $this->ControlsVisible["ContactSubject"] = $this->ContactSubject->Visible;
            $this->ControlsVisible["ContactContent"] = $this->ContactContent->Visible;
            $this->ControlsVisible["ContactReplyDate"] = $this->ContactReplyDate->Visible;
            $this->ControlsVisible["ContactReplyTime"] = $this->ContactReplyTime->Visible;
            $this->ControlsVisible["ContactReplyBy"] = $this->ContactReplyBy->Visible;
            $this->ControlsVisible["ContactReplyContent"] = $this->ContactReplyContent->Visible;
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
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("contactus_Id"));
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
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
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
        $this->contactus_Insert->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_ContactDate->Show();
        $this->Sorter_ContactTime->Show();
        $this->Sorter_ContactName->Show();
        $this->Sorter_ContactEmail->Show();
        $this->Sorter_ContactPhone->Show();
        $this->Sorter_ContactUser->Show();
        $this->Sorter_ContactSubject->Show();
        $this->Sorter_ContactContent->Show();
        $this->Sorter_ContactReplyDate->Show();
        $this->Sorter_ContactReplyTime->Show();
        $this->Sorter_ContactReplyBy->Show();
        $this->Sorter_ContactReplyContent->Show();
        $this->Sorter_IsActive->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @13-EB938E3E
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactTime->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactName->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactEmail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactPhone->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactUser->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactSubject->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactContent->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactReplyDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactReplyTime->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactReplyBy->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ContactReplyContent->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End contactus Class @13-FCB6E20C

class clscontactusDataSource extends clsDBFuelSaver {  //contactusDataSource Class @13-7982D766

//DataSource Variables @13-0C253DF3
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


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

//DataSourceClass_Initialize Event @13-CB311F7E
    function clscontactusDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid contactus";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->ContactDate = new clsField("ContactDate", ccsDate, $this->DateFormat);
        
        $this->ContactTime = new clsField("ContactTime", ccsDate, $this->DateFormat);
        
        $this->ContactName = new clsField("ContactName", ccsText, "");
        
        $this->ContactEmail = new clsField("ContactEmail", ccsText, "");
        
        $this->ContactPhone = new clsField("ContactPhone", ccsText, "");
        
        $this->ContactUser = new clsField("ContactUser", ccsText, "");
        
        $this->ContactSubject = new clsField("ContactSubject", ccsText, "");
        
        $this->ContactContent = new clsField("ContactContent", ccsText, "");
        
        $this->ContactReplyDate = new clsField("ContactReplyDate", ccsDate, $this->DateFormat);
        
        $this->ContactReplyTime = new clsField("ContactReplyTime", ccsDate, $this->DateFormat);
        
        $this->ContactReplyBy = new clsField("ContactReplyBy", ccsText, "");
        
        $this->ContactReplyContent = new clsField("ContactReplyContent", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @13-5A5D3071
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("contactus.Id", ""), 
            "Sorter_ContactDate" => array("ContactDate", ""), 
            "Sorter_ContactTime" => array("ContactTime", ""), 
            "Sorter_ContactName" => array("ContactName", ""), 
            "Sorter_ContactEmail" => array("ContactEmail", ""), 
            "Sorter_ContactPhone" => array("ContactPhone", ""), 
            "Sorter_ContactUser" => array("users.UserId", ""), 
            "Sorter_ContactSubject" => array("ContactSubject", ""), 
            "Sorter_ContactContent" => array("ContactContent", ""), 
            "Sorter_ContactReplyDate" => array("ContactReplyDate", ""), 
            "Sorter_ContactReplyTime" => array("ContactReplyTime", ""), 
            "Sorter_ContactReplyBy" => array("users1.UserId", ""), 
            "Sorter_ContactReplyContent" => array("ContactReplyContent", ""), 
            "Sorter_IsActive" => array("contactus.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @13-BACEFC6D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_ContactUser", ccsInteger, "", "", $this->Parameters["urls_ContactUser"], "", false);
        $this->wp->AddParameter("2", "urls_ContactReplyBy", ccsInteger, "", "", $this->Parameters["urls_ContactReplyBy"], "", false);
        $this->wp->AddParameter("3", "urls_ContactName", ccsText, "", "", $this->Parameters["urls_ContactName"], "", false);
        $this->wp->AddParameter("4", "urls_ContactEmail", ccsText, "", "", $this->Parameters["urls_ContactEmail"], "", false);
        $this->wp->AddParameter("5", "urls_ContactPhone", ccsText, "", "", $this->Parameters["urls_ContactPhone"], "", false);
        $this->wp->AddParameter("6", "urls_ContactSubject", ccsText, "", "", $this->Parameters["urls_ContactSubject"], "", false);
        $this->wp->AddParameter("7", "urls_ContactContent", ccsText, "", "", $this->Parameters["urls_ContactContent"], "", false);
        $this->wp->AddParameter("8", "urls_ContactReplyContent", ccsText, "", "", $this->Parameters["urls_ContactReplyContent"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "contactus.ContactUser", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "contactus.ContactReplyBy", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "contactus.ContactName", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "contactus.ContactEmail", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "contactus.ContactPhone", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opContains, "contactus.ContactSubject", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opContains, "contactus.ContactContent", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsText),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opContains, "contactus.ContactReplyContent", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]), 
             $this->wp->Criterion[5]), 
             $this->wp->Criterion[6]), 
             $this->wp->Criterion[7]), 
             $this->wp->Criterion[8]);
    }
//End Prepare Method

//Open Method @13-64A3430E
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM (contactus LEFT JOIN users ON\n\n" .
        "contactus.ContactUser = users.Id) LEFT JOIN users users1 ON\n\n" .
        "contactus.ContactReplyBy = users1.Id";
        $this->SQL = "SELECT contactus.Id AS contactus_Id, ContactDate, ContactTime, ContactName, ContactEmail, ContactPhone, users.UserId AS users_UserId,\n\n" .
        "ContactSubject, ContactContent, ContactReplyDate, ContactReplyTime, users1.UserId AS users1_UserId, ContactReplyContent,\n\n" .
        "contactus.IsActive AS contactus_IsActive \n\n" .
        "FROM (contactus LEFT JOIN users ON\n\n" .
        "contactus.ContactUser = users.Id) LEFT JOIN users users1 ON\n\n" .
        "contactus.ContactReplyBy = users1.Id {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @13-6361DA4D
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("contactus_Id")));
        $this->ContactDate->SetDBValue(trim($this->f("ContactDate")));
        $this->ContactTime->SetDBValue(trim($this->f("ContactTime")));
        $this->ContactName->SetDBValue($this->f("ContactName"));
        $this->ContactEmail->SetDBValue($this->f("ContactEmail"));
        $this->ContactPhone->SetDBValue($this->f("ContactPhone"));
        $this->ContactUser->SetDBValue($this->f("users_UserId"));
        $this->ContactSubject->SetDBValue($this->f("ContactSubject"));
        $this->ContactContent->SetDBValue($this->f("ContactContent"));
        $this->ContactReplyDate->SetDBValue(trim($this->f("ContactReplyDate")));
        $this->ContactReplyTime->SetDBValue(trim($this->f("ContactReplyTime")));
        $this->ContactReplyBy->SetDBValue($this->f("users1_UserId"));
        $this->ContactReplyContent->SetDBValue($this->f("ContactReplyContent"));
        $this->IsActive->SetDBValue(trim($this->f("contactus_IsActive")));
    }
//End SetValues Method

} //End contactusDataSource Class @13-FCB6E20C

class clsRecordcontactusSearch { //contactusSearch Class @2-9D6FFCC3

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

//Class_Initialize Event @2-6A5592D8
    function clsRecordcontactusSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record contactusSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "contactusSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_ContactName = new clsControl(ccsTextBox, "s_ContactName", "Contact Name", ccsText, "", CCGetRequestParam("s_ContactName", $Method, NULL), $this);
            $this->s_ContactEmail = new clsControl(ccsTextBox, "s_ContactEmail", "Contact Email", ccsText, "", CCGetRequestParam("s_ContactEmail", $Method, NULL), $this);
            $this->s_ContactPhone = new clsControl(ccsTextBox, "s_ContactPhone", "Contact Phone", ccsText, "", CCGetRequestParam("s_ContactPhone", $Method, NULL), $this);
            $this->s_ContactUser = new clsControl(ccsListBox, "s_ContactUser", "Contact User", ccsInteger, "", CCGetRequestParam("s_ContactUser", $Method, NULL), $this);
            $this->s_ContactUser->DSType = dsTable;
            $this->s_ContactUser->DataSource = new clsDBFuelSaver();
            $this->s_ContactUser->ds = & $this->s_ContactUser->DataSource;
            $this->s_ContactUser->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->s_ContactUser->BoundColumn, $this->s_ContactUser->TextColumn, $this->s_ContactUser->DBFormat) = array("Id", "UserId", "");
            $this->s_ContactSubject = new clsControl(ccsTextBox, "s_ContactSubject", "Contact Subject", ccsText, "", CCGetRequestParam("s_ContactSubject", $Method, NULL), $this);
            $this->s_ContactContent = new clsControl(ccsTextBox, "s_ContactContent", "Contact Content", ccsText, "", CCGetRequestParam("s_ContactContent", $Method, NULL), $this);
            $this->s_ContactReplyBy = new clsControl(ccsListBox, "s_ContactReplyBy", "Contact Reply By", ccsInteger, "", CCGetRequestParam("s_ContactReplyBy", $Method, NULL), $this);
            $this->s_ContactReplyBy->DSType = dsTable;
            $this->s_ContactReplyBy->DataSource = new clsDBFuelSaver();
            $this->s_ContactReplyBy->ds = & $this->s_ContactReplyBy->DataSource;
            $this->s_ContactReplyBy->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->s_ContactReplyBy->BoundColumn, $this->s_ContactReplyBy->TextColumn, $this->s_ContactReplyBy->DBFormat) = array("Id", "UserId", "");
            $this->s_ContactReplyContent = new clsControl(ccsTextBox, "s_ContactReplyContent", "Contact Reply Content", ccsText, "", CCGetRequestParam("s_ContactReplyContent", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @2-53BE4217
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_ContactName->Validate() && $Validation);
        $Validation = ($this->s_ContactEmail->Validate() && $Validation);
        $Validation = ($this->s_ContactPhone->Validate() && $Validation);
        $Validation = ($this->s_ContactUser->Validate() && $Validation);
        $Validation = ($this->s_ContactSubject->Validate() && $Validation);
        $Validation = ($this->s_ContactContent->Validate() && $Validation);
        $Validation = ($this->s_ContactReplyBy->Validate() && $Validation);
        $Validation = ($this->s_ContactReplyContent->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_ContactName->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ContactEmail->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ContactPhone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ContactUser->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ContactSubject->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ContactContent->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ContactReplyBy->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ContactReplyContent->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-127E2348
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_ContactName->Errors->Count());
        $errors = ($errors || $this->s_ContactEmail->Errors->Count());
        $errors = ($errors || $this->s_ContactPhone->Errors->Count());
        $errors = ($errors || $this->s_ContactUser->Errors->Count());
        $errors = ($errors || $this->s_ContactSubject->Errors->Count());
        $errors = ($errors || $this->s_ContactContent->Errors->Count());
        $errors = ($errors || $this->s_ContactReplyBy->Errors->Count());
        $errors = ($errors || $this->s_ContactReplyContent->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-CFC49535
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
        $Redirect = "contactus_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "contactus_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-22DA81BF
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

        $this->s_ContactUser->Prepare();
        $this->s_ContactReplyBy->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_ContactName->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ContactEmail->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ContactPhone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ContactUser->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ContactSubject->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ContactContent->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ContactReplyBy->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ContactReplyContent->Errors->ToString());
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
        $this->s_ContactName->Show();
        $this->s_ContactEmail->Show();
        $this->s_ContactPhone->Show();
        $this->s_ContactUser->Show();
        $this->s_ContactSubject->Show();
        $this->s_ContactContent->Show();
        $this->s_ContactReplyBy->Show();
        $this->s_ContactReplyContent->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End contactusSearch Class @2-FCB6E20C

//Include Page implementation @79-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Include Page implementation @111-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

class clsMenuMenu1 extends clsMenu { //Menu1 class @80-FEAC4CDE

//Class_Initialize Event @80-6674A2CC
    function clsMenuMenu1($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Menu1";
        $this->Visible = True;
        $this->controls = array();
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->ErrorBlock = "Menu Menu1";

        $this->StaticItems = array();
        $this->StaticItems[] = array("item_id" => "MenuItem1", "item_id_parent" => null, "item_caption" => "Home", "item_url" => array("Page" => "index.php", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem2", "item_id_parent" => null, "item_caption" => "Product", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem3", "item_id_parent" => null, "item_caption" => "Testimonies", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem4", "item_id_parent" => null, "item_caption" => "Shop", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem4Item1", "item_id_parent" => "MenuItem4", "item_caption" => "Register", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem4Item2", "item_id_parent" => "MenuItem4", "item_caption" => "Buy", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem4Item3", "item_id_parent" => "MenuItem4", "item_caption" => "Pay", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem5", "item_id_parent" => null, "item_caption" => "Contact Us", "item_url" => array("Page" => "contactus_maint.php", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem6", "item_id_parent" => null, "item_caption" => "Profile", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem6Item1", "item_id_parent" => "MenuItem6", "item_caption" => "My Profile", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem6Item2", "item_id_parent" => "MenuItem6", "item_caption" => "Group Sales", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem6Item3", "item_id_parent" => "MenuItem6", "item_caption" => "My Transactions", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7", "item_id_parent" => null, "item_caption" => "Administration", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item1", "item_id_parent" => "MenuItem7", "item_caption" => "Manage", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item1Item1", "item_id_parent" => "MenuItem7Item1", "item_caption" => "Approve Registrations", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item1Item2", "item_id_parent" => "MenuItem7Item1", "item_caption" => "Verify Recipts", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item1Item3", "item_id_parent" => "MenuItem7Item1", "item_caption" => "Manufacture Products", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item1Item4", "item_id_parent" => "MenuItem7Item1", "item_caption" => "Deliver Products", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item2", "item_id_parent" => "MenuItem7", "item_caption" => "Reports", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item2Item1", "item_id_parent" => "MenuItem7Item2", "item_caption" => "Registration Report", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item2Item2", "item_id_parent" => "MenuItem7Item2", "item_caption" => "Receipt Report", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item2Item4", "item_id_parent" => "MenuItem7Item2", "item_caption" => "Manufacturing Report", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item2Item3", "item_id_parent" => "MenuItem7Item2", "item_caption" => "Delivery Report", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item2Item5", "item_id_parent" => "MenuItem7Item2", "item_caption" => "Inventory Report", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item3", "item_id_parent" => "MenuItem7", "item_caption" => "Maintain", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item3Item1", "item_id_parent" => "MenuItem7Item3", "item_caption" => "Countries", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item3Item2", "item_id_parent" => "MenuItem7Item3", "item_caption" => "States", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item3Item3", "item_id_parent" => "MenuItem7Item3", "item_caption" => "Product categories", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");
        $this->StaticItems[] = array("item_id" => "MenuItem7Item3Item4", "item_id_parent" => "MenuItem7Item3", "item_caption" => "Products", "item_url" => array("Page" => "", "Parameters" => null), "item_target" => "", "item_title" => "");

        $this->DataSource = new clsMenu1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->DataSource->SetProvider(array("DBLib" => "Array"));

        parent::clsMenu("item_id_parent", "item_id", null);

        $this->ItemLink = new clsControl(ccsLink, "ItemLink", "ItemLink", ccsText, "", CCGetRequestParam("ItemLink", ccsGet, NULL), $this);
        $this->controls["ItemLink"] = & $this->ItemLink;
        $this->ItemLink->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->ItemLink->Page = "";
        $this->LinkStartParameters = $this->ItemLink->Parameters;
    }
//End Class_Initialize Event

//SetControlValues Method @80-B7BF812B
    function SetControlValues() {
        $this->ItemLink->SetValue($this->DataSource->ItemLink->GetValue());
        $LinkUrl = $this->DataSource->f("item_url");
        $this->ItemLink->Page = $LinkUrl["Page"];
        $this->ItemLink->Parameters = $this->SetParamsFromDB($this->LinkStartParameters, $LinkUrl["Parameters"]);
    }
//End SetControlValues Method

//ShowAttributes @80-17684C76
    function ShowAttributes() {
        $this->Attributes->SetValue("MenuType", "menu_htb");
        $this->Attributes->Show();
    }
//End ShowAttributes

} //End Menu1 Class @80-FCB6E20C

//Menu1DataSource Class @80-201CC8D7
class clsMenu1DataSource extends DB_Adapter {
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;
    var $wp;
    var $Record = array();
    var $Index;
    var $FieldsList = array();

    function clsMenu1DataSource($parent) {
        $this->Parent = & $parent;
        $this->ErrorBlock = "Menu Menu1";
        $this->ItemLink = new clsField("ItemLink", ccsText, "");
        $this->FieldsList["ItemLink"] = & $this->ItemLink;
    }

    function Prepare()
    {
    }

    function Open()
    {
        $this->query($this->Parent->StaticItems);
    }

    function SetValues()
    {
        $this->ItemLink->SetDBValue($this->f("item_caption"));
    }
}
//End Menu1DataSource Class

//Initialize Page @1-4B32D3F9
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
$TemplateFileName = "contactus_list.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/menu/ccs-menu.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Authenticate User @1-4B0BB954
CCSecurityRedirect("3", "");
//End Authenticate User

//Include events file @1-9A793B65
include_once("./contactus_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-1F045C10
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
$contactus = new clsGridcontactus("", $MainPage);
$contactusSearch = new clsRecordcontactusSearch("", $MainPage);
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
$Menu1 = new clsMenuMenu1("", $MainPage);
$MainPage->Head = & $Head;
$MainPage->Content = & $Content;
$MainPage->contactus = & $contactus;
$MainPage->contactusSearch = & $contactusSearch;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu1 = & $Menu1;
$Content->AddComponent("contactus", $contactus);
$Content->AddComponent("contactusSearch", $contactusSearch);
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

//Execute Components @1-5967FDAD
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$contactusSearch->Operation();
//End Execute Components

//Go to destination page @1-CFB073EC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($contactus);
    unset($contactusSearch);
    $MenuIncludablePage->Class_Terminate();
    unset($MenuIncludablePage);
    $headerIncludablePage->Class_Terminate();
    unset($headerIncludablePage);
    unset($Menu1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-BD318DDD
$Menu1->Show();
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

//Unload Page @1-10240688
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($contactus);
unset($contactusSearch);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Menu1);
unset($Tpl);
//End Unload Page


?>
