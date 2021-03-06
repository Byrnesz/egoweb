#!/bin/sh
mkdir -p assets
chmod 777 assets
mkdir -p ${PWD}/protected/runtime
chmod 777 ${PWD}/protected/runtime
if [ ! -f ${PWD}/protected/config/main.php ]; then
  cp -n ${PWD}/protected/config/main.php.example ${PWD}/protected/config/main.php
  NEW_KEY=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 16 | head -n 1)
  sed -i "s/old_key1old_key1/$NEW_KEY/g" ${PWD}/protected/config/main.php
fi
