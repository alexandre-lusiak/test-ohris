<?php

namespace App\Controller;


use App\Entity\Event;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminController extends AbstractController
{
    private $em ;
    private $eventRepo ; 
    private $validator;
    public function __construct(EntityManagerInterface $em, EventRepository $eventRepo, ValidatorInterface $validator)
    {
        $this->em=$em;
        $this->eventRepo=$eventRepo;
        $this->validator=$validator;
    }
 

/**
 * Undocumented function
 * @Route("/admin/{id}/edit", name="app_edit")
 * @Route("/admin/", name="app_admin")
 * @param Request $request
 * @param EntityManagerInterface $entityManager
 * @param Event|null $event
 * @return Response
 */
// public function createOrEdit(Request $request, Event $event = null): Response
// {   
//         $limit=20;
//         $page=(int)$request->get('page',1);
//         $total = $this->eventRepo->getTotalEvent();
//         $events = $this->eventRepo->findByDate($page, $limit);
//     if (!$event) {
//         $event = new Event();
//     }

//     $form = $this->createForm(EventFormType::class, $event);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         $errors = $this->validator->validate($event);
//     if (count($errors) > 0) {
//         // Afficher les erreurs de validation
//         foreach ($errors as $error) {
//             $this->addFlash('error', $error->getMessage());
//         }
//     } else {
//         $isNewEvent = $event->getId() === null; // Vérifier si l'événement est nouveau ou existant
//         $this->em->persist($event);
//         $this->em->flush();
        
//         // Ajouter un message flash en fonction de si l'événement est nouveau ou existant
//         $message = $isNewEvent ? 'crée' : 'modifié';
//         $this->addFlash('success', 'Concert ' . $message . 'avec  succès ;).');
    
//         return $this->redirectToRoute('app_admin');
//     }
//     }

//     return $this->render('admin/index.html.twig', [
//         'form' => $form->createView(),
//         'editMode' => $event->getId() !== null, // Si l'entité a un ID, on est en mode édition
//         "events"=> $events,
//         "total"=>$total,
//         "limit" =>$limit,
//         "page" => $page
//     ]);
// }

/**
 * Undocumented function
 * @Route("/admin/{id}/edit", name="app_edit")
 * @Route("/admin/", name="app_admin")
 * @param Request $request
 * @param EntityManagerInterface $entityManager
 * @param Event|null $event
 * @return Response
 */
public function createOrEdit(Request $request, Event $event = null): Response
{   
    $limit = $request->query->get('limit', 5); // Valeur par défaut : 5
    $page = (int) $request->get('page', 1);
    $total = $this->eventRepo->getTotalEvent();
    $events = $this->eventRepo->findByDate($page, $limit);
    $isEdit = true; 
    if (!$event) {
        $event = new Event();
        $isEdit = false;
    }

    $form = $this->createForm(EventFormType::class, $event);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier si l'artiste participe déjà à un événement à la même date
        $artist = $event->getArtist();
        $date = $event->getEventDate();
        
        $existingEvent = $this->eventRepo->findByArtistAndDate($artist, $date);
        $errors = $this->validator->validate($event);
if (count($errors) > 0) {
    // Afficher les erreurs de validation
    foreach ($errors as $error) {
        $this->addFlash('error', $error->getMessage());
    }
}
        if ($existingEvent && !$isEdit) {
            $this->addFlash('error', "L'artiste participe déjà à un événement pour la date selectionné.");
            return $this->redirectToRoute('app_admin');
        }  else {
            $isNewEvent = $event->getId() === null;
            $this->em->persist($event);
            $this->em->flush();

            $message = $isNewEvent ? 'créé' : 'modifié';
            $this->addFlash('success', 'Concert ' . $message . ' avec succès.');
            return $this->redirectToRoute('app_admin');
        }
    }

    return $this->render('admin/index.html.twig', [
        'form' => $form->createView(),
        'editMode' => $event->getId() !== null,
        'events' => $events,
        'total' => $total,
        'limit' => $limit,
        'page' => $page
    ]);
}



    #[Route('/event/delete/{id}', name: 'app_remove',methods:["DELETE"])]
    public function removeEvent($id,EventRepository $eventRepo,SessionInterface $session)
    {
        $event = $eventRepo->find($id);
       if(!$event instanceof Event){
           $this->addFlash('error',"Erreurr leurs de la suppression :)");
           return $this->redirectToRoute('app_admin');
       }
        $this->em->remove($event);
        $this->em->flush();
        $this->addFlash('sucess',' Le Concert à bien été supprimé!');
        $session->getFlashBag()->setAll($this->session->getFlashBag()->all());
      
       


        return $this->redirectToRoute('app_admin');
    }

   
}
