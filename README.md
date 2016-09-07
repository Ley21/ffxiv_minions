# Final Fantasy XIV - Collection Site

This collection website collects from an public lodestone profile of an charakter some informations. Such as

  - Minions
  - Mounts
  - Charakter Details (Race, Gender, GC, FC,...)

This site has following features:
  - Ranking of searched charakters
  - Showing which minions or mounts are missing
  - Show where to find them
  - Multi language support (will show most of the informations in all languages ffxiv exists)
  - Browser as charakter to compare to other players
  - Check which minions or mounts in your free company are missing
 
Currently we under development and we will be happy if we get some help at translation all texts to frence or japanies.

### Setup Guide:

   1. Clone the repository to your webserver with 'git clone https://github.com/Ley21/ffxiv_minions.git'
   2. Create a copy of config.php.sample and rename it to config.php.
   3. Setup the config.php with a empty database, username and password and if your site will be ssl, and a secret key.
   4. After setup config.php configure you webserver like descipted below.
   5. For the first time call 
      ```
      'BASEURL/caller/init_database.php?update=true&type=minions&last=LAST_NUMBER&readonly=true&key=YOURSECRETKEY' => Import minions from xivdb
      'BASEURL/caller/init_database.php?update=true&type=mounts&last=LAST_NUMBER&readonly=true&key=YOURSECRETKEY' => Import mounts from xivdb
      'BASEURL/caller/init_database.php?update=true&readonly=true&key=YOURSECRETKEY' => Import methodes from json files.
      ```
   6. Now the site ist initialized and you can use it on your server.

### Webserver Configuration

Apache htaccess:
```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /index.php?path=$1 [NC,L,QSA]
```

Nginx Config:
```
# nginx configuration 
location / {
   try_files $uri $uri/ /index.php?$args;
   index index.php
}

```

### Version
v0.1 beta

### Tech

FFXIV Collection uses a number of open source projects to work properly:

* [bootstrap] - CSS Framework
* [medoo] - MySql php framework
* [lodestone-api] - Lodestone parsing api

### Todos

 - Release on reddit
 - ...

License
----


   [bootstrap]: <https://github.com/twbs/bootstrap>
   [medoo]: <https://github.com/catfan/Medoo>
   [lodestone-api]: <https://github.com/viion/XIVPads-LodestoneAPI>
