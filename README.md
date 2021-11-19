# MyYoutube

# KeyWord
-   Docker
-   NodeJs
-   Laravel
-   ElasticSearch
-   Encoding
-   Mailing (coming soon)
-   HelpMeMyBrutha

# Docker

Liste des ports:
-   Mysql           : 3360:3306
-   ElasticSearch   : 9200
-   Kibana          : 5601
-   Laravel         : 8000
-   Encoding        : 5000

Demarrer les containers `docker-compose up --build -d` le -d c'est pour lancer en background<br>
Acceder à un container via votre terminal `docker exec -it container_name /bin/bash`<br>
Possibilité de stopper le containers de adminer avec `docker-compose stop adminer`<br>
Possibilité de stopper le containers de laravel avec `docker-compose stop app`<br>


# NodeJs

```bash
# install dependencies
$ yarn install
$ npm install

# serve with hot reload at localhost:3000
$ yarn run dev
$ npm run dev

# build for production and launch server
$ yarn build
$ yarn start
$ npm build
$ npm start

# generate static project
$ yarn generate
$ npm generate
```

For detailed explanation on how things work, check out [Nuxt.js docs](https://nuxtjs.org).

# Laravel

Créer une KEY APP_KEY : `php artisan key:generate`
Créer une KEY JWT_SECRET : `php artisan jwt:generate`
Migration delete/create : `php artisan migrate:fresh`

# ElasticSearch
Vous pouvez faire des requêtes directement a ElasticSearch avec localhost:4200/MyYoutube/video et vous référé au lien: https://www.elastic.co/guide/en/elasticsearch/reference/6.8/docs.html

# Encoding
Vous pouvez aller sur localhost:5000 pour effectuer vos requêtes, les events adéquats seront bientôt disponible
encoding ici -> 

# Mailing
localhost:1080 - client<br>
localhost:1025 - server->reception de mail<br>

# HelpMeMyBrutha
Il est possible que l'application laravel crash si :
-   Vous n'avez pas faire le composer install et les générations de KEY avant de lancer les containers
-   Vous devrez possiblement clear tout les caches de laravel mais tkt pas j'ai fait un script pour toi, EHWAIMONVIEU ! dirige toi dans ./rootfolder/MyApi et execute le fichier `my_clear.bash` une premiere fois sur ta machine et si ça work tjr pas tu le fait sur le container `docker exec -it laravel_api /bin/bash`
-   Vous devrez possiblement faire les migrations directement dans le container `docker exec -it laravel_api /bin/bash` puis `php artisan migrate:fresh` 
-   Les migrations ne fonctionne pas sur votre ordinateur ? rentrer dans le container et rentrer directement la commande `php artisan migrate`
