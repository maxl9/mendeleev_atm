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
    <div class="errors"></div>
    <div class="atm-manager">
        <div class="atm-manager__welcome">
            <h2>ATM Manager</h2>
            <button type="button" class="js-settings-open">
                <span class="icon"></span>
                <span>Настройки</span>
            </button>
        </div>
        <?php /** Настройки не готовы ?>
            <div class="atm-manager__settings<?=$_REQUEST["test"] === "y" ? " active visible" : ""?>">
                <div class="atm-manager__settings--background js-settings-close"></div>
                <div class="atm-manager__settings--body">
                    <div class="atm-manager__settings--controls">
                        <button type="button js-settings-close">
                            <span>Закрыть</span>
                        </button>
                    </div>
                    <div class="atm-manager__settings--form-wrapper">
                        <form action="">
                            <div class="form__body">
                                <div class="form-field">
                                    <label>
                                        <span>Сервисный код</span>
                                        <input type="text" name="FIELDS[CODE]" value="0000">
                                    </label>
                                </div>
                            </div>
                            <div class="form__footer">
                                <button type="submit">
                                    <span>Сохранить</span>
                                </button>
                                <button type="reset">
                                    <span>Сбросить</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php /** Конец блока настроек*/ ?>
    </div>
    <?php if($arResult["STATUS"] === "OK"): ?>
        <div class="atm-list">
            <h3>ATM's</h3>
            <?php foreach($arResult["ATM"] as $oATM): ?>
                <?php $oATM->build(); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>