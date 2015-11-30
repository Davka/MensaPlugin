<?php

class ShowController extends StudipController
{

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
        $this->file   = MensaHelper::getFilename();
    }

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base.php'));
        PageLayout::setTitle(_('Mensaplan'));

        if (!file_exists($this->file)) {
            throw new Trails_Exception(403, _('Die Mensa-Datei konnte nicht gefunden werden.'));
        }

        $this->mapping = array('vegan'           => 'vegan.png',
                               'Bio'             => 'bio.gif',
                               'Knoblauch'       => 'knoblauch.gif',
                               'vegetarisch'     => 'vegetarisch.png',
                               'Geflügel'        => 'gefluegel.png',
                               'Rindfleisch'     => 'rindfleisch.gif',
                               'Schweinefleisch' => 'schweinefleisch.gif');

        $this->order = array('Hauptgericht',
                             'Beilagen',
                             'Tagessalat',
                             'Eintopf Teller', 'Dessert',
                             'Komplettmenü Fleisch/Fisch Caf',
                             'Essen Hochschulbedienstete',
                             'Komplettmenü veget. Cafe Loung');
    }

    public function index_action($timestamp = null)
    {
        Navigation::activateItem('/mensaplugin/index');
        $data = MensaHelper::getMenu();
        $today = strtotime('today');
        $this->timestamp = !is_null($timestamp) ? $timestamp : $today;
        $this->today     = $data[$this->timestamp];

        $this->data = $data;
        $this->setSidebar();
    }

    private function setSidebar() {

        $sidebar = Sidebar::Get();
        $sidebar->setTitle('Mensa Speisepläne');
        $views = new ViewsWidget();
        $views->setTitle(_('Tagespläne'));
        foreach (array_keys($this->data) as $_stamp) {
            if ($_stamp >= strtotime('today midnight')) {
                $views->addLink(strftime('%A, %d.%m.%Y', $_stamp),
                    $this->url_for('show/index', $_stamp))->setActive($this->timestamp == $_stamp);
            }
        }
        $sidebar->addWidget($views);
    }

    // customized #url_for for plugins
    public function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args    = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    }

}
