<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\entity\Vehicle;
use pocketmine\event\block\BlockBurnEvent;
use pocketmine\event\block\BlockGrowEvent;
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\particle\GenericParticle;
use pocketmine\level\particle\Particle;
use pocketmine\math\Vector2;

class StopAllBlockUpdatesEventListener implements Listener
{

    public function onUpdate(BlockUpdateEvent $event)
    {
        $event->setCancelled();
    }

    public function onGrow(BlockGrowEvent $event)
    {
        $event->setCancelled();
    }

    public function onSpread(BlockSpreadEvent $event)
    {
        $event->setCancelled();
    }

    public function onBurn(BlockBurnEvent $event)
    {
        $event->setCancelled();
    }
}