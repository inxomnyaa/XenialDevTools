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
use xenialdan\MagicWE2\exception\SessionException;
use xenialdan\MagicWE2\helper\SessionHelper;
use xenialdan\MagicWE2\selection\shape\Shape;

class ShowMWEPasteChunksCommand extends PluginCommand
{
    public function __construct(string $name, Plugin $owner)
    {
        parent::__construct($name, $owner);
        $this->setPermission("pocketmine.command.op");
        $this->setDescription("If possible, send a structure block to the player with the current MWE2 clipboard paste chunks");
        $this->setUsage("//pastechunks");
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
                    /** @var Shape $shape */
                    $shape = $clipboard->selection->getShape();
                    $shape->setPasteVector($sender->asVector3()->floor()->subtract($clipboard->position->floor()));
                    #$clipboard->selection->setShape($shape);
                    $touchedChunks1 = $shape->getTouchedChunks($sender->getLevel());//TODO check if this is an ugly hack
                    $touchedChunks = array_map(function ($chunk) {
                        return Chunk::fastDeserialize($chunk);
                    }, $touchedChunks1);
                    $chunkMinX = $chunkMinZ = $chunkMaxX = $chunkMaxZ = null;
                    foreach ($touchedChunks as $chunk) {
                        /** @var Chunk $chunk */
                        if($chunkMinX === null || $chunkMinX>$chunk->getX())$chunkMinX = $chunk->getX();
                        if($chunkMinZ === null || $chunkMinZ>$chunk->getX())$chunkMinZ = $chunk->getZ();
                        if($chunkMaxX === null || $chunkMaxX<$chunk->getX())$chunkMaxX = $chunk->getX();
                        if($chunkMaxZ === null || $chunkMaxZ<$chunk->getX())$chunkMaxZ = $chunk->getZ();
                    }//TODO REMOVE
                    $min = new Vector3($chunkMinX*16,0, $chunkMinZ*16);
                    $max = new Vector3($chunkMaxX*16+15, Level::Y_MAX, $chunkMaxZ*16+15);
                    $name = basename(get_class($shape));
                    $menu = InvMenu::create(StructureUI::class, StructureUI::getMinV3($min, $max), StructureUI::getMaxV3($min, $max), $name);
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