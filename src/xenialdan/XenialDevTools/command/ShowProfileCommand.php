<?php

namespace xenialdan\XenialDevTools\command;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\network\mcpe\protocol\ShowProfilePacket;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class ShowProfileCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("pocketmine.command.op");
        $this->setDescription("ShowProfilePacket for specific player");
        $this->setUsage("/showprofile <playername>");
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

            if (!$sender instanceof Player || empty($args)) return false;

            $player = $this->getPlugin()->getServer()->getPlayer(array_shift($args));

            if (!$player instanceof Player) return false;

            $sender->sendMessage("Trying to send show profile packet for " . $player->getName());

            $pk = new ShowProfilePacket();
            $pk->xuid = $player->getXuid();

            $sender->dataPacket($pk);

            return true;
        }
        return false;
    }
}