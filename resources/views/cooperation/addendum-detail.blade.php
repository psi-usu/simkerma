<!-- Addendum -->
<div id="Addendum" style='display: none !important;'>
    <div class="form-group">
        <label for="name" class="control-label">Pilih Addenum</label>
        <select class="form-control mb-15" name='pilihan' id="pilihan" required>
            <option value="">-- Pilih --</option>
            <option value="mou">MoU / Nota Kesepahaman</option>
            <option value="moa">MoA / Perjanjian Kerja Sama</option>
        </select>
    </div>

    <!-- mou addendum -->
    <div id="mou_addendum" style='display: none !important;'>
        <div class="form-group">
            <label for="name" class="control-label">Bidang kerjasama berdasarkan MoU / Nota Kesepahaman</label>
            <select class="form-control select2" style="width: 100%;" name="bid_kerma_mou" required>
                <option disabled selected>-- Pilih Bidang Kerjasama --</option>
                <option value="Development of mutually beneficial academic and training programs">Development of mutually beneficial academic and training programs</option>
                <option value="Exchange of faculty and staff for purposes of teaching, research and extention">Exchange of faculty and staff for purposes of teaching, research and extention</option>
                <option value="Reciprocal assistance for visiting academic faculty, staff and students">Reciprocal assistance for visiting academic faculty, staff and students</option>
                <option value="Coordination of such activities as joint research and transfer of technologaya">Coordination of such activities as joint research and transfer of technologaya</option>
                <option value="Exchange of documentation and research materials in fields of mutual interest">Exchange of documentation and research materials in fields of mutual interest</option>
            </select>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Instansi Partner</label>
            <input type="text" class="form-control" id="instansi" disabled>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Bidang Kerjasama</label>
            <textarea name="bid_kerma" class="form-control" placeholder="Bidang Kerjasama" id="bid_kerma" required></textarea>
        </div>
        <div class="form-group">
            <label for="tanda-tangan" class="control-label">Tanggal Tanda Tangan</label>
            <input name="tgl_tanda_tangan" class="form-control" id="datepicker tgl_tanda_tangan" type="text" required>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Tanggal Berakhir</label>
            <input name="tgl_berakhir" class="form-control" id="datepicker2 tgl_akhir" type="text" required>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Bentuk Kerjasama</label>
            <input class="form-control" id="bentuk_kerma" name='bentuk_kerma' type="text" placeholder="Bentuk Kerjasama" required>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Nomor Dokumen USU</label>
            <input class="form-control" id="nomor_dokumen_usu" name='nomor_dokumen_usu' type="text" placeholder="Nomor Dokumen USU" required>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Nomor Dokumen Instansi Partner</label>
            <input class="form-control" id="nomor_dokumen_partner" name='nomor_dokumen_partner' type="text" placeholder="Nomor Dokumen Instansi Partner" required>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Upload Dokumen</label>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <span class="btn btn-theme btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" id="upload_dokumen" name="upload_dokumen" required></span>
                <span class="fileinput-filename"></span>
                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Download File Dokumen</label>
            <div class="form-group">
                <a href="download.html"><button class="btn btn-theme"><i class="fa fa-download" aria-hidden="true"></i> File </button></a>
            </div>
        </div>
    </div>

    <!-- moa addendum -->
    <div id="moa_addendum" style='display: none !important;'>
        <div class="form-group">
            <label for="name" class="control-label">Bidang kerjasama berdasarkan MoU / Nota Kesepahaman</label>
            <select class="form-control select2" style="width: 100%;" name="bid_kerma_mou" required>
                <option disabled selected>-- Pilih Bidang Kerjasama --</option>
                <option value="Development of mutually beneficial academic and training programs">Development of mutually beneficial academic and training programs</option>
                <option value="Exchange of faculty and staff for purposes of teaching, research and extention">Exchange of faculty and staff for purposes of teaching, research and extention</option>
                <option value="Reciprocal assistance for visiting academic faculty, staff and students">Reciprocal assistance for visiting academic faculty, staff and students</option>
                <option value="Coordination of such activities as joint research and transfer of technologaya">Coordination of such activities as joint research and transfer of technologaya</option>
                <option value="Exchange of documentation and research materials in fields of mutual interest">Exchange of documentation and research materials in fields of mutual interest</option>
            </select>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Bidang kerjasama berdasarkan MoA / Nota Kesepahaman</label>
            <select class="form-control select2" style="width: 100%;" name="bid_kerma_mou" required>
                <option disabled selected>-- Pilih Bidang Kerjasama --</option>
                <option value="Development of mutually beneficial academic and training programs">Development of mutually beneficial academic and training programs</option>
                <option value="Exchange of faculty and staff for purposes of teaching, research and extention">Exchange of faculty and staff for purposes of teaching, research and extention</option>
                <option value="Reciprocal assistance for visiting academic faculty, staff and students">Reciprocal assistance for visiting academic faculty, staff and students</option>
                <option value="Coordination of such activities as joint research and transfer of technologaya">Coordination of such activities as joint research and transfer of technologaya</option>
                <option value="Exchange of documentation and research materials in fields of mutual interest">Exchange of documentation and research materials in fields of mutual interest</option>
            </select>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Bidang Kerjasama MoA / Perjanjian Kerja Sama</label>
            <textarea name="bid_kerma_moa" class="form-control" id="bid_kerma_moa" placeholder="Bidang Kerjasama MoA / Perjanjian Kerja Sama" required></textarea>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Implementasi</label>
            <textarea name="implementasi" class="form-control" id="implementasi" placeholder="implementasi" required></textarea>
        </div>
        <div class="form-group">
            <label for="tanda-tangan" class="control-label">Tanggal Tanda Tangan</label>
            <input name="tgl_tanda_tangan" class="form-control" id="datepicker3 tgl_tanda_tangan_moa" type="text" placeholder="mm/dd/YYYY" required>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Tanggal Berakhir</label>
            <input name="tgl_berakhir" class="form-control" id="datepicker4 tgl_tanda_akhir" type="text" placeholder="mm/dd/YYYY" required>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Unit yang melakukan kerjasama</label>
            <div>
                <select class="form-control mb-15" name='unit_kerma' id="unit_kerma">
                    <option value="FASILKOM">FASILKOM</option>
                    <option value="FMIPA">FMIPA</option>
                    <option value="FE">FE</option>
                    <option value="FT">FT</option>
                    <option value="FH">FH</option>
                    <option value="FK">FK</option>
                    <option value="FKG">FKG</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Nomor Dokumen USU</label>
            <input class="form-control" id="nomor_dokumen_usu" name='nomor_dokumen_usu' type="text" placeholder="Nomor Dokumen USU" required>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Nomor Dokumen Instansi Partner</label>
            <input class="form-control" id="nomor_dokumen_partner" name='nomor_dokumen_partner' type="text" placeholder="Nomor Dokumen Instansi Partner" required>
        </div>
        <div class="form-group">
            <a href="#tambah" class="btn btn-theme btn-md rounded" data-toggle="modal" title="Tambah"><i class="fa fa-plus"></i></a>
        </div>
        <div class="form-group">
            <table class="table table-responsive table-theme" id="tabel_pekerjaan">
                <thead class="text-center">
                <th>Nama Pekerjaan / Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Total Harga</th>
                <th>Keterangan</th>
                <th>Aksi</th>
                </thead>
                <tbody>
                <tr class="text-center">
                    <td>Psikotes Plus untuk supervisor dasar</td>
                    <td>25</td>
                    <td>Orang</td>
                    <td>28125000</td>
                    <td>Keterangan 1</td>
                    <td class="text-center">
                        <a href="#edit" data-toggle="modal" class="btn btn-theme btn-md rounded" title="Edit"><i class="fa fa-pencil" style="color:white;"></i></a>

                        <a href="#delete" class="btn btn-danger btn-md rounded" data-toggle="modal" title="Hapus"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <tr class="text-center">
                    <td>Assesment Centre untuk supervisori </td>
                    <td>36</td>
                    <td>Orang</td>
                    <td>95400000</td>
                    <td>Keterangan 2</td>
                    <td>
                        <a href="#edit" data-toggle="modal" class="btn btn-theme btn-md rounded" title="Edit"><i class="fa fa-pencil" style="color:white;"></i></a>

                        <a href="#delete" class="btn btn-danger btn-md rounded" data-toggle="modal" title="Hapus"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="form-group">
                <label for="name" class="control-label">Nilai Kontrak</label>
                <input class="form-control" id="nilai_kontrak" name='nilai_kontrak' type="text" required disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Nomor Dokumen Instansi Partner</label>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <span class="btn btn-theme btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span>
                                                <input type="file" id="file_dokumen_instansi" name="file_dokumen_instansi" required></span>
                <span class="fileinput-filename"></span>
                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Download File Dokumen</label>
            <div class="form-group">
                <a href="download.html"><button class="btn btn-theme"><i class="fa fa-download" aria-hidden="true"></i> File </button></a>
            </div>
        </div>
    </div>
</div>