<?php
namespace Evansims;

class Socialworth
{
    protected $curl = null;
    protected $url = null;

    public $services = array(
        'twitter'     => true,
        'facebook'    => true,
        'pinterest'   => true,
        'reddit'      => true,
        //'hackernews'  => true,
        'googleplus'  => true,
        'stumbleupon' => true,
        'linkedin'    => true,
        'testcase'    => false
    );

    public function __construct($url = null, $services = array())
    {
        $this->url($url);
        $this->services = array_merge($this->services, $services);

        return $this;
    }

    public function url($url = null)
    {
        if ($url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $this->url = $url;
                return true;
            } else {
                throw new \InvalidArgumentException(_('The address provided is not a valid URL.'));
            }
        } else {
            return $this->url;
        }
    }

    private function apiEndpoints()
    {
        return array(
            'facebook' => array(
                'method'   => 'GET',
                'url'      => 'https://graph.facebook.com/fql?q=' . urlencode("SELECT like_count, total_count, share_count, click_count, comment_count FROM link_stat WHERE url = \"{$this->url}\""),
                "callback" => function ($resp) {
                    if ($resp && isset($resp->data[0]->total_count) && $resp->data[0]->total_count) {
                        return (int)$resp->data[0]->total_count;
                    }

                    return 0;
                }),
            'pinterest' => array(
                'method'   => 'GET',
                'url'      => "http://api.pinterest.com/v1/urls/count.json?url={$this->url}",
                "callback" => function ($resp) {
                    if ($resp) {
                        $resp = json_decode(substr($resp, strpos($resp, '{'), -1));
                        if ($resp && isset($resp->count) && $resp->count) {
                            return (int)$resp->count;
                        }
                    }

                    return 0;
                }),
            'twitter' => array(
                'method'   => 'GET',
                'url'      => "http://cdn.api.twitter.com/1/urls/count.json?url={$this->url}",
                "callback" => function ($resp) {
                    if ($resp && isset($resp->count) && $resp->count) {
                        return (int)$resp->count;
                    }

                    return 0;
                }),
            'linkedin' => array(
                'method'   => 'GET',
                'url'      => "http://www.linkedin.com/countserv/count/share?url={$this->url}&format=json",
                "callback" => function ($resp) {
                    if ($resp && isset($resp->count) && $resp->count) {
                        return (int)$resp->count;
                    }

                    return 0;
                }),
            'stumbleupon' => array(
                'method'   => 'GET',
                'url'      => "http://www.stumbleupon.com/services/1.01/badge.getinfo?url={$this->url}",
                "callback" => function ($resp) {
                    if ($resp && isset($resp->result) && isset($resp->result->views) && $resp->result->views) {
                        return (int)$resp->result->views;
                    }

                    return 0;
                }),
            'reddit' => array(
                'method'   => 'GET',
                'url'      => "http://www.reddit.com/api/info.json?url={$this->url}",
                "callback" => function ($resp) {
                    if ($resp && isset($resp->data->children) && $resp->data->children) {
                        $c = 0;
                        foreach ($resp->data->children as $story) {
                            if (isset($story->data) && isset($story->data->ups)) {
                                $c = $c + (int)$story->data->ups;
                            }
                        }
                        return (int)$c;
                    }

                    return 0;
                }),
/*            'hackernews' => array(
                'method'   => 'GET',
                'url'      => "http://api.thriftdb.com/api.hnsearch.com/items/_search?q=&filter[fields][url]={$this->url}",
                "callback" => function ($resp) {
                    if ($resp && isset($resp->results) && $resp->results) {
                        $c = 0;
                        foreach ($resp->results as $story) {
                            $c++;
                            if (isset($story->item) && isset($story->item->points)) {
                                $c = $c + (int)$story->item->points;
                            }
                            if (isset($story->item) && isset($story->item->num_comments)) {
                                $c = $c + (int)$story->item->num_comments;
                            }
                        }
                        return (int)$c;
                    }

                    return 0;
                }),*/
            'googleplus' => array(
                'method'   => 'POST',
                'headers'  => array('Content-type: application/json'),
                'url'      => 'https://clients6.google.com/rpc',
                'payload'  => json_encode(array(
                    'method' => 'pos.plusones.get',
                    'id'     => 'p',
                    'params' => array(
                        "nolog"   => true,
                        "id"      => $this->url,
                        "source"  => "widget",
                        "userId"  => "@viewer",
                        "groupId" => "@self"
                    ),
                    "jsonrpc"    => "2.0",
                    "key"        => "p",
                    "apiVersion" => "v1"
                    )),
                "callback" => function ($resp) {
                    if ($resp && isset($resp->result->metadata->globalCounts->count) && $resp->result->metadata->globalCounts->count) {
                        return (int)$resp->result->metadata->globalCounts->count;
                    }

                    return 0;
                }),
            'testcase' => array(
                'method'   => 'GET',
                'url'      => "http://thisisbogus.supercalifragilisticexpialidocious.io",
                "callback" => function ($resp) {
                    return $resp;
                })
        );
    }

    private function apiRequest($method, $endpoint, $params = array())
    {
        if (!isset($this->url)) {
            throw new \InvalidArgumentException(_('You must specify an address to query.'));
        }

        $method = strtoupper($method);
        $options = array();

        if (! $this->curl) {
            $this->curl = curl_init();
        }

        if ($method == 'GET') {
            $options[CURLOPT_HTTPGET] = true;
        } elseif ($method == 'POST') {
            $options[CURLOPT_POST] = true;
        //} else {
        //    $options[CURLOPT_CUSTOMREQUEST] = $method;
        }

        if (isset($params['headers']) && $params['headers']) {
            $options[CURLOPT_HTTPHEADER] = $params['headers'];
        }

        if ($method !== 'GET' && isset($params['payload']) && $params['payload']) {
            $options[CURLOPT_POSTFIELDS] = $params['payload'];
        }

        /*
        if ($method === 'GET' && count($params)) {
            foreach ($params as $p => $v) {
                $endpoint .= $p . '=' . urlencode($v) . '&';
            }
        }
        */

        $options[CURLOPT_URL] = rtrim($endpoint, '?&');
        $options[CURLOPT_TIMEOUT] = 5;
        $options[CURLOPT_HEADER] = false;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_SSL_VERIFYPEER] = false;
        $options[CURLOPT_FOLLOWLOCATION] = true;

        curl_setopt_array($this->curl, $options);

        if ($raw = curl_exec($this->curl)) {
            if ($resp = json_decode($raw)) {
                return $resp;
            } else {
                return $raw;
            }
        }

        return false;
    }

    public function all()
    {
        $endpoints = $this->apiEndpoints();
        $response = array(
            'total' => 0
        );

        foreach ($this->services as $service => $enabled) {
            if ($enabled && isset($endpoints[$service])) {
                $actions = $this->__get($endpoints[$service]);
                $response[$service] = $actions;
                $response['total'] += $actions;
            }
        }

        return (object)$response;
    }

    public function __set($service, $enabled)
    {
        if (isset($this->services[$service])) {
            $this->services[$service] = (boolean)$enabled;
            return true;
        }

        return false;
    }

    public function __get($service)
    {
        if (is_string($service)) {
            $service = strtolower($service);
            $endpoints = $this->apiEndpoints();

            if (isset($endpoints[$service])) {
                $service = $endpoints[$service];
            } else {
                throw new \Exception(sprintf(_('Unknown service %s'), $service));
            }
        }

        if (!is_array($service) || !isset($service['url'])) {
            throw new \InvalidArgumentException(_('Argument expected to be a service name as a string.'));
        }

        $params = array();

        if (isset($service['headers'])) {
            $params['headers'] = $service['headers'];
        }

        if (isset($service['payload'])) {
            $params['payload'] = $service['payload'];
        }

        $actions = $service['callback']($this->apiRequest($service['method'], $service['url'], $params));
        return $actions;
    }

    public function __isset($service)
    {
        if (isset($this->services[$service])) {
            if ($this->services[$service] === true) {
                return true;
            }
        }

        return false;
    }

    public function __unset($service)
    {
        if (isset($this->services[$service])) {
            $this->services[$service] = false;
            return true;
        }

        return false;
    }

    public function __call($service, $arguments = array())
    {
        $previous_url = $this->url;

        if (isset($arguments[0]) && filter_var($arguments[0], FILTER_VALIDATE_URL)) {
            $this->url = $arguments[0];
        }

        $response = $this->__get($service);
        $this->url = $previous_url;
        return $response;
    }

    public static function __callStatic($service, $arguments = array())
    {
        if (!isset($arguments[0]) || !filter_var($arguments[0], FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(_('You must specify an address to query.'));
        }

        $instance = new Socialworth($arguments[0]);
        return $instance->$service;
    }
}
