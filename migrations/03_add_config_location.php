<?

/**
 * @author  David Siegfried <david.siegfried@uni-vechta.de>
 * @license GPL2 or any later version
 */

class AddConfigLocation extends Migration
{
    public function description()
    {
        return 'Lagert den Standort in die globale Konfiguration aus';
    }
    
    public function up()
    {
        if (!Config::get()->MENSA_LOCATION) {
            Config::get()->create('MENSA_LOCATION', [
                'value'       => "Mensa Vechta",
                'is_default'  => 0,
                'type'        => 'string',
                'range'       => 'global',
                'section'     => 'MENSA_Plugin',
                'description' => _('Standort der Mensa'),
            ]);
        }
    }
    
    public function down()
    {
        Config::get()->delete('MENSA_LOCATION');
    }
    
}