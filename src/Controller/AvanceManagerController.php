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

class AvanceManagerController extends AbstractController
{
    #[Route('/manager/avances', name: 'manager_avances')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Paramètres de tri (valeurs par défaut)
        $sortBy = $request->query->get('sortBy', 'date_demande');
        $order = $request->query->get('order', 'ASC');

        // Construction de la requête DQL avec tri dynamique
        $queryBuilder = $em->getRepository(AvanceFrai::class)->createQueryBuilder('a')
            ->orderBy('a.' . $sortBy, $order);

        // Appliquer des filtres supplémentaires si nécessaire
        $recherche = $request->query->get('recherche');
        if ($recherche) {
            $queryBuilder->andWhere('a.motif LIKE :recherche')
                         ->setParameter('recherche', '%' . $recherche . '%');
        }

        $statut = $request->query->get('statut');
        if ($statut && $statut != 'all') {
            $queryBuilder->andWhere('a.statut = :statut')
                         ->setParameter('statut', $statut);
        }

        $devise = $request->query->get('devise');
        if ($devise && $devise != 'all') {
            $queryBuilder->andWhere('a.devise = :devise')
                         ->setParameter('devise', $devise);
        }

        // Exécution de la requête
        $demandes = $queryBuilder->getQuery()->getResult();

        return $this->render('back/manager/avance_manager/index.html.twig', [
            'demandes' => $demandes,
            'rechercheActuelle' => $recherche,
            'statutActuel' => $statut,
            'deviseActuelle' => $devise,
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
public function show(AvanceFrai $avanceFrai): Response
{
    return $this->render('back/manager/avance_manager/show.html.twig', [
        'avance' => $avanceFrai,
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

