<!DOCTYPE html>
<html>
<head>
  <title>Combodate Format</title>
  <script src="{{ asset('plugins/jquery/jquery-2.1.4.min.js') }}" ></script>
  <script src="{{ asset('js/moment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/combodate.js') }}"></script>
</head>
<body>
<input type="text" id="date" data-format="DD-MM-YYYY" data-template="DD / MM / YYYY">

<script>
$(function(){
    $('#date').combodate({
      minYear: 1950,
      maxYear: moment().format('YYYY'),
      yearDescending: true,
      firstItem: 'name',
      errorClass: null,
      customClass: '',
    });    
console.log($('#date').val());
$('.year,.month,.day').on('change',function()
    {
      getdate = $('#date').combodate('getValue','DD-MM-YYYY');
      var today = new Date();
      dob = new Date(getdate);
      age = new Date(today - dob).getFullYear() - 1970;
      $('#bod').val(dob.toLocaleDateString());
      console.log(dob.toLocaleDateString())
      $('#age').val(age);
      console.log(age);
    });
});
</script>
</body>
</html>