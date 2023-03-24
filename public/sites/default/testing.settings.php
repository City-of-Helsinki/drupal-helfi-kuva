<?php

$config['helfi_proxy.settings']['tunnistamo_return_url'] = '/fi/test-kulttuuri-ja-vapaa-aika/openid-connect/tunnistamo';
// These should be unique for each environment (dev-, test-, staging-)
$config['helfi_proxy.settings']['asset_path'] = 'test-kuva-assets';
$config['helfi_proxy.settings']['prefixes'] = [
  'en' => 'test-culture-and-leisure',
  'fi' => 'test-kulttuuri-ja-vapaa-aika',
  'sv' => 'test-kultur-och-fritid',
  'ru' => 'test-culture-and-leisure',
];

$config['helfi_global_announcement.settings']['source_environment'] = 'test';
