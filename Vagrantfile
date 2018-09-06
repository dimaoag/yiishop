# -*- mode: ruby -*-
# vi: set ft=ruby :

box      = "ubuntu/xenial64"
box_url  = "https://cloud-images.ubuntu.com/xenial/current/xenial-server-cloudimg-amd64-vagrant.box"
hostname = "yiishop.com"
www_dst  = "/var/www/yiishop"
www_src  = "./"
ip       = "192.168.56.111"
ram      = "2048"

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = box

  config.vm.box_url = box_url

  config.vm.host_name = hostname

  config.vm.network "private_network", ip: ip
  
#  config.vm.network "forwarded_port", guest: 3306, host: 3306
#  config.vm.network "forwarded_port", guest: 80, host: 8080

  config.vm.synced_folder www_src, www_dst, owner: "www-data", group: "www-data"

  config.vm.provider "virtualbox" do |vb|
    vb.customize [
      "modifyvm", :id,
      "--name", hostname,
      "--memory", ram
    ]
  end

  config.vm.provision "shell", path: "./provision.sh"

end
