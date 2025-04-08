<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/layout')]
class LayoutController extends BaseController
{
    #[Route('/vertical', name: 'layout_vertical')]
    public function vertical(): Response
    {
        return $this->render('layout/vertical.html.twig');
    }

    #[Route('/horizontal', name: 'layout_horizontal')]
    public function horizontal(): Response
    {
        return $this->render('layout/horizontal.html.twig');
    }
}