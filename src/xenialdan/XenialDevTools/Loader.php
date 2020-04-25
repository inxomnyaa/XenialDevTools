<?php

namespace xenialdan\XenialDevTools;

use pocketmine\entity\Entity;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\network\mcpe\protocol\types\RuntimeBlockMapping;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use ReflectionClass;
use ReflectionMethod;
use xenialdan\apibossbar\API;
use xenialdan\XenialDevTools\command\ClearEntityCommand;
use xenialdan\XenialDevTools\command\DelEntityCommand;
use xenialdan\XenialDevTools\command\ShowMWEPasteblocksCommand;
use xenialdan\XenialDevTools\command\ShowMWEPasteChunksCommand;
use xenialdan\XenialDevTools\command\ShowMWESelectionCommand;
use xenialdan\XenialDevTools\command\ShowProfileCommand;
use xenialdan\XenialDevTools\command\VanishCommand;
use xenialdan\XenialDevTools\listener\BossBarWYLAEventListener;
use xenialdan\XenialDevTools\listener\DelEntityEventListener;
use xenialdan\XenialDevTools\listener\FakeplayerWithSkinEventListener;
use xenialdan\XenialDevTools\listener\StopAllBlockUpdatesEventListener;
use xenialdan\XenialDevTools\listener\TestCameraEventListener;
use xenialdan\XenialDevTools\listener\TestCustomEmojiManaListener;
use xenialdan\XenialDevTools\listener\TestLeftEventListener;

#use xenialdan\camerapath\CameraPath;
#use xenialdan\camerapath\CameraPoint;

#use xenialdan\libstructure\PacketListener;

class Loader extends PluginBase
{
    private static $instance;

    public static $delEntities = [];
    public $scoreboardIds = 0;

    /**
     * @return $this|null
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {
        $this->saveResource("wwwroot" . DIRECTORY_SEPARATOR . "index.html");
        #PacketListener::register($this);
        $this->getServer()->getCommandMap()->registerAll("xenialdevtools", [
            new DelEntityCommand("delentity", $this),
            new ClearEntityCommand("clearentity", $this),
            new ShowProfileCommand("showprofile", $this),
            new ShowMWESelectionCommand("/selection", $this),
            new ShowMWEPasteChunksCommand("/pastechunks", $this),
            new ShowMWEPasteblocksCommand("/pasteblocks", $this),
            new VanishCommand("vanish", $this),
        ]);
        $this->getServer()->getPluginManager()->registerEvents(new DelEntityEventListener($this), $this);
        #$this->getServer()->getPluginManager()->registerEvents(new ScoreboardDebugEventListener($this), $this);
        #$this->getServer()->getPluginManager()->registerEvents(new ScoreboardEmojiEventListener($this), $this);
        #$this->getServer()->getPluginManager()->registerEvents(new TestBossBarEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new TestCameraEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new TestLeftEventListener(), $this);
        #$this->getServer()->getPluginManager()->registerEvents(new TestFireworkListener(), $this);
        #$this->getServer()->getPluginManager()->registerEvents(new TestEmojiListener(), $this);
        #$this->getServer()->getPluginManager()->registerEvents(new TestCustomEmojiListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new StopAllBlockUpdatesEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BossBarWYLAEventListener(), $this);
        #$this->getServer()->getPluginManager()->registerEvents(new TestCustomEmojiClockListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new TestCustomEmojiManaListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new FakeplayerWithSkinEventListener(), $this);
        API::load($this);
        #$this->vectorTests();
        #$this->reflectionTest();
        $this->webserverTest();
        #$class = Villager::class;
        #var_dump(is_a($class, Entity::class) ? "true" : "false");
        #/** @var Entity $class */
        #var_dump($class::NETWORK_ID);
    }

    private function vectorTests()
    {
        $v1 = new Vector3(1, 2, 3);
        $v2 = new Vector3(4, 5, 6);
        $dot = $v1->dot($v2);
        var_dump(
            (new Vector3())->dot(new Vector3(1, 1, 1)),
            (new Vector3(1, 1, 1))->dot(new Vector3()),
            (new Vector3(1, 1, 1))->dot(new Vector3(1, 1, 1)),
            (new Vector3(1, 1, 1))->dot(new Vector3(2, 2, 2)),
            (new Vector3())->dot(new Vector3(1, 1, 1)) - (new Vector3(1, 1, 1))->dot(new Vector3(2, 2, 2))
        );
        $normalize = (clone $v1)->normalize();
        $normalizeAdd = $normalize->x + $normalize->y + $normalize->z;
        $normalizeMultiply = $normalize->x * $normalize->y * $normalize->z;
        $normalizeLength = $normalize->length();
        $cross = (clone $v1)->cross($v2);
        $lengthSquared = (clone $v1)->lengthSquared();
        $length = (clone $v1)->length();
        var_dump("dot", $dot, "normalize", $normalize, "normalizeAdd", $normalizeAdd, "normalizeMultiply", $normalizeMultiply, "normalizeLength", $normalizeLength, "cross", $cross, "lengthSquared", $lengthSquared, "length", $length);
    }

    /*    public function cameraPathTest(Player $player)
        {
            $defaultLevel = Server::getInstance()->getDefaultLevel();
            /*$path = new CameraPath($this);
            $path->push((new CameraPoint(0,65,0,0,0,$defaultLevel))->setPathSpeed(1));
            $path->push((new CameraPoint(5,65,0,0,0,$defaultLevel))->setPathSpeed(1));
            $path->push((new CameraPoint(10,65,10,-90,-25,$defaultLevel))->setPathSpeed(1));
            $path->push((new CameraPoint(10,65,10,0,25,$defaultLevel))->setPathSpeed(3));
            $path->push((new CameraPoint(0,65,0,0,0,$defaultLevel))->setPathSpeed(1));* /
            $path = new CameraPath($this);
            $path->push(new CameraPoint(0, 65, 0, 0, 0, $defaultLevel));
            $path->push(new CameraPoint(1, 65, 1, 90, -25, $defaultLevel));
            $path->push(new CameraPoint(0, 65, -1, 180, 0, $defaultLevel));
            $path->push(new CameraPoint(-1, 65, 0, 270, 25, $defaultLevel));
            $path->push(new CameraPoint(0, 65, 1, 0, 0, $defaultLevel));
            $path->setTravelingSpeed(10);
            var_dump($path);
            $path->runPath($player);
            $this->getScheduler()->scheduleRepeatingTask(new class($path, $player) extends Task {
                private $path;
                private $bar;

                public function __construct(CameraPath &$path, Player $player)
                {
                    $this->path = $path;
                    $this->bar = (new BossBar())->setTitle("Camera path " . $path)->setPercentage(0)->addPlayer($player);
                }

                public function onRun(int $currentTick)
                {
                    /** @var CameraPoint $currentPoint , float $state * /
                    [$currentPoint, $state] = $this->path->getCurrentState();
                    if ($state < 0) {
                        foreach ($this->bar->getPlayers() as $player) {
                            $player->sendMessage("Camera path done");
                        }
                        $this->bar->removeAllPlayers();
                        $this->getHandler()->cancel();
                        return;
                    }
                    $this->bar->setPercentage($this->path->getFullPathTime() / 100 * $state)->setSubTitle("S: $state P: $currentPoint");//Todo current speed, current calculated location
                }

            }, $path::$ACCURACY);
        }*/

    /**
     * @param string $nametag The name
     * @param Entity $entity The entity you want to set the name of
     * @param Player $player The player that will see the nametag like that
     */
    public function setNameForPlayer(string $nametag, Entity $entity, Player $player): void
    {
        //construct the packet
        $pk = new SetActorDataPacket();
        //set the properties of the packet
        ///runtime id is the id of the entity you will modify
        $pk->entityRuntimeId = $entity->getId();
        ///metadata is a little more difficult: its a "weird" array. Believe me, this is pain in the eye as array, thats why we fake a datapropertymanager to create it for us
        $fakePropertyManager = clone $entity->getDataPropertyManager();
        $fakePropertyManager->setString(Entity::DATA_NAMETAG, $nametag, false);
        ///get the dirty flags and set the packet metadata
        $pk->metadata = $fakePropertyManager->getDirty();
        //Send the packet to the player
        $player->dataPacket($pk);
    }

    private function reflectionTest()
    {
        $runtimeBlockMapping = new ReflectionClass(RuntimeBlockMapping::class);
        /** @var ReflectionMethod $registerMappingMethod */
        $registerMappingMethod = $runtimeBlockMapping->getMethod("registerMapping");
        $registerMappingMethod->setAccessible(true);
        $registerMapping = $registerMappingMethod->getClosure();
        var_dump($registerMappingMethod, get_class($registerMappingMethod));
        var_dump($registerMapping, get_class($registerMapping));
    }

    private function webserverTest()
    {
        $serverRoot = $this->getDataFolder() . "wwwroot";
        $ws = \Frago9876543210\WebServer\API::startWebServer($this, \Frago9876543210\WebServer\API::getPathHandler($serverRoot));
        $ws->getClassLoader()->getParent()->addPath(realpath($serverRoot), true);
        var_dump($ws->getClassLoader()->getParent()->loadClass("framework\Header"));
        var_dump($ws->getClassLoader()->getParent()->loadClass("website\account\API"));

        var_dump($ws->getClassLoader()->getParent()->findClass("framework\Header"));
        var_dump($ws->getClassLoader()->getParent()->findClass("website"));
        var_dump($ws->getClassLoader()->getParent()->findClass("website\account"));
        var_dump($ws->getClassLoader()->getParent()->findClass("website\account\API"));
        var_dump($ws->getClassLoader()->getParent()->findClass("framework"));
        var_dump($ws->getClassLoader()->getParent()->findClass("framework\Header"));
        var_dump($ws->getClassLoader()->findClass("Frago9876543210\WebServer\WSConnection"));
    }

    private function bigOakTreeTest(Position $position, bool $sapling = false)
    {
        $level = $position->getLevel();
        $baseVector = $position->asVector3()->floor();
        #$heightLimit;
        #$trunkHeight;
        $leafDistanceLimit = $sapling ? 4 : 5;
        $heightAttenuation = 1 / 0.6180339887;
        #$branchCount;
        #$branchSlope;
        //Check if the tree is in a valid spawn location
        $heightLimit = random_int(5, 16);
        for ($y = $position->y + $heightLimit; $y >= $position->y; $y--) {
            if ($level->getBlock($position->add(0, $y))->isSolid()) {
                $heightLimit = $position->y - $y;
                var_dump("Obstructed at {$position->asVector3()}, heightLimit = $heightLimit");
            }
        }
        if ($heightLimit < 6) {
            var_dump("$heightLimit is too smol");
            return;
        }
        //Prepare for growth: choose branch positions
        $trunkHeight = (int)($heightLimit * $heightAttenuation);
        $branchCount = $heightLimit >= 11 ? 2 : 1;
        $branches = [];
        $branches[] = new BOTBranch($baseVector, $position->add(0, $trunkHeight), $position->add(0, $trunkHeight));
        for ($yOffset = ceil($heightLimit - $leafDistanceLimit), $yOffsetMax = ceil($heightLimit * 0.3); $yOffset <= $yOffsetMax; $yOffset++) {
            for ($bc = 0; $bc < $branchCount; $bc++) {

            }
        }
    }
}

class BOTBranch
{
    /**
     * @var Vector3
     */
    private $end;
    /**
     * @var Vector3
     */
    private $atTrunk;
    /**
     * Where the tree starts
     * @var Vector3
     */
    private $sapling;

    public function __construct(Vector3 $sapling, Vector3 $end, Vector3 $atTrunk)
    {
        $this->sapling = $sapling;
        $this->end = $end;
        $this->atTrunk = $atTrunk;
    }

    public function canGenerate(int $heightLimit): bool
    {
        $maxBranchLength = $this->sphereCheck($heightLimit);
        //$branchLength = random_int($);
        return false;
    }

    private function sphereCheck(int $heightLimit, Vector3 $point): float
    {
        $center = $this->sapling->add(0, $heightLimit / 2);
        $radius = $heightLimit * 0.664;
        $distance = $center->distance($point);
        $v = $distance < $radius;//todo check if <=
        var_dump("Sphere check", "Radius $radius, distance $distance, sphereCheck " . $v ? "true" : "false");
        return $distance;
    }
}