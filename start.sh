#!/bin/bash

docker run --name bunq -v "$PWD":/var/www/html -p 8000:80 -d php:7.0-apache
