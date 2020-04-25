<?php

namespace xenialdan\XenialDevTools\command;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class VanishCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("pocketmine.command.op");
        $this->setDescription("Toggle vanish");
        $this->setUsage("/vanish");
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
        if (true) {

            if (!$sender instanceof Player) return false;
            $sender->setInvisible(!$sender->isInvisible());
            $sender->sendMessage("Vanish mode: " . ($sender->isInvisible() ? TextFormat::GREEN . "ON" : TextFormat::RED . "OFF"));
            return true;
        }
        return false;
    }
}