#!/bin/bash

set -e

if [ -z $1 ]; then
  echo 'cmd required'
  exit -1
fi

docker run --rm -it -v $PWD:/app -w /app composer $@