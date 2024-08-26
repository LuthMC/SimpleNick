<?php

declare(strict_types=1);

namespace Luthfi\SimpleNick\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Luthfi\SimpleNick\Main;

class NickCommand extends Command {

    private int $maxNicknameLength;
    private string $allowedCharacters;
    private array $messages;

    public function __construct(int $maxNicknameLength, string $allowedCharacters, array $messages) {
        parent::__construct("nick", "Change your nickname", "/nick <nickname>", []);
        $this->setPermission("sn.use");

        $this->maxNicknameLength = $maxNicknameLength;
        $this->allowedCharacters = $allowedCharacters;
        $this->messages = $messages;
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

        if (empty($args)) {
            $sender->sendMessage(TextFormat::RED . "Usage: /nick <nickname>");
            return false;
        }

        $nickname = implode(" ", $args);

        if (strlen($nickname) > $this->maxNicknameLength) {
            $sender->sendMessage(TextFormat::RED . str_replace("{max_length}", (string)$this->maxNicknameLength, $this->messages['invalid_length'] ?? "Nickname is too long. Maximum length is {max_length} characters."));
            return false;
        }

        if (preg_match('/[^' . preg_quote($this->allowedCharacters, '/') . ']/', $nickname)) {
            $sender->sendMessage(TextFormat::RED . ($this->messages['invalid_characters'] ?? "Your nickname contains invalid characters."));
            return false;
        }

        $sender->setDisplayName($nickname);
        $sender->sendMessage(TextFormat::GREEN . str_replace("{nickname}", $nickname, $this->messages['nick_change'] ?? "Your nickname has been changed to {nickname}."));

        return true;
    }
}
