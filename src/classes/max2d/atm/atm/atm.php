<?php
namespace Max2D\ATM;

use Max2D\User;
class ATM
{
    private static bool $bFilesIsInclude = false;
    private static string $sCatalogNameData = "/data";
    private static string $sCatalogNameDevices = "/data/devices";
    private static string $sCatalogNameHistory = "/data/history";
    protected string $sTemplateName = "default";
    protected array $arResult = array();
    private array $arParamsATM = array(
        "BANKNOTES" => array(
            100 => 150,
            200 => 150,
            500 => 150,
            1000 => 150,
            2000 => 150,
            5000 => 100
        )
    );
    public function __construct($sTemplate = "default")
    {
        if($_REQUEST["IS_AJAX"] === "Y"){
            if($_REQUEST["INSTANCE"] === "ATM"){
                $arResponse = array(
                    "STATUS" => "OK",
                    "DESCRIPTION" => "",
                    "STEP" => "START"
                );

                $sRequestAction = mb_strtoupper($_REQUEST["ACTION"]);
                $sATMId = $_REQUEST["ID"];

                if(empty($sRequestAction) || empty($sATMId)){
                    $arResponse["STATUS"] = "ERROR";
                    unset($arResponse["STEP"]);
                    if(empty($sRequestAction)){
                        $arResponse["DESCRIPTION"] = "Не передано действие";
                    }else{
                        $arResponse["DESCRIPTION"] = "Не передан ID";
                    }
                }else{
                    if($sRequestAction === "START"){
                        $arResponse["STEP"] = "LOGIN";
                    }
                    if($sRequestAction === "LOGIN"){
                        if(is_numeric($_REQUEST["ATM_LOGIN"]) && mb_strlen($_REQUEST["ATM_LOGIN"]) === 6){
                            $arResponse["STEP"] = "AUTH";
                        }else{
                            $arResponse["STEP"] = $sRequestAction;
                            $arResponse["ALERT"] = "Не верно заполнен номер счёта";
                        }
                    }
                    if($sRequestAction === "AUTH"){
                        if(is_numeric($_REQUEST["ATM_CODE"]) && mb_strlen($_REQUEST["ATM_CODE"]) === 4){
                            if(is_numeric($_REQUEST["ATM_LOGIN"]) && mb_strlen($_REQUEST["ATM_LOGIN"]) === 6){

                                $oUser = new User();
                                $arUserData = $oUser->getData($_REQUEST["ATM_LOGIN"], $_REQUEST["ATM_CODE"]);
                                if($arUserData["STATUS"] === "ERROR"){
                                    $arResponse["STEP"] = $sRequestAction;
                                    $arResponse["ALERT"] = $arUserData["DESCRIPTION"];
                                }else{
                                    if(empty($arUserData["DATA"]["MONEY"]["BALANCE"]) || $arUserData["DATA"]["MONEY"]["BALANCE"] < 100){
                                        $arUserData["DATA"]["MONEY"]["BALANCE"] = 100000;
                                        $oUser->update($arUserData["DATA"]["ID"], $arUserData["DATA"]["CODE"], $arUserData["DATA"]);
                                    }
                                    $arResponse["STEP"] = "ACTIONS";
                                    $arResponse["USER_ID"] = $arUserData["DATA"]["ID"];
                                    $arResponse["AUTH"] = "Y";
                                    $arResponse["BALANCE"] = $arUserData["DATA"]["MONEY"]["BALANCE"];
                                }
                            }else{
                                $arResponse["STEP"] = "LOGIN";
                                $arResponse["ALERT"] = "Не верно заполнен номер счёта";
                            }
                        }else{
                            $arResponse["STEP"] = $sRequestAction;
                            $arResponse["ALERT"] = "Не верно заполнен pin-code";
                        }
                    }

                    if($sRequestAction === "WITHDRAWAL"){
                        $oUser = new User();
                        $arUserData = $oUser->getData($_REQUEST["ATM_LOGIN"], $_REQUEST["ATM_CODE"]);
                        if($arUserData["STATUS"] === "ERROR"){
                            $arResponse["STEP"] = "LOGIN";
                            $arResponse["ALERT"] = $arUserData["DESCRIPTION"];
                        }else{
                            $arResponse["USER_ID"] = $arUserData["DATA"]["ID"];
                            $arResponse["AUTH"] = "Y";
                            $iBalance = (int)$arUserData["DATA"]["MONEY"]["BALANCE"];

                            if(empty($_REQUEST["WITHDRAWAL_AMOUNT"])){
                                $arResponse["STEP"] = "WITHDRAWAL";
                            }else{
                                $iAmount = (int)$_REQUEST["WITHDRAWAL_AMOUNT"];
                                if($iAmount <= 100){
                                    $iAmount = 100;
                                }else if($iAmount >= 100000){
                                    $iAmount = 100000;
                                }else{
                                    $remains = $iAmount % 100;
                                    if($remains < 50){
                                        $iAmount -= $remains;
                                    }else{
                                        $iAmount += 100 - $remains;
                                    }
                                }

                                $arAtm = $this->getData($_REQUEST["ID"]);
                                if($iBalance < $iAmount){
                                    $arResponse["ALERT"] = "У вас недостаточно на счёте!";
                                    $arResponse["STEP"] = "WITHDRAWAL";
                                }else{
                                    $iAmountTemp = $iAmount;
                                    $arResponse["POPUP"] = array(
                                        "TYPE" => "WITHDRAWAL",
                                        "TRANSFER" => array(),
                                        "AMOUNT" => 0
                                    );
                                    function calcMoney(&$arBanknotes, &$amount, &$arResponse){
                                        $arNominals = array(5000, 2000, 1000, 500, 200, 100);
                                        foreach($arNominals as $nominal){
                                            $iBillsCount = (int)$arBanknotes[$nominal];
                                            $iBillsNeed = (int)($amount /  $nominal);
                                            $iBills = min($iBillsNeed, $iBillsCount);
                                            $arBanknotes[$nominal] -= $iBills;
                                            $amount -= $nominal * $iBills;
                                            $arResponse["POPUP"]["TRANSFER"][$nominal] = $iBills;
                                        }
                                    }
                                    calcMoney($arAtm["BANKNOTES"], $iAmountTemp, $arResponse);
                                    if($iAmountTemp > 0){
                                        $arResponse["ALERT"] = "В банкомате закончились деньги, снято со счёта: " . $iAmount - $iAmountTemp;
                                        $arUserData["DATA"]["MONEY"]["BALANCE"] -= $iAmount - $iAmountTemp;
                                        $arResponse["POPUP"]["AMOUNT"] = $iAmount - $iAmountTemp;
                                    }else{
                                        $arUserData["DATA"]["MONEY"]["BALANCE"] -= $iAmount;
                                        $arResponse["POPUP"]["AMOUNT"] = $iAmount;
                                    }
                                    $oUser->update($arUserData["DATA"]["ID"], $arUserData["DATA"]["CODE"], $arUserData["DATA"]);
                                    $arResponse["CONSOLE"]["REMAINS"] = $arAtm["BANKNOTES"];
                                    $arAtm["WITHDRAWAL"] += 1;
                                    $this->update($sATMId, $arAtm);
                                    $arResponse["BALANCE"] = $arUserData["DATA"]["MONEY"]["BALANCE"];
                                    $arResponse["STEP"] = "ACTIONS";
                                }
                            }
                        }
                    }

                    if($sRequestAction === "REPLENISHMENT"){
                        $arResponse["STEP"] = "ACTIONS";
                    }
                }

                echo json_encode($arResponse);
                die();
            }
        }else{
            if(!file_exists($sCatalogData = $_SERVER["DOCUMENT_ROOT"] . $this->getCatalog("data"))){
                mkdir($sCatalogData, 0775);
            }
            if(!file_exists($sCatalogData = $_SERVER["DOCUMENT_ROOT"] . $this->getCatalog("devices"))){
                mkdir($sCatalogData, 0775);
            }
            if(!file_exists($sCatalogData = $_SERVER["DOCUMENT_ROOT"] . $this->getCatalog("history"))){
                mkdir($sCatalogData, 0775);
            }
            $this->sTemplateName = $sTemplate;
            // Подключение файлов шаблона, после проверки не регистрироли их ранее
            if(self::$bFilesIsInclude === false) $this->includeFiles();
        }
    }

    public function getCatalog($sCatalogType = "class"): string
    {
        global $CORE;
        if($sCatalogType === "data"){
            return $CORE->getCatalogCurrent(__DIR__) . self::$sCatalogNameData;
        }elseif($sCatalogType === "devices"){
            return $CORE->getCatalogCurrent(__DIR__) . self::$sCatalogNameDevices;
        }elseif($sCatalogType === "history"){
            return $CORE->getCatalogCurrent(__DIR__) . self::$sCatalogNameHistory;
        }else{
            return $CORE->getCatalogCurrent(__DIR__);
        }
    }
    public function create($arParamsATM = array())
    {
        global $CORE;
        if(empty($arParamsATM)) $arParamsATM = $this->arParamsATM;
        $iATMCount = 0;
        $sCatalogName = $this->getCatalog("devices");

        foreach(scandir($_SERVER["DOCUMENT_ROOT"] . $sCatalogName) as $sFileName){
            if($sFileName === "." || $sFileName === "..") continue;
            $iATMCount++;
        }

        $id = rand(1001, 9999);
        if(file_exists($_SERVER["DOCUMENT_ROOT"] . $sCatalogName  . "/" . $id . ".json")){
            $id = rand(1001, 9999);
        }
        if(!file_exists($_SERVER["DOCUMENT_ROOT"] . $sCatalogName  . "/" . $id . ".json")){
            $arParamsATM["ID"] = $id;
            $sDate = date("d.m.Y H:i:s");
            $arParamsATM["DATE_CREATED"] = $sDate;
            $arParamsATM["ACTIVE_LAST"] = $sDate;
            $arParamsATM["ACTIVE"] = "N";
            $arParamsATM["WITHDRAWAL"] = 0;
            $arParamsATM["REPLENISHMENT"] = 0;

            $CORE->writeFileJson($_SERVER["DOCUMENT_ROOT"] . $sCatalogName  . "/" . $id . ".json", json_encode($arParamsATM));
            return $arParamsATM;
        }
    }

    public function update($id, $arParams = array()): array
    {
        global $CORE;
        $arResult = array(
            "STATUS" => "ERROR"
        );
        if(empty($id) || empty($arParams)){
            return $arResult;
        }else{
            $arParams["ACTIVE_LAST"] = date("d.m.Y H:i:s");
            $sCatalogName = $this->getCatalog("devices");
            $CORE->writeFileJson($_SERVER["DOCUMENT_ROOT"] . $sCatalogName  . "/" . $arParams["ID"] . ".json", json_encode($arParams));
            $arResult["STATUS"] = "OK";
            $arResult["DATA"] = $arParams;
        }
        return $arResult;
    }

    public function reset($id): array
    {
        $arResult = array("STATUS" => "ERROR");
        if(empty($id)) return array("STATUS" => "ERROR");
        $arItem = $this->getData($id);
        if(!empty($arItem)){
            $arItem["BANKNOTES"] = $this->arParamsATM["BANKNOTES"];
            $this->update($id, $arItem);
            $arResult["STATUS"] = "OK";
        }
        return $arResult;
    }
    public function build($id)
    {
        $this->arResult = $this->getData($id);
        $this->includeTemplate($this->sTemplateName);
    }

    public function getData($id): array
    {
        global $CORE;
        $arResult = array();

        $sCatalogName = $this->getCatalog("devices");
        if(file_exists($sPath = $_SERVER["DOCUMENT_ROOT"] . $sCatalogName . "/{$id}.json")){
            $arResult = json_decode($CORE->readFileJson($sPath), true);
        }
        return $arResult;
    }

    protected function includeTemplate($sTemplateName)
    {
        global $CORE, $arResult, $sClassCatalogPath, $sTemplateCatalogPath;
        $arResult = $this->arResult;
        $sClassCatalogPath = $CORE->getCatalogCurrent(__DIR__);
        $sTemplateCatalogPath = $sClassCatalogPath . "/templates/" . $sTemplateName;

        require $_SERVER["DOCUMENT_ROOT"] . "{$sTemplateCatalogPath}/template.php";

        // Подключение файлов шаблона, после проверки не регистрироли их ранее
        if(self::$bFilesIsInclude === false) $this->includeFiles();
    }

    private function includeFiles()
    {
        global $CORE;
        self::$bFilesIsInclude = true;
        $sClassCatalogPath = $CORE->getCatalogCurrent(__DIR__);
        $sTemplateCatalogPath = $sClassCatalogPath . "/templates/" . $this->sTemplateName;

        $CORE->addCss("{$sTemplateCatalogPath}/style.css");
        $CORE->addJs("{$sTemplateCatalogPath}/script.js");
    }

    // Пополнение
    private function replenishment()
    {

    }

    // Снятие
    private function withdrawal($iAmount = 0): array
    {
        $arResult = array(
            "STATUS" => "OK",
            "DESCRIPTION" => ""
        );
        if($iAmount < 100){
            $arResult["STATUS"] = "ERROR";
            $arResult["ERROR"] = "Сумма не можнт быть меньше 100";
            return $arResult;
        }else{

            return $arResult;
        }
    }
}