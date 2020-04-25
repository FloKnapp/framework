#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

DOCKER_COMMAND="docker"
DOCKER_COMPOSE_COMMAND="docker-compose"

FRAMEWORK_IMAGE="webasics/framework:latest"

if ! [ $(command -v "$DOCKER_COMMAND") ]; then
  printf "There is no Docker installed. Please install Docker to use this feature.\n"
  exit 1
fi;

function generateDirectoryStructure() {

  mkdir -p "$DIR"/../docker/rootfs/etc/nginx/sites-enabled
  cp "$DIR"/fixtures/app.nginx "$DIR"/../docker/rootfs/etc/nginx/sites-enabled/app.nginx

  mkdir -p "$DIR"/../docker/rootfs/etc/nginx/ssl
  cp "$DIR"/fixtures/Dockerfile "$DIR"/../docker/Dockerfile

}

function generateServerConfiguration() {

  SERVER_CONFIGURATION=$(cat "$DIR"/../bin/fixtures/app.nginx)
  sed -i -e 's/%hostname%/'"$1"'/g' "$DIR"/../docker/rootfs/etc/nginx/sites-enabled/app.nginx

}

function generateServerCertificates() {
  	sudo openssl req -subj "/C=DE/ST=BY/O=$1/CN=$1/emailAddress=admin\@$1/" -x509 -nodes -days 365 -newkey rsa:2048 -keyout "$DIR"/../docker/rootfs/etc/nginx/ssl/key.pem -out "$DIR"/../docker/rootfs/etc/nginx/ssl/full.pem &> /dev/null && sudo chmod 0777 "$DIR"/../docker/rootfs/etc/nginx/ssl/*
}

printf "Generating directory structure..."
generateDirectoryStructure
printf "Done.\n"

printf "Generating server configuration files..."
generateServerConfiguration app.local
printf "Done.\n"

printf "Generating server certificates..."
generateServerCertificates app\.local
printf "Done.\n"

docker build -t webasics/framework "$DIR"/../docker
docker run -p 443:443 -v "$DIR"/../app:/var/www webasics/framework

