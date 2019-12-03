<?php 

	// Recebe um post ou get com a url da imagem e retorna uma
	// div de 400X400 com posição fixa, z-index: 1000000, com box-shadow 
	// tipo material design.
	$url = isset($_REQUEST['img_url']) ? $_REQUEST['img_url'] : 'img_pro/placeholder.png';
	$image_exists = file_exists($_REQUEST['img_url']);

	if(!$image_exists){
		$url = 'img_pro/placeholder.png';
	}

	// opcional, se existir retorna antes do html separado por ***
	if(isset($_REQUEST['extra']) && $_REQUEST['extra'] != 0){
		echo $_REQUEST['extra']."***";
	}
?>

<style type="text/css">
	.img_pop_up_main{
		position: fixed;
		z-index: 1000000;
		width: 400px;
		height: 400px;
		background-color: white;
		box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
	}
	.close_img{
		width: 100%;
		position: absolute;
		top: 10px;
		margin-right: 10px;
		display: flex;
		justify-content: flex-end;
		background-color: transparent;
		font-size: 1.1em;
	}
	.close_img p{
		padding-right: 1em;
		cursor: pointer;
	}
	.close_img p:hover{
		color: blue;
	}
</style>

<div class="img_pop_up_main exist">
	<div class="close_img"><p>X</p></div>
	<img id="img_big" src="<?php echo $url ?>">
</div>