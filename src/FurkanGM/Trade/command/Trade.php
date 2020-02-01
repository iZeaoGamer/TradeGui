<?php

namespace FurkanGM\Trade\command;

use FurkanGM\Trade\form\TradeRequestForm;
use FurkanGM\Trade\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandParameter;
use pocketmine\Player;

class Trade extends Command
{

    /**
     * @var Main
     */
    private $plugin;

    /**
     * Trade constructor.
     */
    public function __construct()
    {
        parent::__construct("trade", "Takas komutu", "/trade <player>", ["takas"]);
        $this->plugin = Main::getInstance();
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool|mixed
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
			$cfg = Main::getInstance()->getConfig()->get("type");
			if (isset($args[0])){
				if ($cfg == "command")
				{
					if ($args[0] == "accept" || $args[0] == "kabul")
					{
						Main::getInstance()->getManager()->acceptTrade($sender);
						return false;
					}
					elseif ($args[0] == "reject" || $args[0] == "reddet"){
						Main::getInstance()->getManager()->rejectTrade($sender);
						return false;
					}
				}
                if (($player = $this->plugin->getServer()->getPlayer($args[0])) instanceof Player){
                    if ($player->getName() == $sender->getName()){
                    	$sender->sendMessage($this->plugin->translateText("command.yourself"));
                    	return false;
                    }else {
                    	Main::getInstance()->getManager()->sendTrade($sender, $player);
					}
                }else{
                    $sender->sendMessage($this->plugin->translateText("command.playernotfound"));
                }
            }else{
				$sender->sendMessage($this->plugin->translateText("command.usage"));
            }
        }
        return true;
    }

}