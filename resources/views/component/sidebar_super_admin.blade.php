<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3>Kepala Cabang</h3>
        <ul class="nav side-menu">
            <!-- {{session()->get('nama')}}
            {{session()->get('id')}}
            {{session()->get('level')}} -->
            <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a>
            </li>
            <li><a><i class="fa fa-cart-plus"></i> Aproval <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('aproval_purchase_order') }}">Data Purchase</a></li>
                    <li><a href="{{ route('aprovalopname') }}">Data Opname</a></li>
                    <li><a href="{{ route('approvesales') }}">Data Sales</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-database"></i> Data Master <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('produk')}}">Data Produk</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-book"></i> Report <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('report_produk') }}">Produk</a></li>
                    <li><a href="{{ route('cost_report') }}">Cost</a></li>
                    <li><a href="{{ route('sales_achievement') }}">Sales Achievement</a></li>
                    <li><a href="{{ route('sales_transaksi') }}">Sales Transaction</a></li>
                    <li><a href="{{ route('broken_exp_report') }}">Broken & Exp Movement</a></li>
                    <li><a href="{{ route('stok-report') }}">Stok Inventory</a></li>
                    <li><a href="{{ route('purchase-report') }}">Purchase</a></li>
                    <li><a href="{{ route('purchase-return-report') }}">Purchase Return</a></li>
                </ul>
            </li>
            <li><a href="{{ route('view_profile') }}"><i class="fa fa-user"></i> Profile </a>
            </li>

        </ul>
    </div>


</div>
