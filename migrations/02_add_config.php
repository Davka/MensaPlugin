<?php
class AddConfig extends Migration
{
    function description ()
    {
        return 'Lagert die FTP-Daten in die globale Konfiguration aus';
    }

    function up()
    {
        Config::get()->create('MENSA_FTP_SERVER', array(
            'value' => "131.173.252.37",
            'is_default' => 0,
            'type' => 'string',
            'range' => 'global',
            'section' => 'MENSA_Plugin',
            'description' => _('Adresse des FTP-Servers')
        ));
        Config::get()->create('MENSA_FTP_USER', array(
            'value' => "",
            'is_default' => 0,
            'type' => 'string',
            'range' => 'global',
            'section' => 'MENSA_Plugin',
            'description' => _('Benutzer')
        ));

        Config::get()->create('MENSA_FTP_PASS', array(
            'value' => "",
            'is_default' => 0,
            'type' => 'string',
            'range' => 'global',
            'section' => 'MENSA_Plugin',
            'description' => _('Passwort')
        ));

        Config::get()->create('MENSA_FTP_FILE', array(
            'value' => "SPEISEPLAN-Export-4.txt",
            'is_default' => 0,
            'type' => 'string',
            'range' => 'global',
            'section' => 'MENSA_Plugin',
            'description' => _('Dateiname')
        ));
    }

    function down()
    {
        Config::get()->delete('MENSA_FTP_SERVER');
        Config::get()->delete('MENSA_FTP_USER');
        Config::get()->delete('MENSA_FTP_PASS');
        Config::get()->delete('MENSA_FTP_FILE');
    }

}