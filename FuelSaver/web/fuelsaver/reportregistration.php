<?php
//Include Common Files @1-2A0AF85B
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "reportregistration.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

//Include Page implementation @180-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

class clsGridreportregistrations { //reportregistrations class @9-431FFFC1

//Variables @9-18E0870C

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
    public $Sorter_Role;
    public $Sorter_UserEmail;
    public $Sorter_UserTelephone;
    public $Sorter_UserAddress1;
    public $Sorter_UserAddress2;
    public $Sorter_UserAddress3;
    public $Sorter_UserTown;
    public $Sorter_postcode;
    public $Sorter_State;
    public $Sorter_Country;
    public $Sorter_IsActive;
    public $Sorter_UserRole;
    public $Sorter_UserState;
    public $Sorter_UserCountry;
//End Variables

//Class_Initialize Event @9-6603A71A
    function clsGridreportregistrations($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "reportregistrations";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid reportregistrations";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsreportregistrationsDataSource($this);
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
        $this->SorterName = CCGetParam("reportregistrationsOrder", "");
        $this->SorterDirection = CCGetParam("reportregistrationsDir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "";
        $this->UserId = new clsControl(ccsLabel, "UserId", "UserId", ccsText, "", CCGetRequestParam("UserId", ccsGet, NULL), $this);
        $this->UserFullName = new clsControl(ccsLabel, "UserFullName", "UserFullName", ccsText, "", CCGetRequestParam("UserFullName", ccsGet, NULL), $this);
        $this->Role = new clsControl(ccsLabel, "Role", "Role", ccsText, "", CCGetRequestParam("Role", ccsGet, NULL), $this);
        $this->UserEmail = new clsControl(ccsLabel, "UserEmail", "UserEmail", ccsText, "", CCGetRequestParam("UserEmail", ccsGet, NULL), $this);
        $this->UserTelephone = new clsControl(ccsLabel, "UserTelephone", "UserTelephone", ccsText, "", CCGetRequestParam("UserTelephone", ccsGet, NULL), $this);
        $this->UserAddress1 = new clsControl(ccsLabel, "UserAddress1", "UserAddress1", ccsText, "", CCGetRequestParam("UserAddress1", ccsGet, NULL), $this);
        $this->UserAddress2 = new clsControl(ccsLabel, "UserAddress2", "UserAddress2", ccsText, "", CCGetRequestParam("UserAddress2", ccsGet, NULL), $this);
        $this->UserAddress3 = new clsControl(ccsLabel, "UserAddress3", "UserAddress3", ccsText, "", CCGetRequestParam("UserAddress3", ccsGet, NULL), $this);
        $this->UserTown = new clsControl(ccsLabel, "UserTown", "UserTown", ccsText, "", CCGetRequestParam("UserTown", ccsGet, NULL), $this);
        $this->postcode = new clsControl(ccsLabel, "postcode", "postcode", ccsInteger, "", CCGetRequestParam("postcode", ccsGet, NULL), $this);
        $this->State = new clsControl(ccsLabel, "State", "State", ccsText, "", CCGetRequestParam("State", ccsGet, NULL), $this);
        $this->Country = new clsControl(ccsLabel, "Country", "Country", ccsText, "", CCGetRequestParam("Country", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->UserRole = new clsControl(ccsLabel, "UserRole", "UserRole", ccsInteger, "", CCGetRequestParam("UserRole", ccsGet, NULL), $this);
        $this->UserState = new clsControl(ccsLabel, "UserState", "UserState", ccsInteger, "", CCGetRequestParam("UserState", ccsGet, NULL), $this);
        $this->UserCountry = new clsControl(ccsLabel, "UserCountry", "UserCountry", ccsInteger, "", CCGetRequestParam("UserCountry", ccsGet, NULL), $this);
        $this->reportregistrations_Insert = new clsControl(ccsLink, "reportregistrations_Insert", "reportregistrations_Insert", ccsText, "", CCGetRequestParam("reportregistrations_Insert", ccsGet, NULL), $this);
        $this->reportregistrations_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->reportregistrations_Insert->Page = "reportregistration.php";
        $this->reportregistrations_TotalRecords = new clsControl(ccsLabel, "reportregistrations_TotalRecords", "reportregistrations_TotalRecords", ccsText, "", CCGetRequestParam("reportregistrations_TotalRecords", ccsGet, NULL), $this);
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_UserId = new clsSorter($this->ComponentName, "Sorter_UserId", $FileName, $this);
        $this->Sorter_UserFullName = new clsSorter($this->ComponentName, "Sorter_UserFullName", $FileName, $this);
        $this->Sorter_Role = new clsSorter($this->ComponentName, "Sorter_Role", $FileName, $this);
        $this->Sorter_UserEmail = new clsSorter($this->ComponentName, "Sorter_UserEmail", $FileName, $this);
        $this->Sorter_UserTelephone = new clsSorter($this->ComponentName, "Sorter_UserTelephone", $FileName, $this);
        $this->Sorter_UserAddress1 = new clsSorter($this->ComponentName, "Sorter_UserAddress1", $FileName, $this);
        $this->Sorter_UserAddress2 = new clsSorter($this->ComponentName, "Sorter_UserAddress2", $FileName, $this);
        $this->Sorter_UserAddress3 = new clsSorter($this->ComponentName, "Sorter_UserAddress3", $FileName, $this);
        $this->Sorter_UserTown = new clsSorter($this->ComponentName, "Sorter_UserTown", $FileName, $this);
        $this->Sorter_postcode = new clsSorter($this->ComponentName, "Sorter_postcode", $FileName, $this);
        $this->Sorter_State = new clsSorter($this->ComponentName, "Sorter_State", $FileName, $this);
        $this->Sorter_Country = new clsSorter($this->ComponentName, "Sorter_Country", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Sorter_UserRole = new clsSorter($this->ComponentName, "Sorter_UserRole", $FileName, $this);
        $this->Sorter_UserState = new clsSorter($this->ComponentName, "Sorter_UserState", $FileName, $this);
        $this->Sorter_UserCountry = new clsSorter($this->ComponentName, "Sorter_UserCountry", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @9-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @9-3054DD48
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_UserId"] = CCGetFromGet("s_UserId", NULL);
        $this->DataSource->Parameters["urls_UserFullName"] = CCGetFromGet("s_UserFullName", NULL);
        $this->DataSource->Parameters["urls_UserEmail"] = CCGetFromGet("s_UserEmail", NULL);
        $this->DataSource->Parameters["urls_UserTelephone"] = CCGetFromGet("s_UserTelephone", NULL);
        $this->DataSource->Parameters["urls_postcode"] = CCGetFromGet("s_postcode", NULL);
        $this->DataSource->Parameters["urls_UserRole"] = CCGetFromGet("s_UserRole", NULL);
        $this->DataSource->Parameters["urls_UserState"] = CCGetFromGet("s_UserState", NULL);
        $this->DataSource->Parameters["urls_UserCountry"] = CCGetFromGet("s_UserCountry", NULL);
        $this->DataSource->Parameters["urls_IsActive"] = CCGetFromGet("s_IsActive", NULL);

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
            $this->ControlsVisible["Role"] = $this->Role->Visible;
            $this->ControlsVisible["UserEmail"] = $this->UserEmail->Visible;
            $this->ControlsVisible["UserTelephone"] = $this->UserTelephone->Visible;
            $this->ControlsVisible["UserAddress1"] = $this->UserAddress1->Visible;
            $this->ControlsVisible["UserAddress2"] = $this->UserAddress2->Visible;
            $this->ControlsVisible["UserAddress3"] = $this->UserAddress3->Visible;
            $this->ControlsVisible["UserTown"] = $this->UserTown->Visible;
            $this->ControlsVisible["postcode"] = $this->postcode->Visible;
            $this->ControlsVisible["State"] = $this->State->Visible;
            $this->ControlsVisible["Country"] = $this->Country->Visible;
            $this->ControlsVisible["IsActive"] = $this->IsActive->Visible;
            $this->ControlsVisible["UserRole"] = $this->UserRole->Visible;
            $this->ControlsVisible["UserState"] = $this->UserState->Visible;
            $this->ControlsVisible["UserCountry"] = $this->UserCountry->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->Id->SetValue($this->DataSource->Id->GetValue());
                $this->Id->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Id->Parameters = CCAddParam($this->Id->Parameters, "Id", $this->DataSource->f("Id"));
                $this->UserId->SetValue($this->DataSource->UserId->GetValue());
                $this->UserFullName->SetValue($this->DataSource->UserFullName->GetValue());
                $this->Role->SetValue($this->DataSource->Role->GetValue());
                $this->UserEmail->SetValue($this->DataSource->UserEmail->GetValue());
                $this->UserTelephone->SetValue($this->DataSource->UserTelephone->GetValue());
                $this->UserAddress1->SetValue($this->DataSource->UserAddress1->GetValue());
                $this->UserAddress2->SetValue($this->DataSource->UserAddress2->GetValue());
                $this->UserAddress3->SetValue($this->DataSource->UserAddress3->GetValue());
                $this->UserTown->SetValue($this->DataSource->UserTown->GetValue());
                $this->postcode->SetValue($this->DataSource->postcode->GetValue());
                $this->State->SetValue($this->DataSource->State->GetValue());
                $this->Country->SetValue($this->DataSource->Country->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->UserRole->SetValue($this->DataSource->UserRole->GetValue());
                $this->UserState->SetValue($this->DataSource->UserState->GetValue());
                $this->UserCountry->SetValue($this->DataSource->UserCountry->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->UserId->Show();
                $this->UserFullName->Show();
                $this->Role->Show();
                $this->UserEmail->Show();
                $this->UserTelephone->Show();
                $this->UserAddress1->Show();
                $this->UserAddress2->Show();
                $this->UserAddress3->Show();
                $this->UserTown->Show();
                $this->postcode->Show();
                $this->State->Show();
                $this->Country->Show();
                $this->IsActive->Show();
                $this->UserRole->Show();
                $this->UserState->Show();
                $this->UserCountry->Show();
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
        $this->reportregistrations_Insert->Show();
        $this->reportregistrations_TotalRecords->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_UserId->Show();
        $this->Sorter_UserFullName->Show();
        $this->Sorter_Role->Show();
        $this->Sorter_UserEmail->Show();
        $this->Sorter_UserTelephone->Show();
        $this->Sorter_UserAddress1->Show();
        $this->Sorter_UserAddress2->Show();
        $this->Sorter_UserAddress3->Show();
        $this->Sorter_UserTown->Show();
        $this->Sorter_postcode->Show();
        $this->Sorter_State->Show();
        $this->Sorter_Country->Show();
        $this->Sorter_IsActive->Show();
        $this->Sorter_UserRole->Show();
        $this->Sorter_UserState->Show();
        $this->Sorter_UserCountry->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @9-7DBF2E3E
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserFullName->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Role->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserEmail->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserTelephone->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserAddress1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserAddress2->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserAddress3->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserTown->Errors->ToString());
        $errors = ComposeStrings($errors, $this->postcode->Errors->ToString());
        $errors = ComposeStrings($errors, $this->State->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Country->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserRole->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserState->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserCountry->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End reportregistrations Class @9-FCB6E20C

class clsreportregistrationsDataSource extends clsDBFuelSaver {  //reportregistrationsDataSource Class @9-3C7D5D72

//DataSource Variables @9-9E7A8C4F
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
    public $Role;
    public $UserEmail;
    public $UserTelephone;
    public $UserAddress1;
    public $UserAddress2;
    public $UserAddress3;
    public $UserTown;
    public $postcode;
    public $State;
    public $Country;
    public $IsActive;
    public $UserRole;
    public $UserState;
    public $UserCountry;
//End DataSource Variables

//DataSourceClass_Initialize Event @9-F516B31D
    function clsreportregistrationsDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid reportregistrations";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->UserId = new clsField("UserId", ccsText, "");
        
        $this->UserFullName = new clsField("UserFullName", ccsText, "");
        
        $this->Role = new clsField("Role", ccsText, "");
        
        $this->UserEmail = new clsField("UserEmail", ccsText, "");
        
        $this->UserTelephone = new clsField("UserTelephone", ccsText, "");
        
        $this->UserAddress1 = new clsField("UserAddress1", ccsText, "");
        
        $this->UserAddress2 = new clsField("UserAddress2", ccsText, "");
        
        $this->UserAddress3 = new clsField("UserAddress3", ccsText, "");
        
        $this->UserTown = new clsField("UserTown", ccsText, "");
        
        $this->postcode = new clsField("postcode", ccsInteger, "");
        
        $this->State = new clsField("State", ccsText, "");
        
        $this->Country = new clsField("Country", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        
        $this->UserRole = new clsField("UserRole", ccsInteger, "");
        
        $this->UserState = new clsField("UserState", ccsInteger, "");
        
        $this->UserCountry = new clsField("UserCountry", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @9-06557A59
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "UserId";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("Id", ""), 
            "Sorter_UserId" => array("UserId", ""), 
            "Sorter_UserFullName" => array("UserFullName", ""), 
            "Sorter_Role" => array("Role", ""), 
            "Sorter_UserEmail" => array("UserEmail", ""), 
            "Sorter_UserTelephone" => array("UserTelephone", ""), 
            "Sorter_UserAddress1" => array("UserAddress1", ""), 
            "Sorter_UserAddress2" => array("UserAddress2", ""), 
            "Sorter_UserAddress3" => array("UserAddress3", ""), 
            "Sorter_UserTown" => array("UserTown", ""), 
            "Sorter_postcode" => array("postcode", ""), 
            "Sorter_State" => array("State", ""), 
            "Sorter_Country" => array("Country", ""), 
            "Sorter_IsActive" => array("IsActive", ""), 
            "Sorter_UserRole" => array("UserRole", ""), 
            "Sorter_UserState" => array("UserState", ""), 
            "Sorter_UserCountry" => array("UserCountry", "")));
    }
//End SetOrder Method

//Prepare Method @9-6C9B5432
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_UserId", ccsText, "", "", $this->Parameters["urls_UserId"], "", false);
        $this->wp->AddParameter("2", "urls_UserFullName", ccsText, "", "", $this->Parameters["urls_UserFullName"], "", false);
        $this->wp->AddParameter("3", "urls_UserEmail", ccsText, "", "", $this->Parameters["urls_UserEmail"], "", false);
        $this->wp->AddParameter("4", "urls_UserTelephone", ccsText, "", "", $this->Parameters["urls_UserTelephone"], "", false);
        $this->wp->AddParameter("5", "urls_postcode", ccsInteger, "", "", $this->Parameters["urls_postcode"], "", false);
        $this->wp->AddParameter("6", "urls_UserRole", ccsInteger, "", "", $this->Parameters["urls_UserRole"], "", false);
        $this->wp->AddParameter("7", "urls_UserState", ccsInteger, "", "", $this->Parameters["urls_UserState"], "", false);
        $this->wp->AddParameter("8", "urls_UserCountry", ccsInteger, "", "", $this->Parameters["urls_UserCountry"], "", false);
        $this->wp->AddParameter("9", "urls_IsActive", ccsInteger, "", "", $this->Parameters["urls_IsActive"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "UserId", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "UserFullName", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "UserEmail", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "UserTelephone", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "postcode", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsInteger),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "UserRole", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsInteger),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opEqual, "UserState", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsInteger),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opEqual, "UserCountry", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsInteger),false);
        $this->wp->Criterion[9] = $this->wp->Operation(opEqual, "IsActive", $this->wp->GetDBValue("9"), $this->ToSQL($this->wp->GetDBValue("9"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
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
             $this->wp->Criterion[9]);
    }
//End Prepare Method

//Open Method @9-B7B97FE6
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM reportregistrations";
        $this->SQL = "SELECT Id, UserId, UserFullName, Role, UserEmail, UserTelephone, UserAddress1, UserAddress2, UserAddress3, UserTown, postcode, State,\n\n" .
        "Country, IsActive, UserRole, UserState, UserCountry \n\n" .
        "FROM reportregistrations {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @9-A35C78A6
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->UserId->SetDBValue($this->f("UserId"));
        $this->UserFullName->SetDBValue($this->f("UserFullName"));
        $this->Role->SetDBValue($this->f("Role"));
        $this->UserEmail->SetDBValue($this->f("UserEmail"));
        $this->UserTelephone->SetDBValue($this->f("UserTelephone"));
        $this->UserAddress1->SetDBValue($this->f("UserAddress1"));
        $this->UserAddress2->SetDBValue($this->f("UserAddress2"));
        $this->UserAddress3->SetDBValue($this->f("UserAddress3"));
        $this->UserTown->SetDBValue($this->f("UserTown"));
        $this->postcode->SetDBValue(trim($this->f("postcode")));
        $this->State->SetDBValue($this->f("State"));
        $this->Country->SetDBValue($this->f("Country"));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
        $this->UserRole->SetDBValue(trim($this->f("UserRole")));
        $this->UserState->SetDBValue(trim($this->f("UserState")));
        $this->UserCountry->SetDBValue(trim($this->f("UserCountry")));
    }
//End SetValues Method

} //End reportregistrationsDataSource Class @9-FCB6E20C

class clsRecordreportregistrationsSearch { //reportregistrationsSearch Class @86-D36C2DEB

//Variables @86-9E315808

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

//Class_Initialize Event @86-1806643A
    function clsRecordreportregistrationsSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record reportregistrationsSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "reportregistrationsSearch";
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
            $this->s_UserEmail = new clsControl(ccsTextBox, "s_UserEmail", "User Email", ccsText, "", CCGetRequestParam("s_UserEmail", $Method, NULL), $this);
            $this->s_UserTelephone = new clsControl(ccsTextBox, "s_UserTelephone", "User Telephone", ccsText, "", CCGetRequestParam("s_UserTelephone", $Method, NULL), $this);
            $this->s_postcode = new clsControl(ccsTextBox, "s_postcode", "Postcode", ccsInteger, "", CCGetRequestParam("s_postcode", $Method, NULL), $this);
            $this->s_UserRole = new clsControl(ccsListBox, "s_UserRole", "User Role", ccsInteger, "", CCGetRequestParam("s_UserRole", $Method, NULL), $this);
            $this->s_UserRole->DSType = dsTable;
            $this->s_UserRole->DataSource = new clsDBFuelSaver();
            $this->s_UserRole->ds = & $this->s_UserRole->DataSource;
            $this->s_UserRole->DataSource->SQL = "SELECT * \n" .
"FROM userroles {SQL_Where} {SQL_OrderBy}";
            list($this->s_UserRole->BoundColumn, $this->s_UserRole->TextColumn, $this->s_UserRole->DBFormat) = array("Id", "UserRole", "");
            $this->s_UserState = new clsControl(ccsListBox, "s_UserState", "User State", ccsInteger, "", CCGetRequestParam("s_UserState", $Method, NULL), $this);
            $this->s_UserState->DSType = dsTable;
            $this->s_UserState->DataSource = new clsDBFuelSaver();
            $this->s_UserState->ds = & $this->s_UserState->DataSource;
            $this->s_UserState->DataSource->SQL = "SELECT * \n" .
"FROM states {SQL_Where} {SQL_OrderBy}";
            list($this->s_UserState->BoundColumn, $this->s_UserState->TextColumn, $this->s_UserState->DBFormat) = array("Id", "State", "");
            $this->s_UserCountry = new clsControl(ccsListBox, "s_UserCountry", "User Country", ccsInteger, "", CCGetRequestParam("s_UserCountry", $Method, NULL), $this);
            $this->s_UserCountry->DSType = dsTable;
            $this->s_UserCountry->DataSource = new clsDBFuelSaver();
            $this->s_UserCountry->ds = & $this->s_UserCountry->DataSource;
            $this->s_UserCountry->DataSource->SQL = "SELECT * \n" .
"FROM countries {SQL_Where} {SQL_OrderBy}";
            list($this->s_UserCountry->BoundColumn, $this->s_UserCountry->TextColumn, $this->s_UserCountry->DBFormat) = array("Id", "Country", "");
            $this->s_IsActive = new clsControl(ccsCheckBox, "s_IsActive", "s_IsActive", ccsInteger, "", CCGetRequestParam("s_IsActive", $Method, NULL), $this);
            $this->s_IsActive->CheckedValue = $this->s_IsActive->GetParsedValue(1);
            $this->s_IsActive->UncheckedValue = $this->s_IsActive->GetParsedValue(0);
            $this->reportregistrationsPageSize = new clsControl(ccsListBox, "reportregistrationsPageSize", "reportregistrationsPageSize", ccsText, "", CCGetRequestParam("reportregistrationsPageSize", $Method, NULL), $this);
            $this->reportregistrationsPageSize->DSType = dsListOfValues;
            $this->reportregistrationsPageSize->Values = array(array("", "Select Value"), array("5", "5"), array("10", "10"), array("25", "25"), array("100", "100"));
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_IsActive->Value) && !strlen($this->s_IsActive->Value) && $this->s_IsActive->Value !== false)
                    $this->s_IsActive->SetValue(false);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @86-697E9E64
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_UserId->Validate() && $Validation);
        $Validation = ($this->s_UserFullName->Validate() && $Validation);
        $Validation = ($this->s_UserEmail->Validate() && $Validation);
        $Validation = ($this->s_UserTelephone->Validate() && $Validation);
        $Validation = ($this->s_postcode->Validate() && $Validation);
        $Validation = ($this->s_UserRole->Validate() && $Validation);
        $Validation = ($this->s_UserState->Validate() && $Validation);
        $Validation = ($this->s_UserCountry->Validate() && $Validation);
        $Validation = ($this->s_IsActive->Validate() && $Validation);
        $Validation = ($this->reportregistrationsPageSize->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_UserId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserFullName->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserEmail->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserTelephone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_postcode->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserRole->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserState->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_UserCountry->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_IsActive->Errors->Count() == 0);
        $Validation =  $Validation && ($this->reportregistrationsPageSize->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @86-5E439051
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_UserId->Errors->Count());
        $errors = ($errors || $this->s_UserFullName->Errors->Count());
        $errors = ($errors || $this->s_UserEmail->Errors->Count());
        $errors = ($errors || $this->s_UserTelephone->Errors->Count());
        $errors = ($errors || $this->s_postcode->Errors->Count());
        $errors = ($errors || $this->s_UserRole->Errors->Count());
        $errors = ($errors || $this->s_UserState->Errors->Count());
        $errors = ($errors || $this->s_UserCountry->Errors->Count());
        $errors = ($errors || $this->s_IsActive->Errors->Count());
        $errors = ($errors || $this->reportregistrationsPageSize->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @86-51916287
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
        $Redirect = "reportregistration.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "reportregistration.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @86-20C2BD51
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
        $this->s_UserCountry->Prepare();
        $this->reportregistrationsPageSize->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_UserId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserFullName->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserEmail->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserTelephone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_postcode->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserRole->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserState->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_UserCountry->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_IsActive->Errors->ToString());
            $Error = ComposeStrings($Error, $this->reportregistrationsPageSize->Errors->ToString());
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
        $this->s_UserEmail->Show();
        $this->s_UserTelephone->Show();
        $this->s_postcode->Show();
        $this->s_UserRole->Show();
        $this->s_UserState->Show();
        $this->s_UserCountry->Show();
        $this->s_IsActive->Show();
        $this->reportregistrationsPageSize->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End reportregistrationsSearch Class @86-FCB6E20C



class clsRecordusers { //users Class @102-9BE1AF6F

//Variables @102-9E315808

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

//Class_Initialize Event @102-32615BFC
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
            $this->Button_Cancel = new clsButton("Button_Cancel", $Method, $this);
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
            $this->postcode = new clsControl(ccsTextBox, "postcode", "Postcode", ccsInteger, "", CCGetRequestParam("postcode", $Method, NULL), $this);
            $this->UserState = new clsControl(ccsListBox, "UserState", "User State", ccsInteger, "", CCGetRequestParam("UserState", $Method, NULL), $this);
            $this->UserState->DSType = dsTable;
            $this->UserState->DataSource = new clsDBFuelSaver();
            $this->UserState->ds = & $this->UserState->DataSource;
            $this->UserState->DataSource->SQL = "SELECT * \n" .
"FROM states {SQL_Where} {SQL_OrderBy}";
            list($this->UserState->BoundColumn, $this->UserState->TextColumn, $this->UserState->DBFormat) = array("Id", "State", "");
            $this->UserCountry = new clsControl(ccsListBox, "UserCountry", "User Country", ccsInteger, "", CCGetRequestParam("UserCountry", $Method, NULL), $this);
            $this->UserCountry->DSType = dsTable;
            $this->UserCountry->DataSource = new clsDBFuelSaver();
            $this->UserCountry->ds = & $this->UserCountry->DataSource;
            $this->UserCountry->DataSource->SQL = "SELECT * \n" .
"FROM countries {SQL_Where} {SQL_OrderBy}";
            list($this->UserCountry->BoundColumn, $this->UserCountry->TextColumn, $this->UserCountry->DBFormat) = array("Id", "Country", "");
            $this->IsActive = new clsControl(ccsCheckBox, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", $Method, NULL), $this);
            $this->IsActive->CheckedValue = $this->IsActive->GetParsedValue(1);
            $this->IsActive->UncheckedValue = $this->IsActive->GetParsedValue(0);
            if(!$this->FormSubmitted) {
                if(!is_array($this->IsActive->Value) && !strlen($this->IsActive->Value) && $this->IsActive->Value !== false)
                    $this->IsActive->SetValue(false);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @102-4F76030F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlId"] = CCGetFromGet("Id", NULL);
    }
//End Initialize Method

//Validate Method @102-22908764
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->DataSource->Where))
            $Where = " AND NOT (" . $this->DataSource->Where . ")";
        $this->DataSource->UserId->SetValue($this->UserId->GetValue());
        if(CCDLookUp("COUNT(*)", "users", "UserId=" . $this->DataSource->ToSQL($this->DataSource->UserId->GetDBValue(), $this->DataSource->UserId->DataType) . $Where, $this->DataSource) > 0)
            $this->UserId->Errors->addError($CCSLocales->GetText("CCS_UniqueValue", "User Id"));
        if(strlen($this->UserEmail->GetText()) && !preg_match ("/^[\w\.-]{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]+$/", $this->UserEmail->GetText())) {
            $this->UserEmail->Errors->addError($CCSLocales->GetText("CCS_MaskValidation", "User Email"));
        }
        if(strlen($this->postcode->GetText()) && !preg_match ("/^\d{5}$/", $this->postcode->GetText())) {
            $this->postcode->Errors->addError($CCSLocales->GetText("CCS_MaskValidation", "Postcode"));
        }
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
        $Validation = ($this->postcode->Validate() && $Validation);
        $Validation = ($this->UserState->Validate() && $Validation);
        $Validation = ($this->UserCountry->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
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
        $Validation =  $Validation && ($this->postcode->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserState->Errors->Count() == 0);
        $Validation =  $Validation && ($this->UserCountry->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @102-AF202E36
    function CheckErrors()
    {
        $errors = false;
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
        $errors = ($errors || $this->postcode->Errors->Count());
        $errors = ($errors || $this->UserState->Errors->Count());
        $errors = ($errors || $this->UserCountry->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @102-288F0419
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
            } else if($this->Button_Cancel->Pressed) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
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

//InsertRow Method @102-F56D3AFC
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
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
        $this->DataSource->postcode->SetValue($this->postcode->GetValue(true));
        $this->DataSource->UserState->SetValue($this->UserState->GetValue(true));
        $this->DataSource->UserCountry->SetValue($this->UserCountry->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @102-5B28274B
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
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
        $this->DataSource->postcode->SetValue($this->postcode->GetValue(true));
        $this->DataSource->UserState->SetValue($this->UserState->GetValue(true));
        $this->DataSource->UserCountry->SetValue($this->UserCountry->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @102-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @102-3639548D
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
        $this->UserCountry->Prepare();

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
                    $this->postcode->SetValue($this->DataSource->postcode->GetValue());
                    $this->UserState->SetValue($this->DataSource->UserState->GetValue());
                    $this->UserCountry->SetValue($this->DataSource->UserCountry->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
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
            $Error = ComposeStrings($Error, $this->postcode->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserState->Errors->ToString());
            $Error = ComposeStrings($Error, $this->UserCountry->Errors->ToString());
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
        $this->Button_Cancel->Show();
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
        $this->postcode->Show();
        $this->UserState->Show();
        $this->UserCountry->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End users Class @102-FCB6E20C

class clsusersDataSource extends clsDBFuelSaver {  //usersDataSource Class @102-032B5816

//DataSource Variables @102-3D1C71AA
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
    public $postcode;
    public $UserState;
    public $UserCountry;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @102-9E60AFFB
    function clsusersDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record users/Error";
        $this->Initialize();
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
        
        $this->postcode = new clsField("postcode", ccsInteger, "");
        
        $this->UserState = new clsField("UserState", ccsInteger, "");
        
        $this->UserCountry = new clsField("UserCountry", ccsInteger, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

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
        $this->InsertFields["postcode"] = array("Name" => "postcode", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["UserState"] = array("Name" => "UserState", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["UserCountry"] = array("Name" => "UserCountry", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
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
        $this->UpdateFields["postcode"] = array("Name" => "postcode", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserState"] = array("Name" => "UserState", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["UserCountry"] = array("Name" => "UserCountry", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @102-F755E9A7
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

//Open Method @102-B071412E
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

//SetValues Method @102-EE32E1B7
    function SetValues()
    {
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
        $this->postcode->SetDBValue(trim($this->f("postcode")));
        $this->UserState->SetDBValue(trim($this->f("UserState")));
        $this->UserCountry->SetDBValue(trim($this->f("UserCountry")));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @102-8ECBC472
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
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
        $this->InsertFields["postcode"]["Value"] = $this->postcode->GetDBValue(true);
        $this->InsertFields["UserState"]["Value"] = $this->UserState->GetDBValue(true);
        $this->InsertFields["UserCountry"]["Value"] = $this->UserCountry->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("users", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @102-90EB72DF
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
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
        $this->UpdateFields["postcode"]["Value"] = $this->postcode->GetDBValue(true);
        $this->UpdateFields["UserState"]["Value"] = $this->UserState->GetDBValue(true);
        $this->UpdateFields["UserCountry"]["Value"] = $this->UserCountry->GetDBValue(true);
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

//Delete Method @102-4AB027F1
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

} //End usersDataSource Class @102-FCB6E20C

//Initialize Page @1-805EEBF7
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
$TemplateFileName = "reportregistration.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.position.js|js/jquery/ui/jquery.ui.menu.js|js/jquery/ui/jquery.ui.autocomplete.js|js/jquery/autocomplete/ccs-autocomplete.js|js/jquery/ui/jquery.ui.mouse.js|js/jquery/ui/jquery.ui.draggable.js|js/jquery/ui/jquery.ui.resizable.js|js/jquery/ui/jquery.ui.button.js|js/jquery/ui/jquery.ui.dialog.js|js/jquery/dialog/ccs-dialog.js|js/jquery/updatepanel/ccs-update-panel.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-DCAA8590
include_once("./reportregistration_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-31974374
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
$Content->GenerateDiv = true;
$Content->PanelId = "Content";
$Content->PlaceholderName = "Content";
$reportregistrations = new clsGridreportregistrations("", $MainPage);
$reportregistrationsSearch = new clsRecordreportregistrationsSearch("", $MainPage);
$Panel2 = new clsPanel("Panel2", $MainPage);
$Panel2->GenerateDiv = true;
$Panel2->PanelId = "ContentPanel2";
$users = new clsRecordusers("", $MainPage);
$MainPage->Head = & $Head;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$MainPage->Content = & $Content;
$MainPage->reportregistrations = & $reportregistrations;
$MainPage->reportregistrationsSearch = & $reportregistrationsSearch;
$MainPage->Panel2 = & $Panel2;
$MainPage->users = & $users;
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$Content->AddComponent("reportregistrations", $reportregistrations);
$Content->AddComponent("reportregistrationsSearch", $reportregistrationsSearch);
$Content->AddComponent("Panel2", $Panel2);
$Panel2->AddComponent("users", $users);
$reportregistrations->Initialize();
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

//Execute Components @1-B37F6DF8
$MasterPage->Operations();
$users->Operation();
$reportregistrationsSearch->Operation();
$MenuIncludablePage->Operations();
$headerIncludablePage->Operations();
//End Execute Components

//Go to destination page @1-40FEAF44
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    $headerIncludablePage->Class_Terminate();
    unset($headerIncludablePage);
    $MenuIncludablePage->Class_Terminate();
    unset($MenuIncludablePage);
    unset($reportregistrations);
    unset($reportregistrationsSearch);
    unset($users);
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

//Unload Page @1-84CFF45A
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($reportregistrations);
unset($reportregistrationsSearch);
unset($users);
unset($Tpl);
//End Unload Page


?>
