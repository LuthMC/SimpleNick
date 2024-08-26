<?php

declare(strict_types=1);

namespace Luthfi\SimpleNick\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Luthfi\SimpleNick\Main;

class UnnickCommand extends Command {

    private array $messages;
    private Main $plugin;

    public function __construct(Main $plugin, array $messages) {
        parent::__construct("unnick", "Reset your nickname", "/unnick", []);
        $this->setPermission("sn.unnick");

        $this->messages = $messages;
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . ($this->messages['in_game_only'] ?? "This command can only be used in-game."));
            return false;
        }

        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . ($this->messages['no_permission'] ?? "You do not have permission to use this command."));
            return false;
        }

        $defaultNickname = $this->plugin->getConfig()->get("default_nickname", $sender->getName());
        $sender->setDisplayName($defaultNickname);

        $sender->sendMessage(TextFormat::GREEN . str_replace("{default_nickname}", $defaultNickname, $this->messages['nick_reset'] ?? "Your nickname has been reset to your default name: {default_nickname}."));

        return true;
    }
}
