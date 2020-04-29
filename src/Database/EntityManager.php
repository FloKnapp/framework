<?php

namespace Webasics\Framework\Database;

/**
 * Class EntityManager
 * @package Webasics\Framework\ORM
 */
class EntityManager extends \ORM\EntityManager
{

    /**
     * EntityManager constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (empty($options)) {
            $options = [
                EntityManager::OPT_CONNECTION => ['sqlite', __DIR__ . '/../../tests/Fixtures/database/db.sq3']
            ];
        }

        parent::__construct($options);
    }

}