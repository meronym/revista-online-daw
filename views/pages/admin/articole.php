<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Articole</h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="/admin/articole/csv">Export CSV</a>
        <a class="btn btn-primary" href="/admin/articole/nou">Articol nou</a>
    </div>
</div>

<div class="table-responsive bg-body rounded shadow-sm">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Titlu</th>
                <th>Rubrică</th>
                <th>Autor</th>
                <th>Stare</th>
                <th>Creat</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php renderList($articles, 'article-row'); ?>
        </tbody>
    </table>
</div>
