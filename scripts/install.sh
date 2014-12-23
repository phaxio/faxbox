#!/bin/bash
apt-get update
apt-get -y upgrade

export DEBIAN_FRONTEND=noninteractive
apt-get install -qq -y python-software-properties

if [[ `cat /etc/issue` == *"12.04"* ]]
then
    add-apt-repository -y ppa:ondrej/php5
    apt-get update
    apt-get -y upgrade
fi

#need to add a line in /etc/hosts for hostname, or sendmail takes forever
if [[ `cat /etc/hosts` != *"$(hostname)"* ]]
    then
    echo "127.0.0.1 $(hostname)" >> /etc/hosts
    echo "Added line for $(hostname) to /etc/hosts"
fi


apt-get install -qq -y php5-cli php5-fpm php5-intl php5-mcrypt php5-mysql php5-sqlite php5-curl git nginx htop sendmail

if [[ `cat /etc/issue` == *"14.04"* ]]
then
    php5enmod mcrypt
fi

curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
chmod +x /usr/local/bin/composer
mkdir -p /var/www
cd /var/www
[ -d faxbox ] || git clone https://bitbucket.org/hackhouse/faxbox.git
cd faxbox
composer install
chmod -R 777 app/storage

mkdir -p userdata/public/images
chmod -R 777 userdata

cp scripts/faxbox-nginx.conf /etc/nginx/sites-enabled/default
sed -i 's,127.0.0.1:9000,/var/run/php5-fpm.sock,g' /etc/php5/fpm/pool.d/www.conf
sed -i 's,;listen.user = www-data,listen.user = nginx,g' /etc/php5/fpm/pool.d/www.conf
sed -i 's,;listen.group = www-data,listen.group = nginx,g' /etc/php5/fpm/pool.d/www.conf
/etc/init.d/nginx restart

if [[ `cat /etc/issue` == *"12.04"* ]]
then
    /etc/init.d/php5-fpm restart
elif [[ `cat /etc/issue` == *"14.04"* ]]
then
    service php5-fpm restart
fi



