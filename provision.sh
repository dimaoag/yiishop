#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

sudo usermod -a -G www-data vagrant

sudo apt-get update

sudo apt-get install -y software-properties-common

sudo apt-get -y install git

sudo apt-get -y install mc

sudo add-apt-repository ppa:nginx/stable
sudo apt-get update
sudo apt-get -y install nginx

sudo add-apt-repository ppa:ondrej/php
sudo apt-get update

sudo apt-get -y install php7.1 php7.1-cli php7.1-common php7.1-mysql php7.1-fpm php7.1-curl php7.1-gd php7.1-bz2 php7.1-mcrypt php7.1-json php7.1-zip php7.1-xml php7.1-imagick php7.1-tidy php7.1-mbstring php-redis php-memcached


echo "mysql-server mysql-server/root_password password 1111" | sudo debconf-set-selections
echo "mysql-server mysql-server/root_password_again password 1111" | sudo debconf-set-selections

apt-get -y install mysql-server

sed -i s/\;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/ /etc/php/7.1/fpm/php.ini
sed -i s/display_errors\ =\ Off/display_errors\ =\ On/ /etc/php/7.1/fpm/php.ini
sed -i s/max_execution_time\ =\ 30/max_execution_time\ =\ 300/ /etc/php/7.1/fpm/php.ini
sed -i s/listen\ =\ 127.0.0.1:9000/listen\ =\ \\/var\\/run\\/php\\/php7.1-fpm.sock/ /etc/php/7.1/fpm/pool.d/www.conf

sudo apt-get install phpmyadmin

sudo echo 'server {
    set $web "/var/www/yiishop/frontend/web";
    set $index "index.php";
    set $charset "utf-8";
    set $fcp "unix:/var/run/php/php7.1-fpm.sock";

    listen  80;
    server_name yiishop.com;
    root $web;

    charset $charset;

    location / {
        index  $index;
        try_files $uri $uri/ /$index?$args;
    }
    
    location ~ \.(js|css|png|jpg|gif|swf|ico|pdf)$ {
        try_files $uri = 404;
    }

    location ~ \.php {
        include fastcgi_params;

        fastcgi_split_path_info  ^(.+\.php)(.*)$;

        set $fsn /$index;
        if (-f $document_root$fastcgi_script_name){
            set $fsn $fastcgi_script_name;
        }

        fastcgi_pass   $fcp;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 256 16k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_temp_file_write_size 256k;

        fastcgi_param  SCRIPT_FILENAME  $document_root$fsn;
        fastcgi_param  PATH_INFO        $fastcgi_path_info;
        fastcgi_param  PATH_TRANSLATED  $document_root$fsn;
    }

    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }

    location /phpmyadmin {
           root /usr/share/;
           index index.php index.html index.htm;
           location ~ ^/phpmyadmin/(.+\.php)$ {
                   try_files $uri =404;
                   root /usr/share/;
                   fastcgi_pass unix:/var/run/php/php7.1-fpm.sock;
                   fastcgi_index index.php;
                   fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                   include /etc/nginx/fastcgi_params;
               }
           location ~* ^/phpmyadmin/(.+\.(jpg|jpeg|gif|css|png|js|ico|html|xml|txt))$ {
                   root /usr/share/;
               }
            }

           location /phpMyAdmin {
                   rewrite ^/* /phpmyadmin last;
        }

}' > /etc/nginx/sites-available/yiishop.com


echo 'zend_extension=xdebug.so
xdebug.remote_autostart=on
xdebug.remote_enable=on
xdebug.remote_handler="dbgp"
xdebug.remote_host="192.168.56.1"
xdebug.remote_port=9001
xdebug.remote_mode=req
xdebug.idekey="PHPSTORM"
' > /etc/php/7.1/mods-available/xdebug.ini

sudo service nginx restart
sudo service php7.1-fpm restart
sudo service mysql restart
echo '
----------------------------------------
DONE!
----------------------------------------
';

# su - vagrant -c "curl -s https://getcomposer.org/installer | php"
# rm -f /usr/local/bin/composer
# cp composer.phar /usr/local/bin/composer
# su - vagrant -c "composer global require "fxp/composer-asset-plugin:~1.1.1""
# su - vagrant -c "mkdir -p /home/vagrant/.config/composer"
# su - vagrant -c "composer config -g github-oauth.github.com e1a428cf16d4b75e38a2452f2bac8449c0e97d1e"
# chown -R vagrant:vagrant /home/vagrant/.composer

# sudo rm -rf /var/www/yii2-app-basic
# cd /var/www
# su - vagrant -c "composer create-project --no-interaction --prefer-dist yiisoft/yii2-app-basic /var/www/yii2-app-basic"
# echo '
# ----------------------------------------
# Installing Yii2 Basic
# ----------------------------------------
# ';
