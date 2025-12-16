<h2><?= $title ?></h2>

<div class="filter-bar">
    <label>Période:</label>
    <a href="/benefits?type=day" class="btn <?= $type === 'day' ? 'active' : '' ?>">Jour</a>
    <a href="/benefits?type=month" class="btn <?= $type === 'month' ? 'active' : '' ?>">Mois</a>
    <a href="/benefits?type=year" class="btn <?= $type === 'year' ? 'active' : '' ?>">Année</a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Période</th>
                <th>Nombre de livraisons</th>
                <th>Revenu total</th>
                <th>Coût total</th>
                <th>Bénéfice total</th>
                <th>Marge (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($benefits as $benefit): ?>
            <tr>
                <td><?= $benefit['periode'] ?></td>
                <td><?= $benefit['nombre_livraisons'] ?></td>
                <td><?= number_format($benefit['revenu_total'], 2) ?> Ar</td>
                <td><?= number_format($benefit['cout_total'], 2) ?> Ar</td>
                <td class="<?= $benefit['benefice_total'] >= 0 ? 'positive' : 'negative' ?>">
                    <?= number_format($benefit['benefice_total'], 2) ?> Ar
                </td>
                <td><?= number_format(($benefit['benefice_total'] / $benefit['revenu_total']) * 100, 2) ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
