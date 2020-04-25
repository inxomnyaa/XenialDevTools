<?php

namespace xenialdan\XenialDevTools\listener;

use BlockHorizons\Fireworks\entity\FireworksRocket;
use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use xenialdan\XenialDevTools\Loader;

class TestFireworkListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event)
    {
        /** @var \Closure $closure */

        $player = $event->getPlayer();

        $closure = function (int $currentTick): void {

            /** @var Fireworks $fw */
            $fw = ItemFactory::get(Item::FIREWORKS);
            $fw->addExplosion(Fireworks::TYPE_CREEPER_HEAD, Fireworks::COLOR_GREEN, "", false, false);
            $fw->setFlightDuration(2);

            $level = Server::getInstance()->getDefaultLevel();
            $vector3 = $level->getSpawnLocation()->add(0.5, 1, 0.5);

            $nbt = FireworksRocket::createBaseNBT($vector3, new Vector3(0.001, 0.05, 0.001), lcg_value() * 360, 90);
            $entity = FireworksRocket::createEntity("FireworksRocket", $level, $nbt, $fw);
            if ($entity instanceof Entity) {
                $entity->spawnToAll();
            }
        };
        Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask($closure), 20 * 5, 20 * 5);

        if ($player->getInventory()->firstEmpty() !== 0) {
            return;
        }

        /** @var Fireworks $fw */
        $fw = ItemFactory::get(Item::FIREWORKS);
        $fw->addExplosion(Fireworks::TYPE_CREEPER_HEAD, Fireworks::COLOR_GREEN, "", false, false);
        $fw->setFlightDuration(2);
        $player->getInventory()->addItem($fw);

        /** @var Fireworks $fw */
        $fw = ItemFactory::get(Item::FIREWORKS);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_BLUE, Fireworks::COLOR_DARK_AQUA, false, false);
        $fw->addExplosion(Fireworks::TYPE_HUGE_SPHERE, Fireworks::COLOR_GOLD, "", false, true);
        $fw->setFlightDuration(1);
        $player->getInventory()->addItem($fw);

        /** @var Fireworks $fw */
        $fw = ItemFactory::get(Item::FIREWORKS);
        $fw->addExplosion(Fireworks::TYPE_STAR, Fireworks::COLOR_YELLOW, "", true, true);
        $fw->setFlightDuration(3);
        $player->getInventory()->addItem($fw);

        /** @var Fireworks $fw */
        $fw = ItemFactory::get(Item::FIREWORKS);
        $fw->addExplosion(Fireworks::TYPE_BURST, Fireworks::COLOR_PINK, Fireworks::COLOR_DARK_PINK, false, false);
        $fw->setFlightDuration(1);
        $player->getInventory()->addItem($fw);

        /** @var Fireworks $fw */
        $fw = ItemFactory::get(Item::FIREWORKS);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_BLACK, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_RED, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_GREEN, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_BROWN, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_BLUE, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_PURPLE, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_AQUA, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_GRAY, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_GRAY, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_PINK, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_GREEN, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_YELLOW, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_LIGHT_AQUA, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_DARK_PINK, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_GOLD, "", false, true);
        $fw->addExplosion(Fireworks::TYPE_SMALL_SPHERE, Fireworks::COLOR_WHITE, "", false, true);
        $fw->setFlightDuration(1);
        $player->getInventory()->addItem($fw);
    }
}