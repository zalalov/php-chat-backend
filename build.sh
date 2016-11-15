#!/bin/bash

set -e

sudo chmod -R 777 src/data
docker build -t bunq .
