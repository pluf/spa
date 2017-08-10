<?php
Pluf::loadFunction('Spa_Shortcuts_SpaManager');

/**
 * Manages an spa with a state machine.
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Spa_Views_States extends Pluf_Views
{

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return array nest possible states
     */
    public function find($request, $match)
    {
        $repo = new Spa_Views_Repository();
        $spa = $repo->get($request, $match);
        $m = new Spa_SPA_Manager_Remote();
        return $m->states($spa);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function get($request, $match)
    {
        $states = $this->find($request, $match);
        foreach ($states as $state){
            if($state['id'] == $match['stateId']){
                return $state;
            }
        }
        throw new Pluf_HTTP_Error404();
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function put($request, $match)
    {
        $repo = new Spa_Views_Repository();
        $spa = $repo->get($request, $match);
        return Spa_Shortcuts_SpaManager($spa)->apply($spa, $match['stateId']);
    }
}