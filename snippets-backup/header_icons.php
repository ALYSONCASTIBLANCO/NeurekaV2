
// Mostrar íconos solo si el usuario no está logueado
function mostrar_iconos_login_registro() {
    ob_start();
    if ( !is_user_logged_in() ) { ?>
        
        <div class="custom-header-icons">
            <!-- Botón Iniciar sesión -->
            <a href="/login" class="btn-header">
                <img src="https://genuinecreators.com/wp-content/uploads/2025/09/cropped-Avionderecha.png" 
                     alt="Icono izquierda" style="height: 18px;">
                <span>Iniciar sesión</span>
                <img src="https://genuinecreators.com/wp-content/uploads/2025/09/Avion-lateral-izquierda-png.png" 
                     alt="Icono derecha" style="height: 18px;">
            </a>

            <!-- Dropdown de idioma -->
            <div class="language-dropdown">
                <button class="btn-header" id="languageToggle">
                    Idioma
                </button>
                <ul class="dropdown-menu">
                    <li><a href="/?glang=es">🇨🇴 Español</a></li>
                    <li><a href="/?glang=en">🇺🇸 English</a></li>
                    <li><a href="/?glang=pt">🇧🇷 Português</a></li>
                </ul>
            </div>
        </div>

   <?php } else { 

    $user_id = get_current_user_id();

    $profile_url = um_user_profile_url( $user_id );

?>
<div class="language-dropdown">
<button class="btn-header" id="languageToggle">

        Account
</button>
<ul class="dropdown-menu">
<li><a href="<?php echo esc_url( $profile_url ); ?>">Perfil</a></li>
<li><a href="<?php echo esc_url( um_get_core_page( 'account' ) ); ?>">Ajustes</a></li>
<li><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Cerrar sesión</a></li>
</ul>
</div>
</div>
<?php } ?>

 

  <script>
document.addEventListener("DOMContentLoaded", function() {
	const dropdown = document.querySelector(".language-dropdown");
	const toggle = document.getElementById("languageToggle");
 	if (toggle) {
		toggle.addEventListener("click", function(e) {
			e.preventDefault();
			dropdown.classList.toggle("open");
		});
	}
});
</script>

 

    <?php
    return ob_get_clean();
}
add_shortcode('iconos_sesion', 'mostrar_iconos_login_registro');
