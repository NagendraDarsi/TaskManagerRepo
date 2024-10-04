<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User and Admin Login</title>
    <style>
        /* Styling for buttons and forms */
        .container {
            text-align: center;
            margin-top: 50px;
        }
        .btn-container {
            margin-bottom: 30px;
        }
        .login-btn {
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            margin: 0 10px;
        }
        .login-box {
            display: none; /* Hide forms initially */
            width: 30%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .login-box label,
        .login-box input,
        .login-box button {
            margin-bottom: 15px;
            display: block;
            width: 100%;
        }
        .login-box input {
            padding: 7px;
            font-size: 16px;
        }
        .login-box button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .error-messages {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
            <div class="btn-container">
                <button class="login-btn" onclick="showForm('user')">Login as User</button>
                <button class="login-btn" onclick="showForm('admin')">Login as Admin</button>
            </div>
            <div id="user-login" class="login-box">
                <h2>User Login</h2>
                <form id="user-login-form" method="POST">
                    @csrf
                    <label for="user-email">Email:</label>
                    <input type="email" name="email" id="user-email" required>
                    
                    <label for="user-password">Password:</label>
                    <input type="password" name="password" id="user-password" required>
                    <button type="submit">Login as User</button>
                </form>
                <div id="user-error" class="error-messages"></div>
            </div>

            <!-- Admin Login Form -->
            <div id="admin-login" class="login-box">
                <h2>Admin Login</h2>
                <form id="admin-login-form" method="POST">
                    @csrf
                    <label for="admin-email">Email:</label>
                    <input type="email" name="email" id="admin-email" required>
                    
                    <label for="admin-password">Password:</label>
                    <input type="password" name="password" id="admin-password" required>
                    
                    <button type="submit">Login as Admin</button>
                </form>
                <div id="admin-error" class="error-messages"></div>
            </div>
    </div>

    <script>
            function showForm(type) {
                // Hide both forms first
                document.getElementById('user-login').style.display = 'none';
                document.getElementById('admin-login').style.display = 'none';

                // Show the correct form based on the button clicked
                if (type === 'user') {
                    document.getElementById('user-login').style.display = 'block';
                } else if (type === 'admin') {
                    document.getElementById('admin-login').style.display = 'block';
                }
            }

            // Handle login form submissions with AJAX for User
            document.getElementById('user-login-form').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent form from submitting the traditional way
                let formData = new FormData(this);
                let errorContainer = document.getElementById('user-error');
                fetch('{{ route('login') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Redirect to the intended page
                        window.location.href = data.redirect_url;
                    } else {
                        // Display error messages
                        errorContainer.innerHTML = `<ul><li>${data.message}</li></ul>`;
                    }
                })
                .catch(error => {
                    // Catch any network errors
                    errorContainer.innerHTML = 'An error occurred. Please try again.';
                });
            });

            // Handle login form submissions with AJAX for Admin
            document.getElementById('admin-login-form').addEventListener('submit', function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                let errorContainer = document.getElementById('admin-error');

                fetch('{{ route('admin.login') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Redirect to the intended page
                        window.location.href = data.redirect_url;
                    } else {
                        // Display error messages
                        errorContainer.innerHTML = `<ul><li>${data.message}</li></ul>`;
                    }
                })
                .catch(error => {
                    // Catch any network errors
                    errorContainer.innerHTML = 'An error occurred. Please try again.';
                });
            });
    </script>
</body>
</html>
