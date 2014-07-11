### author webber (web-ber12@yandex.ru)
version 0.2
визуальное создание и редактирование простых форм на основе сниппета eForm

### easyForm - визуальное создание и редактирование простых форм на основе сниппета eForm (модуль + сниппет)

### DONATE
---------
если считаете данный продукт полезным и хотите отблагодарить автора материально,
либо просто пожертвовать немного средств на развитие проекта - 
можете сделать это на любой удобный Вам электронный кошелек в системе <strong>Webmoney</strong><br>
<strong>WMR:</strong> R133161482227<br>
<strong>WMZ:</strong> Z202420836069<br>
с необязательной пометкой от кого и за что именно :)


### Состав:
---------
* 1. Папка assets/modules/easyForm
* 2. Папка assets/snippets/easyForm

### Установка:
---------
* 1. Скачиваем архив
* 2. Распаковываем содержимое и помещаем в корень сайта
* 3. создаем модуль с названием easyForm и кодом из соответствующего файла папки install
* 4. создаем сниппет с названием easyForm и кодом из соответствующего файла папки install


### Использование на сайте - тут вообще все просто
---------
[!easyForm? &formid=`f1`!] - где цифра после префикса f - это id формы из модуля

* 1 добавлен параметр &config в вызов (по умолчанию default), который вызывает соответствующий файл шаблонизации вывода формы
* 2 ООП
* 3 шаблонизация вывода формы на фронтэнд и в админку
* 4 возможности мультиязычности через подгрузку языкового массива в сниппете в переменную $eF->lang перед загрузкой метода $eF->Run()ж
* 5 возможности самостоятельно задавать названия таблиц для форм и полей форм, если стандартные уже заняты - как в сниппете, так и в модуле
 
 
остальные параметры задаются аналогично eForm
&tpl, &reportTpl и &to задавать не нужно - они формируются автоматом из того, что задано в модуле easyForm
все элементы формы стилизуются общими правилами css

### Пример работы
---------
<a href="http://evobabel.sitex.by/ru/about/">Пример работы</a>


### Сотрудничество:
---------
По вопросам сотрудничества обращайтесь на электронный ящик web-ber12@yandex.ru












