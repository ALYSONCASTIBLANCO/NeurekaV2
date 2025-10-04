<?php
/*
Template Name: Configuration
*/



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

    .bottom-border {
        position: absolute;
        bottom: 85px;
        left: 0;
        right: 0;
        border-top: 1px solid #E5E7EB;
    }

    .bottom-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: space-between;
        padding: 20px 24px;
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

    /* Profile Picture Section */
    .profile-picture {
        width: 100px;
        height: 100px;
        background: #E0E7FF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #6366F1;
        margin-bottom: 20px;
    }

    .upload-button {
        background: #E0E7FF;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        color: #6366F1;
        cursor: pointer;
        font-size: 14px;
    }

    /* Form Styles */
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

    .static-field {
        color: #6B7280;
        cursor: not-allowed;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #8076EA;
        box-shadow: 0 0 0 2px rgba(128, 118, 234, 0.1);
    }

    .password-field {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle img {
        width: 20px;
        height: 20px;
        opacity: 0.6;
    }

    button[type="submit"] {
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
        margin-top: 30px;
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
        color: #FFFFFF;
        background: #A59EF0;
    }

    .action-button:hover:active {
        color: #FFFFFF;
        background: #8076EA;
    }

    .action-button:focus {
        outline: none;
        box-shadow: 0 0 0 2px #C9C5F6;
    }
</style>

<div class="config-container">
    <!-- Sidebar -->
    <div class="config-sidebar">
        <ul class="sidebar-menu">
            <a href="configuration.php" class="active"><li>Editar perfil</li></a>
            <a href="account-management.php"><li>Administración de la cuenta</li></a>
            <a href="notifications.php"><li>Notificaciones</li></a>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="config-content">
        <div class="content-header">
            <h2>Editar perfil</h2>
            <button class="close-button" aria-label="Cerrar">×</button>
        </div>

        <form>
            <!-- Profile Picture -->
            <div style="margin-bottom: 30px;">
                <label>Foto</label>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <div class="profile-picture" style="margin: 0;">
                        A
                    </div>
                    <div style="display: flex; align-items: center; height: 100px;">
                        <button type="button" class="action-button">Cambiar</button>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label>Nombre(s)</label>
                    <input type="text" value="" readonly>
                    <div style="margin-top: 5px; font-size: 12px; color: #666;">
                        Contacta a tu tutor para modificar este campo
                    </div>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Apellidos</label>
                    <input type="text" value="" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label>Racha</label>
                    <div class="static-field">16</div>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Apodo</label>
                    <input type="text" value="User">
                    <div style="margin-top: 5px; font-size: 12px; color: #666;">
                        Podrás cambiarlo nuevamente en 30 días
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 30px; padding: 20px 24px;">
                <button type="submit" class="action-button">Guardar</button>
            </div>

        </form>
    </div>
</div>

<?php get_footer(); ?>
