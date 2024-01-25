<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['borrar'])) {
   $delete_id = $_GET['borrar'];
   $delete_user = $conn->prepare("DELETE FROM `usuarios` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `pedidos` WHERE usuario_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $conn->prepare("DELETE FROM `mensajes` WHERE usuario_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `carro` WHERE usuario_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `lista_de_deseos` WHERE usuario_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cuentas de usuarios</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <section class="accounts">

        <h1 class="heading">cuentas de usuarios</h1>

        <div class="box-container">

            <?php
         $select_accounts = $conn->prepare("SELECT * FROM `usuarios`");
         $select_accounts->execute();
         if ($select_accounts->rowCount() > 0) {
            while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
         ?>
            <div class="box">
                <p> usuario id : <span><?= $fetch_accounts['id']; ?></span> </p>
                <p> nombre de uuario : <span><?= $fetch_accounts['nombre']; ?></span> </p>
                <p> email : <span><?= $fetch_accounts['email']; ?></span> </p>
                <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>"
                    onclick="return confirm('eliminar esta cuenta? La información relacionada con el usuario también se eliminará!')"
                    class="delete-btn">borrar</a>
            </div>
            <?php
            }
         } else {
            echo '<p class="empty">no hay cuentas disponibles!</p>';
         }
         ?>

        </div>

    </section>

    <script src="../js/admin_script.js"></script>

</body>

</html>