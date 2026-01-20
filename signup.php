<?php
session_start();
require_once 'includes/user_functions.php';

$error = '';
$success = '';

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $error = "Password must contain at least one uppercase letter";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $error = "Password must contain at least one lowercase letter";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $error = "Password must contain at least one number";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        if (registerUser($username, $email, $password)) {
            $success = "Registration successful! You can now login.";
            // Redirect to login page after 2 seconds
            header("refresh:2;url=login.php");
        } else {
            $error = "Username or email already exists";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Brand Yasser</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 120px auto;
            padding: 2rem;
            background: var(--white);
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .auth-btn {
            width: 100%;
            padding: 1rem;
            background: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .auth-btn:hover {
            background: #2980b9;
        }

        .error-message {
            color: #e74c3c;
            margin-bottom: 1rem;
            text-align: center;
            padding: 0.5rem;
            background: #fde8e8;
            border-radius: 5px;
        }

        .success-message {
            color: #2ecc71;
            margin-bottom: 1rem;
            text-align: center;
            padding: 0.5rem;
            background: #e8f8e8;
            border-radius: 5px;
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
        }

        .login-link a {
            color: var(--secondary-color);
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-requirements {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .password-requirements ul {
            margin: 0.5rem 0;
            padding-left: 1.5rem;
        }

        .password-requirements li {
            margin-bottom: 0.25rem;
        }

        .password-requirements li.valid {
            color: #2ecc71;
        }

        .password-requirements li.invalid {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.html" class="nav-brand">
            <img src="logo.png" alt="Brand Yasser Logo" class="logo">
            <span class="brand-text">Brand Yasser</span>
        </a>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="shop.html">Shop</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>

    <div class="auth-container">
        <h2 style="text-align: center; margin-bottom: 2rem;">Create Account</h2>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="signup-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required minlength="3">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="8">
                <div class="password-requirements">
                    <p>Password must meet the following requirements:</p>
                    <ul>
                        <li id="length">At least 8 characters long</li>
                        <li id="uppercase">Contains uppercase letter</li>
                        <li id="lowercase">Contains lowercase letter</li>
                        <li id="number">Contains number</li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="auth-btn">Sign Up</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const requirements = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            lowercase: document.getElementById('lowercase'),
            number: document.getElementById('number')
        };

        function checkPassword() {
            const value = password.value;
            
            // Check length
            if (value.length >= 8) {
                requirements.length.classList.add('valid');
                requirements.length.classList.remove('invalid');
            } else {
                requirements.length.classList.add('invalid');
                requirements.length.classList.remove('valid');
            }

            // Check uppercase
            if (/[A-Z]/.test(value)) {
                requirements.uppercase.classList.add('valid');
                requirements.uppercase.classList.remove('invalid');
            } else {
                requirements.uppercase.classList.add('invalid');
                requirements.uppercase.classList.remove('valid');
            }

            // Check lowercase
            if (/[a-z]/.test(value)) {
                requirements.lowercase.classList.add('valid');
                requirements.lowercase.classList.remove('invalid');
            } else {
                requirements.lowercase.classList.add('invalid');
                requirements.lowercase.classList.remove('valid');
            }

            // Check number
            if (/[0-9]/.test(value)) {
                requirements.number.classList.add('valid');
                requirements.number.classList.remove('invalid');
            } else {
                requirements.number.classList.add('invalid');
                requirements.number.classList.remove('valid');
            }
        }

        function checkPasswordMatch() {
            if (password.value === confirmPassword.value) {
                confirmPassword.setCustomValidity('');
            } else {
                confirmPassword.setCustomValidity('Passwords do not match');
            }
        }

        password.addEventListener('input', checkPassword);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html> 