# docker

## create network

    docker network create regitra-network

## rebuild image if needed

    docker build -t regitra/clients .docker/clients



# development

## xdebug

## xdebug

Must write correct ip address in to xdebug.ini and restart docker container.

To get your container ip address you may write from outside you container:

    docker inspect regitra_clients

Activization for cmd line:

    export XDEBUG_SESSION=PHPSTORM
    export PHP_IDE_CONFIG="serverName=clients.dv"

"clients.dv" is written in nginx config at .docker/web/nginx/conf.d/default.conf  
at line: server_name clients.dv;

also "clients.dv" is written in file docker-compose.yml 
at line: PHP_IDE_CONFIG: 'serverName=clients.dv' 

## running

After docker is started, write your browser address field: http://localhost:8088 .

Before may need to run 
    
    chmod 777 cache

