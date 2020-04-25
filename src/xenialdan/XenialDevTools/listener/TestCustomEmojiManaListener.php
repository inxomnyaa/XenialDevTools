<?php

namespace xenialdan\XenialDevTools\listener;

use Closure;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use xenialdan\XenialDevTools\Loader;

class TestCustomEmojiManaListener implements Listener
{
    const LEFT = "\u{E1F7}";
    const EMPTY = "\u{E1F8}";
    const HALF = "\u{E1F9}";
    const FULL = "\u{E1FA}";
    const RIGHT = "\u{E1FB}";
    public static $strings = [
        "",
        "1",
        "2",
        "21",
        "22",
        "221",
        "222",
        "2221",
        "2222",
        "22221",
        "22222",
        "222221",
        "222222",
        "2222221",
        "2222222",
        "22222221",
        "22222222",
        "222222221",
        "222222222",
        "2222222221",
        "2222222222",
    ];

    public function onJoin(PlayerJoinEvent $event)
    {
        $closure = function (int $currentTick): void {
            $selected = next(self::$strings);
            if ($selected === false) {
                $selected = reset(self::$strings);
            }
            foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                $str = TextFormat::RESET.str_replace(["0", "1", "2"], [self::EMPTY, self::HALF, self::FULL], str_pad($selected, 10, "0")) . self::RIGHT;
                $message = TextFormat::BOLD.TextFormat::DARK_RED."Fire power\n$str\n";
                $message .= TextFormat::BOLD.TextFormat::BLUE."Water power\n$str\n";
                $message .= TextFormat::BOLD.TextFormat::DARK_PURPLE."Psychic Remote Help power\n$str\n";
                $onlinePlayer->sendPopup(mb_convert_encoding($message, 'UTF-8'));
            }
        };
        /** @var Closure $closure */
        Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask($closure), 20, 1);
    }
}