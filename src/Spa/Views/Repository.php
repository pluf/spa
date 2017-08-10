<?php

/**
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class Spa_Views_Repository extends Pluf_Views
{

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function find($request, $match)
    {
        // request param
        $path = '/api/marketplace/spa/find';
        $param = $request->REQUEST;
        $backend = Pluf::f('marketplace.backend', 'http://marketplace.webpich.com');
        
        // Do request
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function get($request, $match)
    {}
}