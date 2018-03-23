<?

/**
 * @author  David Siegfried <david.siegfried@uni-vechta.de>
 * @license GPL2 or any later version
 */

class AddConfig extends Migration
{
    public function description()
    {
        return 'Lagert die FTP-Daten in die globale Konfiguration aus';
    }
    
    public function up()
    {
        if (!Config::get()->MENSA_FTP_SERVER) {
            Config::get()->create('MENSA_FTP_SERVER', [
                'value'       => "131.173.252.37",
                'is_default'  => 0,
                'type'        => 'string',
                'range'       => 'global',
                'section'     => 'MENSA_Plugin',
                'description' => _('Adresse des FTP-Servers'),
            ]);
        }
        
        if (!Config::get()->MENSA_FTP_USER) {
            Config::get()->create('MENSA_FTP_USER', [
                'value'       => "",
                'is_default'  => 0,
                'type'        => 'string',
                'range'       => 'global',
                'section'     => 'MENSA_Plugin',
                'description' => _('Benutzer'),
            ]);
        }
        
        if (!Config::get()->MENSA_FTP_PASS) {
            Config::get()->create('MENSA_FTP_PASS', [
                'value'       => "",
                'is_default'  => 0,
                'type'        => 'string',
                'range'       => 'global',
                'section'     => 'MENSA_Plugin',
                'description' => _('Passwort'),
            ]);
        }
        
        if (!Config::get()->MENSA_FTP_FILE) {
            Config::get()->create('MENSA_FTP_FILE', [
                'value'       => "SPEISEPLAN-Export-4.txt",
                'is_default'  => 0,
                'type'        => 'string',
                'range'       => 'global',
                'section'     => 'MENSA_Plugin',
                'description' => _('Dateiname'),
            ]);
        }
    }
    
    public function down()
    {
        Config::get()->delete('MENSA_FTP_SERVER');
        Config::get()->delete('MENSA_FTP_USER');
        Config::get()->delete('MENSA_FTP_PASS');
        Config::get()->delete('MENSA_FTP_FILE');
    }
    
}