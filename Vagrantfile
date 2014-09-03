# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'socket'
require 'yaml'
require 'pp'

def local_ip
  orig, Socket.do_not_reverse_lookup = Socket.do_not_reverse_lookup, true  # turn off reverse DNS resolution temporarily

  UDPSocket.open do |s|
    s.connect '64.233.187.99', 1
    s.addr.last
  end
ensure
  Socket.do_not_reverse_lookup = orig
end

Vagrant::Config.run do |config|
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"
  config.vm.network :bridged,  { :auto_config => false, :bridge => "eth0"}
  config.vm.forward_port 80, 8080
  config.vm.share_folder("src", "/var/www/faxbox", "../faxbox")
  config.ssh.max_tries = 150
end

$script = <<SCRIPT
    #!/bin/bash
    echo "Installing faxbox..."
    apt-get update
    apt-get upgrade
    apt-get install -qq -y python-software-properties
    add-apt-repository ppa:ondrej/php5
    apt-get update
    apt-get upgrade
    apt-get install -qq -y php5-cli php5-fpm php5-intl php5-mcrypt php5-mysql php5-sqlite php5-curl git nginx curl zip
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    chmod +x /usr/local/bin/composer

    cd /var/www/faxbox
    composer update
    chmod -R 777 app/storage
    chmod -R 777 app/config/production
    chmod -R 777 app/database
    chmod -R 777 public/images
    cp scripts/faxbox-nginx.conf /etc/nginx/sites-enabled/default
    sed -i 's,127.0.0.1:9000,/var/run/php5-fpm.sock,g' /etc/php5/fpm/pool.d/www.conf
    sed -i 's,;listen.user = www-data,listen.user = nginx,g' /etc/php5/fpm/pool.d/www.conf
    sed -i 's,;listen.group = www-data,listen.group = nginx,g' /etc/php5/fpm/pool.d/www.conf
    sed -i 's,user = www-data,user = vagrant,g' /etc/php5/fpm/pool.d/www.conf
    sed -i 's,group = www-data,group = vagrant,g' /etc/php5/fpm/pool.d/www.conf
    /etc/init.d/nginx restart
    /etc/init.d/php5-fpm restart

    echo "Installing ngrok..."
    cd /tmp
    wget "https://api.equinox.io/1/Applications/ap_pJSFC5wQYkAyI0FIVwKYs9h1hW/Updates/Asset/ngrok.zip?os=linux&arch=386&channel=stable"
    unzip ngrok.zip* -d /usr/local/bin
    chmod a+x /usr/local/bin/ngrok

    echo "Installing mysql..."
    debconf-set-selections <<< 'mysql-server mysql-server/root_password password faxbox'
    debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password faxbox'
    apt-get -y install mysql-server
    mysql -u root -e "create database if not exists faxbox;" --password=faxbox
SCRIPT

Vagrant.configure("2") do |config|
  config.vm.provision "shell", inline: $script
  config.vm.provider "virtualbox" do |v|
    v.customize ["modifyvm", :id, "--memory", "1028"]
    v.customize ["modifyvm", :id, "--cpus", "2"]
  end
end

