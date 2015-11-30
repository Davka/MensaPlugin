<? $patterns = array('/\(/', '/\)/');
$replacements = array('<sup>', '</sup>'); ?>


<section>
    <? if (!empty($data)) : ?>
        <table class="default">
            <colgroup>
                <col>
                <col width="70px">
                <col width="70px">
            </colgroup>
            <? foreach ($order as $headline) : ?>
                <? if (isset($data[$headline])) : ?>
                    <thead>
                    <tr>
                        <th><?= htmlReady($headline) ?></th>
                        <th><?= htmlReady('Std') ?></th>
                        <th><?= htmlReady('Bed') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($data[$headline] as $row) : ?>
                        <tr>
                            <td>
                                <?= preg_replace($patterns, $replacements, htmlReady($row['TEXT1'])) ?>
                                <?= preg_replace($patterns, $replacements, htmlReady($row['TEXT2'])) ?>
                                <?= preg_replace($patterns, $replacements, htmlReady($row['TEXT3'])) ?>

                                <? if (!empty($row['ZSNAMEN'])) : ?>
                                    <small><?= htmlReady($row['ZSNAMEN']) ?></small>
                                <? endif ?>
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
    <? else : ?>
        <p style="text-align: center"><?= _('Für diesen Tag wurden keine Informationen hinterlegt') ?></p>
    <? endif ?>

</section>