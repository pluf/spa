<?php
Pluf::loadFunction('Pluf_Form_Field_File_moveToUploadFolder');

/**
 *
 * @param Spa_SPA $spa
 * @return Spa_SPA_Manager
 */
function Spa_Shortcuts_SpaManager($spa)
{
    // XXX: maso, 2017: read from settings
    $manager = new Spa_SPA_Manager_Simple();
    return $manager;
}

