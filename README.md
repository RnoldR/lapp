# Flyspray
A very simple LAPP framework using flyspray as an example. Based on the official repositories: PHP:apache and Postgres.

This is version 0.11: a functioning version, use at your own risk.

There are three separate containers which are brought together in
docker-compose.yml, but they can be used separately.

- **dockerfile.postgres** - Initializes a PostgreSQL database if it does not exist. It creates the standard postgres database with the postgres user and requires a POSTGRES_PASSWORD you should supply in an environment variable with the same name, e.g.: POSTGRES_PASSWORD=secret.
The postgres data directory (/var/lib/postgresql/data) must be stored externally in a directory which name is supplied by the POSTGRES_DATA_DIR.
- **dockerfile.php** - Creates a running Apache instance with PHP. The Postgres PDO extensions are installed as well. Apache stores its data in /var/www/html which must be linked to an external directory. The environment APP_DIR is used for that. 
- **dockerfile.flyspray** - Downloads flyspray.tgz and unpacks its contents into the APP_DIR folder.

## Environment variables

As mentioned above the docker-compose files uses several environment variables which should be set by the user before running `docker-compose up`. They are:
- **POSTGRES_PASSWORD=\<pw>** - where \<pw> is the password for the postgres user
- **POSTGRES_DATA_DIR=\<path>** - \<path> to a directory which hosts or will host the postgres data file. If it does not exist it will be created with the correct permissions and ownership
- **APP_DIR=\<path>** - \<path> to the directory that contains the /var/www/html data. When it does not exist it will be created with the wrong ownership (root:root). Best is to create this file before running `docker-compose up` and perform a `chown -R www-data:www-data $APP_DIR`

## Initialisation directories

There are some directories which are assumed to exist when docker-compose.yml is started. 

- **init.postgres** - directory containing the initialisation files for postgres, the directory must exist but may be empty. All files are copied to the directory /docker-entrypoint-initdb.d/ and all .sql and .sh files will be excuted (https://hub.docker.com/_/postgres).
A role is created with a non-sensical password. Please replace this password with one that suits rour needs.
- **init.php** - contains the initializing files for the php:apache image. Currently only vhost.conf is used for making /var/www/html acessible. An index.html and index.php are provided for testing purposes but are currently not used by the containers themselves.
