@extends('main_layout')

@section('content')
    <!-- START @PAGE CONTENT -->
    <section id="page-content">

        <!-- Start page header -->
        <div id="tour-11" class="header-content">
            <h2><i class="fa fa-handshake-o"></i>Kerjasama</h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">Direktori Anda:</span>
                <ol class="breadcrumb">
                    <li class="active">Kerjasama</li>
                </ol>
            </div>
        </div><!-- /.header-content -->
        <!--/ End page header -->

        <!-- Start body content -->
        <div class="body-content animated fadeIn">
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Daftar Approve Kerjasama</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip"
                                data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div><!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive mb-20">
                                <input type="hidden" id="auth" value="{{$auth}}">
                                <table id="coop-approve-list" class="table table-striped table-theme">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Bidang Kerjasama</th>
                                        <th>Instansi Partner</th>
                                        <th>Unit Kerjasama</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.row -->
                </div>
            </div>

        </div><!-- /.body-content -->
        <!--/ End body content -->

        @include('layout.footer')

    </section><!-- /#page-content -->
    <!--/ END PAGE CONTENT -->
@endsection