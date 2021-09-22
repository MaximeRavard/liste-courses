<?php

namespace App\Controller;

use App\Entity\Aliment;
use App\Form\AlimentType;
use App\Repository\AlimentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursesController extends AbstractController
{
    /**
     * @Route("/courses", name="courses")
     */
    public function index(AlimentRepository $repo, Request $request): Response
    {
        $res = $repo->findAll();

        $al = new Aliment();
        $formAliment = $this->createForm(AlimentType::class, $al);
        $formAliment->handleRequest($request);
        if($formAliment->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($al);
            $em->flush();
            return $this->redirectToRoute('courses', ['listecourses' => $res, 'alimentForm' => $formAliment->createView()]);
        }
        return $this->render('courses/liste.html.twig', [
            'listecourses' => $res, 'alimentForm' => $formAliment->createView()
        ]);
    }

    /**
     * @Route("/courses/remove/{id}", name="supprAliment")
     */
    public function supprAliment(Aliment $aliment, EntityManagerInterface $em): Response
    {
        $em->remove($aliment);
        $em->flush();
        return $this->redirectToRoute('courses');
    }

    /**
     * @Route("/courses/check/{id}", name="checkAliment")
     */
    public function checkAliment(Aliment $aliment, EntityManagerInterface $em): Response
    {
        $aliment->setAchete(!$aliment->getAchete());
        $em->persist($aliment);
        $em->flush();
        return $this->redirectToRoute('courses');
    }


}
