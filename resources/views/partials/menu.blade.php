<div class="title-bar">
    <div class="title-bar-left">
        <a href="/{{config('cms.admin_prefix', 'admin')}}">Admin</a>
    </div>
    <div class="title-bar-right">
        <ul class="dropdown menu align-right" data-dropdown-menu>
            <li>
                <a href="#">Menu</a>
                <ul class="menu">
                    @foreach($menu as $menuItem)
                        <li><a href="/{{ config('cms.prefix', 'cms')}}/{{$menuItem}}/edit">{{ str_replace('-', ' ', $menuItem) }}</a></li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</div>

