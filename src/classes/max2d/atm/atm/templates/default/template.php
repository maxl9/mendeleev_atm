<?php
/**
 * @global $arResult array
 * @global $sClassCatalogPath string
 * @global $sTemplateCatalogPath string
 * @global $CORE object
 */

?>
<div class="atm-container" data-atm-id="<?=$arResult["ID"]?>">
    <input type="radio" name="atm" value="<?=$arResult["ID"]?>">
    <div class="atm">
        <div class="errors"></div>
        <div class="atm-header">
            <h3>ATM <?=$arResult["ID"]?></h3>
        </div>
        <div class="atm__screen-wrapper">
            <div class="atm__screen">
                <div class="atm-form-wrapper">
                    <form action="" method="post" class="js-form-ajax js-atm">
                        <div type="text" class="prop-balance" data-value="0">Баланс: </div>
                        <div class="hidden">
                            <input type="hidden" name="IS_AJAX" value="Y">
                            <input type="hidden" name="INSTANCE" value="ATM">
                            <input type="hidden" name="ACTION" value="START">
                            <input type="hidden" name="ID" value="<?=$arResult["ID"]?>">
                        </div>
                        <div class="atm-screen__layouts">
                            <div class="atm-screen__layout active" data-layout-id="start">
                                <button type="button" class="js-atm-navigation" data-nav-direction="next">
                                    <span>Войти</span>
                                </button>
                            </div>
                            <div class="atm-screen__layout" data-layout-id="login">
                                <input type="tel" name="ATM_LOGIN" minlength="6" maxlength="6" required autocomplete="on" class="js-digital" placeholder="Введите номер счёта">
                                <button type="button" class="js-atm-navigation" data-nav-direction="next">
                                    <span>Продолжить</span>
                                </button>
                            </div>
                            <div class="atm-screen__layout" data-layout-id="auth">
                                <input type="tel" name="ATM_CODE" minlength="4" maxlength="4" required  autocomplete="off" class="js-digital" placeholder="Введите pin-code">
                                <button type="button" class="js-atm-navigation" data-nav-direction="next">
                                    <span>Войти</span>
                                </button>
                            </div>
                            <div class="atm-screen__layout" data-layout-id="actions">
                                <div class="fields-column">
                                    <button type="button" class="js-atm-navigation" data-nav-direction="next" data-form-action="withdrawal">
                                        <span>Снять</span>
                                    </button>
                                    <button type="button" class="js-atm-navigation" data-nav-direction="next" data-form-action="replenishment" disabled>
                                        <span>Пополнить</span>
                                    </button>
                                </div>
                                <div class="fields-column">
                                    <button type="reset" class="js-reset">
                                        <span>Выйти</span>
                                    </button>
                                </div>
                            </div>
                            <div class="atm-screen__layout" data-layout-id="withdrawal">
                                <input type="tel" min="100" step="100" name="WITHDRAWAL_AMOUNT" class="js-amount" placeholder="Сумма для снятия">
                                <button type="button" class="js-atm-navigation" data-nav-direction="next">
                                    <span>Снять</span>
                                </button>
                            </div>
                            <div class="atm-screen__layout" data-layout-id="replenishment"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>