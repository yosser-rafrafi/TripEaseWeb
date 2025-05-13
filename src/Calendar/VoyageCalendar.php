<?php
// src/Calendar/VoyageCalendar.php

namespace App\Calendar;

use App\Repository\VoyageRepository;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use CalendarBundle\CalendarEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class VoyageCalendar implements EventSubscriberInterface
{
    private $voyageRepository;
    private $security;

    public function __construct(VoyageRepository $voyageRepository, Security $security)
    {
        $this->voyageRepository = $voyageRepository;
        $this->security = $security;
    }


    
    // CalendarEvents::SET_DATA est déclenché automatiquement par le bundle 
    // lorsque tu appelles le calendrier via le helper Twig
    public static function getSubscribedEvents(): array
    {
        // appelle la méthode onCalendarSetData()
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    // Crée et personnalise chaque événement
    public function onCalendarSetData(CalendarEvent $calendarEvent): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        $voyages = $this->voyageRepository->findVoyagesByUser($user);
        error_log(sprintf('%d voyages trouvés', count($voyages)));


        foreach ($voyages as $voyage) {
            if (!$voyage->getDateDepart() || !$voyage->getDateRetour()) {
                continue;
            }

            $event = new Event(
                $voyage->getTitle(),
                $voyage->getDateDepart(),
                $voyage->getDateRetour()
            );

            $event->setOptions([
                'backgroundColor' => $this->getColorForState($voyage->getEtat()),
                'borderColor' => $this->getColorForState($voyage->getEtat()),
                'textColor' => '#ffffff',
                'url' => '/show/'.$voyage->getId(),
            ]);

            $calendarEvent->addEvent($event);
        }

    }

    private function getColorForState(?string $state): string
    {
        return match($state) {
            'Validé' => '#28a745', // green
            'Refusé' => '#dc3545', // red
            default => '#ffc107', // yellow (for En attente or others)
        };
    }
}