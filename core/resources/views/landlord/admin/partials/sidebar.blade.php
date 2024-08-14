<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    {!! render_image_markup_by_attachment_id(auth('admin')->user()->image) !!}
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{auth('admin')->user()->name}}</span>
                    <span class="text-secondary text-small">{{auth('admin')->user()->username}}</span>
                </div>
                @if(auth('admin')->user()->email_verified === 1)
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                @endif
            </a>
        </li>
        {!! \App\Facades\LandlordAdminMenu::render_sidebar_menus() !!}
    </ul>
</nav>
