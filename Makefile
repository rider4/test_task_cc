.DEFAULT: help
.PHONY: up down run-test

RUN_ARGS=$(filter-out $@,$(MAKECMDGOALS))

help:
	@echo ''
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
	@echo ''

##Commands:
##up          Up & install application
up:
	@symfony check:requirements || true
	@cp -n ./phpunit.xml.dist ./phpunit.xml || true
	@symfony composer install -o || true
	@symfony console doctrine:database:create || true
	@symfony console doctrine:migrations:migrate -n    || true
	@symfony local:server:start

##down        Down application and clean all
down:
	@symfony local:server:stop || true
	@symfony console doctrine:database:drop --force

##run-test    Run all tests
run-test:
	@symfony php ./bin/phpunit --bootstrap ./tests/bootstrap.php --no-configuration ./tests


%: ; @:
