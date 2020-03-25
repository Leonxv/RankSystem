<?php

declare(strict_types=1);

namespace rank\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use rank\Main;

class QuitListener implements Listener {
	
	public function unregisterPlayer(PlayerQuitEvent $e) {
		Main::getInstance()->getGroupManager()->unregisterPlayer($e->getPlayer());
	}
}