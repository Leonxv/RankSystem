<?php

declare(strict_types=1);

namespace rank\provider;

use pocketmine\IPlayer;
use rank\Main;
use rank\group\Group;

class SQLite3Provider implements Provider {
	
	private $db;
	
	public function __construct() {
		$this->init();
	}
	private function init() : void {
		$this->db = $db = new \SQLite3(Main::getInstance()->getDataFolder(). 'DataBase.db');
		$db->exec("CREATE TABLE IF NOT EXISTS groups(nick TEXT, groupName TEXT, expiryDate TEXT)");
		$db->exec("CREATE TABLE IF NOT EXISTS permissions(nick TEXT, permission TEXT, expiryDate TEXT)");
	}
	
	public function getPlayerGroups(IPlayer $player) : array {
		$groups = [];
		$nick = strtolower($player->getName());
  $result = $this->db->query("SELECT * FROM groups WHERE nick = '$nick'");
  
  while($row = $result->fetchArray(SQLITE3_ASSOC))
   $groups[] = Main::getInstance()->getGroupManager()->getGroup($row['groupName']);
  
  return $groups;
	}
	
	public function addPlayerGroup(IPlayer $player, Group $group, ?string $expiryDate = null) : void {
		$nick = strtolower($player->getName());
		$this->db->query("INSERT INTO groups (nick, groupName, expiryDate) VALUES ('$nick', '{$group->getName()}', '$expiryDate')");
	}
	
	public function setPlayerGroup(IPlayer $player, Group $group) : void {
		$this->removePlayerGroups($player);
  $this->addPlayerGroup($player, $group);
 }
 
 public function removePlayerGroup(IPlayer $player, Group $group) : void {
 	$nick = strtolower($player->getName());
  $this->db->query("DELETE FROM groups WHERE nick = '$nick' AND groupName = '{$group->getName()}'");
 }
 
 public function removePlayerGroups(IPlayer $player) : void {
 	$nick = strtolower($player->getName());
  $this->db->query("DELETE FROM groups WHERE nick = '$nick'");
 }
 
 public function hasPlayerGroup(IPlayer $player, ?Group $group = null) : bool {
 	$nick = strtolower($player->getName());
 	
  if($group == null)
    return !empty($this->db->query("SELECT * FROM groups WHERE nick = '$nick'")->fetchArray());
   
   return !empty($this->db->query("SELECT * FROM groups WHERE nick = '$nick' AND groupName = '{$group->getName()}'")->fetchArray());
 }
 
 public function getPlayerGroupExpiryDate(IPlayer $player, Group $group) : ?string {
 	$nick = strtolower($player->getName());
 	$array = $this->db->query("SELECT * FROM groups WHERE nick = '{$nick}' AND groupName = '{$group->getName()}'")->fetchArray(SQLITE3_ASSOC);
 	
 	return $array['expiryDate'];
 }
 
 public function addPlayerPermission(IPlayer $player, string $permission, ?string $expiryDate = null) : void {
		$nick = strtolower($player->getName());
		$this->db->query("INSERT INTO permissions (nick, permission, expiryDate) VALUES ('$nick', '$permission', '$expiryDate')");
	}
 
 public function removePlayerPermission(IPlayer $player, string $permission) : void {
 	$nick = strtolower($player->getName());
  $this->db->query("DELETE FROM permissions WHERE nick = '$nick' AND permission = '$permission'");
 }
 
 public function hasPlayerPermission(IPlayer $player, string $permission) : bool {
 	$nick = strtolower($player->getName());
 	return !empty($this->db->query("SELECT * FROM permissions WHERE nick = '$nick' AND permission = '$permission'")->fetchArray());
 }
 
 public function getPlayerPermissions(IPlayer $player) : array {
		$permissions = [];
		$nick = strtolower($player->getName());
  $result = $this->db->query("SELECT * FROM permissions WHERE nick = '$nick'");
  
  while($row = $result->fetchArray(SQLITE3_ASSOC))
   $permissions[] = $row['permission'];
  
  return $permissions;
	}
	
	public function getGroupPlayers(Group $group) : array {
		$players = [];
		
		$result = $this->db->query("SELECT * FROM groups WHERE groupName = '{$group->getName()}'");
		
		while($row = $result->fetchArray(SQLITE3_ASSOC))
		 $players[] = $row['nick'];
		
		return $players;
	}
	
	public function deleteUser(IPlayer $player) : void {
		$nick = strtolower($player->getName());
		
		$this->db->query("DELETE FROM groups WHERE nick = '$nick'");
		$this->db->query("DELETE FROM permissions WHERE nick = '$nick'");
	}
	
	public function userExists(string $userName) : bool {
		$userName = strtolower($userName);
		
		$array1 = $this->db->query("SELECT * FROM groups WHERE nick = '$userName'")->fetchArray(SQLITE3_ASSOC);
		$array2 = $this->db->query("SELECT * FROM permissions WHERE nick = '$userName'")->fetchArray(SQLITE3_ASSOC);
		
		return !(empty($array1) && empty($array2));
	}
	
	public function getAllUsers() : array {
		$users = [];
		
		$result1 = $this->db->query("SELECT * FROM groups");
		
		while($row = $result1->fetchArray(SQLITE3_ASSOC))
		 $users[] = $row['nick'];
		 
		$result2 = $this->db->query("SELECT * FROM permissions");
		
		while($row = $result2->fetchArray(SQLITE3_ASSOC))
		 if(!in_array($row['nick'], $users))
		  $users[] = $row['nick'];
		
		return $users;
	}
	
	public function taskProccess() : void {
		$result = $this->db->query("SELECT * FROM groups");
		
		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			if($row['expiryDate'] != null) {
				if(time() > strtotime($row['expiryDate'])) {
					$groupManager = Main::getInstance()->getGroupManager();
					$groupManager->getPlayer($row['nick'])->removeGroup($groupManager->getGroup($row['groupName']));
				}
			}
		}
		
		$result = $this->db->query("SELECT * FROM permissions");
		
		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			if($row['expiryDate'] != null) {
				if(time() > strtotime($row['expiryDate'])) {
					$groupManager = Main::getInstance()->getGroupManager();
					$groupManager->getPlayer($row['nick'])->removePermission($row['permission']);
				}
			}
		}
	}
}
