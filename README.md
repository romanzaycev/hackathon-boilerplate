# Hackathon vagrant image

## Boxed:
* Debian 10
* Nginx 1.14.2
* Postgresql 11
* PHP 7.3.4
* Composer 1.8.6
* Nodejs 12.6.0
* NPM 6.9.0
* FFMPEG 4.1.3
* Python 3.7.3
* Beanstalkd 1.10
    * with Aurora 2.2
* Memcached 1.5.6

## Install:
```bash
mkdir hackathon
cd hackathon
git clone https://github.com/romanzaycev/hackathon-vg.git .
vagrant up
```

## Default settings:
* IP: 192.168.55.11
* Pgsql: vagrant:vagrant@192.168.55.11:54322
* Beanstalkd: *:11300
    * Aurora web interface: http://192.168.55.11:3011
