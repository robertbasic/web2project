<?php

if (!defined('W2P_BASE_DIR')) {
    die('You should not call this file directly.');
}

$locale = w2PgetParam($_POST, 'locale', '');

// check permissions
if (!canEdit('system')) {
    $AppUI->redirect('m=public&a=access_denied');
}

// TODO: some sanity checks to double check that the locale can be installed

// we need a temp directory where the new translation will be fetched to first
$tempDirectory = W2P_BASE_DIR . '/files/temp/';
if(!is_writable($tempDirectory)) {
    // sys_get_temp_dir is php 5.2.1+
    // w2p is php 5.2+
    // todo add a fallback, just in case
    $tempDirectory = sys_get_temp_dir();
    if(!is_writable($tempDirectory)) {
        $AppUI->setMsg('Can not install translation, no temporary directory available!', UI_MSG_ERROR);
        $AppUI->redirect('m=system&a=available_translations');
    }
}

// Steps from here will depend on how the translation files will be organized
// on the translation server. ZIPed? Phar? One json file with the complete locale?
// anyway, fetch the locale from the server to the temp directory
// prepare it there, copy the files to the locales directory
// verify somehow the new locale, clean up the temp stuff

$AppUI->setMsg('Translation installed', UI_MSG_OK);
$AppUI->redirect('m=system&a=available_translations');