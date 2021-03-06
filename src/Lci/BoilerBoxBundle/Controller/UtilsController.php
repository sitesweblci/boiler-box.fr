<?php
#src/Lci/BoilerBoxBundle/Controller
namespace Lci\BoilerBoxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UtilsController extends Controller {

private $delais_netcat;
private $tab_details;
private $label;
private $dnsServer;

public function __construct() {
    $this->delais_netcat = 2;
    $this->tab_details = array();
    $this->dnsServer = '8.8.8.8';
}

public function indexAction() {
    return $this->render('LciBoilerBoxBundle:Utils:index.html.twig');
}


// Fonction qui récupère le choix de l'utilitaire à afficher et appelle la fonction associée à l'utilitaire
// Fonction créée également dans un Service - ServiceUtil : function analyseAccess() - A modifier aussi en cas de modification de cette fonction
// L'utilitaire est demandé à la page 	[ src/Lci/BoilerBoxBundle/Resources/views/Utils/index.html.twig ]
// L'url appelée est 			[ /utilitaire/choix => lci_utils_validChoice ]
public function validChoiceAction() {
    // Récupération de la liste des sites autorisés pour l'utilisateur connecté
    $session = $this->getRequest()->getSession();
    $userLog = $session->get('userLog', array());
	$user = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:User')->findOneBy(array('username'=>$userLog['login']));
    $liste_sites = array();
    $this->label = $user->getLabel();

	// Récupération du choix de l'utilitaire demandé par l'utilisateur
	$choixUtilitaire = $_POST['choixUtilitaire'];
	//$choixUtilitaire = 'searchAccess';
	//echo "choix : ".$choixUtilitaire;

	// Appel de la fonction associée à l'utilitaire demandé
	switch($choixUtilitaire) {
		case 'printAccess':
    	    $entities_site = $this->container->get('doctrine')->getManager()->getRepository('LciBoilerBoxBundle:Site')->findAll();
			return $this->render('LciBoilerBoxBundle:Utils:utils_access.html.twig',array(
    	        'entities_sites' => $entities_site,
				'delais_netcat'	=> $this->delais_netcat,
				'tableau_detail' => $this->tab_details,
				'label'	=> $this->label
    	    ));
    		break;
		case 'getionRoles':
			return $this->redirectToRoute('lci_register_role');
			break;
		case 'afficheLogs':
			return $this->redirectToRoute('lci_affiche_logs_connexion');
			break;
	}
	return new Response();
}

}
