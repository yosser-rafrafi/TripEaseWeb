<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications')]
    public function notifications(NotificationRepository $notificationRepository): JsonResponse
    {
        $user = $this->getUser();

        // Fetch notifications for the logged-in user, sorted by creation date (newest first)
        $notifications = $notificationRepository->findBy(
            ['employe' => $user],
            ['createdAt' => 'DESC']
        );

        // Limit the number of notifications to show in the navbar (e.g., 5 most recent ones)
        $notifications = array_slice($notifications, 0, 5);

        // Serialize the notifications into a more usable format for the frontend
        $formattedNotifications = array_map(function ($notification) {
            return [
                'id' => $notification->getId(),
                'message' => $notification->getMessage(), // Replace with actual message field
                'createdAt' => $notification->getCreatedAt()->format('Y-m-d H:i:s'),
                // Add other fields as needed (e.g., 'type', 'status', etc.)
            ];
        }, $notifications);

        // Return notifications as JSON
        return new JsonResponse([
            'notifications' => $formattedNotifications,
        ]);
    }
}
