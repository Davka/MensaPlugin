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
                                <?= MensaHelper::replace($row['TEXT1']) ?>
                                <?= MensaHelper::replace($row['TEXT2']) ?>
                                <?= MensaHelper::replace($row['TEXT3']) ?>
                                <? if (!empty($row['ZSNAMEN'])) : ?>
                                    <small><?= htmlReady($row['ZSNAMEN']) ?></small>
                                <? endif ?>
                            </td>
                            <td><?= sprintf('%s €', htmlReady($row['STD_PREIS'])) ?></td>
                            <td><?= sprintf('%s €', htmlReady($row['BED_PREIS'])) ?></td>
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