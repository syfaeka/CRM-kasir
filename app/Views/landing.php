<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopi Kuy - Modern POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-content {
            z-index: 2;
        }

        .hero-icon {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 2rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .btn-cta {
            background-color: white;
            color: #764ba2;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-cta:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            color: #667eea;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            margin-top: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div class="container text-center hero-content">
            <i class="fas fa-coffee hero-icon"></i>
            <h1 class="display-3 fw-bold mb-4">Kopi Kuy POS</h1>
            <p class="lead mb-5 fs-3" style="opacity: 0.9;">
                Manage your coffee shop efficiently.<br>
                Sales, Inventory, and CRM in one place.
            </p>

            <a href="/login" class="btn btn-cta text-decoration-none">
                <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
            </a>

            <div class="row justify-content-center mt-5">
                <div class="col-md-8">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="glass-card">
                                <i class="fas fa-cash-register fa-2x mb-3"></i>
                                <h5>Fast POS</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass-card">
                                <i class="fas fa-chart-line fa-2x mb-3"></i>
                                <h5>Analytics</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass-card">
                                <i class="fas fa-users fa-2x mb-3"></i>
                                <h5>CRM & Loyalty</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>