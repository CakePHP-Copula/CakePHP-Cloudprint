<?php
                    Router::connect('/oauth2callback', array('plugin' => 'cloudprint', 'controller' => 'oauth', 'action' => 'callback'));
                    Router::connect('/oauth2authorize', array('plugin' => 'cloudprint', 'controller' => 'oauth', 'action' => 'authorize'));
?>