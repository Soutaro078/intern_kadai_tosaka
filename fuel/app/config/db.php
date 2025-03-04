<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */
 return array(
     'default' => array(
         'type'        => 'mysqli',
         'connection'  => array(
             'hostname'   => 'db',
             'port'       => '3306',
             'database'   => 'fuelphp_todo',
             'username'   => 'root',
             'password'   => 'root',
             'persistent' => false,
         ),
         'charset'     => 'utf8',
         'caching'     => false,
         'profiling'   => false,
     ),
 );