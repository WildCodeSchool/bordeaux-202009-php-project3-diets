<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
       if ($formContact->isSubmitted() && $formContact->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();
            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_to'))
                ->subject('Nous les Diets - Nouveau message de contact')
                ->html($this->renderView(
                    'contact/newContactEmail.html.twig',
                    ['contact' => $contact]
                ));
            //$mailer->send($email);
            $this->addFlash('success', 'Vous avez bien envoyé un message');
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form_contact' => $formContact->createView(),
        ]);
    }
}
