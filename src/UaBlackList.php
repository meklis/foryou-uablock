<?php

namespace Meklis\Blocks;

class UaBlackList
{
    protected $url = 'https://uablacklist.net/all.json';
    protected $encodeRusAddresses = true;
    protected $addresses = [];

    function __construct($encodeRusAddresses = true, $url = '')
    {
        if ($url) {
            $this->url = $url;
        }

        LG::get()->info("Init UA block list instance with url={$this->url}");
        $this->encodeRusAddresses = $encodeRusAddresses;
    }

    /**
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function load()
    {
        LG::get()->info("Try load blocked sites from url={$this->url}");
        $client = new \GuzzleHttp\Client();
        $response = $client->get($this->url);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Error code {$response->getStatusCode()}: {$response->getReasonPhrase()}");
        }
        $this->addresses = json_decode($response->getBody(), true);

        LG::get()->debug("Successes loaded ". count($this->addresses) . " domains");
        if (!$this->addresses) {
            throw new \Exception("Error parse json: " . json_last_error_msg());
        }
        return $this;
    }

    /**
     * @return string[]
     */
    public function getDomains()
    {
        return array_map(function ($domain) {
            if (preg_match('/[а-яА-Я]/', $domain)) {
                return idn_to_ascii($domain);
            }
            return $domain;
        }, array_keys($this->addresses));
    }

    /**
     * [ip] => domain.name
     *
     * @return array
     */
    public function getIpsAsKeys()
    {
        $list = [];
        foreach ($this->addresses as $domain => $data) {
            foreach ($data['ips'] as $ip) {
                $list[$ip] = $domain;
            }
        }
        return $list;
    }

    /**
     * @return string[]
     */
    public function getIpsList()
    {
        return array_keys($this->getIpsAsKeys());
    }

}