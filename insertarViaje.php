<!DOCTYPE html>
<?php
    try {
        $conex = new PDO("mysql:host=localhost;dbname=autobuses;charset=utf8mb4", "dwes", "abc123."); 
        $result= $conex->query("SELECT * FROM autos"); 
    } catch (Exception $ex) {
        die($ex->getMessage()); 
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>InsertarViaje</title>
    </head>
    <body>       
        <a href="menu.html">Menú</a>
        <h1>NUEVO VIAJE</h1>
        
        <form action="" method="POST">
            Fecha: <input type="date" name="fecha"><br>
            Matricula: <select name="matricula">
                <?php
                while ($row = $result->fetchObject()){
                    echo "<option value= '$row->Matricula'>";
                    echo $row->Matricula; 
                    echo '</option>';
                }
                ?>
            </select><br>
            Origen: <input type="text" name="origen"><br>
            Destino: <input type="text" name="destino"><br>
            Plazas: <input type="text" name="plazas"><br>
            <input type="submit" name="anyadir" value="Añadir">
        </form>
        <?php
            if (isset($_POST['anyadir'])){
                try {
                    $resultado = $conex->query("SELECT Num_plazas FROM autos WHERE Matricula = '$_POST[matricula]'"); 
                    $plazas = $resultado->fetchObject(); 
                    if ($_POST['plazas'] <= $plazas->Num_plazas){
                        $result = $conex->exec("INSERT INTO viajes VALUES('$_POST[fecha]', '$_POST[matricula]', '$_POST[origen]', '$_POST[destino]', $_POST[plazas])"); 
                    }
                    else{
                        echo '<hr>EL NÚMERO DE PLAZAS INSERTADO ES MAYOR QUE EL NÚMERO DE PLAZAS DISPONIBLES';
                    }
                    
                } catch (Exception $ex) {
                    die($ex->getMessage()); 
                }
                
                if ($result > 0){
                    echo 'Insertado Correctamente';
                }
                
            }
        ?>
    </body>
</html>
