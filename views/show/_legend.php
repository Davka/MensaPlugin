<?
if (!empty($zusaetze)) {
    $data = $zusaetze;
}
if (!empty($legend)) {
    $data = $legend;
}

?>
<dl class="legend">
    <? foreach ($data as $index => $value) : ?>
        <dt><?= $index ?></dt>
        <dd><?= htmlReady($value) ?></dd>
    <? endforeach ?>
</dl>