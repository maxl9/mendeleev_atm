<?php
/**
 * @global $arResult array
 * @global $sClassCatalogPath string
 * @global $sTemplateCatalogPath string
 * @global $CORE object
 */

$CORE->addJs("{$sTemplateCatalogPath}/settings/settings.js");
$CORE->addCss("{$sTemplateCatalogPath}/settings/settings.css");
$CORE->addJs("{$sTemplateCatalogPath}/popup/popup.js");
$CORE->addCss("{$sTemplateCatalogPath}/popup/popup.css");
?>
<section class="atm-section">
    <div class="popup">
        <div class="popup--background js-popup-close"></div>
        <div class="popup__container">
            <div class="popup-header">
                <h3>Вывод</h3>
                <span class="js-popup-close">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4014 16.3998C16.6943 16.1069 17.1692 16.107 17.462 16.3999L24.0013 22.9406L30.5435 16.3998C30.8364 16.1069 31.3113 16.107 31.6042 16.3999C31.897 16.6928 31.897 17.1677 31.6041 17.4606L25.0619 24.0013L31.6033 30.5427C31.8962 30.8356 31.8962 31.3105 31.6033 31.6034C31.3105 31.8963 30.8356 31.8963 30.5427 31.6034L24.0013 25.062L17.462 31.6026C17.1692 31.8955 16.6943 31.8956 16.4014 31.6027C16.1084 31.3098 16.1084 30.835 16.4013 30.542L22.9407 24.0013L16.4013 17.4604C16.1084 17.1675 16.1084 16.6926 16.4014 16.3998Z" fill="#1F2238"/>
                    </svg>
                </span>
            </div>
            <div class="popup-content">
                <?php $arBanknotes = array(100, 200, 500, 1000, 2000, 5000); ?>
                <div class="transfer-container">
                    <ul>
                        <?php foreach($arBanknotes as $sBanknote): ?>
                            <li data-nominal="<?=$sBanknote?>">
                                <span><?=$sBanknote?></span>
                                <span><img src="<?=$sTemplateCatalogPath?>/images/<?=$sBanknote?>.jpg" alt="Купюра <?=$sBanknote?>"></span>
                                <span data-transfer-count="0"></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <hr>
                    <div class="transfer-total" data-transfer-type="Всего снято: " data-transfer-amount="0"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="atm-manager">
        <div class="atm-manager__welcome">
            <h2>ATM Manager</h2>
            <button type="button" class="js-settings-toggle">
                <span class="icon"></span>
                <span>Настройки</span>
            </button>
            <div class="errors"></div>
        </div>
        <div class="atm-manager__settings<?php // =$_REQUEST["test"] === "y" ? " active visible" : ""?>">
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