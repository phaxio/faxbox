#!/bin/sh
apt-get install -qq -y python-software-properties
add-apt-repository ppa:ondrej/php5 > /dev/null 2>&1
apt-get update
apt-get upgrade
apt-get install -qq -y php5-cli php5-fpm php5-intl php5-mcrypt php5-mysql php5-sqlite php5-curl git nginx
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
chmod +x /usr/local/bin/composer
mkdir -p /var/www
cd /var/www
[ -d faxbox ] || git clone https://jnankin@bitbucket.org/hackhouse/faxbox.git
cd faxbox
composer update
chmod -R 777 app/storage

mkdir -p userdata/public/images
chmod -R 777 userdata

cp scripts/faxbox-nginx.conf /etc/nginx/sites-enabled/default
sed -i 's,127.0.0.1:9000,/var/run/php5-fpm.sock,g' /etc/php5/fpm/pool.d/www.conf
sed -i 's,;listen.user = www-data,listen.user = nginx,g' /etc/php5/fpm/pool.d/www.conf
sed -i 's,;listen.group = www-data,listen.group = nginx,g' /etc/php5/fpm/pool.d/www.conf
/etc/init.d/nginx restart
/etc/init.d/php5-fpm restart