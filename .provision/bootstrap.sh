#!/usr/bin/env bash


# =====


# Nginx
if [ ! -f "/home/vagrant/nginx.lock" ]; then
sudo apt-get -y install nginx
sudo service nginx start
touch /home/vagrant/nginx.lock
fi

sudo rm -rf /etc/nginx/sites-enabled/vagrant.conf
sudo yes | cp -f /vagrant/.provision/nginx/vagrant.conf /etc/nginx/sites-available/vagrant.conf
sudo chmod 644 /etc/nginx/sites-available/vagrant.conf
sudo ln -s /etc/nginx/sites-available/vagrant.conf /etc/nginx/sites-enabled/vagrant.conf
sudo service nginx restart


# =====


# Postgresql
PG_CONF="/etc/postgresql/11/main/postgresql.conf"
PG_HBA="/etc/postgresql/11/main/pg_hba.conf"

if [ ! -f "/home/vagrant/postgresql.lock" ]; then
sudo apt-get install -y wget
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
RELEASE=$(lsb_release -cs)
echo "deb http://apt.postgresql.org/pub/repos/apt/ ${RELEASE}"-pgdg main | sudo tee /etc/apt/sources.list.d/pgdg.list
sudo apt-get update
sudo apt-get -y install postgresql-11

sudo sed -i "s/#listen_addresses = 'localhost'/listen_addresses = '*'/" "$PG_CONF"
sudo echo "host    all             all             all                     md5" >> "$PG_HBA"
sudo service postgresql restart

cat << EOF | su - postgres -c psql
    -- Create the database user:
    CREATE USER vagrant WITH PASSWORD 'vagrant';
    -- Create the database:
    CREATE DATABASE vagrant WITH OWNER=vagrant
                                  LC_COLLATE='en_US.utf8'
                                  LC_CTYPE='en_US.utf8'
                                  ENCODING='UTF8'
                                  TEMPLATE=template0;
EOF
touch /home/vagrant/postgresql.lock
fi

sudo rm -rf /etc/postgresql/11/main/conf.d/vagrant.conf
sudo yes | cp -f /vagrant/.provision/postgresql/vagrant.conf /etc/postgresql/11/main/conf.d/vagrant.conf
sudo service postgresql restart


# =====


# PHP
if [ ! -f "/home/vagrant/php.lock" ]; then
sudo apt-get install -y git unzip php php-common php-cli php-fpm php-memcached php-json php-pdo php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath php-pgsql
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === file_get_contents('https://composer.github.io/installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
touch /home/vagrant/php.lock
fi

sudo rm -rf /etc/php/7.3/cli/conf.d/vagrant.ini
sudo yes | cp -f /vagrant/.provision/php/cli/vagrant.ini /etc/php/7.3/cli/conf.d/vagrant.ini


# =====


# Nodejs
if [ ! -f "/home/vagrant/nodejs.lock" ]; then
sudo apt-get install -y curl
curl -sL https://deb.nodesource.com/setup_12.x | sudo bash -
sudo apt-get install -y nodejs
touch /home/vagrant/nodejs.lock
fi


# =====


# FFMPEG
if [ ! -f "/home/vagrant/ffmpeg.lock" ]; then
sudo apt-get update
sudo apt-get install -y ffmpeg
touch /home/vagrant/ffmpeg.lock
fi


# =====


# Beanstalkd
if [ ! -f "/home/vagrant/beanstalkd.lock" ]; then
sudo apt-get update
sudo apt-get install -y beanstalkd
touch /home/vagrant/beanstalkd.lock
fi


# =====


# Aurora
if [ ! -f "/home/vagrant/aurora.lock" ]; then
mkdir /home/vagrant/aurora
sudo apt-get update
sudo apt-get install -y wget
wget --quiet -O /home/vagrant/aurora/dist.tar.gz https://github.com/xuri/aurora/releases/download/2.2/aurora_linux_amd64_v2.2.tar.gz
tar  -C /home/vagrant/aurora -xzf /home/vagrant/aurora/dist.tar.gz
rm -rf /home/vagrant/aurora/dist.tar.gz
touch /home/vagrant/aurora.lock
fi
sudo rm -rf /home/vagrant/aurora/aurora.toml
sudo yes | cp -f /vagrant/.provision/aurora/aurora.toml /home/vagrant/aurora/aurora.toml
sudo chown -R vagrant:vagrant /home/vagrant/aurora/
sudo su - vagrant -c "nohup /home/vagrant/aurora/aurora > /home/vagrant/aurora/aurora.log 2>&1 &"


# =====


# Memcached
if [ ! -f "/home/vagrant/memcached.lock" ]; then
sudo apt-get update
sudo apt-get install -y memcached
touch /home/vagrant/memcached.lock
fi
sudo rm -rf /etc/memcached.conf
sudo yes | cp -f /vagrant/.provision/memcached/memcached.conf /etc/memcached.conf
sudo service memcached restart


# =====


# PIP
if [ ! -f "/home/vagrant/pip.lock" ]; then
sudo apt-get update
sudo apt-get install -y python3-pip
touch /home/vagrant/pip.lock
fi


# =====


# Default config
if [ ! -f "/home/vagrant/hackathon/config/.env" ]; then
cp /home/vagrant/hackathon/config/.env.dist /home/vagrant/hackathon/config/.env
sudo chown vagrant:vagrant /home/vagrant/hackathon/config/.env
fi


# =====


# Roadrunner started by default
if [ -f "/home/vagrant/hackathon/start-dev.sh" ]; then
cd /home/vagrant/hackathon/
sudo chmod +x /home/vagrant/hackathon/start-dev.sh
sudo -u vagrant ./start-dev.sh
fi

