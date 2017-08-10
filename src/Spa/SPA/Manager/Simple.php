<?php

Pluf::loadFunction('Marketplace_Shortcuts_SpaUpdate');

/**
 * Simple SPA management
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class Spa_SPA_Manager_Simple implements Spa_SPA_Manager
{
    
    /**
     * State machine of spa
     *
     * @var array
     */
    static $STATE_MACHINE = array(
        Workflow_Machine::STATE_UNDEFINED => array(
            'next' => 'Published',
            'visible' => false,
            'action' => array(
                'Marketplace_Spa_Manager_Simple',
                'update'
            ),
            'preconditions' => array(
                'Pluf_Precondition::isOwner'
            )
        ),
        // State
        'Published' => array(
            'update' => array(
                'next' => 'Published',
                'visible' => false,
                'action' => array(
                    'Marketplace_Spa_Manager_Simple',
                    'update'
                ),
                'preconditions' => array(
                    'Pluf_Precondition::isOwner'
                ),
                'properties' => array()
            ),
            'read' => array(
                'next' => 'Published',
                'visible' => false
            ),
            'download' => array(
                'next' => 'Published',
                'visible' => false
            ),
            'delete' => array(
                'next' => 'Deleted',
                'visible' => false,
                'action' => array(
                    'Marketplace_Spa_Manager_Simple',
                    'update'
                ),
                'preconditions' => array(
                    'Pluf_Precondition::isOwner'
                )
            )
        ),
        'Deleted' => array()
    );
    
    /**
     *
     * {@inheritdoc}
     * @see Spa_SPA_Manager::filter()
     */
    public function filter($request)
    {
        return new Pluf_SQL("state=%s", array(
            "publised"
        ));
    }
    
    /**
     *
     * {@inheritdoc}
     * @see Spa_SPA_Manager::apply()
     */
    public function apply($spa, $action)
    {
        $machine = new Workflow_Machine();
        $machine->setStates(self::$STATE_MACHINE)
        ->setSignals(array(
            'Spa_SPA::stateChange'
        ))
        ->setProperty('state')
        ->apply($spa, $action);
        return true;
    }
    
    /**
     *
     * {@inheritdoc}
     * @see Spa_SPA_Manager::states()
     */
    public function states($spa)
    {
        $states = array();
        foreach (self::$STATE_MACHINE[$spa->state] as $id => $state) {
            $state['id'] = $id;
            $states[] = $state;
        }
        return $states;
    }
    
    /**
     * Update an spa
     *
     * @param Pluf_HTTP_Request $request
     * @param Spa_SPA $object
     */
    public static function update($request, $object)
    {
        return Marketplace_Shortcuts_SpaUpdate($request, $object);
    }
    
    /**
     * Deletes an spa
     *
     * @param Pluf_HTTP_Request $request
     * @param Spa_SPA $object
     */
    public static function delete($request, $object)
    {
        $object->deleted = true;
        $object->update();
    }
}
