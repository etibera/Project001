<!DOCTYPE html>
<html>
<head>
  <script src="https://cdn.tiny.cloud/1/4ht0sgq3apbnbyqvf9h73ef3o0i02niv8smfw8qympkohuyn/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
	<button onclick="content()"> Send Email </button> 
 <form method="post" action="somepage">
    <textarea id="myTextArea" class="mceEditor">I should buy a boat. </textarea>
</form>
  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'textpattern a11ychecker advcode casechange textcolor export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker image link lists toc emoticons layer pagebreak visualblocks colorpicker nonbreaking',
      toolbar: 'a11ycheck casechange forecolor backcolor checklist code export  table image imagetools link numlist bullist toc emoticons pagebreak visualblocks nonbreaking',
       textpattern_patterns: [
	     {start: '*', end: '*', format: 'italic'},
	     {start: '**', end: '**', format: 'bold'},
	     {start: '#', format: 'h1'},
	     {start: '##', format: 'h2'},
	     {start: '###', format: 'h3'},
	     {start: '####', format: 'h4'},
	     {start: '#####', format: 'h5'},
	     {start: '######', format: 'h6'},
	     {start: '1. ', cmd: 'InsertOrderedList'},
	     {start: '* ', cmd: 'InsertUnorderedList'},
	     {start: '- ', cmd: 'InsertUnorderedList'},
	     {start: '//brb', replacement: 'Be Right Back'}
	  ],pagebreak_split_block: true,
       toolbar_mode: 'floating', 
      tinycomments_mode: 'embedded',
       mode : "specific_textareas",
    })

    
  
  </script>
</body>
</html>
<script type="text/javascript">
	function content() {
    var data=tinyMCE.get('myTextArea').getContent();
    console.log(data);
}
</script>
