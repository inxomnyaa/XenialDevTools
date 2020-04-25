<?php

namespace xenialdan\XenialDevTools\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use xenialdan\XenialDevTools\Loader;
use xenialdan\XenialDevTools\scoreboard\Scoreboard;
use xenialdan\XenialDevTools\scoreboard\ScoreboardDisplaySlot;
use xenialdan\XenialDevTools\scoreboard\ScoreboardObjective;
use xenialdan\XenialDevTools\scoreboard\ScoreboardSortOrder;

class ScoreboardDebugEventListener implements Listener
{
    /** @var Loader */
    public $owner;
    /** @var ScoreboardObjective|null */
    private $scoreboardObjective;

    public function __construct(Plugin $plugin)
    {
        $this->owner = $plugin;
    }

    public function onMove(PlayerMoveEvent $event): void
    {
        $this->checkScoreboardExist($event->getPlayer());
        if ($event->getFrom()->x >> 4 === $event->getTo()->x >> 4 && $event->getFrom()->z >> 4 === $event->getTo()->z >> 4) return;
        $this->scoreboardObjective->getScoreboard()->setScorePacketEntryScore(0, $event->getPlayer()->getLevel()->getChunkAtPosition($event->getTo())->getX(),[$event->getPlayer()]);
        $this->scoreboardObjective->getScoreboard()->setScorePacketEntryScore(1, $event->getPlayer()->getLevel()->getChunkAtPosition($event->getTo())->getZ(),[$event->getPlayer()]);
        $this->scoreboardObjective->setSortOrder($event->getPlayer()->getLevel()->getChunkAtPosition($event->getPlayer())->getX() <= $event->getPlayer()->getLevel()->getChunkAtPosition($event->getPlayer())->getZ() ? ScoreboardSortOrder::ASCENDING : ScoreboardSortOrder::DESCENDING, [$event->getPlayer()]);
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $this->checkScoreboardExist($event->getPlayer());
    }

    /**
     * Checks if the Scoreboard exists for the player and creates a new one if not
     * @param Player $player
     */
    private function checkScoreboardExist(Player $player): void
    {
        if ($this->scoreboardObjective instanceof ScoreboardObjective && $this->scoreboardObjective->getScoreboard() instanceof Scoreboard) {
            if ($this->scoreboardObjective->hasPlayer($player)) return;
            else $this->scoreboardObjective->registerPlayers($player);
            return;
        }
        $this->scoreboardObjective = new ScoreboardObjective("Chunk", "Chunk Position", "dummy", ScoreboardDisplaySlot::SIDEBAR, $player->getLevel()->getChunkAtPosition($player)->getX() <= $player->getLevel()->getChunkAtPosition($player)->getZ() ? ScoreboardSortOrder::ASCENDING : ScoreboardSortOrder::DESCENDING);
        $scoreboard = $this->scoreboardObjective->getScoreboard();
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $this->scoreboardObjective->getObjectiveName();
        $entry->customName = "ChunkX";
        $entry->score = $player->getLevel()->getChunkAtPosition($player)->getX();
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $scoreboard->addScorePacketEntry($entry, false);
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $this->scoreboardObjective->getObjectiveName();
        $entry->customName = "ChunkZ";
        $entry->score = $player->getLevel()->getChunkAtPosition($player)->getZ();
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $scoreboard->addScorePacketEntry($entry, false);

        $this->scoreboardObjective->registerPlayers($player);
        return;
    }
}