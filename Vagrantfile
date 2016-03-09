# -*- mode: ruby -*-
# vi: set ft=ruby :

# Don't touch unless you know what you're doing!

Vagrant.configure(2) do |config|

  # Base Box
  # --------------------
  config.vm.box = "ubuntu/trusty64"

  # Connect to IP
  # Note: Use an IP that doesn't conflict with any OS's DHCP (Below is a safe bet)
  # --------------------
  config.vm.network :private_network, ip: "192.168.50.4"

  # Forward to Port
  # --------------------
  #config.vm.network :forwarded_port, guest: 80, host: 8080

  # Optional (Remove if desired)
  config.vm.provider :virtualbox do |vb|
    # How much RAM to give the VM (in MB)
    # -----------------------------------
    vb.memory = "2048"
  end

  # Provisioning Script
  # --------------------
  config.vm.provision "shell", path: "bootstrap/provision.sh", args: ["127.0.0.1", "apiexample"]

  # Synced Folder
  # --------------------
  config.vm.synced_folder ".", "/vagrant/", :mount_options => [ "dmode=777", "fmode=666" ]
  config.vm.synced_folder "./apiexample", "/vagrant/apiexample/", :mount_options => [ "dmode=775", "fmode=644" ], :owner => 'www-data', :group => 'www-data'
end
