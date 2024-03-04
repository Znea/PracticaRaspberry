<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>InsertarBus</title>
    </head>
    <body>       
        <a href="menu.html">Menú</a>
        <h1>NUEVO AUTOBÚS</h1>
        
        <form action="" method="POST">
            Matrícula: <input type="text" name="matricula"><br>
            Marca: <input type="text" name="marca"><br>
            NºPlazas: <input type="text" name="plazas"><br>
            <input type="submit" name="anyadir" value="Añadir">
        </form>
        
        <?php
            if (isset($_POST['anyadir'])){
                try {
                    $conex = new PDO("mysql:host=localhost;dbname=autobuses;charset=utf8mb4", "dwes", "abc123."); 
                    $result = $conex->exec("INSERT INTO autos VALUES ('$_POST[matricula]', '$_POST[marca]', $_POST[plazas])"); 
                } catch (Exception $ex) {
                    die("<hr>NO SE PUEDE INSERTAR PORQUE YA EXISTE ESTE AUTOBÚS"); 
                }
                
                if ($result > 0){
                    echo 'Insertado correctamente';
                }
            }
        ?>
        
    </body>
</html>
