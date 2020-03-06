<?php

namespace App\Service\MinuteProcessor;

use App\Contracts\MinuteProcessorInterface;
use App\Utils\MinuteBookmark;
use App\Enums\MinuteBookmarkCategory;

class NotubizXml implements MinuteProcessorInterface
{
    public function getMinuteBookmarks(array $minuteData): array
    {
        $minuteBookmarks = [];
        $bookmarks = isset($minuteData['timeline']['record']['bookmark'])? $minuteData['timeline']['record']['bookmark'] : [];

        foreach ($bookmarks as $bookmark) {
            array_push(
                $minuteBookmarks,
                (new MinuteBookmark())
                    ->setPosition(isset($bookmark['@position'])? $bookmark['@position'] : '')
                    ->setCategory(self::getMinuteBookmarkCategory(isset($bookmark['@category'])? $bookmark['@category']: ''))
                    ->setTitle(isset($bookmark['@title'])? $bookmark['@title'] : '')
                    ->setDescription(isset($bookmark['@pcontents'])? $bookmark['@pcontents'] : '')
            );
        }

        return $minuteBookmarks;
    }

    private function getMinuteBookmarkCategory(string $category): string
    {
        switch ($category) {
            case 'agenda-item':
                return MinuteBookmarkCategory::AGENDA_ITEM;
            case 'speaker':
                return MinuteBookmarkCategory::SPEAKER;
            default:
                return MinuteBookmarkCategory::UNDEFINED;
        }
    }
}
