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
    <div class="atm-manager">
        <div class="atm-manager__welcome">
            <h2>ATM Manager</h2>
            <button type="button" class="js-settings-toggle">
                <span class="icon"></span>
                <span>Настройки</span>
            </button>
            <div class="errors"></div>
        </div>
        <div class="atm-manager__settings<?=$_REQUEST["test"] === "y" ? " active visible" : ""?>">
            <div class="atm-manager__settings--background js-settings-toggle"></div>
            <div class="atm-manager__settings--body">
                <div class="atm-manager__settings--controls">
                    <button type="button" class="js-settings-toggle">
                        <span>Закрыть</span>
                    </button>
                </div>
                <div class="atm-manager__settings--form-wrapper">
                    <fieldset class="atm-forms">
                        <legend class="atm-forms__header">Выберите банкоматы</legend>
                        <div class="atm-forms__list">
                            <?php foreach($arResult["ATM"]["ITEMS"] as $arItem): ?>
                                <div class="atm-form__wrapper">
                                    <form action="" method="post" class="js-form-ajax js-choose-atm">
                                        <div class="hidden">
                                            <input type="hidden" name="IS_AJAX" value="Y">
                                            <input type="hidden" name="INSTANCE" value="MANAGER">
                                            <input type="hidden" name="ACTION" value="CHANGE_ACTIVITY">
                                        </div>
                                        <div class="fields">
                                            <div class="input-group input-group-line">
                                                <div class="input-wrapper">
                                                    <span class="input-title"></span>
                                                    <input type="checkbox" name="ACTIVE"<?=$arItem["ACTIVE"] === "Y" ? " checked" : ""?>>
                                                </div>
                                                <div class="input-wrapper">
                                                    <span class="input-title">ID:</span>
                                                    <input type="text" name="ID" class="input-id" value="<?=$arItem["ID"]?>" readonly>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-wrapper">
                                                    <span class="input-title">Дата создания:</span>
                                                    <input type="text" name="DATE_CREATED" value="<?=$arItem["DATE_CREATED"]?>" disabled>
                                                </div>
                                                <div class="input-wrapper">
                                                    <span class="input-title">Последнее использование:</span>
                                                    <input type="text" name="ACTIVE_LAST" value="<?=$arItem["ACTIVE_LAST"]?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                    <div class="description">
                        <p>Чтобы добавить или удалить банкоматы нужно перейти в раздел - Администирование</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="atm-view">
        <?php if(!empty($arResult["ATM"]["ITEMS"])): ?>
            <?php global $ATM; ?>
            <div class="atm-view__header">
                <h3>Банкоматы</h3>
            </div>
            <div class="atm-view__list">
                <?php foreach($arResult["ATM"]["ITEMS"] as $arATM): ?>
                    <?php
                    if(empty($arATM["ACTIVE"]) || $arATM["ACTIVE"] !== "Y") continue;
                    $ATM->build($arATM["ID"]);
                    ?>
                <?php endforeach; ?>
                <div class="atm__empty">Банкоматы не выведены, перейдите в настройки</div>
            </div>
        <?php endif; ?>
    </div>
</section>