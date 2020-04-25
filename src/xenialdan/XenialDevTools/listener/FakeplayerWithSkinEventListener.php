<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\AddActorPacket;

class FakeplayerWithSkinEventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $pk = new AddActorPacket();
        $pk->entityRuntimeId = $this->entityId;
        $pk->type = $this->getEntity() instanceof Entity ? $this->getEntity()::NETWORK_ID : static::NETWORK_ID;
        $pk->attributes = $this->getAttributeMap()->getAll();
        $pk->metadata = $this->getPropertyManager()->getAll();
        $pk->position = $player->asVector3()->subtract(0, 28);
        $player->dataPacket($pk);
    }
}