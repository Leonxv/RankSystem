<?php

declare(strict_types=1);

namespace rank\provider;

use pocketmine\IPlayer;
use rank\group\Group;

interface Provider {
	
	public function getPlayerGroups(IPlayer $player) : array;
	public function addPlayerGroup(IPlayer $player, Group $group, ?string $expiryDate = null) : void;
	public function setPlayerGroup(IPlayer $player, Group $group) : void;
	public function removePlayerGroup(IPlayer $player, Group $group) : void;
 public function removePlayerGroups(IPlayer $player) : void;
 public function hasPlayerGroup(IPlayer $player, ?Group $group = null) : bool;
 public function getPlayerGroupExpiryDate(IPlayer $player, Group $group) : ?string;
 public function addPlayerPermission(IPlayer $player, string $permission, ?string $expiryDate = null) : void;
 public function removePlayerPermission(IPlayer $player, string $permission) : void;
 public function hasPlayerPermission(IPlayer $player, string $permission) : bool;
 public function getPlayerPermissions(IPlayer $player) : array;
 public function getGroupPlayers(Group $group) : array;
 public function deleteUser(IPlayer $player) : void;
 public function userExists(string $userName) : bool;
 public function taskProccess() : void;
 public function getAllUsers() : array;
}