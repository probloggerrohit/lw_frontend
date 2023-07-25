jQuery(document).ready(function( $ ){
	if($('body').hasClass('post-type-products'))
		{
			 $('select#select-course').change(function(){
                var course_id = $(this).val();
                course_id = $.trim(course_id);
                if(course_id!='')
                {
                    var psid = $('input#post_ID').val();
                    /*var dw = $("select#select-course option:selected").text();
                    $('[name="post_title"]').val(dw);*/
                    var dataVariable = {
                        'action': 'lw_get_post_data',
                        'course_id': course_id,
                        'post_cid' : psid,
                    };
            
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: dataVariable, 
                        success: function (response) {
                            $('[name="post_title"]').val(response.post_title);
                            tinymce.get("content").setContent(response.post_content);
                            if(response.html)
                            {
                                $('div#postimagediv .inside').html(response.html);
                            }
                        }
                    });
                }
               
			});

            $('select#select-landing-page').change(function(){
                var course_id = $(this).val();
                course_id = $.trim(course_id);
                if(course_id!='')
                {
                    var psid = $('input#post_ID').val();
                    /*var dw = $("select#select-course option:selected").text();
                    $('[name="post_title"]').val(dw);*/
                    var dataVariable = {
                        'action': 'lw_get_post_data',
                        'course_id': course_id,
                        'post_cid' : psid,
                    };
            
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: dataVariable, 
                        success: function (response) {
                            $('[name="post_title"]').val(response.post_title);
                            tinymce.get("content").setContent(response.post_content);
                            if(response.html)
                            {
                                $('div#postimagediv .inside').html(response.html);
                            }
                        }
                    });
                }
                
			});

            $('select#select-community').change(function(){
                var course_id = $(this).val();
                course_id = $.trim(course_id);
                if(course_id!='')
                {
                    var psid = $('input#post_ID').val();
                    /*var dw = $("select#select-course option:selected").text();
                    $('[name="post_title"]').val(dw);*/
                    var dataVariable = {
                        'action': 'lw_get_post_data',
                        'course_id': course_id,
                        'post_cid' : psid,
                    };
            
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: dataVariable, 
                        success: function (response) {
                            $('[name="post_title"]').val(response.post_title);
                            tinymce.get("content").setContent(response.post_content);
                            if(response.html)
                            {
                                $('div#postimagediv .inside').html(response.html);
                            }
                        }
                    });
                }
                
			});
		}
});