<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use verbb\events\models\EventType;

class EventTypes extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/event-type';
    public static string $id = 'types';
    public static string $matchedRoute = 'events/event-types';
    public static string $pluginHandle = 'events';
    public static string $title = 'Event Type';


    // Public Methods
    // =========================================================================

    public function setupClonedEventType(EventType $oldEventType, string $name, string $handle): EventType
    {
        $eventType = clone $oldEventType;
        $eventType->id = null;
        $eventType->uid = null;
        $eventType->fieldLayoutId = null;
        $eventType->sessionFieldLayoutId = null;
        $eventType->ticketTypeFieldLayoutId = null;
        $eventType->name = $name;
        $eventType->handle = $handle;

        // Each of the event, session and ticket type layouts needs its own copy so the clone never shares a layout record.
        $eventType->getBehavior('eventFieldLayout')->setFieldLayout($this->getFieldLayout($oldEventType->getEventFieldLayout()));
        $eventType->getBehavior('sessionFieldLayout')->setFieldLayout($this->getFieldLayout($oldEventType->getSessionFieldLayout()));
        $eventType->getBehavior('ticketFieldLayout')->setFieldLayout($this->getFieldLayout($oldEventType->getTicketTypeFieldLayout()));

        $allSiteSettings = [];

        foreach ($oldEventType->getSiteSettings() as $siteId => $oldSiteSettings) {
            $siteSettings = clone $oldSiteSettings;
            $siteSettings->id = null;
            $siteSettings->eventTypeId = null;

            $allSiteSettings[$siteId] = $siteSettings;
        }

        $eventType->setSiteSettings($allSiteSettings);

        return $eventType;
    }

}
