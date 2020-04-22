<?php
// Init
error_reporting(NULL);
ob_start();
session_start();

include($_SERVER['DOCUMENT_ROOT']."/inc/main.php");

// Check token
if ($_SESSION['user'] != 'admin'
    || ((!isset($_POST['token']) || $_SESSION['token'] != $_POST['token']) && (!isset($_GET['token']) || $_SESSION['token'] != $_GET['token']))
) {
    header('location: /login/');
    exit();
}

$action = $_GET['action'];
$plugin = $_GET['plugin'];

if (!empty($action) && !empty($plugin)) {
    switch ($action) {
        case 'delete': $cmd='v-delete-plugin';
            break;
        case 'disable': $cmd='v-disable-plugin';
            break;
        case 'enable': $cmd='v-enable-plugin';
            break;
        default: header("Location: /list/plugin/"); exit;
    }

    $plugin = (!is_array($plugin)) ? [$plugin] : $plugin;

    foreach ($plugin as $value) {
        $value = escapeshellarg($value);
        $dom = escapeshellarg($domain);
        exec (VESTA_CMD.$cmd." ".$value, $output, $return_var);
        $restart = 'yes';
    }
}

header("Location: /list/plugin/");
exit;

