<?php

$config['helfi_proxy.settings']['tunnistamo_return_url'] = '/fi/test-kulttuuri-ja-vapaa-aika/openid-connect/tunnistamo';
// These should be unique for each environment (dev-, test-, staging-)
$config['helfi_proxy.settings']['asset_path'] = 'test-kuva-assets';
$config['helfi_proxy.settings']['prefixes'] = [
  'en' => 'test-culture-and-leisure',
  'fi' => 'test-kulttuuri-ja-vapaa-aika',
  'sv' => 'test-kultur-och-fritid',
  'ru' => 'test-culture-and-leisure',
  'zxx' => 'test-culture-and-leisure',
];

$config['helfi_global_announcement.settings']['source_environment'] = 'test';

$config['openid_connect.client.tunnistamo']['settings']['debug_log'] = TRUE;
$config['openid_connect.client.tunnistamo']['settings']['ad_roles'] = [
  [
    'ad_role' => 'Drupal_Helfi_kaupunkitaso_paakayttajat',
    'roles' => ['admin'],
  ],
  [
    'ad_role' => 'Drupal_Helfi_Kulttuuri_ja_vapaa-aika_sisallontuottajat_laaja',
    'roles' => ['editor'],
  ],
  [
    'ad_role' => 'Drupal_Helfi_Kulttuuri_ja_vapaa-aika_sisallontuottajat_suppea',
    'roles' => ['content_producer'],
  ],
];
