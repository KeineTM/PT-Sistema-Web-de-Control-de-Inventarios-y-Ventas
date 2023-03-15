<?php
    # Esta clase gestiona todas las actividades que se relacionen con los usuarios del sistema
    class ctrlUsuarios {

        # Este método llama a la función que consulta los datos del usuario en la BD
        static public function ctrValidarUsuario($usuarioID) {
            return mdlUsuarios::mdlValidarUsuario($usuarioID);
        }
    }
?>