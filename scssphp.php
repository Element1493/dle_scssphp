<?php
/*
 * Автор модуля: Сергей Зверев <element1493@yandex.ru>
 * Библиотека: scssphp 1.10.0 [https://scssphp.github.io/scssphp/]
 * Версия модуля: 1.0.0 (30.01.2022)
 */

/**
 * @global array $member_id
 * @global array $config
 */

if (!defined('DATALIFEENGINE')) die("Go fuck yourself!");

// Для уменьшения нагрузки на сайт выполняем код только если пользователь авторизован как администратор.
if ($member_id['user_group'] != 1) return;

// Список SCSS файлов для обработки (через запятую).
$fileScss = (!empty($scss))?str_ireplace('{THEME}','templates/'.$config['skin'],$scss):'templates/'.$config['skin'].'/scss/styles.scss';
// Путь для скомпилированного файла стилей.
$fileCss = (!empty($css))?str_ireplace('{THEME}','templates/'.$config['skin'],$css):'templates/'.$config['skin'].'/css/styles.css';
// Минифицировать css файл.
$outputStyle = isset($outputStyle) ? true : false;
// Проверка на изменения SCSS файлов.
$scssHash = isset($scssHash) ? true : false;
// Путь для @import
$importPaths = isset($importPaths) ? $importPaths : false;
// Генерировать  sourceMap.
$sourceMap = isset($sourceMap) ? $sourceMap : false;

// Подключаем класс-обёртку компилятора.
require_once 'scssphp.class.php';
// Компилим в соответсвии с параметрами.
$compile = new dleScssCompiler($fileScss, $fileCss, $outputStyle, $importPaths, $sourceMap, $scssHash);
$result = $compile->compile();

$error = '';
if (!empty($result['error'])) {
	if (!empty($result['error']['files'])){
		$error = $error.'<div>dleScssPHP: Не удалось найти файл: '.$result['error']['files'].'</div>';
	}
	if (!empty($result['error']['compiler'])) {
		$error = $error.'<div>dleScssPHP: Не удалось скомпилировать: '.$result['error']['compiler'].'</div>';
	}
}
echo $error;



