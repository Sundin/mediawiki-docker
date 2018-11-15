# Mediawiki-docker

A Docker image for setting up a MediaWiki using MariaDB for storage. Based on the official [MediaWiki image](https://hub.docker.com/_/mediawiki/) and complemented with [deitch/mysql-backup](https://github.com/deitch/mysql-backup) for automatic backups.

## Set up the wiki
* First create a file called `.env` and fill it with the following environment variables:

    * MYSQL_DATABASE=
    * MYSQL_USER=
    * MYSQL_PASSWORD=
    * MYSQL_ROOT_PASSWORD=
    * DB_USER=`<same as MYSQL_USER>`
    * DB_PASS=`<same as MYSQL_PASSWORD>`

* Make sure the line `- ./LocalSettings.php:/var/www/html/LocalSettings.php` in `docker-compose.yml` is commented out.

* Run `docker-compose up` and follow the instructions. 

* When creating the database you need to use `database` as database host (instead of `localhost`). The other database credentials should be the same as the once you specified in the `.env` file.

* After the setup wizard is finished you will get a `LocalSettings.php` file. Download this file and place it in the root directory.

* Uncomment the `- ./LocalSettings.php:/var/www/html/LocalSettings.php` line in `docker-compose.yml`.

* Stop the Docker containers and start them again.

## Start the wiki

    docker-compose up

You can now visit your wiki on http://localhost:8080 in the browser.

## Restore a backup

Backups are stored in the `db_backup` folder.

To restore a backup, simply run the following command in the root folder while the database container is running:

    docker run --env-file=.env -e DB_RESTORE_TARGET=/backup/db_backup_20181115083120.gz -v $PWD/db_backup:/backup --network="mediawiki-network" deitch/mysql-backup

(Replace `db_backup_20181115083120.gz` with the name of your backup file.)