<?php

namespace App\Service;

use App\Contracts\MinuteProcessorInterface;
use App\Entity\ViewObject;
use App\Enums\MediaType;
use App\Service\MinuteProcessor\NotubizXml;
use App\Utils\MinuteInterpreter;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ViewObjectInterpreter
{
    static public function processViewObject(ViewObject $viewObject): ViewObject
    {
        $minuteInterpreter = self::findMinuteInterpreter($viewObject->getMinuteLink());

        $viewObject
            ->setMediaFormat(pathinfo($viewObject->getMediaLink(), PATHINFO_EXTENSION))
            ->setMediaType(self::getMediaType($viewObject))
            ->setMinuteInterpreter($minuteInterpreter->getMinuteInterpreter())
            ->setMinuteVersion($minuteInterpreter->getMinuteVersion())
            ->setSlug(md5(uniqid (rand (),true)));
        return $viewObject;
    }

    static public function getMinuteBookmarks(ViewObject $viewObject): array
    {
        $minuteProcessor = self::getMinuteProcessor($viewObject);

        if (!$minuteProcessor instanceof MinuteProcessorInterface) {
            return [];
        }

        $minuteData = self::getMinuteData($viewObject->getMinuteLink());

        return $minuteProcessor->getMinuteBookmarks($minuteData);
    }

    static public function validateUrls(ViewObject $viewObject): ?string
    {
        $mediaResponse = Validator::validateUrl($viewObject->getMediaLink());
        if (!$mediaResponse) {
            return 'media link not valid';
        } elseif (!strpos($mediaResponse, '200')) {
            return 'media file not found';
        }

        $minuteResponse = Validator::validateUrl($viewObject->getMinuteLink());
        if (!$minuteResponse) {
            return 'minute link not valid';
        } elseif (!strpos($minuteResponse, '200')) {
            return 'minute file not found';
        }

        return null;
    }

    static private function findMinuteInterpreter(string $minuteLink): MinuteInterpreter
    {
        $minuteData = self::getMinuteData($minuteLink);
        $minuteInterpreter = new MinuteInterpreter();

        if (empty($minuteData)) {
            $minuteInterpreter->setMinuteInterpreter(MinuteInterpreter::FILE_NOT_FOUND);
        } elseif (key_exists('@version', $minuteData) && key_exists('notes', $minuteData) && key_exists('timeline', $minuteData)) {
            $minuteInterpreter->setMinuteInterpreter(MinuteInterpreter::NOTUBIZ_XML);
            $minuteInterpreter->setMinuteVersion($minuteData['@version']);
        } else {
            $minuteInterpreter->setMinuteInterpreter(MinuteInterpreter::NOT_SUPPORTED);
        }

        return $minuteInterpreter;
    }

    static private function getMediaType(ViewObject $viewObject): string
    {
        switch($viewObject->getMediaFormat())
        {
            case 'mp3':
                return MediaType::AUDIO;
            default:
                return MediaType::UNSUPPORTED;
        }
    }

    static private function getMinuteProcessor(ViewObject $viewObject): ?MinuteProcessorInterface
    {
        switch ($viewObject->getMinuteInterpreter()) {
            case 'notubiz-xml':
                return new NotubizXml();
        }

        return null;
    }

    static private function getMinuteData(string $minuteLink): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_URL, $minuteLink);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if(FALSE === ($response = curl_exec($ch))) {
            error_log(curl_error($ch));
            return [];
        }

        switch (pathinfo($minuteLink, PATHINFO_EXTENSION)) {
            case 'xml':
                return (new XmlEncoder())->decode($response, 'xml');
            default:
                return [];
        }
    }
}
