@extends('main_layout')

@section('content')

    <div id="delete" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Hapus Kerjasama</h4>
                </div>
                <div class="modal-body">
                    <p id="nama_instansi_hapus">Apakah anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <form action="" method="post">
                        <input type="hidden" name="_method" value="DELETE">
                        {{csrf_field()}}
                        <button type="submit" class="btn btn-danger rounded">OK</button>
                        <button type="button" class="btn btn-default rounded" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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
                        <h3 class="panel-title">Daftar Kerjasama</h3>
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
                            <div class="pull-left">
                                <a href="{{url('cooperations/create')}}" class="btn btn-theme rounded"
                                   data-toggle="tooltip" data-placement="top" title="Tambah">
                                    <i class="fa fa-plus"></i> Tambah Kerjasama
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive mb-20">
                                <input type="hidden" id="auth" value="{{$auth}}">
                                <table id="coop-list" class="table table-striped table-theme">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>No.</th>
                                        <th>Bidang Kerjasama</th>
                                        <th>Instansi Partner</th>
                                        <th>Jenis</th>
                                        <th>Bentuk</th>
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