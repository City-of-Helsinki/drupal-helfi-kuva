<?php

$config['helfi_proxy.settings']['tunnistamo_return_url'] = '/fi/kulttuuri-ja-vapaa-aika/openid-connect/tunnistamo';
$config['helfi_proxy.settings']['asset_path'] = 'kuva-assets';
$config['helfi_proxy.settings']['prefixes'] = [
  'en' => 'culture-and-leisure',
  'fi' => 'kulttuuri-ja-vapaa-aika',
  'sv' => 'kultur-och-fritid',
  'ru' => 'culture-and-leisure',
  'zxx' => 'culture-and-leisure',
];

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
