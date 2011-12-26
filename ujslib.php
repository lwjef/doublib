<?php
include_once('simple_html_dom.php');
$opacrsslink = "http://huiwen.ujs.edu.cn:8080/opac/search_rss.php?dept=ALL&isbn=";
if (isset($_GET["isbn"]) and $_GET["isbn"]) {
    $isbn = $_GET["isbn"];
    $searchlink = $opacrsslink . $isbn;
    $getbooklink = new DOMDocument();
    $getbooklink->load($searchlink);
    if (isset($getbooklink->getElementsByTagName("link")->item(0)->nodeValue)) {
        $booklink = $getbooklink->getElementsByTagName("link")->item(0)->nodeValue;
    }
    else {
        $searchlink = $opacrsslink . substr($isbn,3,-1);
        $getbooklink = new DOMDocument();
        $getbooklink->load($searchlink);

        if (isset($getbooklink->getElementsByTagName("link")->item(0)->nodeValue)) {
            $booklink = $getbooklink->getElementsByTagName("link")->item(0)->nodeValue;
        }
    }
    if (isset($booklink)) {
        $ok = 0;
        $bookpage = file_get_html($booklink);
        $data = array();
        foreach($bookpage->find('tr') as $tr) {
            if ($ok>0) {
                foreach($tr->find('td') as $td) {
                    if ($j==0) {
                        $i = $td->innertext;
                    }
                    elseif ($j==4) {
                        $place = $td->innertext;
                    }
                    elseif ($j==5) {
                        $s = $td->innertext;
                        $s = strip_tags($s);
                    }
                    $j++;
                }
                $data[] = array('place'=>$place,'i'=>$i,'s'=>$s);
            }
            $ok++;
            $j = 0;
        }
        $arr = array('ok'=>$ok,'data'=>$data);
        $json_string = json_encode($arr);
        echo $json_string;
    }
}
?>

