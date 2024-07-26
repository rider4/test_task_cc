#!/bin/bash

GREEN="\033[0;32m"
RESET="\033[0m"

NAME_PREFIX="$1"
DEVOPS_DIR="$2"

echo -e "\n${GREEN}Stopping containers${RESET}"
docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX stop

echo -e "\n${GREEN}Down containers${RESET}"
docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX down -v

echo -e "\n${GREEN}Setup DB${RESET}"
$WINPTY docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX  \
  exec php sh -c "bin/console doctrine:database:drop --force"

echo -e "\n${GREEN}Remove image${RESET}"
docker rmi $NAME_PREFIX

