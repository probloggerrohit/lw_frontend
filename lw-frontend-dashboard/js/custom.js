jQuery(document).ready(function($){

$('form.filter_fom').submit(function(){
    $('.loading_footer').fadeIn();
    var fmdt = $(this).serialize();
    var data = {
        'action': 'filter_pds',
        'form_data': fmdt
    };

    jQuery.post(ajx.ajaxurl, data, function(response) {
        if(response.status)
        {
            $('.cstm_loop .elementor-loop-container.elementor-grid').html(response.html);
            $('.loading_footer').fadeOut('slow');
        }
    });
    return false;
});

$('#videoUpload').change(function(){
    const file = this.files[0];
    console.log(file);
    if (file){
        let reader = new FileReader();
        reader.onload = function(event){
        console.log(event.target.result);
        $('.vid_prev').attr('src', event.target.result);
    }
    reader.readAsDataURL(file);
    }
});
//     $('#videoUpload').change(function(){
//      let file = event.target.files[0];
//      let blobURL = URL.createObjectURL(file);
//      document.querySelector("video").src = blobURL;
//     });
$(document).on('click', '.single_csect h4', function(){
    $(this).parent().toggleClass('active_ac');
});
$(document).on('click', 'a.act_link', function(){
    var tk = $(this).parent().parent();
    var pu = $(this).parent().parent().parent();
    var cl = tk.clone();
    /*cl.find('.acc_title').append('<a class="remove_btn" href="#"><i class="far fa-times-circle"></i></a>');*/
    cl.appendTo(pu);
    return false;
});
$(document).on('click', 'a.act_link2', function(){
    var op = $(this).data('index');
    var p = op+1;
    var tk = $(this).parent().parent();
    var pu = $(this).parent().parent().parent();
    var cl = tk.clone();
    var tstl = $(this).data('title');
    var dm = "Learning Path";
    if(tstl!='')
    {
        dm = tstl;
    }
    cl.find('.acc_title').html('<i class="fas fa-arrows-alt"></i>'+dm+' '+p+' <a class="act_link2" href="#"><i class="fas fa-plus-circle"></i></a><a class="remove_btn" href="#"><i class="far fa-times-circle"></i></a><i class="fas fa-chevron-down"></i>');
    cl.find('.sct_ttl').val(dm+' '+p);
    cl.appendTo(pu);

    return false;
});
$(document).on('change', 'select#SortSelect', function(){
    var lim = $('.filter_fom').serialize();
    $('.loading_footer').fadeIn();
    var sor = $(this).val();
    var data = {
        'action': 'filter_pds',
        'form_data': lim,
        'sort': sor
    };

    jQuery.post(ajx.ajaxurl, data, function(response) {
        if(response.status)
        {
            $('.cstm_loop .elementor-loop-container.elementor-grid').html(response.html);
            $('.loading_footer').fadeOut('slow');
        }
    });
    return false;
});
$(document).on('click', 'a.remove_btn', function(){
    var cd = $(this).parent().parent();
    var cnf = confirm("Are you sure?");
    if(cnf)
    {
        cd.remove();
    }
    return false;
});
$(document).on('click', '.adbt', function(){
    var ti = $(this).parent().find('.dyn_area');
    var yk = ti.find('.tbx').first().clone();
    yk.find('input').val('');
    yk.appendTo(ti);
    return false;
});
$(document).on('click', 'button.button.smbtn', function(){
    var dvl = jQuery('input.sldt.flatpickr-input').val();
    if(dvl!='')
    {
        var timeInMillis = Date.parse(dvl);
        $('.schedule_stamp').val(timeInMillis);
        $('.active_cs').val(1);
        $('.schedule_popup').fadeOut();
        $('.jet-form-builder .Send-Now').trigger('click');
    }
    return false;
});
$(document).on('click', '.dr_btn_sd', function(){
    $('.schedule_popup').slideToggle();
    return false;
});
$(document).on('click', 'a.clz_pop', function(){
    $('.schedule_popup').fadeOut();
    return false;
});
$(document).on('click', 'a.clz_pop_ne', function(){
    $('.test_email_popup').fadeOut();
    return false;
});
$(document).on('click', '.smbtn2', function(){
    var emk = $('.eml_fd').val();
    var cid = $('input.jet-form-builder__field.hidden-field[name="post_id"]').val();
    var data = {
        'action': 'send_tst_email',
        'email_data': emk,
        'cid': cid
    };

    jQuery.post(ajx.ajaxurl, data, function(response) {
       if(response.status)
       {
            alert(response.msg);
            $('.test_email_popup').fadeOut();
       }
       else
       {
            alert(response.msg);
       }
    });
    return false;
});
$(document).on('click', '.jet-nav__item-5822.jet-nav__item a', function(){
    $('.jet-form-builder .Send-Now').trigger('click');
    return false;
});
$(document).on('click', '.jet-nav__item-5824.jet-nav__item a', function(){
    $('input.jet-form-builder__field.hidden-field[name="hidden_field_name_copy"]').val('draft');
    $('form.jet-form-builder.layout-column.submit-type-reload[data-form-id="5136"] .jet-form-builder-row.field-type-submit-field button').trigger('click');
    return false;
});
$(document).on('change', '.sel_catg', function(){
    var catid = $(this).val();
    var data = {
        'action': 'get_sub_terms',
        'term_id': catid,
    };

    jQuery.post(ajx.ajaxurl, data, function(response) {
       if(response.status)
       {
            $('.dyn_sbc').html(response.html);
       }
    });
    return false;
});
$(document).on('click', '.jet-nav__item-5823 a', function(){
    $('.test_email_popup').slideToggle();
    return false;
});
$(document).on('click', '.rmbt', function(){
    var ti = $(this).parent().find('.dyn_area');
    var tq = confirm("Are you sure?");
    if(tq)
    {
        ti.find('.tbx').last().remove();
    }
    /*yk.find('input').val('');
    yk.appendTo(ti);*/
    return false;
});
});


jQuery(document).ready(function ($) {
var counter = 2;
$(".addButton").click(function () {
    if (counter > 10) {
        alert("Only 10 textboxes allow");
        return false;
    }
    var dbox = jQuery(this).parent().find('.nxbox');
    var newTextBoxDiv = $(document.createElement('div')).attr("class", 'TextBoxDiv' + counter);
    newTextBoxDiv.after().html('<input class="txt-field" type="text" name="textbox' + counter + '" id="textbox' + counter + '" value=""style="margin-bottom:10px;" > ');
    newTextBoxDiv.appendTo(dbox);
    counter++;

});
$(".removeButton").click(function () {
    if (counter == 1) {
        alert("Field Required");
        return false;
    }
    counter--;
    $(".TextBoxDiv" + counter).remove();
});
});


jQuery(document).ready(function($){
$('select.drop3').on('change', function(){
    var demovalue = $(this).val();
    if(demovalue!='Categoty')
    {
        $("select.drop4").show();
    }
    else
    {
        $("select.drop4").hide();
    }
});
});

jQuery(document).ready(function( $ ){
    /*$('a.open_drp').click(function(){*/
    $(document).on('click', 'a.open_drp', function(){
        $(this).parent().find('.drp_mn').slideToggle();
        return false;
    });
    $(document).on('click', 'a.delpt', function(){
        var cnf = confirm("Are you sure?");
        if(cnf)
        {
            $(this).parent().remove();
        }
        return false;
    });
    $(document).on('click', 'a.edt_eml', function(){
        $('.cstm_fd').toggleClass('active_em');
        return false;
    });
    /*
    $(document).on('click', '.save_btn', function(){
        if($('.cstm_fd').hasClass('active_em'))
        {
            var emp = $('.eml_fld').val();
            var data = {
                'action': 'email_verify',
                'email_data': emp
            };

            jQuery.post(ajx.ajaxurl, data, function(response) {
                console.log(response);
            });
        }
        return false;
    });
    */
   $(document).on('click', 'a.dr_btn.button', function(){
        $('input.jet-form-builder__field.hidden-field[name="hidden_field_name_copy"]').val('draft');
        $('form.jet-form-builder.layout-column.submit-type-reload[data-form-id="5136"] .jet-form-builder-row.field-type-submit-field button').trigger('click');
        return false;
   });
   if($('body').hasClass('page-id-6041'))
   {
        /*
        jQuery('.prt_sections').sortable({
            handle: 'i.icon-move',
        }); */
        var adjustment;

        $(".prt_sections").sortable({
            group: 'simple_with_animation',
            pullPlaceholder: false,
            // animation on drop
            onDrop: function  ($item, container, _super) {
                var $clonedItem = $('<li/>').css({height: 0});
                $item.before($clonedItem);
                $clonedItem.animate({'height': $item.height()});

                $item.animate($clonedItem.position(), function  () {
                $clonedItem.detach();
                _super($item, container);
                });
            },

            // set $item relative to cursor position
            onDragStart: function ($item, container, _super) {
                var offset = $item.offset(),
                    pointer = container.rootGroup.pointer;

                adjustment = {
                left: pointer.left - offset.left,
                top: pointer.top - offset.top
                };

                _super($item, container);
            },
            onDrag: function ($item, position) {
                $item.css({
                left: position.left - adjustment.left,
                top: position.top - adjustment.top
                });
            }
        });
   }
});
