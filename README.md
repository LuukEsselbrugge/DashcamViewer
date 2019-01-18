# Automated OBDII car Dashcam system

----
## What is DashcamViewer?
A way to view trips uploaded by my Dashcam project

[Images](https://imgur.com/a/HetpOQ9)

**Work in progress**

## Configuration
**Database**

* Import the included db file into a database called "Dashcam"
* Account password is can be created by making an SHA1 hash of UserID+password

**Nginx**

This project uses a Nginx virtual server

    server {
        listen 80;

        root /var/www/html/dashcam;
        index index.php;

        server_name servername.com;

        location / {
        #try_files $uri $uri/ =404;
          if (!-e $request_filename){
            rewrite ([^/]+)/([^/]+)/?$ /index.php?uri=$1&uri2=$2 last;

            rewrite ([^/]+)/?$ /index.php?uri=$1 last;
          }
        }

      




