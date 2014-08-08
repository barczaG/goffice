<?=$pager?>
<ul class="thumbnails">
	<?foreach($iterator as $kep):?>
	<li class="span3">
		<a href="#" class="thumbnail">
			<img src="http://szalagavato.tv/cor/img/<?=$kep?>.jpg" alt="">
		</a>
	</li>
	<?endforeach;?>
</ul>
<?=$pager?>

<script>
$(function(){
	$('.navbar').remove();
	$('.footer p').text('Évkönyv 2013');

	$('.thumbnail').live('click',function(e){
		e.preventDefault();
		$(this).parent('li.span3').switchClass( "span3", "span", 1000 );
		$(this).parent('li.span').switchClass( "span", "span3", 1000 );
	});
	jQuery.ias({
		container : '.thumbnails',
		item: '.span3',
		pagination: '.pagination',
		next: '.pagination .next a',
		triggerPageThreshold:500,
		loader: '<img src="{{theme:image_url file="loading.gif"}}"/>'
	});
});

</script>
