<?php
/**
 * @global $arResult array
 * @global $sClassCatalogPath string
 * @global $sTemplateCatalogPath string
 * @global $CORE object
 */

?>
<div class="atm-container">
    <input type="radio" name="atm" value="<?=$arResult["ID"]?>">
    <div class="atm">
        <div class="errors"></div>
        <div class="atm-header">
            <h2>ATM <?=$arResult["ID"]?></h2>
        </div>
        <div class="atm__screen">
            <div class="layer active" data-layout-id="auth"></div>
            <div class="layer" data-layout-id="actions">
                <ul>
                    <?php foreach($arResult["ACTIONS"] as $arAction): ?>
                        <li><?=$arAction["NAME"]?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>