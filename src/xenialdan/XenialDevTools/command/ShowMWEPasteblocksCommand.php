<?php

namespace xenialdan\XenialDevTools\command;

use muqsit\invmenu\InvMenu;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ShowProfilePacket;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xenialdan\libstructure\StructureUI;
use xenialdan\MagicWE2\clipboard\SingleClipboard;
use xenialdan\MagicWE2\exception\SessionException;
use xenialdan\MagicWE2\helper\SessionHelper;
use xenialdan\MagicWE2\selection\shape\Shape;

class ShowMWEPasteblocksCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("pocketmine.command.op");
        $this->setDescription("If possible, send a structure block to the player with the current MWE2 clipboard paste blocks");
        $this->setUsage("//pasteblocks");
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
                $clipboard = $session->getCurrentClipboard();
                if (is_null($clipboard)) return true;
                try {
                    /** @var SingleClipboard $clipboard */
                    /** @var Shape $shape */
                    $shape = clone $clipboard->selection->getShape();
                    #$shape->setPasteVector($sender->add($clipboard->position)->add(1,0,1)->floor());
                    #$clipboard->selection->setShape($shape);
                    $aabb = clone $shape->getAABB();
                    $offset = clone $clipboard->position->floor();
                    $aabbCopy = $aabb->offsetCopy(-$offset->getX(),-$offset->getY(),-$offset->getZ());
                    $min = new Vector3($aabb->minX, $aabb->minY, $aabb->minZ);
                    $max = new Vector3($aabb->maxX, $aabb->maxY, $aabb->maxZ);
                    $mincopy = new Vector3($aabbCopy->minX, $aabbCopy->minY, $aabbCopy->minZ);
                    $maxcopy = new Vector3($aabbCopy->maxX, $aabbCopy->maxY, $aabbCopy->maxZ);
                    $name = basename(get_class($shape));
                    $menu = InvMenu::create(StructureUI::class, StructureUI::getMinV3($min, $max), StructureUI::getMaxV3($min, $max), $name);
                    $menu->send($sender);
                    $name = basename(get_class($shape));
                    $menu = InvMenu::create(StructureUI::class, StructureUI::getMinV3($mincopy, $maxcopy), StructureUI::getMaxV3($mincopy, $maxcopy), $name);
                    $menu->send($sender);
                } catch (\Exception $ex) {
                    $sender->sendMessage($ex->getMessage());
                    return false;
                }
            } catch (SessionException $e) {
            }

            return true;
        }
        return false;
    }
}