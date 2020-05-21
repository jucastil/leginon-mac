# A docker Leginon on OSX
A leginon docker with myamiweb.  
The webserver is connected to a subnet (the **leginon** network).  
The **leginon** network is in principle indepented of the working subnet, but it can be the same.  
If you want to know more about leginon, please refer to the [Leginon Project Page](http://emg.nysbc.org/redmine/projects/leginon/wiki/Leginon_Homepage).  
Features:  
- CentOS 7 Docker Container , Apache 2.4 (w/ SSL), MariaDB 10.1
- PHP **5.6**, EXIM, ssh, phpMyAdmin, Git, Drush, NodeJS

We start with a clean OSX 10.15.3 (Catalina). 
To download docker for mac, follow the instructions](https://docs.docker.com/docker-for-mac/install/).   

## Donwload, install and first checks
- Choose a folder where the docker will lay  
- Download the docker: ``git clone https://github.com/jucastil/leginon-mac.git``  
- Go into the newly created folder **leginon-mac**, start the leginon docker.  
``./start-leginon.sh dockername hostname DOCKER-IP``.  
For example: ``./start-leginon.sh leginon leginon 127.0.0.1``     
- Check "dockername" is running ``docker ps -a`` should show it as running.
- Attach your shell to your container by running: ``docker exec -i -t dockername /bin/bash``
- Check you have **internet access** from inside. This is needed by the installer.``ping www.google.com``  
- Check the web server. Visit [https://DOCKER-IP:8443](https://DOCKER-IP:8443) for SSL or [http://DOCKER-IP:8080](http://DOCKER-IP:8080) for no SSL.
- Check phpMyadmin : visit [https://DOCKER-IP:8080/phpmyadmin](https://DOCKER-IP:8080/phpmyadmin)

## Configuration of myamiweb    
- Configuration is done with the files inside the **/extra** folder. Check that you can ``ls /extra`` from inside the docker.
-  Attach your shell to your container by running: ``docker exec -i -t dockername /bin/bash``
- cd to **/extra** and run ``./extra-config.sh``. Before running it, **you need to have a registration key**. Follow the instructions. The installation **needs your imput**. 

#### What ``./extra-config.sh`` does, in this order   
  * Setup all the root **PASSWORDS**. 
  * Copy __config.inc.php.phpMyAdmin.docker__ to protect the phpmyadmin web interface
  * Copy __my.cnf.docker__ to setup the right cache limits 
  * Install missing packages via yum  
  * Run the php script __leginon-db-config.php__ to setup the database (it can fail)
  * Run the  __centos7AutoInstallation.py__, a wrap over the official[Autoinstaller CentOS](http://emg.nysbc.org/redmine/projects/leginon/wiki/Autoinstaller_for_CentOS) available on the [Complete Install](http://emg.nysbc.org/redmine/projects/leginon/wiki/Complete_Installation) page.
  * My answers the questions GroEL and EMAN, Xmipp, Spider and Protomo is **N**  
  * Copy __config.php.myamiweb.docker__ to initialize myamiweb (it will be configured later)

### You should have by now a fully functional Leginon docker.

## IMPORTANT remarks

- There is **no data share** mapped inside the container.   
If you want to do that, simply edit **start-leginon.sh** and start a new instance.
- Once these scripts are done, one needs to run the Web Tools Setup, by opening [https://DOCKER-IP/myamiweb/setup](https://DOCKER-IP/myamiweb/setup).  
NOTE that this is visible only in the same subnet. NOTE that not all the options were tested.
- Users and password are usually the main source of issues at installation time, if you ask me. Please be careful :-) 

## Configuration of myamiweb    
- Configuration is done with the files inside the **/extra** folder, as in CentOS 7.
- Ssh to your container, cd to **extra** and run ``./extra-config.sh``. Before running it, **you need to have a registration key**. Follow the instructions.  The installation **needs your imput**. Since we are running inside the docker, the script does the same than for the CentOS 7 install. Please refer to that explanation if you have doubts. 

### You should have by now a fully functional Leginon docker.

## IMPORTANT remarks

- There is **no data share** mapped inside the container.  
If you want to do that, simply edit **start-sbleginon.sh** and start a new instance.
- Once these scripts are done, one needs to run the Web Tools Setup, by opening [https://DOCKER-IP/myamiweb/setup](https://DOCKER-IP/myamiweb/setup).  
NOTE that this is visible only in the same subnet. NOTE that not all the options were tested.
- Users and password are usually the main source of issues at installation time, if you ask me. Please be careful :-) 
