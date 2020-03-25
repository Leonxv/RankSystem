<?php

declare(strict_types=1);

namespace rank\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use rank\Main;
use rank\chat\ChatManager;

class ChatListener implements Listener {
	
	public function chatFormat(PlayerChatEvent $e) {
		$player = $e->getPlayer();
	 $groupManager = Main::getInstance()->getGroupManager();
	 
	 if($groupManager->getPlayer($player->getName())->getGroup()->getFormat() != null)
	  $e->setFormat(ChatManager::getFormat($player, $e->getMessage()));
	}
}