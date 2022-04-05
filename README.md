# dle_scssphp
![version](https://img.shields.io/badge/version-1.0.2-orange "Version")
![dle](https://img.shields.io/badge/DLE-15%3C-green "DLE")
[![MIT License](https://img.shields.io/badge/license-MIT-blue "MIT License")](https://github.com/Element1493/dle_scssphp/blob/main/LICENSE)

Данный модуль c помощью библиотеки [SCSSPHP](https://github.com/leafo/scssphp/) автоматически компилирует файлы SCSS в файл CSS.

## Инструкция
1. Создаем папку, например: **SCSS** в `/engine/modules/` и в данную папку загружаем содержимое из архива.
2. В шаблоне находим файл **main.tpl** и в начале прописать: `{include file="engine/modules/SCSS/scssphp.php"}`
3. Чтобы модуль начал компилировать необходимо создать в шаблоне папку с файлом: `/scss/styles.scss`, но если у вас несколько файлов или расположен(ы) в другой папке то дописываем в `{include file="engine/modules/SCSS/scssphp.php"}` свой путь к файлу, например: `{include file="engine/modules/SCSS/scssphp.php?scss={THEME}/scss/styles.scss"}`, если несколько то прописываем через запятую: `{include file="engine/modules/SCSS/scssphp.php?scss={THEME}/scss/styles1.scss,{THEME}/scss/styles2.scss"}`
4. По умолчанию компилируемый файл CSS создаётся в шаблоне: `/css/styles.css`, также предусмотрен параметр для указа нового пути для файла CSS: `{include file="engine/modules/SCSS/scssphp.php?css={THEME}/css/styles.css"}`
> :warning: **ВНИМАНИЕ:**
> 
> Для параметров предусмотрен только тег: `{THEME}`, при попытке добавить другие теги приведёт к ошибке.

## Параметры
|Название|Описание|По умолчанию|
|-|-|-|
|**scss**|Список файлов SCSS для обработки, каждый новый путь к файлу прописываем через запятую.|`{THEME}/scss/styles.scss`|
|**css**|Путь к файлу CSS|`{THEME}/css/styles.css`|
|**outputStyle**|Сжать выходной файл CSS|`false`|
|**scssHash**|Компилировать в файл CSS, только после изменение одного или несколько файлов SCSS (Для снижения нагрузки рекомендую включить)|`false`|
|**importPaths**|Если файлы для импорта **@import** находятся в другой папке, то указав путь при компиляции подхватить их из указанной папке|`false`|
|**sourceMap**|Генерировать  sourceMap, в папку с компилированным файлом CSS|`false`|

### Пример:
`{include file="engine/modules/SCSS/scssphp.php?scss={THEME}/scss/styles1.scss,{THEME}/scss/styles2.scss&css={THEME}/css/styles.css&outputStyle=true&scssHash=true&sourceMap=true"}`

## Ссылки:
1. [Библиотека SCSSPHP](https://github.com/leafo/scssphp/)
2. [Документация по SCSS](https://sass-lang.com/documentation)
