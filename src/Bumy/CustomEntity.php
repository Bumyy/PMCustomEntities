<?php

namespace Bumy\StaffCore\entity;

use Bumy\StaffCore\Main;

use pocketmine\entity\Human;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\event\entity\EntityDamageEvent;

class LegionEntity extends Human {

    /** @var float */
    protected $gravity = 0.00;


    public $staff;

    /** @var int */
    protected $timeCounter = 0;



    public function __construct($pos, $level, $staff) {
        $nbt = self::createBaseNBT($pos->add(0.5, 0, 0.5));
        Main::addSkinData($nbt);
        parent::__construct($level, $nbt);

        $this->staff = $staff;
    }

    protected function initEntity(): void {
        parent::initEntity();

        $this->setScale(0.7);
        $this->setCanSaveWithChunk(false);
        if($this->getPlayer() == ""){
            $this->setScale(5);
        }
    }

    /**
     * @return Player
     */
    public function getPlayer() {
        return $this->staff;
    }


    /**
     * @param EntityDamageEvent $source
     */
    public function attack(EntityDamageEvent $source): void {

    }

    /**
     * @param int $tickDiff
     * @return bool
     * @throws \Exception
     */
    public function entityBaseTick(int $tickDiff = 1): bool {
        if($this->getPlayer() == ""){
            return true;
        }
        $this->timeCounter += $tickDiff;

        $this->yaw += 1;
        if($this->yaw > 360){
            $this->yaw = 0;
        }

        if($this->getPlayer()->isOnline() == false){
            $this->flagForDespawn();

            return true;
        }

        return parent::entityBaseTick($tickDiff);
    }

    public function moveWithPlayer(){
        if($this->getPlayer()->isOnline() == false){
            $this->flagForDespawn();

            return true;
        }

        $position = $this->staff->asPosition();
        $position->y += 2.5;

        $this->setPosition($position);
    }
}