<?php
require __DIR__ . '/../init.php';

try {
    \Meklis\Blocks\LG::get()->info("Start mikrotik rules generation");

    $bind = new \Meklis\Blocks\BindBlockGenerator(\Meklis\Blocks\Configuration::get());

    $blackList = (new \Meklis\Blocks\UaBlackList())->load();

    \Meklis\Blocks\LG::get()->info("Adding addresses from uablacklist.com");
    $bind = $bind->addDomains($blackList->getDomains());

    \Meklis\Blocks\LG::get()->info("Adding advanced domains blocks");
    if ($domains = \Meklis\Blocks\Configuration::get()->getAdvancedDomainBlocks()) {
        $bind = $bind->addDomains($domains);
    }


    \Meklis\Blocks\LG::get()->info("Write block list");
    $status = file_put_contents(\Meklis\Blocks\Configuration::get()->getBindBlockedListPath(), $bind->getConfiguration());

    \Meklis\Blocks\LG::get()->info("Finished");
} catch (\Throwable $e) {
    \Meklis\Blocks\LG::get()->critical($e->getMessage());
}