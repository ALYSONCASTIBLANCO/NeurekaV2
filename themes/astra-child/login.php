<?php
/*
Template Name: Login Page
*/

get_header();
?>
<style>
    body {
        background-color: #F5F5F5 !important;
        margin: 0;
        min-height: 100vh;
    }
    ::selection {
        background: #f3f4f6;
        color: inherit;
    }
    ::-moz-selection {
        background: #f3f4f6;
        color: inherit;
    }
    .page-content {
        position: relative;
        min-height: 100vh;
        padding: 40px 0;
    }
    .login-container {
        width: 380px;
        padding: 30px;
        background: #FFFFFF;
        border-radius: 30px;
        box-shadow: 0px 4px 9px rgba(23, 26, 31, 0.11), 0px 0px 2px rgba(23, 26, 31, 0.12);
        margin-left: calc(50% + 150px);
        transform: translateX(-50%);
    }
    .login-form {
        width: 100%;
    }
    .form-group {
        margin-bottom: 15px;
        position: relative;
    }
    .form-group input {
        width: 100%;
        height: 38px;
        padding-left: 16px;
        padding-right: 16px;
        font-family: 'Open Sans', sans-serif;
        font-size: 15px;
        line-height: 24px;
        font-weight: 400;
        background: #F3F4F6FF;
        border-radius: 12px;
        border-width: 0px;
        outline: none;
        box-sizing: border-box;
        transition: box-shadow 0.2s ease;
    }
    .form-group input:focus {
        box-shadow: 0 0 0 2px #C9C5F6;
        outline: none;
    }
    input {
        width: 100%;
        padding: 5px;
    }
    .password-toggle {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .password-toggle:focus {
        outline: none;
    }
    .password-toggle img {
        width: 20px;
        height: 20px;
        opacity: 0.6;
        transition: opacity 0.2s;
        filter: grayscale(100%) brightness(150%);
    }
    .password-toggle:hover img {
        opacity: 1;
    }
    .password-toggle::selection,
    .password-toggle *::selection {
        background: #f3f4f6;
        color: inherit;
    }
    .password-toggle::-moz-selection,
    .password-toggle *::-moz-selection {
        background: #f3f4f6;
        color: inherit;
    }
    .password-field-container {
        position: relative;
        -webkit-tap-highlight-color: transparent;
    }
    .login-options {
        margin: 15px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }
    .remember-me {
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
    }
    .remember-me input[type="checkbox"] {
        width: auto;
        margin: 0;
        accent-color: #8076EA;
    }
    .remember-me input[type="checkbox"]:focus {
        outline: none;
        box-shadow: 0 0 0 2px #C9C5F6;
    }
    .forgot-password {
        font-size: 13px;
    }
    .forgot-password a {
        color: #C9C5F6FF;
        text-decoration: none;
    }
    .forgot-password a:hover {
        text-decoration: underline;
    }
    .register-link {
        margin-top: 20px;
        text-align: center;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    .register-link p {
        margin: 0;
        color: #666;
    }
    .register-link a {
        color: #C9C5F6FF;
        text-decoration: none;
    }
    .register-link a:hover {
        text-decoration: underline;
    }
    button[type="submit"] {
        width: 100%;
        height: 42px;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Open Sans', sans-serif;
        font-size: 16px;
        line-height: 24px;
        font-weight: 400;
        color: #FFFFFF;
        background: #C9C5F6;
        opacity: 1;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        margin-top: 20px;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
    button[type="submit"]:hover {
        color: #FFFFFF;
        background: #A59EF0;
    }
    button[type="submit"]:hover:active {
        color: #FFFFFF;
        background: #8076EA;
    }
    button[type="submit"]:disabled {
        opacity: 0.4;
    }
    button[type="submit"]:focus {
        outline: none;
        box-shadow: 0 0 0 2px #C9C5F6;
    }
    h3 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
    }
</style>

<div class="page-content">
    <div class="login-container">
        <div class="login-form">
            <h3>¡Estas de vuelta!</h3>
            <form method="POST" action="" >
                <div class="form-group">
                    <label for="username">Apodo o Correo Electrónico</label>
                    <input type="text" id="username-emailaddress" name="user_login" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-field-container">
                        <input type="password" id="password" name="user_password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/hide.png" alt="Show password" class="eye-closed">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/view.png" alt="Hide password" class="eye-open" style="display: none;">
                        </button>
                    </div>
                </div>
                <div class="login-options">
                    <div class="remember-me">
                        <input type="checkbox" id="rememberme" name="rememberme">
                        <label for="rememberme">Recuérdame</label>
                    </div>
                    <div class="forgot-password">
                        <a href="https://genuinecreators.com/password-reset/">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
                <button type="submit" name="login_submit" >Iniciar sesión</button>
            </form>
            <div class="register-link">
                <p>¿No tienes cuenta?</p>
                <a href="https://genuinecreators.com/role-options/">Regístrate ahora</a>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['login_submit'])) {
    $login    = sanitize_text_field($_POST['user_login']); // puede ser username o correo
    $password = $_POST['user_password'];
 
    // Si es email, buscamos el user_login
    if (is_email($login)) {
        $user_obj = get_user_by('email', $login);
        if ($user_obj) {
            $login = $user_obj->user_login;
        }
    }
 
    $creds = array(
        'user_login'    => $login,
        'user_password' => $password,
        'remember'      => true,
    );
 
    $user = wp_signon($creds, false);
    $perfil_url = um_user_profile_url($user->ID);
    
    if (is_wp_error($user)) {
        echo '<p style="color:red;">Error: ' . $user->get_error_message() . '</p>';
    } else {
        do_action('um_after_login', $user->ID, $user->user_login);
        wp_redirect('/dashboard');
        exit;
    }
}
?>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.querySelector('.eye-open');
        const eyeClosed = document.querySelector('.eye-closed');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        } else {
            passwordInput.type = 'password';
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        }
    }
</script>
