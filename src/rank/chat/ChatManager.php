<?php

declare(strict_types=1);

namespace rank\chat;

use pocketmine\Player;
use rank\Main;

class ChatManager {
	
	public static function getFormat(Player $player, string $message) : string {
		$group = Main::getInstance()->getGroupManager()->getPlayer($player->getName())->getGroup();
		$format = $group->getFormat();
		
		$format = str_replace("{rank}", $group->getName(), $format);
		$format = str_replace("{displayname}", $player->getDisplayName(), $format);
		$format = str_replace("{msg}", $message, $format);
		
		return $format;
	}
}