<?php

namespace boymelancholy\behave\command;

use boymelancholy\behave\Behave;
use boymelancholy\behave\utils\CallTag;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\Player;
use pocketmine\Server;

class UnbehaveCommand extends Command {

    private $owner;

    public function __construct($owner) {
        parent::__construct("unbehave", "Unleash the disguise of others.", "/unbehave");
        $this->setPermission("unbehave-command");
        $this->setAliases(["ubh"]);
        $this->owner = $owner;
    }


    public function execute(CommandSender $sender, string $label, array $params) : bool {
        if(!$this->getOwner()->isEnabled()) return false;
        if(!$this->testPermission($sender)) return false;

        if ($sender instanceof ConsoleCommandSender) {
            $sender->sendMessage(CallTag::ERROR."You can use this command only in game");
            return false;
        }

        if ($this->getOwner()->getBehaveHelper()->existsOriginal($sender)) {
            $data = $this->getOwner()->getBehaveHelper()->getOriginal($sender);

            if (!($sender instanceof Player)) return false;

            Server::getInstance()->updatePlayerListData(
                $sender->getUniqueId(),
                $sender->getId(),
                $data["DISPLAYNAME"],
                $data["SKINDATA"],
                $sender->getXuid()
            );

            $pk = new SetActorDataPacket();
            $pk->entityRuntimeId = $sender->getId();
            $pk->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $data["NAMETAG"]]];

            Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $pk);
            $this->getOwner()->getBehaveHelper()->removeOriginal($sender);
            $sender->sendMessage(CallTag::SUCCESS."Disguise undone.");
            return true;
        } else {
            $sender->sendMessage(CallTag::ERROR."Disguise couldn't be undone due to lack of initial data.");
            return false;
        }
        return true;
    }

    public function getOwner() : Behave {
        return $this->owner;
    }

}