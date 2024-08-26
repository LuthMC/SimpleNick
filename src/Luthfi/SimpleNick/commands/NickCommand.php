<?php

declare(strict_types=1);

namespace Luthfi\SimpleNick\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class NickCommand extends Command {

    private bool $defaultPermission;

    public function __construct(bool $defaultPermission) {
        parent::__construct("nick", "Change your nickname", "/nick <nickname>");
        $this->defaultPermission = $defaultPermission;

        $this->setPermission($defaultPermission ? null : "sn.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used in-game.");
            return false;
        }

        if (!$this->defaultPermission && !$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command.");
            return false;
        }

        if (strtolower($commandLabel) === "nick") {
            if (empty($args)) {
                $sender->sendMessage(TextFormat::RED . "Usage: /nick <nickname>");
                return false;
            }

            $nickname = implode(" ", $args);

            if (strlen($nickname) > 16) {
                $sender->sendMessage(TextFormat::RED . "Nickname is too long. Maximum length is 16 characters.");
                return false;
            }

            $sender->setDisplayName($nickname);
            $sender->sendMessage(TextFormat::GREEN . "Your nickname has been changed to " . TextFormat::YELLOW . $nickname);

        } elseif (strtolower($commandLabel) === "unnick") {
            $sender->setDisplayName($sender->getName());
            $sender->sendMessage(TextFormat::GREEN . "Your nickname has been reset to your default name: " . TextFormat::YELLOW . $sender->getName());
        }

        return true;
    }
}
