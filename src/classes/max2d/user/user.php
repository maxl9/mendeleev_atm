<?php
namespace Max2D;

class User
{
    private static string $sCatalogNameData = "/data";
    public function __construct()
    {
        if(!file_exists($sCatalogData = $_SERVER["DOCUMENT_ROOT"] . $this->getCatalog())){
            mkdir($sCatalogData, 0775);
        }
    }
    public function create($id = "", $code = ""): array
    {
        global $CORE;
        $arResult = array(
            "STATUS" => "ERROR"
        );
        if(empty($id) || empty($code)){
            return $arResult;
        }else{
            $sCatalogName = $this->getCatalog();
            $arParams = array(
                "ID" => $id,
                "CODE" => $code,
                "MONEY" => array(
                    "BALANCE" => 100000,
                    "CACHE" => 0
                )
            );
            $CORE->writeFileJson($_SERVER["DOCUMENT_ROOT"] . $sCatalogName  . "/" . $arParams["ID"] . ".json", json_encode($arParams));
            $arResult["STATUS"] = "OK";
            $arResult["DATA"] = $arParams;
            return $arResult;
        }
    }

    public function update($id = "", $code = "", $arParams = array()): array
    {
        global $CORE;
        $arResult = array(
            "STATUS" => "ERROR"
        );
        if(empty($id) || empty($code) || empty($arParams)){
            return $arResult;
        }else{
            $arUser = $this->getData($id, $code);
            if((int)$arUser["DATA"]["CODE"] === (int)$code){
                $sCatalogName = $this->getCatalog();
                $CORE->writeFileJson($_SERVER["DOCUMENT_ROOT"] . $sCatalogName  . "/" . $arParams["ID"] . ".json", json_encode($arParams));
                $arResult["STATUS"] = "OK";
                $arResult["DATA"] = $arParams;
            }
            return $arResult;
        }
    }
    public function getCatalog(): string
    {
        global $CORE;
        return $CORE->getCatalogCurrent(__DIR__) . self::$sCatalogNameData;
    }
    public function getData($id = "", $code = ""): array
    {
        $arResult = array();
        if(empty($id) || empty($code)){
            $arResult["STATUS"] = "ERROR";
            $arResult["DESCRIPTION"] = "Не заполнен 'Счёт' или 'Pin-code'";
        }else{
            global $CORE;
            $sCatalogPath = $this->getCatalog();
            $sFilePath = $_SERVER["DOCUMENT_ROOT"] . $sCatalogPath . "/{$id}.json";
            if(file_exists($sFilePath)){
                $arItem = json_decode($CORE->readFileJson($sFilePath), true);
            }else{
                $arItem = $this->create($id, $code);
            }
            if((int)$arItem["CODE"] === (int)$code){
                $arResult["STATUS"] = "OK";
                $arResult["DATA"] = $arItem;
            }else{
                $arResult["STATUS"] = "ERROR";
                $arResult["DESCRIPTION"] = "Не верный код!";
            }
        }
        return $arResult;
    }
}