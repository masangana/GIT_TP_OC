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

	function verifierUtilisateur(){
		$connexion = connexion_bdd();
		$requete = "SELECT* FROM utilisateur ";
		$cmd = $connexion->prepare($requete);
		$cmd->execute();

		// recuperer la reponse de la bdd

		$rbdd = $cmd->fetchAll(PDO::FETCH_ASSOC);
		// valeur de retour
		return $rbdd;
	}


	function ajouterImageExamen($idResExam, $chemin){
		$connexion = connexion_bdd();

		$requete = "INSERT INTO imagesExamen VALUES(?,?,?)";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,0,PDO::PARAM_INT);
		$cmd->bindValue(2,$idResExam,PDO::PARAM_INT);
		$cmd->bindValue(3,$chemin,PDO::PARAM_STR);
		
		$cmd->execute();

		
		$id = $connexion->lastInsertId();

		return $id;
	}

	function ajouterResultatExamen($idExamen, $resultat, $note){
		$connexion = connexion_bdd();

		$requete = "INSERT INTO resultatExamen VALUES(?,?,?,?,NOW())";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,0,PDO::PARAM_INT);
		$cmd->bindValue(2,$idExamen,PDO::PARAM_INT);
		$cmd->bindValue(3,$resultat,PDO::PARAM_STR);
		$cmd->bindValue(4,$note,PDO::PARAM_STR);
		
		$cmd->execute();

		
		$id = $connexion->lastInsertId();

		return $id;
	}

	function recupererResultat($idExam){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM resultatExamen WHERE idExamen = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idExam,PDO::PARAM_INT);
		$cmd->execute();

		$resultat = $cmd->fetch();

		return $resultat;
	}
	// fonction d'ajout d'une personne
	
	function recupererImageExamen($idResultat){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM imagesExamen WHERE idResultatExamen = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idResultat,PDO::PARAM_INT);
		$cmd->execute();

		$images = $cmd->fetchAll();

		return $images;	
	}

	function ajouterPersonne($prenom,$nom,$postNom,$sexe,$date,$telephone){
		
		// connexion a la base des données 
		$connexion = connexion_bdd();
		
		//verification anti redondance avant insertion 
		$requete = "SELECT telephone FROM adresse WHERE (telephone = ? AND telephone != '' AND telephone IS NOT NULL)";

		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$telephone,PDO::PARAM_INT);
		$cmd->execute();
		
		//recuperation de la reponse de la bdd

		$rbdd = $cmd->fetchAll(PDO::FETCH_ASSOC);

		if (empty($rbdd)) {
			// insertion des données 
			$requete = "INSERT INTO Personne VALUES(?,?,?,?,?,?)";
			$cmd = $connexion->prepare($requete);
			$cmd->bindValue(1,'0',PDO::PARAM_INT);
			$cmd->bindValue(2,$nom,PDO::PARAM_STR);
			$cmd->bindValue(3,$postNom,PDO::PARAM_STR);
			$cmd->bindValue(4,$prenom,PDO::PARAM_STR);
			$cmd->bindValue(5,$sexe,PDO::PARAM_STR);
			$cmd->bindValue(6,$date,PDO::PARAM_STR);
			$cmd->execute();

			$idPersonne = $connexion->lastInsertId();

			return $idPersonne;
		}

		else {
			return $rbdd;
		}

	}

	//cette fonction ajoute les informations sur l'adresse de la personne
	function ajouterAdressePersonne($pays, $province, $commune, $quartier, $avenue, $numero, $telephone, $idPersonne){
			$connexion = connexion_bdd();

			$requete = "INSERT INTO adresse VALUES(?,?,?,?,?,?,?,?,?)";
			$cmd = $connexion->prepare($requete);
			$cmd->bindValue(1,'0',PDO::PARAM_INT);
			$cmd->bindValue(2,$pays,PDO::PARAM_STR);
			$cmd->bindValue(3,$province,PDO::PARAM_STR);
			$cmd->bindValue(4,$commune,PDO::PARAM_STR);
			$cmd->bindValue(5,$quartier,PDO::PARAM_STR);
			$cmd->bindValue(6,$avenue,PDO::PARAM_STR);
			$cmd->bindValue(7,$numero,PDO::PARAM_STR);
			$cmd->bindValue(8,$telephone,PDO::PARAM_STR);
			$cmd->bindValue(9,$idPersonne,PDO::PARAM_INT);
			$cmd->execute();

			$idPersonne = $connexion->lastInsertId();

			return $idPersonne;
	}

	// enregistrement d'une consultation

	function ajouterUneConsultation($idAppHopital, $idPatient, $motif, $observation){
		$connexion = connexion_bdd();

		$requete = "INSERT INTO consultation VALUES(?,?,?,?,?,?,?,NOW(),?)";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,'0',PDO::PARAM_INT);
		$cmd->bindValue(2,$idAppHopital,PDO::PARAM_STR);
		$cmd->bindValue(3,$idPatient,PDO::PARAM_STR);
		$cmd->bindValue(4,$motif,PDO::PARAM_STR);
		$cmd->bindValue(5,NULL,PDO::PARAM_STR);
		$cmd->bindValue(6,NULL,PDO::PARAM_STR);
		$cmd->bindValue(7,$observation,PDO::PARAM_STR);
		$cmd->bindValue(8,ouvert,PDO::PARAM_STR);
		$cmd->execute();

		$idConsultation = $connexion->lastInsertId();

		return $idConsultation;
	}

	function recupererUneConsultation($id){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM consultation WHERE id = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$id,PDO::PARAM_INT);
		$cmd->execute();

		$consultation = $cmd->fetch();

		return $consultation;
	}

	function chargerConsultation($idPatient){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM consultation WHERE idPatient = ? ORDER BY date DESC";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idPatient,PDO::PARAM_INT);
		$cmd->execute();

		$consultation = $cmd->fetchAll();

		return $consultation;
	}

	function cloturerConsultation($id){
		$connexion = connexion_bdd();
		$requete = "UPDATE consultation SET etat = 'cloture' WHERE id = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$id,PDO::PARAM_INT);
		$cmd->execute();

		$consultation = $cmd->fetch();

		return $consultation;
	}
	function ajouterOrdonnance($idConsultation, $prescription, $posologie, $note){
		$connexion = connexion_bdd();

		$requete = "INSERT INTO ordonnance VALUES(?,?,?,?,?,NOW())";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,'0',PDO::PARAM_INT);
		$cmd->bindValue(2,$idConsultation,PDO::PARAM_STR);
		$cmd->bindValue(3,$prescription,PDO::PARAM_STR);
		$cmd->bindValue(4,$posologie,PDO::PARAM_STR);
		$cmd->bindValue(5,$note,PDO::PARAM_STR);
		$cmd->execute();

		$idOrdonnance = $connexion->lastInsertId();

		return $idOrdonnance;
	}

	function ajouterExamen($idConsultation, $examen, $contenu){
		$connexion = connexion_bdd();

		$requete = "INSERT INTO examenBio VALUES(?,?,?,?,NOW())";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,'0',PDO::PARAM_INT);
		$cmd->bindValue(2,$idConsultation,PDO::PARAM_STR);
		$cmd->bindValue(3,$examen,PDO::PARAM_STR);
		$cmd->bindValue(4,$contenu,PDO::PARAM_STR);
		$cmd->execute();

		$idExamen = $connexion->lastInsertId();

		return $idExamen;
	}

	function recupererOdonnance($idConsultation){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM ordonnance WHERE idConsultation = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idConsultation,PDO::PARAM_INT);
		$cmd->execute();

		$ordonnance = $cmd->fetchAll();

		return $ordonnance;
	}

	function recupererExamen($idConsultation){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM examenBio WHERE idConsultation = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idConsultation,PDO::PARAM_INT);
		$cmd->execute();

		$examen = $cmd->fetchAll();

		return $examen;
	}

	// fonction attribuant un privilege a une personne 

	function attribuerPrivilege($idPersonne,$idRole){

		// cette fonction permet d'attribuer un privilege a un utilisateur x

		// elle doit verifier en premier si la personne n'a pas encore ce meme priv.

		$connexion = connexion_bdd();

		//verification anti redondance avant insertion 
		$requete = "SELECT * FROM avoirRole WHERE (idPersonne = ? AND idRole = ?)";

		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idPersonne,PDO::PARAM_INT);
		$cmd->bindValue(2,$idRole,PDO::PARAM_INT);
		$cmd->execute();
		
		//recuperation de la reponse de la bdd

		if (empty($rbdd)) {
			// insertion des données 
			$rbdd = $cmd->fetchAll(PDO::FETCH_ASSOC);

			$requete = "INSERT INTO avoirRole VALUES(?,?,?)";
			$cmd = $connexion->prepare($requete);
			$cmd->bindValue(1,'0',PDO::PARAM_INT);
			$cmd->bindValue(2,$idPersonne,PDO::PARAM_INT);
			$cmd->bindValue(3,$idRole,PDO::PARAM_INT);
			$cmd->execute();
			
			$id = $connexion->lastInsertId();

			return $id;
		}

		else {
			return $rbdd;
		}

		
	}

	function recupererUtilisateurViaCle($cle){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM utilisateur WHERE cle=?";

		$cmd = $connexion->prepare($requete);

		$cmd->bindValue(1,$cle,PDO::PARAM_INT);
		$cmd->execute();

		$rbdd = $cmd->fetch();
		return $rbdd;	
	}
	


	// function d'ajout d'un user

	function ajouterUtilisateur($idPersonne,$login,$mdp,$cle){
		
		/*
			cette fonction commence par recuperer le mail de la personne dont on veut generer le compte ensuite genere un usr name et un mdp par defaut qu'il ajoute dans la table, et envoi en meme temps un ladite personne 

			il faudrai peut etre decouper toute les diffrente fonctionalité de cette fonction en fonction a part entiere
		*/

		// selectionner id grace au mail recu en argument

		$connexion = connexion_bdd();
		
		$requete = "INSERT INTO utilisateur VALUES(?,?,?,?,?,?,NOW())";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,'0',PDO::PARAM_INT);
		$cmd->bindValue(2,$idPersonne,PDO::PARAM_INT);
		$cmd->bindValue(3,$login,PDO::PARAM_STR);
		$cmd->bindValue(4,$mdp,PDO::PARAM_STR);
		$cmd->bindValue(5,$cle,PDO::PARAM_STR);
		$cmd->bindValue(6,'2',PDO::PARAM_INT);
		$cmd->execute();
		
		return "code600";		
	}

	// fonction qui génère un clé aleatoire pour un utilisateur

	function chaineAleatoire($nbr_char){
		$genesis_pass="";
		$chaines ="0123456789@azertyuiopqsdfghjkmwxcvbnAZERTYUOPMLKJHGFDSQNBVCXW";
		srand((double)microtime()*1000000);
		for ($i=0; $i < $nbr_char; $i++) { 
				$genesis_pass .= $chaines[rand()%strlen($chaines)];
			}	
		return $genesis_pass;
	}

	// fonction qui verifie si un champs est deja occupé

	function champsUnique($nomTable,$nomChamp,$valeurChamp){
		
		$connexion = connexion_bdd();
	    $requete = "SELECT id FROM ".$nomTable." WHERE ".$nomChamp." = ?";
	    $cmd = $connexion->prepare($requete);
	    $cmd->bindValue(1,$valeurChamp,PDO::PARAM_STR);
	    $cmd->execute();
	    $exist = $cmd->rowCount($requete);
	    return $exist;
	}


	//recuperation de la personne par son id 
	
	function recupererPersonne($id){
		$connexion = connexion_bdd();

		$requete = "SELECT * FROM Personne WHERE  id = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$id,PDO::PARAM_INT);
		$cmd->execute();
		$user = $cmd->fetch();
		return $user;
	}

	function recupererCorpsMedical($idHopital){

		$connexion = connexion_bdd();

		$req = "SELECT idAvoirRole FROM appartenirHopital WHERE idHopital = ?";
		$cmd = $connexion->prepare($req);
		$cmd->bindValue(1,$idHopital,PDO::PARAM_INT);
		$cmd->execute();

		$rbdd = $cmd->fetchAll();

		if (empty($rbdd)) {
			return "APPARTENIRHOPITALVIDE";
		}
		else{
			$couche1 = array();
			$i = 0;
			foreach ($rbdd as $lienHopital) {
				$i++;
				$couche1[$i] = $lienHopital['idAvoirRole'];
			}

			return $corpsMed = implode(',', $couche1);

		}
	}

	function recupererPersonnes($ids){
		$connexion = connexion_bdd();

		$requete = "SELECT * FROM Personne WHERE  id IN ($ids)";
		$cmd = $connexion->prepare($requete);
		$cmd->execute();
		$users = $cmd->fetchAll();
		return $users;
	}


	//ratache une personne à l'hopital
	function ratacherHopital($idAvoirRole,$idHopital){

		$connexion = connexion_bdd();
		// verifié si ce lien n'existe pas deja avant de rajouter 

		$requete = "SELECT id FROM appartenirHopital WHERE (idAvoirRole = ? AND idHopital=? )";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idAvoirRole,PDO::PARAM_INT);
		$cmd->bindValue(2,$idHopital,PDO::PARAM_STR);
		$cmd->execute();

		$rbdd = $cmd->fetch();

		if (empty($rbdd)) {
			// si cette ligne n'existe pas deja alors l'inserer

			$requete = "INSERT INTO appartenirHopital VALUES(?,?,?)";
			$cmd = $connexion->prepare($requete);
			$cmd->bindValue(1,'0',PDO::PARAM_INT);
			$cmd->bindValue(2,$idHopital,PDO::PARAM_INT);
			$cmd->bindValue(3,$idAvoirRole,PDO::PARAM_STR);
			
			$cmd->execute();

			$idLien = $connexion->lastInsertId();

			return $idLien;

		}
		else{
			return "DEJAVOIRPRIV";
		}
	}	


	//recuperation de la personne par son id 
	
	function recupererRolePersonne($idPersonne){
		$connexion = connexion_bdd();

		$requete = "SELECT * FROM avoirRole WHERE  idPersonne = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idPersonne,PDO::PARAM_INT);
		$cmd->execute();
		$user = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $user;
	}

	// fonction qui crypte un mot de passe

	function crypter($mot_de_passe) {
		$sel = "05@@tx19";
		$crypt =$mot_de_passe.$sel;
		$crypt = sha1($crypt);
		return $crypt;
	}


	// cette fonction verifie la validité d'un mail

	function email_valide($email){
		if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	// fonction permettant de recuperer les infos sur une ecole en particulier

	function recupererUnHopital($id){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM hopital WHERE id = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$id,PDO::PARAM_INT);
		$cmd->execute();

		$ecole = $cmd->fetch();

		return $ecole;
	}

	/**
	* Cette fonction charge en session les infos d'une ecole
	*@param id
	*@return null
	*
	**/

	function ChargerInfoHopital($idHopital){
		$hopital = recupererUnHopital($idHopital);

		$_SESSION['nom_ecole'] = $hopital['nom'];
		// $_SESSION['abbreviation_ecole'] = $hopital['abbreviation'];
		// $_SESSION['adresse_ecole'] = $hopital['adresse'];
		// $_SESSION['boitePostal_ecole'] = $hopital['boitePostal'];
		// $_SESSION['mail_ecole'] = $hopital['mail'];
		// $_SESSION['telephone_ecole'] = $hopital['telephone'];
		// $_SESSION['logo_ecole'] = $hopital['logo'];
	} 


	// les methodes utilisé par le super admin et celui des ecoles

	function membreHopital($idHopital){

		$connexion = connexion_bdd();
		$requete = "SELECT* FROM appartenirHopital WHERE idHopital = ?";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idHopital,PDO::PARAM_INT);
		$cmd->execute();

		$membres = $cmd->fetchAll(PDO::FETCH_ASSOC);

		return $membres;
	}

	// cette fonction qui trouve les adminsitrateurs d'une ecole

	function trouverMembreHopitalParRole($idAvoirRole,$idRole){

		$connexion = connexion_bdd();
		$requete = "SELECT idPersonne FROM avoirRole WHERE id=? AND idRole = ?";

		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,$idAvoirRole,PDO::PARAM_INT);
		$cmd->bindValue(2,$idRole,PDO::PARAM_INT);
		$cmd->execute();

		$rbdd = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $rbdd;
	}

	// fonction qui recupère un utilisateur

	function recupererUtilisateur($idPersonne){
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM utilisateur WHERE idPersonne=?";

		$cmd = $connexion->prepare($requete);

		$cmd->bindValue(1,$idPersonne,PDO::PARAM_INT);
		$cmd->execute();

		$rbdd = $cmd->fetch();
		return $rbdd;	
	}
	

	//fonction pour le profile de l'utilisateur

	//update code modification 16 fevrier
	function recuperIdentifiantsPersonne ($idPersonne){

		$connexion = connexion_bdd();
		$requete = "SELECT userName, mdp,cle FROM utilisateur WHERE idPersonne = ?";
		$cmd = $connexion->prepare($requete);

		$cmd->bindValue(1,$idPersonne,PDO::PARAM_INT);

		$cmd->execute();

		return $rbdd = $cmd->fetch();

	}


	function recupererPersonneConfiance($idPatient) {
		$connexion = connexion_bdd();
		$requete = "SELECT * FROM persConfiance WHERE idPatient=?";

		$cmd = $connexion->prepare($requete);

		$cmd->bindValue(1,$idPatient,PDO::PARAM_INT);
		$cmd->execute();

		$rbdd = $cmd->fetch();
		return $rbdd;
	}


	function ajouterPersonneConfiance($idPatient, $nom, $prenom, $telephone, $adresse){
		$connexion = connexion_bdd();

		$requete = "INSERT INTO persConfiance VALUES(?,?,?,?,?,?)";
		$cmd = $connexion->prepare($requete);
		$cmd->bindValue(1,0,PDO::PARAM_INT);
		$cmd->bindValue(2,$idPatient,PDO::PARAM_INT);
		$cmd->bindValue(3,$nom,PDO::PARAM_STR);
		$cmd->bindValue(4,$prenom,PDO::PARAM_STR);
		$cmd->bindValue(5,$telephone,PDO::PARAM_STR);
		$cmd->bindValue(6,$adresse,PDO::PARAM_STR);
		
		$cmd->execute();

		
		$id = $connexion->lastInsertId();

		return $id;
	}