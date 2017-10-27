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

/**
 *
 * @author maso
 *        
 */
class Spa_HTTP_Response_Stream extends Pluf_HTTP_Response
{

    /**
     *
     * @param
     *            Psr\Http\Message\StreamInterface URL
     */
    function __construct($stream)
    {
        $content = $stream;
        parent::__construct($content);
    }

    /**
     *
     * {@inheritdoc}
     * @see Pluf_HTTP_Response::render()
     */
    function render($output_body = true)
    {
        /**
         *
         * @var Psr\Http\Message\StreamInterface $content
         */
        $content = $this->content;
        if ($this->status_code >= 200 && $this->status_code != 204 && $this->status_code != 304) {
            $this->headers['Content-Length'] = $content->getSize();
        }
        $this->outputHeaders();
        while (! $content->eof()) {
            echo $response->getBody()->read(1024);
        }
    }
}
