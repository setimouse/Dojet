<?php
/*************************	database	*********************/
function dbquery($sql, $conn = null) {
	if ( is_resource($conn) ) {
		return @mysql_query($sql, $conn);
	} else {
		return @mysql_query($sql);
	}
}

function rs2array($sql, $conn = null) {
	$rs = dbquery($sql, $conn);
	$ret = array();
	if ( false === $rs ) {
		return $ret;
	}

	if ( $rs ) {
		while ( $row = @mysql_fetch_assoc($rs) ) {
			$ret[] = $row;
		}
	}
	return  $ret;
}

function rs2rowline($sql, $conn = null) {
	$rs = dbquery($sql, $conn);

	$ret = @mysql_fetch_assoc($rs);

	return  $ret;
}

function rs2rowcount($sql, $conn = null) {
	$ret = rs2firstvalue($sql, $conn);
	return $ret;
}

function rs2firstvalue($sql, $conn = null) {
	$rs = dbquery($sql, $conn);
	$row = @mysql_fetch_row($rs);
	$ret = $row[0];
	return $ret;
}

function rs2foundrows($conn = null) {
	return rs2firstvalue("SELECT FOUND_ROWS()", $conn);
}

function mes($string) {
	return mysql_real_escape_string($string);
}




/*********************	charset	**********************/
function gb2u($s) {
	return _iconvEx('GB18030', 'UTF-8', $s);
}

function u2gb($s) {
	return _iconvEx('UTF-8', 'GB2312', $s);
}

function _iconvEx($in_charset, $out_charset, $str) {
	if ( is_array($str) ) {
		foreach ( $str as $key => $value ) {
			$ret[$key] = _iconvEx($in_charset, $out_charset, $value);
		}
	} else {
		$ret = iconv($in_charset, $out_charset, $str);
	}
	return $ret;
}



function array2object($array) {
	$obj = (object)$array;
	foreach ((array)$array as $key => $value) {
		if (is_array($value)) {
			$obj->$key = array2object($value);
		}
	}
	return $obj;
}


/*********************** other functions	*********************/
function pglist($pg, $pgpadding, $pgcount, $tpl_current, $tpl_link) {
	$thepage = 1;
	$firstpage = ($pg == 1) ? str_replace('{p}', 1, $tpl_current) : strtr($tpl_link, array('{p}' => 1, '{pgno}' => 1));
	
	$midlist = '';
	for ($i = max($pg - $pgpadding, 2); $i <= min($pg + $pgpadding, $pgcount - 1); $i++) {
		if ( $i == $pg ) {
			$midlist.= str_replace('{p}', $i, $tpl_current);
		} else {
			$midlist.= strtr($tpl_link, array('{p}' => $i, '{pgno}' => $i));
		}
	}
	
	$pgcount > 1 && 
		$lastpage = ($pg == $pgcount) ? str_replace('{p}', $pgcount, $tpl_current) : strtr($tpl_link, array('{p}' => $pgcount, '{pgno}' => $pgcount));
	
	$ret = '';
	$ret.= $firstpage;
	$ret.= $pg - $pgpadding > 2 ? "..." : '';
	$ret.= $midlist;
	$ret.= $pg + $pgpadding < $pgcount - 1 ? "..." : '';
	$ret.= $lastpage;
	
	return $ret;
}

function printbr($str, $flush = true) {
	if ( is_array($str) ) {
		$str = print_r($str, true);
	}
	$str = str_replace(" ", "&nbsp;", $str);
	$str = nl2br($str);
	print $str."<br />";
	if ( $flush ) flush();
}

function println($str, $flush = true){
	if ( is_array($str) ) {
		$str = print_r($str, true);
	}
	print $str."\n";
	if ( $flush ) flush();
}

function printa($array) {
	print nl2br(str_replace(array(' ', "\t"), '&nbsp;', print_r($array, true)));
}

function nicetime($timestamp) {
	$duration = time() - $timestamp;
	
	$strEcho = date("Y-m-d H:i", $timestamp);
	
	if( $duration < 60 ){
		$strEcho = "刚刚";
	}elseif( $duration < 3600 ){
		$strEcho = intval($duration/60)."分钟�?";
	}elseif( $duration >= 3600 && $duration <= 86400  ){
		$strEcho = intval($duration/3600)."小时�?";
	}elseif( $duration > 86400 && $duration <= 172800 ){
		$strEcho = '昨天'.date("H:i", $timestamp);
	} elseif ( date("Y") === date("Y", $timestamp) ) {
		$strEcho = date("m-d H:i", $timestamp);
	}
	
	return $strEcho;
}

function redirect($location) {
	@header("Location: $location");
	exit();
}

function get_microtime() {
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}

function safeHtml($html) {
	return htmlspecialchars($html, ENT_QUOTES);
}

function safeUrl($url) {
	$url = str_replace('&amp;', '&', $url);
	$url = str_replace('&', '&amp;', $url);
	
	return $url;
}

/**
 * ��ȡl��WEB���������ʵIP�������ô��??�絻ص��Ǵ���IP��
 *
 * @param string $strDefaultIp Ĭ��IP��ַ
 * @param bool $hasTransmit �Ƿ�ǰ����transmit
 * @return string
 */
function getConnectIp ($strDefaultIp = '0.0.0.0', $hasTransmit = true)
{
    //��ȡIP
    $strIp = '';
    if (! $hasTransmit && isset($_SERVER['REMOTE_ADDR']) ){
    	//û��transmit������ѡ��REMOTE_ADDR
    	$strIp = strip_tags($_SERVER['REMOTE_ADDR']);
    } elseif (isset($_SERVER['HTTP_CLIENTIP'])) {
        //transmit ���е�
        $strIp = strip_tags($_SERVER['HTTP_CLIENTIP']);
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $strIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        //��ȡ���һ��?
        $strIp = strip_tags(trim($strIp));
        $intPos = strrpos($strIp, ',');
        if ($intPos > 0) {
            $strIp = substr($strIp, $intPos + 1);
        }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $strIp = strip_tags($_SERVER['HTTP_CLIENT_IP']);
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $strIp = strip_tags($_SERVER['REMOTE_ADDR']);
    }
    $strIp = trim($strIp);
    if (empty($strIp) || !ip2long($strIp)) {
        //IP��ַ���Ϸ�
        $strIp = $strDefaultIp;
    }
    return $strIp;
}

/**
 * ��ȡ�û��ͻ��˵�IP��ַ����IP��ַ���ܱ��û�α��
 *
 * @param stringĬ��IP��ַ $strDefaultIp
 * @return string
 */
function getUserClientIp ($strDefaultIp = '0.0.0.0')
{
    $strIp = '';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $strIp = strip_tags($_SERVER['HTTP_X_FORWARDED_FOR']);
        //��ȡ��һ��
        $intPos = strpos($strIp, ',');
        if ($intPos > 0) {
            $strIp = substr($strIp, 0, $intPos);
        }
    } elseif (isset($_SERVER['HTTP_CLIENTIP'])) {
        //transmit����
        $strIp = strip_tags($_SERVER['HTTP_CLIENTIP']);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $strIp = strip_tags($_SERVER['HTTP_CLIENT_IP']);
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $strIp = strip_tags($_SERVER['REMOTE_ADDR']);
    }
    $strIp = trim($strIp);
    if (empty($strIp) || !ip2long($strIp)) {
        $strIp = $strDefaultIp;
    }
    return $strIp;
}
