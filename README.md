<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://symfony.com/logos/symfony_black_02.svg?v=4"></a></p>


## Projet d’exercice 2016-2017
Projet à réaliser en cadre de troisième année à ISL.
- - - -

## Cahier de charges
### Présentation de l’entreprise
La société « bien-être » est une association des 10 personnes dont le principal objectif est de promouvoir des services dans le domaine du bien-être.

La société « bien-être » agit en qualité d'intermédiaire entre les prestataires de services et les internautes. 
Les  services proposés se fondent sur les informations fournies par les prestataires. Les prestataires restent les seuls responsables quant à la correction de ces informations. Ils peuvent modifier les informations sur leur établissement, comme les prix, disponibilités et autres prestations à tout moment.

### Présentation du projet
> Création d’un annuaire où les prestataires de services pourront s’inscrire gratuitement et mettre en avant leurs services dans le domaine du bien-être.  

#### Objectifs du site
* Proposer un annuaire de prestataires de services 
* Fidéliser les internautes via des promotions régulières. 
* Générer des publicités sponsorisées

#### Public cible
* Institut, praticien désirant promouvoir leurs services
* Internaute désirant trouver un service


### Description fonctionnelle du site
* Site multilingue  : EN / FR
* :white_check_mark: Recherche par type de services ou_et  par lieu ou_et  par prestataire - attention prévoir une pagination pour les résultats de la recherche
* :white_check_mark: Visualiser les services et leur description
* :white_check_mark: Visualiser les différents prestataires
* :white_check_mark: Visualiser les informations détaillées des prestataires de services
	* Description
	* Photo
	* Lien vers site officiel
	* Informations
		* nom
		* adresse
		* mobile
* :white_check_mark: Numéro de TVA
* :white_check_mark: Stages proposés
	* Description
	* Tarif + information complémentaire
	* Date de début et date de fin
* :white_check_mark: Promotions (si utilisateur authentifié)
* :white_check_mark: Possibilité 
	* Ajouter à mes favoris (si utilisateur authentifié)
	* Laisser un commentaire (si utilisateur authentifié)
	* Contacter cet établissement (formulaire de contact)
	* Envoyer à un ami (si utilisateur authentifié)
	* Cotation du prestataire (sous forme d’étoiles) (si utilisateur authentifié)
	* Zoomer sur le plan (affichage dans une lightbox un google map)
	* Prévenir de commentaires abusifs (si utilisateur authentifié)

* :white_check_mark: « Ces prestataires pourraient vous intéresser » afficher d’autres prestataires qui offrent des services comparables dans la même ville
* :white_check_mark: Liens vers réseaux sociaux 
* :white_check_mark: Prévoir la ré-écriture d’url

#### Inscription sur le site
* :white_check_mark: Un prestataire peut s’inscrire sur le site via un formulaire assez simple : nom – prénom – mail (vérification de l’inscription via mail) ou via Facebook Connect (à voir…)
* :white_check_mark: Une fois connecté, le prestataire peut modifier toutes les informations qui apparaissent dans sa page d’information :
	* Description
	* Photos
	* Lien vers site officiel
	* Informations
		* nom
		* adresse
		* téléphone 
		* Numéro de TVA
* :white_check_mark: Ajout de promo en vue de fidéliser les internautes (par exemple : promotion sur un type de soin/massage – une réduction sur un stage, … ) => pdf à télécharger (créer dynamiquement via les données insérées dans le formulaire)
* :white_check_mark: Le prestataire choisit   les  catégories dans lesquelles il désire apparaître.  Si la catégorie est manquante, il peut demander sa création (avec modération de l’administrateur du site pour éviter des doublons avec fautes d’orthographes, par exemple).
* :white_check_mark: Il peut également annoncer des stages
* :white_check_mark: Pour chaque stage, les informations suivantes sont demandées :
	* Description
	* Tarif + information complémentaire
	* Date de début et date de fin
	* Date à partir laquelle il désire que l’annonce soit publiée + durée de parution
	
#### Internautes
* :white_check_mark: Un internaute peut s’inscrire sur le site via un formulaire assez simple :
 nom – prénom – mail (vérification de l’inscription via mail) ou via Facebook Connect (à voir…)
* :white_check_mark: Une fois connecté, l’internaute peut 
	* gérer les informations de son profil : Nom – prénom – avatar – mail
	* laisser des commentaires sur les prestataires
	* donner une appréciation (cotation sous forme d’étoile)
	* ajouter le prestataire à ses favoris
	* prévenir de commentaires abusifs


#### Administrateur du site
* :white_check_mark: Gestion des catégories de services
* :white_check_mark: Gestion des prestataires (avec bannissement)
* :white_check_mark: Gestion des utilisateurs internautes  (avec bannissement)
* :white_check_mark: Gestion des commentaires	

### Authentification 
* Le compte est bloqué après 4 essais.  Un lien permet de débloquer le compte en envoyant un nouveau mot de passe par mail
* :white_check_mark: Attention, le mot de passe doit comporter au minimum 7 caractères avec chiffres et lettres

### Home
#### La page d’accueil comportera 
* :white_check_mark: un slider (dont les images sont gérées automatiquement par l’administrateur)
* :white_check_mark: une zone de recherche permettant une recherche par type de services ou/et  par lieu ou/et  par prestataire – attention prévoir une pagination pour les résultats de la recherche
* :white_check_mark: le service du mois (un service est mis aléatoirement en avant par mois : photo + nom – le clic sur cet élément renvoie à la page descriptive de ce service
* :white_check_mark: les 4 derniers prestataires encodés.  Le logo apparait et au survol de celui-ci le nom et un lien vers la fiche du prestataire sont disponibles

### Flux RSS
* Un flux rss dynamique doit être mis en place sur les nouvelles insertions des prestataires
#### Menus 
* Un bloc présentant les différents services sera toujours présent
* :white_check_mark: Menu principal : Home / A propos / Partenaire /  Par région / Stages / Contact 

### Bloc additionnels
* Top des 5 derniers stages insérés
* API googleMaps Exemple : trouver les praticiens qui habitent à 10km de chez moi
* Gestion d’annonces publicitaires
* :white_check_mark: Gestion des newsletters
* L’internaute, une fois authentifié peut choisir les « blocs » qui s’affichent (personnaliser un mini-portail)
	* Les 5 derniers stages d’un praticien favori
	* Les 5 praticiens favoris
	* Les 5 derniers commentaires sur un praticien favori