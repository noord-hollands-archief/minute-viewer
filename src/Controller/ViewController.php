<?php

namespace App\Controller;

use App\Entity\ViewObject;
use App\Enums\MediaType;
use App\Repository\ViewObjectRepository;
use App\Service\ViewObjectInterpreter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view")
 */
class ViewController extends AbstractController
{
    /**
     * @Route("/{viewSlug}/", name="viewer")
     */
    public function viewer(string $viewSlug, ViewObjectRepository $viewObjectRepository)
    {
        $viewObject = $viewObjectRepository->findOneBy(['slug' => $viewSlug]);

        if(!$viewObject instanceof ViewObject) {
            throw $this->createNotFoundException('view object not found');
        }

        $minuteBookmarks = ViewObjectInterpreter::getMinuteBookmarks($viewObject);

        switch ($viewObject->getMediaType()) {
            case MediaType::AUDIO:
                $template = 'viewer/audio.html.twig';
                break;
            case MediaType::VIDEO:
                $template = 'viewer/video.html.twig';
                break;
            case MediaType::UNSUPPORTED:
            default:
                $template = 'viewer/unsupported.html.twig';
        }

        return $this->render($template, [
            'viewObject' => $viewObject,
            'minuteBookmarks' => $minuteBookmarks,
        ]);
    }
}
