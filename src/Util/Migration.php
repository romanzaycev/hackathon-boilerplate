<?php

namespace Hackathon\Util;

use Phinx\Migration\AbstractMigration;

/**
 * Class Migration
 *
 * @author Roman Zaycev <box@romanzaycev.ru>
 * @package Hackathon\Util
 */
abstract class Migration extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    protected function init()
    {
        parent::init();
    }

    /**
     * Get PG database owner
     *
     * @return string
     */
    protected function getOwner(): string
    {
        return  \getenv("DB_USER");
    }
}