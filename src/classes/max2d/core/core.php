<?php
namespace Max2D\Core;

class Core
{
    private static array $arFiles = array();

    public function __contruct(): void
    {
        // Возможно что-то будет
    }

    // Вывод файлов из массива
    public function includeFiles(): void
    {
        foreach(self::$arFiles as $sFile) echo $sFile;
    }

    // Регистрируем файлы в массив
    private function registerFile($sPath = ""): bool
    {
        $sPath = trim($sPath);
        if(empty($sPath)) return false;
        if(!in_array($sPath, self::$arFiles)) self::$arFiles[] = $sPath;
        return true;
    }

    public function addJs($sPath = ""): bool
    {
        $sPath = trim($sPath);
        if(empty($sPath)) return false;
        $this->registerFile("<script src=\"{$sPath}\"></script>");
        return true;
    }

    public function addCss($sPath = ""): bool
    {
        $sPath = trim($sPath);
        if(empty($sPath)) return false;
        $this->registerFile("<link rel=\"stylesheet\" href=\"{$sPath}\">");
        return true;
    }

    // Регистрируем файлы в массив
    public function getCatalogCurrent($sPath = ""): string
    {
        return preg_replace("/" . preg_replace("/\//", "\/", $_SERVER["DOCUMENT_ROOT"]) . "/", "" , $sPath) ?: "";
    }

}