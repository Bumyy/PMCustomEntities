<?php
namespace Bumy;

use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\ByteArrayTag;

use Bumy\CustomEntity;

class Main extends PluginBase{

    protected static $instance;

    public static function getInstance(): Main {
        return self::$instance;
    }

    //you may want to edit these later
    public $entities = [];

    public function onEnable(){
        self::$instance = $this;

        Entity::registerEntity(CustomEntity::class, true, ["CustomEntity"]);

        //the level you want this to spawn
        $level = $this->getServer()->getDefaultLevel();
        $x = 1;
        $y = 0;
        $z = 1;
        $name = "TheNameOfTheEntity";
        //PLEASE NAME EVERYTHING THE SAME WAY, cuz this might cause some issues in the code down in addSkinData
        
        //call the function we created
        $this->spawnCustomEntity($level, $x, $y, $z, $name);
        
    }

    //The function which you can create a new entity with
    public function spawnCustomEntity(Level $level, $x, $y, $z, String $name){
        $pos = new Position($x, $y, $z, $level);
        $entity = new CustomEntity($pos, $level, $name);
        $entity->spawnToAll();
        //kinda makes a unique name to save this
        $this->entities[$name . mt_rand(1000000, 9999999)] = $entity;
    }


    //basically builds up the entitys
    //Put the .png and .json in the resource folder, name them the same
    public static function addSkinData(CompoundTag $nbt, String $name): void {
        $image = imagecreatefrompng(Main::getInstance()->getDataFolder() . $name . ".png");
        $data = "";
        for($y = 0, $height = imagesy($image); $y < $height; $y++){
            for($x = 0, $width = imagesx($image); $x < $width; $x++){
                $color = imagecolorat($image, $x, $y);
                $data .= pack("c", ($color >> 16) & 0xFF) . pack("c", ($color >> 8) & 0xFF) . pack("c", $color & 0xFF) . pack("c", 255 - (($color & 0x7F000000) >> 23));
            }
        }
        $nbt->setTag(new CompoundTag("Skin", [
            new StringTag("Name", $name),
            new StringTag("Data", $data),
            new StringTag("GeometryName", "geometry." . $name),
            new ByteArrayTag("GeometryData", file_get_contents(Main::getInstance()->getDataFolder() . $name . ".json"))
        ]));
    }
}   

 
