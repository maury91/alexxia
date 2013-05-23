<?php
/**
 *	Installation (lang file) for ALExxia
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
	$req = array(
		'version' => 'Versione PHP >= 5.1',
		'rglobals' => 'Register Globals OFF',
		'zlib' => 'Supporto Zlib Compression',
		'db' => 'Supporto Database: (mysql, mysqli)',
		'json' => 'Supporto JSON',
		'write' => 'config/ e cache/ scrivibile',
		'http' => 'Curl o allow_url_fopen',
		'output' => 'Output Buffering',
		'bcrypt' => 'CRYPT_BLOWFISH attivo'
	);
	$__req = 'Requisiti';
	$__req_d = 'Per poter installare ALExxia hai bisogno che tutti i Requisiti qui elencati siano contrassegnati da un SI, se qualche requisito &egrave; indicato come NO sei pregato di correggerlo per poter iniziare l\'installazione';
	$rac = array(
		'magic' => 'Magic Quotes GPC OFF',
		'safe' => 'Safe Mode OFF',
		'error' => 'Display Errors OFF',
		'upload' => 'Caricamento Files',
		'double' => 'Doppio supporto database'
	);
	$__rac = 'Impostazioni Consigliate';
	$__rac_d = 'Queste impostazioni sono consigliate, tuttavia ALExxia &egrave; in grado di funzionare discrettamente bene anche senza.';
	$__y = 'Si';
	$__n = 'No';
	$__lang_sel = 'Seleziona la lingua';
	$__continue = 'Continua';
	$__configuration = 'Configurazione generale';
	$__site_with = 'Il mio nuovo sito con ALExxia';
	$__name = 'Nome Sito';
	$__name_d = 'Inserisci il nome del tuo sito';
	$__desc = 'Descrizione';
	$__desc_d = 'Inserisci una descrizione del sito che verr&agrave; utilizzata dai motori di ricerca. Generalmente, &egrave; ottimale un massimo di 20 parole.';
	$__email = 'Email Fondatore';
	$__email_d = 'Inserisci il tuo indirizzo email, questo sar&agrave; l\'indirizzo email del fondatore del sito.';
	$__nick = 'Nick Fondatore';
	$__nick_d = 'Puoi cambiare il nick del fondatore, normalmente &egrave; <b>admin</b>';
	$__pass = 'Password Fondatore';
	$__pass_d = 'Imposta la password dell\'account del fondatore del sito e confermala nel campo sottostante';
	$__pass2 = 'Conferma Password';
	$__not_nick = 'Il nick deve essere almeno di 4 caratteri alfanumerici';
	$__not_pass = 'La password deve essere di almeno di 6 caratteri';
	$__not_pass2 = 'La conferma della password non corrisponde';
	$__not_email = 'Email non valida';
	$__not_sname = 'Il nome del sito &egrave; troppo corto';
	$__prev = 'Indietro';
	$__database = 'Configurazione Database';
	$__dbt = 'Tipo Database';
	$__dbt_d = 'MySQLi risulta pi&ugrave; veloce di MySQL ma non &egrave; assicurato il supporto a lungo termine';
	$__host = 'Nome Host';
	$__host_d = 'Generalmente &egrave; localhost';
	$__dbfile = 'Nome File';
	$__dbfile_d = 'Scegli il nome con cui chiamare (o con cui hai chiamato) il tuo database';
	$__dbuser = 'Nome Utente';
	$__dbuser_d = 'Generalmente &egrave; root';
	$__dbpass = 'Password';
	$__dbpass_d = 'Password per accedere al database';
	$__dbname = 'Nome Database';
	$__dbname_d = 'Nome del database in cui installare ALExxia, questo database sar&agrave; usato delle estensioni e per la memorizzazione degli utenti del sito';
	$__dbpref = 'Prefisso Database';
	$__dbpref_d = 'Se non hai a disposizione pi&ugrave; di un database puoi usare un prefisso per installare pi&ugrave; di un sito con ALExxia';
	$__db2 = 'Database Secondario';
	$__db2_d = 'Se hai a disposizione pi&ugrave; di un database compilando i seguenti campi, ALExxia usera il secondo database per i dati sensibili.<br/>Per una sicurezza pi&ugrave; efficace &egrave; consigliato un database non accessibile all\'utente del database principale';
	$__not_db = 'Impossibile connettersi al database, verificare che host,nome utente e password siano corretti';
	$__not_dblite = 'Impossibile creare il file del database, controllare i permessi di scrittura in config/';
	$__not_dbname = 'Il nome del database non &egrave; valido, controllare di averlo scritto correttamente e che l\'utente abbia il permesso di accesso';
	$__not_db2 = 'Impossibile connettersi al database secondario, se non lo si vuole usare lasciare il campo vuoto';
	$__not_dbname2 = 'Il nome del database secondario non &egrave; valido';
	$__sum = 'Riepilogo';
	$__end = 'Installazione Completa';
	$__end_d = 'L\'installazione &egrave; stata completata con successo, per terminare cliccare nel bottone qua sotto';
	$__fine = 'Vai al tuo sito';
	$__secure = 'La connessione &egrave; sicura';
	$__loading = 'Caricamento...';
?>