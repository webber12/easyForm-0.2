### author webber (web-ber12@yandex.ru)
version 0.1
визуальное создание и редактирование простых форм на основе сниппета eForm



### easyForm - визуальное создание и редактирование простых форм на основе сниппета eForm (модуль + сниппет)

### Состав:
---------
* 1. Папка assets/modules/easyForm
* 2. Папка assets/snippets/easyForm

### Установка:
---------
* 1. Скачиваем архив со сниппетом
* 2. Распаковываем содержимое и помещаем в корень сайта
* 3. создаем модуль с названием easyForm и кодом require_once MODX_BASE_PATH."assets/modules/easyForm/module.easyForm.php";
* 4. создаем сниппет с названием easyForm и кодом 
return require_once MODX_BASE_PATH."assets/snippets/easyForm/snippet.easyForm.php";



### Использование на сайте - тут вообще все просто
---------
[!easyForm? &formid=`f1`!] - где цифра после префикса f - это id формы из модуля

* 1 добавлен параметр &config в вызов (по умолчанию default), который вызывает соответствующий файл шаблонизации вывода формы

остальные параметры задаются аналогично eForm
&tpl, &reportTpl и &to задавать не нужно - они формируются автоматом из того, что задано в модуле easyForm
все элементы формы стилизуются общими правилами css

### Пример работы
---------
<a href="http://evobabel.sitex.by/ru/about/">Пример работы</a>












