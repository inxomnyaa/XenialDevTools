<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use xenialdan\apibossbar\BossBar;
use xenialdan\XenialDevTools\Loader;

class TestCameraEventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event)
    {
        return;
        $player = $event->getPlayer();
        Loader::getInstance()->getScheduler()->scheduleDelayedTask(new class($player) extends Task{
            private $player;
            public function __construct(Player $player)
            {
                $this->player = $player;
                $this->player->sendMessage("Camera path test starting in 15 seconds. Sneak to abort");
            }
            public function onRun(int $currentTick)
            {
                if(!$this->player->isSneaking())
                    Loader::getInstance()->cameraPathTest($this->player);
            }
        }, 20*15);
    }
}