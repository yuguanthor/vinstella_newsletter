<style>
  .email-body{
    border:1px solid black;
    padding:20px;
    display: block;
    width: 100%;
    margin:0px auto;
  }
  img {
    max-width: 100%;
    max-height: 100%;
  }
</style>

<center>
  <h1> MAIL LOG - VIEW </h1>
  <h2>START</h2>
  <hr>
</center>

<div class="email-body">
  {!! $data->body !!}
</div>

<center>
  <hr>
  <h2>END</h2>
</center>


<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
<script>
$(function(){
  $('.email-body').css({'width':'700px'})
});
</script>