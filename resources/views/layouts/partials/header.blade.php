
<header class="header">
   
    <div class="search-bar">
  
    </div>
    <button id="toggleSidebar" class="toggle-sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

      
 


    <div class="header-actions">
        {{ Auth::user()->name }}
        <div class="dropdown">
            <button class="dropdown-toggle" id="notificationsToggle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </button>
            <div class="dropdown-menu" id="notificationsMenu">
                <h3>Notificaciones</h3>
                <ul>
                    <li>Nuevo pedido recibido</li>
                    <li>Actualización del servidor completada</li>
                </ul>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropdown-toggle" id="profileToggle">
                <div class="avatar"></div>
            </button>
            <div class="dropdown-menu" id="profileMenu">
                <h3>Mi Cuenta</h3>
                <ul>
                  
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer; padding: 0; font: inherit;">
                                Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            
        </div>
    </div>
</header>