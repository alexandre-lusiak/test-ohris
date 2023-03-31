<?php

namespace App\Controller;

use App\Entity\SearchFilter;
use App\Form\FilterType;
use App\Form\SearchFilterType as FormSearchFilterType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use SearchFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;




class HomeController extends AbstractController
{
    private $em ;
    private $eventRepo ; 
    public function __construct(EntityManagerInterface $em, EventRepository $eventRepo)
    {
        $this->em=$em;
        $this->eventRepo=$eventRepo;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $searchFilter = new SearchFilter();
        $form = $this->createForm(FormSearchFilterType::class,  $searchFilter);
        $form->handleRequest($request);
        $limit=30;
        $page=(int)$request->get('page',1);
        $total = $this->eventRepo->getTotalEvent();
        if ($form->isSubmitted() && $form->isValid()) {
            $events = $this->eventRepo->search($searchFilter,$page,$limit);
        } else {
            $events = $this->eventRepo->search(null,$page,$limit);
        }
        
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'events' => $events,
            'total' => $total,
            'limit' => $limit,
            'page' => $page
        ]);
    }
}
