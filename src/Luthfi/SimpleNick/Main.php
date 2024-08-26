<?php

declare(strict_types=1);

namespace Luthfi\SimpleNick;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    private int $maxNicknameLength;
    private string $allowedCharacters;
    private array $messages;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $config = $this->getConfig();

        $this->maxNicknameLength = $config->get("max_nickname_length", 16);
        $this->allowedCharacters = $config->get("allowed_characters", "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");
        $this->messages = $config->get("messages", []);

        $this->getServer()->getCommandMap()->register("nick", new \Luthfi\SimpleNick\commands\NickCommand(
            $this->maxNicknameLength,
            $this->allowedCharacters,
            $this->messages
        ));

        $this->getServer()->getCommandMap()->register("unnick", new \Luthfi\SimpleNick\commands\UnnickCommand(
            $this->messages
        ));

        $this->getLogger()->info("SimpleNick Enabled");
    }

    public function onDisable(): void {
        $this->getLogger()->info("SimpleNick Disabled");
    }

    public function getMaxNicknameLength(): int {
        return $this->maxNicknameLength;
    }

    public function getAllowedCharacters(): string {
        return $this->allowedCharacters;
    }

    public function getMessages(): array {
        return $this->messages;
    }
}
