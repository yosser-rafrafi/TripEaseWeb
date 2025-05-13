<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Avi;
use App\Form\AviType;
use App\Repository\AviRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Hotel;
use App\Repository\HotelRepository;

class AviController extends AbstractController
{ 
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/avis/{hotelId}', name: 'add_avis', methods: ['GET', 'POST'])]
    public function addAvis(Request $request, int $hotelId, AviRepository $aviRepository, UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $hotel = $entityManager->getRepository(Hotel::class)->find($hotelId);
    
        if (!$hotel) {
            throw $this->createNotFoundException('Hôtel non trouvé.');
        }
    
        $avi = new Avi();
        $avi->setUser($user);
        $avi->setHotel($hotel);
        $avi->setDateAvis(new \DateTimeImmutable());
    
        $form = $this->createForm(AviType::class, $avi);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if ($avi->getNote() === null) {
                $this->addFlash('error', 'Veuillez noter l\'hôtel avant d\'envoyer votre avis.');
                return $this->redirectToRoute('add_avis', ['hotelId' => $hotelId]);
            }
    
            $entityManager->persist($avi);
            $entityManager->flush();
    
            $this->addFlash('success', 'Votre avis a été ajouté avec succès.');
    
           // return $this->redirectToRoute('hotel_details', ['id' => $hotelId]);
        }
    
        return $this->render('front/avis/add_avis.html.twig', [
            'form' => $form->createView(),
            'hotel' => $hotel,
        ]);
    }
    /*#[Route('/avis/affich/{id}', name: 'app_hotel_avis')]
 public function hotelDetails(int $id, EntityManagerInterface $entityManager): Response
{
    $hotel = $entityManager->getRepository(Hotel::class)->find($id);

    if (!$hotel) {
        throw $this->createNotFoundException('Hôtel non trouvé.');
    }

    // Récupérer les avis de l'hôtel
    $avis = $hotel->getAvis(); // si ta relation est bien configurée dans Hotel (OneToMany)

    return $this->render('front/avis/affiche_avis.html.twig', [
        'hotel' => $hotel,
        'avis' => $avis,
    ]);
}



public function index(PaginatorInterface $paginator, Request $request, AviRepository $aviRepository)
{
    $query = $aviRepository->getAvisQuery();

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        5
    );

    return $this->render('front/avis/affiche_avis.html.twig', [
        'pagination' => $pagination,
    ]);
}*/

/*#[Route('/avis/affich/{id}', name: 'app_hotel_avis')]
public function hotelDetails(
    int $id, 
    EntityManagerInterface $entityManager, 
    PaginatorInterface $paginator, 
    Request $request, 
    AviRepository $aviRepository
): Response 
{
    $hotel = $entityManager->getRepository(Hotel::class)->find($id);

    if (!$hotel) {
        throw $this->createNotFoundException('Hôtel non trouvé.');
    }

    $query = $aviRepository->getAvisByHotelQuery($id);

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        5
    );

    return $this->render('front/avis/affiche_avis.html.twig', [
        'hotel' => $hotel,
        'pagination' => $pagination,
    ]);
}*/
#[Route('/avis/affich/{id}/{page<\d+>?1}', name: 'app_hotel_avis')]
public function hotelDetails(
    int $id,
    int $page,
    EntityManagerInterface $entityManager,
    PaginatorInterface $paginator,
    AviRepository $aviRepository,
    Request $request,
    HotelRepository $hotelRepository)
: Response {
    $hotel = $entityManager->getRepository(Hotel::class)->find($id);

    if (!$hotel) {
        throw $this->createNotFoundException('Hôtel non trouvé.');
    }

    $pagination = $paginator->paginate(
        $aviRepository->getAvisByHotelQuery($id),
        $page, // Numéro de page depuis l'URL
       3,     // Items par page
        [
            'pageParameterName' => 'page',
            'sortFieldParameterName' => 'sort',
            'sortDirectionParameterName' => 'direction',
            'filterFieldParameterName' => 'filter',
            'distinct' => true
        ]
    );
     // Suggestions basées sur la même ville
     $ville = $hotel->getVille(); // très propre
     $topHotels = $hotelRepository->findTopRatedHotelsByCity($ville);

    return $this->render('front/avis/affiche_avis.html.twig', [
        'hotel' => $hotel,
        'pagination' => $pagination,
        'query_params' => $request->query->all(), // Pour conserver les filtres
        'topHotels' => $topHotels,

    ]);
}

}
