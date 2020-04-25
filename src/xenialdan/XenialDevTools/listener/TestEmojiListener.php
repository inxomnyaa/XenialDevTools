<?php

namespace xenialdan\XenialDevTools\listener;

use Closure;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use xenialdan\customui\windows\ModalForm;
use xenialdan\XenialDevTools\Loader;

class TestEmojiListener implements Listener
{
    const GRID = 16;//16 * 16 icons

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $filename = basename("glyph_E1", ".png");
        $startChar = hexdec(substr($filename, strrpos($filename, "_") + 1) . "00");
        $i = 0;
        do {
            $x = $i % self::GRID;
            $z = ($i - ($i % self::GRID)) / self::GRID;
            $ci = $startChar + $i;//char index
            $hex = dechex($ci);
            $char = mb_chr($ci);
            $msg = "I:$i X:$x Z:$z H:$hex C#:$ci C:$char";
            $player->sendMessage($msg);
        } while (++$i < self::GRID ** 2);
        return;
        $closure = function (int $currentTick): void {
            $strings = [
                "\u{E100}",
                "\u{E101}",
                "\u{E102}",
                "\u{E103}",
                "\u{E104}",
                "\u{E100}\u{E101}\u{E102}\u{E103}\u{E104}",
                "\u{E000}\u{E001}\u{E002}\u{E003}\u{E004}\u{E005}\u{E006}\u{E007}\u{E008}\u{E009}\u{E00A}\u{E00B}\u{E00C}\u{E00D}\u{E00E}\u{E00F}\u{E010}\u{E011}\u{E012}\u{E013}\u{E020}\u{E021}\u{E022}\u{E023}\u{E024}\u{E025}\u{E026}\u{E027}\u{E028}\u{E029}\u{E02A}\u{E02B}\u{E02C}\u{E02D}\u{E02E}\u{E02F}\u{E040}\u{E041}\u{E042}\u{E043}\u{E044}\u{E045}\u{E046}\u{E047}\u{E048}\u{E049}\u{E04A}\u{E04B}\u{E04C}\u{E04D}\u{E04E}\u{E04F}\u{E060}\u{E061}\u{E062}\u{E063}\u{E065}\u{E066}\u{E067}\u{E068}\u{E069}\u{E06A}\u{E06B}\u{E06C}\u{E06D}\u{E06E}\u{E06F}\u{E070}\u{E071}\u{E072}\u{E073}\u{E075}\u{E076}\u{E080}\u{E081}\u{E082}\u{E083}\u{E084}\u{E085}\u{E086}\u{E087}\u{E0A0}\u{E0A1}\u{E100}\u{E101}\u{E102}\u{E103}\u{E104}\u{E102}\u{E0C0}\u{E0C1}\u{E0C2}\u{E0C3}\u{E0C4}\u{E0C5}\u{E0C6}\u{E0C7}\u{E0C8}\u{E0C9}\u{E0CA}\u{E0CB}\u{E0CC}\u{E0CD}\u{E0E0}\u{E0E1}\u{E0E2}\u{E0E3}\u{E0E4}\u{E0E5}\u{E0E6}\u{E0E7}\u{E0E8}\u{E0E9}\u{E0EA}",
            ];
            $selected = $strings[array_rand($strings)];
            print $selected.PHP_EOL;
            foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                $onlinePlayer->sendMessage($selected);
            }
            if (mt_rand(0, 1) === 0) {
                foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                    $onlinePlayer->addTitle($selected, $selected, 0, 20, 0);
                }
            } else {
                $ui = new ModalForm($selected, $selected, "OK", "OK");
                foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                    $onlinePlayer->sendForm($ui);
                }
            }
        };
        /** @var Closure $closure */
        Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask($closure), 20 * 5, 20 * 10);
    }
}