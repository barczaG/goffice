<h2>Büfé rendelésfelvétel</h2>
<form id="form_bufe">
	<div class="input-prepend">
		<span class="add-on">Rendelés azonosító</span>
		<input class="" name="bufe_azon" id="prependedInput" type="text" placeholder="Asztal 1">
	</div>
	<div class="tetelek">
		<div class="row-fluid tetel">
			<div class="span4">
				<div class="input-prepend">
					<span class="add-on">Tétel</span>
					<input class="tetel_nev" name="m[1][nev]" id="prependedInput" type="text" placeholder="Tétel neve">
					<input type="hidden" class="tetel_id" name="m[1][tetel_id]">
				</div>
			</div>
			<div class="span2">
				<div class="input-append">
					<input class="input-mini db" name="m[1][db]" id="appendedInput" type="text">
					<span class="add-on mertekegyseg">Db</span>
				</div>
			</div>
			<div class="span3">
				<button class="btn btn-danger" id="valt">Töröl</button>
			</div>
		</div>
	</div>
</form>

<h2> Összesen: <span id="osszesen"></span></h2>
<div class="btn-group">
	<?if($rendeles_id AND !$szemelyzet_id):?>
	<button class="btn btn-large button_felvesz" data-tipus="{{rendeles_id}}">Felvesz</button>
	<?elseif($szemelyzet_id):?>
	<button class="btn btn-large button_felvesz" data-tipus="{{rendeles_id}}/{{szemelyzet_id}}">Felvesz</button>
	<?else:?>
	<button class="btn btn-large button_felvesz" data-tipus="felvesz">Felvesz</button>
	<button class="btn btn-large button_felvesz" data-tipus="kp">Kp</button>
	<button class="btn btn-large button_felvesz" data-tipus="kartya">Bankkártya</button>
	<?endif;?>
</div>

<script>
var sorok_szama=1;
$(function(){

	$('.button_felvesz').click(function(){
		$(this).button('loading');
		var form=$('#form_bufe').serializeObject();
		$.post('goffice/bufe_ajax/rendeles_felvesz/'+$(this).data('tipus'),form,function(data){
			top.location.href="goffice/aktiv";
		});
	});

	$('.db').live('keyup',function(){
		var ar=parseInt($(this).parents('.tetel').data('tetel_ar'))*parseInt($(this).val());
		$(this).parents('.tetel').data('ar',ar);
		ar_szamol();
	});


	$('.tetel_nev').live('keyup.autocomplete', function(){
		$(this).autocomplete({
			source: "goffice/bufe_ajax/tetel_lista",
			minLength: 2,
			focus: function( event, ui ) {
				$(this).val( ui.item.nev );
				return false;
			},
			select: function( event, ui ) {
				$(this).val( ui.item.nev );
				$(this).siblings('.tetel_id').val( ui.item.id );
				$(this).parents('.tetel').find('.mertekegyseg').text(ui.item.mertekegyseg);
				$(this).parents('.tetel').find('.db').val(1);
				var ar=parseInt(ui.item.ar);
				$(this).parents('.tetel').data('ar',ar);
				$(this).parents('.tetel').data('tetel_ar',ar);
				ar_szamol();
				if($(this).parents('.tetel').next('.tetel').length==0)
				{
					sor_hozzaad();
				}
				return false;
			}
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.append( "<a>" + item.nev +"</a>" )
			.appendTo( ul );
		};
	});

});

function ar_szamol()
{
	var ossz=0;
	$('.tetel').each(function(index){
		if($(this).data('ar'))
		{
			ossz+=parseInt($(this).data('ar'));
		}
		
	});
	$('#osszesen').text(number_format(ossz,0,',','.'));
	//console.log(osszesen);
}

function sor_hozzaad()
{
	sorok_szama++;
	var sor_html='<div class="row-fluid tetel">'+
	'<div class="span4">'+
	'<div class="input-prepend">'+
	'<span class="add-on">Tétel</span>'+
	'<input class="tetel_nev" name="m['+sorok_szama+'][nev]" id="prependedInput" type="text" placeholder="Tétel neve">'+
	'<input type="hidden" class="tetel_id" name="m['+sorok_szama+'][tetel_id]">'+
	'</div>'+
	'</div>'+
	'<div class="span2">'+
	'<div class="input-append">'+
	'<input class="input-mini db" name="m['+sorok_szama+'][db]" id="appendedInput" type="text">'+
	'<span class="add-on mertekegyseg">Db</span>'+
	'</div>'+
	'</div>'+
	'<div class="span3">'+
	'<button class="btn btn-danger" id="valt">Töröl</button>'+
	'</div>'+
	'</div>';
	$('.tetelek').append(sor_html);
}

</script>