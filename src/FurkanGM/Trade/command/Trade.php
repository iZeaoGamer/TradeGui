<?php

namespace FurkanGM\Trade\command;

use FurkanGM\Trade\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class Trade extends Command implements PluginIdentifiableCommand
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
        parent::__construct("trade", "Trade command", "/trade <player>");
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
					if ($args[0] == "accept")
					{
						$this->getPlugin()->getManager()->acceptTrade($sender);
						return false;
					}
					elseif ($args[0] == "reject"){
						$this->getPlugin()->getManager()->rejectTrade($sender);
						return false;
					}
				}
                if (($player = $this->plugin->getServer()->getPlayer($args[0])) instanceof Player){
                    if ($player->getName() == $sender->getName()){
                    	$sender->sendMessage(TextFormat::colorize("&cYou cannot use this command on yourself."));
                    	return false;
                    }else {
                    	$this->getPlugin()->getManager()->sendTrade($sender, $player);
					}
                }else{
                    $sender->sendMessage(TextFormat::colorize("&cThe player name you entered cannot be found."));
                }
            }else{
				$sender->sendMessage(TextFormat::colorize("&aPlease use: &b/trade <player>"));
            }
        }
        return true;
    }

	/**
	 * @return \pocketmine\plugin\Plugin
	 */
	public function getPlugin(): Plugin
	{
		return $this->plugin;
	}

}
