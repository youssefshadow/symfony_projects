<?php

namespace App\Controller;
use App\Service\Utils;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactTypeController extends AbstractController
{
    #[Route('/contact/type', name: 'app_contact')]
    public function index(Request $request,EntityManagerInterface $em): Response
    {
        $msg = "";
      
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        // Récupération des données du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setContenu( Utils::cleanInputStatic($request->request->all('contact')['contenu']));
            $contact->setObjet( Utils::cleanInputStatic($request->request->all('contact')['objet']));
            // $contact->setDate( new \DateTimeImmutable($contact->getDate()));
            $contact->setNom( Utils::cleanInputStatic($request->request->all('contact')['nom']));
            $contact->setPrenom( Utils::cleanInputStatic($request->request->all('contact')['prenom']));
            $mail = Utils::cleanInputStatic($request->request->all('contact')['mail']);

           
            $em->persist($contact);
            $em->flush();
            $msg = 'Message envoyé';

            // Rediriger vers une autre page ou afficher un message de succès
                    }

        return $this->render('contact_type/index.html.twig', [
            'controller_name' => 'ContactTypeController',
            'form' => $form->createView(),
            'msg' => $msg
        ]);
    }
}


