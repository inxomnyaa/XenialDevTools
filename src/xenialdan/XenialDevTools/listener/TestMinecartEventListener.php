<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\entity\Vehicle;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\particle\GenericParticle;
use pocketmine\level\particle\Particle;
use pocketmine\math\Vector2;

class TestMinecartEventListener implements Listener
{

    public function onMove(EntityMotionEvent $event)
    {
        if(!($cart = $event->getEntity()) instanceof Vehicle) return;
        $player = $event->getPlayer();
        $v3 = $player->asVector3();
        $yaw = ($player->yaw - 90)%360;
        $add = (new Vector2(-cos(deg2rad($yaw) - M_PI_2), -sin(deg2rad($yaw) - M_PI_2)))->normalize();
        $v3 = $v3->add($add->x, 0, $add->y);
        $player->sendTip($player->getLevel()->getBlock($v3)->__toString() . " " . $v3->__toString());
        $player->getLevel()->addParticle(new GenericParticle($v3, Particle::TYPE_DRIP_LAVA));
    }
}