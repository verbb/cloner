<?php
namespace verbb\cloner\events;

use yii\base\Event;

class RegisterClonerGroupEvent extends Event
{
    // Properties
    // =========================================================================

    public array $groups = [];
}
