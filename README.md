# `tydoo/vacairo`

[![Vacairo](https://github.com/tydoo/vacairo/actions/workflows/ci.yaml/badge.svg)](https://github.com/tydoo/vacairo/actions/workflows/ci.yaml)


Vacairo est une application à destination des réservistes de la police nationale afin de pouvoir gérer leurs vacations.

Elle tourne sous Symfony 8.

## Prerequisites

- **PHP** >= 8.4
- **Composer**
- **Symfony CLI**
- **Docker**
- **Docker** & **Docker Compose**
- **Symfony CLI**

## Installation d'un environnement de développement

### Prérequis
 1. [Symfony CLI](https://symfony.com/download)
 2. [PHP 8.4](https://www.php.net/downloads) ou supérieur et ses extensions :
	 1. [Ctype](https://www.php.net/book.ctype)
	 2. [iconv](https://www.php.net/book.iconv)
	 3. [PCRE](https://www.php.net/book.pcre)
	 4. [Session](https://www.php.net/book.session)
	 5. [SimpleXML](https://www.php.net/book.simplexml)
	 6. [Tokenizer](https://www.php.net/book.tokenizer)
	 7. [Mbstring](https://www.php.net/book.mbstring)
	 8. [Intl](https://www.php.net/book.intl)
	 9. [PDO + PDO Mysql](https://www.php.net/book.pdo)
	 10. [OPcache](https://www.php.net/book.opcache)
	 11. [OpenSSL](https://www.php.net/book.openssl)
	 12. [cURL](https://www.php.net/book.curl)
	 13. [Sodium](https://www.php.net/book.sodium)
	 14. [Gd](https://www.php.net/book.image)
	 15. [Soap](https://www.php.net/book.soap)
 3. [Composer](https://getcomposer.org/doc/00-intro.md)
 4. Git
 5. [Docker + Docker Compose](https://www.docker.com/)
 6. [OpenSSL](https://www.openssl.org/)


### Démarrage de l'environnement de développement
```bash
composer install
docker-compose up -d
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
symfony serve -d
```

L'application est disponible sur **https://localhost:8000**
