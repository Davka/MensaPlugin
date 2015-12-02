<?php
require 'bootstrap.php';

/**
 * MensaPlugin.class.php
 *
 * Mensa Plugin for UOS / Vechta
 *
 * @author  David Siegfried <david.siegfried@uni-vechta.de>
 * @version 0.6
 */
class MensaPlugin extends StudIPPlugin implements SystemPlugin
{

    public function __construct()
    {
        parent::__construct();

        $navigation = new Navigation(_('Mensaplan'));
        $navigation->setURL(PluginEngine::GetURL($this, array(), 'show/index'));
        $navigation->setImage(Assets::image_path('icons/lightblue/mensa.svg'));
        Navigation::addItem('/mensaplugin', $navigation);

        $sub = new Navigation(_('Mensaplan'));
        $sub->setURL(PluginEngine::GetURL($this, array(), 'show/index'));
        $sub->setImage(Assets::image_path('icons/blue/mensa'));
        $navigation->addSubNavigation('index', $sub);
    }

    public function initialize()
    {
        $this->addStylesheet('assets/style.less');
        die('foo');
    }

    public function perform($unconsumed_path)
    {
        $dispatcher         = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }
}
