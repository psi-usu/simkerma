
<div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
            <div class="panel-heading">
                <div class="pull-left">
                    <h3 class="panel-title">Approve Kerjasama</h3>
                </div>
                <div class="clearfix"></div>
            </div><!-- /.panel-heading -->
            <form action="approve"  method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <input name="id" type="hidden" value="{{$cooperation['id']}}">
                <div class="panel-body">
                    <label for="note" class="control-label form-group">Status</label> :
                    <div class='rdio radio-inline rdio-theme rounded'>
                        <input type='radio' class='radio-inline' id='status1'
                               required value='AC' name="status">
                        <label class='' for='status1'>Diterima</label>
                    </div>
                    <div class='rdio radio-inline rdio-theme rounded'>
                        <input type='radio' class='radio-inline' id='status2'
                               required value='RJ' name="status">
                        <label class='' for='status2'>Ditolak</label>
                    </div>
                    <div class="form-group">
                        <label for="note" class="control-label">Catatan</label>
                        <textarea name="note" class="form-control" id="note" placeholder="Catatan approve kerjasama" ></textarea>
                    </div>
                </div><!-- /.panel-body -->
                <div class="panel-footer">
                    <input type="submit" class="btn btn-success rounded btn-slideright" value="Submit">
                    <a href="{{url('/')}}" class="btn btn-danger rounded btn-slideright">Batal</a>
                </div>
            </form>
        </div>
    </div><!-- /.col-md-12 -->
</div><!-- /.row -->