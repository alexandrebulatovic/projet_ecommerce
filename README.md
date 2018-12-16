# Projet PHP de 2nde année DUT Info

Copyright © 2016 Alexandre BULATOVIC & Jordan BARRAL

Le projet a été développé pour, et tourne sous [WampServer](http://www.wampserver.com/).

Le but du projet était de créer un site e-commerce vendant des produits quelconque (dans notre cas des smartphones) utilisant la technologie PHP et réutilisant ce que l'on avait appris en cours de programmation web c'est-à-dire architecture MVC, cookies, sessions, création et utilisation de classes, connexion puis interaction avec une base de données, validation par e-mail, etc. avec un accent sur la sécurité également.

Il n'y avait pas de bonus à faire un joli site avec beaucoup de CSS donc il y a très peu de CSS dans le code et celui-ci est "inline".

Le fichier [script_creation_BD.sql](https://github.com/alexandrebulatovic/projet_ecommerce/blob/master/script_creation_BD.sql) contient le script du schéma relationnel servant à stocker les clients (ou utilisateurs enregistrés), les commandes, produits ainsi que la relation N-N (contenir) pour trouver les produits commandés par un ou plusieurs clients.

Le fichier [resultats-tests-fonctionnels.txt](https://raw.githubusercontent.com/alexandrebulatovic/projet_ecommerce/master/resultats-tests-fonctionnels.txt) est une liste de fonctionnalités qui ont été testés et que le site web permet donc de réaliser.

Le schéma relationnel de la base de données est ici :
<a href="https://raw.githubusercontent.com/alexandrebulatovic/projet_ecommerce/master/schema_relationnel.PNG"> 
	<img src="https://github.com/alexandrebulatovic/projet_ecommerce/blob/master/schema_relationnel.PNG" width="700">
</a>


*Il y a d'autres réalisations ici : https://alexandrebulatovic.github.io/*
