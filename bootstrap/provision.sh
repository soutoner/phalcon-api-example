#!/bin/bash
# Using Trusty64 Ubuntu

function valid_ip()
{
    local  ip=$1
    local  stat=1

    if [[ $ip =~ ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$   ]]; then
        OIFS=$IFS
        IFS='.'
        ip=($ip)
        IFS=$OIFS
        [[ ${ip[0]} -le 255 && ${ip[1]} -le 255 \
            && ${ip[2]} -le 255 && ${ip[3]} -le 255 ]]
        stat=$?
    fi
    return $stat
}

#######################

USAGE_TOOL="Usage: $0 <LOCAL_IP> <PROJECT_NAME>"

if [ "$EUID" -ne 0  ]
then echo "Please run as root"
    exit
fi

if [ -z "$1"   ]
then echo "$USAGE_TOOL"
    exit
fi

if [ -z "$2"   ]
then echo "$USAGE_TOOL"
    exit
fi

if valid_ip $1;
then
    echo "good";
else
    echo "Bad IP address"
    exit;
fi

#
# Add PHP 5.6, Phalcon repositories
#
apt-add-repository -y ppa:ondrej/php5-5.6
apt-add-repository -y ppa:phalcon/stable

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
apt-get install -y apache2 libapache2-mod-php5 openssl


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

# XDebug

read -r -d '' XDEBUG_CONF << ROTO
;zend_extension='xdebug.so'
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_mode=req
xdebug.remote_host=$1
xdebug.remote_port=9000
xdebug.remote_log=/var/log/xdebug.log
xdebug.remote_autostart=1
ROTO

echo "$XDEBUG_CONF" > /etc/php5/apache2/conf.d/20-xdebug.ini

service apache2 restart

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
# Install Phalcon Framework
#
apt-get install -y php5-dev libpcre3-dev gcc make
git clone git://github.com/phalcon/cphalcon.git
cd cphalcon/build
sudo ./install
echo -e "extension=phalcon.so" | tee /etc/php5/mods-available/phalcon.ini > /dev/null
php5enmod phalcon

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
echo "<VirtualHost *:80>
        DocumentRoot /vagrant/$2
        ErrorLog  /vagrant/$2/log/projects-error.log
        CustomLog /vagrant/$2/log/projects-access.log combined
</VirtualHost>

<Directory \"/vagrant/$2\">
        Options Indexes Followsymlinks
        AllowOverride All
        Require all granted
</Directory>" > vagrant.conf

mv vagrant.conf /etc/apache2/sites-available
a2enmod rewrite

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

#
# Setting aliases to avoid xdebug for composer
#
read -r -d '' ALIASES << ROTO
# Load xdebug Zend extension with php command
alias php='php -dzend_extension=xdebug.so'
# PHPUnit needs xdebug for coverage. In this case, just make an alias with php command prefix.
alias phpunit='php $(which phpunit)'
ROTO

echo "$ALIASES" >> /home/vagrant/.bash_aliases

echo "alias sudo='sudo '" >> /home/vagrant/.bashrc

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
