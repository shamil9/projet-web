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
* Recherche par type de services ou_et  par lieu ou_et  par prestataire - attention prévoir une pagination pour les résultats de la recherche
* Visualiser les services et leur description
* Visualiser les différents prestataires
* Visualiser les informations détaillées des prestataires de services
	* Description
	* Photo
	* Lien vers site officiel
	* Informations
		* nom
		* adresse
		* mobile
* Numéro de TVA
* Stages proposés
	* Description
	* Tarif + information complémentaire
	* Date de début et date de fin
* Promotions (si utilisateur authentifié)
* Possibilité 
	* Ajouter à mes favoris (si utilisateur authentifié)
	* Laisser un commentaire (si utilisateur authentifié)
	* Contacter cet établissement (formulaire de contact)
	* Envoyer à un ami (si utilisateur authentifié)
	* Cotation du prestataire (sous forme d’étoiles) (si utilisateur authentifié)
	* Zoomer sur le plan (affichage dans une lightbox un google map)
	* Prévenir de commentaires abusifs (si utilisateur authentifié)

* « Ces prestataires pourraient vous intéresser » afficher d’autres prestataires qui offrent des services comparables dans la même ville
* Liens vers réseaux sociaux 
* Prévoir la ré-écriture d’url

#### Inscription sur le site
* Un prestataire peut s’inscrire sur le site via un formulaire assez simple : nom – prénom – mail (vérification de l’inscription via mail) ou via Facebook Connect (à voir…)
* Une fois connecté, le prestataire peut modifier toutes les informations qui apparaissent dans sa page d’information :
	* Description
	* Photos
	* Lien vers site officiel
	* Informations
		* nom
		* adresse
		* téléphone 
		* Numéro de TVA
* Ajout de promo en vue de fidéliser les internautes (par exemple : promotion sur un type de soin/massage – une réduction sur un stage, … ) => pdf à télécharger (créer dynamiquement via les données insérées dans le formulaire)
* Le prestataire choisit   les  catégories dans lesquelles il désire apparaître.  Si la catégorie est manquante, il peut demander sa création (avec modération de l’administrateur du site pour éviter des doublons avec fautes d’orthographes, par exemple).
* Il peut également annoncer des stages
* Pour chaque stage, les informations suivantes sont demandées :
	* Description
	* Tarif + information complémentaire
	* Date de début et date de fin
	* Date à partir laquelle il désire que l’annonce soit publiée + durée de parution
	
#### Internautes
* Un internaute peut s’inscrire sur le site via un formulaire assez simple :
 nom – prénom – mail (vérification de l’inscription via mail) ou via Facebook Connect (à voir…)
* Une fois connecté, l’internaute peut 
	* gérer les informations de son profil : Nom – prénom – avatar – mail
	* laisser des commentaires sur les prestataires
	* donner une appréciation (cotation sous forme d’étoile)
	* ajouter le prestataire à ses favoris
	* prévenir de commentaires abusifs


#### Administrateur du site
* Gestion des catégories de services
* Gestion des prestataires (avec bannissement)
* Gestion des utilisateurs internautes  (avec bannissement)
* Gestion des commentaires	

