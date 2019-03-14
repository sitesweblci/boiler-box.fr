<?php
// src/Ipc/ProgBundle/Services/Mailing/ServiceMailing.php
namespace Lci\BoilerBoxBundle\Services;

// Service d'envoi de mails ( par SMTP )
class ServiceMailing {
protected $mailer;
protected $templating;
protected $fichier_log = 'mailing.log';
protected $log;
protected $mail_administrateur;

	public function __construct(\Swift_Mailer $mailer, $templating, $mail_administrateur, $loging) {
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->mail_administrateur = $mail_administrateur;
		$this->log = $loging;
	}


	public function sendProblemeTechniqueMail($sender, $destinataire, $message_probleme_technique) {
		$message = \Swift_Message::newInstance()
			->setSubject('Affectation de problème technique')
			->setFrom('Assistance_IBC@lci-group.fr')
			->setTo($destinataire);
		//$image_link = $message->embed(\Swift_Image::fromPath('/var/www/vhosts/boiler-box.fr/httpdocs/BoilerBox/web/images/Lci.png'));
		$image_link = $message->embed(\Swift_Image::fromPath('/var/www/vhosts/boiler-box.fr/httpdocs/BoilerBox/web/images/logo_lci.jpg'));
		//$image_link = $message->embed(\Swift_Image::fromPath('/srv/www/htdocs/Symfony/web/images/logo_lci.jpg'));
		$message ->setBody($this->templating->render('LciBoilerBoxBundle:Mail:email_probleme_technique.html.twig', array('liste_contenus' => $message_probleme_technique, 'image_link' => $image_link)));
		$message ->setContentType('text/html');
		$nb_delivery = $this->mailer->send($message); 
		if ($nb_delivery == 0) {
			$this->log->setLog("[ERROR] [MAIL];Echec de l'envoi de l'email : [Affectation de problème technique] à $destinataire", $this->fichier_log);
			return(1);
		} else {
			$this->log->setLog("[MAIL];Mail envoyé à $destinataire", $this->fichier_log);
			return(0);
		}
	}


    public function sendRapportIndisponibilite($t_entities_sites_injoignables, $t_entities_sites_indisponibles, $str_erreur_crontab) {
        $message = \Swift_Message::newInstance()
            ->setSubject("Rapport d'indisponibilité Boiler-box")
            ->setFrom($this->mail_administrateur)
            ->setTo($this->mail_administrateur);
        //$image_link = $message->embed(\Swift_Image::fromPath('/srv/www/htdocs/Symfony/web/images/logo_lci.jpg'));
	$image_link = $message->embed(\Swift_Image::fromPath('/var/www/vhosts/boiler-box.fr/httpdocs/BoilerBox/web/images/logo_lci.jpg'));
        $message ->setBody($this->templating->render('LciBoilerBoxBundle:Mail:email_rapport_indisponibilite.html.twig', array(
            't_entities_sites_injoignables' => $t_entities_sites_injoignables,
            't_entities_sites_indisponibles' => $t_entities_sites_indisponibles,
			'str_erreur_crontab' => $str_erreur_crontab,
            'image_link' => $image_link
        )));
        $message ->setContentType('text/html');
        $nb_delivery = $this->mailer->send($message);
        if ($nb_delivery == 0) {
            $this->log->setLog("[ERROR] [MAIL];Echec de l'envoi de l'email : [Rapport d'indisponibilité des sites] à $destinataire", $this->fichier_log);
            return(1);
        } else {
			$this->log->setLog("[MAIL];Mail envoyé à $destinataire", $this->fichier_log);
            return(0);
        }
    }

	
	public function sendAllMails() {
		$cheminConsole = __DIR__.'/../../../../../app/console';
		$commande = "php $cheminConsole swiftmailer:spool:send ";
		$retour = shell_exec($commande);
		return(0);
	}

}
