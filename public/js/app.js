
// import Filter from '../js/modules/Filter';
// new Filter(document.querySelector('.js-filter'))
////////////////////////////////////////////////////
// VIDEOPLAYER
///////////////////////////////////////////////////

var videoPlayer = document.getElementById("videoPlayer");
var myVideo = document.getElementById("myVideo");

function stopVideo() {
	videoPlayer.style.display = "none";
	myVideo.src = '';


}

function playVideo(file) {
	myVideo.src = file;
	myVideo.src += '?enablejsapi=1&autoplay=1'
	// myVideo.src += '&autoplay=1'
	videoPlayer.style.display = "block";
}

////////////////////////////////////////////////////
// BUTTON SCROLLUP
///////////////////////////////////////////////////
//Get the button:
mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () { scrollFunction() };

function scrollFunction() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		mybutton.style.display = "block";
	} else {
		mybutton.style.display = "none";
	}
}


// When the user clicks on the button, scroll to the top of the document
function topFunction() {
	document.body.scrollTop = 0; // For Safari

	document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

////////////////////////////////////////////////////
// INCREMENTATION PRODUCT IN SHOW DETAIL
///////////////////////////////////////////////////

// const $result = document.getElementById("result")
// document.getElementById("btnMore").addEventListener("click", e => {
// 	$result.innerHTML = parseInt($result.innerHTML) + 1
// })
// document.getElementById("btnLess").addEventListener("click", e => {
// 	if ($result.innerHTML < 1) {
// 		$result.innerHTML = 0
// 	} else {
// 		$result.innerHTML = parseInt($result.innerHTML) - 1

// 	}

// })

// var minus = document.getElementsByClassName('minus');
// var plus = document.getElementsByClassName('plus');
// var input = document.getElementsByClassName('qty');

// plus.onclick = function (e) {
// 	e.preventDefault();
// 	input.value++;
// }
// minus.onclick = function (e) {
// 	e.preventDefault();
// 	if (input.value > 1) input.value--;
// }
// function incrementation() {

// 	// // qty = qty.attributes.value.nodeValues;
// 	// console.log(qty)
// 	qty;
// 	console.log(qty)
// }

// function decrementation() {

// 	qty--;
// 	console.log(qty)
// }


////////////////////////////////////////////////////
// ANIMATION BTN AJOUT AU PANIER
///////////////////////////////////////////////////
var btn = document.getElementById("show-btn-add");
//console.log(btn.innerHTML);

function animationCart() {
	//console.log(btn.nextSibling);
	btn.innerHTML = "<b>Produit ajouté avec succès</b>";
	btn.style = "background-color : green; color : white;";
	btn.className += " scale-up-center";
}

//////////////////////////////////////////////////////////
//////////EVENEMENT CLICK SUR L IMAGE / MODAL ///////////
////////////////////////////////////////////////////////

// $(document).ready(function () {
$('#modal-image').hide();//on cache l image
$('#ma_modal').hide();
$modal = $('#ma_modal');

console.log($modal);

$('.product-container img').click(function () {
	//console.log($(this).attr('src'));//src de limage cliquée (this)
	$('#modal-image img').attr('src', $(this).attr('src'));//modal-image recupere le src de l'image cliquée
	$('#modal-image').show(200);// on show modal-image qui etait hide
	$('#ma_modal').show();
});

// + evenement click sur icone qui cache la modal 

$('#modal-image').on('click', function () {
	$('#modal-image').hide(1000);
	// $('#ma_modal').slideUp(1000);
	$('#ma_modal').hide(1000);
});
// });
