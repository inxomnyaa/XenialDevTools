<?php

namespace xenialdan\XenialDevTools\scoreboard;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\Player;
use xenialdan\XenialDevTools\Loader;

class ScoreboardObjective
{
    //TODO move into virion, add some kind of Scoreboard storage to cache these
    /** @var string */
    private $objectiveName;
    /** @var string */
    private $displayName;
    /** @var string */
    private $criteriaName = "dummy";
    /** @var string */
    private $displaySlot;
    /** @var int */
    private $sortOrder = ScoreboardSortOrder::ASCENDING;
    /** @var Scoreboard */
    private $scoreboard;
    /** @var Player[] */
    private $players = [];

    /**
     * ScoreboardObjective constructor.
     * @param string $objectiveName
     * @param string $displayName
     * @param string $criteriaName
     * @param string $displaySlot
     * @param int $sortOrder
     */
    public function __construct(string $objectiveName, string $displayName, string $criteriaName, string $displaySlot = ScoreboardDisplaySlot::SIDEBAR, int $sortOrder = ScoreboardSortOrder::ASCENDING)
    {
        $this->objectiveName = $objectiveName;
        $this->displayName = $displayName;
        $this->criteriaName = /*$criteriaName*/
            "dummy";//TODO change this when MCPE adds new criteria
        $this->displaySlot = $displaySlot;
        $this->sortOrder = $sortOrder;
        $this->scoreboard = new Scoreboard();
    }

    /**
     * Adds players to the Scoreboard player pool
     * @param Player[] $players
     */
    public function registerPlayers(Player ...$players)
    {
        foreach ($players as $player) {
            $this->players[$player->getId()] = $player;
            if ($this->displaySlot === ScoreboardDisplaySlot::BELOWNAME) {
                $player->setScoreTag($this->displayName);
            }
        }
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = $this->displaySlot;
        $pk->objectiveName = $this->objectiveName;
        $pk->displayName = $this->displayName;
        $pk->criteriaName = $this->criteriaName;
        $pk->sortOrder = $this->sortOrder;
        Loader::getInstance()->getServer()->broadcastPacket($players, $pk);
        $this->getScoreboard()->registerPlayers(...$players);
    }

    /**
     * Removes players from the Scoreboard player pool
     * @param Player[] $players
     */
    public function unregisterPlayers(Player ...$players)
    {
        $this->getScoreboard()->unregisterPlayers(...$players);
        foreach ($players as $player) {
            unset($this->players[$player->getId()]);
            if ($this->displaySlot === ScoreboardDisplaySlot::BELOWNAME) {
                $player->setScoreTag("");
            }
        }
        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = $this->objectiveName;
        Loader::getInstance()->getServer()->broadcastPacket($players, $pk);
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
     * @return string
     */
    public function getObjectiveName(): string
    {
        return $this->objectiveName;
    }

    /**
     * @param string $objectiveName
     */
    public function setObjectiveName(string $objectiveName): void
    {
        $this->objectiveName = $objectiveName;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getDisplaySlot(): string
    {
        return $this->displaySlot;
    }

    /**
     * @param string $displaySlot
     */
    public function setDisplaySlot(string $displaySlot): void
    {
        $this->displaySlot = $displaySlot;
    }

    /**
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * @param int $order
     * @param Player[] $players
     */
    public function setSortOrder(int $order = ScoreboardSortOrder::ASCENDING, array $players = [])
    {
        if(empty($players)) $players = $this->players;
        $this->sortOrder = $order;
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = $this->displaySlot;
        $pk->objectiveName = $this->objectiveName;
        $pk->displayName = $this->displayName;
        $pk->criteriaName = $this->criteriaName;
        $pk->sortOrder = $this->sortOrder;
        Loader::getInstance()->getServer()->broadcastPacket($players, $pk);
    }

    /**
     * @return Scoreboard
     */
    public function getScoreboard(): Scoreboard
    {
        return $this->scoreboard;
    }

    /**
     * @param Scoreboard $scoreboard
     */
    public function setScoreboard(Scoreboard $scoreboard): void
    {
        $this->scoreboard = $scoreboard;
    }


}