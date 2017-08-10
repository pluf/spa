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
            'next' => 'Enable',
            'visible' => false
        ),
        // State
        'Enable' => array(
            'checkUpdate' => array(
                'next' => 'Enable',
                'visible' => true,
                'action' => array(
                    'Spa_SPA_Manager_Simple',
                    'checkUpdate'
                ),
                'preconditions' => array(
                    'Pluf_Precondition::isOwner'
                ),
                'properties' => array()
            ),
            'update' => array(
                'next' => 'Enable',
                'visible' => true,
                'action' => array(
                    'Spa_SPA_Manager_Simple',
                    'update'
                ),
                'preconditions' => array(
                    'Pluf_Precondition::isOwner'
                ),
                'properties' => array()
            ),
            'read' => array(
                'next' => 'Enable',
                'visible' => false
            ),
            'delete' => array(
                'next' => 'Deleted',
                'visible' => true,
                'preconditions' => array(
                    'Pluf_Precondition::isOwner'
                )
            ),
            'disable' => array(
                'next' => 'Disabled',
                'visible' => true,
                'preconditions' => array(
                    'Pluf_Precondition::isOwner'
                )
            )
        ),
        'Disabled' => array(),
        'Deleted' => array()
    );

    /**
     *
     * {@inheritdoc}
     * @see Spa_SPA_Manager::filter()
     */
    public function filter($request)
    {
        return new Pluf_SQL("true");
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
     * Check update of an spa
     *
     * @param Pluf_HTTP_Request $request
     * @param Spa_SPA $object
     */
    public static function checkUpdate($request, $object)
    {
        $repo = new Spa_Views_Repository();
        $spa = $repo->get(new Pluf_HTTP_Request('/'), array(
            'modelId' => $object->name
        ));
        $object->last_version = $spa->version;
        $object->update();
        return $object;
    }

    /**
     * Update an spa
     *
     * @param Pluf_HTTP_Request $request
     * @param Spa_SPA $object
     */
    public static function update($request, $object)
    {}
}
