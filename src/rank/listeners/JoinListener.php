<?php

declare(strict_types=1);

namespace rank\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use rank\Main;

class JoinListener implements Listener {
	
	public function permissionsOnJoin(PlayerJoinEvent $e) {
		$player = $e->getPlayer();
	 $groupManager = Main::getInstance()->getGroupManager();
	 $groupManager->registerPlayer($player);
	 
	 if(!$groupManager->getPlayer($player->getName())->hasGroup()) {
	 	 if($groupManager->getDefaultGroup() == null) {
	 	 	$player->sendMessage(Main::format("Rang würde nicht gefunden!"));
	 	 	return;
	 	 }
	  $groupManager->getPlayer($player->getName())->addDefaultGroup();
	 }
	}
}