<?php

declare(strict_types=1);

namespace rank\task;

use pocketmine\scheduler\Task;
use rank\Main;

class GroupsTask extends Task {
	
	public function onRun(int $currentTick) {
		Main::getInstance()->getProvider()->taskProccess();
	}
}