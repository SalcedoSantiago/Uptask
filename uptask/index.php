<?php
include 'includes/funciones/sessiones.php';
include 'includes/funciones/funciones.php';
include 'includes/templates/header.php';
include 'includes/templates/barra.php';

//// 

if (isset($_GET['id_proyecto'])) {
    $id_proyecto =  $_GET['id_proyecto'];
}
?>



<div class="contenedor">
    <?php include 'includes/templates/sidebar.php'; ?>

    <main class="contenido-principal">
        <h1>
            <?php $names = obetenerProyecto($id_proyecto);
            if ($names) {
                foreach ($names as $name) {  ?>
                    <span>
                        <?php echo $name['nombre']; ?>
                    </span>
                <?php  }
            } else { ?>
                <span>Selecciona un proyecto</span>
            <?php } ?>
        </h1>

        <form action="#" class="agregar-tarea" id="agregar-tarea">
            <div class="campo">
                <label for="tarea">Tarea:</label>
                <input type="text" id="tarea" name="tarea" placeholder="Nombre Tarea" class="nombre-tarea">
            </div>
            <div class="campo enviar">
                <input type="hidden" id="id_proyecto" value="<?php echo $id_proyecto ?>">
                <input type="submit" class="boton nueva-tarea" value="Agregar">
            </div>
        </form>

        <h2>Listado de tareas:</h2>

        <div class="listado-pendientes">
            <?php $tareas = obtenerTareas($id_proyecto);
            $tareas->fetch_array();
            ?>
            <ul id="lista-tareas">
                <?php
                if ($tareas->lengths != null) {
                    foreach ($tareas as $tarea) : ?>
                        <li id="tarea:<?php echo $tarea['id']; ?>" class="tarea">
                            <p><?php echo $tarea['tarea']; ?></p>
                            <div class="acciones">
                                <i class="far fa-check-circle <?php echo ($tarea['estado'] == 1) ? 'completo' : '' ?>"></i>
                                <i class="fas fa-trash"></i>
                            </div>
                        </li>
                <?php endforeach;
                } else {
                    echo '<p class="null-tareas">No hay tareas pendientes</p>';
                }
                ?>
            </ul>

        </div>
        <h2>Barra de pogreso</h2>
        <div class="contenedor-progreso">
            <div class="progreso"></div>
        </div>
    </main>
</div>
<!--.contenedor-->

<?php include_once 'includes/templates/footer.php'; ?>