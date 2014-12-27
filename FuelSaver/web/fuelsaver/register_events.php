<?php
//BindEvents Method @1-37F133AB
function BindEvents()
{
    global $users;
    $users->CCSEvents["AfterInsert"] = "users_AfterInsert";
}
//End BindEvents Method

//users_AfterInsert @8-F49FDF23
function users_AfterInsert(& $sender)
{
    $users_AfterInsert = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $users; //Compatibility
//End users_AfterInsert

//Login @29-CC4C3502
    global $CCSLocales;
    if ( !CCLoginUser( $Component->UserId->Value, $Component->UserPassword->Value)) {
        $Component->Errors->addError($CCSLocales->GetText("CCS_LoginError"));
        $Component->UserPassword->SetValue("");
        $users_AfterInsert = 0;
    }
//End Login

//Close users_AfterInsert @8-11208659
    return $users_AfterInsert;
}
//End Close users_AfterInsert


?>
