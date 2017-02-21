<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *
 * @author pluf.ir
 * @since 2.0.3
 */
class Spa_Service
{

    public static function getNotfoundSpa ()
    {
        $name = 'not-found';
        $spa = Spa_SPA::getSpaByName($name);
        if (! isset($spa)) {
            return self::installFromFile(__DIR__ . '/resources/not-found.zip');
        }
        return $spa;
    }

    /**
     * Install spa from file into the tenant.
     *
     * @param String $path            
     * @param string $deleteFile            
     * @throws Pluf_Exception
     */
    public static function installFromFile ($path, $deleteFile = false)
    {
        // Temp folder
        $key = 'spa-' .
                 md5(microtime() . rand(0, 123456789) . Pluf::f('secret_key'));
        $dir = Pluf_Tenant::storagePath() . '/spa/' . $key;
        if (! mkdir($dir, 0777, true)) {
            throw new Pluf_Exception('Failed to create folder in temp');
        }
        
        // Unzip to temp folder
        $zip = new ZipArchive();
        if ($zip->open($path) === TRUE) {
            $zip->extractTo($dir);
            $zip->close();
        } else {
            throw new Pluf_Exception('Unable to unzip SPA.');
        }
        if ($deleteFile) {
            unlink($path);
        }
        
        // 2- load infor
        $filename = $dir . '/' . Pluf::f('spa_config', 'spa.json');
        $myfile = fopen($filename, 'r') or die('Unable to open file!');
        $json = fread($myfile, filesize($filename));
        fclose($myfile);
        $package = json_decode($json, true);
        
        // 3- crate spa
        $spa = new Spa_SPA();
        $spa->path = 'not/set';
        $spa->setFromFormData($package);
        $spa->create();
        
        $spa->path = Pluf_Tenant::storagePath() . '/spa/' . $spa->id;
        $spa->update();
        
        Pluf_FileUtil::removedir($spa->path);
        rename($dir, $spa->path);
        return $spa;
    }
}