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
return array(
    
    
    array(
        'regex' => '#^/(?P<modelId>\d+)/states/find$#',
        'model' => 'Spa_Views_States',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)/states/(?P<stateId>.+)$#',
        'model' => 'Spa_Views_States',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)/states/(?P<stateId>.+)$#',
        'model' => 'Spa_Views_States',
        'method' => 'put',
        'http-method' => 'PUT',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    
    array(
        'regex' => '#^/(?P<modelId>\d+)/resources/find$#',
        'model' => 'Spa_Views_Resources',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)/resources/new$#',
        'model' => 'Spa_Views_Resources',
        'method' => 'create',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)/resources/(?P<resourcePath>.+)$#',
        'model' => 'Spa_Views_Resources',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)/resources/(?P<resourcePath>.+)$#',
        'model' => 'Spa_Views_Resources',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)/resources/(?P<resourcePath>.+)$#',
        'model' => 'Spa_Views_Resources',
        'method' => 'delete',
        'http-method' => 'Delete',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    
    array(
        'regex' => '#^/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Spa_SPA',
            'listFilters' => array(
                'id',
                'title',
                'symbol'
            ),
            'listDisplay' => array(
                'id' => 'spa id',
                'title' => 'title',
                'creation_dtime' => 'creation time'
            ),
            '$searchFields' => array(
                'name',
                'title',
                'description',
                'homepage'
            ),
            'sortFields' => array(
                'id',
                'name',
                'title',
                'homepage',
                'license',
                'version',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'creation_dtime',
                'DESC'
            )
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Spa_SPA'
        )
    ),
    array(
        'regex' => '#^/new$#',
        'model' => 'Spa_Views',
        'method' => 'create',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>.+)$#',
        'model' => 'Spa_Views',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<spaId>.+)$#',
        'model' => 'Spa_Views',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    )
);
