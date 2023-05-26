<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\Utils;
use App\Service\Messagerie;
use Doctrine\ORM\EntityManagerInterface;
class ContactTypeController extends AbstractController
{
    #[Route('/contact/type', name: 'app_contact')]
    public function contactForm(EntityManagerInterface $em, Request $request,
    ContactRepository $repo,Messagerie $messagerie):Response{
        $msg = "";
        $status = "";
        $contact = New Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        //test si le formulaire est submit 
        if($form->isSubmitted()AND $form->isValid()){
            $recup = $repo->findOneBy(['nom'=>$contact->getNom(), 
            'mail'=>$contact->getMail(), 
            'prenom'=>$contact->getPrenom(), 
            'objet'=>$contact->getObjet(), 
            'contenu'=>$contact->getContenu() ]);
            if(!$recup){
                $contact->setContenu(Utils::cleanInputStatic($request->request->all('contact')['contenu']));
                $contact->setObjet(Utils::cleanInputStatic($request->request->all('contact')['objet']));
                $contact->setNom(Utils::cleanInputStatic($request->request->all('contact')['nom']));
                $contact->setPrenom(Utils::cleanInputStatic($request->request->all('contact')['prenom']));
                $contact->setMail(Utils::cleanInputStatic($request->request->all('contact')['mail']));
                $em->persist($contact);
                $em->flush();
                $login = $this->getParameter('login');
                $mdp = $this->getParameter('mdp');
                $date = $contact->getDate()->format('d-m-Y');
                $objet = $contact->getObjet();
                $content = '<p>Nom: <strong>' . $contact->getNom() . '</strong></p>' .
               '<p>Prenom: <strong>' . $contact->getPrenom() . '</strong></p>' .
               '<p>Mail: <strong>' . $contact->getMail() . '</strong></p>' .
               '<p>Contenu: <strong>' . mb_convert_encoding($contact->getContenu(), 'ISO-8859-1', 'UTF-8') . '</strong></p>' .
               '<p>Date: <strong>' . $contact->getDate()->format('d-m-Y') . '</strong></p>';
                $destinataire ='youssef20ben@gmail.com';
                $statut = $messagerie->sendMail($login, $mdp, $destinataire, $objet, $content);
                $msg = "Demande de contact ajoutée en BDD";
            }
            else{
                $msg = "La demande de contact existe déja en BDD";
            }
            
        }
        
        return $this->render('contact_type/index.html.twig',[
            'message' => $msg,
            'form'=>$form->createView()
        ]);
    }
}