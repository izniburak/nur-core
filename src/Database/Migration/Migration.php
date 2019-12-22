<?php

namespace Nur\Database\Migration;

use Phpmig\Migration\Migration as BaseMigration;

class Migration extends BaseMigration
{
    /**
     * @var
     */
    protected $schema;

    /**
     * Initialize for Migration Class.
     *
     * @return void
     */
    public function init()
    {
        $this->schema = $this->get('schema');
    }
}
