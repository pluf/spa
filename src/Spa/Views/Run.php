<?php

/**
 * Run SPAs.
 * 
 * @author pluf<info@pluf.ir>
 *
 */
class Spa_Views_Run
{

    /**
     * Load default spa
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public static function defaultSpa($request, $match)
    {
        $name = Setting_Service::get('spa.default', 'not-found');
        $spa = Spa_SPA::getSpaByName($name);
        if (! isset($spa)) {
            $spa = Spa_Service::getNotfoundSpa();
        }
        $resPath = $spa->getMainPagePath();
        return new Spa_HTTP_Response_Main($resPath, Pluf_FileUtil::getMimeType($resPath));
    }

    /**
     * Load a resource from SPA
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public static function loadResource($request, $match)
    {
        // First part of path
        $firstPart = $match['firstPart'];
        // Remain part of path
        $remainPart = '';
        if (array_key_exists('remainPart', $match)) {
            $remainPart = $match['remainPart'];
        }
        $spa = Spa_SPA::getSpaByName($firstPart);
        if (isset($spa)) { // SPA is valid
            $path = $remainPart;
            $spaName = $firstPart;
        } else { // first part is not an SPA so use default SPA
            $name = Setting_Service::get('spa.default', 'not-found');
            $spa = Spa_SPA::getSpaByName($name);
            $path = $firstPart . '/' . $remainPart;
            $spaName = null;
        }
        if (preg_match('/.+\.[a-zA-Z0-9]+$/', $path)) {
            // Looking for file in SPA
            $resPath = $spa->getResourcePath($path);
            $isMain = false;
        } else {
            // Request is for main file (path is an internal state)
            $resPath = $spa->getMainPagePath();
            $isMain = true;
        }
        if (file_exists($resPath)) {
            if ($isMain) {
                return new Spa_HTTP_Response_Main($resPath, Pluf_FileUtil::getMimeType($resPath), $spaName);
            } else {
                return new Pluf_HTTP_Response_File($resPath, Pluf_FileUtil::getMimeType($resPath));
            }
        }
        
        $spa = Spa_Service::getNotfoundSpa();
        // Looking for file in notfound spa
        $w = $match[0];
        do {
            $w = substr($w, strpos($w, '/') + 1);
            $resPath = $spa->getResourcePath($w);
            if (file_exists($resPath)) {
                return new Pluf_HTTP_Response_File($resPath, Pluf_FileUtil::getMimeType($resPath));
            }
        } while (strpos($w, '/'));
        
        // not found spa main page
        $resPath = $spa->getMainPagePath();
        return new Spa_HTTP_Response_Main($resPath, Pluf_FileUtil::getMimeType($resPath), 'not-found');
    }
}