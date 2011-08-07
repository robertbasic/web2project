<?php
/* $Id$ $URL$ */
if (!defined('W2P_BASE_DIR')) {
    die('You should not access this file directly.');
}

// check permissions
$perms = &$AppUI->acl();
if (!canEdit('system')) {
    $AppUI->redirect('m=public&a=access_denied');
}

$module = w2PgetParam($_REQUEST, 'module', 'admin');
$lang = w2PgetParam($_REQUEST, 'lang', $AppUI->user_locale);

$AppUI->savePlace('m=system&a=available_translations');

$canInstall = true;

// read the installed languages
$installedLocales = $AppUI->readDirs('locales');

/**
 * This will be fetched from some outside server, where the translations will
 * be located: sourceforge, github, translations.web2project.net...
 */
$availableLocales = array(
    'sr' => array(
        'Serbian',
        'Srpski',
        'sr',
        'utf-8'
    ),
    'hr' => array(
        'Croatian',
        'Hrvatski',
        'hr',
        'utf-8'
    )
);

$localesFolder = W2P_BASE_DIR . '/locales';

$titleBlock = new CTitleBlock('Translation Management', 'rdf2.png', $m, $m . '.' . $a);
/*
 * TODO: While this implementation is close, I'd rather use the normal setMsg
 *   functionality as it handles marking the message as an error and inserting
 *   linebreaks, etc.
 */
if (!is_writable($localesFolder)) {
    $titleBlock->addCell('', '', '<span class="warning">' . $AppUI->_("Locales folder ($localesFolder) is not writable.") . '</span>', '');
    $canInstall = false;
}

$titleBlock->addCrumb('?m=system', 'system admin');
$titleBlock->addCrumb('?m=system&a=available_translations', 'available translations');
$titleBlock->show();
?>

<table width="100%" border="0" cellpadding="1" cellspacing="1" class="tbl">
    <tr>
        <th width="15%" nowrap="nowrap"><?php echo $AppUI->_('Langauge code'); ?></th>
        <th width="30%" nowrap="nowrap"><?php echo $AppUI->_('English name'); ?></th>
        <th width="30%" nowrap="nowrap"><?php echo $AppUI->_('Name'); ?></th>
        <th width="15%" nowrap="nowrap"><?php echo $AppUI->_('Download and install'); ?></th>
    </tr>
    
    <?php
    $s = '';
    
    foreach($availableLocales as $locale => $localeInfo) {
        $s .= '<tr>';
        $s .= '<td>' . $locale . '</td>';
        $s .= '<td>' . $localeInfo[0] . '</td>';
        $s .= '<td>' . $localeInfo[1] . '</td>';
        if($canInstall && !in_array($locale, $installedLocales)) {
            $s .= '<td>';
            $s .= '<form action="?m=system&a=do_translation_install" method="post" name="installlocale" accept-charset="utf-8">';
            $s .= '<input type="hidden" name="locale" value="'. $locale .'" />';
            $s .= '<input type="submit" value="'. $AppUI->_('submit') .'" class="button" />';
            $s .= '</form>';
            $s .= '</td>';
        } elseif($canInstall) {
            // a check to see if the already installed locale is up to date
            // if not, add an option to update it
            
            // else
            $s .= '<td>Up to date</td>';
        } else {
            $s .= '<td>Can not install</td>';
        }
        $s .= '</tr>';
    }
    
    echo $s;
    ?>
</table>