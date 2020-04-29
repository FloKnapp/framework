#!/bin/bash

SCRIPT_PATH=$(dirname `which $0`)

nohup php -S localhost:8000 -t $SCRIPT_PATH/www &>> $SCRIPT_PATH/server.log &
echo -n $!