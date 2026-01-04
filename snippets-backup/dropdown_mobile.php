// Off-Canvas Sidebar independiente con dropdown (versión corregida: evita duplicados y JS idempotente)
function mostrar_offcanvas_custom() {
    // Guard para evitar que se renderice más de una vez por petición
    static $kai_offcanvas_rendered = false;
    if ( $kai_offcanvas_rendered ) return '';
    $kai_offcanvas_rendered = true;

    ob_start();
	
	if( !is_user_logged_in() ) { ?>
    <div id="kai-offcanvas" class="kai-offcanvas" aria-hidden="true">
        <button id="kai-offcanvas-close" class="kai-btn-close" aria-label="Cerrar offcanvas">×</button>
        <ul class="kai-menu">
            <li><a href="/login">Iniciar sesión</a></li>
            <li><a href="/role-options">Opciones</a></li>
        </ul>

        <!-- Dropdown idioma exclusivo off-canvas -->
        <div class="kai-language-dropdown" aria-haspopup="true">
            <button class="kai-btn" id="kai-languageToggle" aria-expanded="false">Idioma</button>
            <ul class="kai-dropdown-menu" role="menu" aria-hidden="true">
                <li role="none"><a role="menuitem" href="/?glang=es">🇨🇴 Español</a></li>
                <li role="none"><a role="menuitem" href="/?glang=en">🇺🇸 English</a></li>
                <li role="none"><a role="menuitem" href="/?glang=pt">🇧🇷 Português</a></li>
            </ul>
        </div>
    </div>

    <button id="kai-offcanvas-open" class="kai-btn-open" aria-controls="kai-offcanvas">☰</button>
<?php } else {
		$user_id = get_current_user_id();
		$profile_url = um_user_profile_url( $user_id );
?>
	    <div id="kai-offcanvas" class="kai-offcanvas" aria-hidden="true">
        <button id="kai-offcanvas-close" class="kai-btn-close" aria-label="Cerrar offcanvas">×</button>
		<ul class="kai-menu">
			<li><a href="<?php echo esc_url( $profile_url ); ?>">Perfil</a></li>
			<li><a href="<?php echo esc_url( um_get_core_page( 'account' ) ); ?>">Ajustes</a></li>
			<li><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Cerrar sesión</a></li>
        </ul>

        <!-- Dropdown idioma exclusivo off-canvas -->
        <div class="kai-language-dropdown" aria-haspopup="true">
            <button class="kai-btn" id="kai-languageToggle" aria-expanded="false">Idioma</button>
            <ul class="kai-dropdown-menu" role="menu" aria-hidden="true">
                <li role="none"><a role="menuitem" href="/?glang=es">🇨🇴 Español</a></li>
                <li role="none"><a role="menuitem" href="/?glang=en">🇺🇸 English</a></li>
                <li role="none"><a role="menuitem" href="/?glang=pt">🇧🇷 Português</a></li>
            </ul>
        </div>
    </div>

    <button id="kai-offcanvas-open" class="kai-btn-open" aria-controls="kai-offcanvas">☰</button>
<?php } ?>

    <style>
        /* Solo se muestra en mobile (menor o igual a 992px) */
        #kai-offcanvas, #kai-offcanvas-open { display: none; }
        @media(max-width: 992px){
         #kai-offcanvas, #kai-offcanvas-open { display: block; }
        }
        /* Estilos off-canvas */
        #kai-offcanvas {
            position: fixed;
            top: 0;
            right: -320px;
            width: 300px;
            height: 100%;
            background: #fff;
            box-shadow: -4px 0 12px rgba(0,0,0,0.2);
            transition: right 0.28s ease;
            z-index: 2000;
            padding: 20px;
        }
        #kai-offcanvas.kai-open { right: 0; }

        .kai-btn-close { 
            position: absolute; top: 10px; right: 10px; font-size: 20px; background: none; border: none; cursor: pointer; color: black;
        }

        .kai-menu { list-style: none; padding: 0; margin: 0 0 12px 0; }
        .kai-menu li { margin: 10px 0; }
        .kai-menu li a { text-decoration: none; color: #333; }

        /* Dropdown exclusivo off-canvas */
        .kai-language-dropdown { position: relative; margin-top: 20px; }
        .kai-language-dropdown .kai-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            /* background: #333; */
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 140px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 2100;
        }
        .kai-language-dropdown.kai-open .kai-dropdown-menu { display: block; }
        .kai-dropdown-menu li { list-style: none; }
        .kai-dropdown-menu li a { display: block; padding: 8px 12px; color: #333; text-decoration: none; }
        .kai-dropdown-menu li a:hover { background: #f1f1f1; }
		.kai-btn, .kai-btn:focus { background-color: #D6C6FF; }

        #kai-offcanvas-open { margin: 10px; padding: 8px 12px; cursor: pointer; background-color: #D6C6FF;}
    </style>

    <script>
    (function(){
        // Idempotencia global: solo una inicialización
        if (window.__kaiOffInit) return;
        window.__kaiOffInit = true;

        document.addEventListener("DOMContentLoaded", function() {
            const offcanvas = document.getElementById('kai-offcanvas');
            const openBtn = document.getElementById('kai-offcanvas-open');
            const closeBtn = document.getElementById('kai-offcanvas-close');

            const dropdown = document.querySelector('.kai-language-dropdown');
            const toggle = document.getElementById('kai-languageToggle');
            const menu = dropdown ? dropdown.querySelector('.kai-dropdown-menu') : null;

            // Safety: si algún elemento falta, no romper
            if (openBtn && offcanvas && !openBtn.__kaiBound) {
                openBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    offcanvas.classList.add('kai-open');
                    offcanvas.setAttribute('aria-hidden','false');
                });
                openBtn.__kaiBound = true;
            }

            if (closeBtn && offcanvas && !closeBtn.__kaiBound) {
                closeBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    offcanvas.classList.remove('kai-open');
                    offcanvas.setAttribute('aria-hidden','true');
                });
                closeBtn.__kaiBound = true;
            }

            // Cerrar si clic fuera (solo una vez)
            if (!document.__kaiClickOutsideBound) {
                document.addEventListener('click', function(e){
                    // cerrar offcanvas si clic fuera del mismo y fuera del botón open
                    if (offcanvas && offcanvas.classList.contains('kai-open')) {
                        const target = e.target;
                        if (!offcanvas.contains(target) && (!openBtn || !openBtn.contains(target))) {
                            offcanvas.classList.remove('kai-open');
                            offcanvas.setAttribute('aria-hidden','true');
                        }
                    }
                    // cerrar dropdown si abierto y clic fuera
                    const openDd = document.querySelector('.kai-language-dropdown.kai-open');
                    if (openDd && !openDd.contains(e.target)) {
                        openDd.classList.remove('kai-open');
                        const t = openDd.querySelector('#kai-languageToggle');
                        if (t) t.setAttribute('aria-expanded','false');
                        const m = openDd.querySelector('.kai-dropdown-menu');
                        if (m) m.setAttribute('aria-hidden','true');
                    }
                });
                document.__kaiClickOutsideBound = true;
            }

            // Dropdown idempotente
            if (toggle && dropdown && !toggle.__kaiBound) {
                toggle.addEventListener('click', function(e){
                    e.preventDefault();
                    dropdown.classList.toggle('kai-open');
                    const isOpen = dropdown.classList.contains('kai-open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    if (menu) menu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                });
                toggle.__kaiBound = true;
            }

            // Escape para cerrar dropdown y offcanvas (añadir handler una sola vez)
            if (!document.__kaiEscBound) {
                document.addEventListener('keydown', function(e){
                    if (e.key === "Escape") {
                        // cerrar dropdown
                        const openDd = document.querySelector('.kai-language-dropdown.kai-open');
                        if (openDd) {
                            openDd.classList.remove('kai-open');
                            const t = openDd.querySelector('#kai-languageToggle');
                            if (t) t.setAttribute('aria-expanded','false');
                            const m = openDd.querySelector('.kai-dropdown-menu');
                            if (m) m.setAttribute('aria-hidden','true');
                        }
                        // cerrar offcanvas
                        if (offcanvas && offcanvas.classList.contains('kai-open')) {
                            offcanvas.classList.remove('kai-open');
                            offcanvas.setAttribute('aria-hidden','true');
                        }
                    }
                });
                document.__kaiEscBound = true;
            }
        });
    })();
    </script>

<?php
    return ob_get_clean();
}
add_shortcode('offcanvas_custom', 'mostrar_offcanvas_custom');
