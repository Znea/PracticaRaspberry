<!DOCTYPE html>
<?php
    $viajeModificado = false;
    $viajeBorrado = false; 
    $masPlazas = false;  
    $resultUpdate = 0; 
    
    try {
        $opciones = array(pdo::ATTR_ERRMODE, pdo::ERRMODE_EXCEPTION); 
        $conex = new PDO("mysql:host=localhost;dbname=autobuses;charset=utf8mb4", "dwes", "abc123.", $opciones); 
    } catch (PDOException $ex) {
        die($ex->getMessage()); 
    }
    
    if(isset($_POST['mod'])){
        try {
            $resultado = $conex->query("SELECT Num_plazas FROM autos WHERE Matricula = '$_POST[matricula]'"); 
            $plazas = $resultado->fetchObject(); 
            if ($_POST['plazas'] <= $plazas->Num_plazas){
                $resultUpdate= $conex->exec("UPDATE viajes SET Fecha='$_POST[fecha]', Origen='$_POST[origen]', Destino='$_POST[destino]', Plazas_libres=$_POST[plazas] WHERE Fecha = '$_POST[fecha2]' AND Matricula='$_POST[matricula2]' AND Origen = '$_POST[origen2]' AND Destino = '$_POST[destino2]'"); 
            } 
            else{
                $masPlazas = true;
            }
        } catch (PDOException $ex) {
        die($ex->getMessage()); 
        }

        if ($resultUpdate > 0){
            $viajeModificado = true;
        }
    }

    if(isset($_POST['borrar'])){
        try {
            $result= $conex->exec("DELETE FROM viajes WHERE Fecha = '$_POST[fecha]' AND Matricula='$_POST[matricula]' AND Origen = '$_POST[origen]' AND Destino = '$_POST[destino]'"); 
        } catch (PDOException $ex) {
            die($ex->getMessage()); 
        }

        if ($result > 0){
            $viajeBorrado = true;
        }
    }
    
    try {
        $result= $conex->query("SELECT * FROM viajes"); 
    } catch (PDOException $ex) {
        die($ex->getMessage()); 
    }
    ?>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Mod Y Borrar Viajes</title>
        </head>
        <body>       
            <a href="menu.html">Menú</a>
            <h1>MODIFICAR/BORRAR VIAJES</h1>

            <table border='1'>
                <tr>
                    <th>Fecha</th>
                    <th>Matrícula</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Plazas</th>
                    <th>Operación</th>
                </tr>
                <?php
                    while($row = $result->fetchObject()){
                        echo '<tr>';
                        echo "<td> $row->Fecha </td>";
                        echo "<td> $row->Matricula </td>";
                        echo "<td> $row->Origen </td>";
                        echo "<td> $row->Destino </td>";
                        echo "<td> $row->Plazas_libres </td>"; 
                        echo "<td> <form action='' method='POST'>";
                        echo "<input type='submit' name='modificar' value='Modificar'>";
                        echo "<input type='submit' name='borrar' value='Borrar'>"; 
                        echo "<input type='hidden' name='matricula' value='$row->Matricula'>";
                        echo "<input type='hidden' name='fecha' value='$row->Fecha'>";
                        echo "<input type='hidden' name='origen' value='$row->Origen'>";
                        echo "<input type='hidden' name='destino' value='$row->Destino'>";
                        echo "</form> </td>"; 
                        echo '</tr>';
                    }
                ?>
            </table>

            <?php 
                if(isset($_POST['modificar'])){
                    try {
                        $result= $conex->query("SELECT * FROM autos"); 
                        $result2= $conex->query("SELECT * FROM viajes WHERE Fecha = '$_POST[fecha]' AND Matricula='$_POST[matricula]' AND Origen = '$_POST[origen]' AND Destino = '$_POST[destino]'");
                        $row2 = $result2->fetchObject(); 
                    } catch (PDOException $ex) {
                        die($ex->getMessage()); 
                    }
            ?>
            
            <form action="" method="POST">
                <hr>
                Fecha: <input type="date" name="fecha" value="<?php echo $row2->Fecha;?>"><br>
                Matricula: <select name="matricula">
                    <?php
                    while ($row = $result->fetchObject()){
                        echo "<option value='$row->Matricula'";
                        if($row2->Matricula == $row->Matricula){
                            echo " selected "; 
                        }
                        echo "> $row->Matricula"; 
                        echo '</option>';
                    }
                    ?>
                </select><br>
                Origen: <input type="text" name="origen" value="<?php echo $row2->Origen;?>"><br>
                Destino: <input type="text" name="destino"  value="<?php echo $row2->Destino;?>"><br>
                Plazas: <input type="text" name="plazas"  value="<?php echo $row2->Plazas_libres;?>"><br>
                
                <input type="hidden" name="matricula2" value="<?php echo $_POST['matricula'];?>">
                <input type="hidden" name="fecha2" value="<?php echo $_POST['fecha'];?>">
                <input type="hidden" name="origen2" value="<?php echo $_POST['origen'];?>">
                <input type="hidden" name="destino2" value="<?php echo $_POST['destino'];?>">
                <input type="submit" name="mod" value="Modificar">
            </form>

            <?php
                }
                if ($viajeModificado) echo '<hr> VIAJE MODIFICADO CORRECTAMENTE'; 
                
                if($masPlazas) echo '<hr>EL NÚMERO DE PLAZAS INSERTADO ES MAYOR QUE EL NÚMERO DE PLAZAS DISPONIBLES'; 
                
                if ($viajeBorrado) echo '<hr> VIAJE BORRADO CORRECTAMENTE';
            ?>
        </body>
    </html>