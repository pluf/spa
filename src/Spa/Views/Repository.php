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
        $backend = Pluf::f('marketplace.backend', 'http://marketplace.webpich.com');
        $path = '/api/marketplace/spa/find';
        $param = $request->REQUEST;
        
        // Do request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $backend . $path, [
            'query' => $param
        ]);
        
        if("200" != $response->getStatusCode()){
            throw new Pluf_Exception($response->getBody()->getContents());
        }
        $contents = $response->getBody()->getContents();
        return json_decode($contents, true);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function get($request, $match)
    {
        
        // request param
        $backend = Pluf::f('marketplace.backend', 'http://marketplace.webpich.com');
        $path = '/api/marketplace/spa/'.$match['modelId'];
        $param = $request->REQUEST;
        
        // Do request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $backend . $path, [
            'query' => $param
        ]);
        
        if("200" != $response->getStatusCode()){
            throw new Pluf_Exception($response->getBody()->getContents());
        }
        $contents = $response->getBody()->getContents();
        $spa = new Spa_SPA();
        $spa->setFromFormData(json_decode($contents, true));
        return $spa;
    }
}