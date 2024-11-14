<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Login</h3>
                        <?php if (session()->getFlashdata('msg')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('msg') ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= base_url('/login') ?>" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Username</label> <!-- Change label -->
                                <input type="text" class="form-control" id="email" name="email" required> <!-- Change input type to text -->
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <p class="text-center mt-3">Not a member? <a href="<?= base_url('/register') ?>">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>