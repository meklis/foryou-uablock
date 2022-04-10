<?php
require __DIR__ . '/../init.php';

try {
    \Meklis\Blocks\LG::get()->info("Start mikrotik rules generation");

    $mikrotik = new \Meklis\Blocks\MikrotikRulesGenerator(\Meklis\Blocks\Configuration::get());

    $blackList = (new \Meklis\Blocks\UaBlackList())->load();

    \Meklis\Blocks\LG::get()->info("Adding addresses from uablacklist.com");
    $mikrotik = $mikrotik->addAddresses($blackList->getIpsAsKeys());

    \Meklis\Blocks\LG::get()->info("Adding advanced IPs for block");
    if ($ips = \Meklis\Blocks\Configuration::get()->getAdvancedIPsBlocks()) {
        $list = [];
        foreach ($ips as $ip) {
            $list[$ip] = $ip;
        }
        $mikrotik = $mikrotik->addAddresses($list);
    }


    \Meklis\Blocks\LG::get()->info("Adding advanced domains blocks with resolv");
    if ($domains = \Meklis\Blocks\Configuration::get()->getAdvancedDomainBlocks()) {
        $resolver = new \Meklis\Blocks\Resolver();
        $resolver->setList($domains);
        $mikrotik = $mikrotik->addAddresses($resolver->resolvListAsKeys());
    }


    \Meklis\Blocks\LG::get()->info("Exclude allowed networks");
    $mikrotik = $mikrotik->excludeAllowedNetworks();

    \Meklis\Blocks\LG::get()->info("Write block list");
    $status = file_put_contents(\Meklis\Blocks\Configuration::get()->getMikrotikRulesPath(), $mikrotik->getRulesAsText());

    \Meklis\Blocks\LG::get()->info("Finished");
} catch (\Throwable $e) {
    \Meklis\Blocks\LG::get()->critical($e->getMessage());
    throw $e;
}