#!/bin/bash

set -e

docker run --rm --name bunq -p 8000:80 -it bunq
