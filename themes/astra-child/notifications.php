<?php
/*
Template Name: Notifications
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
        position: fixed;
        top: 40px;
        right: calc((100% - 1000px) / 2 + 40px);
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6B7280;
        outline: none;
        padding: 8px;
        margin: -8px;
        line-height: 1;
        z-index: 10;
    }
</style>

<div class="config-container">
    <!-- Sidebar -->
    <div class="config-sidebar">
        <ul class="sidebar-menu">
            <a href="configuration.php"><li>Editar perfil</li></a>
            <a href="account-management.php"><li>Administración de la cuenta</li></a>
            <a href="notifications.php"><li class="active" >Notificaciones</li></a>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="config-content">
        <div class="content-header">
            <h2>Notificaciones</h2>
            <button class="close-button" aria-label="Cerrar">×</button>
        </div>

        <!-- Notifications content will go here -->
        <div style="color: #666;">
            Configuración de notificaciones próximamente...
        </div>
    </div>
</div>

<?php get_footer(); ?> 