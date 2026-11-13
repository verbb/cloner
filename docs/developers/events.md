# Events

Cloner provides events for extending its functionality. Modules and plugins can register event listeners, typically in their `init()` methods, to change Cloner’s behaviour.

## Clone Button Events

### The `registerClonerGroups` Event
The event that is triggered when Cloner gathers the settings screens that show a clone button, on each control panel request from an admin.

The event’s `groups` property is an array keyed by the control panel route of each screen, such as `sections/index`. Each value holds the `id` of the screen’s table, the `title` used in the prompt for the new name, and the `action` of Cloner’s clone controller (without the `cloner/` prefix). Removing a group hides the clone buttons on that screen. A group can only point to one of Cloner’s own clone actions, such as `clone/section`.

```php
use verbb\cloner\base\Service;
use verbb\cloner\events\RegisterClonerGroupEvent;
use yii\base\Event;

Event::on(Service::class, Service::EVENT_REGISTER_CLONER_GROUPS, function(RegisterClonerGroupEvent $event) {
    // Hide the clone buttons on the Sites screen
    unset($event->groups['sites/settings-index']);
});
```
