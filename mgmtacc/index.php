<?php
require_once("includes/init.php");
include "model/User.php";
$model = new User();
if ($session->is_signed_in()) {
  redirect('home');
}
if (isset($_POST['submit'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $perm = "";

  $user_found = $model->verify_user($username, $password);
  if ($user_found) {
    $permissions = $model->getperm($user_found['user_id']);
    $perm = "'0':";
    foreach ($permissions as $val) {

      $perm = $perm . "'" . $val['user_pages'] . "';";
    }

    $session->login($user_found, $perm);
    redirect('home');
  } else {
    $the_message = "Your password or username are incorrect";
  }
} else {
  $the_message = '';
  $username = '';
  $password = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/icon/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="../assets/icon/favicon.ico" type="image/x-icon">
  <link rel="icon" href="https://mb.pesoapp.ph/assets/icon/favicon.ico" type="image/x-icon" />
  <title>PESO App Admin Login</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/4efcfd1ff0.js" crossorigin="anonymous"></script>

</head>

<body>
  <div class="absolute top-0 w-full h-full" style="background: #4B6ED6;"></div>
  <section class="absolute top-0 w-full h-full bg-transparent">
    <div class="container mx-auto px-4 h-full">
      <div class="flex items-center justify-center h-full">
        <div class="w-full 2xl:w-4/12 xl:w-5/12 lg:w-6/12 md:w-8/12 sm:w-10/12 px-4 py-10 sm:py-0">
          <div class="relative flex flex-col min-w-0 break-words w-full shadow-lg rounded-lg bg-gray-100 border-0">
            <div class="rounded-t mb-0 px-6 py-0 sm:py-6">
              <div class="text-center mb-8">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-700">PESO App Admin Login</h1>
              </div>
              <div class="flex justify-center rounded-full h-32 sm:h-40 md:h-44 w-32 sm:w-40 md:w-44 bg-blue-600 mx-auto shadow-lg shadow-gray-500">
                <img class="object-contain px-3" src="../assets/NEW PESO GADGET MALL 1 - WHITE.png" alt="logo">
              </div>
            </div>
            <div class="flex-auto px-4 lg:px-10 py-0 sm:py-10">
              <form method="POST">
                <div class="relative w-full mb-3">
                  <label class="block text-gray-700 text-xs font-bold mb-2" for="username">Username</label>
                  <div class="flex items-center border-2 border-blue-600 rounded-lg bg-blue-600">
                    <i class="fa-solid fa-user-large text-xl px-3 rounded-full text-gray-100"></i>
                    <input name="username" type="text" class="px-3 py-3 placeholder-gray-400 text-gray-700 rounded-r-md bg-white border-0 text-sm shadow focus:outline-none focus:shadow-outline w-full" placeholder="Username" style="transition: all 0.15s ease 0s;">
                  </div>
                </div>
                <div class="relative w-full">
                  <label class="block text-gray-700 text-xs font-bold mb-2" for="password">Password</label>
                  <div class="flex items-center border-2 border-blue-600 rounded-lg bg-blue-600">
                    <i class="fa-solid fa-key text-xl px-3 rounded-full text-gray-100"></i>
                    <input name="password" type="password" class="px-3 py-3 placeholder-gray-400 text-gray-700 rounded-r-md bg-white border-0 text-sm shadow focus:outline-none focus:shadow-outline w-full" placeholder="Password" style="transition: all 0.15s ease 0s;">
                  </div>
                  <div>
                    <p class="text-red-500 mt-1 text-bold"><?php echo $the_message; ?></p>
                  </div>
                  <div class="mt-5">
                    <label class="inline-flex items-center cursor-pointer">
                      <input id="remember" id="customCheckLogin" type="checkbox" class="form-checkbox text-gray-800 ml-1 w-5 h-5 accent-blue-500" style="transition: all 0.15s ease 0s;"><span class="ml-2 text-sm font-semibold text-gray-700 ">Remember me</span></label>
                  </div>
                  <div class="text-center mt-3">
                          <button type="submit" name="submit" class="bg-gray-900 text-white active:bg-gray-700 text-md font-bold uppercase px-6 py-3 rounded-xl bg-gradient-to-r from-blue-900 to-blue-700 py-5 shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full" type="button" style="transition: all 0.15s ease 0s;">Sign In</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>