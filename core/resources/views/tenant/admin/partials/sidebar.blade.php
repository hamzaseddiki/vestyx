<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="{{url('/')}}" class="nav-link" target="_blank">
                <div class="nav-profile-image">

                    {!! render_image_markup_by_attachment_id(optional(auth('admin')->user())->image,'','full',true) ?? '' !!}

                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{optional(auth('admin')->user())->name}}</span>
                    <span class="text-secondary text-small">{{optional(auth('admin')->user())->username}}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        {!! \App\Facades\LandlordAdminMenu::render_tenant_sidebar_menus() !!}
    </ul>
</nav>
