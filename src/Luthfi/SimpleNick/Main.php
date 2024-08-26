<?php

declare(strict_types=1);

namespace Luthfi\SimpleNick;

use pocketmine\plugin\PluginBase;
use Luthfi\SimpleNick\commands\NickCommand;
use Luthfi\SimpleNick\commands\UnnickCommand;

class Main extends PluginBase {

    private bool $defaultPermission;
    private string $defaultNickname;
    private int $maxNicknameLength;
    private string $allowedCharacters;
    private array $messages;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $config = $this->getConfig();
        $this->defaultPermission = $config->get("default", false);
        $this->defaultNickname = $config->get("default_nickname", "Player");
        $this->maxNicknameLength = $config->get("max_nickname_length", 16);
        $this->allowedCharacters = $config->get("allowed_characters", "a-zA-Z0-9_");
        $this->messages = $config->get("messages", []);

        $this->getServer()->getCommandMap()->register("nick", new NickCommand($this, $this->maxNicknameLength, $this->allowedCharacters, $this->messages));
        $this->getServer()->getCommandMap()->register("unnick", new UnnickCommand($this, $this->messages));

        $this->getLogger()->info("SimpleNick Enabled");
    }

    public function onDisable(): void {
        $this->getLogger()->info("SimpleNick Disabled");
    }
}
