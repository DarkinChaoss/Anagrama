// [name] + [identifier] + .png
var name = $('#croppie-link').attr('data-name') == '' ? 'placeholder' : $('#croppie-link').attr('data-name');
var id = $('#croppie-link').attr('data-id') == '' ? '' : $('#croppie-link').attr('data-id');



$('#close_mdl').click(function(){
	$('.img_modal').css("display", "none");
})

$('.item-img').click(function(){
	$('.img_modal').css("display", "flex");

	$('.pro_img_large').hide();

})
	// Start upload preview image
$(".gambar").attr("src", "img/clienteAlterarSenha.png");
var $uploadCrop,
tempFilename,
rawImg,
imageId;
function readFile(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			rawImg = e.target.result;
			$uploadCrop.croppie('bind', {
				url: rawImg
			}).then(function(){
				//console.log('jQuery bind complete');
			});
		}
		reader.readAsDataURL(input.files[0]);
	}
	else {
		swal("Desculpe, seu navegador não suporta o FileReader API.");
	}
}

$uploadCrop = $('#upload-demo').croppie({
	viewport: {
		width: 360,
		height: 360,
	},
	enforceBoundary: false,
	enableExif: true
});

$('.item-img').on('change', function () { 
	imageId = $(this).data('id'); tempFilename = $(this).val();
	$('#cancelCropBtn').data('id', imageId); readFile(this); 
});


$('#cropImageBtn').on('click', function (ev) {
	$uploadCrop.croppie('result', {
		type: 'base64',
		format: 'png',
		size: {width: 400, height: 400},
	}).then(function (resp) {
		//$('#item-img-output').attr('src', resp);
		setTimeout(function(){
			$('.img_modal').css("display", "none");
		}, 500)
		

		$.post('croppie.upload', {'image':resp, name:name, id:id}, function(data){
			console.log(data)
		})
	
});
});