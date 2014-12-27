<?php
//Include Common Files @1-9FC6F5C5
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "reportsales.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridreportsales1 { //reportsales1 class @9-5E2F608E

//Variables @9-F60760D7

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
    public $Sorter_SalesDate;
    public $Sorter_UserId;
    public $Sorter_Product;
    public $Sorter_SalesQuantity;
    public $Sorter_SalesPrice;
    public $Sorter_SalesValue;
    public $Sorter_email;
    public $Sorter_telephone;
    public $Sorter_Town;
    public $Sorter_State;
    public $Sorter_Country;
    public $Sorter_SalesOffice;
    public $Sorter_IsActive;
    public $Sorter_SalesUser;
    public $Sorter_SalesItem;
    public $Sorter_StateId;
    public $Sorter_CountryId;
    public $Sorter_SalesOfficeId;
//End Variables

//Class_Initialize Event @9-270FF71F
    function clsGridreportsales1($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "reportsales1";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid reportsales1";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsreportsales1DataSource($this);
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
        $this->SorterName = CCGetParam("reportsales1Order", "");
        $this->SorterDirection = CCGetParam("reportsales1Dir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "";
        $this->SalesDate = new clsControl(ccsLabel, "SalesDate", "SalesDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("SalesDate", ccsGet, NULL), $this);
        $this->UserId = new clsControl(ccsLabel, "UserId", "UserId", ccsInteger, "", CCGetRequestParam("UserId", ccsGet, NULL), $this);
        $this->Product = new clsControl(ccsLabel, "Product", "Product", ccsText, "", CCGetRequestParam("Product", ccsGet, NULL), $this);
        $this->SalesQuantity = new clsControl(ccsLabel, "SalesQuantity", "SalesQuantity", ccsSingle, "", CCGetRequestParam("SalesQuantity", ccsGet, NULL), $this);
        $this->SalesPrice = new clsControl(ccsLabel, "SalesPrice", "SalesPrice", ccsSingle, "", CCGetRequestParam("SalesPrice", ccsGet, NULL), $this);
        $this->SalesValue = new clsControl(ccsLabel, "SalesValue", "SalesValue", ccsSingle, "", CCGetRequestParam("SalesValue", ccsGet, NULL), $this);
        $this->email = new clsControl(ccsLabel, "email", "email", ccsText, "", CCGetRequestParam("email", ccsGet, NULL), $this);
        $this->telephone = new clsControl(ccsLabel, "telephone", "telephone", ccsText, "", CCGetRequestParam("telephone", ccsGet, NULL), $this);
        $this->Town = new clsControl(ccsLabel, "Town", "Town", ccsText, "", CCGetRequestParam("Town", ccsGet, NULL), $this);
        $this->State = new clsControl(ccsLabel, "State", "State", ccsText, "", CCGetRequestParam("State", ccsGet, NULL), $this);
        $this->Country = new clsControl(ccsLabel, "Country", "Country", ccsText, "", CCGetRequestParam("Country", ccsGet, NULL), $this);
        $this->SalesOffice = new clsControl(ccsLabel, "SalesOffice", "SalesOffice", ccsText, "", CCGetRequestParam("SalesOffice", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->SalesUser = new clsControl(ccsLabel, "SalesUser", "SalesUser", ccsInteger, "", CCGetRequestParam("SalesUser", ccsGet, NULL), $this);
        $this->SalesItem = new clsControl(ccsLabel, "SalesItem", "SalesItem", ccsInteger, "", CCGetRequestParam("SalesItem", ccsGet, NULL), $this);
        $this->StateId = new clsControl(ccsLabel, "StateId", "StateId", ccsInteger, "", CCGetRequestParam("StateId", ccsGet, NULL), $this);
        $this->CountryId = new clsControl(ccsLabel, "CountryId", "CountryId", ccsInteger, "", CCGetRequestParam("CountryId", ccsGet, NULL), $this);
        $this->SalesOfficeId = new clsControl(ccsLabel, "SalesOfficeId", "SalesOfficeId", ccsInteger, "", CCGetRequestParam("SalesOfficeId", ccsGet, NULL), $this);
        $this->reportsales1_Insert = new clsControl(ccsLink, "reportsales1_Insert", "reportsales1_Insert", ccsText, "", CCGetRequestParam("reportsales1_Insert", ccsGet, NULL), $this);
        $this->reportsales1_Insert->Parameters = CCGetQueryString("QueryString", array("sales.Id", "ccsForm"));
        $this->reportsales1_Insert->Page = "reportsales.php";
        $this->reportsales1_TotalRecords = new clsControl(ccsLabel, "reportsales1_TotalRecords", "reportsales1_TotalRecords", ccsText, "", CCGetRequestParam("reportsales1_TotalRecords", ccsGet, NULL), $this);
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_SalesDate = new clsSorter($this->ComponentName, "Sorter_SalesDate", $FileName, $this);
        $this->Sorter_UserId = new clsSorter($this->ComponentName, "Sorter_UserId", $FileName, $this);
        $this->Sorter_Product = new clsSorter($this->ComponentName, "Sorter_Product", $FileName, $this);
        $this->Sorter_SalesQuantity = new clsSorter($this->ComponentName, "Sorter_SalesQuantity", $FileName, $this);
        $this->Sorter_SalesPrice = new clsSorter($this->ComponentName, "Sorter_SalesPrice", $FileName, $this);
        $this->Sorter_SalesValue = new clsSorter($this->ComponentName, "Sorter_SalesValue", $FileName, $this);
        $this->Sorter_email = new clsSorter($this->ComponentName, "Sorter_email", $FileName, $this);
        $this->Sorter_telephone = new clsSorter($this->ComponentName, "Sorter_telephone", $FileName, $this);
        $this->Sorter_Town = new clsSorter($this->ComponentName, "Sorter_Town", $FileName, $this);
        $this->Sorter_State = new clsSorter($this->ComponentName, "Sorter_State", $FileName, $this);
        $this->Sorter_Country = new clsSorter($this->ComponentName, "Sorter_Country", $FileName, $this);
        $this->Sorter_SalesOffice = new clsSorter($this->ComponentName, "Sorter_SalesOffice", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Sorter_SalesUser = new clsSorter($this->ComponentName, "Sorter_SalesUser", $FileName, $this);
        $this->Sorter_SalesItem = new clsSorter($this->ComponentName, "Sorter_SalesItem", $FileName, $this);
        $this->Sorter_StateId = new clsSorter($this->ComponentName, "Sorter_StateId", $FileName, $this);
        $this->Sorter_CountryId = new clsSorter($this->ComponentName, "Sorter_CountryId", $FileName, $this);
        $this->Sorter_SalesOfficeId = new clsSorter($this->ComponentName, "Sorter_SalesOfficeId", $FileName, $this);
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

//Show Method @9-2EC8A041
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_SalesDate"] = CCGetFromGet("s_SalesDate", NULL);
        $this->DataSource->Parameters["urls_SalesUser"] = CCGetFromGet("s_SalesUser", NULL);
        $this->DataSource->Parameters["urls_SalesItem"] = CCGetFromGet("s_SalesItem", NULL);
        $this->DataSource->Parameters["urls_Town"] = CCGetFromGet("s_Town", NULL);
        $this->DataSource->Parameters["urls_StateId"] = CCGetFromGet("s_StateId", NULL);
        $this->DataSource->Parameters["urls_CountryId"] = CCGetFromGet("s_CountryId", NULL);

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
            $this->ControlsVisible["SalesDate"] = $this->SalesDate->Visible;
            $this->ControlsVisible["UserId"] = $this->UserId->Visible;
            $this->ControlsVisible["Product"] = $this->Product->Visible;
            $this->ControlsVisible["SalesQuantity"] = $this->SalesQuantity->Visible;
            $this->ControlsVisible["SalesPrice"] = $this->SalesPrice->Visible;
            $this->ControlsVisible["SalesValue"] = $this->SalesValue->Visible;
            $this->ControlsVisible["email"] = $this->email->Visible;
            $this->ControlsVisible["telephone"] = $this->telephone->Visible;
            $this->ControlsVisible["Town"] = $this->Town->Visible;
            $this->ControlsVisible["State"] = $this->State->Visible;
            $this->ControlsVisible["Country"] = $this->Country->Visible;
            $this->ControlsVisible["SalesOffice"] = $this->SalesOffice->Visible;
            $this->ControlsVisible["IsActive"] = $this->IsActive->Visible;
            $this->ControlsVisible["SalesUser"] = $this->SalesUser->Visible;
            $this->ControlsVisible["SalesItem"] = $this->SalesItem->Visible;
            $this->ControlsVisible["StateId"] = $this->StateId->Visible;
            $this->ControlsVisible["CountryId"] = $this->CountryId->Visible;
            $this->ControlsVisible["SalesOfficeId"] = $this->SalesOfficeId->Visible;
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
                $this->SalesDate->SetValue($this->DataSource->SalesDate->GetValue());
                $this->UserId->SetValue($this->DataSource->UserId->GetValue());
                $this->Product->SetValue($this->DataSource->Product->GetValue());
                $this->SalesQuantity->SetValue($this->DataSource->SalesQuantity->GetValue());
                $this->SalesPrice->SetValue($this->DataSource->SalesPrice->GetValue());
                $this->SalesValue->SetValue($this->DataSource->SalesValue->GetValue());
                $this->email->SetValue($this->DataSource->email->GetValue());
                $this->telephone->SetValue($this->DataSource->telephone->GetValue());
                $this->Town->SetValue($this->DataSource->Town->GetValue());
                $this->State->SetValue($this->DataSource->State->GetValue());
                $this->Country->SetValue($this->DataSource->Country->GetValue());
                $this->SalesOffice->SetValue($this->DataSource->SalesOffice->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->SalesUser->SetValue($this->DataSource->SalesUser->GetValue());
                $this->SalesItem->SetValue($this->DataSource->SalesItem->GetValue());
                $this->StateId->SetValue($this->DataSource->StateId->GetValue());
                $this->CountryId->SetValue($this->DataSource->CountryId->GetValue());
                $this->SalesOfficeId->SetValue($this->DataSource->SalesOfficeId->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->SalesDate->Show();
                $this->UserId->Show();
                $this->Product->Show();
                $this->SalesQuantity->Show();
                $this->SalesPrice->Show();
                $this->SalesValue->Show();
                $this->email->Show();
                $this->telephone->Show();
                $this->Town->Show();
                $this->State->Show();
                $this->Country->Show();
                $this->SalesOffice->Show();
                $this->IsActive->Show();
                $this->SalesUser->Show();
                $this->SalesItem->Show();
                $this->StateId->Show();
                $this->CountryId->Show();
                $this->SalesOfficeId->Show();
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
        $this->reportsales1_Insert->Show();
        $this->reportsales1_TotalRecords->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_SalesDate->Show();
        $this->Sorter_UserId->Show();
        $this->Sorter_Product->Show();
        $this->Sorter_SalesQuantity->Show();
        $this->Sorter_SalesPrice->Show();
        $this->Sorter_SalesValue->Show();
        $this->Sorter_email->Show();
        $this->Sorter_telephone->Show();
        $this->Sorter_Town->Show();
        $this->Sorter_State->Show();
        $this->Sorter_Country->Show();
        $this->Sorter_SalesOffice->Show();
        $this->Sorter_IsActive->Show();
        $this->Sorter_SalesUser->Show();
        $this->Sorter_SalesItem->Show();
        $this->Sorter_StateId->Show();
        $this->Sorter_CountryId->Show();
        $this->Sorter_SalesOfficeId->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @9-42BBDD57
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Product->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesQuantity->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesPrice->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesValue->Errors->ToString());
        $errors = ComposeStrings($errors, $this->email->Errors->ToString());
        $errors = ComposeStrings($errors, $this->telephone->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Town->Errors->ToString());
        $errors = ComposeStrings($errors, $this->State->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Country->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesOffice->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesUser->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesItem->Errors->ToString());
        $errors = ComposeStrings($errors, $this->StateId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->CountryId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesOfficeId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End reportsales1 Class @9-FCB6E20C

class clsreportsales1DataSource extends clsDBFuelSaver {  //reportsales1DataSource Class @9-76965614

//DataSource Variables @9-FFEFB8E4
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $SalesDate;
    public $UserId;
    public $Product;
    public $SalesQuantity;
    public $SalesPrice;
    public $SalesValue;
    public $email;
    public $telephone;
    public $Town;
    public $State;
    public $Country;
    public $SalesOffice;
    public $IsActive;
    public $SalesUser;
    public $SalesItem;
    public $StateId;
    public $CountryId;
    public $SalesOfficeId;
//End DataSource Variables

//DataSourceClass_Initialize Event @9-5F9A9B72
    function clsreportsales1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid reportsales1";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->SalesDate = new clsField("SalesDate", ccsDate, $this->DateFormat);
        
        $this->UserId = new clsField("UserId", ccsInteger, "");
        
        $this->Product = new clsField("Product", ccsText, "");
        
        $this->SalesQuantity = new clsField("SalesQuantity", ccsSingle, "");
        
        $this->SalesPrice = new clsField("SalesPrice", ccsSingle, "");
        
        $this->SalesValue = new clsField("SalesValue", ccsSingle, "");
        
        $this->email = new clsField("email", ccsText, "");
        
        $this->telephone = new clsField("telephone", ccsText, "");
        
        $this->Town = new clsField("Town", ccsText, "");
        
        $this->State = new clsField("State", ccsText, "");
        
        $this->Country = new clsField("Country", ccsText, "");
        
        $this->SalesOffice = new clsField("SalesOffice", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        
        $this->SalesUser = new clsField("SalesUser", ccsInteger, "");
        
        $this->SalesItem = new clsField("SalesItem", ccsInteger, "");
        
        $this->StateId = new clsField("StateId", ccsInteger, "");
        
        $this->CountryId = new clsField("CountryId", ccsInteger, "");
        
        $this->SalesOfficeId = new clsField("SalesOfficeId", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @9-20F58D1F
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "SalesDate";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("Id", ""), 
            "Sorter_SalesDate" => array("SalesDate", ""), 
            "Sorter_UserId" => array("UserId", ""), 
            "Sorter_Product" => array("Product", ""), 
            "Sorter_SalesQuantity" => array("SalesQuantity", ""), 
            "Sorter_SalesPrice" => array("SalesPrice", ""), 
            "Sorter_SalesValue" => array("SalesValue", ""), 
            "Sorter_email" => array("email", ""), 
            "Sorter_telephone" => array("telephone", ""), 
            "Sorter_Town" => array("Town", ""), 
            "Sorter_State" => array("State", ""), 
            "Sorter_Country" => array("Country", ""), 
            "Sorter_SalesOffice" => array("SalesOffice", ""), 
            "Sorter_IsActive" => array("IsActive", ""), 
            "Sorter_SalesUser" => array("SalesUser", ""), 
            "Sorter_SalesItem" => array("SalesItem", ""), 
            "Sorter_StateId" => array("StateId", ""), 
            "Sorter_CountryId" => array("CountryId", ""), 
            "Sorter_SalesOfficeId" => array("SalesOfficeId", "")));
    }
//End SetOrder Method

//Prepare Method @9-7AC0841F
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_SalesDate", ccsDate, $DefaultDateFormat, $this->DateFormat, $this->Parameters["urls_SalesDate"], "", false);
        $this->wp->AddParameter("2", "urls_SalesUser", ccsInteger, "", "", $this->Parameters["urls_SalesUser"], "", false);
        $this->wp->AddParameter("3", "urls_SalesItem", ccsInteger, "", "", $this->Parameters["urls_SalesItem"], "", false);
        $this->wp->AddParameter("4", "urls_Town", ccsText, "", "", $this->Parameters["urls_Town"], "", false);
        $this->wp->AddParameter("5", "urls_StateId", ccsInteger, "", "", $this->Parameters["urls_StateId"], "", false);
        $this->wp->AddParameter("6", "urls_CountryId", ccsInteger, "", "", $this->Parameters["urls_CountryId"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "SalesDate", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsDate),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "SalesUser", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "SalesItem", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "Town", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "StateId", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsInteger),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "CountryId", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
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
             $this->wp->Criterion[6]);
    }
//End Prepare Method

//Open Method @9-3BC047A2
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM reportsales";
        $this->SQL = "SELECT Id, SalesDate, UserId, Product, SalesQuantity, SalesPrice, SalesValue, email, telephone, Town, State, Country, SalesOffice,\n\n" .
        "IsActive, SalesUser, SalesItem, StateId, CountryId, SalesOfficeId \n\n" .
        "FROM reportsales {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @9-316EB593
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->SalesDate->SetDBValue(trim($this->f("SalesDate")));
        $this->UserId->SetDBValue(trim($this->f("UserId")));
        $this->Product->SetDBValue($this->f("Product"));
        $this->SalesQuantity->SetDBValue(trim($this->f("SalesQuantity")));
        $this->SalesPrice->SetDBValue(trim($this->f("SalesPrice")));
        $this->SalesValue->SetDBValue(trim($this->f("SalesValue")));
        $this->email->SetDBValue($this->f("email"));
        $this->telephone->SetDBValue($this->f("telephone"));
        $this->Town->SetDBValue($this->f("Town"));
        $this->State->SetDBValue($this->f("State"));
        $this->Country->SetDBValue($this->f("Country"));
        $this->SalesOffice->SetDBValue($this->f("SalesOffice"));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
        $this->SalesUser->SetDBValue(trim($this->f("SalesUser")));
        $this->SalesItem->SetDBValue(trim($this->f("SalesItem")));
        $this->StateId->SetDBValue(trim($this->f("StateId")));
        $this->CountryId->SetDBValue(trim($this->f("CountryId")));
        $this->SalesOfficeId->SetDBValue(trim($this->f("SalesOfficeId")));
    }
//End SetValues Method

} //End reportsales1DataSource Class @9-FCB6E20C

class clsRecordreportsalesSearch { //reportsalesSearch Class @86-2E2C147C

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

//Class_Initialize Event @86-128AED70
    function clsRecordreportsalesSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record reportsalesSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "reportsalesSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_SalesDate = new clsControl(ccsTextBox, "s_SalesDate", "Sales Date", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("s_SalesDate", $Method, NULL), $this);
            $this->s_SalesUser = new clsControl(ccsListBox, "s_SalesUser", "Sales User", ccsInteger, "", CCGetRequestParam("s_SalesUser", $Method, NULL), $this);
            $this->s_SalesUser->DSType = dsTable;
            $this->s_SalesUser->DataSource = new clsDBFuelSaver();
            $this->s_SalesUser->ds = & $this->s_SalesUser->DataSource;
            $this->s_SalesUser->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesUser->BoundColumn, $this->s_SalesUser->TextColumn, $this->s_SalesUser->DBFormat) = array("Id", "UserId", "");
            $this->s_SalesItem = new clsControl(ccsListBox, "s_SalesItem", "Product", ccsInteger, "", CCGetRequestParam("s_SalesItem", $Method, NULL), $this);
            $this->s_SalesItem->DSType = dsTable;
            $this->s_SalesItem->DataSource = new clsDBFuelSaver();
            $this->s_SalesItem->ds = & $this->s_SalesItem->DataSource;
            $this->s_SalesItem->DataSource->SQL = "SELECT * \n" .
"FROM inventoryitem {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesItem->BoundColumn, $this->s_SalesItem->TextColumn, $this->s_SalesItem->DBFormat) = array("Id", "InventoryItem", "");
            $this->s_Town = new clsControl(ccsTextBox, "s_Town", "Town", ccsText, "", CCGetRequestParam("s_Town", $Method, NULL), $this);
            $this->s_StateId = new clsControl(ccsListBox, "s_StateId", "State Id", ccsInteger, "", CCGetRequestParam("s_StateId", $Method, NULL), $this);
            $this->s_StateId->DSType = dsTable;
            $this->s_StateId->DataSource = new clsDBFuelSaver();
            $this->s_StateId->ds = & $this->s_StateId->DataSource;
            $this->s_StateId->DataSource->SQL = "SELECT * \n" .
"FROM states {SQL_Where} {SQL_OrderBy}";
            list($this->s_StateId->BoundColumn, $this->s_StateId->TextColumn, $this->s_StateId->DBFormat) = array("Id", "State", "");
            $this->s_CountryId = new clsControl(ccsListBox, "s_CountryId", "Country Id", ccsInteger, "", CCGetRequestParam("s_CountryId", $Method, NULL), $this);
            $this->s_CountryId->DSType = dsTable;
            $this->s_CountryId->DataSource = new clsDBFuelSaver();
            $this->s_CountryId->ds = & $this->s_CountryId->DataSource;
            $this->s_CountryId->DataSource->SQL = "SELECT * \n" .
"FROM countries {SQL_Where} {SQL_OrderBy}";
            list($this->s_CountryId->BoundColumn, $this->s_CountryId->TextColumn, $this->s_CountryId->DBFormat) = array("Id", "Country", "");
            $this->reportsalesPageSize = new clsControl(ccsListBox, "reportsalesPageSize", "reportsalesPageSize", ccsText, "", CCGetRequestParam("reportsalesPageSize", $Method, NULL), $this);
            $this->reportsalesPageSize->DSType = dsListOfValues;
            $this->reportsalesPageSize->Values = array(array("", "Select Value"), array("5", "5"), array("10", "10"), array("25", "25"), array("100", "100"));
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_SalesDate->Value) && !strlen($this->s_SalesDate->Value) && $this->s_SalesDate->Value !== false)
                    $this->s_SalesDate->SetValue(time());
            }
        }
    }
//End Class_Initialize Event

//Validate Method @86-9FD98648
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_SalesDate->Validate() && $Validation);
        $Validation = ($this->s_SalesUser->Validate() && $Validation);
        $Validation = ($this->s_SalesItem->Validate() && $Validation);
        $Validation = ($this->s_Town->Validate() && $Validation);
        $Validation = ($this->s_StateId->Validate() && $Validation);
        $Validation = ($this->s_CountryId->Validate() && $Validation);
        $Validation = ($this->reportsalesPageSize->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_SalesDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_SalesUser->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_SalesItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_Town->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_StateId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_CountryId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->reportsalesPageSize->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @86-4F4442EA
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_SalesDate->Errors->Count());
        $errors = ($errors || $this->s_SalesUser->Errors->Count());
        $errors = ($errors || $this->s_SalesItem->Errors->Count());
        $errors = ($errors || $this->s_Town->Errors->Count());
        $errors = ($errors || $this->s_StateId->Errors->Count());
        $errors = ($errors || $this->s_CountryId->Errors->Count());
        $errors = ($errors || $this->reportsalesPageSize->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @86-91C9C9EF
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
        $Redirect = "reportsales.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "reportsales.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @86-7D1ED586
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
        $this->s_StateId->Prepare();
        $this->s_CountryId->Prepare();
        $this->reportsalesPageSize->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_SalesDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_SalesUser->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_SalesItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_Town->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_StateId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_CountryId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->reportsalesPageSize->Errors->ToString());
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
        $this->s_SalesDate->Show();
        $this->s_SalesUser->Show();
        $this->s_SalesItem->Show();
        $this->s_Town->Show();
        $this->s_StateId->Show();
        $this->s_CountryId->Show();
        $this->reportsalesPageSize->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End reportsalesSearch Class @86-FCB6E20C

class clsRecordsales { //sales Class @101-33F972EF

//Variables @101-9E315808

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

//Class_Initialize Event @101-2C6ABB65
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
            $this->Button_Cancel = new clsButton("Button_Cancel", $Method, $this);
            $this->SalesDate = new clsControl(ccsTextBox, "SalesDate", "Date", ccsDate, array("GeneralDate"), CCGetRequestParam("SalesDate", $Method, NULL), $this);
            $this->SalesDate->Required = true;
            $this->SalesUser = new clsControl(ccsListBox, "SalesUser", "Sales User", ccsInteger, "", CCGetRequestParam("SalesUser", $Method, NULL), $this);
            $this->SalesUser->DSType = dsTable;
            $this->SalesUser->DataSource = new clsDBFuelSaver();
            $this->SalesUser->ds = & $this->SalesUser->DataSource;
            $this->SalesUser->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->SalesUser->BoundColumn, $this->SalesUser->TextColumn, $this->SalesUser->DBFormat) = array("Id", "UserId", "");
            $this->SalesItem = new clsControl(ccsListBox, "SalesItem", "Product", ccsInteger, "", CCGetRequestParam("SalesItem", $Method, NULL), $this);
            $this->SalesItem->DSType = dsTable;
            $this->SalesItem->DataSource = new clsDBFuelSaver();
            $this->SalesItem->ds = & $this->SalesItem->DataSource;
            $this->SalesItem->DataSource->SQL = "SELECT * \n" .
"FROM inventoryitem {SQL_Where} {SQL_OrderBy}";
            list($this->SalesItem->BoundColumn, $this->SalesItem->TextColumn, $this->SalesItem->DBFormat) = array("Id", "InventoryItem", "");
            $this->SalesItem->Required = true;
            $this->SalesQuantity = new clsControl(ccsTextBox, "SalesQuantity", "Quantity", ccsSingle, "", CCGetRequestParam("SalesQuantity", $Method, NULL), $this);
            $this->SalesQuantity->Required = true;
            $this->SalesPrice = new clsControl(ccsTextBox, "SalesPrice", "Unit Price", ccsSingle, "", CCGetRequestParam("SalesPrice", $Method, NULL), $this);
            $this->SalesPrice->Required = true;
            $this->SalesValue = new clsControl(ccsTextBox, "SalesValue", "Amount", ccsSingle, "", CCGetRequestParam("SalesValue", $Method, NULL), $this);
            $this->SalesValue->Required = true;
            $this->SalesOffice = new clsControl(ccsListBox, "SalesOffice", "Sales Office", ccsInteger, "", CCGetRequestParam("SalesOffice", $Method, NULL), $this);
            $this->SalesOffice->DSType = dsTable;
            $this->SalesOffice->DataSource = new clsDBFuelSaver();
            $this->SalesOffice->ds = & $this->SalesOffice->DataSource;
            $this->SalesOffice->DataSource->SQL = "SELECT * \n" .
"FROM salesoffices {SQL_Where} {SQL_OrderBy}";
            list($this->SalesOffice->BoundColumn, $this->SalesOffice->TextColumn, $this->SalesOffice->DBFormat) = array("Id", "SalesOffice", "");
            $this->SalesOffice->Required = true;
            $this->SalesRemarks = new clsControl(ccsTextArea, "SalesRemarks", "Remarks", ccsText, "", CCGetRequestParam("SalesRemarks", $Method, NULL), $this);
            $this->IsActive = new clsControl(ccsCheckBox, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", $Method, NULL), $this);
            $this->IsActive->CheckedValue = $this->IsActive->GetParsedValue(1);
            $this->IsActive->UncheckedValue = $this->IsActive->GetParsedValue(0);
            if(!$this->FormSubmitted) {
                if(!is_array($this->SalesDate->Value) && !strlen($this->SalesDate->Value) && $this->SalesDate->Value !== false)
                    $this->SalesDate->SetValue(time());
                if(!is_array($this->IsActive->Value) && !strlen($this->IsActive->Value) && $this->IsActive->Value !== false)
                    $this->IsActive->SetValue(false);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @101-4F76030F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlId"] = CCGetFromGet("Id", NULL);
    }
//End Initialize Method

//Validate Method @101-9C286A60
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->SalesDate->Validate() && $Validation);
        $Validation = ($this->SalesUser->Validate() && $Validation);
        $Validation = ($this->SalesItem->Validate() && $Validation);
        $Validation = ($this->SalesQuantity->Validate() && $Validation);
        $Validation = ($this->SalesPrice->Validate() && $Validation);
        $Validation = ($this->SalesValue->Validate() && $Validation);
        $Validation = ($this->SalesOffice->Validate() && $Validation);
        $Validation = ($this->SalesRemarks->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->SalesDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesUser->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesItem->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesQuantity->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesPrice->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesValue->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesOffice->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesRemarks->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @101-E752D5D5
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->SalesDate->Errors->Count());
        $errors = ($errors || $this->SalesUser->Errors->Count());
        $errors = ($errors || $this->SalesItem->Errors->Count());
        $errors = ($errors || $this->SalesQuantity->Errors->Count());
        $errors = ($errors || $this->SalesPrice->Errors->Count());
        $errors = ($errors || $this->SalesValue->Errors->Count());
        $errors = ($errors || $this->SalesOffice->Errors->Count());
        $errors = ($errors || $this->SalesRemarks->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @101-288F0419
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

//InsertRow Method @101-E791AB6A
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->SalesDate->SetValue($this->SalesDate->GetValue(true));
        $this->DataSource->SalesUser->SetValue($this->SalesUser->GetValue(true));
        $this->DataSource->SalesItem->SetValue($this->SalesItem->GetValue(true));
        $this->DataSource->SalesQuantity->SetValue($this->SalesQuantity->GetValue(true));
        $this->DataSource->SalesPrice->SetValue($this->SalesPrice->GetValue(true));
        $this->DataSource->SalesValue->SetValue($this->SalesValue->GetValue(true));
        $this->DataSource->SalesOffice->SetValue($this->SalesOffice->GetValue(true));
        $this->DataSource->SalesRemarks->SetValue($this->SalesRemarks->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @101-98586CCC
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->SalesDate->SetValue($this->SalesDate->GetValue(true));
        $this->DataSource->SalesUser->SetValue($this->SalesUser->GetValue(true));
        $this->DataSource->SalesItem->SetValue($this->SalesItem->GetValue(true));
        $this->DataSource->SalesQuantity->SetValue($this->SalesQuantity->GetValue(true));
        $this->DataSource->SalesPrice->SetValue($this->SalesPrice->GetValue(true));
        $this->DataSource->SalesValue->SetValue($this->SalesValue->GetValue(true));
        $this->DataSource->SalesOffice->SetValue($this->SalesOffice->GetValue(true));
        $this->DataSource->SalesRemarks->SetValue($this->SalesRemarks->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @101-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @101-516A8FB2
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
        $this->SalesOffice->Prepare();

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
                    $this->SalesDate->SetValue($this->DataSource->SalesDate->GetValue());
                    $this->SalesUser->SetValue($this->DataSource->SalesUser->GetValue());
                    $this->SalesItem->SetValue($this->DataSource->SalesItem->GetValue());
                    $this->SalesQuantity->SetValue($this->DataSource->SalesQuantity->GetValue());
                    $this->SalesPrice->SetValue($this->DataSource->SalesPrice->GetValue());
                    $this->SalesValue->SetValue($this->DataSource->SalesValue->GetValue());
                    $this->SalesOffice->SetValue($this->DataSource->SalesOffice->GetValue());
                    $this->SalesRemarks->SetValue($this->DataSource->SalesRemarks->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->SalesDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesUser->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesItem->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesQuantity->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesPrice->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesValue->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesOffice->Errors->ToString());
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
        $this->Button_Cancel->Show();
        $this->SalesDate->Show();
        $this->SalesUser->Show();
        $this->SalesItem->Show();
        $this->SalesQuantity->Show();
        $this->SalesPrice->Show();
        $this->SalesValue->Show();
        $this->SalesOffice->Show();
        $this->SalesRemarks->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End sales Class @101-FCB6E20C

class clssalesDataSource extends clsDBFuelSaver {  //salesDataSource Class @101-78DAF30D

//DataSource Variables @101-A2E081EB
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
    public $SalesDate;
    public $SalesUser;
    public $SalesItem;
    public $SalesQuantity;
    public $SalesPrice;
    public $SalesValue;
    public $SalesOffice;
    public $SalesRemarks;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @101-5E525F96
    function clssalesDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record sales/Error";
        $this->Initialize();
        $this->SalesDate = new clsField("SalesDate", ccsDate, $this->DateFormat);
        
        $this->SalesUser = new clsField("SalesUser", ccsInteger, "");
        
        $this->SalesItem = new clsField("SalesItem", ccsInteger, "");
        
        $this->SalesQuantity = new clsField("SalesQuantity", ccsSingle, "");
        
        $this->SalesPrice = new clsField("SalesPrice", ccsSingle, "");
        
        $this->SalesValue = new clsField("SalesValue", ccsSingle, "");
        
        $this->SalesOffice = new clsField("SalesOffice", ccsInteger, "");
        
        $this->SalesRemarks = new clsField("SalesRemarks", ccsText, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["SalesDate"] = array("Name" => "SalesDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesUser"] = array("Name" => "SalesUser", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesItem"] = array("Name" => "SalesItem", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesQuantity"] = array("Name" => "SalesQuantity", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesPrice"] = array("Name" => "SalesPrice", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesValue"] = array("Name" => "SalesValue", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesOffice"] = array("Name" => "SalesOffice", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesRemarks"] = array("Name" => "SalesRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
        $this->UpdateFields["SalesDate"] = array("Name" => "SalesDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesUser"] = array("Name" => "SalesUser", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesItem"] = array("Name" => "SalesItem", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesQuantity"] = array("Name" => "SalesQuantity", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesPrice"] = array("Name" => "SalesPrice", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesValue"] = array("Name" => "SalesValue", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesOffice"] = array("Name" => "SalesOffice", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesRemarks"] = array("Name" => "SalesRemarks", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @101-F755E9A7
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

//Open Method @101-F8C585FB
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

//SetValues Method @101-664915D3
    function SetValues()
    {
        $this->SalesDate->SetDBValue(trim($this->f("SalesDate")));
        $this->SalesUser->SetDBValue(trim($this->f("SalesUser")));
        $this->SalesItem->SetDBValue(trim($this->f("SalesItem")));
        $this->SalesQuantity->SetDBValue(trim($this->f("SalesQuantity")));
        $this->SalesPrice->SetDBValue(trim($this->f("SalesPrice")));
        $this->SalesValue->SetDBValue(trim($this->f("SalesValue")));
        $this->SalesOffice->SetDBValue(trim($this->f("SalesOffice")));
        $this->SalesRemarks->SetDBValue($this->f("SalesRemarks"));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @101-75045EB5
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["SalesDate"]["Value"] = $this->SalesDate->GetDBValue(true);
        $this->InsertFields["SalesUser"]["Value"] = $this->SalesUser->GetDBValue(true);
        $this->InsertFields["SalesItem"]["Value"] = $this->SalesItem->GetDBValue(true);
        $this->InsertFields["SalesQuantity"]["Value"] = $this->SalesQuantity->GetDBValue(true);
        $this->InsertFields["SalesPrice"]["Value"] = $this->SalesPrice->GetDBValue(true);
        $this->InsertFields["SalesValue"]["Value"] = $this->SalesValue->GetDBValue(true);
        $this->InsertFields["SalesOffice"]["Value"] = $this->SalesOffice->GetDBValue(true);
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

//Update Method @101-303D551A
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["SalesDate"]["Value"] = $this->SalesDate->GetDBValue(true);
        $this->UpdateFields["SalesUser"]["Value"] = $this->SalesUser->GetDBValue(true);
        $this->UpdateFields["SalesItem"]["Value"] = $this->SalesItem->GetDBValue(true);
        $this->UpdateFields["SalesQuantity"]["Value"] = $this->SalesQuantity->GetDBValue(true);
        $this->UpdateFields["SalesPrice"]["Value"] = $this->SalesPrice->GetDBValue(true);
        $this->UpdateFields["SalesValue"]["Value"] = $this->SalesValue->GetDBValue(true);
        $this->UpdateFields["SalesOffice"]["Value"] = $this->SalesOffice->GetDBValue(true);
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

//Delete Method @101-7D696C38
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

} //End salesDataSource Class @101-FCB6E20C

//Include Page implementation @7-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Initialize Page @1-026BD1C2
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
$TemplateFileName = "reportsales.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.datepicker.js|js/jquery/datepicker/ccs-date-timepicker.js|js/jquery/ui/jquery.ui.position.js|js/jquery/ui/jquery.ui.menu.js|js/jquery/ui/jquery.ui.autocomplete.js|js/jquery/autocomplete/ccs-autocomplete.js|js/jquery/ui/jquery.ui.mouse.js|js/jquery/ui/jquery.ui.draggable.js|js/jquery/ui/jquery.ui.resizable.js|js/jquery/ui/jquery.ui.button.js|js/jquery/ui/jquery.ui.dialog.js|js/jquery/dialog/ccs-dialog.js|js/jquery/updatepanel/ccs-update-panel.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-C8C654E6
include_once("./reportsales_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-552EC56F
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
$Panel1 = new clsPanel("Panel1", $MainPage);
$Panel1->GenerateDiv = true;
$Panel1->PanelId = "ContentPanel1";
$reportsales1 = new clsGridreportsales1("", $MainPage);
$reportsalesSearch = new clsRecordreportsalesSearch("", $MainPage);
$Panel2 = new clsPanel("Panel2", $MainPage);
$Panel2->GenerateDiv = true;
$Panel2->PanelId = "ContentPanel1Panel2";
$sales = new clsRecordsales("", $MainPage);
$HeaderSidebar = new clsPanel("HeaderSidebar", $MainPage);
$HeaderSidebar->PlaceholderName = "HeaderSidebar";
$Menu = new clsPanel("Menu", $MainPage);
$Menu->PlaceholderName = "Menu";
$MenuIncludablePage = new clsmenuincludablepage("", "MenuIncludablePage", $MainPage);
$MenuIncludablePage->Initialize();
$MainPage->Head = & $Head;
$MainPage->Content = & $Content;
$MainPage->Panel1 = & $Panel1;
$MainPage->reportsales1 = & $reportsales1;
$MainPage->reportsalesSearch = & $reportsalesSearch;
$MainPage->Panel2 = & $Panel2;
$MainPage->sales = & $sales;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$Content->AddComponent("Panel1", $Panel1);
$Panel1->AddComponent("reportsales1", $reportsales1);
$Panel1->AddComponent("reportsalesSearch", $reportsalesSearch);
$Panel1->AddComponent("Panel2", $Panel2);
$Panel2->AddComponent("sales", $sales);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$reportsales1->Initialize();
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

//Execute Components @1-F86692E9
$MasterPage->Operations();
$MenuIncludablePage->Operations();
$sales->Operation();
$reportsalesSearch->Operation();
//End Execute Components

//Go to destination page @1-4B8F3C47
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($reportsales1);
    unset($reportsalesSearch);
    unset($sales);
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

//Unload Page @1-5CF879FF
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($reportsales1);
unset($reportsalesSearch);
unset($sales);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($Tpl);
//End Unload Page


?>
