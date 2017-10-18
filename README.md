Apibilemo
================

API in charge of the management of the mobile produces.

Installation

After copied (clone or download) this repository github on your computer

1) Adapt your data base parameters in the following folder :        "app/config/parameters.yml"
    -use the commande php composer.phar install to create the vendor
    
2 )Create your Database with the following command : 
    -"php bin/console doctrine:database:create"

3) Generate your tables : 
    -"php bin/console doctrine:schema:update --force

4) Create a Super_Admin :

    php bin/console customer:add  --email=yourfacebookaccountmail@example.com --super-admin

    (remove --super-admin if you want create a simple account without rights)


############## You must be authenticated by Facebook to use this api, then you need to use the web site Powerphone ###############


5) Copie (clone or download) Powerphone project repository github on your computer:
    https://github.com/S7tH/OPC_Projet7-Powerphone

    - Configure an ip adress to the api and powerphone (in local by sample, configure your virtual host)

    - Create a dev account facebook
        https://developers.facebook.com/
        Create a Facebook login and configure it

    - Repeat the rule 1 by adding the facebook_client_id and facebook_client_secret


6) After have configured an ip adress to each other, launch them servers
    samples:

        api:

            - php bin/console server:run 127.0.0.1:8001

        powerphone

            - php bin/console server:run 127.0.0.1:8002

In prod mod, remove the file config.php in the web folder
