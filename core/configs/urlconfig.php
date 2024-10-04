<?php

use core\base\Request;

define(
    'BASE_URL',
    $_ENV['BASE_URL'] ?? CONFIG_APP['hosturl'] ?? (new Request)->getBaseUrl() ?? 'http//localhost'
);

/**
 * 
 */
define('CONFIG_URL', [
    'assets' => "/assets",
    'uploads' => "/uploads",
    'api' => "/api",
]);


const base_url = BASE_URL;

const assets = base_url . CONFIG_URL['assets'];
const assets_js = assets . '/js';
const assets_css = assets . '/css';
const assets_img = assets . '/img';
const assets_ico = assets . '/ico';
const assets_images = assets . '/images';

const uploads = base_url . CONFIG_URL['uploads'];
const uploads_images = uploads . '/img';
const uploads_videos = uploads . '/vid';
const uploads_audio = uploads . '/aud';
const uploads_profile = uploads . '/profile';