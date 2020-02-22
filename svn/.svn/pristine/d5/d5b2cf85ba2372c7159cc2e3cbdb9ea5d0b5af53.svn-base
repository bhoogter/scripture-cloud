<?php

function scripturecloud_verse($text = '') {

    // skip everything within a hyperlink, a <pre> block, a <code> block, or a tag
    // we skip inside tags because something like <img src="nicodemus.jpg" alt="John 3:16"> should not be messed with
	$anchor_regex = '<a\s+href.*?<\/a>';
	$pre_regex = '<pre>.*<\/pre>';
	$code_regex = '<code>.*<\/code>';
	$other_plugin_regex= '\[bible\].*\[\/bible\]'; // for the ESV Wordpress plugin (out of courtesy)
	$other_plugin_block_regex='\[bibleblock\].*\[\/bibleblock\]'; // ditto
	$tag_regex = '<(?:[^<>\s]*)(?:\s[^<>]*){0,1}>'; // $tag_regex='<[^>]+>';
	$split_regex = "/((?:$anchor_regex)|(?:$pre_regex)|(?:$code_regex)|(?:$other_plugin_regex)|(?:$other_plugin_block_regex)|(?:$tag_regex))/i";
	$parsed_text = preg_split($split_regex,$text,-1,PREG_SPLIT_DELIM_CAPTURE);
	$linked_text = '';

  while (list($key,$value) = each($parsed_text)) {
      if (preg_match($split_regex,$value)) {
      } else {
		$reflist .= ($reflist==""?"":scripturecloud_sep()).scripturecloud_fixref($value);
      }
  }

	while (strstr($reflist, scripturecloud_sep().scripturecloud_sep())) $reflist = str_replace(scripturecloud_sep().scripturecloud_sep(),scripturecloud_sep(),$reflist);
	$reflist = trim($reflist," \n\r\t".scripturecloud_sep());
	return $reflist;
}


function scripturecloud_fixref($text = '') {
//print "<br/>scripturecloud_fixref($text)";
    $volume_regex = '1|2|3|I|II|III|1st|2nd|3rd|First|Second|Third';

    $book_regex  = 'Genesis|Exodus|Leviticus|Numbers|Deuteronomy|Joshua|Judges|Ruth|Samuel|Kings|Chronicles|Ezra|Nehemiah|Esther';
    $book_regex .= '|Job|Psalms?|Proverbs?|Ecclesiastes|Songs? of Solomon|Song of Songs|Isaiah|Jeremiah|Lamentations|Ezekiel|Daniel|Hosea|Joel|Amos|Obadiah|Jonah|Micah|Nahum|Habakkuk|Zephaniah|Haggai|Zechariah|Malachi';
    $book_regex .= '|Mat+hew|Mark|Luke|John|Acts?|Acts of the Apostles|Romans|Corinthians|Galatians|Ephesians|Phil+ippians|Colossians|Thessalonians|Timothy|Titus|Philemon|Hebrews|James|Peter|Jude|Revelations?';

	// I split these into two different variables from Dean's original Perl code because I want to be able to have an optional period at the end of just the abbreviations

    $abbrev_regex  = 'Gen|Ex|Exo|Lev|Num|Nmb|Deut?|Josh?|Judg?|Jdg|Rut|Sam|Ki?n|Chr(?:on?)?|Ezr|Neh|Est';
    $abbrev_regex .= '|Jb|Psa?|Pr(?:ov?)?|Eccl?|Song?|Isa|Jer|Lam|Eze|Dan|Hos|Joe|Amo|Oba|Jon|Mic|Nah|Hab|Zeph?|Hag|Zech?|Mal';
    $abbrev_regex .= '|Mat+|Mr?k|Lu?k|Jh?n|Jo|Act|Rom|Cor|Gal|Eph|Col|Phil?|The?|Thess?|Tim|Tit|Phile|Heb|Ja?m|Pe?t|Ju?d|Rev';

    $book_regex='(?:'.$book_regex.')|(?:'.$abbrev_regex.')\.?';

    $verse_regex="\d{1,3}(?::\d{1,3})?(?:\s?(?:[-&,]\s?\d+))*";

	// we don't really care about translations for this...  add some dummy ones
	$translation_regex = "KJV|NIV";

	// note that this will be executed as PHP code after substitution thanks to the /e at the end!
    $passage_regex = '/(?:('.$volume_regex.')\s)?('.$book_regex.')\s('.$verse_regex.')(?:\s?[,-]?\s?((?:'.$translation_regex.')|\s?\((?:'.$translation_regex.')\)))?/e';

    $replacement_regex = "scripturecloud_prettyref('\\0','\\1','\\2','\\3','\\4')";

	global $scripture_cloud_build;
	$scripture_cloud_build="";

    $text=preg_filter($passage_regex,$replacement_regex,$text);

//print "<br/>scripturecloud_fixref(...): $text";
    return $scripture_cloud_build; //$text;
}

function scripturecloud_book($rawbook, $wno=false) {
	// ultimately I need to restore all abbreviations to the full book.
	// perhaps take the first three letters and expand?
	$book = strtolower(trim($rawbook));
	$book = preg_replace('/\s+/', '', $book); //strip whitespace
	$book= substr($book,0,3);
	switch ($book) {
		case 'gen': $book='genesis'; break;
		case 'exo': case 'ex': $book='exodus'; break;
		case 'lev': case 'lv': $book='leviticus'; break;
		case 'num': $book='numbers'; break;
		case 'deu': case 'dt': $book='deuteronomy'; break;
		case 'jos': $book='joshua'; break;
		case 'jud': case 'jd':
			// could be either Judges or Jude
			// abbreviations for Judges should always have a g in them
			$judges=strpos($rawbook,'g');
			if ($judges===FALSE) {
				$book='jude';
			} else {
				$book='judges';
			}
			break;
		case 'rut': case 'rth': $book='ruth'; break;
		case '1sa': $book='1samuel'; break;
		case '2sa': $book='2samuel'; break;
		case '1ki': $book='1kings'; break;
		case '2ki': $book='2kings'; break;
		case '1ch': $book='1chronicles'; break;
		case '2ch': $book='2chronicles'; break;
		case 'ezr': case 'ez': $book='ezra'; break;
		case 'neh': case 'nh': $book='nehemiah'; break;
		case 'est': $book='esther'; break;
		case 'job': case 'jb': $book='job'; break;
		case 'psa': case 'ps': $book='psalms'; break;
		case 'pro': case 'pr': $book='proverbs'; break;
		case 'ecc': $book='ecclesiastes'; break;
		case 'son': case 'sos': $book='song of songs'; break;
		case 'isa': case 'is': $book='isaiah'; break;
		case 'jer': $book='jeremiah'; break;
		case 'lam': $book='lamentations'; break;
		case 'eze': case 'ez': $book='ezekiel'; break;
		case 'dan': case 'dn': $book='daniel'; break;
		case 'hos': $book='hosea'; break;
		case 'joe': $book='joel'; break;
		case 'amo': case 'am': $book='amos'; break;
		case 'oba': case 'ob': $book='obadiah'; break;
		case 'jon': $book='jonah'; break;
		case 'mic': $book='micah'; break;
		case 'nah': $book='nahum'; break;
		case 'hab': $book='habakkuk'; break;
		case 'zep': $book='zephaniah'; break;
		case 'hag': $book='haggai'; break;
		case 'zec': $book='zechariah'; break;
		case 'mal': $book='malachi'; break;
		case 'mat': case 'mt': $book='matthew'; break;
		case 'mar': case 'mk': $book='mark'; break;
		case 'luk': case 'lk': $book='luke'; break;
		case 'joh': case 'jn': $book='john'; break;
		case 'act': $book='acts'; break;
		case 'rom': case 'rm': $book='romans'; break;
		case '1co': $book='1corinthians'; break;
		case '2co': $book='2corinthians'; break;
		case 'gal': $book='galatians'; break;
		case 'eph': $book='ephesians'; break;
		case 'phi': $book='philippians'; break;
		case 'col': $book='colossians'; break;
		case '1th': $book='1rhessalonians'; break;
		case '2th': $book='2thessalonians'; break;
		case '1ti': $book='1timothy'; break;
		case '2ti': $book='2timothy'; break;
		case 'tit': case 'ti': $book='titus'; break;
		case 'phi': $book='philemon'; break;
		case 'heb': $book='hebrews'; break;
		case 'jam': $book='james'; break;
		case '1pe': $book='1peter'; break;
		case '2pe': $book='2peter'; break;
		case '1jo': $book='1john'; break;
		case '2jo': $book='2john'; break;
		case '3jo': $book='3john'; break;
		// jude is handled up by judges
		case 'rev': $book='revelation'; break;
		default:
			$book=$rawbook;
	}
	if (!$wno) $book = str_replace(array("1","2","3"),"",$book);
	return ucwords($book);
}

function scripturecloud_prettyref($reference='',$volume='',$book='',$verse='') {
//print "<br/>scripturecloud_prettyref($reference,$volume,$book,$verse)";
    if ($volume) {
       $volume = str_replace('III','3',$volume);
	   $volume = str_replace('Third','3',$volume);
       $volume = str_replace('II','2',$volume);
	   $volume = str_replace('Second','2',$volume);
       $volume = str_replace('I','1',$volume);
	   $volume = str_replace('First','1',$volume);
       $volume = $volume{0}; // will remove st,nd,and rd (presupposes regex is correct)
    }

	//catch an obscure bug where a sentence like "The 3 of us went downtown" triggers a link to 1 Thess 3
	if (!strcmp(strtolower($book),"the") && $volume=='' ) return "";

   // if necessary, just choose part of the verse reference to pass to the web interfaces
   // they wouldn't know what to do with John 5:1-2, 5, 10-13 so I just give them John 5:1-2
   // this doesn't work quite right with something like 1:5,6 - it gets chopped to 1:5 instead of converted to 1:5-6
//   if ($verse) {$verse = strtok($verse,',& ');}

	$book = scripturecloud_book($volume.$book);
	$pieces = split('[:]', $verse, 3);
	$chapter = $pieces[0];
	$num = $pieces[1];

	$x = trim("$volume $book $chapter");
	if ($num!='') $x.=":".$num;

	global $scripture_cloud_build;
	$scripture_cloud_build .= scripturecloud_sep().$x;
	return $x;
}


?>