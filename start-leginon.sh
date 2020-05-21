#!/bin/bash

EXPECTED_ARGS=3
if [[ $# -ne $EXPECTED_ARGS ]]; then 
	echo "";
	echo "    USAGE: ./start-sbleginon.sh NAME HOSTNANE IP";
	echo "";
	exit 0
fi

NAME=$1
HOSTNAME=$2
IP=$3

function run_leginon {

    docker run --name $NAME --hostname=$HOSTNAME \
    -e DISPLAY=host.docker.internal:0 \
    -d -p $IP:80:80 \
    -p $IP:443:443 \
    -p $IP:2222:22 \
    -p $IP:3306:3306 \
    -v `pwd`/extra:/extra \
    -v `pwd`/html:/var/www/html \
    -v `pwd`/database:/var/lib/phpMyAdmin/upload \
    -t andrewklau/centos-lamp
}

if [[ "$(docker images -q andrewklau/centos-lamp 2> /dev/null)" == "" ]]; then
    echo "  Pulling docker image"
    docker pull andrewklau/centos-lamp
    echo "  Image installed, starting the run"
    run_leginon
else
    echo "  Image is present, checking if there is a docker with that name"
    if [ ! "$(docker ps -q -f name=$NAME)" ]; then
        echo "  There is a docker with the given name : checking status"
        if [ "$(docker ps -aq -f status=exited -f name=$NAME)" ]; then
            ## cleanup
            echo "  The docker is present but not running"
            read -r -p "  Would you like to remove it? (y/N) " response
            case "$response" in
                [yY][eE][sS]|[yY])
                    docker rm $NAME
                    echo "  docker removed: run the script again to create it anew"
                    ;;
                *)
                    echo "  Starting the existing docker "
                    docker start $NAME
                    echo "  Started : please check your docker"
                    ;;
            esac
        else
            echo "  No docker with the given name : creating it"
            ## run the container with the given parameters
            run_leginon
        fi
    else
        echo "  docker seems to be running already : nothing to do :-)"
    fi
fi

