<?php
namespace ItsGabry\WarPlugin;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;


class Main extends PluginBase implements Listener {


    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::RED . "nano techs pronta all'utilizzo");
        $this->saveDefaultConfig();
        $levels = scandir($this->getServer()->getDataPath() . "worlds/");
        foreach ($levels as $level) {
            if ($level === "." || $level === "..") {
                continue;
            }
            $this->getServer()->loadLevel($level);
        }
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch (strtolower($command->getName())) {
            case "war":
                if ($sender instanceof Player) {
                    if ($sender->hasPermission("war.command")) {
                        $sender->teleport($this->getServer()->getLevelByName($this->getConfig()->get("War"))->getSpawnLocation());
                        $sender->addTitle(TextFormat::RED . "Benvenuto nell'arena War");
                        return true;
                        break;

                    }
                }
            case "unwar":
                if ($sender instanceof Player) {
                    $sender->teleport($this->getServer()->getLevelByName($this->getConfig()->get("Unwar"))->getSpawnLocation());
                    $sender->addTitle(TextFormat::GOLD . "Sei uscito dall'arena war");
                    return true;
                    break;
                }

        }

    }

     public function nanotechs(PlayerCommandPreprocessEvent $event) {
        $message = $event->getMessage();
        $inizio  = mb_substr($message,0,1 );
        var_dump($inizio);
        if ($inizio == "/" or ".") {
            if ($message != "/unwar") {
                if ($event->getPlayer()->getLevel()->getName() == $this->getConfig()->get("War")) {
                    if (!($event->getPlayer()->hasPermission("war.bypass"))) {
                        $event->setCancelled(true);
                    }
                }
            }
        }
    }
}
