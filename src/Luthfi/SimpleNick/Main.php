<?php

declare(strict_types=1);

namespace Luthfi\SimpleNick;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    private bool $defaultPermission;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->defaultPermission = $this->getConfig()->get("default", false);

        $this->getServer()->getCommandMap()->register("nick", new \Luthfi\SimpleNick\commands\NickCommand($this->defaultPermission));

        $this->getLogger()->info("SimpleNick Enabled");
    }

    public function onDisable(): void {
        $this->getLogger()->info("SimpleNick Disabled");
    }
}
