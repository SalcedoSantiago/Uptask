<script src="js/sweetalert2.all.min.js"></script>
<?php   $pagina = obtenerPaginaActual();
        if($pagina === 'login' || $pagina === 'crear-cuenta'){
            echo '<script src="js/formulario.js"></script>';
        }else {
            echo '<script src="js/main.js"></script>';
        }
?>

</body>

</html>