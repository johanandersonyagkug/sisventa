document.addEventListener('DOMContentLoaded', function() {
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.querySelector('.sidebar');
    const notificationsToggle = document.getElementById('notificationsToggle');
    const notificationsMenu = document.getElementById('notificationsMenu');
    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');

    toggleSidebar.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
    });

    function toggleDropdown(toggle, menu) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('active');
        });
    }

    toggleDropdown(notificationsToggle, notificationsMenu);
    toggleDropdown(profileToggle, profileMenu);

    document.addEventListener('click', function() {
        notificationsMenu.classList.remove('active');
        profileMenu.classList.remove('active');
    });
});

