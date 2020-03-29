<?php

namespace boymelancholy\behave\helper;

use pocketmine\Player;

class BehaveHelper {

    /** @var array $original */
    private $original;

    /**
     * Get player cache data.
     * @param Player $player
     * @return array|null
     */
    public function getOriginal(Player $player) : ?array {
        $name = $player->getName();
        if (isset($this->original[$name])) {
            return $this->original[$name];
        } else {
            return null;
        }
    }

    /**
     * Set player cache data. (cannot overwrite)
     * @param Player $player
     * @return bool
     */
    public function setOriginal(Player $player) : bool {
        $name = $player->getName();
        if (!isset($this->original[$name])) {
            $data["NAMETAG"] = $player->getNameTag();
            $data["SKINDATA"] = $player->getSkin();
            $data["DISPLAYNAME"] = $player->getDisplayName();
            $this->original[$name] = $data;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove player cache data.
     * @param Player $player
     * @return bool
     */
    public function removeOriginal(Player $player) : bool {
        $name = $player->getName();
        if (isset($this->original[$name])) {
            unset($this->original[$name]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Search player cache data.
     * @param Player $player
     * @return bool
     */
    public function existsOriginal(Player $player) : bool {
        $name = $player->getName();
        return (isset($this->original[$name]));
    }
}