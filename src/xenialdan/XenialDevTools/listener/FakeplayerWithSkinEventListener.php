<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;
use pocketmine\entity\Human;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\PlayerSkinPacket;
use pocketmine\network\mcpe\protocol\types\SkinAdapterSingleton;
use pocketmine\utils\UUID;

class FakeplayerWithSkinEventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event)
    {
        $uuid = UUID::fromRandom();
        $player = $event->getPlayer();
        $pk = new PlayerSkinPacket();
        $pk->skin = SkinAdapterSingleton::get()->toSkinData($player->getSkin());
        $pk->uuid = $uuid;
        $player->dataPacket($pk);

        $pk = new AddPlayerPacket();
        $pk->uuid = $uuid;
        $pk->username =$player->getDisplayName();
        $pk->entityRuntimeId = Entity::$entityCount++;
        $pk->position = $player->asVector3();
        $pk->motion = new Vector3();
        $pk->yaw = $player->yaw;
        $pk->pitch = $player->pitch;
        $pk->item = $player->getInventory()->getItemInHand();
        $pk->metadata = $player->getDataPropertyManager()->getAll();
        $player->dataPacket($pk);
    }
}