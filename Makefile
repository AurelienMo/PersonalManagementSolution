EXEC_PHP        = php
EXEC_JS         = npm

SYMFONY         = $(EXEC_PHP) bin/console
COMPOSER        = composer
YARN            = $(EXEC_JS) npm

ARTEFACTS = var/artefacts

##
## Project
## -------
##




##
## Utils
## -----
##

db: ## Reset the database and load fixtures
db: .env vendor
	@$(EXEC_PHP) php -r 'echo "Wait database...\n"; set_time_limit(15); require __DIR__."/vendor/autoload.php"; (new \Symfony\Component\Dotenv\Dotenv())->load(__DIR__."/.env"); $$u = parse_url(getenv("DATABASE_URL")); for(;;) { if(@fsockopen($$u["host"].":".($$u["port"] ?? 3306))) { break; }}'
	-$(SYMFONY) doctrine:database:drop --if-exists --force
	-$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --allow-no-migration

db-diff: ## Generate a new doctrine migration
db-diff: vendor
	$(SYMFONY) doctrine:migrations:diff

db-migr: ## Apply migration doctrine
db-migr: vendor
	$(SYMFONY) doctrine:migrations:migrate

console: ## Execute command symfony
console: vendor
	$(SYMFONY) $* $(ARGS)

db-validate-schema: ## Validate the doctrine ORM mapping
db-validate-schema: .env vendor
	$(SYMFONY) doctrine:schema:validate

assets: ## Run Webpack Encore to compile assets
assets: node_modules
	$(YARN) run dev

watch: ## Run Webpack Encore in watch mode
watch: node_modules
	$(YARN) run watch

.PHONY: db migration assets watch

##
## Tests
## -----
##

test: ## Run unit and functional tests
test: tu tf

tu: ## Run unit tests
tu: vendor
	vendor/bin/phpunit

tf: ## Run functional tests
tf: vendor
	vendor/bin/behat

tf-coverage: ## Run functional tests with coverage
tf-coverage: vendor
	vendor/bin/behat --profile=coverage

.PHONY: test tu tf

# rules based on files
composer.lock: composer.json
	$(COMPOSER) update --lock --no-scripts --no-interaction

vendor: composer.lock
	$(COMPOSER) install

new-vendor:
	$(COMPOSER) req $(ARGS)

new-vendor-dev:
	$(COMPOSER) req --dev $(ARGS)

node_modules: yarn.lock
	$(YARN) install
	@touch -c node_modules

yarn.lock: package.json
	$(YARN) upgrade

.env: .env
	@if [ -f .env ]; \
	then\
		echo '\033[1;41m/!\ The .env file has changed. Please check your .env file (this message will not be displayed again).\033[0m';\
		touch .env;\
		exit 1;\
	else\
		echo cp .env .env.local;\
		cp .env .env.local;\
	fi


##
## Quality assurance
## -----------------
##

lint: ## Lints twig and yaml files
lint: lt ly

lt: vendor
	$(SYMFONY) lint:twig templates

ly: vendor
	$(SYMFONY) lint:yaml config

phploc: ## PHPLoc (https://github.com/sebastianbergmann/phploc)
	vendor/bin/phploc src/

cs: ## PHP_CodeSnifer (https://github.com/squizlabs/PHP_CodeSniffer)
	vendor/bin/phpcs -v

stan: ## PHPStan
	vendor/bin/phpstan analyze src


phpmetrics: ## PhpMetrics (http://www.phpmetrics.org)
phpmetrics: artefacts
	vendor/bin/phpmetrics --report-html=$(ARTEFACTS)/phpmetrics src

php-cs-fixer: ## php-cs-fixer (http://cs.sensiolabs.org)
	vendor/bin/php-cs-fixer fix src --dry-run --using-cache=no --verbose --diff

twigcs: ## twigcs (https://github.com/allocine/twigcs)
	$(QA) twigcs lint templates

eslint: ## eslint (https://eslint.org/)
eslint: node_modules
	$(EXEC_JS) node_modules/.bin/eslint --fix-dry-run assets/js/**

artefacts:
	mkdir -p $(ARTEFACTS)

.PHONY: lint lt ly phploc pdepend phpmd php_codesnifer phpcpd phpdcd phpmetrics php-cs-fixer apply-php-cs-fixer artefacts



.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
