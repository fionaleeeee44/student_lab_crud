<?php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="list.php">Student Manager</a>
  </div>
</nav>

<div class="container py-4">
    <h1 class="h3 mb-3">Add Student</h1>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="actions.php" method="post" class="row g-3 needs-validation" novalidate>
                <input type="hidden" name="action" value="create">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full name" required>
                        <label for="name">Name</label>
                        <div class="invalid-feedback">Name is required.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        <label for="email">Email</label>
                        <div class="invalid-feedback">Valid email is required.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="course" name="course" placeholder="Course name" required>
                        <label for="course">Course</label>
                        <div class="invalid-feedback">Course is required.</div>
                    </div>
                </div>
                <div class="col-12">
                    <a href="list.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });
})();
</script>
</body>
</html>

