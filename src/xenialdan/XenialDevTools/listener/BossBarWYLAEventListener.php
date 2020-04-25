<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use xenialdan\apibossbar\BossBar;
use xenialdan\MagicWE2\helper\BlockStatesEntry;
use xenialdan\MagicWE2\helper\BlockStatesParser;
use xenialdan\XenialDevTools\Loader;

class BossBarWYLAEventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new class($player) extends Task
        {
            private $bar;

            public function __construct(Player $player)
            {
                $this->bar = (new BossBar())->addPlayer($player);
                $this->bar->hideFromAll();
            }

            public function onRun(int $currentTick)
            {
                $players = $this->bar->getPlayers();
                foreach ($players as $player) {
                    if (!$player->isOnline()) {
                        #$this->bar->removePlayer($player);
                        continue;
                    }
                    if (($block = $player->getTargetBlock(6)) instanceof Block && $block->getId() !== 0) {
                        $this->bar->showTo([$player]);
                        $stateEntry = BlockStatesParser::getStateByBlock($block);
                        $sub = $block->getName();
                        $title = strval($block);
                        if ($stateEntry instanceof BlockStatesEntry) {
                            $sub = implode("," . TextFormat::EOL, explode(",", strval($stateEntry)));
                        }
                        $this->bar->setTitle($title)->setSubTitle($sub);
                    } else
                        $this->bar->hideFrom([$player]);
                }
            }
        }, 60, 1);
    }
}