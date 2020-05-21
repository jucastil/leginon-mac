#!/bin/bash

echo "	-------------------------------------"
echo 
echo "	LEGINON DOCKER: Additional Configuration"
echo
echo " 	-------------------------------------"
tput setaf 1; 
echo "	IMPORTANT: passwords must be uniform or the installation may fail"
echo "	DEFAULT password is BIOLBIOL"
echo "	Please change all after a successful install"
tput sgr0;
echo " 	-------------------------------------"
echo  " "
echo "	Please change NOW the docker ROOT password to BIOLBIOL"
passwd
echo "	"
echo "	Now changing phpMyAdmin to password-protected, cookie-based "
\cp config.inc.php.phpMyAdmin.docker  /etc/phpMyAdmin/config.inc.php
echo "	"
echo " 	Please change NOW the MYSQL root password to BIOLBIOL"
mysqladmin -u root password
echo " 	Please test login on the phpMyAdmin web interface"
echo ""
echo "	Setting up my.cnf limits"	
\cp my.cnf.docker /etc/my.cnf 
echo ""
echo "	Installing missing packages. This may take some time "	

yum -y install nedit gedit net-tools torque-client torque-mom ImageMagick MySQL-python compat-gcc-34-g77 fftw3-devel gcc-c++ gcc-gfortran gcc-objc gnuplot grace gsl-devel libtiff-devel netpbm-progs numpy openmpi-devel opencv-python python-devel python-imaging python-matplotlib python-tools python-pip scipy wxPython xorg-x11-server-Xvfb libjpeg-devel zlib-devel
echo " "

### to have openmpi temporary and permanently available
echo "export PATH=\$PATH:/usr/lib64/openmpi/bin" >> /root/.bash_profile
export PATH=$PATH:/usr/lib64/openmpi/bin

echo " 	..packages installed"
echo  
echo "	-------------------------------------"
echo 
echo "	Database Configuration and checks"
echo
echo " 	-------------------------------------"
echo ""
echo "	"
echo " 	Setting up NOW the localhost password to BIOLBIOL"
echo "	Please edit THIS file to change it"
php -f leginon-db-config.php localhost BIOLBIOL
echo ""
echo " 	-------------------------------------"
echo 
echo "	Testing user usr_object, default password localhost password"
echo " "
echo " 	A MariaDB prompt will open, please"
echo "		copy: SHOW VARIABLES LIKE 'query%'; "
echo "		Inspect results, then" 
echo " 		copy: exit; " 
echo "	IMPORTANT: use semicolon \";\" after each command"
echo " "
mysql -u usr_object -p leginondb
echo " "
echo "	Intial configuration done. "
echo "  Starting the autoinstaller..."
echo " "
python centos7AutoInstallation.py
echo " "
echo "	Autoinstaller done. "
echo " 	Now please run the Web Tools Setup Wizard"
echo " "	
cp config.php.myamiweb.docker /var/www/html/myamiweb/config.php
chmod 777 /var/www/html/myamiweb
chmod 777 /var/www/html/myamiweb/config.php
echo " 	Open your browser to: "
echo "  "
echo "	http://DOCKER-IP/myamiweb/setup/index.php"
echo "	"
echo "	Database User: usr_object, Database password: BIOLBIOL"
echo "  "
echo " 	It is recommended to restart the docker first"
echo "  From outside: "
echo " "
echo " 	-------------------------------------"
tput setaf 1;
echo "	docker stop DOCKERNAME"
echo "	docker start DOCKERNAME"
echo ""
echo "	ssh in again and run /extra/start_redux.sh"
echo ""
echo " 	...REMEMBER THIS IS A DEFAULT CONFIGURATION"
tput sgr0;
echo " 	-------------------------------------"
echo ""
