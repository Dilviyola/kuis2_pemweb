<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GudangNET - Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://images.unsplash.com/photo-warehouse') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
        }
        h2 {
            margin-bottom: 10px;
            color: #0984e3;
        }
        .role-switch {
            margin-bottom: 20px;
        }
        .role-switch button {
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            background-color: #dfe6e9;
            cursor: pointer;
        }
        .role-switch button.active {
            background-color: #74b9ff;
            color: white;
        }
        .action-buttons button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            background-color: #0984e3;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="role-switch">
            <button id="customerBtn" class="active" onclick="setRole('customer')">Customer</button>
            <button id="adminBtn" onclick="setRole('admin')">Admin</button>
        </div>
        <h2>Selamat Datang</h2>
        <p>Silakan login atau register untuk melanjutkan.</p>

        <div class="action-buttons">
            <a id="loginLink" href="login.php?role=customer"><button>Login</button></a>
            <a id="registerLink" href="register.php?role=customer"><button>Register</button></a>
        </div>
    </div>

    <script>
        let role = 'customer';
        function setRole(selectedRole) {
            role = selectedRole;
            document.getElementById('loginLink').href = "login.php?role=" + role;
            document.getElementById('registerLink').href = "register.php?role=" + role;

            document.getElementById('customerBtn').classList.toggle('active', role === 'customer');
            document.getElementById('adminBtn').classList.toggle('active', role === 'admin');
        }
    </script>
</body>
</html>
