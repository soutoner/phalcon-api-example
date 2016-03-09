# apiexample

REST API scaffolding for Phalcon 2 framework.

# Deployment

```
vagrant up # if it's not made
vagrant ssh
cd /vagrant/apiexample/www/
composer install
```

Note: always check `www/apiexample/.htaccess` to set the correct `APP_ENV` to `production`, `test` or `development`.
To avoid file overhead, `.env` file will be read only in `development` or `test`. In `production` the necessary
env variables must be set.

# Integrate PHPStorm with Phalcon

```
# pwd www/apiexample/
composer install
# It's easier to install composer in the Host machine, and install from there, because as XDebug is enabled in the Guest
# machine, composer will decrease its speed. 
```

Go to `Settings > Languages & Frameworks > PHP` and inside `Include Path` add the path 
`www/apiexample/vendor/phalcon/devtools/ide/<version de phalcon>`

# Run test

This will take care of creating the test database (inside `config.php`), seeding it, and run the tests.

```
# pwd: /vagrant/www/apiexample (guest)
sudo php vendor/bin/codecept run
```

# Migrations

## Create migration

```
# pwd: /vagrant/www/apiexample (guest)
sudo php vendor/bin/phinx create <MigrationName>
```

## Run migrations

```
# pwd: /vagrant/www/apiexample (guest)
php app/cli.php migrate
```

# Seeds 

## Create seeder

```
# pwd: /vagrant/www/apiexample (guest)
sudo php vendor/bin/phinx seed:create <Model>Seeder
```

## Run seeding

```
# pwd: /vagrant/www/apiexample (guest)
# It creates database if it's not created yet
php app/cli.php seed
```

# Vagrant environment

## Requirements

* Operating System: Windows, Linux, or OSX.
* Virtualbox >= 4.3.10
* Vagrant >= 1.4.1

## Steps

[Phalcon/Vagrant repository](https://github.com/phalcon/vagrant)

First you need a Git enabled terminal. Then you should clone this repository locally.

`$ git clone https://github.com/phalcon/vagrant.git`

For newer versions of Vagrant and VirtualBox you may need guest additions, so install the plugin:

```
# For Linux/OSX
$ vagrant plugin install vagrant-vbguest

# For Windows
$ vagrant plugin install vagrant-windows
```

Note: if instalation fails during `guest additions`, exec:

`sudo apt-get install zlib1g-dev`

## Enable PHPStorm debugging

```
# /etc/php5/apache2/conf.d/20-xdebug.ini

zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_mode=req
xdebug.remote_host=<IP cliente>
xdebug.remote_port=9000
xdebug.remote_autostart=1
```

# Easier domain name

While developing (remember to edit with `sudo`):

```
# /etc/hosts
...
192.168.50.4    dev.apiexample.com
...
```

So you will be able to enter `http://dev.apiexample.com/apiexample` and you will reach the server.

# Important facts

* `Phalcon-devtools` must remain from custom repository as it's modified to provide independent migrations between dev and test. 
* Always follow the naming convention, or you will get into troubles (namespaces, you know).
