<?php

// src/Controller/AvanceManagerController.php

namespace App\Controller;

use App\Entity\AvanceFrai;
use App\Form\ManagerTraitementAvanceType;
use App\Repository\AvanceFraiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\CurrencyApiService;

class AvanceManagerController extends AbstractController
{
    #[Route('/manager/avances', name: 'manager_avances')]
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $em->getRepository(AvanceFrai::class)->createQueryBuilder('a');
    
        // Recherche par motif
        $recherche = $request->query->get('recherche');
        if ($recherche) {
            $queryBuilder->andWhere('a.motif LIKE :recherche')
                         ->setParameter('recherche', '%' . $recherche . '%');
        }
    
        // Tri dynamique
        $sortBy = $request->query->get('sortBy', 'dateDemande');
        $order = strtoupper($request->query->get('order', 'DESC'));
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = 'DESC';
        }
        $queryBuilder->orderBy('a.' . $sortBy, $order);
    
        // Pagination
        $demandes = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5 // Nombre d'éléments par page
        );
    
        return $this->render('back/manager/avance_manager/index.html.twig', [
            'demandes' => $demandes,
            'rechercheActuelle' => $recherche,
        ]);
    }
    

    #[Route('/manager/avances/{id}/frais', name: 'manager_avance_frais')]
public function voirFraisParAvance(AvanceFrai $avance): Response
{
    $frais = $avance->getFrais(); // relation OneToMany

    return $this->render('back/manager/avance_manager/frais_par_avance.html.twig', [
        'avance' => $avance,
        'frais' => $frais,
    ]);
}

#[Route('/manager/avances/{id}', name: 'manager_avance_show')]
public function show(
    AvanceFrai $avanceFrai,
    CurrencyApiService $currencyApiService
): Response {
    // 1. Récupérer les taux
    $currencyApiService->fetchExchangeRates();

    // 2. Construire la liste des devises (TND, EUR, USD en tête)
    $allCodes   = array_keys($currencyApiService->getExchangeRates());
    $preferred  = ['TND', 'EUR', 'USD'];
    $others     = array_diff($allCodes, $preferred);
    sort($others);
    $currencies = array_merge($preferred, $others);

    // 3. Passer tout à Twig
    return $this->render('back/manager/avance_manager/show.html.twig', [
        'avance'       => $avanceFrai,
        'currencies'   => $currencies,
        'baseCurrency' => $avanceFrai->getDevise(),
        'amount'       => $avanceFrai->getMontantDemande(),
    ]);
}

    #[Route('/manager/avances/traiter/{id}', name: 'manager_traiter_avance')]
    public function traiter(Request $request, AvanceFrai $avanceFrai, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ManagerTraitementAvanceType::class, $avanceFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avanceFrai->setDateValidation(new \DateTime());
            $avanceFrai->setStatut('Traité');
            $em->flush();

            $this->addFlash('success', 'Avance traitée avec succès.');
            return $this->redirectToRoute('manager_avances');
        }

        return $this->render('back/manager/avance_manager/traiter.html.twig', [
            'form' => $form->createView(),
            'avance' => $avanceFrai,
        ]);
    }
}

