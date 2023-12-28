<?php
use Max2D\Core\Core,
    Max2D\ATM\Manager,
    Max2D\ATM\ATM;
require_once __DIR__ . "/../include.php";
global $CORE, $ATM;
$CORE = new Core();
$ATM = new ATM();
$ATMManager = new Manager("admin");
if(!empty($_REQUEST["IS_AJAX"]) && $_REQUEST["IS_AJAX"] === "Y") die();
## OUTPUT HTML
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ATM</title>
    <link rel="stylesheet" href="../src/css/styles.css">
</head>
<body>
<div id="page">
    <header id="header">
        <div class="container">
            <div class="grid">
                <div class="col-3">
                    <h1>ATM</h1>
                </div>
                <div class="col-9">
                    <div class="menu-wrapper menu-top">
                        <ul>
                            <li class="active"><span>Администрирование</span></li>
                            <li><a href="../">Банкоматы</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <?php $ATMManager->includeTemplate(); ?>
        </div>
    </main>
    <footer id="footer">
        <div class="container">
            <div class="grid">
                <div class="col-12">
                    <span>ATM &copy 2023</span>
                </div>
            </div>
        </div>
        <div class="ui-sources hidden">
            <?php $CORE->includeFiles(); // Подключаем все зарегистрированные файлы скриптов и стилей ?>
        </div>
    </footer>
</div>
</body>
</html>