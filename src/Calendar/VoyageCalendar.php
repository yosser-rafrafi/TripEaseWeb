<?php
namespace App\Calendar;

use App\Repository\VoyageRepository;
use Symfony\Component\Security\Core\Security;

class VoyageCalendar
{
    private $voyageRepository;
    private $security;

    public function __construct(VoyageRepository $voyageRepository, Security $security)
    {
        $this->voyageRepository = $voyageRepository;
        $this->security = $security;
    }

    public function getEvents($user = null): array
    {
        $user = $user ?: $this->security->getUser();
        if (!$user) {
            return [];
        }

        $voyages = $this->voyageRepository->findVoyagesByUser($user);
        $events = [];

        foreach ($voyages as $voyage) {
            $events[] = [
                'title' => $voyage->getTitle(),
                'start' => $voyage->getDateDepart()->format('Y-m-d'),
                'end' => $voyage->getDateRetour()->format('Y-m-d'),
                'url' => '/show/'.$voyage->getId(),
                'color' => '#3788d8',
                'textColor' => '#ffffff'
            ];
        }

        return $events;
    }
}