<?php
//Include Common Files @1-C43A8E7E
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "users_list.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridusers { //users class @15-0CB76799

//Variables @15-F3D627BA

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
    public $Sorter_UserId;
    public $Sorter_UserFullName;
    public $Sorter_UserRole1;
    public $Sorter_UserEmail;
    public $Sorter_UserTelephone;
    public $Sorter_UserAddress1;
    public $Sorter_UserAddress2;
    public $Sorter_UserAddress3;
    public $Sorter_UserTown;
    public $Sorter_State;
    public $Sorter_IsActive;
//End Variables

//Class_Initialize Event @15-A1692FAF
    function clsGridusers($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "users";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid users";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsusersDataSource($this);
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
        $this->SorterName = CCGetParam("usersOrder", "");
        $this->SorterDirection = CCGetParam("usersDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "users_maint.php";
        $this->UserId = new clsControl(ccsLabel, "UserId", "UserId", ccsText, "", CCGetRequestParam("UserId", ccsGet, NULL), $this);
        $this->UserFullName = new clsControl(ccsLabel, "UserFullName", "UserFullName", ccsText, "", CCGetRequestParam("UserFullName", ccsGet, NULL), $this);
        $this->UserRole1 = new clsControl(ccsLabel, "UserRole1", "UserRole1", ccsText, "", CCGetRequestParam("UserRole1", ccsGet, NULL), $this);
        $this->UserEmail = new clsControl(ccsLabel, "UserEmail", "UserEmail", ccsText, "", CCGetRequestParam("UserEmail", ccsGet, NULL), $this);
        $this->UserTelephone = new clsControl(ccsLabel, "UserTelephone", "UserTelephone", ccsText, "", CCGetRequestParam("UserTelephone", ccsGet, NULL), $this);
        $this->UserAddress1 = new clsControl(ccsLabel, "UserAddress1", "UserAddress1", ccsText, "", CCGetRequestParam("UserAddress1", ccsGet, NULL), $this);
        $this->UserAddress2 = new clsControl(ccsLabel, "UserAddress2", "UserAddress2", ccsText, "", CCGetRequestParam("UserAddress2", ccsGet, NULL), $this);
        $this->UserAddress3 = new clsControl(ccsLabel, "UserAddress3", "UserAddress3", ccsText, "", CCGetRequestParam("UserAddress3", ccsGet, NULL), $this);
        $this->UserTown = new clsControl(ccsLabel, "UserTown", "UserTown", ccsText, "", CCGetRequestParam("UserTown", ccsGet, NULL), $this);
        $this->State = new clsControl(ccsLabel, "State", "State", ccsText, "", CCGetRequestParam("State", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->users_Insert = new clsControl(ccsLink, "users_Insert", "users_Insert", ccsText, "", CCGetRequestParam("users_Insert", ccsGet, NULL), $this);
        $this->users_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->users_Insert->Page = "users_maint.php";
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_UserId = new clsSorter($this->ComponentName, "Sorter_UserId", $FileName, $this);
        $this->Sorter_UserFullName = new clsSorter($this->ComponentName, "Sorter_UserFullName", $FileName, $this);
        $this->Sorter_UserRole1 = new clsSorter($this->ComponentName, "Sorter_UserRole1", $FileName, $this);
        $this->Sorter_UserEmail = new clsSorter($this->ComponentName, "Sorter_UserEmail", $FileName, $this);
        $this->Sorter_UserTelephone = new clsSorter($this->ComponentName, "Sorter_UserTelephone", $FileName, $this);
        $this->Sorter_UserAddress1 = new clsSorter($this->ComponentName, "Sorter_UserAddress1", $FileName, $this);
        $this->Sorter_UserAddress2 = new clsSorter($this->ComponentName, "Sorter_UserAddress2", $FileName, $this);
        $this->Sorter_UserAddress3 = new clsSorter($this->ComponentName, "Sorter_UserAddress3", $FileName, $this);
        $this->Sorter_UserTown = new clsSorter($this->ComponentName, "Sorter_UserTown", $FileName, $this);
        $this->Sorter_State = new clsSorter($this->ComponentName, "Sorter_State", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @15-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @15-F0834192
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_UserRole"] = CCGetFromGet("s_UserRole", NULL);
        $this->DataSource->Parameters["urls_UserState"] = CCGetFromGet("s_UserState", NULL);
        $this->DataSource->Parameters["urls_UserId"] = CCGetFromGet("s_UserId", NULL);
        $this->DataSource->Parameters["urls_UserFullName"] = CCGetFromGet("s_UserFullName", NULL);
        $this->DataSource->Parameters["urls_UserEmail"] = CCGetFromGet("s_UserEmail", NULL);
        $this->DataSource->Parameters["urls_UserTelephone"] = CCGetFromGet("s_UserTelephone", NULL);
        $this->DataSource->Parameters["urls_UserAddress1"] = CCGetFromGet("s_UserAddress1", NULL);
        $this->DataSource->Parameters["urls_UserAddress2"] = CCGetFromGet("s_UserAddress2", NULL);
        $this->DataSource->Parameters["urls_UserAddress3"] = CCGetFromGet("s_UserAddress3", NULL);
        $this->DataSource->Parameters["urls_UserTown"] = CCGetFromGet("s_UserTown", NULL);

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
            $this->ControlsVisible["UserId"] = $this->UserId->Visible;
            $this->ControlsVisible["UserFullName"] = $this->UserFullName->Visible;
            $this->ControlsVisible["UserRole1"] = $this->UserRole1->Visible;
            $this->ControlsVisible["UserEmail"] = $this->UserEmail->Visible;
            $this->ControlsVisible["UserTelephone"] = $this->UserTelephone->Visible;
            $this->ControlsVisible["UserAddress1"] = $this->UserAddress1->Visible;
            $this->ControlsVisible["UserAddress2"] = $this->UserAddress2->Visible;
            $this->ControlsVisible["UserAddress3"] = $this->UserAddress3->Visible;
            $this->ControlsVisible["UserTown"] = $this->UserTown->Visible;
            $this->ControlsVisible["State"] = $this->State->Visible;
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
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("users_Id"));
                $this->UserId->SetValue($this->DataSource->UserId->GetValue());
                $this->UserFullName->SetValue($this->DataSource->UserFullName->GetValue());
                $this->UserRole1->SetValue($this->DataSource->UserRole1->GetValue());
                $this->UserEmail->SetValue($this->DataSource->UserEmail->GetValue());
                $this->UserTelephone->SetValue($this->DataSource->UserTelephone->GetValue());
                $this->UserAddress1->SetValue($this->DataSource->UserAddress1->GetValue());
                $this->UserAddress2->SetValue($this->DataSource->UserAddress2->GetValue());
                $this->UserAddress3->SetValue($this->DataSource->UserAddress3->GetValue());
                $this->UserTown->SetValue($this->DataSource->UserTown->GetValue());
                $this->State->SetValue($this->DataSource->State->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->UserId->Show();
                $this->UserFullName->Show();
                $this->UserRole1->Show();
                $this->UserEmail->Show();
                $this->UserTelephone->Show();
                $this->UserAddress1->Show();
                $this->UserAddress2->Show();
                $this->UserAddress3->Show();
                $this->UserTown->Show();
                $this->State->Show();
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
        $this->users_Insert->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_UserId->Show();
        $this->Sorter_UserFullName->Show();
        $this->Sorter_UserRole1->Show();
        $this->Sorter_UserEmail->Show();
        $this->Sorter_UserTelephone->Show();
        $this->Sorter_UserAddress1->Show();
        $this->Sorter_UserAddress2->Show();
        $this->Sorter_UserAddress3->Show();
        $this->Sorter_UserTown->Show();
        $this->Sorter_State->Show();
        $this->Sorter_IsActive->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @15-20F71CB2
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserFullName->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserRole1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserEmail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserTelephone->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserAddress1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserAddress2->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserAddress3->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserTown->Errors->ToString());
        $errors = ComposeStrings($errors, $this->State->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End users Class @15-FCB6E20C

class clsusersDataSource extends clsDBFuelSaver {  //usersDataSource Class @15-032B5816

//DataSource Variables @15-5EDB9B5E
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $UserId;
    public $UserFullName;
    public $UserRole1;
    public $UserEmail;
    public $UserTelephone;
    public $UserAddress1;
    public $UserAddress2;
    public $UserAddress3;
    public $UserTown;
    public $State;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @15-FC87A78E
    function clsusersDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid users";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->UserId = new clsField("UserId", ccsText, "");
        
        $this->UserFullName = new clsField("UserFullName", ccsText, "");
        
        $this->UserRole1 = new clsField("UserRole1", ccsText, "");
        
        $this->UserEmail = new clsField("UserEmail", ccsText, "");
        
        $this->UserTelephone = new clsField("UserTelephone", ccsText, "");
        
        $this->UserAddress1 = new clsField("UserAddress1", ccsText, "");
        
        $this->UserAddress2 = new clsField("UserAddress2", ccsText, "");
        
        $this->UserAddress3 = new clsField("UserAddress3", ccsText, "");
        
        $this->UserTown = new clsField("UserTown", ccsText, "");
        
        $this->State = new clsField("State", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @15-6E37FD5A
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("users.Id", ""), 
            "Sorter_UserId" => array("UserId", ""), 
            "Sorter_UserFullName" => array("UserFullName", ""), 
            "Sorter_UserRole1" => array("userroles.UserRole", ""), 
            "Sorter_UserEmail" => array("UserEmail", ""), 
            "Sorter_UserTelephone" => array("UserTelephone", ""), 
            "Sorter_UserAddress1" => array("UserAddress1", ""), 
            "Sorter_UserAddress2" => array("UserAddress2", ""), 
            "Sorter_UserAddress3" => array("UserAddress3", ""), 
            "Sorter_UserTown" => array("UserTown", ""), 
            "Sorter_State" => array("State", ""), 
            "Sorter_IsActive" => array("users.IsActive", "")));
    }
//End SetOrder Method

//Prepare Method @15-BE014C5A
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_UserRole", ccsInteger, "", "", $this->Parameters["urls_UserRole"], "", false);
        $this->wp->AddParameter("2", "urls_UserState", ccsInteger, "", "", $this->Parameters["urls_UserState"], "", false);
        $this->wp->AddParameter("3", "urls_UserId", ccsText, "", "", $this->Parameters["urls_UserId"], "", false);
        $this->wp->AddParameter("4", "urls_UserFullName", ccsText, "", "", $this->Parameters["urls_UserFullName"], "", false);
        $this->wp->AddParameter("5", "urls_UserEmail", ccsText, "", "", $this->Parameters["urls_UserEmail"], "", false);
        $this->wp->AddParameter("6", "urls_UserTelephone", ccsText, "", "", $this->Parameters["urls_UserTelephone"], "", false);
        $this->wp->AddParameter("7", "urls_UserAddress1", ccsText, "", "", $this->Parameters["urls_UserAddress1"], "", false);
        $this->wp->AddParameter("8", "urls_UserAddress2", ccsText, "", "", $this->Parameters["urls_UserAddress2"], "", false);
        $this->wp->AddParameter("9", "urls_UserAddress3", ccsText, "", "", $this->Parameters["urls_UserAddress3"], "", false);
        $this->wp->AddParameter("10", "urls_UserTown", ccsText, "", "", $this->Parameters["urls_UserTown"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "users.UserRole", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "users.UserState", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "users.UserId", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "users.UserFullName", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "users.UserEmail", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opContains, "users.UserTelephone", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsText),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opContains, "users.UserAddress1", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsText),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opContains, "users.UserAddress2", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsText),false);
        $this->wp->Criterion[9] = $this->wp->Operation(opContains, "users.UserAddress3", $this->wp->GetDBValue("9"), $this->ToSQL($this->wp->GetDBValue("9"), ccsText),false);
        $this->wp->Criterion[10] = $this->wp->Operation(opContains, "users.UserTown", $this->wp->GetDBValue("10"), $this->ToSQL($this->wp->GetDBValue("10"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
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
             $this->wp->Criterion[8]), 
             $this->wp->Criterion[9]), 
             $this->wp->Criterion[10]);
    }
//End Prepare Method

//Open Method @15-F44BCAF4
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM (users LEFT JOIN userroles ON\n\n" .
        "users.UserRole = userroles.Id) LEFT JOIN states ON\n\n" .
        "users.UserState = states.Id";
        $this->SQL = "SELECT users.Id AS users_Id, UserId, UserFullName, userroles.UserRole AS userroles_UserRole, UserEmail, UserTelephone, UserAddress1,\n\n" .
        "UserAddress2, UserAddress3, UserTown, State, users.IsActive AS users_IsActive \n\n" .
        "FROM (users LEFT JOIN userroles ON\n\n" .
        "users.UserRole = userroles.Id) LEFT JOIN states ON\n\n" .
        "users.UserState = states.Id {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @15-97753E3F
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("users_Id")));
        $this->UserId->SetDBValue($this->f("UserId"));
        $this->UserFullName->SetDBValue($this->f("UserFullName"));
        $this->UserRole1->SetDBValue($this->f("userroles_UserRole"));
        $this->UserEmail->SetDBValue($this->f("UserEmail"));
        $this->UserTelephone->SetDBValue($this->f("UserTelephone"));
        $this->UserAddress1->SetDBValue($this->f("UserAddress1"));
        $this->UserAddress2->SetDBValue($this->f("UserAddress2"));
        $this->UserAddress3->SetDBValue($this->f("UserAddress3"));
        $this->UserTown->SetDBValue($this->f("UserTown"));
        $this->State->SetDBValue($this->f("State"));
        $this->IsActive->SetDBValue(trim($this->f("users_IsActive")));
    }
//End SetValues Method

} //End usersDataSource Class @15-FCB6E20C

class clsRecordusersSearch { //usersSearch Class @2-C4FF86BD

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

//Class_Initialize Event @2-47DED39B
    function clsRecordusersSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record usersSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "usersSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_UserId = new clsControl(ccsTextBox, "s_UserId", "User Id", ccsText, "", CCGetRequestParam("s_UserId", $Method, NULL), $this);
            $this->s_UserFullName = new clsControl(ccsTextBox, "s_UserFullName", "User Full Name", ccsText, "", CCGetRequestParam("s_UserFullName", $Method, NULL), $this);
            $this->s_UserRole = new clsControl(ccsListBox, "s_UserRole", "User Role", ccsInteger, "", CCGetRequestParam("s_UserRole", $Method, NULL), $this);
            $this->s_UserRole->DSType = dsTable;
            $this->s_UserRole->DataSource = new clsDBFuelSaver();
            $this->s_UserRole->ds = & $this->s_UserRole->DataSource;
            $this->s_UserRole->DataSource->SQL = "SELECT * \n" .
"FROM userroles {SQL_Where} {SQL_OrderBy}";
            list($this->s_UserRole->BoundColumn, $this->s_UserRole->TextColumn, $this->s_UserRole->DBFormat) = array("Id", "UserRole", "");
            $this->s_UserEmail = new clsControl(ccsTextBox, "s_UserEmail", "User Email", ccsText, "", CCGetRequestParam("s_UserEmail", $Method, NULL), $this);
            $this->s_UserTelephone = new clsControl(ccsTextBox, "s_UserTelephone", "User Telephone", ccsText, "", CCGetRequestParam("s_UserTelephone", $Method, NULL), $this);
            $this->s_UserAddress1 = new clsControl(ccsTextBox, "s_UserAddress1", "User Address1", ccsText, "", CCGetRequestParam("s_UserAddress1", $Method, NULL), $this);
            $this->s_UserAddress2 = new clsControl(ccsTextBox, "s_UserAddress2", "User Address2", ccsText, "", CCGetRequestParam("s_UserAddress2", $Method, NULL), $this);
            $this->s_UserAddress3 = new clsControl(ccsTextBox, "s_UserAddress3", "User Address3", ccsText, "", CCGetRequestParam("s_UserAddress3", $Method, NULL), $this);
            $this->s_UserTown = new clsControl(ccsTextBox, "s_UserTown", "User Town", ccsText, "", CCGetRequestParam("s_UserTown", $Method, NULL), $this);
            $this->s_UserState = new clsControl(ccsListBox, "s_UserState", "User State", ccsInteger, "", CCGetRequestParam("s_UserState", $Method, NULL), $this);
            $this->s_UserState->DSType = dsTable;
            $this->s_UserState->DataSource = new clsDBFuelSaver();
            $this->s_UserState->ds = & $this->s_UserState->DataSource;
            $this->s_UserState->DataSource->SQL = "SELECT * \n" .
"FROM states {SQL_Where} {SQL_OrderBy}";
            list($this->s_UserState->BoundColumn, $this->s_UserState->TextColumn, $this->s_UserState->DBFormat) = array("Id", "State", "");
        }
    }
//End Class_Initialize Event

//Validate Method @2-C64E9962
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_UserId->Validate() && $Validation);
        $Validation = ($this->s_UserFullName->Validate() && $Validation);
        $Validation = ($this->s_UserRole->Validate() && $Validation);
        $Validation = ($this->s_UserEmail->Validate() && $Validation);
        $Validation = ($this->s_UserTelephone->Validate() && $Validation);
        $Validation = ($this->s_UserAddress1->Validate() && $Validation);
        $Validation = ($this->s_UserAddress2->Validate() && $Validation);
        $Validation = ($this->s_UserAddress3->Validate() && $Validation);
        $Validation = ($this->s_UserTown->Validate() && $Validation);
        $Validation = ($this->s_UserState->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_UserId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserFullName->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserRole->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserEmail->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserTelephone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserAddress1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserAddress2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserAddress3->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserTown->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserState->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-962B75BD
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_UserId->Errors->Count());
        $errors = ($errors || $this->s_UserFullName->Errors->Count());
        $errors = ($errors || $this->s_UserRole->Errors->Count());
        $errors = ($errors || $this->s_UserEmail->Errors->Count());
        $errors = ($errors || $this->s_UserTelephone->Errors->Count());
        $errors = ($errors || $this->s_UserAddress1->Errors->Count());
        $errors = ($errors || $this->s_UserAddress2->Errors->Count());
        $errors = ($errors || $this->s_UserAddress3->Errors->Count());
        $errors = ($errors || $this->s_UserTown->Errors->Count());
        $errors = ($errors || $this->s_UserState->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-51996F74
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
        $Redirect = "users_list.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "users_list.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @2-2B6A7728
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

        $this->s_UserRole->Prepare();
        $this->s_UserState->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_UserId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserFullName->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserRole->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserEmail->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserTelephone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserAddress1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserAddress2->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserAddress3->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserTown->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserState->Errors->ToString());
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
        $this->s_UserId->Show();
        $this->s_UserFullName->Show();
        $this->s_UserRole->Show();
        $this->s_UserEmail->Show();
        $this->s_UserTelephone->Show();
        $this->s_UserAddress1->Show();
        $this->s_UserAddress2->Show();
        $this->s_UserAddress3->Show();
        $this->s_UserTown->Show();
        $this->s_UserState->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End usersSearch Class @2-FCB6E20C

//Include Page implementation @77-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Include Page implementation @78-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Initialize Page @1-C2CF76F5
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
$TemplateFileName = "users_list.html";
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

//Include events file @1-13285D66
include_once("./users_list_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-7F874332
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
$users = new clsGridusers("", $MainPage);
$usersSearch = new clsRecordusersSearch("", $MainPage);
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
$MainPage->users = & $users;
$MainPage->usersSearch = & $usersSearch;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Sidebar1 = & $Sidebar1;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$Content->AddComponent("users", $users);
$Content->AddComponent("usersSearch", $usersSearch);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$users->Initialize();
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

//Execute Components @1-E618226A
$MasterPage->Operations();
$headerIncludablePage->Operations();
$MenuIncludablePage->Operations();
$usersSearch->Operation();
//End Execute Components

//Go to destination page @1-9266F2CA
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($users);
    unset($usersSearch);
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

//Unload Page @1-1515789B
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($users);
unset($usersSearch);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
unset($Tpl);
//End Unload Page


?>
