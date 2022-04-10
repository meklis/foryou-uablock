<?php

namespace Meklis\Blocks;

use IPv4\SubnetCalculator;

class Configuration
{
    protected static $self;
    protected $configuration = [];

    protected function __construct($configurationPath = '')
    {
        if (!$configurationPath) {
            $configurationPath = __DIR__ . '/../configuration.yml';
        }
        $this->configuration = yaml_parse_file($configurationPath);
    }

    /**
     * @return Configuration
     */
    public static function get()
    {
        return self::$self;
    }

    public static function init($configurationPath = '')
    {
        self::$self = new self($configurationPath);
        return self::$self;
    }

    function isExcludedIp($ip)
    {
        foreach ($this->configuration['excluded_block_networks'] as $networkName) {
            list($net, $size) = explode("/", $networkName);
            $network = new SubnetCalculator($net, $size);
            if ($network->isIPAddressInSubnet($ip)) {
                return true;
            }
        }
        return false;
    }

    function getAddressListName()
    {
        return $this->configuration['address_list_name'];
    }

    function getMikrotikRulesPath()
    {
        return $this->configuration['path_mikrotik_rules'];
    }

    function getBindBlockedListPath()
    {
        return $this->configuration['path_bind_blocks'];
    }

    function getReplacedAddress()
    {
        return $this->configuration['replace_to_address'];
    }

    function getAdvancedDomainBlocks() {
        return $this->configuration['advanced_block_domains'];
    }
    function getAdvancedIPsBlocks() {
        return $this->configuration['advanced_block_ips'];
    }
}