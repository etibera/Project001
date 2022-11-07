

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>without bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
  </head>
  <body>
    <div id="summernote"></div>
     <button id="button-serial" class="btn btn-primary"> <i class="fa fa-plus-circle"></i> save</button>
       <input type="text" id="summernoteval" onchange="summernotesetval()">
    <script>
      $('#summernote').summernote({
        placeholder: 'Hello stand alone ui',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
       $(document).delegate('#button-serial', 'click', function() {
        var markupStr = $('#summernote').summernote('code');
        $('#summernoteval').val(markupStr);
        //console.log(markupStr);
        $('#summernote').summernote('code', '');
    })
       function summernotesetval(){
         var txtval = $('#summernoteval').val();
        //console.log(markupStr);
        $('#summernote').summernote('code', txtval);
      }
    </script>
  </body>
</html>

