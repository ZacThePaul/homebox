<header>

    <div class="logo-container">
        <!-- <img src="/public/images/homebox.png" alt="homebox logo" class="homebox-logo"> -->
        <i class="fa-solid fa-house-laptop"></i>
        <h4>HomeBox</h4>
    </div>
    <div class="menu-container" style="position: relative;">

        <ul>
            <?php if (!isset($_SESSION['user_email'])) : ?>
                <li class="menu-item">
                    <i class="fa-regular fa-money-check-pen"></i>
                    <a href="/usm/register" class="menu-item-anchor">Register</a>
                </li>
                <li class="menu-item">
                    <i class="fa-regular fa-arrow-right-to-bracket"></i>
                    <a href="/usm/login" class="menu-item-anchor">Login</a>
                </li>
            <?php 
                else: ?>
                <li class="menu-item">
                    <i class="fa-regular fa-house"></i>
                    <a href="/dashboard" class="menu-item-anchor">Home</a>
                </li>
                <li class="menu-item">
                    <i class="fa-regular fa-box-archive"></i>
                    <a href="/fss" class="menu-item-anchor">FSS</a>
                </li>
                <li class="menu-item">
                    <i class="fa-light fa-users"></i>
                    <a href="/usm" class="menu-item-anchor">USM</a>
                </li>
                <li class="menu-item">
                    <i class="fa-light fa-grid-2"></i>
                    <a href="/apps" class="menu-item-anchor">Apps</a>
                </li>
                <li class="menu-item">
                    <i class="fa-regular fa-arrow-right-from-bracket"></i>
                    <a href="/usm/user/logout" class="menu-item-anchor">Logout</a>
                </li>
            <?php endif; ?>
        </ul>

        <span id="close" style="color: blue; position: absolute; right: -40px;">close</span>
        
    </div>

    <script>

        const menuItems = document.getElementsByClassName('menu-item');
        const currentUrl = window.location.href;

        for (let item of menuItems) {
            let anchors = item.getElementsByClassName('menu-item-anchor');
            for (let anchor of anchors) {
                if (anchor.href == currentUrl) {
                    item.classList.add('menu-item-active');
                }
            }
        }

        const close = document.getElementById('close');
        const header = document.getElementsByTagName('header');

        close.addEventListener('click', function() {
            if (header[0].style.marginLeft == "-250px") {
                header[0].style.marginLeft = "0";
            }
            else {
                header[0].style.marginLeft = "-250px";
            }
        });

    </script>

    <?php if (false) : ?>
    <div id="podcast-popup" class="is_off">
        <div class="podcast-popup-container">
            <audio controls id="player-source">
                <source src="" type="audio/mpeg">
            </audio>
            <div class="player">

                <div class="player-playing-now-artwork">
                    <img src="https://assets.pippa.io/shows/61ba0ccb1a8cbea8343cf12c/show-cover.jpg" alt="">
                </div>

                <div class="player-controls">
                    <div id="player-rewind" class="player-rewind">
                        <i class="fa-regular fa-backward"></i>
                    </div>
                    <div id="player-play" class="is_paused" data-single="">
                        <i class="fa-solid fa-play"></i>
                        <i class="fa-solid fa-pause"></i>
                    </div>
                    <div id="player-forward" class="player-forward">
                        <i class="fa-regular fa-forward"></i>
                    </div>
                </div>

                <div style="width: 60px">.</div>

                <div class="player-time-container">
                    <span id="player-time-numer"></span> / <span id="player-time-denom"></span>
                </div>

            </div>
        </div>
        <?php get_scripts('apps/podcrack-mini.min.js') ?>
    </div>
    <?php endif; ?>
        
</header>