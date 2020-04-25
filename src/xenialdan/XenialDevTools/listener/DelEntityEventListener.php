<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xenialdan\XenialDevTools\Loader;

class DelEntityEventListener implements Listener
{
    /** @var Loader */
    public $owner;

    public function __construct(Plugin $plugin)
    {
        $this->owner = $plugin;
    }

    public function onEntityHit(EntityDamageEvent $event): void
    {
        if ($event instanceof EntityDamageByEntityEvent) {
            if (($player = $event->getDamager()) instanceof Player) {
                if (array_key_exists($player->getId(), Loader::$delEntities)) {
                    $event->getEntity()->flagForDespawn();
                    unset(Loader::$delEntities[$player->getId()]);
                }
            }
        }
    }
}