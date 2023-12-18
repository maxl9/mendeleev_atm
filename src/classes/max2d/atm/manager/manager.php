<?php
namespace Max2D\ATM;

class Manager
{
    private static bool $bFilesIsInclude = false;
    protected array $arResult = array();

    public function __construct($sTemplate = "default")
    {
        $this->arResult["ID"] = rand(10, 99);
        $this->includeTemplate($sTemplate);
    }

    protected function includeTemplate($sTemplateName)
    {
        global $CORE, $arResult, $sClassCatalogPath, $sTemplateCatalogPath;
        $arResult = $this->arResult;
        $sClassCatalogPath = $CORE->getCatalogCurrent(__DIR__);
        $sTemplateCatalogPath = $sClassCatalogPath . "/templates/" . $sTemplateName;

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
        global $CORE, $sTemplateCatalogPath;
        self::$bFilesIsInclude = true;

        $CORE->addCss("{$sTemplateCatalogPath}/style.css");
        $CORE->addJs("{$sTemplateCatalogPath}/script.js");
    }
}