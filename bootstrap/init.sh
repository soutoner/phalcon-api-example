#!/bin/bash
# Using Trusty64 Ubuntu

#
# Add PHP, Phalcon, PostgreSQL and libsodium repositories
#
apt-add-repository -y ppa:ondrej/php5-5.6
apt-add-repository -y ppa:phalcon/stable
apt-add-repository -y ppa:chris-lea/libsodium
touch /etc/apt/sources.list.d/pgdg.list
echo -e "deb http://apt.postgresql.org/pub/repos/apt/ trusty-pgdg main" | tee -a /etc/apt/sources.list.d/pgdg.list > /dev/null
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add -

rm /var/lib/apt/lists/* -f
apt-get update
apt-get install -y build-essential software-properties-common python-software-properties

#
# Setup locales
#
echo -e "LC_CTYPE=en_US.UTF-8\nLC_ALL=en_US.UTF-8\nLANG=en_US.UTF-8\nLANGUAGE=en_US.UTF-8" | tee -a /etc/environment > /dev/null
locale-gen en_US en_US.UTF-8
dpkg-reconfigure locales

export LANGUAGE=en_US.UTF-8
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8

#
# Hostname
#
hostnamectl set-hostname phalcon-vm

#
# MySQL with root:<no password>
#
export DEBIAN_FRONTEND=noninteractive
apt-get -q -y install mysql-server-5.6 mysql-client-5.6 php5-mysql

#
# Apache
#
apt-get install -y apache2 libapache2-mod-php5


#
# PHP
#
apt-get install -y php5 php5-cli php5-dev php-pear php5-mcrypt php5-curl php5-intl php5-xdebug php5-gd php5-imagick php5-imap php5-mhash php5-xsl
php5enmod mcrypt intl curl

# Update PECL channel
pecl channel-update pecl.php.net

#
# Apc
#
apt-get -y install php-apc php5-apcu
echo 'apc.enable_cli = 1' | tee -a /etc/php5/mods-available/apcu.ini > /dev/null

#
# Memcached
#
apt-get install -y memcached php5-memcached php5-memcache

#
# Beanstalkd
#
apt-get -y install beanstalkd

#
# YAML
#
apt-get install libyaml-dev
(CFLAGS="-O1 -g3 -fno-strict-aliasing"; pecl install yaml < /dev/null &)
echo 'extension = yaml.so' | tee /etc/php5/mods-available/yaml.ini > /dev/null
php5enmod yaml

#
# Utilities
#
apt-get install -y curl htop git dos2unix unzip vim grc gcc make re2c libpcre3 libpcre3-dev lsb-core autoconf

#
# Libsodium
#
apt-get install -y libsodium-dev
pecl install -a libsodium < /dev/null &
echo 'extension=libsodium.so' | tee /etc/php5/mods-available/libsodium.ini > /dev/null
php5enmod libsodium

#
# Zephir
#
git clone --depth=1 git://github.com/phalcon/zephir.git
cd zephir
./install -c

#
# Install Phalcon Framework
#
git clone --depth=1 git://github.com/phalcon/cphalcon.git
cd cphalcon
zephir build
echo -e "extension=phalcon.so" | tee /etc/php5/mods-available/phalcon.ini > /dev/null
php5enmod phalcon
cd ..

#
# Redis
#
# Allow us to remote from Vagrant with port
#
apt-get install -y redis-server redis-tools php5-redis
cp /etc/redis/redis.conf /etc/redis/redis.bkup.conf
sed -i 's/bind 127.0.0.1/bind 0.0.0.0/' /etc/redis/redis.conf
service redis-server restart

#
# MySQL configuration
# Allow us to remote from Vagrant with port
#
cp /etc/mysql/my.cnf /etc/mysql/my.bkup.cnf
# Note: Since the MySQL bind-address has a tab character I comment out the end line
sed -i 's/bind-address/bind-address = 0.0.0.0#/' /etc/mysql/my.cnf

#
# Grant all privilege to root for remote access
#
mysql -u root -Bse "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '' WITH GRANT OPTION;"
service mysql restart

#
# Composer for PHP
#
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

#
# Apache VHost
#
cd ~
echo '<VirtualHost *:80>
        DocumentRoot /vagrant/www
        ErrorLog  /vagrant/www/projects-error.log
        CustomLog /vagrant/www/projects-access.log combined
</VirtualHost>

<Directory "/vagrant/www">
        Options Indexes Followsymlinks
        AllowOverride All
        Require all granted
</Directory>' > vagrant.conf

mv vagrant.conf /etc/apache2/sites-available
a2enmod rewrite


#
# Install Phalcon DevTools
#
cd /home/vagrant/
git clone https://github.com/phalcon/phalcon-devtools.git
cd phalcon-devtools
sudo ln -s /home/vagrant/phalcon-devtools/phalcon.php /usr/bin/phalcon
sudo chmod ugo+x /usr/bin/phalcon

#
# Update PHP Error Reporting
#
sudo sed -i 's/short_open_tag = Off/short_open_tag = On/' /etc/php5/apache2/php.ini
sudo sed -i 's/error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT/error_reporting = E_ALL/' /etc/php5/apache2/php.ini
sudo sed -i 's/display_errors = Off/display_errors = On/' /etc/php5/apache2/php.ini
#  Append session save location to /tmp to prevent errors in an odd situation..
sudo sed -i '/\[Session\]/a session.save_path = "/tmp"' /etc/php5/apache2/php.ini

#
# Reload apache
#
sudo a2ensite vagrant
sudo a2dissite 000-default
sudo service apache2 restart
sudo service mongodb restart

#
#  Cleanup
#
sudo apt-get autoremove -y

sudo usermod -a -G www-data vagrant

echo -e "----------------------------------------"
echo -e "To create a Phalcon Project:\n"
echo -e "----------------------------------------"
echo -e "$ cd /vagrant/www"
echo -e "$ phalcon project <projectname>\n"
echo -e
echo -e "Then follow the README.md to copy/paste the VirtualHost!\n"

echo -e "----------------------------------------"
echo -e "Default Site: http://192.168.50.4"
echo -e "----------------------------------------"
