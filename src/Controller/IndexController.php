<?php

namespace App\Controller;

use App\Entity\ViewObject;
use App\Form\ViewObjectType;
use App\Repository\ViewObjectRepository;
use App\Service\ViewObjectInterpreter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function index(Request $request, ViewObjectRepository $viewObjectRepository)
    {
        $viewObject = new ViewObject();
        $form = $this->createForm(ViewObjectType::class, $viewObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $urlValidationError = ViewObjectInterpreter::validateUrls($viewObject);
            if (!is_null($urlValidationError)) {
                throw $this->createNotFoundException($urlValidationError);
            }

            $viewObject = $viewObjectRepository->insert($viewObject);

            return $this->redirectToRoute('viewer', [
                'viewSlug' => $viewObject->getSlug(),
            ]);
        }

        return $this->render('homepage/index.html.twig', [
            'view_object' => $viewObject,
            'form' => $form->createView(),
        ]);
    }
}
