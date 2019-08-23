<?php

use Hackathon\Util\Migration;

/**
 * Class TestMig
 */
class TestMig extends Migration
{
    public function up()
    {
        $this->query("
            CREATE TABLE IF NOT EXISTS yolo
            (
                id serial NOT NULL CONSTRAINT yolo_pk PRIMARY KEY,
                name VARCHAR(512) NOT NULL
            );
        ");
        $this->query("ALTER TABLE yolo owner TO ". $this->getOwner() .";");
    }

    public function down()
    {
        $this->query("DROP TABLE IF EXISTS yolo;");
    }
}
