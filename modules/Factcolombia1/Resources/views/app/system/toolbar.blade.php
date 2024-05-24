        <header class="header">
            <ul class="header-nav">
                <li>
                    <button id="js-toggle-sidebar" class="header-nav-item">
                        <i class="fas fa-bars"></i>
                    </button>
                </li>
            </ul>
            <ul class="header-nav pull-right">
                {{-- <notification-notification :user="{{auth()->user()}}"></notification-notification> --}}
                <menu-popover :user="{{auth()->user()}}"></menu-popover>
            </ul>
        </header>
