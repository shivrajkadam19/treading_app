<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"> <img alt="image" src="assets/img/logo.png" class="header-logo" /> <span
                    class="logo-name">Treading app</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <a href="index.php" class="nav-link"><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Courses</li>
            <li class="dropdown <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>">
                <a href="courses.php" class="nav-link"><span>Courses</span></a>
            </li>
            <li class="dropdown <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                <a href="profile.php" class="nav-link"><span>Profile</span></a>
            </li>
        </ul>
    </aside>
</div>

<script>
    $(document).ready(function() {
        const currentPage = window.location.pathname.split("/").pop();
        console.log(currentPage);

        $('.sidebar-menu a').each(function() {
            const menuItemHref = $(this).attr('href');

            if (menuItemHref === currentPage) {
                $(this).parent().addClass('active');
            }
        });
    });
</script>