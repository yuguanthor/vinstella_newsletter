<?php
//<script src="https://cdn.ckeditor.com/4.12.1/full/ckeditor.js"></script>
?>
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

<script>
$(function(){
   $('.btn-remove-attachment').click(function(){
    var div = $(this).closest('.attachment-div');
    var name =  $(this).closest('.attachment-div').find('.input-attachment').attr('name');

    div.find('.input-attachment').attr('disabled','disabled');
    div.append('<input type="hidden" name="'+name+'_disabled" value="1">');
    div.find('.template_attachment').addClass('greyout');

    $(this).hide();
    div.find('.btn-allow-attachment').show();
  });

  $('.btn-allow-attachment').click(function(){
    var div = $(this).closest('.attachment-div');
    var name =  $(this).closest('.attachment-div').find('.input-attachment').attr('name');

    div.find('.input-attachment').removeAttr('disabled');
    div.find('input[name="'+name+'_disabled"]').remove();
    div.find('.template_attachment').removeClass('greyout');

    $(this).hide();
    div.find('.btn-remove-attachment').show();
  });

  CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';

  };
  CKEDITOR.replace('email-body',{
    height: 450,
    filebrowserBrowseUrl: "{{ asset('vendor/kcfinder/browse.php') }}",
    //filebrowserImageBrowseUrl : "{{ asset('vendor/cfinder/browse.php?opener=ckeditor&type=images') }}",
    //filebrowserFlashBrowseUrl: "{{ asset('vendor/cfinder/browse.php?opener=ckeditor&type=flash') }}",
    baseUrl: "{{url('asd')}}",
    filebrowserUploadUrl: "{{ asset('vendor/kcfinder/upload.php?opener=ckeditor&type=files&format=json') }}",
    filebrowserImageUploadUrl: "{{ asset('vendor/kcfinder/upload.php?opener=ckeditor&type=images&format=json') }}",
    filebrowserImageUploadUrl: "{{ asset('vendor/kcfinder/upload.php?opener=ckeditor&type=flash&format=json') }}",
    filebrowserUploadMethod: 'form'

  });


})//end of ready function

//function
function ck_append_text(type=''){
  var dynamic_code='';
  if(type=='name'){
    dynamic_code = '[CUSTOMER_NAME]';
  }else if(type=='email'){
    dynamic_code = '[CUSTOMER_EMAIL]';
  }else if(type=='group'){
    dynamic_code = '[CUSTOMER_GROUP]';
  }
  CKEDITOR.instances['email-body'].insertText(dynamic_code);
}


function TestMail(){
  showLoading('Sending Test Mail ...');
  for (instance in CKEDITOR.instances) {
    CKEDITOR.instances[instance].updateElement();
  }
  var formData = new FormData($('#FormEmail')[0]);
  $.ajax({
    url: "{{ url('/ajax/test_mail') }}",
    type: 'POST',
    data: formData,
    contentType : false,
    processData : false,
    success: function(data){
      if( !validateEmail(data) ){
        alert(data);
      }else{
        alert('Test Mail has been sent to '+data);
      }
      hideLoading();
    }
  });
}
</script>