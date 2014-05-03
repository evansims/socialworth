<?php
require 'tests/bootstrap.php';

use EvanSims\Socialworth;

// Command Line
if (PHP_SAPI == 'cli' && isset($argv) && count($argv > 1)) {
    header("Content-Type: text/plain");

    $instance = new Socialworth();
    $services = array();
    $response = array('total' => 0);

    for ($i = 1; $i < count($argv); $i++) {
        $arg = $argv[$i];

        if (substr($arg, 0, 2) == '--') {
            $arg = substr($arg, 2);
            if (isset($instance->services[$arg])) {
                $services[] = $arg;
            }
        } elseif (filter_var($arg, FILTER_VALIDATE_URL)) {
            $instance->url($arg);
        }
    }

    if ($services) {
        foreach ($services as $service) {
            $response[$service] = $instance->$service;
            $response['total'] += $response[$service];
        }
    } else {
        $response = $instance->all();
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

// Script queried directly
if (isset($_GET['url'])) {
    header("Content-Type: application/json");
    $services = (isset($_GET['services']) ? $_GET['services'] : '');
    $services = explode(',', $services);

    $instance = new Socialworth($_GET['url'], $services);
    echo json_encode($instance->all(), JSON_PRETTY_PRINT);
    exit;
}
