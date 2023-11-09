<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}")
 */
class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="Accueil")
     */
    public function accueil(Request $request)
    {
        return  $this->render('gnt/accueil.html.twig');
    }
    /**
     * @Route("/About", name="About")
     */
    public function about(Request $request)
    {
        return  $this->render('gnt/about.html.twig');
    }

    /**
     * @Route("/Logistique", name="Logistique")
     */
    public function planning(Request $request)
    {
        return  $this->render('gnt/logistique.html.twig');
    }

    /**
     * @Route("/Distribution", name="Distribution")
     */
    public function improve(Request $request)
    {
        return  $this->render('gnt/distribution.html.twig');
    }

    /**
     * @Route("/Promotion", name="Promotion")
     */
    public function securite(Request $request)
    {
        return  $this->render('gnt/promotion.html.twig');
    }

    /**
     * @Route("/Actualite", name="Actualite")
     */
    public function actualite(Request $request)
    {
        return  $this->render('gnt/actualite.html.twig');
    }

    /**
     * @Route("/Contact", name="Contact")
     */
    public function contact(Request $request)
    {
        return  $this->render('gnt/contact.html.twig');
    }

    /**
     * @Route("/Carriere", name="Carriere")
     */
    public function realisations(Request $request)
    {
        $candidature =  new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $candidature->getCv()->upload($candidature);
                $em->persist($candidature);
                $em->flush();
                $this->addFlash('notice', 'Candidature enregistrée avec succée');
                return $this->redirectToRoute('Carriere');
            }
        }
        return  $this->render('gnt/carrieres.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/Gallery-photo", name="Gallery_photo")
     */
    public function photo(Request $request)
    {
        $albums = $this->getDoctrine()->getRepository(Album::class)->findAll();
        return  $this->render('gnt/galleryphoto.html.twig',[
            'albums' => $albums,
        ]);
    }

    /**
     * @Route("/Gallery/Photo/Album/{album}", name="Gallery_photo_album_view")
     */
    public function photo_reservoir(Request $request, Album $album, ImageRepository $repository)
    {
        return  $this->render('gnt/reservoir.html.twig',[
            'album' => $album,
            'images' => $repository->findBy(['album' => $album]),
        ]);
    }

}
