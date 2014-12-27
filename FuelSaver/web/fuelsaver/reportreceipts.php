<?php
//Include Common Files @1-35BA748A
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "reportreceipts.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-92E723B7
include_once(RelativePath . "/Designs/fuelsaver/MasterPage.php");
//End Master Page implementation

class clsGridreportreceipts1 { //reportreceipts1 class @9-7948A8FB

//Variables @9-52CC33F5

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
    public $Sorter_ReceiptNo;
    public $Sorter_ReceiptDate;
    public $Sorter_SalesOrder;
    public $Sorter_ReceiptAmount;
    public $Sorter_ReferenceId;
    public $Sorter_VerifiedAmount;
    public $Sorter_VerifiedBy;
    public $Sorter_IsActive;
    public $Sorter_SalesDate;
    public $Sorter_SalesUser;
    public $Sorter_SalesItem;
    public $Sorter_SalesQuantity;
    public $Sorter_SalesPrice;
    public $Sorter_SalesValue;
    public $Sorter_SalesRemarks;
    public $Sorter_Product;
    public $Sorter_ProductCategory;
    public $Sorter_UserId;
    public $Sorter_UserName;
    public $Sorter_UserFullName;
    public $Sorter_UserRole;
    public $Sorter_email;
    public $Sorter_telephone;
    public $Sorter_Address1;
    public $Sorter_Address2;
    public $Sorter_Address3;
    public $Sorter_Postcode;
    public $Sorter_Town;
    public $Sorter_StateId;
    public $Sorter_CountryId;
    public $Sorter_State;
    public $Sorter_Country;
    public $Sorter_SalesSummary;
    public $Sorter_ReceiptsSummary;
    public $Sorter_SalesOfficeId;
    public $Sorter_SalesOffice;
//End Variables

//Class_Initialize Event @9-82FB43D8
    function clsGridreportreceipts1($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "reportreceipts1";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid reportreceipts1";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsreportreceipts1DataSource($this);
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
        $this->SorterName = CCGetParam("reportreceipts1Order", "");
        $this->SorterDirection = CCGetParam("reportreceipts1Dir", "");

        $this->Id = new clsControl(ccsLink, "Id", "Id", ccsInteger, "", CCGetRequestParam("Id", ccsGet, NULL), $this);
        $this->Id->Page = "";
        $this->ReceiptNo = new clsControl(ccsLabel, "ReceiptNo", "ReceiptNo", ccsInteger, "", CCGetRequestParam("ReceiptNo", ccsGet, NULL), $this);
        $this->ReceiptDate = new clsControl(ccsLabel, "ReceiptDate", "ReceiptDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("ReceiptDate", ccsGet, NULL), $this);
        $this->SalesOrder = new clsControl(ccsLabel, "SalesOrder", "SalesOrder", ccsInteger, "", CCGetRequestParam("SalesOrder", ccsGet, NULL), $this);
        $this->ReceiptAmount = new clsControl(ccsLabel, "ReceiptAmount", "ReceiptAmount", ccsSingle, "", CCGetRequestParam("ReceiptAmount", ccsGet, NULL), $this);
        $this->ReferenceId = new clsControl(ccsLabel, "ReferenceId", "ReferenceId", ccsText, "", CCGetRequestParam("ReferenceId", ccsGet, NULL), $this);
        $this->VerifiedAmount = new clsControl(ccsLabel, "VerifiedAmount", "VerifiedAmount", ccsSingle, "", CCGetRequestParam("VerifiedAmount", ccsGet, NULL), $this);
        $this->VerifiedBy = new clsControl(ccsLabel, "VerifiedBy", "VerifiedBy", ccsInteger, "", CCGetRequestParam("VerifiedBy", ccsGet, NULL), $this);
        $this->IsActive = new clsControl(ccsLabel, "IsActive", "IsActive", ccsInteger, "", CCGetRequestParam("IsActive", ccsGet, NULL), $this);
        $this->SalesDate = new clsControl(ccsLabel, "SalesDate", "SalesDate", ccsDate, $DefaultDateFormat, CCGetRequestParam("SalesDate", ccsGet, NULL), $this);
        $this->SalesUser = new clsControl(ccsLabel, "SalesUser", "SalesUser", ccsInteger, "", CCGetRequestParam("SalesUser", ccsGet, NULL), $this);
        $this->SalesItem = new clsControl(ccsLabel, "SalesItem", "SalesItem", ccsInteger, "", CCGetRequestParam("SalesItem", ccsGet, NULL), $this);
        $this->SalesQuantity = new clsControl(ccsLabel, "SalesQuantity", "SalesQuantity", ccsSingle, "", CCGetRequestParam("SalesQuantity", ccsGet, NULL), $this);
        $this->SalesPrice = new clsControl(ccsLabel, "SalesPrice", "SalesPrice", ccsSingle, "", CCGetRequestParam("SalesPrice", ccsGet, NULL), $this);
        $this->SalesValue = new clsControl(ccsLabel, "SalesValue", "SalesValue", ccsSingle, "", CCGetRequestParam("SalesValue", ccsGet, NULL), $this);
        $this->SalesRemarks = new clsControl(ccsLabel, "SalesRemarks", "SalesRemarks", ccsText, "", CCGetRequestParam("SalesRemarks", ccsGet, NULL), $this);
        $this->Product = new clsControl(ccsLabel, "Product", "Product", ccsText, "", CCGetRequestParam("Product", ccsGet, NULL), $this);
        $this->ProductCategory = new clsControl(ccsLabel, "ProductCategory", "ProductCategory", ccsText, "", CCGetRequestParam("ProductCategory", ccsGet, NULL), $this);
        $this->UserId = new clsControl(ccsLabel, "UserId", "UserId", ccsInteger, "", CCGetRequestParam("UserId", ccsGet, NULL), $this);
        $this->UserName = new clsControl(ccsLabel, "UserName", "UserName", ccsText, "", CCGetRequestParam("UserName", ccsGet, NULL), $this);
        $this->UserFullName = new clsControl(ccsLabel, "UserFullName", "UserFullName", ccsText, "", CCGetRequestParam("UserFullName", ccsGet, NULL), $this);
        $this->UserRole = new clsControl(ccsLabel, "UserRole", "UserRole", ccsText, "", CCGetRequestParam("UserRole", ccsGet, NULL), $this);
        $this->email = new clsControl(ccsLabel, "email", "email", ccsText, "", CCGetRequestParam("email", ccsGet, NULL), $this);
        $this->telephone = new clsControl(ccsLabel, "telephone", "telephone", ccsText, "", CCGetRequestParam("telephone", ccsGet, NULL), $this);
        $this->Address1 = new clsControl(ccsLabel, "Address1", "Address1", ccsText, "", CCGetRequestParam("Address1", ccsGet, NULL), $this);
        $this->Address2 = new clsControl(ccsLabel, "Address2", "Address2", ccsText, "", CCGetRequestParam("Address2", ccsGet, NULL), $this);
        $this->Address3 = new clsControl(ccsLabel, "Address3", "Address3", ccsText, "", CCGetRequestParam("Address3", ccsGet, NULL), $this);
        $this->Postcode = new clsControl(ccsLabel, "Postcode", "Postcode", ccsInteger, "", CCGetRequestParam("Postcode", ccsGet, NULL), $this);
        $this->Town = new clsControl(ccsLabel, "Town", "Town", ccsText, "", CCGetRequestParam("Town", ccsGet, NULL), $this);
        $this->StateId = new clsControl(ccsLabel, "StateId", "StateId", ccsInteger, "", CCGetRequestParam("StateId", ccsGet, NULL), $this);
        $this->CountryId = new clsControl(ccsLabel, "CountryId", "CountryId", ccsInteger, "", CCGetRequestParam("CountryId", ccsGet, NULL), $this);
        $this->State = new clsControl(ccsLabel, "State", "State", ccsText, "", CCGetRequestParam("State", ccsGet, NULL), $this);
        $this->Country = new clsControl(ccsLabel, "Country", "Country", ccsText, "", CCGetRequestParam("Country", ccsGet, NULL), $this);
        $this->SalesSummary = new clsControl(ccsLabel, "SalesSummary", "SalesSummary", ccsText, "", CCGetRequestParam("SalesSummary", ccsGet, NULL), $this);
        $this->ReceiptsSummary = new clsControl(ccsLabel, "ReceiptsSummary", "ReceiptsSummary", ccsText, "", CCGetRequestParam("ReceiptsSummary", ccsGet, NULL), $this);
        $this->SalesOfficeId = new clsControl(ccsLabel, "SalesOfficeId", "SalesOfficeId", ccsInteger, "", CCGetRequestParam("SalesOfficeId", ccsGet, NULL), $this);
        $this->SalesOffice = new clsControl(ccsLabel, "SalesOffice", "SalesOffice", ccsText, "", CCGetRequestParam("SalesOffice", ccsGet, NULL), $this);
        $this->reportreceipts1_Insert = new clsControl(ccsLink, "reportreceipts1_Insert", "reportreceipts1_Insert", ccsText, "", CCGetRequestParam("reportreceipts1_Insert", ccsGet, NULL), $this);
        $this->reportreceipts1_Insert->Parameters = CCGetQueryString("QueryString", array("Id", "ccsForm"));
        $this->reportreceipts1_Insert->Page = "reportreceipts.php";
        $this->reportreceipts1_TotalRecords = new clsControl(ccsLabel, "reportreceipts1_TotalRecords", "reportreceipts1_TotalRecords", ccsText, "", CCGetRequestParam("reportreceipts1_TotalRecords", ccsGet, NULL), $this);
        $this->Sorter_Id = new clsSorter($this->ComponentName, "Sorter_Id", $FileName, $this);
        $this->Sorter_ReceiptNo = new clsSorter($this->ComponentName, "Sorter_ReceiptNo", $FileName, $this);
        $this->Sorter_ReceiptDate = new clsSorter($this->ComponentName, "Sorter_ReceiptDate", $FileName, $this);
        $this->Sorter_SalesOrder = new clsSorter($this->ComponentName, "Sorter_SalesOrder", $FileName, $this);
        $this->Sorter_ReceiptAmount = new clsSorter($this->ComponentName, "Sorter_ReceiptAmount", $FileName, $this);
        $this->Sorter_ReferenceId = new clsSorter($this->ComponentName, "Sorter_ReferenceId", $FileName, $this);
        $this->Sorter_VerifiedAmount = new clsSorter($this->ComponentName, "Sorter_VerifiedAmount", $FileName, $this);
        $this->Sorter_VerifiedBy = new clsSorter($this->ComponentName, "Sorter_VerifiedBy", $FileName, $this);
        $this->Sorter_IsActive = new clsSorter($this->ComponentName, "Sorter_IsActive", $FileName, $this);
        $this->Sorter_SalesDate = new clsSorter($this->ComponentName, "Sorter_SalesDate", $FileName, $this);
        $this->Sorter_SalesUser = new clsSorter($this->ComponentName, "Sorter_SalesUser", $FileName, $this);
        $this->Sorter_SalesItem = new clsSorter($this->ComponentName, "Sorter_SalesItem", $FileName, $this);
        $this->Sorter_SalesQuantity = new clsSorter($this->ComponentName, "Sorter_SalesQuantity", $FileName, $this);
        $this->Sorter_SalesPrice = new clsSorter($this->ComponentName, "Sorter_SalesPrice", $FileName, $this);
        $this->Sorter_SalesValue = new clsSorter($this->ComponentName, "Sorter_SalesValue", $FileName, $this);
        $this->Sorter_SalesRemarks = new clsSorter($this->ComponentName, "Sorter_SalesRemarks", $FileName, $this);
        $this->Sorter_Product = new clsSorter($this->ComponentName, "Sorter_Product", $FileName, $this);
        $this->Sorter_ProductCategory = new clsSorter($this->ComponentName, "Sorter_ProductCategory", $FileName, $this);
        $this->Sorter_UserId = new clsSorter($this->ComponentName, "Sorter_UserId", $FileName, $this);
        $this->Sorter_UserName = new clsSorter($this->ComponentName, "Sorter_UserName", $FileName, $this);
        $this->Sorter_UserFullName = new clsSorter($this->ComponentName, "Sorter_UserFullName", $FileName, $this);
        $this->Sorter_UserRole = new clsSorter($this->ComponentName, "Sorter_UserRole", $FileName, $this);
        $this->Sorter_email = new clsSorter($this->ComponentName, "Sorter_email", $FileName, $this);
        $this->Sorter_telephone = new clsSorter($this->ComponentName, "Sorter_telephone", $FileName, $this);
        $this->Sorter_Address1 = new clsSorter($this->ComponentName, "Sorter_Address1", $FileName, $this);
        $this->Sorter_Address2 = new clsSorter($this->ComponentName, "Sorter_Address2", $FileName, $this);
        $this->Sorter_Address3 = new clsSorter($this->ComponentName, "Sorter_Address3", $FileName, $this);
        $this->Sorter_Postcode = new clsSorter($this->ComponentName, "Sorter_Postcode", $FileName, $this);
        $this->Sorter_Town = new clsSorter($this->ComponentName, "Sorter_Town", $FileName, $this);
        $this->Sorter_StateId = new clsSorter($this->ComponentName, "Sorter_StateId", $FileName, $this);
        $this->Sorter_CountryId = new clsSorter($this->ComponentName, "Sorter_CountryId", $FileName, $this);
        $this->Sorter_State = new clsSorter($this->ComponentName, "Sorter_State", $FileName, $this);
        $this->Sorter_Country = new clsSorter($this->ComponentName, "Sorter_Country", $FileName, $this);
        $this->Sorter_SalesSummary = new clsSorter($this->ComponentName, "Sorter_SalesSummary", $FileName, $this);
        $this->Sorter_ReceiptsSummary = new clsSorter($this->ComponentName, "Sorter_ReceiptsSummary", $FileName, $this);
        $this->Sorter_SalesOfficeId = new clsSorter($this->ComponentName, "Sorter_SalesOfficeId", $FileName, $this);
        $this->Sorter_SalesOffice = new clsSorter($this->ComponentName, "Sorter_SalesOffice", $FileName, $this);
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

//Show Method @9-A8FBE4CB
    function Show()
    {
        $Tpl = CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_UserFullName"] = CCGetFromGet("s_UserFullName", NULL);
        $this->DataSource->Parameters["urls_SalesOrder"] = CCGetFromGet("s_SalesOrder", NULL);
        $this->DataSource->Parameters["urls_SalesOfficeId"] = CCGetFromGet("s_SalesOfficeId", NULL);
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
            $this->ControlsVisible["ReceiptNo"] = $this->ReceiptNo->Visible;
            $this->ControlsVisible["ReceiptDate"] = $this->ReceiptDate->Visible;
            $this->ControlsVisible["SalesOrder"] = $this->SalesOrder->Visible;
            $this->ControlsVisible["ReceiptAmount"] = $this->ReceiptAmount->Visible;
            $this->ControlsVisible["ReferenceId"] = $this->ReferenceId->Visible;
            $this->ControlsVisible["VerifiedAmount"] = $this->VerifiedAmount->Visible;
            $this->ControlsVisible["VerifiedBy"] = $this->VerifiedBy->Visible;
            $this->ControlsVisible["IsActive"] = $this->IsActive->Visible;
            $this->ControlsVisible["SalesDate"] = $this->SalesDate->Visible;
            $this->ControlsVisible["SalesUser"] = $this->SalesUser->Visible;
            $this->ControlsVisible["SalesItem"] = $this->SalesItem->Visible;
            $this->ControlsVisible["SalesQuantity"] = $this->SalesQuantity->Visible;
            $this->ControlsVisible["SalesPrice"] = $this->SalesPrice->Visible;
            $this->ControlsVisible["SalesValue"] = $this->SalesValue->Visible;
            $this->ControlsVisible["SalesRemarks"] = $this->SalesRemarks->Visible;
            $this->ControlsVisible["Product"] = $this->Product->Visible;
            $this->ControlsVisible["ProductCategory"] = $this->ProductCategory->Visible;
            $this->ControlsVisible["UserId"] = $this->UserId->Visible;
            $this->ControlsVisible["UserName"] = $this->UserName->Visible;
            $this->ControlsVisible["UserFullName"] = $this->UserFullName->Visible;
            $this->ControlsVisible["UserRole"] = $this->UserRole->Visible;
            $this->ControlsVisible["email"] = $this->email->Visible;
            $this->ControlsVisible["telephone"] = $this->telephone->Visible;
            $this->ControlsVisible["Address1"] = $this->Address1->Visible;
            $this->ControlsVisible["Address2"] = $this->Address2->Visible;
            $this->ControlsVisible["Address3"] = $this->Address3->Visible;
            $this->ControlsVisible["Postcode"] = $this->Postcode->Visible;
            $this->ControlsVisible["Town"] = $this->Town->Visible;
            $this->ControlsVisible["StateId"] = $this->StateId->Visible;
            $this->ControlsVisible["CountryId"] = $this->CountryId->Visible;
            $this->ControlsVisible["State"] = $this->State->Visible;
            $this->ControlsVisible["Country"] = $this->Country->Visible;
            $this->ControlsVisible["SalesSummary"] = $this->SalesSummary->Visible;
            $this->ControlsVisible["ReceiptsSummary"] = $this->ReceiptsSummary->Visible;
            $this->ControlsVisible["SalesOfficeId"] = $this->SalesOfficeId->Visible;
            $this->ControlsVisible["SalesOffice"] = $this->SalesOffice->Visible;
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
                $this->ReceiptNo->SetValue($this->DataSource->ReceiptNo->GetValue());
                $this->ReceiptDate->SetValue($this->DataSource->ReceiptDate->GetValue());
                $this->SalesOrder->SetValue($this->DataSource->SalesOrder->GetValue());
                $this->ReceiptAmount->SetValue($this->DataSource->ReceiptAmount->GetValue());
                $this->ReferenceId->SetValue($this->DataSource->ReferenceId->GetValue());
                $this->VerifiedAmount->SetValue($this->DataSource->VerifiedAmount->GetValue());
                $this->VerifiedBy->SetValue($this->DataSource->VerifiedBy->GetValue());
                $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                $this->SalesDate->SetValue($this->DataSource->SalesDate->GetValue());
                $this->SalesUser->SetValue($this->DataSource->SalesUser->GetValue());
                $this->SalesItem->SetValue($this->DataSource->SalesItem->GetValue());
                $this->SalesQuantity->SetValue($this->DataSource->SalesQuantity->GetValue());
                $this->SalesPrice->SetValue($this->DataSource->SalesPrice->GetValue());
                $this->SalesValue->SetValue($this->DataSource->SalesValue->GetValue());
                $this->SalesRemarks->SetValue($this->DataSource->SalesRemarks->GetValue());
                $this->Product->SetValue($this->DataSource->Product->GetValue());
                $this->ProductCategory->SetValue($this->DataSource->ProductCategory->GetValue());
                $this->UserId->SetValue($this->DataSource->UserId->GetValue());
                $this->UserName->SetValue($this->DataSource->UserName->GetValue());
                $this->UserFullName->SetValue($this->DataSource->UserFullName->GetValue());
                $this->UserRole->SetValue($this->DataSource->UserRole->GetValue());
                $this->email->SetValue($this->DataSource->email->GetValue());
                $this->telephone->SetValue($this->DataSource->telephone->GetValue());
                $this->Address1->SetValue($this->DataSource->Address1->GetValue());
                $this->Address2->SetValue($this->DataSource->Address2->GetValue());
                $this->Address3->SetValue($this->DataSource->Address3->GetValue());
                $this->Postcode->SetValue($this->DataSource->Postcode->GetValue());
                $this->Town->SetValue($this->DataSource->Town->GetValue());
                $this->StateId->SetValue($this->DataSource->StateId->GetValue());
                $this->CountryId->SetValue($this->DataSource->CountryId->GetValue());
                $this->State->SetValue($this->DataSource->State->GetValue());
                $this->Country->SetValue($this->DataSource->Country->GetValue());
                $this->SalesSummary->SetValue($this->DataSource->SalesSummary->GetValue());
                $this->ReceiptsSummary->SetValue($this->DataSource->ReceiptsSummary->GetValue());
                $this->SalesOfficeId->SetValue($this->DataSource->SalesOfficeId->GetValue());
                $this->SalesOffice->SetValue($this->DataSource->SalesOffice->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->Id->Show();
                $this->ReceiptNo->Show();
                $this->ReceiptDate->Show();
                $this->SalesOrder->Show();
                $this->ReceiptAmount->Show();
                $this->ReferenceId->Show();
                $this->VerifiedAmount->Show();
                $this->VerifiedBy->Show();
                $this->IsActive->Show();
                $this->SalesDate->Show();
                $this->SalesUser->Show();
                $this->SalesItem->Show();
                $this->SalesQuantity->Show();
                $this->SalesPrice->Show();
                $this->SalesValue->Show();
                $this->SalesRemarks->Show();
                $this->Product->Show();
                $this->ProductCategory->Show();
                $this->UserId->Show();
                $this->UserName->Show();
                $this->UserFullName->Show();
                $this->UserRole->Show();
                $this->email->Show();
                $this->telephone->Show();
                $this->Address1->Show();
                $this->Address2->Show();
                $this->Address3->Show();
                $this->Postcode->Show();
                $this->Town->Show();
                $this->StateId->Show();
                $this->CountryId->Show();
                $this->State->Show();
                $this->Country->Show();
                $this->SalesSummary->Show();
                $this->ReceiptsSummary->Show();
                $this->SalesOfficeId->Show();
                $this->SalesOffice->Show();
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
        $this->reportreceipts1_Insert->Show();
        $this->reportreceipts1_TotalRecords->Show();
        $this->Sorter_Id->Show();
        $this->Sorter_ReceiptNo->Show();
        $this->Sorter_ReceiptDate->Show();
        $this->Sorter_SalesOrder->Show();
        $this->Sorter_ReceiptAmount->Show();
        $this->Sorter_ReferenceId->Show();
        $this->Sorter_VerifiedAmount->Show();
        $this->Sorter_VerifiedBy->Show();
        $this->Sorter_IsActive->Show();
        $this->Sorter_SalesDate->Show();
        $this->Sorter_SalesUser->Show();
        $this->Sorter_SalesItem->Show();
        $this->Sorter_SalesQuantity->Show();
        $this->Sorter_SalesPrice->Show();
        $this->Sorter_SalesValue->Show();
        $this->Sorter_SalesRemarks->Show();
        $this->Sorter_Product->Show();
        $this->Sorter_ProductCategory->Show();
        $this->Sorter_UserId->Show();
        $this->Sorter_UserName->Show();
        $this->Sorter_UserFullName->Show();
        $this->Sorter_UserRole->Show();
        $this->Sorter_email->Show();
        $this->Sorter_telephone->Show();
        $this->Sorter_Address1->Show();
        $this->Sorter_Address2->Show();
        $this->Sorter_Address3->Show();
        $this->Sorter_Postcode->Show();
        $this->Sorter_Town->Show();
        $this->Sorter_StateId->Show();
        $this->Sorter_CountryId->Show();
        $this->Sorter_State->Show();
        $this->Sorter_Country->Show();
        $this->Sorter_SalesSummary->Show();
        $this->Sorter_ReceiptsSummary->Show();
        $this->Sorter_SalesOfficeId->Show();
        $this->Sorter_SalesOffice->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @9-C1ACCB7F
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->Id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReceiptNo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReceiptDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesOrder->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReceiptAmount->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReferenceId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->VerifiedAmount->Errors->ToString());
        $errors = ComposeStrings($errors, $this->VerifiedBy->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IsActive->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesDate->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesUser->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesItem->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesQuantity->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesPrice->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesValue->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesRemarks->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Product->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ProductCategory->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserName->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserFullName->Errors->ToString());
        $errors = ComposeStrings($errors, $this->UserRole->Errors->ToString());
        $errors = ComposeStrings($errors, $this->email->Errors->ToString());
        $errors = ComposeStrings($errors, $this->telephone->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Address1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Address2->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Address3->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Postcode->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Town->Errors->ToString());
        $errors = ComposeStrings($errors, $this->StateId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->CountryId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->State->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Country->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesSummary->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ReceiptsSummary->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesOfficeId->Errors->ToString());
        $errors = ComposeStrings($errors, $this->SalesOffice->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End reportreceipts1 Class @9-FCB6E20C

class clsreportreceipts1DataSource extends clsDBFuelSaver {  //reportreceipts1DataSource Class @9-80966F4F

//DataSource Variables @9-3FC0A6B5
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $Id;
    public $ReceiptNo;
    public $ReceiptDate;
    public $SalesOrder;
    public $ReceiptAmount;
    public $ReferenceId;
    public $VerifiedAmount;
    public $VerifiedBy;
    public $IsActive;
    public $SalesDate;
    public $SalesUser;
    public $SalesItem;
    public $SalesQuantity;
    public $SalesPrice;
    public $SalesValue;
    public $SalesRemarks;
    public $Product;
    public $ProductCategory;
    public $UserId;
    public $UserName;
    public $UserFullName;
    public $UserRole;
    public $email;
    public $telephone;
    public $Address1;
    public $Address2;
    public $Address3;
    public $Postcode;
    public $Town;
    public $StateId;
    public $CountryId;
    public $State;
    public $Country;
    public $SalesSummary;
    public $ReceiptsSummary;
    public $SalesOfficeId;
    public $SalesOffice;
//End DataSource Variables

//DataSourceClass_Initialize Event @9-F473AB3F
    function clsreportreceipts1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid reportreceipts1";
        $this->Initialize();
        $this->Id = new clsField("Id", ccsInteger, "");
        
        $this->ReceiptNo = new clsField("ReceiptNo", ccsInteger, "");
        
        $this->ReceiptDate = new clsField("ReceiptDate", ccsDate, $this->DateFormat);
        
        $this->SalesOrder = new clsField("SalesOrder", ccsInteger, "");
        
        $this->ReceiptAmount = new clsField("ReceiptAmount", ccsSingle, "");
        
        $this->ReferenceId = new clsField("ReferenceId", ccsText, "");
        
        $this->VerifiedAmount = new clsField("VerifiedAmount", ccsSingle, "");
        
        $this->VerifiedBy = new clsField("VerifiedBy", ccsInteger, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        
        $this->SalesDate = new clsField("SalesDate", ccsDate, $this->DateFormat);
        
        $this->SalesUser = new clsField("SalesUser", ccsInteger, "");
        
        $this->SalesItem = new clsField("SalesItem", ccsInteger, "");
        
        $this->SalesQuantity = new clsField("SalesQuantity", ccsSingle, "");
        
        $this->SalesPrice = new clsField("SalesPrice", ccsSingle, "");
        
        $this->SalesValue = new clsField("SalesValue", ccsSingle, "");
        
        $this->SalesRemarks = new clsField("SalesRemarks", ccsText, "");
        
        $this->Product = new clsField("Product", ccsText, "");
        
        $this->ProductCategory = new clsField("ProductCategory", ccsText, "");
        
        $this->UserId = new clsField("UserId", ccsInteger, "");
        
        $this->UserName = new clsField("UserName", ccsText, "");
        
        $this->UserFullName = new clsField("UserFullName", ccsText, "");
        
        $this->UserRole = new clsField("UserRole", ccsText, "");
        
        $this->email = new clsField("email", ccsText, "");
        
        $this->telephone = new clsField("telephone", ccsText, "");
        
        $this->Address1 = new clsField("Address1", ccsText, "");
        
        $this->Address2 = new clsField("Address2", ccsText, "");
        
        $this->Address3 = new clsField("Address3", ccsText, "");
        
        $this->Postcode = new clsField("Postcode", ccsInteger, "");
        
        $this->Town = new clsField("Town", ccsText, "");
        
        $this->StateId = new clsField("StateId", ccsInteger, "");
        
        $this->CountryId = new clsField("CountryId", ccsInteger, "");
        
        $this->State = new clsField("State", ccsText, "");
        
        $this->Country = new clsField("Country", ccsText, "");
        
        $this->SalesSummary = new clsField("SalesSummary", ccsText, "");
        
        $this->ReceiptsSummary = new clsField("ReceiptsSummary", ccsText, "");
        
        $this->SalesOfficeId = new clsField("SalesOfficeId", ccsInteger, "");
        
        $this->SalesOffice = new clsField("SalesOffice", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @9-05858394
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "ReceiptDate";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_Id" => array("Id", ""), 
            "Sorter_ReceiptNo" => array("ReceiptNo", ""), 
            "Sorter_ReceiptDate" => array("ReceiptDate", ""), 
            "Sorter_SalesOrder" => array("SalesOrder", ""), 
            "Sorter_ReceiptAmount" => array("ReceiptAmount", ""), 
            "Sorter_ReferenceId" => array("ReferenceId", ""), 
            "Sorter_VerifiedAmount" => array("VerifiedAmount", ""), 
            "Sorter_VerifiedBy" => array("VerifiedBy", ""), 
            "Sorter_IsActive" => array("IsActive", ""), 
            "Sorter_SalesDate" => array("SalesDate", ""), 
            "Sorter_SalesUser" => array("SalesUser", ""), 
            "Sorter_SalesItem" => array("SalesItem", ""), 
            "Sorter_SalesQuantity" => array("SalesQuantity", ""), 
            "Sorter_SalesPrice" => array("SalesPrice", ""), 
            "Sorter_SalesValue" => array("SalesValue", ""), 
            "Sorter_SalesRemarks" => array("SalesRemarks", ""), 
            "Sorter_Product" => array("Product", ""), 
            "Sorter_ProductCategory" => array("ProductCategory", ""), 
            "Sorter_UserId" => array("UserId", ""), 
            "Sorter_UserName" => array("UserName", ""), 
            "Sorter_UserFullName" => array("UserFullName", ""), 
            "Sorter_UserRole" => array("UserRole", ""), 
            "Sorter_email" => array("email", ""), 
            "Sorter_telephone" => array("telephone", ""), 
            "Sorter_Address1" => array("Address1", ""), 
            "Sorter_Address2" => array("Address2", ""), 
            "Sorter_Address3" => array("Address3", ""), 
            "Sorter_Postcode" => array("Postcode", ""), 
            "Sorter_Town" => array("Town", ""), 
            "Sorter_StateId" => array("StateId", ""), 
            "Sorter_CountryId" => array("CountryId", ""), 
            "Sorter_State" => array("State", ""), 
            "Sorter_Country" => array("Country", ""), 
            "Sorter_SalesSummary" => array("SalesSummary", ""), 
            "Sorter_ReceiptsSummary" => array("ReceiptsSummary", ""), 
            "Sorter_SalesOfficeId" => array("SalesOfficeId", ""), 
            "Sorter_SalesOffice" => array("SalesOffice", "")));
    }
//End SetOrder Method

//Prepare Method @9-148EA777
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_UserFullName", ccsText, "", "", $this->Parameters["urls_UserFullName"], "", false);
        $this->wp->AddParameter("2", "urls_SalesOrder", ccsInteger, "", "", $this->Parameters["urls_SalesOrder"], "", false);
        $this->wp->AddParameter("3", "urls_SalesOfficeId", ccsInteger, "", "", $this->Parameters["urls_SalesOfficeId"], "", false);
        $this->wp->AddParameter("4", "urls_IsActive", ccsInteger, "", "", $this->Parameters["urls_IsActive"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "UserFullName", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "SalesOrder", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "SalesOfficeId", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "IsActive", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]);
    }
//End Prepare Method

//Open Method @9-9612B48F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM reportreceipts";
        $this->SQL = "SELECT Id, ReceiptNo, ReceiptDate, SalesOrder, ReceiptAmount, ReferenceId, VerifiedAmount, VerifiedBy, IsActive, SalesDate, SalesUser,\n\n" .
        "SalesItem, SalesQuantity, SalesPrice, SalesValue, SalesRemarks, Product, ProductCategory, UserId, UserName, UserFullName,\n\n" .
        "UserRole, email, telephone, Address1, Address2, Address3, Postcode, Town, StateId, CountryId, State, Country, SalesSummary,\n\n" .
        "ReceiptsSummary, SalesOfficeId, SalesOffice \n\n" .
        "FROM reportreceipts {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @9-B2FC332C
    function SetValues()
    {
        $this->Id->SetDBValue(trim($this->f("Id")));
        $this->ReceiptNo->SetDBValue(trim($this->f("ReceiptNo")));
        $this->ReceiptDate->SetDBValue(trim($this->f("ReceiptDate")));
        $this->SalesOrder->SetDBValue(trim($this->f("SalesOrder")));
        $this->ReceiptAmount->SetDBValue(trim($this->f("ReceiptAmount")));
        $this->ReferenceId->SetDBValue($this->f("ReferenceId"));
        $this->VerifiedAmount->SetDBValue(trim($this->f("VerifiedAmount")));
        $this->VerifiedBy->SetDBValue(trim($this->f("VerifiedBy")));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
        $this->SalesDate->SetDBValue(trim($this->f("SalesDate")));
        $this->SalesUser->SetDBValue(trim($this->f("SalesUser")));
        $this->SalesItem->SetDBValue(trim($this->f("SalesItem")));
        $this->SalesQuantity->SetDBValue(trim($this->f("SalesQuantity")));
        $this->SalesPrice->SetDBValue(trim($this->f("SalesPrice")));
        $this->SalesValue->SetDBValue(trim($this->f("SalesValue")));
        $this->SalesRemarks->SetDBValue($this->f("SalesRemarks"));
        $this->Product->SetDBValue($this->f("Product"));
        $this->ProductCategory->SetDBValue($this->f("ProductCategory"));
        $this->UserId->SetDBValue(trim($this->f("UserId")));
        $this->UserName->SetDBValue($this->f("UserName"));
        $this->UserFullName->SetDBValue($this->f("UserFullName"));
        $this->UserRole->SetDBValue($this->f("UserRole"));
        $this->email->SetDBValue($this->f("email"));
        $this->telephone->SetDBValue($this->f("telephone"));
        $this->Address1->SetDBValue($this->f("Address1"));
        $this->Address2->SetDBValue($this->f("Address2"));
        $this->Address3->SetDBValue($this->f("Address3"));
        $this->Postcode->SetDBValue(trim($this->f("Postcode")));
        $this->Town->SetDBValue($this->f("Town"));
        $this->StateId->SetDBValue(trim($this->f("StateId")));
        $this->CountryId->SetDBValue(trim($this->f("CountryId")));
        $this->State->SetDBValue($this->f("State"));
        $this->Country->SetDBValue($this->f("Country"));
        $this->SalesSummary->SetDBValue($this->f("SalesSummary"));
        $this->ReceiptsSummary->SetDBValue($this->f("ReceiptsSummary"));
        $this->SalesOfficeId->SetDBValue(trim($this->f("SalesOfficeId")));
        $this->SalesOffice->SetDBValue($this->f("SalesOffice"));
    }
//End SetValues Method

} //End reportreceipts1DataSource Class @9-FCB6E20C

class clsRecordreportreceiptsSearch { //reportreceiptsSearch Class @136-BB04F62C

//Variables @136-9E315808

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

//Class_Initialize Event @136-95AA4AF8
    function clsRecordreportreceiptsSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record reportreceiptsSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "reportreceiptsSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_UserFullName = new clsControl(ccsTextBox, "s_UserFullName", "User Full Name", ccsText, "", CCGetRequestParam("s_UserFullName", $Method, NULL), $this);
            $this->s_SalesOrder = new clsControl(ccsListBox, "s_SalesOrder", "Sales Order", ccsInteger, "", CCGetRequestParam("s_SalesOrder", $Method, NULL), $this);
            $this->s_SalesOrder->DSType = dsTable;
            $this->s_SalesOrder->DataSource = new clsDBFuelSaver();
            $this->s_SalesOrder->ds = & $this->s_SalesOrder->DataSource;
            $this->s_SalesOrder->DataSource->SQL = "SELECT * \n" .
"FROM reportsales {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesOrder->BoundColumn, $this->s_SalesOrder->TextColumn, $this->s_SalesOrder->DBFormat) = array("Id", "SalesSummary", "");
            $this->s_SalesOfficeId = new clsControl(ccsListBox, "s_SalesOfficeId", "Sales Office Id", ccsInteger, "", CCGetRequestParam("s_SalesOfficeId", $Method, NULL), $this);
            $this->s_SalesOfficeId->DSType = dsTable;
            $this->s_SalesOfficeId->DataSource = new clsDBFuelSaver();
            $this->s_SalesOfficeId->ds = & $this->s_SalesOfficeId->DataSource;
            $this->s_SalesOfficeId->DataSource->SQL = "SELECT * \n" .
"FROM salesoffices {SQL_Where} {SQL_OrderBy}";
            list($this->s_SalesOfficeId->BoundColumn, $this->s_SalesOfficeId->TextColumn, $this->s_SalesOfficeId->DBFormat) = array("Id", "SalesOffice", "");
            $this->s_IsActive = new clsControl(ccsCheckBox, "s_IsActive", "s_IsActive", ccsInteger, "", CCGetRequestParam("s_IsActive", $Method, NULL), $this);
            $this->s_IsActive->CheckedValue = $this->s_IsActive->GetParsedValue(1);
            $this->s_IsActive->UncheckedValue = $this->s_IsActive->GetParsedValue(0);
            $this->reportreceiptsPageSize = new clsControl(ccsListBox, "reportreceiptsPageSize", "reportreceiptsPageSize", ccsText, "", CCGetRequestParam("reportreceiptsPageSize", $Method, NULL), $this);
            $this->reportreceiptsPageSize->DSType = dsListOfValues;
            $this->reportreceiptsPageSize->Values = array(array("", "Select Value"), array("5", "5"), array("10", "10"), array("25", "25"), array("100", "100"));
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_IsActive->Value) && !strlen($this->s_IsActive->Value) && $this->s_IsActive->Value !== false)
                    $this->s_IsActive->SetValue(false);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @136-5B86FCAC
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_UserFullName->Validate() && $Validation);
        $Validation = ($this->s_SalesOrder->Validate() && $Validation);
        $Validation = ($this->s_SalesOfficeId->Validate() && $Validation);
        $Validation = ($this->s_IsActive->Validate() && $Validation);
        $Validation = ($this->reportreceiptsPageSize->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_UserFullName->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_SalesOrder->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_SalesOfficeId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_IsActive->Errors->Count() == 0);
        $Validation =  $Validation && ($this->reportreceiptsPageSize->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @136-AB18B0EF
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_UserFullName->Errors->Count());
        $errors = ($errors || $this->s_SalesOrder->Errors->Count());
        $errors = ($errors || $this->s_SalesOfficeId->Errors->Count());
        $errors = ($errors || $this->s_IsActive->Errors->Count());
        $errors = ($errors || $this->reportreceiptsPageSize->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @136-B1B3394F
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
        $Redirect = "reportreceipts.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "reportreceipts.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @136-EE21691C
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
        $this->s_SalesOfficeId->Prepare();
        $this->reportreceiptsPageSize->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_UserFullName->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_SalesOrder->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_SalesOfficeId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_IsActive->Errors->ToString());
            $Error = ComposeStrings($Error, $this->reportreceiptsPageSize->Errors->ToString());
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
        $this->s_UserFullName->Show();
        $this->s_SalesOrder->Show();
        $this->s_SalesOfficeId->Show();
        $this->s_IsActive->Show();
        $this->reportreceiptsPageSize->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End reportreceiptsSearch Class @136-FCB6E20C

class clsRecordreceipts { //receipts Class @146-8C823F0F

//Variables @146-9E315808

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

//Class_Initialize Event @146-EC7F9277
    function clsRecordreceipts($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record receipts/Error";
        $this->DataSource = new clsreceiptsDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "receipts";
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
            $this->ReceiptNo = new clsControl(ccsTextBox, "ReceiptNo", "Receipt No", ccsInteger, "", CCGetRequestParam("ReceiptNo", $Method, NULL), $this);
            $this->ReceiptDate = new clsControl(ccsTextBox, "ReceiptDate", "Receipt Date", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("ReceiptDate", $Method, NULL), $this);
            $this->SalesOrder = new clsControl(ccsListBox, "SalesOrder", "Sales Order", ccsInteger, "", CCGetRequestParam("SalesOrder", $Method, NULL), $this);
            $this->SalesOrder->DSType = dsTable;
            $this->SalesOrder->DataSource = new clsDBFuelSaver();
            $this->SalesOrder->ds = & $this->SalesOrder->DataSource;
            $this->SalesOrder->DataSource->SQL = "SELECT * \n" .
"FROM reportsales {SQL_Where} {SQL_OrderBy}";
            list($this->SalesOrder->BoundColumn, $this->SalesOrder->TextColumn, $this->SalesOrder->DBFormat) = array("Id", "SalesSummary", "");
            $this->SalesOrder->Required = true;
            $this->ReceiptAmount = new clsControl(ccsTextBox, "ReceiptAmount", "Receipt Amount", ccsSingle, "", CCGetRequestParam("ReceiptAmount", $Method, NULL), $this);
            $this->ReceiptAmount->Required = true;
            $this->ReferenceId = new clsControl(ccsTextBox, "ReferenceId", "Reference Id", ccsText, "", CCGetRequestParam("ReferenceId", $Method, NULL), $this);
            $this->VerifiedAmount = new clsControl(ccsTextBox, "VerifiedAmount", "Verified Amount", ccsSingle, "", CCGetRequestParam("VerifiedAmount", $Method, NULL), $this);
            $this->VerifiedBy = new clsControl(ccsListBox, "VerifiedBy", "Verified By", ccsInteger, "", CCGetRequestParam("VerifiedBy", $Method, NULL), $this);
            $this->VerifiedBy->DSType = dsTable;
            $this->VerifiedBy->DataSource = new clsDBFuelSaver();
            $this->VerifiedBy->ds = & $this->VerifiedBy->DataSource;
            $this->VerifiedBy->DataSource->SQL = "SELECT * \n" .
"FROM users {SQL_Where} {SQL_OrderBy}";
            list($this->VerifiedBy->BoundColumn, $this->VerifiedBy->TextColumn, $this->VerifiedBy->DBFormat) = array("Id", "UserId", "");
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

//Initialize Method @146-4F76030F
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlId"] = CCGetFromGet("Id", NULL);
    }
//End Initialize Method

//Validate Method @146-AE2934F2
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->ReceiptNo->Validate() && $Validation);
        $Validation = ($this->ReceiptDate->Validate() && $Validation);
        $Validation = ($this->SalesOrder->Validate() && $Validation);
        $Validation = ($this->ReceiptAmount->Validate() && $Validation);
        $Validation = ($this->ReferenceId->Validate() && $Validation);
        $Validation = ($this->VerifiedAmount->Validate() && $Validation);
        $Validation = ($this->VerifiedBy->Validate() && $Validation);
        $Validation = ($this->IsActive->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->ReceiptNo->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReceiptDate->Errors->Count() == 0);
        $Validation =  $Validation && ($this->SalesOrder->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReceiptAmount->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ReferenceId->Errors->Count() == 0);
        $Validation =  $Validation && ($this->VerifiedAmount->Errors->Count() == 0);
        $Validation =  $Validation && ($this->VerifiedBy->Errors->Count() == 0);
        $Validation =  $Validation && ($this->IsActive->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @146-52CA5B46
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->ReceiptNo->Errors->Count());
        $errors = ($errors || $this->ReceiptDate->Errors->Count());
        $errors = ($errors || $this->SalesOrder->Errors->Count());
        $errors = ($errors || $this->ReceiptAmount->Errors->Count());
        $errors = ($errors || $this->ReferenceId->Errors->Count());
        $errors = ($errors || $this->VerifiedAmount->Errors->Count());
        $errors = ($errors || $this->VerifiedBy->Errors->Count());
        $errors = ($errors || $this->IsActive->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @146-288F0419
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

//InsertRow Method @146-C2EBA9DD
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->ReceiptNo->SetValue($this->ReceiptNo->GetValue(true));
        $this->DataSource->ReceiptDate->SetValue($this->ReceiptDate->GetValue(true));
        $this->DataSource->SalesOrder->SetValue($this->SalesOrder->GetValue(true));
        $this->DataSource->ReceiptAmount->SetValue($this->ReceiptAmount->GetValue(true));
        $this->DataSource->ReferenceId->SetValue($this->ReferenceId->GetValue(true));
        $this->DataSource->VerifiedAmount->SetValue($this->VerifiedAmount->GetValue(true));
        $this->DataSource->VerifiedBy->SetValue($this->VerifiedBy->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @146-8C41A2E2
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->ReceiptNo->SetValue($this->ReceiptNo->GetValue(true));
        $this->DataSource->ReceiptDate->SetValue($this->ReceiptDate->GetValue(true));
        $this->DataSource->SalesOrder->SetValue($this->SalesOrder->GetValue(true));
        $this->DataSource->ReceiptAmount->SetValue($this->ReceiptAmount->GetValue(true));
        $this->DataSource->ReferenceId->SetValue($this->ReferenceId->GetValue(true));
        $this->DataSource->VerifiedAmount->SetValue($this->VerifiedAmount->GetValue(true));
        $this->DataSource->VerifiedBy->SetValue($this->VerifiedBy->GetValue(true));
        $this->DataSource->IsActive->SetValue($this->IsActive->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @146-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @146-2E977469
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

        $this->SalesOrder->Prepare();
        $this->VerifiedBy->Prepare();

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
                    $this->ReceiptNo->SetValue($this->DataSource->ReceiptNo->GetValue());
                    $this->ReceiptDate->SetValue($this->DataSource->ReceiptDate->GetValue());
                    $this->SalesOrder->SetValue($this->DataSource->SalesOrder->GetValue());
                    $this->ReceiptAmount->SetValue($this->DataSource->ReceiptAmount->GetValue());
                    $this->ReferenceId->SetValue($this->DataSource->ReferenceId->GetValue());
                    $this->VerifiedAmount->SetValue($this->DataSource->VerifiedAmount->GetValue());
                    $this->VerifiedBy->SetValue($this->DataSource->VerifiedBy->GetValue());
                    $this->IsActive->SetValue($this->DataSource->IsActive->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->ReceiptNo->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReceiptDate->Errors->ToString());
            $Error = ComposeStrings($Error, $this->SalesOrder->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReceiptAmount->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ReferenceId->Errors->ToString());
            $Error = ComposeStrings($Error, $this->VerifiedAmount->Errors->ToString());
            $Error = ComposeStrings($Error, $this->VerifiedBy->Errors->ToString());
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
        $this->ReceiptNo->Show();
        $this->ReceiptDate->Show();
        $this->SalesOrder->Show();
        $this->ReceiptAmount->Show();
        $this->ReferenceId->Show();
        $this->VerifiedAmount->Show();
        $this->VerifiedBy->Show();
        $this->IsActive->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End receipts Class @146-FCB6E20C

class clsreceiptsDataSource extends clsDBFuelSaver {  //receiptsDataSource Class @146-AA038C14

//DataSource Variables @146-E6BC047C
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
    public $ReceiptNo;
    public $ReceiptDate;
    public $SalesOrder;
    public $ReceiptAmount;
    public $ReferenceId;
    public $VerifiedAmount;
    public $VerifiedBy;
    public $IsActive;
//End DataSource Variables

//DataSourceClass_Initialize Event @146-7AFAE48A
    function clsreceiptsDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record receipts/Error";
        $this->Initialize();
        $this->ReceiptNo = new clsField("ReceiptNo", ccsInteger, "");
        
        $this->ReceiptDate = new clsField("ReceiptDate", ccsDate, $this->DateFormat);
        
        $this->SalesOrder = new clsField("SalesOrder", ccsInteger, "");
        
        $this->ReceiptAmount = new clsField("ReceiptAmount", ccsSingle, "");
        
        $this->ReferenceId = new clsField("ReferenceId", ccsText, "");
        
        $this->VerifiedAmount = new clsField("VerifiedAmount", ccsSingle, "");
        
        $this->VerifiedBy = new clsField("VerifiedBy", ccsInteger, "");
        
        $this->IsActive = new clsField("IsActive", ccsInteger, "");
        

        $this->InsertFields["ReceiptNo"] = array("Name" => "ReceiptNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ReceiptDate"] = array("Name" => "ReceiptDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["SalesOrder"] = array("Name" => "SalesOrder", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["ReceiptAmount"] = array("Name" => "ReceiptAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["ReferenceId"] = array("Name" => "ReferenceId", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["VerifiedAmount"] = array("Name" => "VerifiedAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["VerifiedBy"] = array("Name" => "VerifiedBy", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
        $this->UpdateFields["ReceiptNo"] = array("Name" => "ReceiptNo", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ReceiptDate"] = array("Name" => "ReceiptDate", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["SalesOrder"] = array("Name" => "SalesOrder", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["ReceiptAmount"] = array("Name" => "ReceiptAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["ReferenceId"] = array("Name" => "ReferenceId", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["VerifiedAmount"] = array("Name" => "VerifiedAmount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["VerifiedBy"] = array("Name" => "VerifiedBy", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["IsActive"] = array("Name" => "IsActive", "Value" => "", "DataType" => ccsInteger);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @146-F755E9A7
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

//Open Method @146-078594BC
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM receipts {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @146-D3E4526D
    function SetValues()
    {
        $this->ReceiptNo->SetDBValue(trim($this->f("ReceiptNo")));
        $this->ReceiptDate->SetDBValue(trim($this->f("ReceiptDate")));
        $this->SalesOrder->SetDBValue(trim($this->f("SalesOrder")));
        $this->ReceiptAmount->SetDBValue(trim($this->f("ReceiptAmount")));
        $this->ReferenceId->SetDBValue($this->f("ReferenceId"));
        $this->VerifiedAmount->SetDBValue(trim($this->f("VerifiedAmount")));
        $this->VerifiedBy->SetDBValue(trim($this->f("VerifiedBy")));
        $this->IsActive->SetDBValue(trim($this->f("IsActive")));
    }
//End SetValues Method

//Insert Method @146-B09EA05C
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["ReceiptNo"]["Value"] = $this->ReceiptNo->GetDBValue(true);
        $this->InsertFields["ReceiptDate"]["Value"] = $this->ReceiptDate->GetDBValue(true);
        $this->InsertFields["SalesOrder"]["Value"] = $this->SalesOrder->GetDBValue(true);
        $this->InsertFields["ReceiptAmount"]["Value"] = $this->ReceiptAmount->GetDBValue(true);
        $this->InsertFields["ReferenceId"]["Value"] = $this->ReferenceId->GetDBValue(true);
        $this->InsertFields["VerifiedAmount"]["Value"] = $this->VerifiedAmount->GetDBValue(true);
        $this->InsertFields["VerifiedBy"]["Value"] = $this->VerifiedBy->GetDBValue(true);
        $this->InsertFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildInsert("receipts", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @146-9725587B
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["ReceiptNo"]["Value"] = $this->ReceiptNo->GetDBValue(true);
        $this->UpdateFields["ReceiptDate"]["Value"] = $this->ReceiptDate->GetDBValue(true);
        $this->UpdateFields["SalesOrder"]["Value"] = $this->SalesOrder->GetDBValue(true);
        $this->UpdateFields["ReceiptAmount"]["Value"] = $this->ReceiptAmount->GetDBValue(true);
        $this->UpdateFields["ReferenceId"]["Value"] = $this->ReferenceId->GetDBValue(true);
        $this->UpdateFields["VerifiedAmount"]["Value"] = $this->VerifiedAmount->GetDBValue(true);
        $this->UpdateFields["VerifiedBy"]["Value"] = $this->VerifiedBy->GetDBValue(true);
        $this->UpdateFields["IsActive"]["Value"] = $this->IsActive->GetDBValue(true);
        $this->SQL = CCBuildUpdate("receipts", $this->UpdateFields, $this);
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

//Delete Method @146-CDE00867
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM receipts";
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

} //End receiptsDataSource Class @146-FCB6E20C

//Include Page implementation @223-C1F5F4D3
include_once(RelativePath . "/headerIncludablePage.php");
//End Include Page implementation

//Include Page implementation @7-A6D0C5B5
include_once(RelativePath . "/menuincludablepage.php");
//End Include Page implementation

//Initialize Page @1-BF6AA1B2
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
$TemplateFileName = "reportreceipts.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.position.js|js/jquery/ui/jquery.ui.menu.js|js/jquery/ui/jquery.ui.autocomplete.js|js/jquery/autocomplete/ccs-autocomplete.js|js/jquery/ui/jquery.ui.datepicker.js|js/jquery/datepicker/ccs-date-timepicker.js|js/jquery/ui/jquery.ui.mouse.js|js/jquery/ui/jquery.ui.draggable.js|js/jquery/ui/jquery.ui.resizable.js|js/jquery/ui/jquery.ui.button.js|js/jquery/ui/jquery.ui.dialog.js|js/jquery/dialog/ccs-dialog.js|js/jquery/updatepanel/ccs-update-panel.js|";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-2B06A8A3
include_once("./reportreceipts_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-27224588
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
$reportreceipts1 = new clsGridreportreceipts1("", $MainPage);
$reportreceiptsSearch = new clsRecordreportreceiptsSearch("", $MainPage);
$Panel2 = new clsPanel("Panel2", $MainPage);
$Panel2->GenerateDiv = true;
$Panel2->PanelId = "ContentPanel1Panel2";
$receipts = new clsRecordreceipts("", $MainPage);
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
$MainPage->Panel1 = & $Panel1;
$MainPage->reportreceipts1 = & $reportreceipts1;
$MainPage->reportreceiptsSearch = & $reportreceiptsSearch;
$MainPage->Panel2 = & $Panel2;
$MainPage->receipts = & $receipts;
$MainPage->HeaderSidebar = & $HeaderSidebar;
$MainPage->headerIncludablePage = & $headerIncludablePage;
$MainPage->Menu = & $Menu;
$MainPage->MenuIncludablePage = & $MenuIncludablePage;
$Content->AddComponent("Panel1", $Panel1);
$Panel1->AddComponent("reportreceipts1", $reportreceipts1);
$Panel1->AddComponent("reportreceiptsSearch", $reportreceiptsSearch);
$Panel1->AddComponent("Panel2", $Panel2);
$Panel2->AddComponent("receipts", $receipts);
$HeaderSidebar->AddComponent("headerIncludablePage", $headerIncludablePage);
$Menu->AddComponent("MenuIncludablePage", $MenuIncludablePage);
$reportreceipts1->Initialize();
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

//Execute Components @1-F4B13411
$MasterPage->Operations();
$MenuIncludablePage->Operations();
$headerIncludablePage->Operations();
$receipts->Operation();
$reportreceiptsSearch->Operation();
//End Execute Components

//Go to destination page @1-A520E5BE
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBFuelSaver->close();
    header("Location: " . $Redirect);
    unset($reportreceipts1);
    unset($reportreceiptsSearch);
    unset($receipts);
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

//Unload Page @1-E3C04E30
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBFuelSaver->close();
unset($MasterPage);
unset($reportreceipts1);
unset($reportreceiptsSearch);
unset($receipts);
$headerIncludablePage->Class_Terminate();
unset($headerIncludablePage);
$MenuIncludablePage->Class_Terminate();
unset($MenuIncludablePage);
unset($Tpl);
//End Unload Page


?>
