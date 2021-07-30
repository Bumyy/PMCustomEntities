<?php

namespace Bumy;

use Bumy\Main;

use pocketmine\entity\Human;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\event\entity\EntityDamageEvent;

class Custom extends Human {

    /** @var float */
    protected $gravity = 0.00;

    public function __construct($pos, $level, $name) {
        $nbt = self::createBaseNBT($pos->add(0.5, 0, 0.5));
        Main::addSkinData($nbt, $name);
        parent::__construct($level, $nbt);
    }

    protected function initEntity(): void {
        parent::initEntity();

        $this->setScale(1);
        $this->setCanSaveWithChunk(false);
    }


    /**
     * @param EntityDamageEvent $source
     */
    public function attack(EntityDamageEvent $source): void {
        //guess called when you hit it
    }

    /**
     * @param int $tickDiff
     * @return bool
     * @throws \Exception
     */
    public function entityBaseTick(int $tickDiff = 1): bool {

        //basically makes it spin slowly, you can just delete this
        $this->yaw += 1;
        if($this->yaw > 360){
            $this->yaw = 0;
        }

        return parent::entityBaseTick($tickDiff);
    }
}