<style>
  /*
	This is For Css Loader
*/
#cssloader{ position: fixed;display:none;  left: 0; top: 0; z-index: 9999999999; width: 100%; height: 100%; overflow: visible;background:rgba(0,0,0,0.7) }


#cssloader .loading-txt{
	font-size:25px;
	font-weight:800;
	color:white;
	text-align:center;
}




.sk-wave {
  margin: 40px auto;
  width: 150px;
  height: 100px;
  text-align: center;
  font-size: 20px; }
  .sk-wave .sk-rect {
    background-color: white;
    height: 100%;
    width: 8px;
    display: inline-block;
    -webkit-animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
            animation: sk-waveStretchDelay 1.2s infinite ease-in-out; }
  .sk-wave .sk-rect1 {
    -webkit-animation-delay: -1.2s;
            animation-delay: -1.2s; }
  .sk-wave .sk-rect2 {
    -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s; }
  .sk-wave .sk-rect3 {
    -webkit-animation-delay: -1s;
            animation-delay: -1s; }
  .sk-wave .sk-rect4 {
    -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s; }
  .sk-wave .sk-rect5 {
    -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s; }

@-webkit-keyframes sk-waveStretchDelay {
  0%, 40%, 100% {
    -webkit-transform: scaleY(0.4);
            transform: scaleY(0.4); }
  20% {
    -webkit-transform: scaleY(1.2);
            transform: scaleY(1.2); } }

@keyframes sk-waveStretchDelay {
  0%, 40%, 100% {
    -webkit-transform: scaleY(0.4);
            transform: scaleY(0.4); }
  20% {
    -webkit-transform: scaleY(1);
            transform: scaleY(1); } }

</style>

<script>
function showLoading(text=''){
  var display = 'Loading ...';
  if(text!=''){
    var display = text;
  }
  $('#cssloader').find('.loading-txt').html(display);
  $('#cssloader').show();
}
function hideLoading(){
  $('#cssloader').hide();
}
</script>

  <div id="cssloader" >
    <div class="sk-wave">
      <div class="sk-rect sk-rect1"></div>
      <div class="sk-rect sk-rect2"></div>
      <div class="sk-rect sk-rect3"></div>
      <div class="sk-rect sk-rect4"></div>
      <div class="sk-rect sk-rect5"></div>
    </div>
  <div class="col-md-12 loading-txt"> ... </div>
</div>