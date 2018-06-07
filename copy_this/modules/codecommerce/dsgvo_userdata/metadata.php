<?php
/**
 * Copyright (c) 2018
 * CodeCommerce - Christopher Bauer
 * www.codecommerce.de
 */
$sMetadataVersion = "2.0";

$aModule = [
    'id'          => 'codecommerce_dsgvo_userdata',
    'title'       => '<img src="../modules/codecommerce/cc_modul.png" alt="CodeCommerce.de" title="CodeCommerce.de"> CodeCommerce.de :: DSGVO Userdaten Export',
    'description' => 'Exportiert die Nutzerdaten in einer Maschinenlesbaren Form<br><br><a href="https://oxidforge.org/en/how-we-temporarily-handle-the-right-to-data-portability-art-20-gdpr.html">Link zum Beitrag für dieses Modul</a>',
    'thumbnail'   => '../logo.png',
    'version'     => '1.0',
    'author'      => 'C. Bauer',
    'email'       => 'info@codecommerce.de',
    'url'         => 'http://www.codecommerce.de',
    'extend'      => [
        \OxidEsales\Eshop\Core\Email::class => \CodeCommerce\UserData\Core\Email::class,
    ],
    'controllers'       => [
        'cc_dsgvo_userdata_export' => \CodeCommerce\UserData\Controller\Admin\Userdataexport::class
    ],
    'templates'   => [
        'cc_dsgvo_userdata_export.tpl'  => 'codecommerce/dsgvo_userdata/views/tpl/cc_dsgvo_userdata_export.tpl',
        'email/html/dsgvo_userdata.tpl' => 'codecommerce/dsgvo_userdata/views/tpl/email/html/dsgvo_userdata.tpl',
    ],
    'events'      => [
        'onActivate' => '\CodeCommerce\UserData\Core\Events\Userdatainit::onActivate',

    ],
];