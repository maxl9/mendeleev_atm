<?php
namespace Max2D\ATM;

use Max2D\ATM\ATM;

class Manager
{
    private static string $sServiceCode = "12344321";
    private static bool $bFilesIsInclude = false;
    protected string $sTemplateName = "default";
    protected array $arResult = array();

    public function __construct($sTemplate = "default")
    {
        global $ATM;
        if($_REQUEST["IS_AJAX"] === "Y"){
            if($_REQUEST["INSTANCE"] === "MANAGER"){
                if($_REQUEST["ACTION"] === "CHANGE_ACTIVITY" && !empty($_REQUEST["ID"])){
                    $arConfig = isset($_COOKIE["ATM"]) ? json_decode($_COOKIE["ATM"], true) : array();
                    $arConfig["VISIBLE"][$_REQUEST["ID"]] = !empty($_REQUEST["ACTIVE"]) ? "Y" : "N";
                    setcookie("ATM", json_encode($arConfig), time() + 604800, "/", $_SERVER["HTTP_HOST"]);
                    if(!empty($_REQUEST["ACTIVE"])){
                        $ATM->build($_REQUEST["ID"]);
                    }
                }
            }
        }else{
            // Сброс конфигурации банкомата
            if($_REQUEST["INSTANCE"] === "ATM" && $_REQUEST["ACTION"] === "RESET" && $_REQUEST["CODE"] === self::$sServiceCode){
                if(!empty($_REQUEST["ID"])) $ATM->reset($_REQUEST["ID"]);
            }
            $this->sTemplateName = $sTemplate;
        }
    }

    protected function getItems()
    {
        $arItems = array();
        global $CORE, $ATM;
        $sATMCatalog = $ATM->getCatalog("devices");
        if(file_exists($_SERVER["DOCUMENT_ROOT"] . $sATMCatalog)){
            $iCount = 0;
            $iCountMinATM = 2;
            $arVisible = !empty($_COOKIE["ATM"]) ? json_decode($_COOKIE["ATM"], true) : array("VISIBLE" => array());
            $this->arResult["VISIBLE"] = $arVisible["VISIBLE"];
            foreach(scandir($sCatalog = $_SERVER["DOCUMENT_ROOT"] . $sATMCatalog) as $sFileName){
                if($sFileName === "." || $sFileName === "..") continue;
                $arItem = json_decode($CORE->readFileJson($sCatalog . "/" . $sFileName), true);
                if(isset($this->arResult["VISIBLE"][$arItem["ID"]])){
                    $arItem["ACTIVE"] = $this->arResult["VISIBLE"][$arItem["ID"]] === "Y" ? "Y" : "N";
                }
                $arItems[] = $arItem;
                $iCount++;
            }
            if($iCount < $iCountMinATM){
                for($i = 0; $i < $iCountMinATM; $i++){
                    $arItems[] = $ATM->create();
                }
            }
        }
        return $arItems;
    }
    public function includeTemplate($sTemplateName = "")
    {
        global $CORE, $arResult, $sClassCatalogPath, $sTemplateCatalogPath;
        $arResult = array();
        if(empty($sTemplateName)) $sTemplateName = $this->sTemplateName;
        $sClassCatalogPath = $CORE->getCatalogCurrent(__DIR__);
        $sTemplateCatalogPath = $sClassCatalogPath . "/templates/" . $sTemplateName;
        $arItems = $this->getItems();
        $arResult = $this->arResult;
        $arResult["MANAGER"] = array();
        $arResult["ATM"]["ITEMS"] = $arItems;
        try{
            include_once $_SERVER["DOCUMENT_ROOT"] . "{$sTemplateCatalogPath}/template.php";
        }catch(\Exception $error){
            $this->arResult["ERRORS"] = $error;
        }
        // Подключение файлов шаблона
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
}