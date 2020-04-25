<?php

namespace xenialdan\XenialDevTools\scoreboard;

use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;
use xenialdan\XenialDevTools\Loader;

class Scoreboard
{
    /** @var int */
    public $id;
    /** @var Player[] */
    private $players = [];
    /** @var int */
    private $scorePacketEntryId = -1;
    /** @var ScorePacketEntry[] */
    private $scorePacketEntries = [];

    /**
     * Scoreboard constructor.
     * @param int $id If not given, it will automatically generate a new id
     */
    public function __construct($id = -1)
    {
        if ($id === -1) {
            $this->id = Loader::getInstance()->scoreboardIds++;
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getScorePacketEntryId(): int
    {
        return $this->scorePacketEntryId;
    }

    /**
     * @param ScorePacketEntry $entry
     * @param bool $update
     * @return int The id for the added entry. Used to modify its data
     */
    public function addScorePacketEntry(ScorePacketEntry $entry, bool $update = true): int
    {
        $this->scorePacketEntryId++;
        $entry->scoreboardId = $this->id + $this->scorePacketEntryId;
        $this->scorePacketEntries[$entry->scoreboardId] = $entry;
        if ($update) $this->sendUpdate(SetScorePacket::TYPE_CHANGE, [$entry->scoreboardId]);

        return $this->scorePacketEntryId;
    }

    /**
     * Removes an entry from this scoreboard by its id
     * @param int $id
     * @param bool $update
     * @param Player[] $players
     * @return bool false if the entry does not exist
     */
    public function removeScorePacketEntry(int $id, bool $update = true, array $players = []): bool
    {
        if (isset($this->scorePacketEntries[$id])) {
            unset($this->scorePacketEntries[$id]);
            if ($update) $this->sendUpdate(SetScorePacket::TYPE_REMOVE, [$id], $players);
            return true;
        }
        return false;
    }

    /**
     * Gets the entry by id
     * @param int $id
     * @return null|ScorePacketEntry
     */
    public function getScorePacketEntry(int $id): ?ScorePacketEntry
    {
        return $this->scorePacketEntries[$id] ?? null;
    }

    /**
     * Updates an entry from this scoreboard by its id
     * @param int $id
     * @param int $score
     * @param Player[] $players
     * @return bool false if the entry does not exist
     */
    public function setScorePacketEntryScore(int $id, int $score, array $players = []): bool
    {
        if (isset($this->scorePacketEntries[$id])) {
            $this->scorePacketEntries[$id]->score = $score;
            $this->sendUpdate(SetScorePacket::TYPE_CHANGE, [$id], $players);
            return true;
        }
        return false;
    }

    /**
     * @return ScorePacketEntry[]
     */
    public function getScorePacketEntries(): array
    {
        return $this->scorePacketEntries;
    }

    /**
     * Adds players to the Scoreboard player pool
     * @param Player[] $players
     */
    public function registerPlayers(Player ...$players)
    {
        foreach ($players as $player) {
            $this->players[$player->getId()] = $player;
        }
        $this->sendUpdate(SetScorePacket::TYPE_CHANGE, [], $players);
        /**
         * @internal UNKNOWN WHAT THAT DOES TODO
         */
        /*$pk = new SetScoreboardIdentityPacket();
        $pk->type = SetScoreboardIdentityPacket::TYPE_REGISTER_IDENTITY;
        $entries = [];
        foreach ($this->scorePacketEntries as $scoreEntry){
            $entry = new ScoreboardIdentityPacketEntry();
            $entry->entityUniqueId = $scoreEntry->entityUniqueId??-1;
            $entry->scoreboardId = $this->scoreboardId;
            $entries[] = $entry;
        }
        $pk->entries = $entries;
        Loader::getInstance()->getServer()->broadcastPacket($players, $pk);*/
    }

    /**
     * Removes players from the Scoreboard player pool
     * @param Player[] $players
     */
    public function unregisterPlayers(Player ...$players)
    {
        foreach ($players as $player) {
            unset($this->players[$player->getId()]);
        }
        $this->sendUpdate(SetScorePacket::TYPE_REMOVE, [], $players);
        /**
         * @internal UNKNOWN WHAT THAT DOES TODO
         */
        /*$pk = new SetScoreboardIdentityPacket();
        $pk->type = SetScoreboardIdentityPacket::TYPE_CLEAR_IDENTITY;
        $entries = [];
        foreach ($this->scorePacketEntries as $scoreEntry){
            $entry = new ScoreboardIdentityPacketEntry();
            $entry->entityUniqueId = $scoreEntry->entityUniqueId??-1;
            $entry->scoreboardId = $this->scoreboardId;
            $entries[] = $entry;
        }
        $pk->entries = $entries;
        Loader::getInstance()->getServer()->broadcastPacket($players, $pk);*/
    }

    /**
     * @return Player[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function hasPlayer(Player $player): bool
    {
        return array_key_exists($player->getId(), $this->players);
    }

    /**
     * Broadcasts the changes to the Scoreboard player pool
     * @param int $type SetScorePacket TYPE_* constant
     * @param int[] $forcedIds If given any, only these entries will send
     * @param Player[] $players
     */
    public function sendUpdate(int $type = SetScorePacket::TYPE_CHANGE, array $forcedIds = [], array $players = [])
    {
        if (empty($players)) $players = $this->getPlayers();
        if (empty($players)) return;
        if (empty($forcedIds)) $scoreEntries = $this->scorePacketEntries;
        else
            $scoreEntries = array_filter($this->scorePacketEntries, function ($k) use ($forcedIds) {
                return in_array($k, $forcedIds);
            }, ARRAY_FILTER_USE_KEY);
        if (count($scoreEntries) <= 0) return;
        $pk = new SetScorePacket();
        $pk->entries = $scoreEntries;
        $pk->type = $type;
        Loader::getInstance()->getServer()->broadcastPacket($players, $pk);
    }

}