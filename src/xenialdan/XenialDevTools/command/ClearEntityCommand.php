<?php

namespace xenialdan\XenialDevTools\command;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class ClearEntityCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("pocketmine.command.op");
        $this->setDescription("Delete all entities in the current level");
        $this->setUsage("/clearentity");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$this->getPlugin()->isEnabled()) {
            return false;
        }

        if (!$this->testPermission($sender)) {
            $sender->sendMessage($this->getPermissionMessage());
            return false;
        }

        if (!$sender instanceof Player) $level = $sender->getServer()->getDefaultLevel();
        else $level = $sender->getLevel();
        /** @var Entity $e */
        foreach (array_filter($level->getEntities(), function (Entity $entity) {
            return !$entity instanceof Player;
        }) as $e) $e->close();

        return true;
    }
}