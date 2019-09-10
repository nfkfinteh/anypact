<?
function GetLCSAlgoritm(&$_a, &$_b) {
    $a = explode(" ",$_a);
    $b = explode(" ",$_b);
    $maxLen = array();
    for($i=0, $x=count($a); $i<=$x; $i++) {
        $maxLen[$i] = array();
        for($j=0, $y=count($b); $j<=$y; $j++) $maxLen[$i][$j] = '';
    }
    for($i=count($a)-1; $i>=0; $i--) {
        for($j=count($b)-1; $j>=0; $j--) {
            if($a[$i] == $b[$j]) $maxLen[$i][$j] = 1+$maxLen[$i+1][$j+1];
            else $maxLen[$i][$j] = max($maxLen[$i+1][$j],$maxLen[$i][$j+1]);
        }
    }

    $rez = "";
    for($i=0, $j=0; $maxLen[$i][$j]!=0 && $i<$x && $j<$y;) {
        if($a[$i] == $b[$j]) {
            $rez .= $a[$i]." ";
            $i++;
            $j++;
        }
        else {
            if($maxLen[$i][$j] == $maxLen[$i+1][$j]) $i++;
            else $j++;
        }
    }
    return trim($rez);
}

function GetUnickStr(&$arr, &$arrUnick) {
    $s='';
    $arrUnickFlip = array_flip($arrUnick);
    foreach($arr as $v) {
        $s .= $arrUnickFlip[$v].' ';
    }
    return trim($s);
}

function FromUnickToArr(&$arrStr, &$arrUnick) {
    $r = array();
    foreach($arrStr as $v) {
        $buff   = array();
        $buff[] = $arrUnick[$v[0]];
        $buff[] = $v[1];
        $r[]    = $buff;
    }
    return $r;
}

function SelDiffsStr(&$_a, &$_b, &$retA, &$retB) {
    $_longest = GetLCSAlgoritm($_a,$_b);
    $longest  = explode(" ",$_longest);

    $a		  = explode(" ",$_a);
    $b		  = explode(" ",$_b);
    $rB		  = array();

    $i1 = 0; $i2 = 0;
    for($i=0, $iters = count($b); $i<$iters; $i++) {
        $simbol = array();
        if(isset($longest[$i1]) && $longest[$i1] == $b[$i2]) {
            $simbol[] = $longest[$i1];
            $simbol[] = "*";
            $rB[] 	  = $simbol;
            $i1++;
            $i2++;
        }
        else {
            $simbol[] = $b[$i2];
            $simbol[] = "+";
            $rB[] 	  = $simbol;
            $i2++;
        }
    }
    $retB = $rB;

    $i1 = 0; $i2 = 0;
    for($i=0,$iters = count($a); $i<$iters; $i++) {
        $simbol = array();
        if(isset($longest[$i1]) && $longest[$i1] == $a[$i2]) {
            $simbol[] = $longest[$i1];
            $simbol[] = "*";
            $rA[] 	  = $simbol;
            $i1++;
            $i2++;
        }
        else {
            $simbol[] = $a[$i2];
            $simbol[] = "-";
            $rA[] 	  = $simbol;
            $i2++;
        }
    }
    $retA = $rA;
}

function SelDiffsText(&$aText, &$bText, &$retAText, &$retBText) {
    $arrA = str_replace("r","",$aText);
    $arrB = str_replace("r","",$bText);
    $arrA = explode("n",$arrA);
    $arrB = explode("n",$arrB);
    $unickTable = array_unique(array_merge($arrA,$arrB));

    $strA = GetUnickStr($arrA,$unickTable);
    $strB = GetUnickStr($arrB,$unickTable);

    SelDiffsStr($strA,$strB,$retA,$retB);
    $retAText = FromUnickToArr($retA,$unickTable);
    $retBText = FromUnickToArr($retB,$unickTable);
}

function SelDiffsColor(&$rdyAText, &$rdyBText, &$strRetA, &$strRetB) {
    $strRetA = "";
    $strRetB = "";

    foreach($rdyAText as $v) {
        if($v[1] == "+") 		$strRetA.=''.$v[0].'';
        elseif($v[1] == '-') 	$strRetA.=''.$v[0].'';
        elseif($v[1] == 'm')	$strRetA.=''.$v[0].'';
        elseif($v[1] == '*')	$strRetA.=$v[0];
    }

    foreach($rdyBText as $v) {
        if($v[1] == "+")		$strRetB.=''.$v[0].'';
        elseif($v[1] == '-')	$strRetB.=''.$v[0].'';
        elseif($v[1] == 'm')	$strRetB.=''.$v[0].'';
        elseif($v[1] == '*')	$strRetB.=$v[0];
    }
}

function MergeInsertAndDelete(&$rdyAText, &$rdyBText) {
    $max = count($rdyAText)>count($rdyBText)?count($rdyAText):count($rdyBText);

    for($i1=0,$i2=0; $i1<$max && $i2<$max; ) 	{
        if($rdyAText[$i1][1]=="-" && $rdyBText[$i2][1]=="+" && $rdyBText[$i2][0]!="") {
            $rdyAText[$i1][1]="*";
            $rdyBText[$i2][1]="m";
        }
        elseif($rdyAText[$i1][1]!="-" && $rdyBText[$i2][1]=="+") $i2++;
        elseif($rdyAText[$i1][1]=="-" && $rdyBText[$i2][1]!="+") $i1++;

        $i1++;
        $i2++;
    }
}

// ***********************************************************
// 					Main function
// ***********************************************************
// string  $sA, $sB 	= 	strings where try find differences
// string  $retA, $retB	=	strings for return result of work
function SelectedDiffs(&$sA, &$sB, &$retA, &$retB) {
    SelDiffsText($sA,$sB,$retAText,$retBText);
    MergeInsertAndDelete($retAText,$retBText);
    SelDiffsColor($retAText,$retBText,$retA,$retB);
}