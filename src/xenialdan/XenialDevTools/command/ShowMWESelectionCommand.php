<?php

namespace xenialdan\XenialDevTools\command;

use muqsit\invmenu\InvMenu;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ShowProfilePacket;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xenialdan\libstructure\StructureUI;
use xenialdan\MagicWE2\exception\SessionException;
use xenialdan\MagicWE2\helper\SessionHelper;

class ShowMWESelectionCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("pocketmine.command.op");
        $this->setDescription("If possible, send a structure block ui to the player with the current MWE2 selection");
        $this->setUsage("//selection");
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

            try {
                $session = SessionHelper::getUserSession($sender);
                if (is_null($session)) return true;
                $selection = $session->getLatestSelection();
                if (is_null($selection)) return true;
                try {
                    $shape = $selection->getShape();
                } catch (\Exception $ex) {
                    $sender->sendMessage($ex->getMessage());
                    return false;
                }
                $aabb = $shape->getAABB();
                $min = new Vector3($aabb->minX, $aabb->minY, $aabb->minZ);
                $max = new Vector3($aabb->maxX, $aabb->maxY, $aabb->maxZ);
                $name = basename(get_class($shape));
                $menu = InvMenu::create(StructureUI::class, StructureUI::getMinV3($min, $max), StructureUI::getMaxV3($min, $max), $name);
                $menu->send($sender);
            } catch (SessionException $e) {
            }

            return true;
        }
        return false;
    }
}