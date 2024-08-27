<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">

        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li>
            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn d-lg-none">
                <i data-feather="align-justify"></i>
            </a>
        </li>
        <li>
            <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
            </a>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="assets/img/user.png"
                    class="user-img-radious-style">
                <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title"><?= htmlspecialchars($_SESSION['username']) ?></div>
                <a href="./profile.php" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile
                </a>
                <!-- <a href="#" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a> -->
                <div class="dropdown-divider"></div>
                <a href="./auth-logout.php" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>