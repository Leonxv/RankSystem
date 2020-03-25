<?php

declare(strict_types=1);

namespace rank\commands;

use pocketmine\{
	Server, Player
};
use pocketmine\command\{
	Command, CommandSender
};
use rank\Main;
use rank\group\GroupManager;

class RankCommand extends Command {

	public function __construct() {
		parent::__construct("rank", "§brank Command");
	}

	public function execute(CommandSender $sender, string $label, array $args) : void {
		$groupManager = Main::getInstance()->getGroupManager();

		if(empty($args) || isset($args[0]) && $args[0] == "help") {
			if(!$sender->hasPermission("rang.command.general")) {
				$sender->sendMessage(Main::getPermissionMessage());
				return;
			}
			
		 $sender->sendMessage("§eRang§bSystem§a help:");
		 $sender->sendMessage(" ");
		 $sender->sendMessage("§8".str_repeat('-',38)."(§aInfo§8)".str_repeat('-',39));
		 $sender->sendMessage("§b/rank user");
		 $sender->sendMessage("§b/rank user (nick)");
		 $sender->sendMessage("§b/rank user (nick) list");
		 $sender->sendMessage("§b/rank user (nick) list");
		 $sender->sendMessage("§b/rank user (nick) add (permission)");
		 $sender->sendMessage("§b/rank user (nick) remove (permission)");
		 $sender->sendMessage("§b/rank user (nick) group set (group)");
		 $sender->sendMessage("§b/rank user (nick) group add (group) {time[s/m/h/d]}");
		 $sender->sendMessage("§b/rank user (nick) group remove (group)");
		 $sender->sendMessage("§b/rank group");
		 $sender->sendMessage("§b/rank group (group) list");
		 $sender->sendMessage("§b/rank group (group) players");
		 $sender->sendMessage("§b/rank group (group) format set formart");
		 $sender->sendMessage("§b/rank group (group) format remove");
		 $sender->sendMessage("§b/rank group (group) rank (rang)");
		 $sender->sendMessage("§b/rank group (group) create");
		 $sender->sendMessage("§b/rank group (group) delete");
		 $sender->sendMessage("§b/rank group add (permission)");
		 $sender->sendMessage("§b/rank group (group) remove (permission)");
		 $sender->sendMessage("§b/rank group (group) parents set (parent)");
		 $sender->sendMessage("§b/rank group (group) parents add (parent)");
		 $sender->sendMessage("§b/rank group (group) parents remove (parent)");
		 return;
		}

		switch($args[0]) {
			case "info":
			 if(!$sender->hasPermission("rang.command.general")) {
			  $sender->sendMessage(Main::getPermissionMessage());
			 	return;
		 	}
		 	
			 $sender->sendMessage("§7Plugin name: §eRank§bSystem");
			 $sender->sendMessage("§7Version: §2".Main::VERSION);
			 $sender->sendMessage("§7Author: §bAleondev");
			break;
			
			case "set":
		 	if(!$sender->hasPermission("rang.command.set")) {
		 		$sender->sendMessage(Main::getPermissionMessage());
	 			return;
		 	}
		 	
		 	if(!isset($args[1])) {
		 		$sender->sendMessage(Main::getErrorMessage());
		 		return;
		 	}
			 switch($args[1]) {
			 	case "default":
			 	 switch($args[2]) {
			 	 	case "group":
			 	 	 if(!isset($args[3])) {
			 	 	 	$sender->sendMessage("Usage: /rank set default group (group)");
			 	 	 	return;
			 	 	 }
			 	 	 
			 	 	 if(!$groupManager->isGroupExists($args[3])) {
			 	 	 	$sender->sendMessage(Main::format("Group mich gefunden!"));
			 	 	 	return;
			 	 	 }
			 	 	 
			 	 	 $groupManager->setDefaultGroup($groupManager->getGroup($args[3]));
			 	 	 $sender->sendMessage(Main::format("Setted default group to ”§2{$args[3]}§7”"));
			 	 	break;
			 	 	default:
			 	   $sender->sendMessage(Main::getErrorMessage());
			 	 }
			 	break;
			 	default:
			 	 $sender->sendMessage(Main::getErrorMessage());
			 }
			break;
			
			
			case "group":
	 		 if(!$sender->hasPermission("rang.command.groups")) {
		 		$sender->sendMessage(Main::getPermissionMessage());
			 	return;
		 	}
		 	
			 if(!isset($args[1])) {
			  $sender->sendMessage("§balle Ränge: ");
			  foreach($groupManager->getAllGroups() as $group) {
			 	
			 	 $parentsFormat = function($group) : string {
			 		 $format = "";
			 		
			 	 	foreach($group->getParents() as $g)
			 		  $format .= $g->getName().", ";
			 		 
			 		 if($format != "")
			 	  	$format = substr($format, 0, strlen($format) - 2);
 
			  	 return $format;
			  	};
			  	
			 	 $sender->sendMessage(" §7{$group->getName()} #{$group->getRank()} §2[{$parentsFormat($group)}]");
			 	}
			 	return;
			 }
			 
			 if(!isset($args[2])) {
			 	$sender->sendMessage(Main::getErrorMessage());
			 	return;
			 }
			 
			 switch($args[2]) {
			 	case "list":
			 	 if(!$groupManager->isGroupExists($args[1])) {
		  	 	$sender->sendMessage(Main::format("This group does not exists!"));
			   	return;
		  	 }
		  	 
		  	 $group = $groupManager->getGroup($args[1]);
		  	 
			 	 $sender->sendMessage("§7Group ”§2{$args[1]}§7” permissions:");
			 	 
    	 foreach($group->getPermissions() as $permission) {
    	 	foreach($group->getParents() as $parentGroup) {
    	 	 if($parentGroup->hasPermission($permission)) {
    	 	 	$sender->sendMessage(" §7{$permission} ({$parentGroup->getName()})");
    	 	 	return;
    	 	 }
    	 	}
    	 	$sender->sendMessage(" §7{$permission} (own)");
    	 }
			 	break;
			 	
			 	case "players":
			 	 if(!$groupManager->isGroupExists($args[1])) {
			   	$sender->sendMessage(Main::format("This group does not exists!"));
			 	  return;
			   }
			   
			 	 $sender->sendMessage("§7Group ”§2{$args[1]}§7” players:");
    	 foreach($groupManager->getGroup($args[1])->getPlayers() as $nick)
    	  $sender->sendMessage(" §7{$nick}");
			 	break;
			 	
			 	case "format":
			 	 if(!$groupManager->isGroupExists($args[1])) {
			   	$sender->sendMessage(Main::format("This group does not exists!"));
			 	  return;
			   }
			   
			   if(!isset($args[3])) {
			   	$sender->sendMessage(Main::getErrorMessage());
			   	return;
			   }
			   
			 	 switch($args[3]) {
			 	 	case "set":
			 	 	 if(!isset($args[4])) {
			 	 	 	$sender->sendMessage(Main::format("Usage: /rank group $args[1] format set (format)"));
			 	 	 	return;
			 	 	 }
			 	 	 
			 	 	 $groupManager->getGroup($args[1])->setFormat(str_replace('&', '§', $args[4]));
			 	 	 
			 	 	 $sender->sendMessage(Main::format("Group format updated!"));
			 	 	break;
			 	 	
			 	 	case "remove":
			 	 	 $groupManager->getGroup($args[1])->removeFormat();
			 	 	 
			 	 	 $sender->sendMessage(Main::format("Group format removed!"));
			 	 	break;
			 	 	
			 	 	default:
			 	 	 $sender->sendMessage(Main::getErrorMessage());
			 	 }
			 	break;
			 	
			 	case "rank":
			 	 if(!$groupManager->isGroupExists($args[1])) {
			   	$sender->sendMessage(Main::format("This group does not exists!"));
			 	  return;
			   }
			   
      if(!isset($args[3])) {
      	$sender->sendMessage(Main::format("Usage: /rank group $args[1] rank (rank)"));
      	return;
      }
      
      if(!is_numeric($args[3])) {
      	$sender->sendMessage(Main::format("Rank must be numeric!"));
      	return;
      }
      
      $groupManager->getGroup($args[1])->setRank((int) $args[3]);
      
      $sender->sendMessage(Main::format("{$args[1]}’s rank set to #{$args[3]}"));
     break;
			 	
			 	case "create":
			 	 $parents = [];
			 	 
			 	 if($groupManager->isGroupExists($args[1])) {
			 	 	$sender->sendMessage(Main::format("This group is already exists!"));
			 	 	return;
			 	 }
			 	 
			 	 if(isset($args[3])) {
			 	 	$arg = str_replace(' ', '', strtolower($args[3]));
			 	 	$parents = explode(',', $arg);
			 	 	
			 	 	foreach($parents as $parentGroup)
			 	 	 if(!$groupManager->isGroupExists($parentGroup))
			 	 	  unset($parents[array_search($parentGroup, $parents)]);
			 	 }
			 	 
			 	 $groupManager->createGroup($args[1], $parents);
			 	 $sender->sendMessage(Main::format("Group ”§2{$args[1]}§7” created!"));
			 	break;
			 	
			 	case "delete":
			 	 if(!$groupManager->isGroupExists($args[1])) {
			   	$sender->sendMessage(Main::format("This group does not exists!"));
			 	  return;
			   }
			   
			 	 $groupManager->getGroup($args[1])->delete();
			 	 $sender->sendMessage(Main::format("Group ”§2{$args[1]}§7” deleted!"));
			 	break;
			 	
			 	case "add":
			 	 if(!$groupManager->isGroupExists($args[1])) {
			   	$sender->sendMessage(Main::format("This group does not exists!"));
			 	  return;
			   }
			   
			 	 if(!isset($args[3])) {
			 	 	$sender->sendMessage(Main::format("Usage: /rank group $args[1] add (permission)"));
			 	 	return;
			 	 }
			 	 $perm = strtolower($args[3]);
			 	 
			 	 $groupManager->getGroup($args[1])->addPermission($perm);
			 	 $sender->sendMessage(Main::format("Permission ”§2{$perm}§7” added to ”§2{$args[1]}§7”!"));
			 	break;
			 	
			 	case "remove":
			 	 if(!$groupManager->isGroupExists($args[1])) {
			   	$sender->sendMessage(Main::format("This group does not exists!"));
			 	  return;
			   }
			   
			 	 if(!isset($args[3])) {
			 	 	$sender->sendMessage(Main::format("Usage: /rank group $args[1] remove (permission)"));
			 	 	return;
			 	 }
			 	 $perm = strtolower($args[3]);
			 	 
			 	 $groupManager->getGroup($args[1])->removePermission($perm);
			 	 $sender->sendMessage(Main::format("Permission ”§2{$perm}§7” removed from ”§2{$args[1]}§7”!"));
			 	break;
			 	
			 	case "parents":
			 	 if(!$groupManager->isGroupExists($args[1])) {
			   	$sender->sendMessage(Main::format("This group does not exists!"));
			 	  return;
			   }
			 	 if(!isset($args[3])) {
			 	 	$sender->sendMessage(Main::getErrorMessage());
			 	 	return;
			 	 }
			 	 
			 	 switch($args[3]) {
			 	 	case "set":
			 	 	 if(!isset($args[4])) {
			 	 	 	$sender->sendMessage(Main::format("Usage: /rank group $args[1] parents set (parents)"));
			 	 	 	return;
			 	 	 }
			 	 	 
			 	 	 if(!$groupManager->isGroupExists($args[4])) {
			     	$sender->sendMessage(Main::format("This parent group does not exists!"));
			     	return;
			     }
			     
			     if($groupManager->getGroup($args[4])->hasParent($groupManager->getGroup($args[1]))) {
			     	$sender->sendMessage(Main::format("This group can’t be {$args[1]}’s parent!"));
			     	return;
			     }
			 	 	 
			 	 	 $groupManager->getGroup($args[1])->removeParents();
			 	 	 $groupManager->getGroup($args[1])->addParent($groupManager->getGroup($args[4]));
			 	 	 
			 	 	 $sender->sendMessage(Main::format("Parent ”§2{$args[4]}§7” setted to ”§2{$args[1]}§7”!"));
			 	 	break;
			 	 	
			 	 	case "add":
			 	 	 if(!isset($args[4])) {
			 	 	 	$sender->sendMessage(Main::format("Usage: /rank group $args[1] parents add (parents)"));
			 	 	 	return;
			 	 	 }
			 	 	 
			 	 	 if(!$groupManager->isGroupExists($args[4])) {
			     	$sender->sendMessage(Main::format("This parent group does not exists!"));
			     	return;
			     }
			     
			     if($groupManager->getGroup($args[4])->hasParent($groupManager->getGroup($args[1]))) {
			     	$sender->sendMessage(Main::format("Group ”§2{$args[4]}§7” can’t be §2{$args[1]}§7’s parent"));
			     	return;
			     }

			 	 	 $groupManager->getGroup($args[1])->addParent($groupManager->getGroup($args[4]));
			 	 	 
			 	 	 $sender->sendMessage(Main::format("Parent ”§2{$args[4]}§7” added to ”§2{$args[1]}§7”!"));
			 	 	break;
			 	 	
			 	 	case "remove":
			 	 	 if(!isset($args[4])) {
			 	 	 	$sender->sendMessage(Main::format("Usage: /rank group $args[1] parents remove (parents)"));
			 	 	 	return;
			 	 	 }
			 	 	 
			 	 	 if(!$groupManager->isGroupExists($args[4])) {
			     	$sender->sendMessage(Main::format("This parent group does not exists!"));
			     	return;
			     } 
			     
			 	 	 $groupManager->getGroup($args[1])->removeParent($groupManager->getGroup($args[4]));
			 	 	 
			 	 	 $sender->sendMessage(Main::format("Parent ”§2{$args[4]}§7” removed from ”§2{$args[1]}§7”!"));
			 	 	break;
			 	 	
			 	 	default:
			 	 	 $sender->sendMessage(Main::getErrorMessage());
			 	 }
			 	break;
			 	default:
			 	 $sender->sendMessage(Main::getErrorMessage());
			 }
			break;
			
   case "user":
   if(!$sender->hasPermission("rang.command.users")) {
   	$sender->sendMessage(Main::getPermissionMessage());
		 	return;
		 }
		 
   if(!isset($args[1])) {
   	$sender->sendMessage("§7liste users:");
   	foreach(Main::getInstance()->getProvider()->getAllUsers() as $nick)
   	 $sender->sendMessage(" §7{$nick}");
   	
   	return;
   }
   
   if(!$groupManager->userExists($args[1])) {
   	$sender->sendMessage(Main::format("User not found!"));
   	return;
   }
   
    if(!isset($args[2])) {
     $sender->sendMessage("§7{$args[1]}’s groups:");
     foreach($groupManager->getAllGroups() as $group) {
     	if(!$groupManager->getPlayer($args[1])->hasGroup($group))
      $sender->sendMessage("§2Group §7{$group->getName()}§2: §7doesn’t have");
      else {
      	$expiryTime = $groupManager->getPlayer($args[1])->getGroupExpiry($group);
      	$expiryFormat = GroupManager::expiryFormat($expiryTime);
      	
       $sender->sendMessage("§2Group §7{$group->getName()}§2: §7".($expiryTime == null ? "forever" : "§2{$expiryFormat['days']}§7d §2{$expiryFormat['hours']}§7h §2{$expiryFormat['minutes']}§7m §2{$expiryFormat['seconds']}§7s"));
      }
     }
     return;
    }
    
    switch($args[2]) {
    	case "list":
    	 $sender->sendMessage("§7{$args[1]}‘s permissions:");
    	 foreach($groupManager->getPlayer($args[1])->getPermissions() as $permission)
    	  $sender->sendMessage(" §7{$permission}");
    	break;
    	
    	case "delete":
    	 $groupManager->getPlayer($args[1])->delete();
    	 $sender->sendMessage("User §2{$args[1]} §7deleted!");
    	break;
    	
    	case "add":
    	 if(!isset($args[3])) {
    	 	$sender->sendMessage(Main::format("Usage: /rank user $args[1] add (permission) {time[s/m/h/d]}"));
    	 	return;
    	 }
    	 $time = null;
        
      if(isset($args[4])) {
  	    if(strpos($args[4], "d"))
		      $time = intval(explode("d", $args[4])[0]) * 86400;
          
       if(strpos($args[4], "h"))
	 	     $time = intval(explode("h", $args[4])[0]) * 3600;

	   	  if(strpos($args[4], "m"))
		      $time = intval(explode("h", $args[4])[0]) * 60;

	 	    if(strpos($args[4], "s"))
	 	     $time = intval(explode("s", $args[4])[0]);
	 	    $playerManager = $groupManager->getPlayer($args[1])->addPermission($args[3], $time);
      } else
       $playerManager = $groupManager->getPlayer($args[1])->addPermission($args[3]);
    	 
    	 $sender->sendMessage(Main::format("Permission ”§2{$args[3]}§7” added!".($time == null ? "" : " for §2{$args[4]}")));
    	break;
    	
    	case "remove":
    	 if(!isset($args[3])) {
    	 	$sender->sendMessage(Main::format("Usage: /rank user $args[1] remove (permission)"));
    	 	return;
    	 }
    	 
    	 $playerManager = $groupManager->getPlayer($args[1])->removePermission($args[3]);
    	 $sender->sendMessage(Main::format("Permission ”§2{$args[3]}§7” removed!"));
    	break;
    	
    	case "group":
      if(!isset($args[4])) {
       $sender->sendMessage(Main::getErrorMessage());
       return;
      }
      
      if(!$groupManager->isGroupExists($args[4])) {
       $sender->sendMessage(Main::format("This group does not exists!"));
       return;
      }
      
      switch($args[3]) {
      	case "add":  
        $playerManager = $groupManager->getPlayer($args[1]);
        
        $player = $playerManager->getPlayer();
        $nick = $player instanceof Player ? $player->getName() : $args[1];
        
        $time = null;
        
        if(isset($args[5])) {
  	      if(strpos($args[5], "d"))
		        $time = intval(explode("d", $args[5])[0]) * 86400;
          
        	if(strpos($args[5], "h"))
	 	       $time = intval(explode("h", $args[5])[0]) * 3600;

	       	if(strpos($args[5], "m"))
		        $time = intval(explode("h", $args[5])[0]) * 60;

	 	      if(strpos($args[5], "s"))
	 	       $time = intval(explode("s", $args[5])[0]);
	 	      $playerManager->addGroup($groupManager->getGroup($args[4]), $time);
        } else
         $playerManager->addGroup($groupManager->getGroup($args[4]));
        
        $sender->sendMessage(Main::format("User §2{$nick} §7add group ”§2{$args[4]}§7”".($time == null ? "" : " for §2{$args[5]}")));
   break;
   
       case "remove":      
        $playerManager = $groupManager->getPlayer($args[1]);
        
        $player = $playerManager->getPlayer();
        $nick = $player instanceof Player ? $player->getName() : $args[1];
        
        $playerManager->removeGroup($groupManager->getGroup($args[4]));
        
        $sender->sendMessage(Main::format("User §2{$nick} §7dein rang wurde dir wegenommen ”§2{$args[4]}§7”"));
   break;
   
   case "set":
    $playerManager = $groupManager->getPlayer($args[1]);
    
    $player = $playerManager->getPlayer();
    $nick = $player instanceof Player ? $player->getName() : $args[1];
    
    $time = null;
        
    if(isset($args[5])) {
  	  if(strpos($args[5], "d"))
		    $time = intval(explode("d", $args[5])[0]) * 86400;
          
     if(strpos($args[5], "h"))
	 	   $time = intval(explode("h", $args[5])[0]) * 3600;

	   	if(strpos($args[5], "m"))
		    $time = intval(explode("h", $args[5])[0]) * 60;

	 	  if(strpos($args[5], "s"))
	 	   $time = intval(explode("s", $args[5])[0]);
	 	  $playerManager->setGroup($groupManager->getGroup($args[4]), $time);
    } else
     $playerManager->setGroup($groupManager->getGroup($args[4]));
        
    $sender->sendMessage(Main::format("§b{$nick} hat denn rang ”§2{$args[4]}§7” §bBekommen".($time == null ? "" : " for §2{$args[5]}")));
     
     break;
     default:
			 	 $sender->sendMessage(Main::getErrorMessage());
    }
   break;
   default:
			 $sender->sendMessage(Main::getErrorMessage());
  }
 break;
 default:
		$sender->sendMessage(Main::getErrorMessage());
		}
	}
}