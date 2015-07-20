function ready(){
	var div = document.createElement('div');
	var params = {'type':'button', 'label':'<?php echo $_REQUEST['label']?>', 'draggable': true};
	div.setAttribute('cg', JSON.stringify(params));
	div.setAttribute('style', '<?php echo $_REQUEST['style']?>');
	document.body.appendChild(div);
}

if (window.addEventListener){
	window.addEventListener('load', ready, false);
} else if (window.attachEvent){
	window.attachEvent('onload', ready);
}



