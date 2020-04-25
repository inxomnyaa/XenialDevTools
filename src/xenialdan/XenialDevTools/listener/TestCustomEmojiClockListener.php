<?php

namespace xenialdan\XenialDevTools\listener;

use Closure;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use xenialdan\XenialDevTools\Loader;

class TestCustomEmojiClockListener implements Listener
{
    public static $strings = [
        "\u{E1FF}",
        "\u{E1FE}",
        "\u{E1FD}",
        "\u{E1FC}",
    ];

    public function onJoin(PlayerJoinEvent $event)
    {
        $closure = function (int $currentTick): void {
            $selected = next(self::$strings);
            if($selected === false) {
                $selected = reset(self::$strings);
            }
            foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                #$onlinePlayer->addTitle($selected, "", 1, 20, 1);
                $onlinePlayer->sendTip($selected);
                $onlinePlayer->sendPopup($selected);
                $onlinePlayer->addActionBarMessage($selected);
            }
        };
        /** @var Closure $closure */
        Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask($closure), 5, 5);
    }
}