<?php
$__prod_id 	= 'ID Prodotto';
$__prod_q 	= 'Quantit&agrave;';
$__prod_p 	= 'Prezzo';
$__prod_tp 	= 'Prezzo Totale';
$__prod_pi 	= 'pezzi';
$__prod_w 	= 'Peso';
$__prod_bn 	= 'Compra Ora';
$__prod_cart= 'Aggiungi al carrello';
$__prod_det = 'Dettagli Prodotto';
$__prod_shp = 'Spedizioni';
$__prod_pay = 'Pagamenti';
$__prod_nf 	= 'Prodotto non trovato!';
$__cart_emp = 'Il tuo carrello &egrave; vuoto';
$__cart_empt= 'Il tuo carrello &egrave; vuoto. Per aggiungere articoli al tuo carrello naviga sul sito, quando trovi un articolo che ti interessa, clicca su "Aggiungi al carrello".';
$__cart_rem = ' &egrave; stato rimosso';
$__remove 	= 'Rimuovi';
$__cart 	= 'Carrello';
$__proced	= 'Procedi all\'acquisto';
$__next		= 'Continua';
$__cart_removed = 'L\'articolo &egrave; stato rimosso';
$__step_addr= 'INDIRIZZO';
$__step_ship= 'SPEDIZIONE';
$__step_pay	= 'PAGAMENTO';
$__step_sum = 'RIEPILOGO';
$__invalid_fname= 'Nome e cognome non validi';
$__invalid_telephone= 'Numero non valido';
$__title_addr	= 'Seleziona un indirizzo di consegna';
$__fname 	= 'Nome e cognome';
$__addr 	= 'Indirizzo';
$__addr2 	= 'Indirizzo (seconda riga)';
$__city		= 'Citt&agrave';
$__province = 'Provincia';
$__cap 		= 'CAP';
$__state	= 'Stato';
$__telephone= 'Telefono';
$__title_ship	= 'Scegli la modalit&agrave; di spedizione';
$__sped_info	= 'Informazioni sulla spedizione';
$__ship_to	= 'Spedizione a  %fname%, %address%, %city%, %province%, %cap% %state%';
$__edit_del = 'Modifica o rimuovi prodotti';
$__ship_mode= 'Scegli una modalit&agrave; di spedizione';
$__ship_det	= '%time% giorni lavorativi (Spedizione via %modal%)';
$__title_pay= 'Come vuoi pagare?';
$__title_sum= 'Riepilogo';
$__order	= 'Ordina';
$__sum_ship = 'Modalit&agrave; di spedizione';
$__sum_pay	= 'Metodo di pagamento';
$__tot		= 'Totale';
$__redirect = 'Reindirizzamento a %s';
$__prods 	= 'Prodotti';
$__creator	= 'Produttore';
$__order_conf_sub	= 'Conferma ordine';
$__order_conf_html	= '<h1>%sitename%</h1>
<h3>Complimenti per l\'acquisto!</h3>
<br/>
<hr/>
Gentile %fname%,<br/>
<br/>
Ci auguriamo che tu sia soddisfato dell\'acquisto. Adesso non ti resta che effettuare il pagamento. Paga subito in modo da riceve l\'ordine pi&ugrave; rapidamente.<br/>
<br/><br/>
<a href="%pay_link%" target="_blank" %button_style%>Paga subito</a>
<div class="order_details">
	<h3>Info sull\'ordine</h3>
	<hr/>
	%prod_list%<br/>
indirizzo di spedizione:
	%shipment_address%
</div>';


$__invoice_html	= '<h1>%sitename%</h1>
<h3>Complimenti per l\'acquisto!<br/>
Non dimenticare di pagare l\'ordine.</h3><br/>
<hr/>
Gentile %fname%,<br/>
<br/><br/>
Ci auguriamo che tu sia soddisfato dell\'acquisto. Paga subito in modo da riceve l\'ordine pi&ugrave; rapidamente.<br/>
Ricorda di pagare entro %num% giorni, altrimenti l\'ordine verrà automaticamente annullato.
<br/>
<br/><br/>
<a href="%pay_link%" target="_blank" %button_style%>Paga subito</a>
<div class="order_details">
	<h3>Info sull\'ordine</h3>
	<hr/>
	%prod_list%
	<br/>
indirizzo di spedizione:
	%shipment_address%
</div>';

$__receive_payment_html	= '<h1>%sitename%</h1>
<h3>Complimenti per l\'acquisto!<br/>
Il pagamento è stato effettuato con successo.</h3><br/>
<hr/>
Gentile %fname%,<br/>
<br/><br/>
abbiamo ricevuto il pagamento di €%tot%<br/>
Pagato tramite %paym_metod%
<br/>
La spedizione verrà processata entro 3 giorni lavorativi all\'indirizzo
<br/>
	%shipment_address%.<hr/>';

$__shipment_html	= '<h1>%sitename%</h1>
<h3>I prodotti sono stati spediti!</h3><br/>
<hr/>
Gentile %fname%,<br/>
<br/><br/>
il suo ordine è stato conrassegnato come spedito,<br/>
riceverà  
<br/>
La spedizione verrà processata entro 3 giorni lavorativi.<hr/>';


$__mail_footer='
Questa email ti è stata inviata tramite la piattaforma %sitename%. <br/>
Per qualsiasi domanda al riguardo, consulta le Regole sulla Privacy e l\'Accordo per gli utenti.<br/>
Puoi segnalare questo messaggio come email non richiesta (di spamming/contraffatta).<br/>
Quest\'email è stata inviata da %sitename%, che si riserva il diritto di utilizzare i propri affiliati per la fornitura dei servizi.<br/>
Copyright © 2013 %sitename%. Tutti i diritti riservati. I marchi registrati e i segni distintivi sono di proprietà dei rispettivi titolari.<br/>
';


$__order_conf_prod_html = '<table height="60px"><tr><td rowspan="3" width="60px"><img src="%image%" width="60px" style="max-height:60px"/></td><td><span %name_style%>%name%</span></tr>
<tr><td><span %price_style%>%price%</span></td></tr><tr><td><span %quantity_style%>Quantit&agrave; : %quantity%</span></td></tr></table>';
/*$__order_conf_html	= '<h1>%sitename%</h1>
<h3>Ecco la conferma dell\'ordine</h3>
<hr/>
<br/>
Gentile %fname%,<br/>
<br/>
di seguito un riepilogo del tuo recente ordine. Puoi vedere i <a href="%sum_link%">dettagli dell\'ordine</a> aggiorna anche nel tuo profilo personale.<br/>
Grazie per aver fatto acquisti su %sitename%!<br/>

%prods_list%

<br/>'*/
$__state_list = array(
	'IT' => 'Italia',
	'AF' => 'Afghanistan',
	'AL' => 'Albania',
	'DZ' => 'Algeria',
	'AD' => 'Andorra',
	'AO' => 'Angola',
	'AI' => 'Anguilla',
	'AQ' => 'Antartide',
	'AG' => 'Antigua-Barbuda',
	'AN' => 'Antille Olandesi',
	'SA' => 'Arabia Saudita',
	'AR' => 'Argentina',
	'AM' => 'Armenia',
	'AW' => 'Aruba',
	'AU' => 'Australia',
	'AT' => 'Austria',
	'AZ' => 'Azerbaijan',
	'BS' => 'Bahamas',
	'BH' => 'Bahrain',
	'BD' => 'Bangladesh',
	'BB' => 'Barbados',
	'BE' => 'Belgio',
	'BZ' => 'Belize',
	'BJ' => 'Benin',
	'BM' => 'Bermuda',
	'BT' => 'Bhutan',
	'BY' => 'Bielorussia',
	'BO' => 'Bolivia',
	'BQ' => 'Bonaire, Saint Eustatius e Saba',
	'BA' => 'Bosnia-Erzegovina',
	'BW' => 'Botswana',
	'BR' => 'Brasile',
	'BN' => 'Brunei Darussalam',
	'BG' => 'Bulgaria',
	'BF' => 'Burkina Faso',
	'BI' => 'Burundi',
	'KH' => 'Cambogia',
	'CM' => 'Cameroon',
	'CA' => 'Canada',
	'CV' => 'Capo Verde',
	'TD' => 'Ciad',
	'CL' => 'Cile',
	'CN' => 'Cina',
	'CY' => 'Cipro',
	'CO' => 'Colombia',
	'KM' => 'Comoros',
	'CG' => 'Congo',
	'CD' => 'Congo, Repubblica Democratica del',
	'KR' => 'Corea, Repubblica di',
	'CI' => 'Costa d\'Avorio',
	'CR' => 'Costa Rica',
	'HR' => 'Croazia',
	'CW' => 'Curaçao',
	'DK' => 'Danimarca',
	'DM' => 'Dominica',
	'EC' => 'Ecuador',
	'EG' => 'Egitto',
	'SV' => 'El Salvador',
	'AE' => 'Emirati Arabi Uniti',
	'ER' => 'Eritrea',
	'EE' => 'Estonia',
	'ET' => 'Etiopia',
	'RU' => 'Federazione Russa',
	'FJ' => 'Fiji',
	'PH' => 'Filippine',
	'FI' => 'Finlandia',
	'FR' => 'Francia',
	'GA' => 'Gabon',
	'GM' => 'Gambia',
	'GE' => 'Georgia',
	'GS' => 'Georgia del Sud e Isole Sandwich Meridionali',
	'DE' => 'Germania',
	'GH' => 'Ghana',
	'JM' => 'Giamaica',
	'JP' => 'Giappone',
	'GI' => 'Gibilterra',
	'DJ' => 'Gibuti',
	'JO' => 'Giordania',
	'GR' => 'Grecia',
	'GD' => 'Grenada',
	'GL' => 'Groenlandia',
	'GP' => 'Guadalupa',
	'GU' => 'Guam',
	'GT' => 'Guatemala',
	'GG' => 'Guernsey',
	'GF' => 'Guiana Francese',
	'GN' => 'Guinea',
	'GQ' => 'Guinea Equatoriale',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HT' => 'Haiti',
	'HN' => 'Honduras',
	'HK' => 'Hong Kong',
	'IN' => 'India',
	'ID' => 'Indonesia',
	'IQ' => 'Iraq',
	'IE' => 'Irlanda',
	'IS' => 'Islanda',
	'BV' => 'Isola Bouvet',
	'IM' => 'Isola di Man',
	'CX' => 'Isola di Natale',
	'HM' => 'Isola Heard e Isole Mcdonald',
	'NF' => 'Isola Norfolk',
	'AX' => 'Isole Åland',
	'KY' => 'Isole Cayman',
	'CC' => 'Isole Cocos (Keeling)',
	'CK' => 'Isole Cook',
	'FK' => 'Isole Falkland (Malvine)',
	'FO' => 'Isole Faroe',
	'MP' => 'Isole Marianne Settentrionali',
	'MH' => 'Isole Marshall',
	'UM' => 'Isole Minori Esterne degli Stati Uniti',
	'SB' => 'Isole Salomone',
	'TC' => 'Isole Turks e Caicos',
	'VG' => 'Isole Vergini, Britanniche',
	'VI' => 'Isole Vergini, Statunitensi',
	'IL' => 'Israele',
	'LY' => 'Jamahiriya Araba di Libia',
	'JE' => 'Jersey',
	'KZ' => 'Kazakistan',
	'KE' => 'Kenya',
	'KG' => 'Kirghizistan',
	'KI' => 'Kiribati',
	'KW' => 'Kuwait',
	'LS' => 'Lesotho',
	'LV' => 'Lettonia',
	'LB' => 'Libano',
	'LR' => 'Liberia',
	'LI' => 'Liechtenstein',
	'LT' => 'Lituania',
	'LU' => 'Lussemburgo',
	'MO' => 'Macao',
	'MK' => 'Macedonia, ex Repubblica Jugoslava di',
	'MG' => 'Madagascar',
	'MW' => 'Malawi',
	'MV' => 'Maldive',
	'MY' => 'Malesia',
	'ML' => 'Mali',
	'MT' => 'Malta',
	'MA' => 'Marocco',
	'MQ' => 'Martinica',
	'MR' => 'Mauritania',
	'MU' => 'Mauritius',
	'YT' => 'Mayotte',
	'MX' => 'Messico',
	'FM' => 'Micronesia, Stati Federati di',
	'MD' => 'Moldavia, Repubblica di',
	'MC' => 'Monaco',
	'MN' => 'Mongolia',
	'ME' => 'Montenegro',
	'MS' => 'Montserrat',
	'MZ' => 'Mozambico',
	'MM' => 'Myanmar',
	'NA' => 'Namibia',
	'NR' => 'Nauru',
	'NP' => 'Nepal',
	'NI' => 'Nicaragua',
	'NE' => 'Niger',
	'NG' => 'Nigeria',
	'NU' => 'Niue',
	'NO' => 'Norvegia',
	'NC' => 'Nuova Caledonia',
	'NZ' => 'Nuova Zelanda',
	'OM' => 'Oman',
	'NL' => 'Paesi Bassi',
	'PK' => 'Pakistan',
	'PW' => 'Palau',
	'PA' => 'Panama',
	'PG' => 'Papua Nuova Guinea',
	'PY' => 'Paraguay',
	'PE' => 'Perù',
	'PN' => 'Pitcairn',
	'PF' => 'Polinesia Francese',
	'PL' => 'Polonia',
	'PR' => 'Porto Rico',
	'PT' => 'Portogallo',
	'QA' => 'Qatar',
	'GB' => 'Regno Unito',
	'CZ' => 'Repubblica Ceca',
	'CF' => 'Repubblica Centrafricana',
	'LA' => 'Repubblica Democratica Popolare del Laos',
	'DO' => 'Repubblica Dominicana',
	'RE' => 'Riunione',
	'RO' => 'Romania',
	'RW' => 'Ruanda',
	'EH' => 'Sahara Occidentale',
	'BL' => 'Saint Barthelemy',
	'KN' => 'Saint Kitts e Nevis',
	'MF' => 'Saint Martin',
	'PM' => 'Saint Pierre e Miquelon',
	'VC' => 'Saint Vincent e Grenadine',
	'WS' => 'Samoa',
	'AS' => 'Samoa Americane',
	'SM' => 'San Marino',
	'SH' => 'Sant\'Elena, Ascensione e Tristan da Cunha',
	'LC' => 'Santa Lucia',
	'VA' => 'Santa Sede (Stato della Città del Vaticano)',
	'ST' => 'São Tomé e Príncipe',
	'SN' => 'Senegal',
	'RS' => 'Serbia',
	'SC' => 'Seychelles',
	'SL' => 'Sierra Leone',
	'SG' => 'Singapore',
	'SX' => 'Sint Maarten',
	'SK' => 'Slovacchia',
	'SI' => 'Slovenia',
	'SO' => 'Somalia',
	'ES' => 'Spagna',
	'LK' => 'Sri Lanka',
	'US' => 'Stati Uniti',
	'ZA' => 'Sudafrica',
	'SR' => 'Suriname',
	'SJ' => 'Svalbard e Jan Mayen',
	'SE' => 'Svezia',
	'CH' => 'Svizzera',
	'SZ' => 'Swaziland',
	'TJ' => 'Tagikistan',
	'TH' => 'Tailandia',
	'TW' => 'Taiwan',
	'TZ' => 'Tanzania, Repubblica Unita di',
	'TF' => 'Territori Francesi Meridionali',
	'IO' => 'Territorio Britannico dell\'Oceano Indiano',
	'TL' => 'Timor Est',
	'TG' => 'Togo',
	'TK' => 'Tokelau',
	'TO' => 'Tonga',
	'TT' => 'Trinidad e Tobago',
	'TN' => 'Tunisia',
	'TR' => 'Turchia',
	'TM' => 'Turkmenistan',
	'TV' => 'Tuvalu',
	'UA' => 'Ucraina',
	'UG' => 'Uganda',
	'HU' => 'Ungheria',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VU' => 'Vanuatu',
	'VE' => 'Venezuela',
	'VN' => 'Vietnam',
	'WF' => 'Wallis e Futuna',
	'YE' => 'Yemen',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe');
?>