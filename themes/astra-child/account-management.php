<?php
/*
Template Name: Account Management
*/

$user_email = um_user('user_email');
$user_country = um_user('country');
$user_language = um_user('language');

get_header();
?>
<style>
    body {
        background-color: #F5F5F5 !important;
        margin: 0;
        min-height: 100vh;
    }

    .config-container {
        width: 1000px;
        margin: 40px auto;
        background: #FFFFFF;
        border-radius: 15px;
        box-shadow: 0px 4px 9px rgba(23, 26, 31, 0.11);
        display: flex;
        height: 600px;
        position: relative;
    }

    /* Sidebar Styles */
    .config-sidebar {
        width: 250px;
        border-right: 1px solid #E5E7EB;
        padding: 20px 0;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        padding: 12px 24px;
        cursor: pointer;
        color: #4B5563;
        font-size: 15px;
    }

    .sidebar-menu li:hover {
        background-color: #F3F4F6;
    }

    .sidebar-menu li.active {
        color: #8076EA;
        background-color: #F3F4F6;
    }

    /* Main Content Styles */
    .config-content {
        flex: 1;
        padding: 40px;
        position: relative;
        overflow-y: auto;
        height: 100%;
    }

    .config-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 90px;
        background: linear-gradient(to bottom, 
            rgba(255,255,255,1) 0%,
            rgba(255,255,255,1) 60%,
            rgba(255,255,255,0) 100%
        );
        pointer-events: none;
        z-index: 5;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        position: relative;
        z-index: 10;
    }

    .content-header h2 {
        font-size: 24px;
        color: #111827;
        margin: 0;
        font-weight: 500;
    }

    .close-button {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6B7280;
        outline: none;
        padding: 8px;
        margin: -8px;
        line-height: 1;
    }

    .form-group {
        margin-bottom: 8px;
        position: relative;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 8px;
    }

    .form-group label {
        display: block;
        margin-bottom: 2px;
        font-size: 14px;
    }

    .form-group input,
    .form-group .static-field {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        background: #F3F4F6;
    }

    .form-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        background: #F3F4F6;
        cursor: pointer;
    }

    .form-group select:focus {
        outline: none;
        border-color: #8076EA;
        box-shadow: 0 0 0 2px rgba(128, 118, 234, 0.1);
    }

    .static-field {
        color: #6B7280;
        cursor: not-allowed;
    }

    .section-title {
        font-size: 20px;
        color: #111827;
        font-weight: 500;
        margin-bottom: 12px;
    }

    .action-button {
        width: auto;
        min-width: 120px;
        height: 42px;
        padding: 0 30px;
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
        margin-top: 16px;
    }

    .action-button:hover {
        opacity: 0.8;
    }

    .action-button:hover:active {
        opacity: 0.7;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 8px;
    }

    .info-content {
        flex: 1;
    }
</style>

<div class="config-container">
    <!-- Sidebar -->
    <div class="config-sidebar">
        <ul class="sidebar-menu">
            <a href="configuration.php"><li>Editar perfil</li></a>
            <a href="account-management.php" class="active"><li>Administración de la cuenta</li></a>
            <a href="notifications.php"><li class="active" >Notificaciones</li></a>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="config-content">
        <div class="content-header">
            <h2>Administración de la cuenta</h2>
            <button class="close-button" aria-label="Cerrar">×</button>
        </div>

        <div class="section-title">Tu cuenta</div>

        <div class="info-row">
            <div class="form-group" style="flex: 1;">
                <label>Correo electrónico</label>
                <input type="email" readonly>
            </div>
            <button class="action-button">Cambiar</button>
        </div>

        <div class="info-row">
            <div class="form-group" style="flex: 1;">
                <label>Contraseña</label>
                <div class="static-field">••••••••</div>
            </div>
            <button class="action-button">Cambiar</button>
        </div>

        <div class="section-title" style="margin-top: 40px;">Información personal</div>

        <div class="info-row">
            <div class="form-group" style="flex: 0.79;">
                <label>País</label>
            <select>
                <option value="co" >Colombia</option>
                <option value="mx" >México</option>
                <option value="ar" >>Argentina</option>
                <option value="es" >>España</option>
                <option value="pe" >Perú</option>
                <option value="cl" >Chile</option>
                <option value="other">Otro país</option>
            </select>
            </div>
        </div>

        <div class="info-row">
            <div class="form-group" style="flex: 1;">
                <label>Idioma</label>
                <select>
                    <option value="es">Español</option>
                    <option value="en">English</option>
                </select>
            </div>
            <button class="action-button">Guardar</button>
        </div>
    </div>
</div>

<?php get_footer(); ?> 