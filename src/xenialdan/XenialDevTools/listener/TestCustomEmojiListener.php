<?php

namespace xenialdan\XenialDevTools\listener;

use Closure;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use xenialdan\apibossbar\BossBar;
use xenialdan\XenialDevTools\Loader;

class TestCustomEmojiListener implements Listener
{
    public static $bar;

    public function onJoin(PlayerJoinEvent $event)
    {
        $pickaxe = Item::get(Item::DIAMOND_PICKAXE);
        $pickaxe->setCustomName(TextFormat::colorize("§l§b\u{E1FD} Cool Pickaxe", "§"));
        if (!$event->getPlayer()->getInventory()->contains($pickaxe)) $event->getPlayer()->getInventory()->addItem($pickaxe);
        if (self::$bar === null) self::$bar = (new BossBar())->setTitle("\u{E1FE} " . TextFormat::BOLD . TextFormat::GRAY . "WolvesFortress")->setPercentage(1.0);
        self::$bar->addPlayer($event->getPlayer());
        $closure = function (int $currentTick): void {
            $strings = [
                #"\u{E1FF} Test \u{E1FF} Custom \u{E1FF}",
                "\u{E1FE} " . TextFormat::BOLD . TextFormat::GRAY . "WolvesFortress ".TextFormat::RESET,
                TextFormat::ITALIC."\u{E1FC} Me thonks..",
                "\u{E1FD}" . TextFormat::WHITE . "[" . TextFormat::BOLD . TextFormat::DARK_AQUA . "Builder" . TextFormat::RESET . TextFormat::WHITE . "]".TextFormat::RESET,
            ];
            $selected = $strings[array_rand($strings)];
            foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                $onlinePlayer->sendMessage($selected);
                $onlinePlayer->addTitle(" ", $selected, 0, 20 * 3, 0);
                $onlinePlayer->addSubTitle($selected);
                $onlinePlayer->addActionBarMessage($selected);
                $onlinePlayer->sendTip($selected);
                $onlinePlayer->sendPopup($selected);
                $onlinePlayer->setDisplayName($selected . $onlinePlayer->getName());
                $onlinePlayer->setScoreTag($selected);
            }
        };
        /** @var Closure $closure */
        Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask($closure), 20 * 5, 20 * 5);
    }
}