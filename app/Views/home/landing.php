<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopi Kuy - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .landing-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .2);
            padding: 50px;
            text-align: center;
            max-width: 500px;
        }

        .landing-card i.main-icon {
            font-size: 5rem;
            color: #ffc107;
            margin-bottom: 20px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px 40px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>

<body>
    <div class="landing-card">
        <i class="fas fa-coffee main-icon"></i>
        <h1 class="fw-bold mb-3">Kopi Kuy</h1>
        <p class="text-muted mb-4">
            Complete Point of Sale & Customer Relationship Management System
        </p>
        <a href="/login" class="btn btn-primary btn-login">
            <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
        </a>
        <div class="mt-4">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i> Secure Access Only
            </small>
        </div>
    </div>
</body>

</html>