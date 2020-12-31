<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3>Pimpinan</h3>
        <ul class="nav side-menu">
            <!-- {{session()->get('nama')}}
            {{session()->get('id')}}
            {{session()->get('level')}} -->
            <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a>
            </li>
            
            <li><a><i class="fa fa-user"></i> Management <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                <li><a href="{{route('user')}}">User</a></li>
            <li><a href="{{ route('cabang') }}">Cabang</a></li>
            <li><a href="{{route('gudang')}}">Gudang</a></li>
                </ul>
            </li>
            <!--<li><a><i class="fa fa-file"></i> Report <span class="fa fa-chevron-down"></span></a>-->
            <!--    <ul class="nav child_menu">-->
            <!--        <li><a href="{{ route('cost') }}">Data Cost</a></li>-->
            <!--        <li><a href="{{ route('sales_transaction') }}">Sales Transaction</a></li>-->
                   
            <!--    </ul>-->
            <!--</li>-->
            <li><a href="{{ route('view_profile') }}"><i class="fa fa-user"></i> Profile </a>
            </li>

        </ul>
    </div>


</div>
