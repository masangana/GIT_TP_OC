<?php 

	// modeles ayant trait au controlleur frontal
	
	// declaration de la variable global de connexino a la bdd
	
	$GLOBALS['connexion'] = connexion_bdd();


	// methode qui recuperer l'id de la personne grace au mail
	
	function recupererIdPersonne($mail){
		
		$connexion = connexion_bdd();
		$requete = "SELECT id FROM personne WHERE mail = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$mail,PDO::PARAM_STR);
		$cmd->execute();

		// reponse de la bdd

		$idPersonne = $cmd->fetch();
		if (empty($idPersonne)) {
			return "erreur";
		}
		else{
			return $idPersonne;
		}

	}

	// function de vérification de la presence d'un utilisateur 
