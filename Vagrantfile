# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "debian/buster64"
  # config.vm.box_check_update = false
  # config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "forwarded_port", guest: 5432, host: 54322, host_ip: "127.0.0.1" # Postgresql
  config.vm.network "private_network", ip: "192.168.55.11"

  config.vm.synced_folder "public/", "/srv/vagrant_public"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  config.vm.provider "virtualbox" do |vb|
    vb.gui = false
    vb.memory = "1024"
  end

  config.vm.provision :shell, :run => 'always', :path => ".provision/bootstrap.sh"
end
