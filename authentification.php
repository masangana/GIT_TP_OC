<?php 
	
	// fonction chargeant les informations d"une personne dans de
	
	function chargerSessionPersonne($idPersonne,$nom,$postNom,$preNom,$sexe,$dateNaissance,$etatCompte,$idUser){
		
		
		$_SESSION['id_user'] = $idUser;
		$_SESSION['id_personne'] = $idPersonne;
		$_SESSION['nom_user'] = $nom;
		$_SESSION['etatCompte_user'] = $etatCompte;
		$_SESSION['postNom_user'] = $postNom;
		$_SESSION['preNom_user'] = $preNom;
		$_SESSION['sexe_user'] = $sexe;
		$_SESSION['date_Naissance'] = $dateNaissance;
		
		header('Location:index.php');
		exit();
	}

	// fonction d'authentification
	function authentification($login ,$mdp){

		$connexion = connexion_bdd();
		$requete = "SELECT * FROM utilisateur WHERE userName = ? AND mdp = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$login,PDO::PARAM_STR);
		$cmd->bindValue(2,$mdp,PDO::PARAM_STR);
		$cmd->execute();
		$user = $cmd->fetch();

		if (!empty($user)) {


			// verfier l'etat du compte : si pas bloqué 

			if ($user['idEtatCompte'] !=  3) {
				
				/*traitement apres connection*/ 
			
				// recuperation de l'identité de la personne

				$personne = recupererPersonne($user['idPersonne']);
				if (!empty($personne)) {
						 
						 /*
							appelle de la fonction qui charge les infos de la personne en session et le revoie dans son compte.
						 */
						$utilisateur = recupererUtilisateur($user['idPersonne']);
						if (!empty($utilisateur)) {
							
							chargerSessionPersonne($personne['id'],$personne['nom'],$personne['Postnom'],$personne['prenom'],$personne['sexe'], $personne['dateNaissance'], $utilisateur['idEtatCompte'], $utilisateur['id']);
						}


				}
				else{
					$GLOBALS['msg_err'] = "Une erreur s'est produite lors du chargement de vos informations.";
				}	
				
			}
			else{
				return "lock";
			}	
		}
		else{
			return "erreur";
		}
	}

	