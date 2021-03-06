<?php

/**
 * @author  David Siegfried <david.siegfried@uni-vechta.de>
 * @license GPL2 or any later version
 */
StudipAutoloader::addAutoloadPath(__DIR__ . '/classes');

class MensaPlugin extends StudIPPlugin implements PortalPlugin
{
    public           $mapping;
    public           $order;
    protected static $injected = false;

    /**
     * Constructor sets up the mapping table since PHP won't let you use
     * gettext's _-function in class declaration.
     */
    public function __construct()
    {
        parent::__construct();

        $this->order = [
            'Hauptgericht',
            'Beilagen',
            'Tagessalat',
            'Eintopf Teller',
            'Dessert',
            'Komplettmenü Fleisch/Fisch Caf',
            'Essen Hochschulbedienstete',
            'Komplettmenü veget. Cafe Loung'
        ];
    }

    /**
     * Add several information to object
     * @param Integer $date current date
     */
    public function injectAssets($date = null)
    {
        if (!self::$injected) {
            $this->addStylesheet('assets/mensa-widget.less');
            PageLayout::addScript($this->getPluginURL() . '/assets/mensa-widget.js');
            PageLayout::addHeadElement('meta', [
                'name'    => 'mensa-widget-url',
                'content' => PluginEngine::getLink($this, [], 'menu', true),
            ]);
            PageLayout::addHeadElement('meta', [
                'name'    => 'mensa-widget-date',
                'content' => $date ?: time(),
            ]);

            self::$injected = true;
        }
    }

    /**
     * Returns a template from this plugin with an optional layout (as long
     * as the request was not issued via AJAX).
     *
     * @param String $template Name of the template file
     * @return FlexiTemplate object
     */
    private function getTemplate($template)
    {
        static $factory = null;
        if ($factory === null) {
            $factory = new Flexi_TemplateFactory(__DIR__ . '/templates');
        }
        $template         = $factory->open($template);
        $template->plugin = $this;
        return $template;
    }

    /**
     * Displays the menu for a certain a specific date.
     *
     * @param mixed $date Date to display; optional, defaults to today
     */
    public function menu_action($date = null, $direction = null)
    {
        $date = $this->timeshift($date ?: time(), $direction);

        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 30 * 60));
        header('Pragma: cache');
        header('Cache-Control: max-age=' . 30 * 60);
        header('X-Mensa-Widget-Title: ' . $this->getTitle($date));
        header('X-Mensa-Widget-Date: ' . $date);

        echo $this->renderMenu($date);
    }

    /**
     * Returns the widget/plugin name.
     *
     * @return String containing the localized plugin name.
     */
    public function getPluginName()
    {
        return _('Mensaplan');
    }

    /**
     * Returns the title for the widget.
     *
     * @param int $date Date to display the title for
     * @return String containing the widget title for the given date.
     */
    private function getTitle($date)
    {
        return $this->getPluginName() . ' - ' . strftime('%A %x', $date);
    }

    /**
     * Renders the menu for a specific date.
     *
     * @param int $date Date to display
     * @return String containing the html output for the menu
     */
    private function renderMenu($date)
    {
        try {
            $template        = $this->getTemplate('menu.php');
            $template->data  = $this->getMenu($date);
            $template->order = $this->order;
        } catch (Exception $e) {
            $template          = $this->getTemplate('exception.php');
            $template->message = $e->getMessage();

            if ($e->getCode() > 1) {
                header('X-Mensa-Widget-Disable-Direction: true');
            }
        }

        return $template->render();
    }

    /**
     * Renders the whole menu widget for a specific date.
     *
     * @param int $date Date to display
     * @return String containing the html output for the widget
     * @see MensaWidget::renderMenu
     */
    private function renderWidget($date)
    {
        $template       = $this->getTemplate('widget.php');
        $template->menu = $this->renderMenu($date);
        return $template->render();
    }

    /**
     * Renders the portal widget.
     *
     * @return FlexiTemplate object
     */
    public function getPortalTemplate()
    {
        $options = Request::getArray('mensa-widget');

        if (isset($options['date'])) {
            $date = $options['date'];
        } elseif (date('G') >= 15) {
            $date = $this->timeshift(time(), 'next');
        } else {
            $date = strtotime('today midnight');
        }

        $this->injectAssets($date);

        $navigation = [];

        $nav = new Navigation('');
        $nav->setURL(URLHelper::getURL($GLOBALS['ABSOLUTE_URI_STUDIP'],
            ['mensa-widget' => ['date' => strtotime('yesterday', $date)]]));
        $nav->setImage(
            Icon::create(
                'arr_1left',
                ICON::ROLE_CLICKABLE
            ),
            tooltip2(_('Einen Tag zurück')) + ['class' => 'mensa-widget-back']
        );
        $navigation[] = $nav;

        $nav = new Navigation('');
        $nav->setURL(URLHelper::getURL($GLOBALS['ABSOLUTE_URI_STUDIP'],
            ['mensa-widget' => ['date' => strtotime('tomorrow', $date)]]));
        $nav->setImage(
            Icon::create(
                'arr_1right',
                ICON::ROLE_CLICKABLE
            ),
            tooltip2(_('Einen Tag weiter')) + ['class' => 'mensa-widget-forward']
        );
        $navigation[] = $nav;

        $widget          = $GLOBALS['template_factory']->open('shared/string');
        $widget->content = $this->renderWidget($date);
        $widget->icons   = $navigation;
        $widget->title   = $this->getTitle($date);
        return $widget;
    }

    /**
     * Get the diet for a specific date
     * @param null $date
     * @return mixed[] $data Mensa diet
     */
    private function getMenu($date = null)
    {
        $timestamp = $date ?: strtotime('today midnight');
        return MensaHelper::getMenu($timestamp);
    }

    /**
     * Calculate the prev / next date
     * @param        $date
     * @param string $direction
     * @return int
     */
    protected function timeshift($date, $direction = 'next')
    {
        if ($direction === 'next') {
            do {
                $date = strtotime('tomorrow', $date);
            } while (date('N', $date) > 5);
        } elseif ($direction === 'previous') {
            do {
                $date = strtotime('yesterday', $date);
            } while (date('N', $date) > 5);
        }
        return $date;
    }
}
