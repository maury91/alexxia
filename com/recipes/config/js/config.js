var r_cur_lang = 'it-IT';
recipes_images=[];
recipe_prods=[];
var recipe_id = -1;
r_data={};
var langs = [];

function choose_img(a) {
	for (i in a) {
		//inserisci immagine a[i].n
		recipes_images.push(a[i].n);
		$('.images .thumbs').append($('<span></span>').addClass('thumb').css('background-image','url('+a[i].n+')').mouseenter(function() {
			$('.images .image').css('background-image',$(this).css('background-image'));
		}));
	}
}
function recipe_get_all_data() {
	return {name : $('#recip_name').html(),ingredients : $('#recipe').html(),preparation : $('#recip_preparation').html()};
}
function save_recipe(save_new) {
	r_data[r_cur_lang] = recipe_get_all_data();
	to_save = {difficulty:$('#difficulty').val(),tempo:$('#time').val(),images : recipes_images, langs : r_data,prods : recipe_prods};
	//COntrolli
	if (recipes_images.length<1)
		return alert('Seleziona almeno un\'immagine');
	var data_to_save = {add_recipe : to_save};
	if ((recipe_id!=-1)&&(save_new != true))
		data_to_save['edit'] = recipe_id;	
	ajax_request({
		type : 'component',
		data : data_to_save,
		url : 'recipes/config/recipes.php',
		success : function(d) {
			console.log(d);
			if (d.content.r == 'y') {
				recipe_id = d.content.id;
				$('.special_save').show().css('display','inline-block');
				$('.normal_save').hide();
				alert('Ricetta salvata');
			} else
				alert('Errore : '+d.content.err);
		}
	});
}
function del_product() {
	if (recipe_prods.indexOf(parseInt($(this).data('id')))!=-1) {
		recipe_prods.splice(recipe_prods.indexOf(parseInt($(this).data('id'))),1);
		$(this).closest('li').slideUp(600,function(){$(this).remove()});
	
	}
}
function select_product() {
	$('#recipe_products_win').hide();
	if (recipe_prods.indexOf(parseInt($(this).data('id')))==-1) {
		recipe_prods.push(parseInt($(this).data('id')));
		$('#recip_prods').append($('<li></li>').text('#'+$(this).data('id')+' '+$(this).text()).append($('<a></a>').addClass('img del').click(del_product).data('id',$(this).data('id'))));
	}
}
function open_cat() {
	ajax_request({
		type : 'component',
		data : {category : $(this).data('id')},
		url : 'recipes/config/recipes.php',
		success : function(d) {
			$('#recipe_products_win').html('<li class="title">Scegli il prodotto :</li>');
			for (i in d.content.prods) 
				$('#recipe_products_win').append($('<li></li>').text(d.content.prods[i].name).data('id',d.content.prods[i].id).click(select_product));
		}
	});
}

function load_recipe() {
	recipe_id = recipe_data.id;
	$('.special_save').show().css('display','inline-block');
	$('.normal_save').hide();
	$('#difficulty').val(recipe_data.difficulty);
	$('#time').val(recipe_data.tempo);
	for (i in recipe_data.langs)
		r_data[recipe_data.langs[i].lang] = {name : recipe_data.langs[i].name,ingredients : recipe_data.langs[i].ingredients,preparation : recipe_data.langs[i].preparation};
	$('#recip_name').html(r_data[r_cur_lang].name);
	$('#recipe').html(r_data[r_cur_lang].ingredients);
	$('#recip_preparation').html(r_data[r_cur_lang].preparation);
	for (i in recipe_data.images)
		choose_img([{n : recipe_data.images[i].url}]);
	recipe_prods = [];
	for (i in recipe_data.prods) {
		recipe_prods.push(parseInt(recipe_data.prods[i].id));
		$('#recip_prods').append($('<li></li>').text('#'+recipe_data.prods[i].id+' '+recipe_data.prods[i].name).append($('<a></a>').addClass('img del').click(del_product).data('id',recipe_data.prods[i].id)));
	}
}
$(function() {
	$('.special_save').hide();
	CKEDITOR.disableAutoInline = true;
	CKEDITOR.on( 'instanceCreated', function( event ) {
		var editor = event.editor,
			element = editor.element;
		if ( element.is( 'h1', 'h2', 'h3', 'span' ) || element.getAttribute( 'id' ) == 'taglist' ) {
			editor.on( 'configLoaded', function() {
				editor.config.removePlugins = 'colorbutton,find,flash,font,' +
					'forms,iframe,image,newpage,removeformat,' +
					'smiley,specialchar,stylescombo,templates,link,unlink,anchor';
				editor.config.toolbarGroups = [
					{ name: 'editing',		groups: [ 'basicstyles' ] },
					{ name: 'undo' },
					{ name: 'clipboard',	groups: [ 'selection', 'clipboard' ] }
				];
			});
		} else {
			editor.on( 'configLoaded', function() {
				editor.config.removePlugins = 'about';
			});
		}	
	});
	CKEDITOR.inline( document.getElementById( 'recipe' ) );
	CKEDITOR.inline( document.getElementById( 'recip_preparation' ) );
	$('.abutton').button();
	$('.img.flag').each(function(i,el){
		langs.push($(this).attr('title'));
		$(this).data('lang',$(this).attr('title')).removeAttr('title');
	}).click(function() {
		$('.img.flag').css('border-bottom','');
		$(this).css('border-bottom','2px solid #69F');
		r_data[r_cur_lang] = recipe_get_all_data();
		r_cur_lang=$(this).data('lang');
		if (r_data[r_cur_lang]) {
			$('#recip_name').html(r_data[r_cur_lang].name);
			$('#recipe').html(r_data[r_cur_lang].ingredients);
			$('#recip_preparation').html(r_data[r_cur_lang].preparation);
			//Caricamento dati
		} else {
			//Svuota tutti i campi
			$('#recip_name').html('Nome Ricetta ('+r_cur_lang+')');
			$('#recipe').html('Ingredienti della ricetta');
			$('#recip_preparation').html('<p>Scrivi qui la ricetta</p>');
		}
	});
	$('#recip_buy .abutton').click(function() {
		//Mostra lista dei prodotti
		ajax_request({
			type : 'component',
			data : {show_cats : ' '},
			url : 'recipes/config/recipes.php',
			success : function(d) {
				console.log(d);
				$('#recipe_products_win').html('<li class="title">Scegli la categoria :</li>').show();
				for (i in d.content.cats) {
					$('#recipe_products_win').append($('<li></li>').text(d.content.cats[i].name).data('id',d.content.cats[i].id).click(open_cat));
					for (j in d.content.cats[i].subs)
						$('#recipe_products_win').append($('<li></li>').addClass('second').text(d.content.cats[i].subs[j].name).data('id',d.content.cats[i].subs[j].id).click(open_cat));
				}
			}
		});
		
	})
	$('#recipe_save,#recipe_save_over').click(function(){save_recipe(false);});
	$('#recipe_save_new').click(function(){save_recipe(true);});
});