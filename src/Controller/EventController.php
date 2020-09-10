<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    /**
     * @Route("/events", name="events")
     */
    public function index(EventRepository $eventRepository)
    {
        $events = $eventRepository->findAll();

        return $this->render('events/index.html.twig', compact('events'));
    }

    /**
     * @Route("/events/create", name="events_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $data = $request->request->all();
        
        if($request->getMethod() == 'POST') {
            $event = new Event();
            $event->setTitle($data['title']);
            $event->setDescription($data['description']);
            
            $event->setEventDate(new \DateTime(
                $data['event_date'], 
                new DateTimeZone('America/Sao_Paulo')
            ));

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('events');
        }

        return $this->render('events/create.html.twig');
    }


    /**
     * @Route("/events/edit/{event}", name="events_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em, Event $event)
    {
        $data = $request->request->all();

        if ($request->getMethod() == 'POST') {
            
            $event->setTitle($data['title']);
            $event->setDescription($data['description']);
            
            $event->setEventDate(new \DateTime(
                $data['event_date'], 
                new DateTimeZone('America/Sao_Paulo')
            ));

            $em->flush();

            return $this->redirectToRoute('events');
        }

        return $this->render('events/edit.html.twig', compact('event'));
    }


    /**
     * @Route("/events/remove/{event}", name="events_remove")
     */
    public function remove(EntityManagerInterface $em, Event $event)
    {
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('events');
    }
}
