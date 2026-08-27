<tr>
    <td><?= e($item['titlu']) ?></td>
    <td><?= e($item['rubrica']) ?></td>
    <td><?= e($item['autor']) ?></td>
    <td>
        <span class="badge text-bg-<?= $item['stare'] === 'publicat' ? 'success' : 'secondary' ?>">
            <?= $item['stare'] === 'publicat' ? 'Publicat' : 'Ciornă' ?>
        </span>
    </td>
    <td class="text-body-secondary small"><?= e(formatDate($item['creat_la'])) ?></td>
    <td class="text-end text-nowrap">
        <a class="btn btn-sm btn-outline-secondary" href="/admin/articole/<?= (int) $item['id'] ?>">Editează</a>

        <form class="d-inline" method="post"
              action="/admin/articole/<?= (int) $item['id'] ?>/stergere"
              onsubmit="return confirm('Ștergi articolul definitiv?')">
            <button class="btn btn-sm btn-outline-danger" type="submit">Șterge</button>
        </form>
    </td>
</tr>
