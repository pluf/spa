<?php

/**
 * نمایش و اجرای spa
 * 
 * @author maso
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
    public static function defaultSpa ($request, $match)
    {
        $name = Setting_Service::get('spa.default', 'start');
        $spa = Spa_SPA::getSpaByName($name);
        return self::loadSpaResource($request, $spa);
    }

    /**
     * Loads SPA (by name) or resource (by name).
     * First search for SPA with specified name.
     * If such SPA is not found search for resource file with specified name in
     * default SPA of tenant.
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public static function loadSpaOrResource ($request, $match)
    {
        $path = $match['path'];
        if (! isset($path)) {
            throw new Pluf_Exception('Name for spa or resource is null!');
        }
        $spa = Spa_SPA::getSpaByName($path);
        $resource = null;
        if (! isset($spa)) {
            $name = Setting_Service::get('spa.default', 'start');
            $spa = Spa_SPA::getSpaByName($name);
            $resource = $path;
        }
        return self::loadSpaResource($request, $spa, $resource);
    }

    /**
     * Load a resource from SPA
     * 
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public static function loadResource ($request, $match)
    {
        // Load data
        $resourcePath = $match['resource'];
        $spa = Spa_SPA::getSpaByName($match['spa']);
        if (! isset($spa)) {
            $name = Setting_Service::get('spa.default', 'start');
            $spa = Spa_SPA::getSpaByName($name);
            $resourcePath = $match['spa'] . '/' . $resourcePath;
        }
        return self::loadSpaResource($request, $spa, $resourcePath);
    }

    /**
     * Loads a resource from an SPA of a tenant.
     * Tenant could not be null.
     * If $spa is null default SPA of tenant is used. If $resource is null
     * default main page of
     * SPA is used.
     *
     * @param Pluf_HTTP_Request $request            
     * @param Spa_SPA $spa            
     * @param string $resource            
     * @throws Pluf_EXception if tenant is null or spa could not be found.
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response|Pluf_HTTP_Response_File
     */
    protected static function loadSpaResource ($request, $spa = null, 
            $resource = null)
    {
        if(!isset($spa)){
            $spa = Spa_Service::getNotfoundSpa();
        }
        if (! isset($resource)) {
            $resPath = $spa->getMainPagePath();
        } else {
            $resPath = $spa->getResourcePath($resource);
        }
        return new Pluf_HTTP_Response_File($resPath, 
                Pluf_FileUtil::getMimeType($resPath));
    }
}