<?php
/**
 * @global $arResult array
 * @global $sClassCatalogPath string
 * @global $sTemplateCatalogPath string
 * @global $CORE object
 */

$CORE->addJs("{$sTemplateCatalogPath}/settings/settings.js");
$CORE->addCss("{$sTemplateCatalogPath}/settings/settings.css");
?>
<section class="atm-section">
    <div class="form-wrapper">
        <form action="">
            Введите сервисный код <br>
            <br>
            <input type="tel" name="" placeholder="Сервисный код">
        </form>
    </div>
</section>