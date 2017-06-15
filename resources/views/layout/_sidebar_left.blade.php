<!-- START @SIDEBAR LEFT -->
<aside id="sidebar-left" class="sidebar-circle">
    <!-- Start left navigation - profile shortcut -->
    <div id="tour-8" class="sidebar-content">
        <div class="media">
            <a class="pull-left has-notif avatar" href="{{url('user/profile')}}">
                <img src="../img/logo.png" alt="admin">
                <i class="online"></i>
            </a>
            <div class="media-body">
                <h4 class="media-heading">Hello, <span id='username'>{{$user_info['full_name']}}</span></h4>
                <!-- <small>Web Designer</small> -->
            </div>
        </div>
    </div><!-- /.sidebar-content -->
    <!--/ End left navigation -  profile shortcut -->

    <!-- Start left navigation - menu -->
    <ul id="tour-9" class="sidebar-menu">

        <!-- Start navigation - dashboard -->
        <li class="submenu {!! Request::is('/', '/') ? 'active' : null !!}">
            <a href="{{url('/')}}">
                <span class="icon"><i class="fa fa-handshake-o"></i></span>
                <span class="text">Kerjasama</span>
            </a>
        </li>
        {{--<li class="submenu {!! Request::is('cooperations', 'cooperations/soon-ends') ? 'active' : null !!}">--}}
            {{--<a href="{{url('cooperations/soon-ends')}}">--}}
                {{--<span class="icon"><i class="fa fa-handshake-o"></i></span>--}}
                {{--<span class="text">Kerjasama Segera Berakhir</span>--}}
            {{--</a>--}}
        {{--</li>--}}
        <li class="submenu">
            <a href="javascript:void(0);">
                <span class="icon"><i class="fa fa-dot-circle-o"></i></span>
                <span class="text">Data Referensi </span>
                <span class="arrow"></span>
                <span class="selected"></span>
            </a>
            <ul>
                <li><a href="{{url('partners')}}">Instansi Partner</a></li>
                <li><a href="{{url('units')}}">Unit Kerja</a></li>
            </ul>
        </li>

        <li class="submenu" id="menu_admin" style="visibility: hidden;">
            <a href="user.html">
                <span class="icon"><i class="fa fa-dot-circle-o"></i></span>
                <span class="text">User</span>
            </a>
        </li>

        <!--/ End navigation - dashboard -->
    </ul>

</aside><!-- /#sidebar-left -->
<!--/ END SIDEBAR LEFT -->