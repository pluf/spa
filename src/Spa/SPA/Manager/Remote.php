<?php
Pluf::loadFunction('Marketplace_Shortcuts_SpaUpdate');

/**
 * Simple SPA management
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Spa_SPA_Manager_Remote implements Spa_SPA_Manager
{

    /**
     * State machine of spa
     *
     * @var array
     */
    static $STATE_MACHINE = array(
        // State
        'Published' => array(
            'install' => array(
                'next' => 'Enable',
                'visible' => false,
                'preconditions' => array(
                    'Pluf_Precondition::isOwner'
                ),
                'action' => array(
                    'Spa_SPA_Manager_Remote',
                    'install'
                )
            )
        )
    );

    /**
     *
     * {@inheritdoc}
     * @see Spa_SPA_Manager::filter()
     */
    public function filter($request)
    {
        return new Pluf_SQL("state=%s", array(
            "Published"
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
     * Install an spa
     *
     * @param Pluf_HTTP_Request $request
     * @param Spa_SPA $object
     */
    public static function install($request, $object)
    {}
}
