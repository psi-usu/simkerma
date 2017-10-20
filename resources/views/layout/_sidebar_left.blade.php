<!-- START @SIDEBAR LEFT -->
<aside id="sidebar-left" class="sidebar-circle">
    <!-- Start left navigation - profile shortcut -->
    <div class="sidebar-content">
        <div class="media">
            <a class="pull-left has-notif avatar">
                <img src="{{$user_info['photo']}}" alt="admin">
                <i class="online"></i>
            </a>
            <div class="media-body">
                <h4 class="media-heading">Hello, <span id='username'>{{$user_info['full_name']}}</span></h4>
                <small>NIP : {{$user_info['username']}}</small>
            </div>
        </div>
    </div><!-- /.sidebar-content -->
    <!--/ End left navigation -  profile shortcut -->

    <!-- Start left navigation - menu -->
    <ul id="tour-9" class="sidebar-menu">

        <!-- Start navigation - dashboard -->
        <li class="submenu {!! Request::is('cooperations') ? 'active' : null !!}">
            <a href="{{url('cooperations')}}">
                <span class="icon"><i class="fa fa-handshake-o"></i></span>
                <span class="text">Kerjasama</span>
                {!! Request::is('cooperations') ? '<span class="selected"></span>' : null !!}
            </a>
        </li>
        <li class="submenu {!! Request::is('/', 'cooperations/soon-ends') ? 'active' : null !!}">
            <a href="{{url('cooperations/soon-ends')}}">
                <span class="icon"><i class="fa fa-hand-stop-o"></i></span>
                <span class="text">Kerjasama Segera Berakhir</span>
                {!! Request::is('/', 'cooperations/soon-ends') ? '<span class="selected"></span>' : null !!}
            </a>
        </li>
        @can('admin-menu')
            <li class="submenu {!! Request::is('cooperations/approve-list') ? 'active' : null !!}">
                <a href="{{url('cooperations/approve-list')}}">
                    <span class="icon"><i class="fa fa-check-square-o"></i></span>
                    <span class="text">Approve Kerjasama</span>
                    {!! Request::is('cooperations/approve-list') ? '<span class="selected"></span>' : null !!}
                </a>
            </li>
        @endcan
        <li class="submenu {!! Request::is('partners', 'partners/*', 'units') ? 'active' : null !!}">
            <a href="javascript:void(0);">
                <span class="icon"><i class="fa fa-dot-circle-o"></i></span>
                <span class="text">Data Referensi </span>
                <span class="arrow"></span>
                {!! Request::is('partners', 'partners/*', 'units') ? '<span class="selected"></span>' : null !!}
            </a>
            <ul>
                @can('admin-menu')
                    <li><a href="{{url('partners')}}">Instansi Partner</a></li>
                @endcan
                <li><a href="{{url('units')}}">Unit Kerja</a></li>
            </ul>
        </li>
        @can('admin-menu')
            <li class="submenu {!! Request::is('report', 'report') ? 'active' : null !!}">
                <a href="{{url('report')}}">
                    <span class="icon"><i class="fa fa-bar-chart"></i></span>
                    <span class="text">Laporan</span>
                    {!! Request::is('report') ? '<span class="selected"></span>' : null !!}
                </a>
            </li>

            <li class="submenu {!! Request::is('users', 'users/*') ? 'active' : null !!}">
                <a href="javascript:void(0);">
                    <span class="icon"><i class="fa fa-lock"></i></span>
                    <span class="text">Admin </span>
                    <span class="arrow"></span>
                    {!! Request::is('users', 'users/*') ? '<span class="selected"></span>' : null !!}
                </a>
                <ul>
                    <li><a href="{{url('users')}}">User</a></li>
                </ul>
            </li>
        @endcan

        <!--/ End navigation - dashboard -->
    </ul>

</aside><!-- /#sidebar-left -->
<!--/ END SIDEBAR LEFT -->