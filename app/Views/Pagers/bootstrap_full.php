<?php
// Pagination style: SB Admin 2 (Bootstrap 4)
$pager->setSurroundCount(2);
?>
<nav aria-label="Page navigation">
  <ul class="pagination justify-content-end mb-0">
    <?php if ($pager->hasPrevious()) : ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Sebelumnya">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
    <?php endif; ?>

    <?php foreach ($pager->links() as $link): ?>
      <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
        <a class="page-link" href="<?= $link['uri'] ?>">
          <?= $link['title'] ?>
        </a>
      </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
      <li class="page-item">
        <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Berikutnya">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    <?php endif; ?>
  </ul>
</nav>