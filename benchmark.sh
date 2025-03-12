PHP_VERSION=8.4 composer update > /dev/null
PHP_VERSION=8.4 php vendor/bin/phpbench run tests/Benchmark/ --report=short
