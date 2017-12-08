/**
 * Created by Surya on 29/05/2017.
 */

function notify(message, type){
    $.notify({
        message: message
    },{
        type: type,
        placement: {
            from: "bottom"
        },
        animate: {
            enter: "animated fadeInRight",
            exit: "animated fadeOutRight"
        }
    })
}

$(document).ready(function () {
    var getUrl = window.location,
        baseUrl = getUrl.protocol + "//" + getUrl.host + "/";

    if ($("#coop-list").length) {
        var auth = $('#auth').val();
        // var button_action = '<button class="btn btn-theme btn-sm rounded coop-view-btn" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-eye"></i></button>';
        if(auth=='SU'){
            var button_action = '<button class="btn btn-theme btn-sm rounded coop-view-btn" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-eye"></i></button> '+
                '<a data-toggle="tooltip" data-placement="top" data-original-title="Delete"><button class="btn btn-danger btn-sm rounded delete" data-toggle="modal" data-target="#delete"><i class="fa fa-times"></i></button></a>';
        }else{
            var button_action = '<button class="btn btn-theme btn-sm rounded coop-view-btn" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-eye"></i></button>';
        }

        var coopDatatable = $("#coop-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'cooperations/ajax',
            columnDefs: [
                {
                    orderable: false,
                    targets: 7
                },
                {
                    className: "dt-center",
                    targets: [1, 3, 5, 6, 9]
                },
                {
                    width: "5%",
                    targets: 1
                },
                {
                    width: "10%",
                    targets: 6
                },
                {
                    visible: false,
                    targets: 0,
                }
            ],
        });

        $(document).on("click", "#coop-list a button.delete", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = coopDatatable.fnGetPosition(target_row);
            }
            var coop_id = coopDatatable.fnGetData(position)[0];

            $("#delete form").attr("action", baseUrl + "cooperations/delete?id=" + coop_id);
        });
    }

    if ($("#coop-list-soon-ends").length) {
        $("#coop-list-soon-ends").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'cooperations/ajaxCoopSoonEnds',
            columnDefs: [
                {
                    orderable: false,
                    targets: 7
                },
                {
                    className: "dt-center",
                    targets: [1, 5, 6, 7, 8]
                },
                {
                    width: "5%",
                    targets: 1,
                },
                {
                    width: "10%",
                    targets: 6,
                },
                {
                    visible: false,
                    targets: 0,
                }
            ],
        });
    }

    if ($("#coop-approve-list").length) {
        $("#coop-approve-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'cooperations/ajaxCoopApprove',
            columnDefs: [
                {
                    orderable: false,
                    targets: 6
                },
                {
                    className: "dt-center",
                    targets: [0, 4, 5]
                },
                {
                    width: "5%",
                    targets: 0,
                }
            ],
        });
    }

    $(document).on("click", "#tambah_kerma #coop-submit", function (e) {
        e.preventDefault();
        $.confirm({
            title: 'Konfirmasi',
            content: 'Apakah anda yakin ingin submit data ini? Setelah disubmit, data tidak dapat diubah',
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Ya',
                    btnClass: 'btn-danger',
                    action: function(){
                        var myForm = $('#tambah_kerma');
                        $("#tambah_kerma").attr("action", baseUrl + "cooperations/create");
                        myForm.find(':submit').click();
                    }
                },
                close: function () {
                }
            }
        });
        if ($("#moa-table").length) {
            $(":input").inputmask()
            $("#tambah_kerma").validate({
                rules: {
                    "item_name[]": {
                        required: true
                    },
                    "item_quantity[]": {
                        required: true
                    },
                    "item_uom[]": {
                        required: true
                    },
                    "item_total_amount[]": {
                        required: true
                    },
                    "item_annotation[]": {
                        required: true
                    },
                },
                highlight: function (element) {
                    $(element).parents('.form-group').addClass('has-error has-feedback');
                },
                unhighlight: function (element) {
                    $(element).parents('.form-group').removeClass('has-error');
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        }
        else if ($("#spk-table").length) {
            $(":input").inputmask()
            $("#tambah_kerma").validate({
                rules: {
                    "item_name[]": {
                        required: true
                    },
                    "item_quantity[]": {
                        required: true
                    },
                    "item_uom[]": {
                        required: true
                    },
                    "item_total_amount[]": {
                        required: true
                    },
                    "item_annotation[]": {
                        required: true
                    },
                },
                highlight: function (element) {
                    $(element).parents('.form-group').addClass('has-error has-feedback');
                },
                unhighlight: function (element) {
                    $(element).parents('.form-group').removeClass('has-error');
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        }
    });

    $(document).on("click", "#tambah_kerma #coop-temp", function (e) {
        e.preventDefault();
        var myForm = $('#tambah_kerma');
        $("#tambah_kerma").attr("action", baseUrl + "cooperations/create-temp");
        myForm.find(':submit').click();
    });

    $(document).on("click", "#tambah_kerma #coop-update", function (e) {
        e.preventDefault();
        $.confirm({
            title: 'Konfirmasi',
            content: 'Apakah anda yakin ingin submit data ini? Setelah disubmit, data tidak dapat diubah',
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Ya',
                    btnClass: 'btn-danger',
                    action: function(){
                        var myForm = $('#tambah_kerma');
                        $("#tambah_kerma").attr("action", baseUrl + "cooperations/edit");
                        myForm.find(':submit').click();
                    }
                },
                close: function () {
                }
            }
        });
    });

    $(document).on("click", "#tambah_kerma #coop-approve", function (e) {
        e.preventDefault();
        $.confirm({
            title: 'Konfirmasi',
            content: 'Apakah anda yakin ingin submit data ini? Setelah disubmit, data tidak dapat diubah',
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Ya',
                    btnClass: 'btn-danger',
                    action: function(){
                        var myForm = $('#tambah_kerma');
                        $("#tambah_kerma").attr("action", baseUrl + "cooperations/edit");
                        myForm.find(':submit').click();
                    }
                },
                close: function () {
                }
            }
        });
    });

    $(document).on("click", "#tambah_kerma #coop-Utemp", function (e) {
        e.preventDefault();
        var myForm = $('#tambah_kerma');
        $("#tambah_kerma").attr("action", baseUrl + "cooperations/edit-temp");
        myForm.find(':submit').click();
    });

    if ($("#partner-list").length) {
        var partnerDatatable = $("#partner-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'partners/ajax',
            columnDefs: [
                {
                    orderable: false,
                    defaultContent: '<a data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-theme btn-sm rounded edit"><i class="fa fa-pencil" style="color:white;"></i></button></a>' +
                    '<a data-toggle="tooltip" data-placement="top" data-original-title="Delete"><button class="btn btn-danger btn-sm rounded delete" data-toggle="modal" data-target="#delete"><i class="fa fa-times"></i></button></a>',
                    targets: 4
                },
                {
                    className: "dt-center",
                    targets: [1, 4]
                },
                {
                    width: "5%",
                    targets: 1,
                },
                {
                    width: "20%",
                    targets: [2, 4],
                },
                {
                    visible: false,
                    targets: 0,
                },
            ],
        });

        $(document).on("click", "#partner-list a button.delete", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = partnerDatatable.fnGetPosition(target_row);
            }
            var partner_id = partnerDatatable.fnGetData(position)[0];

            $("#delete form").attr("action", baseUrl + "partners/delete?id=" + partner_id);
        });

        $(document).on("click", "#partner-list a button.edit", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = partnerDatatable.fnGetPosition(target_row);
            }
            var partner_id = partnerDatatable.fnGetData(position)[0];

            window.open(baseUrl + "partners/edit?id=" + partner_id, "_self");
        });
    }

    if ($("#area-list").length) {
        var areaDatatable = $("#area-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'areas_of_coop/ajax',
            columnDefs: [
                {
                    orderable: false,
                    targets: 3
                },
                {
                    className: "dt-center",
                    targets: [1, 3]
                },
                {
                    width: "5%",
                    targets: 1,
                },
                {
                    width: "20%",
                    targets: [2, 3],
                },
                {
                    visible: false,
                    targets: 0,
                },
            ],
        });

        $(document).on("click", "#area-list a button.delete", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = areaDatatable.fnGetPosition(target_row);
            }
            var id = areaDatatable.fnGetData(position)[0];

            $("#delete form").attr("action", baseUrl + "areas_of_coop/delete?id=" + id);
        });

        $(document).on("click", "#area-list a.edit", function (e) {
            $("#edit").modal('show');

            var id = $(this).attr('data-id1');
            var area = $(this).attr('data-id2');

            $("#edit #id").val(id);
            $("#edit #area").val(area);
            $("#edit form").attr("action", baseUrl + "areas_of_coop/edit");
        });
    }

    if ($("#unit-list").length) {
        $("#unit-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'units/ajax',
        });
    }

    if ($("#user-list").length) {
        var userDatatable = $("#user-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'users/ajax',
            columnDefs: [
                {
                    orderable: false,
                    defaultContent: '<a data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-theme btn-sm rounded edit"><i class="fa fa-pencil" style="color:white;"></i></button></a>' +
                    '<a data-toggle="tooltip" data-placement="top" data-original-title="Delete"><button class="btn btn-danger btn-sm rounded delete" data-toggle="modal" data-target="#delete"><i class="fa fa-times"></i></button></a>',
                    targets: 4
                },
                {
                    className: "dt-center",
                    targets: [0, 3, 4]
                },
                {
                    width: "5%",
                    targets: 0,
                },
            ],
        });

        $(document).on("click", "#user-list a button.delete", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = userDatatable.fnGetPosition(target_row);
            }
            var username = userDatatable.fnGetData(position)[1];

            $("#delete form").attr("action", baseUrl + "users/delete?username=" + username);
        });

        $(document).on("click", "#user-list a button.edit", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = userDatatable.fnGetPosition(target_row);
            }
            var username = userDatatable.fnGetData(position)[1];

            window.open(baseUrl + "users/edit?id=" + username, "_self");
        });
    }

    if ($("input[name=coop_type]:checked").val() == "MOU") {
        toggleCoopDetail(true, false, false, false);
    } else if ($("input[name=coop_type]:checked").val() == "MOA") {
        toggleCoopDetail(false, true, false, false);
    } else if($("input[name=coop_type]:checked").val() == "SPK") {
        toggleCoopDetail(false, false, false, true);
    } else {
        toggleCoopDetail(false, false, true, false);
    }

    if ($("#MoA input[name=is_accidental]:checked").val() == 0) {
        $('#nonAccidental').show();
        $('#nonAccidental').fadeIn('slow');
        $('#Accidental').hide();
    }else if ($("#MoA input[name=is_accidental]:checked").val() == 1) {
        $('#Accidental').show();
        $('#Accidental').fadeIn('slow');
        $('#nonAccidental').hide();
    }

    $("#MoA .nonaccmoa").click(function () {
        $('#nonAccidental').fadeIn('slow');
        $('#Accidental').hide();
    });

    $("#MoA .accmoa").click(function () {
        $('#Accidental').fadeIn('slow');
        $('#nonAccidental').hide();
    });   

    $("#SPK .nonaccspk").click(function () {
        $('#nonAccidentalSPK').fadeIn('slow');
        $('#AccidentalSPK').hide();
    });

    $("#SPK .accspk").click(function () {
        $('#AccidentalSPK').fadeIn('slow');
        $('#nonAccidentalSPK').hide();
    });   


    // if($("#MoA input:radio[name=is_accidental]:checked")) {
    //     $("#MoA input:radio[name=is_accidental]").click(function () {
    //         if (this.value == '0') {
    //             $('#nonAccidental').fadeIn('slow');
    //             $('#Accidental').hide();
    //         } else if (this.value == '1') {

    //             $('#Accidental').fadeIn('slow');

    //             $('#Accidental').show();
    //             $('#nonAccidental').hide();
    //         }
    //     });
    // }
    

    if ($("#fileinput-mou-doc").length) {
        var cooperation_id = $("input[name=id]").attr("value");
        if (cooperation_id > 0) {
            var initialPreview = "<a href='" + baseUrl + "cooperations/download-document?id=" + cooperation_id + "' class='file-preview-other'>download</a>";
            $("#fileinput-mou-doc").fileinput({
                "showCaption": true,
                "showRemove": false,
                "showUpload": true,
                "initialPreview": [
                    initialPreview
                ],
                "browseLabel": "Pilih File...",
                "language": "en"
            });
        } else {
            // $("#fileinput-mou-doc").fileinput({
            //     "showCaption": true,
            //     "showRemove": false,
            //     "showUpload": true,
            //     "browseLabel": "Pilih File...",
            //     "language": "en"
            // });
        }
    }

    var button = '<button class="btn btn-theme rounded" type="submit" id="submit">Submit</button>'
        + '  <button class="btn btn-danger rounded" type="reset">Reset</button>';
    $('#submit').html(button);

    $("input:radio[name=coop_type]").change(function () {
        if ($('input[name=coop_type]:checked').val() == 'MOU') {
            toggleCoopDetail(true, false, false, false);
            refreshMOUData();
        } else if ($('input[name=coop_type]:checked').val() == 'MOA') {
            toggleCoopDetail(false, true, false, false);
            refreshMOAData();
        } else if ($('input[name=coop_type]:checked').val() == 'SPK') {
            toggleCoopDetail(false, false, false, true);
            refreshSPKData();
        } else if ($('input[name=coop_type]:checked').val() == 'ADDENDUM') {
            toggleCoopDetail(false, false, true, false);
            $("#choose-addendum-type").find("select").val("").change();
            $("#choose-addendum-type").find("select").trigger("chosen: updated");
        }
    });

    $("#choose-addendum-type select[name=addendum_type]").change(function () {
        if ($(this).val() == 'MOU') {
            $("#choose-mou").fadeIn("slow").find("select").val("").change().attr("disabled", false);
            $("#choose-mou").find("select").trigger("chosen: udpated");
            $("#choose-moa").hide().find("select").attr("disabled", true);
            toggleCoopDetail(false, false, true, false);
        } else if ($(this).val() == 'MOA') {
            $("#choose-moa").fadeIn("slow").find("select").val("").change().attr("disabled", false);
            $("#choose-moa").find("select").trigger("chosen: udpated");
            $("#choose-mou").hide().find("select").attr("disabled", true);
            $("#choose-spk").hide().find("select").attr("disabled", true);
            toggleCoopDetail(false, false, true, false);
        } else if ($(this).val() == 'SPK') {
            $("#choose-spk").fadeIn("slow").find("select").val("").change().attr("disabled", false);
            $("#choose-spk").find("select").trigger("chosen: udpated");
            $("#choose-mou").hide().find("select").attr("disabled", true);
            $("#choose-moa").hide().find("select").attr("disabled", true);
            toggleCoopDetail(false, false, true, false);
        }
    });

    if ($("#choose-addendum-type select[name=addendum_type]").val() != null) {
        if ($("#choose-addendum-type select[name=addendum_type]").val() == 'MOU') {
            $("#choose-mou").fadeIn("slow");
            toggleCoopDetail(false, false, true, false);
        } else if ($("#choose-addendum-type select[name=addendum_type]").val() == 'MOA') {
            $("#choose-moa").fadeIn("slow");
            toggleCoopDetail(false, false, true, false);
        } else if ($("#choose-addendum-type select[name=addendum_type]").val() == 'SPK') {
            $("#choose-spk").fadeIn("slow");
            toggleCoopDetail(false, false, false, true);
        }
    }
    if ($("#choose-mou select[name=cooperation_id]:visible").val() > 0) {
        toggleCoopDetail(true, false, true, false);
    }
    if ($("#choose-moa select[name=cooperation_id]:visible").val() > 0) {
        toggleCoopDetail(false, true, true, false);
    }

    if ($("#choose-spk select[name=cooperation_id]:visible").val() > 0) {
        toggleCoopDetail(false, false, true, true);
    }

    $("#choose-mou select[name=cooperation_id]").change(function () {
        toggleCoopDetail(true, false, true, false);
        refreshMOUData();
        var id = $(this).val();
        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                // $("select[name=partner_id]").val(data['partner_id']).change();
                $('select[name=partner_id]').prepend('<option value='+data["partner_id"]+' selected="selected">'+data["partner_name"]+'</option>').change();
                $("select[name=partner_id]").trigger("chosen:updated");
                $("select[name=form_of_coop]").val(data['form_of_coop']).change();
                $("select[name=form_of_coop]").trigger("chosen:updated");
                $("textarea[name=area_of_coop]").val(data['area_of_coop']);
                $("input[name=sign_date]").val(data['sign_date']);
                $("input[name=end_date]").val(data['end_date']);
                $("input[name=usu_doc_no]").val(data['usu_doc_no']);
                $("input[name=partner_doc_no]").val(data['partner_doc_no']);
            }
        });
    });

    $("#choose-moa select[name=cooperation_id]").change(function () {
        toggleCoopDetail(false, true, true, false);
        refreshMOAData();
        var id = $(this).val();
        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $("#MoA select[name=cooperation_id]").val(data['cooperation_id']).change();
                $("#MoA select[name=cooperation_id]").trigger("chosen: updated");
                $("#MoA select[name=cooperation_id]").attr("disabled", true);

                $("#MoA textarea[name=subject_of_coop]").val(data['subject_of_coop']);
                $("#MoA select[name=area_of_coop]").val(data['area_of_coop']).change();
                $("#MoA select[name=area_of_coop]").trigger("chosen: updated");
                $("#MoA textarea[name=implementation]").val(data['implementation']);
                $("#MoA input[name=sign_date]").val(data['sign_date']);
                $("#MoA input[name=end_date]").val(data['end_date']);
                $("#MoA input[name=usu_doc_no]").val(data['usu_doc_no']);
                $("#MoA input[name=partner_doc_no]").val(data['partner_doc_no']);
                $("#MoA textarea[name=benefit]").val(data['benefit']);
                $("#MoA select[name=unit]").val(data['unit']).change();
                $("#MoA select[name=unit]").trigger("chosen: updated");
                $("#MoA input[name=contract_amount]").val(data['contract_amount']+".00");

                $.each(data.coop_items, function (k, v) {
                    if (k > 0) {
                        var $clone = $("#moa-table").find('tr.hide').clone(true).removeClass('hide table-line');
                        $clone.find("input").attr("disabled", false);
                        $("#moa-table").find('table').append($clone);
                        $(":input").inputmask()
                    }
                });

                $.each($("#moa-table input[name^=item_name]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_name']);
                });
                $.each($("#moa-table input[name^=item_quantity]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_quantity']);
                });
                $.each($("#moa-table input[name^=item_uom]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_uom']);
                });
                $.each($("#moa-table input[name^=item_total_amount]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_total_amount']);
                });
                $.each($("#moa-table input[name^=item_annotation]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_annotation']);
                });
            }
        });
    });

    if ($("#choose-moa select[name=cooperation_id]").val() > 0) {
        var id = $("#choose-moa select[name=cooperation_id]").val();

        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $("#MoA select[name=cooperation_id]").val(data['cooperation_id']).change();
                $("#MoA select[name=cooperation_id]").trigger("chosen: updated");
                $("#MoA select[name=cooperation_id]").attr("disabled", true);
            }
        });
    }

    $("#choose-spk select[name=cooperation_id]").change(function () {
        toggleCoopDetail(false, false, true, true);
        refreshSPKData();
        var id = $(this).val();
        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $("#SPK select[name=cooperation_id]").val(data['cooperation_id']).change();
                $("#SPK select[name=cooperation_id]").trigger("chosen: updated");
                $("#SPK select[name=cooperation_id]").attr("disabled", true);

                $("#SPK textarea[name=subject_of_coop]").val(data['subject_of_coop']);
                $("#SPK select[name=area_of_coop]").val(data['area_of_coop']).change();
                $("#SPK select[name=area_of_coop]").trigger("chosen: updated");
                $("#SPK textarea[name=implementation]").val(data['implementation']);
                $("#SPK input[name=sign_date]").val(data['sign_date']);
                $("#SPK input[name=end_date]").val(data['end_date']);
                $("#SPK input[name=usu_doc_no]").val(data['usu_doc_no']);
                $("#SPK input[name=partner_doc_no]").val(data['partner_doc_no']);
                $("#SPK textarea[name=benefit]").val(data['benefit']);
                $("#SPK select[name=unit]").val(data['unit']).change();
                $("#SPK select[name=unit]").trigger("chosen: updated");
                $("#SPK input[name=contract_amount]").val(data['contract_amount']+".00");

                $.each(data.coop_items, function (k, v) {
                    if (k > 0) {
                        var $clone = $("#spk-table").find('tr.hide').clone(true).removeClass('hide table-line');
                        $clone.find("input").attr("disabled", false);
                        $("#spk-table").find('table').append($clone);
                        $(":input").inputmask()
                    }
                });

                $.each($("#spk-table input[name^=item_name]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_name']);
                });
                $.each($("#spk-table input[name^=item_quantity]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_quantity']);
                });
                $.each($("#spk-table input[name^=item_uom]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_uom']);
                });
                $.each($("#spk-table input[name^=item_total_amount]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_total_amount']);
                });
                $.each($("#spk-table input[name^=item_annotation]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_annotation']);
                });
            }
        });
    });

    if ($("#choose-spk select[name=cooperation_id]").val() > 0) {
        var id = $("#choose-spk select[name=cooperation_id]").val();

        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $("#SPK select[name=cooperation_id]").val(data['cooperation_id']).change();
                $("#SPK select[name=cooperation_id]").trigger("chosen: updated");
                $("#SPK select[name=cooperation_id]").attr("disabled", true);
            }
        });
    }

    function toggleCoopDetail(mou, moa, addendum, spk) {
        if (mou) {
            $('#MoU').fadeIn('slow').find('input, textarea, select').attr('disabled', false);
        } else {
            $('#MoU').hide().find('input, textarea, select').attr('disabled', true);
        }
        if (moa) {
            $('#MoA').fadeIn('slow');
            var val = $("#tambah_kerma input[name=approve]").val();

            if(val==""){
                $('#MoA').find('input, textarea, select').attr('disabled', false);
            }else{
                $('#MoA').find('input, textarea, select').attr('disabled', true);
            }

            $('#MoA').find('input[name^=mou_detail_], textarea[name^=mou_detail_], select[name^=mou_detail_], input[name=contract_amount], input[name^=item_]:hidden').attr('disabled', true);
        } else {
            $('#nonAccidental').hide();
            $('#Accidental').hide();
            $('#MoA').hide().find('input, textarea, select').attr('disabled', true);
        }
        if (addendum) {
            $('#Addendum').fadeIn('slow').find('input, textarea, select').attr('disabled', false);
            $('#choose-addendum-type').fadeIn('slow').find('input, textarea, select').attr('disabled', false);
        } else {
            $('#Addendum').hide().find('input, textarea, select').attr('disabled', true);
            $('#choose-addendum-type').hide().find('select').attr('disabled', false);
            $('#choose-mou').hide().find('select').attr('disabled', false);
            $('#choose-moa').hide().find('select').attr('disabled', false);
            $('#choose-spk').hide().find('select').attr('disabled', false);
        }
        if (spk) {
            $('#SPK').fadeIn('slow').find('input, textarea, select').attr('disabled', false);
            $('#SPK').find('input[name^=mou_detail_], textarea[name^=mou_detail_], select[name^=mou_detail_], input[name=contract_amount], input[name^=item_]:hidden').attr('disabled', true);
        } else {
            $('#nonAccidentalSPK').hide();
            $('#AccidentalSPK').hide();
            $('#SPK').hide().find('input, textarea, select').attr('disabled', true);
        }
    }

    function refreshMOUData() {
        $("#MoU select[name=partner_id]").val("").change();
        $("#MoU select[name=partner_id]").trigger("chosen:updated");
        $("#MoU select[name=form_of_coop]").val("").change();
        $("#MoU select[name=form_of_coop]").trigger("chosen:updated");
        $("#MoU select[name=area_of_coop]").val("").change();
        $("#MoU select[name=area_of_coop]").trigger("chosen:updated");
        $("#MoU textarea[name=subject_of_coop]").val("");
        $("#MoU input[name=sign_date]").val("");
        $("#MoU input[name=end_date]").val("");
        $("#MoU input[name=usu_doc_no]").val("");
        $("#MoU input[name=partner_doc_no]").val("");
    }

    function refreshMOAData() {
        $("#MoA input[name=is_accidental]").prop('checked', false);
        $("#MoA select[name=cooperation_id]").val("").change();
        $("#MoA select[name=cooperation_id]").trigger("chosen: updated");

        $("#MoA input[name^=mou_detail_]").val("");

        $("#MoA textarea[name=area_of_coop]").val("");
        $("#MoA textarea[name=implementation]").val("");
        $("#MoA input[name=sign_date]").val("");
        $("#MoA input[name=end_date]").val("");
        $("#MoA input[name=usu_doc_no]").val("");
        $("#MoA input[name=partner_doc_no]").val("");
        $("#MoA select[name=unit]").val("").change();
        $("#MoA select[name=unit]").trigger("chosen: updated");
        $("#MoA input[name=contract_amount]").val(0);

        $("#moa-table").find("tbody tr:visible").detach();
        var $clone = $("#moa-table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find("input").attr("disabled", false);
        $("#moa-table").find('table').append($clone);
        $(":input").inputmask();
    }

    function refreshSPKData() {
        $("#SPK input[name=is_accidental]").prop('checked', false);
        $("#SPK select[name=cooperation_id]").val("").change();
        $("#SPK select[name=cooperation_id]").trigger("chosen: updated");

        $("#SPK input[name^=mou_detail_]").val("");

        $("#SPK textarea[name=area_of_coop]").val("");
        $("#SPK textarea[name=implementation]").val("");
        $("#SPK input[name=sign_date]").val("");
        $("#SPK input[name=end_date]").val("");
        $("#SPK input[name=usu_doc_no]").val("");
        $("#SPK input[name=partner_doc_no]").val("");
        $("#SPK select[name=unit]").val("").change();
        $("#SPK select[name=unit]").trigger("chosen: updated");
        $("#SPK input[name=contract_amount]").val(0);

        $("#spk-table").find("tbody tr:visible").detach();
        var $clone = $("#spk-table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find("input").attr("disabled", false);
        $("#spk-table").find('table').append($clone);
        $(":input").inputmask();
    }

    if ($("input[name=upd_mode]").val() == 'display') {
        $("#tambah_kerma input:visible, #tambah_kerma select:visible, #tambah_kerma textarea:visible").attr("disabled", true);
    }

    if ($(".select2").length) {
        $(".select2").select2();
    }

    $("#data").DataTable();
    $('#back-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });

    var i = 0;
    while (i < 10) {
        var element = $("#datepicker");
        if (i > 0) {
            element = $("#datepicker" + i);
        }
        if (element.length) {
            element.datepicker({
                changeMonth: true,
                changeYear: true,
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 3,
                onSelect: function(selectedDate) {
                    var option = this.id == "from" ? "minDate" : "maxDate",
                        instance = $(this).data("datepicker"),
                        date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                    dates.not(this).datepicker("option", option, date);
                }
            });
        }
        i++;
    }

    if ($(".date-picker").length) {
        $(".date-picker").datepicker({
            format: 'dd-mm-yyyy',
            forceParse: false,
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3,
            onSelect: function(selectedDate) {
                var option = this.id == "from" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                dates.not(this).datepicker("option", option, date);
            }
        });
    }

    if ($("#MoA select[name=cooperation_id]").val() > 0) {
        var id = $("#MoA select[name=cooperation_id]").val();
        if (id > 0) {
            getCoopDetail(id)
        }
    }

    if ($("#SPK select[name=cooperation_id]").val() > 0) {
        var id = $("#SPK select[name=cooperation_id]").val();
        if (id > 0) {
            getCoopDetail(id)
        }
    }

    $("#MoA select[name=cooperation_id]").change(function () {
        var id = $("#MoA select[name=cooperation_id]").val();
        if (id > 0) {
            getCoopDetail(id)
        }
    });

    $("#SPK select[name=cooperation_id]").change(function () {
        var id = $("#SPK select[name=cooperation_id]").val();
        if (id > 0) {
            getCoopDetail(id)
        }
    });

    function getCoopDetail(id) {
        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $("input[name=mou_detail_partner_id]").val(data['partner_name']);
                $("input[name=mou_detail_subject_of_coop]").val(data['subject_of_coop']);
                $("input[name=mou_detail_sign_date]").val(data['sign_date']);
                $("input[name=mou_detail_end_date]").val(data['end_date']);
                $("input[name=mou_detail_usu_doc_no]").val(data['usu_doc_no']);
                $("input[name=mou_detail_partner_doc_no]").val(data['partner_doc_no']);
            }
        });
    }

    if ($("#tambah_kerma").length) {
        $("#tambah_kerma").validate({
            highlight: function (element) {
                $(element).parents('.form-group').addClass('has-error has-feedback');
            },
            unhighlight: function (element) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    $('.table-add').click(function (e) {
        e.preventDefault();
       if ($("#user-auth-table").length) {
            var v_table = $("#user-auth-table");
        }else if ($("#MoA #moa-table").length) {
            var v_table = $("#MoA #moa-table");
        }

        var $clone = v_table.find('tr.hide').clone(true).removeClass('hide table-line');
        if ($("#moa-table").length) {
            $clone.find("input").attr("disabled", false);

            $(":input").inputmask();
        }else if ($("#user-auth-table").length) {
            $clone.find("select").attr("disabled", false);
            $clone.find("select").addClass("select2");
        }
        v_table.find('table').append($clone);
        if ($("#user-auth-table").length) {
            $(".select2").select2();
        }
    });

    $('.table-addSPK').click(function (e) {
        e.preventDefault();
        if($("#SPK #spk-table").length){
            var v_table = $("#SPK #spk-table");
        } 

        var $clone = v_table.find('tr.hide').clone(true).removeClass('hide table-line');
        if($("#spk-table").length){
            $clone.find("input").attr("disabled", false);
            $(":input").inputmask();
        }
        v_table.find('table').append($clone);
    });

    $('.table-remove').click(function (e) {
        e.preventDefault();
        $(this).parents('tr').detach();
        sumTotalAmount();
    });

    if ($("#moa-table").length) {
        $(":input").inputmask();
    }

    $(document).on("keyup", "#moa-table input", function () {
         $(":input").inputmask();
    })

    $(document).on("keyup", "#spk-table input", function () {
         $(":input").inputmask();
    })

    $(document).on("keyup", "#moa-table input[name^=item_total_amount]", function () {
        sumTotalAmount();
    })

    if ($("#spk-table").length) {
        $(":input").inputmask();
    }

    $(document).on("keyup", "#spk-table input[name^=item_total_amount]", function () {
        sumTotalAmount();
    })

    function sumTotalAmount() {
        var contractElement = $("input[name=contract_amount]");
        var sum = 0;
        $("input[name^=item_total_amount]:visible").each(function () {
            var value = $(this).val();
            if (value != "") {
                value = value.replace(/\,/g, "");
                sum += parseInt(value);
            }
        });
        contractElement.val(sum)
    }

    $("#MoA select[name=unit]").change(function () {
        if ($(this).val() != null) {
            $.ajax({
                url: baseUrl + 'cooperations/ajax/get-study-program',
                data: {
                    faculty: $(this).val()
                },
                dataType: "json",
                success: function (data) {
                    var subUnitElement = $("#MoA select[name=sub_unit]");
                    subUnitElement.find("option").remove();
                    subUnitElement.append("<option value='' disabled selected>Pilih Sub Unit</option>")
                    subUnitElement.select2('data', null);
                    subUnitElement.select2({placeholder: "-- Pilih Sub Unit --"});
                    $.each(data, function (k, v) {
                        subUnitElement.append("<option value='" + v["name"] + "'>" + v["name"] + "</option>")
                    });
                    subUnitElement.trigger("chosen: updated");
                }
            });
        }
    });

    if ($(".search-employee").length) {
        var autocomp_opt = {
            source: function (request, response) {
                $.ajax({
                    url: baseUrl + '/users/ajax/search',
                    dataType: "json",
                    data: {
                        query: request.term,
                        limit: 10
                    },
                    success: function (data) {
                        var transformed = $.map(data, function (el) {
                            return {
                                label: el.label,
                                id: el.id,
                                uname: el.username,
                                full_name: el.full_name
                            };
                        });
                        response(transformed);
                    }
                });
            },
            select: function (event, ui) {
                $("input[name=user_id]").val(ui.item.id);
                $("input[name=username]").val(ui.item.uname);
                $("input[name=full_name]").val(ui.item.full_name);
                $('.search-employee').trigger('change');
            }
        };
        $(".search-employee").autocomplete(autocomp_opt);
    }

    $('#report_coop').on('submit', function (e) {
        e.preventDefault();
        var coop_type = $('#coop_type').val();
        var sign_date1 = $('#sign_date1').val();
        var sign_date2 = $('#sign_date2').val();
        var partner = $('#partner').val();
        $('.loading').fadeIn('slow');
        $('#result').fadeOut('slow');
        $.ajax({
            url     : baseUrl + 'report',
            type    : 'POST',
            dataType : 'json',
            data    : $('#report_coop').serialize(), '_token': $('meta[name=csrf-token]').attr('content'),
            success: function(response){
                console.log(response);
                $('.loading').fadeOut('slow');
                if(response.length>0){
                    $("#btn-download").fadeIn('slow');
                }

                $("#result").fadeIn('slow');

                var table='';
                var head = "<thead><tr>" +
                                "<td><b>No</b></td>" +
                                "<td><b>Instansi / Unit </b></td>" +
                                "<td><b>Jenis Kerjasama</b></td>" +
                                "<td><b>Bidang Kerjasama</b></td>" +
                                "<td><b>Unit Pelaksana</b></td>" +
                                "<td><b>Tanggal Tanda Tangan</b></td>" +
                                "<td><b>Tanggal Berakhir Kerjasama</b></td>" +
                            "</tr></thead>";
                table += head;
                var data = "";

                $.each(response, function(key, item) {
                    var tr = "";
                    tr += '<tr><td>'+ item.no+ '</td>'+
                            '<td>'+ item.partner+ '</td>'+
                            '<td>'+ item.coop_type+ '</td>'+
                            '<td>'+ item.area_of_coop+ '</td>'+
                            '<td>'+ item.unit+ '</td>'+
                            '<td>'+ item.sign_date+ '</td>'+
                            '<td>'+ item.end_date+ '</td>'+
                            '</tr>';

                    data += tr;
                });
                var tbody = "<tbody>"+ data +"</tbody>";
                table += tbody;
                // alert(tbody);

                $("#table-report").html(table);
                $("#table-report").dataTable();

                $("#btn-download").attr("href", baseUrl + "report/downloadExcel?coop_type=" + coop_type + "&sign_date1=" + sign_date1 + "&sign_date2=" + sign_date2 +"&partner=" +partner);
            },
            error:function(data){
                alert(data.status);
            }

        });
    });
});