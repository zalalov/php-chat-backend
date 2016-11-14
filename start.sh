#!/bin/bash

sudo chmod +x .
docker run --rm --name bunq -v "$PWD":/var/www/html -p 8000:80 -it php:7.0-apache
