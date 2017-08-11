<?php

/**
 * Manages lifecycle of an SPA
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
interface Spa_SPA_Manager
{
    
    /**
     * Creates a filter
     *
     * @param Pluf_HTTP_Request $request
     * @return Pluf_SQL
     */
    public function filter($request);
    
    /**
     * Apply action on object
     *
     * Each order must follow CRUD actions in life cycle. Here is default action
     * list:
     *
     * <ul>
     * <li>create</li>
     * <li>read</li>
     * <li>update</li>
     * <li>delete</li>
     * </ul>
     *
     * @param Spa_SPA $order
     * @param String $action
     * @return Spa_SPA
     */
    public function apply($spa, $action);
    
    /**
     * Returns next possible states
     *
     * @param Spa_SPA $order
     * @return array of states
     */
    public function states($spa);
}