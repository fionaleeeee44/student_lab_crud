<?php
require_once __DIR__ . '/config.php';

$allowed = ['name','email','course','created_at'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed, true) ? $_GET['sort'] : 'created_at';
$order = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC','DESC'], true) ? strtoupper($_GET['order']) : 'DESC';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$where = '';
$params = [];
if ($q !== '') {
    $where = 'WHERE name LIKE :q OR email LIKE :q OR course LIKE :q';
    $params[':q'] = "%{$q}%";
}

$stmt = $pdo->prepare("SELECT id, name, email, course, created_at FROM students {$where} ORDER BY {$sort} {$order}");
$stmt->execute($params);
$rows = $stmt->fetchAll();

function linkSort($label, $field, $sort, $order, $q): string {
    $newOrder = ($sort === $field && $order === 'ASC') ? 'DESC' : 'ASC';
    $qs = http_build_query(['sort'=>$field,'order'=>$newOrder,'q'=>$q]);
    $arrow = $sort === $field ? ($order === 'ASC' ? '▲' : '▼') : '';
    return '<a href="?'. h($qs) .'" class="text-decoration-none">' . h($label) . ' ' . $arrow . '</a>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Students - List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="list.php">Student Manager</a>
    <div class="ms-auto">
      <a class="btn btn-outline-light" href="create.php">Add Student</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo h($_GET['msg']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="row g-3 align-items-center mb-3">
    <div class="col-md-8">
      <form class="d-flex" method="get">
        <input class="form-control me-2" type="search" placeholder="Search name, email, course" name="q" value="<?php echo h($q); ?>">
        <button class="btn btn-primary" type="submit">Search</button>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th><?php echo linkSort('Name','name',$sort,$order,$q); ?></th>
              <th><?php echo linkSort('Email','email',$sort,$order,$q); ?></th>
              <th><?php echo linkSort('Course','course',$sort,$order,$q); ?></th>
              <th><?php echo linkSort('Created','created_at',$sort,$order,$q); ?></th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="6" class="text-center text-muted p-4">No records.</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $i => $r): ?>
              <tr>
                <td><?php echo (string)($i+1); ?></td>
                <td><?php echo h($r['name']); ?></td>
                <td><?php echo h($r['email']); ?></td>
                <td><?php echo h($r['course']); ?></td>
                <td class="text-nowrap"><span class="badge text-bg-secondary"><?php echo h($r['created_at']); ?></span></td>
                <td>
                  <a class="btn btn-sm btn-primary" href="edit.php?id=<?php echo (string)$r['id']; ?>">Edit</a>
                  <form action="actions.php" method="post" class="d-inline" onsubmit="return confirm('Delete this student?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo (string)$r['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

