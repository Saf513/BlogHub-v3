
<?php 
session_start();
require_once __DIR__ . '/../config/connection.php';

$pdo = $dbConnection->getConnection();
$auth = new Auth($pdo);
$user = $auth -> getUser();
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<header class="flex shadow-md py-4 px-4 sm:px-10 bg-white font-[sans-serif] min-h-[70px] tracking-wide relative z-50">
    <div class="flex flex-wrap items-center justify-between gap-5 w-full">
        <a href="/">
            <img src="/asset/img/BLOG HUB.png" alt="logo" class="w-36" style="width: 100px; height: auto;">
        </a>
        <nav id="collapseMenu" class="max-lg:hidden lg:block">
            <ul class="lg:flex gap-x-5">
                <li><a href="/" class="text-[#007bff] font-semibold hover:text-blue-700">Home</a></li>
                <li><a href="/view/BLOG.PHP" class="text-gray-500 font-semibold hover:text-blue-700">Blog</a></li>
                <li><a href="#" class="text-gray-500 font-semibold hover:text-blue-700">Feature</a></li>
                <li><a href="#" class="text-gray-500 font-semibold hover:text-blue-700">About</a></li>
                <li><a href="#" class="text-gray-500 font-semibold hover:text-blue-700">Contact</a></li>
                <?php if ($user && $user['role'] === 'admin') { ?>
                <li><a href="/Admin/dashboard.php" class="text-gray-500 font-semibold hover:text-blue-700">Dashboard</a></li>
                <?php } ?>
            </ul>
        </nav>
        <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['user_id']) && $user) { ?>
            <p class='text-gray-700'>Bonjour, <?php echo htmlspecialchars($user['username']); ?></p>
            <div class="relative inline-block">
                <a href="../view/profile.php"><img src="../users/<?php echo htmlspecialchars($user['user_image']); ?>" class="w-14 h-14 rounded-full border-2 border-blue-600 p-0.5" /></a>
                <span class="h-3 w-3 rounded-full border border-white bg-green-500 block absolute top-1 right-0"></span>
            </div>
            <a href="/authentification/logout.php" class="px-4 py-2 text-sm rounded-full font-bold text-white border-2 border-red-500 bg-red-500 hover:bg-transparent hover:text-red-500">Déconnexion</a>
            <?php } else { ?>
            <a href="/authentification/login.php" class="px-4 py-2 text-sm rounded-full font-bold text-gray-500 border-2 hover:bg-gray-50">Login</a>
            <a href="/authentification/inscreption.php" class="px-4 py-2 text-sm rounded-full font-bold text-white border-2 border-[#007bff] bg-[#007bff] hover:bg-transparent hover:text-[#007bff]">Sign Up</a>
            <?php } ?>
            <button id="toggleOpen" class="lg:hidden">
                <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</header>
<div class="flex rounded-full border-2 border-blue-500 overflow-hidden max-w-md mx-auto font-[sans-serif] mt-4">
    <input type="email" placeholder="Search Something..." class="w-full outline-none bg-white text-sm px-5 py-3" />
    <button type='button' class="flex items-center justify-center bg-blue-500 hover:bg-blue-600 px-6">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" width="18px" class="fill-white">
            <path d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.908-6.991 51.981-18.603l47.078 47.078a7.5 7.5 0 0 0 10.606-10.604z"></path>
        </svg>
    </button>
</div>