<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['actualizar_estado'])) {
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['estado'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `pedidos` SET estado = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'estado actualizado!';
}

if (isset($_GET['borrar'])) {
   $delete_id = $_GET['borrar'];
   $delete_order = $conn->prepare("DELETE FROM `pedidos` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pedidos realizados</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <section class="orders">

        <h1 class="heading">pedidos realizados</h1>

        <div class="box-container">

            <?php
         $select_orders = $conn->prepare("SELECT * FROM `pedidos`");
         $select_orders->execute();
         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
         ?>
            <div class="box">
                <p> fecha de pedido : <span><?= $fetch_orders['fecha_pedido']; ?></span> </p>
                <p> nombre : <span><?= $fetch_orders['nombre']; ?></span> </p>
                <p> número : <span><?= $fetch_orders['numero']; ?></span> </p>
                <p> dirección : <span><?= $fetch_orders['direccion']; ?></span> </p>
                <p> productos : <span><?= $fetch_orders['productos']; ?></span> </p>
                <p> precio total : <span>Bs.<?= $fetch_orders['precio_total']; ?>/-</span> </p>
                <p> método de pago : <span><?= $fetch_orders['metodo']; ?></span> </p>
                <form action="" method="post">
                    <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                    <select name="estado" class="select">
                        <option selected disabled><?= $fetch_orders['estado']; ?></option>
                        <option value="pendiente">pendiente</option>
                        <option value="completado">completado </option>
                    </select>
                    <div class="flex-btn">
                        <input type="submit" value="actualizar" class="option-btn" name="actualizar_estado">
                        <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn"
                            onclick="return confirm('borrar este pedido?');">borrar</a>
                    </div>
                </form>
            </div>
            <?php
            }
         } else {
            echo '<p class="empty">no se han realizado pedidos</p>';
         }
         ?>

        </div>

    </section>

    </section>












    <script src="../js/admin_script.js"></script>

</body>

</html>