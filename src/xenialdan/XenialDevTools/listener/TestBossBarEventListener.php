<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use xenialdan\apibossbar\BossBar;
use xenialdan\XenialDevTools\Loader;

class TestBossBarEventListener implements Listener
{

    /** @var BossBar */
    private $bar1;

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        #$this->bar1 = (new BossBar())->setTitle("Hello " . $player->getDisplayName())->setPercentage(0.0)->addPlayer($player);
        #print $this->bar1;
        Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new class($player) extends Task
        {
            private $bar;

            public function __construct(Player $player)
            {
                $this->bar = (new BossBar())->setTitle(date('H:i:s'))->setPercentage((float)max(0.01, floatval(date('s')) / 60))->setSubTitle(date('d M Y'))->addPlayer($player);
                print $this->bar;
            }

            public function onRun(int $currentTick)
            {
                $this->bar->setTitle(date('H:i:s'))->setSubTitle(date('d M Y'))->setPercentage((float)max(0.01, floatval(date('s')) / 60));
            }
        }, 20, 20);
    }
}