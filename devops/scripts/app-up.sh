#!/bin/bash

GREEN="\033[0;32m"
RESET="\033[0m"

APP_NETWORK='cc-test-app-net'
FOUND_APP_NETWORK=$(docker network ls --format="{{.Name}}" -f name=${APP_NETWORK} | grep ${APP_NETWORK})

if [[ -z ${FOUND_APP_NETWORK} ]]
then
    docker network create ${APP_NETWORK}
fi

WINPTY=''
if [[ -n ${WINDIR} ]]
then
   echo "WINDIR is defined"
   WINPTY='winpty '
fi

# start application
NAME_PREFIX="$1"
DEVOPS_DIR="$2"

# ensure that old containers are removed
echo -e "\n${GREEN}Stopping containers${RESET}"
docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX stop

echo -e "\n${GREEN}Removing containers${RESET}"
docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX rm -f

echo -e "\n${GREEN}Building images${RESET}"
docker buildx build --tag $NAME_PREFIX ${DEVOPS_DIR}/image/php

echo -e "\n${GREEN}Up containers${RESET}"
docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX up -d --force-recreate

echo -e "\n${GREEN}Installing dependencies${RESET}"
$WINPTY docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX  \
  exec php sh -c "composer install -a -n --prefer-dist --ignore-platform-reqs;"

echo -e "\n${GREEN}Installing dependencies${RESET}"
$WINPTY docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX  \
  exec php sh -c "composer app:cache:preparing"

echo -e "\n${GREEN}Setup DB${RESET}"
$WINPTY docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX  \
  exec php sh -c "bin/console doctrine:database:create"

echo -e "\n${GREEN}Migration apply${RESET}"
$WINPTY docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX  \
  exec php sh -c "bin/console doctrine:migrations:migrate -n"
