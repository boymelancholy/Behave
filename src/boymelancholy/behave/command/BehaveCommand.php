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

class BehaveCommand extends Command {

    private $owner;

    public function __construct($owner) {
        parent::__construct("behave", "Disguise as another person.", "/behave");
        $this->setPermission("behave-command");
        $this->setAliases(["bh"]);
        $this->owner = $owner;
    }


    public function execute(CommandSender $sender, string $label, array $params) : bool {
        if(!$this->getOwner()->isEnabled()) return false;
        if(!$this->testPermission($sender)) return false;

        if ($sender instanceof ConsoleCommandSender) {
            $sender->sendMessage(CallTag::ERROR."You can use this command only in game");
            return false;
        }

        $name = $params[0];
        $player = Server::getInstance()->getPlayer($name);
        if ($player instanceof Player && $player->isOnline()) {

            $bool = $this->getOwner()->getBehaveHelper()->setOriginal($sender);

            if (!$bool) {
                $sender->sendMessage(CallTag::CAUTION."You could not disguise because already disguised others.");
                $sender->sendMessage(CallTag::CAUTION."Please use /unbehave.");
                return false;
            }

            if (!($sender instanceof Player)) return false;

            Server::getInstance()->updatePlayerListData(
                $sender->getUniqueId(),
                $sender->getId(),
                $player->getDisplayName(),
                $player->getSkin(),
                $sender->getXuid()
            );

            $pk = new SetActorDataPacket;
            $pk->entityRuntimeId = $sender->getId();
            $pk->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $player->getNameTag()]];
            Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $pk);

            $sender->sendMessage(CallTag::SUCCESS."You could disguise as ".$player->getName());
            return true;
        } else {
            $sender->sendMessage(CallTag::ERROR."Cannot disguise because the specified player does not exist or is not online.");
            return false;
        }
        return true;
    }

    public function getOwner() : Behave {
        return $this->owner;
    }

}