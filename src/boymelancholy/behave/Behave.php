<?php

namespace boymelancholy\behave;

use boymelancholy\behave\command\BehaveCommand;
use boymelancholy\behave\command\UnbehaveCommand;
use boymelancholy\behave\helper\BehaveHelper;
use pocketmine\plugin\PluginBase;

class Behave extends PluginBase {

	public function onEnable() : void {
		$map = $this->getServer()->getCommandMap();
		$map->register("behave-command", new BehaveCommand($this));
		$map->register("unbehave-command", new UnbehaveCommand($this));

		$this->getLogger()->info("-- Let's behave sameone --");
		$this->getLogger()->info("version : ".$this->getDescription()->getVersion());
		$this->getLogger()->info("author : ".$this->getDescription()->getAuthors()[0]);
		$this->getLogger()->info("website : ".$this->getDescription()->getWebsite());

		$this->behaveHelper = new BehaveHelper;
	}

	/**
	 * Returen BehaveHelper instance
	 * @return BehaveHelper
	 */
	public function getBehaveHelper() : BehaveHelper {
		return $this->behaveHelper;
	}
}
