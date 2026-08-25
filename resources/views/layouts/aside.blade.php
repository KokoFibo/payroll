```blade
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-elegant" id="mainSidebar">

    <!-- Brand Logo -->
    <a href="/" class="brand-link sidebar-brand">

        <div class="brand-logo-wrapper">
            <img src="{{ asset('images/logo-only.png') }}" alt="Yifang Logo" class="brand-logo-img">
        </div>

        <span class="brand-text">Yifang</span>

        <span class="brand-status"></span>

    </a>


    <!-- Edge Toggle Button -->
    <button type="button" class="sidebar-edge-toggle" id="sidebarEdgeToggle" aria-label="Minimize/maximize sidebar"
        title="Minimize/maximize sidebar">

        <i class="fas fa-chevron-left" id="sidebarEdgeToggleIcon"></i>

    </button>


    <!-- Sidebar -->
    <div class="sidebar">

        <!-- User Panel -->
        <div class="user-panel sidebar-user-panel">

            <div class="user-avatar">

                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&length=1"
                    alt="User Image">

            </div>

            <div class="user-info">

                <span class="user-label">
                    SIGNED IN AS
                </span>

                <a href="#" class="user-name">
                    {{ namaDiAside(Auth::user()->name) }}
                </a>

            </div>

            <div class="user-online"></div>

        </div>


        <!-- Navigation Menu -->
        @include('layouts.menu')


    </div>
    <!-- /.sidebar -->

</aside>


<!-- Mobile Overlay -->
<div class="sidebar-mobile-overlay" id="sidebarMobileOverlay">
</div>


<style>
    /* =========================================================
   YIFANG SIDEBAR
   Elegant / Professional / Responsive
   ========================================================= */

    :root {

        --sb-bg: #000000;
        --sb-bg-soft: #0b0b0d;
        --sb-bg-card: #111114;

        --sb-border: rgba(255, 255, 255, 0.07);

        --sb-text: rgba(255, 255, 255, 0.86);
        --sb-text-muted: rgba(255, 255, 255, 0.45);

        --sb-hover: rgba(255, 255, 255, 0.055);

        --sb-active: rgba(230, 179, 37, 0.10);

        --sb-accent: #e6b325;
        --sb-accent-soft: rgba(230, 179, 37, 0.18);

        --sb-width: 250px;
        --sb-width-mini: 4.6rem;

        --sb-radius: 11px;

        --sb-transition:
            width .25s ease,
            transform .25s ease,
            opacity .2s ease,
            background-color .2s ease;
    }


    /* =========================================================
   SIDEBAR SHELL
   ========================================================= */

    .sidebar-elegant {

        background:
            linear-gradient(180deg,
                var(--sb-bg) 0%,
                var(--sb-bg-soft) 100%) !important;

        width:
            var(--sb-width);

        border-right:
            1px solid var(--sb-border);

        box-shadow:
            8px 0 30px rgba(0, 0, 0, .28);

        transition:
            var(--sb-transition);

        overflow:
            visible;

        z-index:
            1045;
    }


    /* =========================================================
   SIDEBAR CONTENT
   ========================================================= */

    .sidebar-elegant .sidebar {

        height:
            calc(100% - 4.5rem);

        overflow-y:
            auto;

        overflow-x:
            hidden;

        padding:
            4px 0 18px;

        scrollbar-width:
            thin;

        scrollbar-color:
            rgba(255, 255, 255, .15) transparent;
    }


    /* Scrollbar Chrome / Edge */

    .sidebar-elegant .sidebar::-webkit-scrollbar {

        width:
            5px;
    }


    .sidebar-elegant .sidebar::-webkit-scrollbar-track {

        background:
            transparent;
    }


    .sidebar-elegant .sidebar::-webkit-scrollbar-thumb {

        background:
            rgba(255, 255, 255, .14);

        border-radius:
            20px;
    }


    .sidebar-elegant .sidebar::-webkit-scrollbar-thumb:hover {

        background:
            rgba(230, 179, 37, .45);
    }


    /* =========================================================
   BRAND
   ========================================================= */

    .sidebar-elegant .brand-link {

        height:
            72px;

        display:
            flex;

        align-items:
            center;

        gap:
            12px;

        padding:
            0 18px;

        border-bottom:
            1px solid var(--sb-border);

        background:
            rgba(255, 255, 255, .012);

        white-space:
            nowrap;

        position:
            relative;

        transition:
            background-color .2s ease,
            padding .25s ease;
    }


    .sidebar-elegant .brand-link:hover {

        background:
            rgba(255, 255, 255, .025);

        text-decoration:
            none;
    }


    /* Logo wrapper */

    .brand-logo-wrapper {

        width:
            38px;

        height:
            38px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        flex-shrink:
            0;

        border-radius:
            10px;

        background:
            linear-gradient(145deg,
                rgba(230, 179, 37, .14),
                rgba(255, 255, 255, .025));

        border:
            1px solid rgba(230, 179, 37, .12);

        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, .04);
    }


    /* Logo */

    .sidebar-elegant .brand-logo-img {

        width:
            29px;

        height:
            29px;

        object-fit:
            contain;

        opacity:
            .95;

        filter:
            drop-shadow(0 3px 7px rgba(0, 0, 0, .5));
    }


    /* Brand text */

    .sidebar-elegant .brand-text {

        color:
            #fff;

        font-size:
            1.05rem;

        font-weight:
            600;

        letter-spacing:
            .055em;

        line-height:
            1;

        transition:
            opacity .18s ease,
            transform .18s ease;
    }


    /* Gold status */

    .brand-status {

        width:
            6px;

        height:
            6px;

        margin-left:
            auto;

        border-radius:
            50%;

        background:
            var(--sb-accent);

        box-shadow:
            0 0 0 4px var(--sb-accent-soft),
            0 0 10px rgba(230, 179, 37, .4);
    }


    /* =========================================================
   USER PANEL
   ========================================================= */

    .sidebar-elegant .user-panel {

        margin:
            14px 12px 10px !important;

        padding:
            12px 11px !important;

        min-height:
            66px;

        display:
            flex;

        align-items:
            center;

        position:
            relative;

        border:
            1px solid var(--sb-border);

        border-radius:
            12px;

        background:
            linear-gradient(145deg,
                rgba(255, 255, 255, .045),
                rgba(255, 255, 255, .015));

        transition:
            background-color .2s ease,
            border-color .2s ease;
    }


    .sidebar-elegant .user-panel:hover {

        background:
            linear-gradient(145deg,
                rgba(255, 255, 255, .06),
                rgba(255, 255, 255, .018));

        border-color:
            rgba(230, 179, 37, .16);
    }


    /* Avatar */

    .user-avatar {

        width:
            38px;

        height:
            38px;

        flex-shrink:
            0;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        border-radius:
            50%;

        background:
            var(--sb-bg-card);

        box-shadow:
            0 0 0 2px rgba(255, 255, 255, .08),
            0 0 0 4px rgba(230, 179, 37, .06);

        overflow:
            hidden;
    }


    .user-avatar img {

        width:
            100%;

        height:
            100%;

        object-fit:
            cover;

        border-radius:
            50%;
    }


    /* User information */

    .user-info {

        min-width:
            0;

        margin-left:
            11px;

        display:
            flex;

        flex-direction:
            column;

        overflow:
            hidden;
    }


    .user-label {

        color:
            var(--sb-text-muted);

        font-size:
            .57rem;

        font-weight:
            600;

        letter-spacing:
            .12em;

        line-height:
            1.2;

        margin-bottom:
            4px;
    }


    .sidebar-elegant .user-name {

        color:
            var(--sb-text) !important;

        font-size:
            .86rem;

        font-weight:
            500;

        line-height:
            1.2;

        white-space:
            nowrap;

        overflow:
            hidden;

        text-overflow:
            ellipsis;

        max-width:
            145px;
    }


    .sidebar-elegant .user-name:hover {

        color:
            #fff !important;

        text-decoration:
            none;
    }


    /* Online */

    .user-online {

        width:
            7px;

        height:
            7px;

        border-radius:
            50%;

        background:
            #42d392;

        box-shadow:
            0 0 0 3px rgba(66, 211, 146, .10);

        margin-left:
            auto;

        flex-shrink:
            0;
    }


    /* =========================================================
   NAVIGATION
   ========================================================= */

    .sidebar-elegant .nav-sidebar {

        padding-top:
            4px;
    }


    .sidebar-elegant .nav-sidebar>.nav-item {

        margin:
            3px 10px;
    }


    .sidebar-elegant .nav-sidebar .nav-treeview>.nav-item {

        margin:
            2px 5px 2px 17px;
    }


    /* Nav link */

    .sidebar-elegant .nav-link {

        min-height:
            42px;

        display:
            flex;

        align-items:
            center;

        border-radius:
            var(--sb-radius) !important;

        padding:
            .55rem .75rem !important;

        color:
            var(--sb-text) !important;

        font-size:
            .88rem;

        font-weight:
            400;

        position:
            relative;

        transition:
            background-color .18s ease,
            color .18s ease,
            transform .18s ease;
    }


    /* Hover */

    .sidebar-elegant .nav-link:hover {

        background:
            var(--sb-hover) !important;

        color:
            #fff !important;

        transform:
            translateX(2px);
    }


    /* Icon */

    .sidebar-elegant .nav-link .nav-icon {

        width:
            21px;

        min-width:
            21px;

        margin:
            0 11px 0 1px;

        text-align:
            center;

        font-size:
            .93rem;

        color:
            var(--sb-text-muted);

        transition:
            color .18s ease,
            transform .18s ease;
    }


    .sidebar-elegant .nav-link:hover .nav-icon {

        color:
            var(--sb-accent);

        transform:
            scale(1.04);
    }


    /* Menu text */

    .sidebar-elegant .nav-link p {

        margin:
            0;

        white-space:
            nowrap;

        overflow:
            hidden;

        text-overflow:
            ellipsis;

        line-height:
            1.3;
    }


    /* =========================================================
   ACTIVE MENU
   ========================================================= */

    .sidebar-elegant .nav-item>.nav-link.bg-secondary,
    .sidebar-elegant .nav-item.bg-secondary>.nav-link {

        background:
            var(--sb-active) !important;

        color:
            #fff !important;

        font-weight:
            500;

        box-shadow:
            inset 0 0 0 1px rgba(230, 179, 37, .06);
    }


    /* Gold indicator */

    .sidebar-elegant .nav-item>.nav-link.bg-secondary::before,
    .sidebar-elegant .nav-item.bg-secondary>.nav-link::before {

        content:
            "";

        position:
            absolute;

        left:
            -10px;

        top:
            9px;

        bottom:
            9px;

        width:
            3px;

        border-radius:
            0 4px 4px 0;

        background:
            var(--sb-accent);

        box-shadow:
            0 0 8px rgba(230, 179, 37, .3);
    }


    .sidebar-elegant .nav-item>.nav-link.bg-secondary .nav-icon,
    .sidebar-elegant .nav-item.bg-secondary>.nav-link .nav-icon {

        color:
            var(--sb-accent);
    }


    /* =========================================================
   SUBMENU
   ========================================================= */

    .sidebar-elegant .nav-link .right {

        margin-left:
            auto;

        font-size:
            .68rem;

        color:
            var(--sb-text-muted);

        transition:
            transform .22s ease,
            color .18s ease;
    }


    .sidebar-elegant .menu-open>.nav-link .right {

        transform:
            rotate(-90deg);

        color:
            var(--sb-accent);
    }


    .sidebar-elegant .nav-treeview {

        margin:
            3px 0 5px;

        padding:
            3px 0;

        background:
            rgba(255, 255, 255, .018);

        border-left:
            1px solid rgba(255, 255, 255, .055);

        border-radius:
            0 9px 9px 0;
    }


    /* Submenu links */

    .sidebar-elegant .nav-treeview .nav-link {

        min-height:
            38px;

        font-size:
            .83rem;

        color:
            rgba(255, 255, 255, .66) !important;

        padding:
            .48rem .65rem !important;
    }


    .sidebar-elegant .nav-treeview .nav-link:hover {

        color:
            #fff !important;

        background:
            rgba(255, 255, 255, .045) !important;
    }


    /* =========================================================
   BADGES
   ========================================================= */

    .sidebar-elegant .badge {

        border-radius:
            6px;

        padding:
            .25em .5em;

        font-size:
            .68rem;

        box-shadow:
            0 0 0 2px rgba(0, 0, 0, .35);
    }


    /* =========================================================
   EDGE TOGGLE BUTTON
   ========================================================= */

    .sidebar-edge-toggle {

        position:
            absolute;

        top:
            84px;

        /*
     * Hanya sedikit keluar dari sidebar
     * supaya tidak terpotong content-wrapper.
     */
        right:
            -10px;

        width:
            30px;

        height:
            30px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        padding:
            0;

        border:
            1px solid rgba(230, 179, 37, .65);

        border-radius:
            50%;

        background:
            #151518;

        color:
            var(--sb-accent);

        cursor:
            pointer;

        /*
     * Di atas AdminLTE content.
     */
        z-index:
            9999 !important;

        box-shadow:
            0 4px 15px rgba(0, 0, 0, .65),
            0 0 0 3px rgba(0, 0, 0, .30);

        transition:
            transform .2s ease,
            background-color .2s ease,
            color .2s ease,
            border-color .2s ease;
    }


    .sidebar-edge-toggle:hover {

        background:
            var(--sb-accent);

        color:
            #111;

        border-color:
            var(--sb-accent);

        transform:
            scale(1.08);
    }


    .sidebar-edge-toggle:active {

        transform:
            scale(.94);
    }


    .sidebar-edge-toggle i {

        font-size:
            .72rem;

        transition:
            transform .25s ease;
    }


    /* =========================================================
   DESKTOP COLLAPSED
   ========================================================= */

    body.sidebar-collapse .sidebar-elegant {

        width:
            var(--sb-width-mini);

        z-index:
            1055 !important;
    }


    /* Hide content when minimized */

    body.sidebar-collapse .sidebar-elegant:not(:hover) .brand-text,

    body.sidebar-collapse .sidebar-elegant:not(:hover) .brand-status,

    body.sidebar-collapse .sidebar-elegant:not(:hover) .user-info,

    body.sidebar-collapse .sidebar-elegant:not(:hover) .nav-link p {

        display:
            none !important;
    }


    /* Center brand */

    body.sidebar-collapse .sidebar-elegant:not(:hover) .brand-link {

        justify-content:
            center;

        padding:
            0 .5rem;
    }


    body.sidebar-collapse .sidebar-elegant:not(:hover) .brand-logo-wrapper {

        width:
            36px;

        height:
            36px;
    }


    /* User */

    body.sidebar-collapse .sidebar-elegant:not(:hover) .user-panel {

        justify-content:
            center;

        margin:
            14px 8px 10px !important;

        padding:
            10px 5px !important;

        background:
            transparent;

        border:
            0;
    }


    body.sidebar-collapse .sidebar-elegant:not(:hover) .user-avatar {

        width:
            36px;

        height:
            36px;
    }


    body.sidebar-collapse .sidebar-elegant:not(:hover) .user-online {

        position:
            absolute;

        right:
            7px;

        bottom:
            8px;
    }


    /* Navigation */

    body.sidebar-collapse .sidebar-elegant:not(:hover) .nav-sidebar>.nav-item {

        margin:
            3px 8px;
    }


    body.sidebar-collapse .sidebar-elegant:not(:hover) .nav-link {

        justify-content:
            center;

        padding:
            .62rem !important;
    }


    body.sidebar-collapse .sidebar-elegant:not(:hover) .nav-link:hover {

        transform:
            none;

        padding-left:
            .62rem !important;
    }


    body.sidebar-collapse .sidebar-elegant:not(:hover) .nav-link .nav-icon {

        margin:
            0;

        font-size:
            1rem;
    }


    /* Hide submenu while minimized */

    body.sidebar-collapse .sidebar-elegant:not(:hover) .nav-treeview,

    body.sidebar-collapse .sidebar-elegant:not(:hover) .nav-link .right {

        display:
            none !important;
    }


    /* Rotate toggle */

    body.sidebar-collapse #sidebarEdgeToggleIcon {

        transform:
            rotate(180deg);
    }


    /* =========================================================
   COLLAPSED + HOVER
   ========================================================= */

    body.sidebar-collapse .sidebar-elegant:hover {

        position:
            fixed;

        top:
            0;

        left:
            0;

        width:
            var(--sb-width);

        height:
            100vh;

        z-index:
            1055 !important;

        box-shadow:
            12px 0 38px rgba(0, 0, 0, .62);

        overflow:
            visible;
    }


    /*
 * IMPORTANT:
 * Override AdminLTE's sidebar-collapse rules.
 * Everything becomes visible again on hover.
 */


    /* Brand */

    body.sidebar-collapse .sidebar-elegant:hover .brand-text {

        display:
            block !important;

        opacity:
            1 !important;

        visibility:
            visible !important;
    }


    body.sidebar-collapse .sidebar-elegant:hover .brand-status {

        display:
            block !important;
    }


    /* User */

    body.sidebar-collapse .sidebar-elegant:hover .user-info {

        display:
            flex !important;

        opacity:
            1 !important;

        visibility:
            visible !important;
    }


    body.sidebar-collapse .sidebar-elegant:hover .user-online {

        display:
            block !important;
    }


    /* Menu text */

    body.sidebar-collapse .sidebar-elegant:hover .nav-link p {

        display:
            block !important;

        width:
            auto !important;

        max-width:
            none !important;

        opacity:
            1 !important;

        visibility:
            visible !important;

        overflow:
            visible !important;

        white-space:
            nowrap !important;
    }


    /* Menu */

    body.sidebar-collapse .sidebar-elegant:hover .nav-link {

        justify-content:
            flex-start !important;

        padding:
            .55rem .75rem !important;
    }


    /* Icons */

    body.sidebar-collapse .sidebar-elegant:hover .nav-link .nav-icon {

        margin:
            0 11px 0 1px !important;
    }


    /* Submenu */

    body.sidebar-collapse .sidebar-elegant:hover .nav-treeview {

        display:
            block !important;
    }


    body.sidebar-collapse .sidebar-elegant:hover .nav-link .right {

        display:
            block !important;
    }


    /* =========================================================
   ADMINLTE OVERRIDE
   ========================================================= */

    /*
 * These rules have intentionally high specificity
 * so AdminLTE cannot hide the menu text when
 * the sidebar is hovered.
 */

    body.sidebar-collapse .sidebar-elegant:hover .nav-sidebar .nav-item .nav-link p {

        display:
            block !important;

        visibility:
            visible !important;

        opacity:
            1 !important;

        width:
            auto !important;

        max-width:
            none !important;

        overflow:
            visible !important;

        white-space:
            nowrap !important;
    }


    body.sidebar-collapse .sidebar-elegant:hover .nav-sidebar .nav-treeview {

        display:
            block !important;
    }


    body.sidebar-collapse .sidebar-elegant:hover .user-panel .info {

        display:
            flex !important;
    }


    /* =========================================================
   MOBILE
   ========================================================= */

    @media (max-width: 991.98px) {

        .sidebar-elegant {

            position:
                fixed;

            top:
                0;

            left:
                0;

            width:
                280px;

            height:
                100vh;

            transform:
                translateX(-100%);

            z-index:
                1040 !important;

            box-shadow:
                12px 0 35px rgba(0, 0, 0, .55);
        }


        body.sidebar-mobile-open .sidebar-elegant {

            transform:
                translateX(0);
        }


        body.sidebar-collapse .sidebar-elegant {

            width:
                280px;
        }


        /* Always expanded on mobile */

        body.sidebar-collapse .sidebar-elegant .brand-text,

        body.sidebar-collapse .sidebar-elegant .brand-status,

        body.sidebar-collapse .sidebar-elegant .user-info,

        body.sidebar-collapse .sidebar-elegant .nav-link p {

            display:
                block !important;
        }


        /* Brand */

        body.sidebar-collapse .sidebar-elegant .brand-link {

            justify-content:
                flex-start;

            padding:
                0 18px;
        }


        /* User */

        body.sidebar-collapse .sidebar-elegant .user-panel {

            justify-content:
                flex-start;

            margin:
                14px 12px 10px !important;

            padding:
                12px 11px !important;

            background:
                linear-gradient(145deg,
                    rgba(255, 255, 255, .045),
                    rgba(255, 255, 255, .015));

            border:
                1px solid var(--sb-border);
        }


        /* Navigation */

        body.sidebar-collapse .sidebar-elegant .nav-link {

            justify-content:
                flex-start;

            padding:
                .55rem .75rem !important;
        }


        body.sidebar-collapse .sidebar-elegant .nav-treeview,

        body.sidebar-collapse .sidebar-elegant .nav-link .right {

            display:
                block !important;
        }


        /* Mobile toggle */

        .sidebar-edge-toggle {

            top:
                84px;

            right:
                -10px;

            z-index:
                9999 !important;
        }


        body.sidebar-mobile-open #sidebarEdgeToggleIcon {

            transform:
                rotate(180deg);
        }


        /* Mobile overlay */

        .sidebar-mobile-overlay {

            display:
                none;

            position:
                fixed;

            inset:
                0;

            background:
                rgba(0, 0, 0, .58);

            backdrop-filter:
                blur(2px);

            -webkit-backdrop-filter:
                blur(2px);

            z-index:
                1035;

            opacity:
                0;

            transition:
                opacity .2s ease;
        }


        body.sidebar-mobile-open .sidebar-mobile-overlay {

            display:
                block;

            opacity:
                1;
        }

    }


    /* =========================================================
   DESKTOP ONLY
   ========================================================= */

    @media (min-width: 992px) {

        .sidebar-mobile-overlay {

            display:
                none !important;
        }

    }


    /* =========================================================
   SMALL MOBILE
   ========================================================= */

    @media (max-width: 575.98px) {

        .sidebar-elegant {

            width:
                285px;
        }


        body.sidebar-collapse .sidebar-elegant {

            width:
                285px;
        }


        .sidebar-elegant .brand-link {

            height:
                68px;
        }


        .sidebar-edge-toggle {

            width:
                30px;

            height:
                30px;

            right:
                -10px;
        }

    }


    /* =========================================================
   REDUCE MOTION
   ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        .sidebar-elegant,
        .sidebar-elegant *,
        .sidebar-edge-toggle,
        .sidebar-mobile-overlay {

            transition:
                none !important;
        }

    }
</style>


<script>
    (function() {

        var body =
            document.body;

        var toggleBtn =
            document.getElementById(
                'sidebarEdgeToggle'
            );

        var overlay =
            document.getElementById(
                'sidebarMobileOverlay'
            );

        var sidebar =
            document.getElementById(
                'mainSidebar'
            );


        /* =====================================================
           MOBILE CHECK
           ===================================================== */

        function isMobile() {

            return window.innerWidth < 992;

        }


        /* =====================================================
           TOOLTIP
           ===================================================== */

        function setTitleTooltips() {

            sidebar
                .querySelectorAll('.nav-link')
                .forEach(function(link) {

                    var p =
                        link.querySelector('p');

                    if (
                        p &&
                        !link.hasAttribute('title')
                    ) {

                        link.setAttribute(
                            'title',
                            p.textContent
                            .trim()
                            .split('\n')[0]
                        );

                    }

                });

        }


        setTitleTooltips();


        /* =====================================================
           APPLY SAVED STATE
           ===================================================== */

        function applyState() {

            if (isMobile()) {

                body.classList.remove(
                    'sidebar-collapse'
                );

                var open =
                    localStorage.getItem(
                        'sidebarMobileOpen'
                    ) === '1';

                body.classList.toggle(
                    'sidebar-mobile-open',
                    open
                );

            } else {

                body.classList.remove(
                    'sidebar-mobile-open'
                );

                var collapsed =
                    localStorage.getItem(
                        'sidebarCollapsed'
                    ) === '1';

                body.classList.toggle(
                    'sidebar-collapse',
                    collapsed
                );

            }

        }


        applyState();


        /* =====================================================
           TOGGLE BUTTON
           ===================================================== */

        toggleBtn.addEventListener(
            'click',
            function() {

                if (isMobile()) {

                    var open = !body.classList.contains(
                        'sidebar-mobile-open'
                    );

                    body.classList.toggle(
                        'sidebar-mobile-open',
                        open
                    );

                    localStorage.setItem(
                        'sidebarMobileOpen',
                        open ? '1' : '0'
                    );

                } else {

                    var collapsed = !body.classList.contains(
                        'sidebar-collapse'
                    );

                    body.classList.toggle(
                        'sidebar-collapse',
                        collapsed
                    );

                    localStorage.setItem(
                        'sidebarCollapsed',
                        collapsed ? '1' : '0'
                    );

                }

            }
        );


        /* =====================================================
           MOBILE OVERLAY
           ===================================================== */

        overlay.addEventListener(
            'click',
            function() {

                body.classList.remove(
                    'sidebar-mobile-open'
                );

                localStorage.setItem(
                    'sidebarMobileOpen',
                    '0'
                );

            }
        );


        /* =====================================================
           AUTO CLOSE MOBILE
           ===================================================== */

        sidebar.addEventListener(
            'click',
            function(e) {

                var link =
                    e.target.closest(
                        '.nav-link'
                    );

                if (
                    link &&
                    isMobile() &&
                    link.getAttribute('href') &&
                    link.getAttribute('href') !== '#'
                ) {

                    body.classList.remove(
                        'sidebar-mobile-open'
                    );

                    localStorage.setItem(
                        'sidebarMobileOpen',
                        '0'
                    );

                }

            }
        );


        /* =====================================================
           RESIZE
           ===================================================== */

        var resizeTimer;

        window.addEventListener(
            'resize',
            function() {

                clearTimeout(
                    resizeTimer
                );

                resizeTimer =
                    setTimeout(
                        applyState,
                        150
                    );

            }
        );

    })();
</script>
```
