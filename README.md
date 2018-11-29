# Mediawiki-docker

A Docker image for setting up a MediaWiki using MariaDB for storage. Based on the official [MediaWiki image](https://hub.docker.com/_/mediawiki/) and complemented with [deitch/mysql-backup](https://github.com/deitch/mysql-backup) for automatic backups and VisualEditor for WSIWYG editing of wiki pages.

## Features
* [MediaWiki](https://hub.docker.com/_/mediawiki/) 1.31.1
* MariaDB
* [deitch/mysql-backup](https://github.com/deitch/mysql-backup): for automatic database backups.
* Parsoid: Translates between markdown syntax and HTML at runtime. Necessary for the VisualEditor plugin.
* VisualEditor: enables WSIWYG editing of wiki pages.

## Set up the wiki
* First create a file called `.env` and fill it with the following environment variables:

    * MYSQL_DATABASE=
    * MYSQL_USER=
    * MYSQL_PASSWORD=
    * MYSQL_ROOT_PASSWORD=
    * DB_USER=`[same as MYSQL_USER]`
    * DB_PASS=`[same as MYSQL_PASSWORD]`

* Create a file called `mediawiki_secrets.php` in the `settings` folder and enter the following content:
    ```
    <?php
    $wgDBname = "[mysql_database]";
    $wgDBuser = "[mysql_user]";
    $wgDBpassword = "[mysql_password]";
    ```

* Comment out the line `- ./settings/LocalSettings.php:/var/www/html/LocalSettings.php` in `docker-compose.yml`.

* Build the mediawiki Docker image: `docker build -t sundin-mediawiki .`

* Run `docker-compose up` and follow the instructions. 

* Visit http://localhost:8080 in the browser and follow the instructions in the setup wizard.

    * When creating the database you need to use `database` as database host (instead of `localhost`). The other database credentials should be the same as the ones you specified in the `.env` and `mediawiki_secrets.php` files.

* After the setup wizard is finished you will get a `LocalSettings.php` file. Download this file and place it in the `settings` directory. You need to make some modifications to it as listed below. Alternatively, you can use the one already included in this repo.
    * Add the following line at the very end of the file to configure VisualEditor: `require_once("/external_includes/visual_editor_configuration.php");`
    * Replace the three lines containing your $wgDBname, $wgDBuser and $wgDBpassword with this line: `require_once("/external_includes/mediawiki_secrets.php");`. When you have done this you can share your LocalSettings file without leaking any sensitive data about your database.

* Uncomment the `- ./settings/LocalSettings.php:/var/www/html/LocalSettings.php` line in `docker-compose.yml`.

* Stop the Docker containers and start them again.

## Start the wiki

    docker-compose up

You can now visit your wiki on http://localhost:8080 in the browser.

## Restore a backup

Backups are stored in the `db_backup` folder.

To restore a backup, simply run the following command in the project's root folder while the database container is running:

    docker run --env-file=.env -v $PWD/db_backup:/backup --network="mediawiki-network" -e DB_RESTORE_TARGET=/backup/db_backup_20181115083120.gz deitch/mysql-backup

(Replace `db_backup_20181115083120.gz` with the name of your backup file.)

## Hosting on AWS EC2

See [the EC2 instructions](/ec2.md) for details on how to get your MediaWiki instance running on EC2.

## Acknowledgments

* Full credits go to [Divinenephron](https://github.com/divinenephron/docker-mediawiki) for the VisualEditor setup.
* [deitch](https://github.com/deitch/mysql-backup) for the mysql-backup solution.
