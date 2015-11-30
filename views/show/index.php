<? $patterns  = array('/\(/', '/\)/',);
$replacements = array('<sup>', '</sup>'); ?>


<? if (!empty($today)) : ?>

    <table class="default">
        <colgroup>
            <col>
            <col width="70px">
            <col width="70px">
        </colgroup>
        <caption>
            <?= sprintf('Speiseplan f�r den %s', strftime('%A, %d.%m.%Y', $timestamp)) ?>
        </caption>
        <? foreach ($order as $headline) : ?>
            <? if (isset($today[$headline])) : ?>
                <thead>
                <tr>
                    <th><?= htmlReady($headline) ?></th>
                    <th><?= htmlReady('Std') ?></th>
                    <th><?= htmlReady('Bed') ?></th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($today[$headline] as $row) : ?>
                    <tr>
                        <td>

                            <?= preg_replace($patterns, $replacements, htmlReady($row['TEXT1'])) ?>
                            <?= preg_replace($patterns, $replacements, htmlReady($row['TEXT2'])) ?>
                            <?= preg_replace($patterns, $replacements, htmlReady($row['TEXT3'])) ?>

                            <? if (!empty($row['ZSNAMEN'])) : ?>
                                <small><?= preg_replace($patterns, $replacements, htmlReady($row['ZSNAMEN'])) ?></small>
                            <? endif ?>
                            <? foreach ($mapping as $pattern => $replace) : ?>
                                <? if (strpos($row['ZSNAMEN'], $pattern) !== false) : ?>
                                    <?= Assets::img($plugin->getPluginUrl() . '/assets/images/' . $replace) ?>
                                <? endif ?>
                            <? endforeach ?>
                        </td>
                        <td>
                            <?= sprintf('%s &euro;', htmlReady($row['STD_PREIS'])) ?>
                        </td>
                        <td>
                            <?= sprintf('%s &euro;', htmlReady($row['BED_PREIS'])) ?>
                        </td>
                    </tr>
                <? endforeach ?>
                </tbody>
            <? endif ?>
        <? endforeach ?>
    </table>


    <small>
        Die Preisangaben gelten f�r Studierende und Bedienstete.
        F�r G�ste und Bedienstete ohne Dienstausweis gelten folgende Preisaufschl�ge:<br/>
        Eintopf (Teller/Terrine): 0,60 � � Tagessuppe: 0,10 �<br/>
        Hauptkomponenten: 0,50 � � Beilagen: 0,10 �
    </small>

    <?

    $legend = array('a' => 'Glutenhaltiges Getreide',
                    'b' => 'Krebstiere',
                    'c' => 'H�hnerei',
                    'd' => 'Fisch',
                    'e' => 'Erdn�sse',
                    'f' => 'Soja',
                    'g' => 'Milch und Laktose',
                    'h' => 'Schalenfr�chte (N�sse)',
                    'i' => 'Sellerie',
                    'j' => 'Senf',
                    'k' => 'Schwefeldioxid und Sulfite',
                    'l' => 'Lupine',
                    'm' => 'Sesam',
                    'n' => 'Weichtiere',
    );

    $zusaetze = array(1  => 'mit Farbstoff',
                      2  => 'mit Konservierungsstoffen',
                      3  => 'mit Antioxidationsmitteln',
                      4  => 'mit Geschmacksverst�rker',
                      5  => 'geschwefelt',
                      6  => 'geschw�rzt',
                      7  => 'gewachst',
                      8  => 'mit Phosphat',
                      9  => 'mit S��ungsmitteln',
                      10 => 'enth�lt eine Phenylalaninquelle',
    );


    $sidebar = Sidebar::Get();
    $widget  = new SidebarWidget();
    $widget->setTitle('Deklarationspflichtige Zusatzstoffe');
    $widget->addElement(new InfoboxElement($this->render_partial('show/_legend', compact('zusaetze'))));
    $sidebar->addWidget($widget);
    $widget = new SidebarWidget();
    $widget->setTitle('Deklarationspflichtige Allergene');
    $widget->addElement(new InfoboxElement($this->render_partial('show/_legend', compact('legend'))));
    $sidebar->addWidget($widget);
    ?>

<? else : ?>
    <?= MessageBox::info('F�r den gew�nschten Tage wurden noch keine Informationen hinterlegt!') ?>
<? endif ?>
