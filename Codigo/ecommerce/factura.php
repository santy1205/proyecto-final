<?php
$total = $_REQUEST['total'] ?? '';
include_once "stripe/init.php";
\Stripe\Stripe::setApiKey("sk_test_51HuNDQDYu3Lo8OqO6uPyxsDQ9skTazpflT5yaWOOh4clh35fRFqdHW4oYNhXTl49crDbiYZscYw9wEYzvREnPHl300zDG6bFiV");
$toke = $_POST['stripeToken'];
$charge = \Stripe\Charge::create([
    'amount' => $total,
    'currency' => 'usd',
    'description' => 'Pago de ecommerce',
    'source' => $toke
]);
if ($charge['captured']) {
    $queryVenta = "INSERT INTO ventas 
        (idCli                       ,idPago             ,fecha) values
        ('" . $_SESSION['idCliente'] . "','" . $charge['id'] . "',now());
        ";
    $resVenta = mysqli_query($con, $queryVenta);
    $id = mysqli_insert_id($con);
    /*
        if($resVenta){
            echo "La venta fue exitosa con el id=".$id;
        }
        */

?>
        <div class="row">
            <div class="col-6">
                <?php muestraRecibe($id); ?>
            </div>
            <div class="col-6">
                <?php muestraDetalle($id); ?>
            </div>
        </div>
    <?php
        borrarCarrito();
    
}
function borrarCarrito()
{
    ?>
    <script>
        $.ajax({
            type: "post",
            url: "ajax/borrarCarrito.php",
            dataType: "json",
            success: function(response) {
                $("#badgeProducto").text("");
                $("#listaCarrito").text("");
            }
        });
    </script>
<?php
}
function muestraRecibe($idVenta)
{
?>
    <table class="table">
        <thead>
            <tr>
                <th colspan="3" class="text-center">Compra realizada || Datos comprador</th>
            </tr>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Direccion</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $con;
            $queryRecibe = "SELECT nombre,email,direccion 
                from recibe 
                where idCli='" . $_SESSION['idCliente'] . "';";
            $resRecibe = mysqli_query($con, $queryRecibe);
            $row = mysqli_fetch_assoc($resRecibe);
            ?>
            <tr>
                <td><?php echo $row['nombre'] ?></td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['direccion'] ?></td>
            </tr>
        </tbody>
    </table>
    <a class="btn btn-secondary float-right" target="_blank" href="imprimirFactura.php?idVenta=<?php echo $idVenta; ?>" role="button">Imprimir factura <i class="fas fa-file-pdf"></i> </a>
<?php
}
function muestraDetalle($idVenta)
{
?>

<?php

}
?>