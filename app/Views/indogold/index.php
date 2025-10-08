<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Update Data Emas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2 class="mb-2">💰 Update Data Harga Emas</h2>
  <div class="text-muted mb-3">Update: <?= esc($updatedAt) ?></div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>Pecahan</th>
          <th>Antam Harga</th>
          <th>Antam Buyback</th>
          <!-- <th>UBS Harga</th>
          <th>UBS Buyback</th> -->
        </tr>
      </thead>
      <tbody>
      <?php foreach ($harga as $gram => $brands): ?>
        <tr>
          <td><?= esc($gram) ?> g</td>
          <td><?= esc($brands['Antam']['harga'] ?? '-') ?></td>
          <td><?= esc($brands['Antam']['harga_buyback'] ?? '-') ?></td>
          <!-- <td><?= esc($brands['UBS']['harga'] ?? '-') ?></td>
          <td><?= esc($brands['UBS']['harga_buyback'] ?? '-') ?></td> -->
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
