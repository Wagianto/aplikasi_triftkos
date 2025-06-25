<?php
require_once __DIR__ . '/includes/db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!empty($email) && !empty($password)) {
        // Cek apakah email sudah digunakan
        $checkQuery = "SELECT id FROM users WHERE email = :email";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(":email", $email);
        $checkStmt->execute();

        if ($checkStmt->fetch()) {
            $error = "Email sudah terdaftar. Silakan gunakan email lain.";
        } else {
            // Simpan password apa adanya (TIDAK DIREKOMENDASIKAN untuk produksi)
            $insertQuery = "INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindParam(":fullname", $fullname);
            $insertStmt->bindParam(":email", $email);
            $insertStmt->bindParam(":password", $password);

            if ($insertStmt->execute()) {
                session_start();
                $_SESSION['success'] = "Pendaftaran akun berhasil! Silakan login.";
                header('Location: login.php');
                exit;
            } else {
                $error = "Terjadi kesalahan saat mendaftar.";
            }
        }
    } else {
        $error = "Email dan password tidak boleh kosong.";
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
    <title>Aplikasi | Register</title>
</head>

<body style="background-image: url('./images/background.jpg'); background-size: cover; background-repeat: no-repeat;">
    <section>
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0 ">
            <div class="w-full bg-white/30 backdrop-blur-md rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0 ">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8 ">
                    <img class="block mx-auto" src="./images/logo_usti.png" alt="Logo Usti" width="100">
                    <h1 class="text-xl text-center font-bold leading-tight tracking-tight text-gray-900 md:text-2xl ">
                        Register Akun
                    </h1>
                    <form class="space-y-4 md:space-y-6" method="POST">
                        <div>
                            <label for="fullname" class="block mb-2 text-sm font-medium text-gray-900 ">Nama Lengkap</label>
                            <input type="text" name="fullname" id="fullname" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" placeholder="Asep Maxwell" required="">
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" placeholder="johndoe@example.com" required="">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" required="">
                        </div>
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Register</button>
                        <p class="text-sm font-light text-gray-500">
                            Udah punya akun? <a href="/aplikasi_login/login.php" class="font-medium text-blue-600 hover:underline">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>