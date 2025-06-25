<?php
require_once __DIR__ . '/includes/db_connect.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi input
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validasi input
    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        // Query dengan prepared statement
        $query = "SELECT id, fullname, email, password FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Untuk sementara plain-text, bisa diganti jadi password_verify() nanti
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email'] = $user['email'];
                header('Location: index.php');
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Email tidak ditemukan!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Aplikasi | Login</title>
</head>

<body style="background-image: url('./images/background.jpg'); background-size: cover; background-repeat: no-repeat;">

    <?php

    if (isset($_SESSION['success'])) {
        echo '<div id="flash-message" class="transition-opacity duration-500 opacity-100 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 w-fit absolute bottom-0 left-0 right-0 mx-auto">'
            . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    ?>
    <section>
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0 ">
            <div class="w-full bg-white/30 backdrop-blur-md rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0 ">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8 ">
                    <img class="block mx-auto" src="./images/logo_usti.png" alt="Logo Usti" width="100">
                    <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl ">
                        Login Akun
                    </h1>
                    <form class="space-y-4 md:space-y-6" method="POST">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" placeholder="johndoe@example.com" required>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" required>
                        </div>
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Login</button>
                        <?php if ($error): ?>
                            <p class="text-red-500 font-semibold text-sm italic">Ada error</p>
                        <?php endif; ?>
                        <p class="text-sm font-light text-black-500">
                            Belum punya akun? <a href="/aplikasi_login/register.php" class="font-medium text-blue-600 hover:underline">Register</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script>
        // Tunggu 3 detik (3000ms), lalu sembunyikan elemen flash message
        const flash = document.getElementById('flash-message');
        setTimeout(function() {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.classList.remove("opacity-100");
                flash.classList.add("opacity-0");
                setTimeout(() => flash.remove(), 500); // Hapus elemen dari DOM setelah fade-out
            }
        }, 3000);
    </script>
</body>

</html>