<h1>Météo pour <?= htmlspecialchars($forecast['city']) ?></h1>
<form method="get" action="/ApiPhpTest/meteo">
    <input type="text" name="city" placeholder="Entrez une ville" value="<?= htmlspecialchars($forecast['city']) ?>" required>
    <button type="submit">Rechercher</button>
</form>
<ul>
    <?php foreach ($forecast['forecast'] as $day): ?>
        <li>
            <?= htmlspecialchars($day['date']->format('d/m/Y H:i')) ?> - 
            Température : <?= htmlspecialchars($day['temp']) ?>°C - 
            Description : <?= htmlspecialchars($day['description']) ?>
        </li>
    <?php endforeach; ?>
</ul>