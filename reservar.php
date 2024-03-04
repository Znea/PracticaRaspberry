<!DOCTYPE html>
<?php

try {
    $conex = new PDO('mysql:host=localhost;dbname=autobuses;charset=utf8mb4', 'dwes', 'abc123.'); 
    $origen = $conex->query("SELECT DISTINCT Origen from viajes "); 
    $destino = $conex->query("SELECT DISTINCT Destino from viajes "); 
} catch (Exception $ex) {
    die($ex->getMessage()); 
}

 
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Reservar</title>
    </head>
    <body>   
        <a href="menu.html">Menú</a>
        <h1>RESERVAR</h1>
        <form action="" method="POST">
            Fecha: <input type="date" name="fecha"><br>
            Origen: <select name="origen">
            <?php
                while ($ori = $origen->fetchObject()){
                    echo "<option value='$ori->Origen'>";
                    echo $ori->Origen; 
                    echo "</option>"; 
                }
                ?>
            </select><br>
             Destino: <select name="destino">
                <?php
                while ($dest = $destino->fetchObject()){
                    echo "<option value='$dest->Destino'>";
                    echo $dest->Destino; 
                    echo "</option>"; 
                }
            ?>
            </select><br>
            <input type="submit" name="consultar" value="Consultar">
            
            <?php
                if (isset($_POST['consultar'])){
                    try {
                        $result = $conex->query("SELECT * FROM viajes WHERE Fecha= '$_POST[fecha]' AND Origen = '$_POST[origen]' AND Destino = '$_POST[destino]'"); 
                        $row = $result->fetchObject(); 
                    } catch (Exception $ex) {
                        die($ex->getMessage()); 
                    }
                    
                    if($result->rowCount() == 0){
                        echo "<hr> NO HAY NINGÚN VIAJE DESDE $_POST[origen] HASTA $_POST[destino] EN LA FECHA $_POST[fecha]";
                    }
                    else{
                        echo '<hr>';
                        echo "Fecha: $row->Fecha <br>"; 
                        echo "Origen: $row->Origen <br>"; 
                        echo "Destino: $row->Destino <br>"; 
                        echo "Plazas disponibles: $row->Plazas_libres <br>"; 
                        echo '<form action="" method="POST">';
                        echo "Nº de plazas a reservar: <input type='text' name='plazas'><br>"; 
                        echo "<input type='submit' name='reservar' value='Reservar'>"; 
                        echo "<input type='hidden' name='fecha' value='$_POST[fecha]'>";
                        echo "<input type='hidden' name='origen' value='$_POST[origen]'>";
                        echo "<input type='hidden' name='destino' value='$_POST[destino]'>";
                        echo "<input type='hidden' name='pLibres' value='$row->Plazas_libres'>";
                        echo '</form>';
                    }
                }
                
                if (isset($_POST['reservar'])){
                    try {
                        if ($_POST['plazas'] > $_POST['pLibres']){
                            echo '<hr>NO HAY PLAZAS SUFICIENTES';
                        }
                        else{
                            $result = $conex->exec("UPDATE viajes SET Plazas_libres = Plazas_libres - $_POST[plazas] WHERE Fecha= '$_POST[fecha]' AND Origen = '$_POST[origen]' AND Destino = '$_POST[destino]'");
                            echo "<hr>HA RESERVADO $_POST[plazas] PLAZAS PARA IR DESDE $_POST[origen] HASTA $_POST[destino] EL $_POST[fecha]";   
                        }                       
                    } catch (Exception $ex) {
                        die($ex->getMessage()); 
                    }
                }
            ?>
        </form>
    </body>
</html>    