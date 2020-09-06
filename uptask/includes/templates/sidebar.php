<?php 
    $proyectos = ObetenerProyectos();
    $proyectos->fetch_assoc();
?>
   <aside class="contenedor-proyectos">
        <div class="panel crear-proyecto">
            <a href="#" class="boton">Nuevo Proyecto <i class="fas fa-plus"></i> </a>
        </div>

        <div class="panel lista-proyectos">
            <h2>Proyectos</h2>
            <ul id="proyectos">
            <?php foreach($proyectos as $proyecto): ?>
            <li>
                <a href="index.php?id_proyecto=<?php echo $proyecto['id'] ;?>"><?php echo $proyecto['nombre']; ?></a>
            </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </aside>