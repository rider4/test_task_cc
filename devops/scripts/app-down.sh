#!/bin/bash

GREEN="\033[0;32m"
RESET="\033[0m"

NAME_PREFIX="$1"
DEVOPS_DIR="$2"

echo -e "\n${GREEN}Stopping containers${RESET}"
docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX stop

echo -e "\n${GREEN}Down containers${RESET}"
docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $NAME_PREFIX down -v
