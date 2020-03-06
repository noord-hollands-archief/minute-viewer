<?php

namespace App\Controller;

use App\Entity\ViewObject;
use App\Repository\ViewObjectRepository;
use App\Service\ViewObjectInterpreter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/create-view", name="api_create_view", methods={"POST"})
     */
    public function new(Request $request, ViewObjectRepository $viewObjectRepository): Response
    {
        $requestBody = json_decode($request->getContent(), true);

        if (is_null($requestBody)) {
            return $this->json([
                'message' => 'invalid body send'
            ], 400);
        }

        if (!key_exists('mediaLink', $requestBody)) {
            return $this->json([
                'message' => 'missing value: mediaLink'
            ], 200);
        }

        if (!key_exists('minuteLink', $requestBody)) {
            return $this->json([
                'message' => 'missing value: minuteLink'
            ], 200);
        }

        $viewObject = (new ViewObject())
            ->setMediaLink($requestBody['mediaLink'])
            ->setMinuteLink($requestBody['minuteLink']);

        $urlValidationError = ViewObjectInterpreter::validateUrls($viewObject);
        if (!is_null($urlValidationError)) {
            return $this->json([
                'message' => $urlValidationError,
            ], 200);
        }

        $viewObject = $viewObjectRepository->insert($viewObject);

        return $this->json([
            'message' => 'created',
            'url' => $request->getSchemeAndHttpHost() . '/view/' . $viewObject->getSlug(),
        ], 201);
    }
}
