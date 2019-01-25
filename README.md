Голландский аукцион

php 7.0
yii2
mysql

Все необходимые пакеты устанавливались с помощью композера
Все таблицы в БД создавались с помощью миграций console/migration
Инициализация прав доступа console\controllers\RbacController.php

Отправляю  уже все в инициализированном виде.

В корне проекта скрипт по созданию БД и таблиц bargaining.sql

основной контроллер для продавца и покупателя >> frontend\controllers\LotController.php
модели тут >> common\models

Настройки apache
в файле httpd-vhosts.conf

<VirtualHost *:80>
    ServerName test1
    ServerAlias www.test1
    DocumentRoot "<путь до проекта>/bargaining/frontend/web/"
    Alias /admin "<путь до проекта>/bargaining/backend/web/"
    <Directory  "<путь до проекта>/bargaining/">
        Options +Indexes +Includes +FollowSymLinks +MultiViews
        AllowOverride All
	#Require host localhost
    </Directory>
    <FilesMatch \.(htaccess|htpasswd|svn|git)>
        Deny from all
        Satisfy All
    </FilesMatch>
</VirtualHost>

в файле C:\Windows\System32\drivers\etc\hosts добавить строку
127.0.0.1     www.test1

www.test1 - стартовая страница содержит список всех товаров
www.test1/lot/index - страница для продавца с его товарами
	Продавец может просматривать товары, редактировать их, закрывать 
www.test1/lot/purchase - страница для покупателя с приобретенными товарами
	Покупатель может купить товар 

Админка 
www.test1/admin/ - доступ есть только у пользователя с ролью admin 
	Админ может просматривать товары, редактировать их, закрывать (все как у продавца)
	