# test-ohris
    - Technonologie utilisé :  Symfony 5.4 php 8.1
    - design : tailwind + css 

    Lancement projet :
        - composer install  
        - symfony:server start  (assurer vous d'avoir crée votre base de donnée ( php bin/console d:d:c) )
    Pour le css : si le css n'est pas pris en compte au lancement essayer la commande "yarn build" ou "yarn run dev"

    Pour le teste fonctionnalité désiré : 
        - page administration avec le listing des évenement + CRUD sur l'entité Event 
        - homepage avec un listing des evenement jusque 3 mois dans le futur + possibilité d'executé un filtre selon les différents propriété de l'entité évenement

    Fonctionnalité ajouter 
        Pagination pour la parti Adminstriative + regles en fonction de la date de l'événement + régles de validation ajouter pour le fomulaire de création 


    retour test : 
        Le test m'a pris environ une dizaine heure ,je pense qu'avec un design plus leger et sans les différente fonctionnalité ajouter et régles de validation je serais entre 4h et 6h aproximativement.
        refacto du code possible :  
            - template twig , faire plus de component pour aller les différentes pages Home et Admin. 
            - Pagination : utliiser aurait été plus éfficaces mais on évite les problémes de dépendance 
            - Enlever la parti fash message et utiliser les Eventlistener/eventDispatcher pour aller le controleur
                       
