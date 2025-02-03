<?php

require 'db.php'; 


$error = '';
$success = '';

// Handle Login
if (isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'lastname' => $user['lastname'],
                'username' => $user['username']
            ];

            // Redirect based on role
            if ($_SESSION['user']['role'] === 'admin') {
                header("Location: manager.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "ایمیل یا رمز عبور اشتباه است";
        }
    } catch (PDOException $e) {
        $error = "خطا در سیستم: " . $e->getMessage();
    }
}

// Handle Signup
if (isset($_POST['signup'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "این ایمیل قبلا ثبت شده است";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, lastname, username, email, password, role) 
                                 VALUES (?, ?, ?, ?, ?, 'user')");
            $stmt->execute([$name, $lastname, $username, $email, $hashed_password]);
            
            $success = "ثبت نام با موفقیت انجام شد!";
            $_POST = array(); // Clear form fields
        }
    } catch (PDOException $e) {
        $error = "خطا در ثبت نام: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Behnews | Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="form-toggle">
            <button class="toggle-btn <?= !isset($_POST['signup']) ? 'active' : '' ?>" onclick="showForm('login')">ورود</button>
            <button class="toggle-btn <?= isset($_POST['signup']) ? 'active' : '' ?>" onclick="showForm('signup')">ثبت نام</button>
        </div>

        <!-- Login Form -->
        <div class="form-box login-form <?= !isset($_POST['signup']) ? 'active' : '' ?>">
            <h2>ورود به سامانه</h2>
            <form method="POST">
                <div class="form-group">
                    <label>ایمیل</label>
                    <input type="email" name="email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group password-container">
                    <label>رمز عبور</label>
                    <div class="input-wrapper">
                        <input type="password" id="loginPassword" name="password" required>
                        <span class="toggle-password" onclick="togglePassword('loginPassword', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                    </div>
                </div>
                <button type="submit" name="login">ورود به حساب</button>
            </form>
        </div>

        <!-- Signup Form -->
        <div class="form-box signup-form <?= isset($_POST['signup']) ? 'active' : '' ?>">
            <h2>ثبت نام جدید</h2>
            <form method="POST">
                <div class="form-group">
                    <label>نام</label>
                    <input type="text" name="name" required 
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>نام خانوادگی</label>
                    <input type="text" name="lastname" required 
                           value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>نام کاربری</label>
                    <input type="text" name="username" required 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>ایمیل</label>
                    <input type="email" name="email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group password-container">
                    <label>رمز عبور</label>
                    <div class="input-wrapper">
                        <input type="password" id="signupPassword" name="password" required>
                        <span class="toggle-password" onclick="togglePassword('signupPassword', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                    </div>
                </div>
                <button type="submit" name="signup">ایجاد حساب</button>
            </form>
        </div>
    </div>

    <script>
        function showForm(formType) {
            document.querySelectorAll('.form-box').forEach(form => {
                form.classList.remove('active');
            });
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.${formType}-form`).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        function togglePassword(inputId, icon) {
            const passwordInput = document.getElementById(inputId);
            const isPassword = passwordInput.type === 'password';
            
            passwordInput.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('active', !isPassword);
        }
    </script>
</body>
</html>