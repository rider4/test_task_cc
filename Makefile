.DEFAULT: help
.PHONY: up down exec dump-config ps

RUN_ARGS=$(filter-out $@,$(MAKECMDGOALS))
NAME_PREFIX=cc-test-app
DEVOPS_DIR=devops

help:
	@echo ''
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
	@echo ''

##Commands:
##up          Up & install application
up:
	@cp -n ${DEVOPS_DIR}/.env.dist ${DEVOPS_DIR}/.env || true
	@cp -n ./app/phpunit.xml.dist ./app/phpunit.xml || true
	@ln -fs ${DEVOPS_DIR}/.env ./.env || true
	@bash ${DEVOPS_DIR}/scripts/app-up.sh $(NAME_PREFIX) ${DEVOPS_DIR}

##down        Down application and clean all
down:
	@bash ${DEVOPS_DIR}/scripts/app-down.sh $(NAME_PREFIX) ${DEVOPS_DIR}

##exec        Execute command in service
exec:
	@docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $(NAME_PREFIX) exec $(RUN_ARGS) || true

##dump-config Print config
dump-config:
	@docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml config

##ps          Show prepared docker ps
ps:
	@docker-compose --project-directory ${DEVOPS_DIR} -f ${DEVOPS_DIR}/docker-compose.yml -p $(NAME_PREFIX) ps

%: ; @:
