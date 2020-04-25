<?php

namespace xenialdan\XenialDevTools\command;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xenialdan\XenialDevTools\Loader;

class DelEntityCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("pocketmine.command.op");
        $this->setDescription("Delete entity when hit");
        $this->setUsage("/delentity");
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

        if (!$sender instanceof Player) return false;

        Loader::$delEntities[$sender->getId()] = true;

        return true;
    }
}