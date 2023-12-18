<?php
use Max2D\Core\Core,
    Max2D\ATM\Manager;
require_once __DIR__ . "/include.php";
global $CORE;
$CORE = new Core();

## OUTPUT HTML
?><!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>ATM</title>
        <link rel="stylesheet" href="src/css/styles.css">
    </head>
    <body>
        <div id="page">
            <header id="header">
                <div class="container">
                    <h1>ATM</h1>
                </div>
            </header>
            <main>
                <div class="container">
                    <?php $ATMManager = new Manager(); ?>
                </div>
            </main>
            <footer id="footer">
                <div class="container"></div>
                <div class="ui-sources hidden">
                    <?php $CORE->includeFiles(); // Подключаем все зарегистрированные файлы скриптов и стилей ?>
                </div>
            </footer>
        </div>
    </body>
</html>