first of all , use composer install and configure the database 
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
then use the script to load articles : php src/scripts/add_dummy_articles.php  
remove the script folder before proceeding


le code dans le controlleur repose sur FOSRestBundle et serializer ,  les routes  créées avec l’annotation @Get @post etc.. au lieu de @Route.

utiliser postman pour tester , les body doivent etre entré en JSON format , et respecter les champs : 

***************************************************

pour récupérer tous les articles dans une JsonResponse : 
methode : GET :  http://localhost:8000/articles   

pour récupérer l’article avec l'id : {id} : 
methode : GET :  http://localhost:8000/articles/{id}

pour insèrer un nouvel article conforme à celui passé via le body de la requête en base : 
methode : POST :  http://localhost:8000/article
les body doivent etre entré en JSON format , et respecter les champs : 
{
  "titre": "titre-test",
  "contenu": "contenu-test.",
  "auteur": "auteur-test"
}

pour insèrer ou modifier un article conforme à celui passé via le body de la requête en base :
methode : PUT :  http://localhost:8000/article
les body doivent etre entré en JSON format , et respecter les champs : 
{
  "titre": "titre-test",
  "contenu": "contenu-test.",
  "auteur": "auteur-test"
}

pour récupèrer les 3 derniers articles ( par date de publication ) : 
methode : GET : http://localhost:8000/article/last3article

pour suprimer l’article avec l'id : {id} : http://localhost:8000/article
methode : DELETE :  http://localhost:8000/article/{id}

