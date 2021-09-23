# EC2 Guide

Here follows instructions for how to get your MediaWiki instance running on an AWS EC2 instance.

- Instantiate a new EC2 instance. Generate a key-pair file and download it. You will not be able to re-create this file later so don't loose it!

- Set correct permissions on the key-pair file.

        chmod 400 ~/.ssh/<private-key-pair-file>.pem

- Make sure the Security Group of your EC2 instance allows SSH traffic (port 22) from your current IP. If you can't see your IP under Inbound Rules in the Security Group, you will need to whitelist it.

- Connect to the EC2

        ssh ec2-user@<public-dns> -i ~/.ssh/<private-key-pair-file>.pem

- Install Docker

        sudo yum update -y

        sudo yum install -y docker

        sudo service docker start

        sudo usermod -a -G docker ec2-user

- Install Docker-Compose. Get the latest one from here https://github.com/docker/compose/releases

        sudo curl -L https://github.com/docker/compose/releases/download/1.23.2/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose

        sudo chmod +x /usr/local/bin/docker-compose

- Optional: Install git

        sudo yum install git

- Log out and log back in again.

- Follow instructions from README.

  - Change port from 8080 to 80 in docker-compose.yml
  - Change `$wgServer` in LocalSettings.php to the URL (public DNS) of your EC2 instance (no port number).
  - Make images folder accessible to wiki container:

        sudo chmod 777 images
        sudo chown -R www-data:www-data images/

- Make sure the backup folder is writable, e.g. `mkdir db_backup && chmod 777 db_backup`, see https://github.com/deitch/mysql-backup#permissions for other options.

- Open HTTP port 80 in the security group of your EC2 instance.

## Backup the backup folder

Run the following command in order to copy the whole `db_backup` folder from the EC2 to your local computer, in case anything happens with the EC2 instance:

        scp -i ~/.ssh/<private-key-pair-file>.pem -r ec2-user@<public-dns>:~/mediawiki-docker/db_backup db_backup

You also need to backup the images folder on top of this:

        scp -i ~/.ssh/<private-key-pair-file>.pem -r ec2-user@<public-dns>:~/mediawiki-docker/images images_backup
