<?php

declare(strict_types=1);

namespace rank;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use rank\group\GroupManager;
use rank\provider\{
	Provider, SQLite3Provider
};
use rank\listeners\{
	JoinListener, QuitListener, ChatListener
};
use rank\commands\{
	RankCommand
};
use rank\task\GroupsTask;

class Main extends PluginBase {
	
	public const VERSION = '1.0';
	
	private static $instance;
	
	private $groupManager;
	private $settings;
	private $provider;
	
	public static function getInstance() : Main {
		return self::$instance;
	}
	
	public static function format(string $str) : string {
		return "§8(§eRank§bSystem§8) §7$str";
	}
	
	public static function getErrorMessage() : string {
		return "§cError mache /rank help";
	}
	
	public static function getPermissionMessage() : string {
		return self::format("You don’t have permission!");
	}
	
	public function onEnable() : void {
		$this->init();
		$this->registerCommands();
		$this->registerEvents();
		$this->getScheduler()->scheduleRepeatingTask(new GroupsTask(), 20);
		$this->getLogger()->info("Plugin");
	}
	
	public function getGroupManager() : GroupManager {
		return $this->groupManager;
	}
	
	public function getProvider() : Provider {
		return $this->provider;
	}
	
	private function init() : void {
		$this->saveResource("settings.yml");
		
		self::$instance = $this;
		
		$this->settings = $settings = new Config($this->getDataFolder(). 'settings.yml', Config::YAML);
		$provider = null;
		
		switch(strtolower($settings->get("provider"))) {
			case "sqlite3":
			 $provider = new SQLite3Provider();
			break;
			
			case "mysql":
			 //TODO
			break;
			
			default:
			 $provider = new SQLite3Provider();
		}
		
		$this->provider = $provider;
		
		$this->groupManager = new GroupManager($provider);
	}
	
	private function registerCommands() : void {
		$cmds = [
		 new RankCommand()
		];
		
		$this->getServer()->getCommandMap()->registerAll("core", $cmds);
	}
	
	private function registerEvents() : void {
	 $listeners = [
		 new JoinListener(),
		 new QuitListener(),
		 new ChatListener()
		];
		
		foreach($listeners as $listener)
		 $this->getServer()->getPluginManager()->registerEvents($listener, $this);
	}
}
