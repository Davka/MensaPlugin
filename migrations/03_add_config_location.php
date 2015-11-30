<?php
class AddConfigLocation extends Migration
{
    function description ()
    {
        return 'Lagert den Standort in die globale Konfiguration aus';
    }

    function up()
    {
        Config::get()->create('MENSA_LOCATION', array(
            'value' => "Mensa Vechta",
            'is_default' => 0,
            'type' => 'string',
            'range' => 'global',
            'section' => 'MENSA_Plugin',
            'description' => _('Standort der Mensa')
        ));

    }

    function down()
    {
        Config::get()->delete('MENSA_LOCATION');
    }

}