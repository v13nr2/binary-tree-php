<?php

function hitungSisi ($data, $indukId, $sisi, $initLvl = 0, $jumlah = 0){
            
    foreach($data as $v) {
        if(0 == $initLvl) {
            if( ($v['induk_id'] == $indukId) && ($v['sisi'] == $sisi) ) {
                ++$jumlah;
                $jumlah += hitungSisi($data, $v['id'], 'kiri');
                $jumlah += hitungSisi($data, $v['id'], 'kanan');
            }
        }
        else {
            if( ($v['induk_id'] == $indukId) ) 
                $jumlah++;
        }
    }

    return $jumlah;
}

function hitungSisiOmset ($data, $indukId, $sisi, $initLvl = 0, $jumlah = 0){
            
    foreach($data as $v) {
        if(0 == $initLvl) {
            if( ($v['induk_id'] == $indukId) && ($v['sisi'] == $sisi) ) {
                $jumlah += $v['omset'];
                $jumlah += hitungSisiOmset($data, $v['id'], 'kiri');
                $jumlah += hitungSisiOmset($data, $v['id'], 'kanan' );
            }
        }
        else {
            if( ($v['induk_id'] == $indukId) ) 
                $jumlah = $v['omset'];
        }
    }

    return $jumlah;
}

function getBTree($data, $id = 0) {
    $kedalaman = 1;
    $postingdi = "AA";
    $indukId = 0;
    $result = array();

    $adjust = function($data, $indukId, $level = 0) use (&$adjust) {
        $result = array();

        foreach($data as $v) {
            if($v['induk_id'] == $indukId) {
                $children = $adjust($data, $v['id'], $level+1);
                $omsetKanan = $omsetKiri = $kanan = $kiri = 0;

                $kanan = hitungSisi($data, $v['id'], 'kanan');
                $kiri  = hitungSisi($data, $v['id'], 'kiri');
                
                $omsetKanan = hitungSisiOmset($data, $v['id'], 'kanan');
                $omsetKiri = hitungSisiOmset($data, $v['id'], 'kiri');



                
                
                if($result[0]["header_posting_di"]==$v['sisi']){
                    $end_user_placement = $v["id"];
                }
                
                if($v['sisi']=="kanan" && $level == 2){
                    break;
                }
                
                $result[] = [
                    'id'=>$v['id'],
                    'nomor'=>$v['nomor'],
                    'nama'=>$v['nama'],
                    'induk_id'=>$v['induk_id'],
                    'foto'=>$v['foto'],
                    'id'=>$v['id'],
                    'sisi'=>$v['sisi'],
                    'prioritas'=>('kiri' == $v['sisi']) ? 2 : 1,
                    'head'=>$v['id']."#".$v['sisi'],
                    'kiri'=>$kiri,
                    'kanan'=>$kanan,
                    'level'=>$level,
                    'omset'=>$v['omset'],
                    'omsetKiri'=>$omsetKiri,
                    'omsetKanan'=>$omsetKanan,
                    'omsetAll'=>$omsetKiri+$omsetKanan,
                    'children'=>empty($children) ? null : $children,
                    'header_posting_di' => $postingdi,
                    'kedalaman' => $kedalaman,
                    'end_user_placement'    => $end_user_placement
                ];
            }
            
            
            $kedalaman++;
        }
        return $result;
    };

    if(!empty($id)) {
        foreach($data as $v) {
            if($v['id'] == $id) {
                $indukId = $v['id'];

                $omsetKanan = $omsetKiri = $kanan = $kiri = 0;

                $kanan = hitungSisi($data, $v['id'], 'kanan');
                $kiri  = hitungSisi($data, $v['id'], 'kiri');
                
                //$omsetKanan = hitungSisiOmset($data, $v['omset'], 'kanan',0, $v['omset']);
                //$omsetKiri = hitungSisiOmset($data, $v['omset'], 'kiri',0, $v['omset']);
                
                $omsetKanan = hitungSisiOmset($data, $v['id'], 'kanan');
                $omsetKiri = hitungSisiOmset($data, $v['id'], 'kiri');
                
                
                if($omsetKiri == $omsetKanan){
                    //die("Posing di Kiri");
                    $a=$result;;
                    array_push($a,"Posing di Kiri","Posing di Kiri");
                    $postingdi  = "kiri";

                }
                if($omsetKiri > $omsetKanan){
                    //die("Posing di Kanan");
                    $a=$result;;
                    array_push($a,"Posing di Kanan","Posing di Kanan");
                    $postingdi  = "kanan";
                }
                if($omsetKiri < $omsetKanan){
                    //die("Posing di Kanan");
                    $a=$result;;
                    array_push($a,"Posing di Kiri","Posing di Kiri");
                    $postingdi  = "kiri";
                }
                
                if($result[0]["header_posting_di"]==$v['sisi']){
                    $end_user_placement = $v["id"];
                }
                
                
                $result[0] = [
                    'id'=>$v['id'],
                    'nomor'=>$v['nomor'],
                    'nama'=>$v['nama'],
                    'induk_id'=>$v['induk_id'],
                    'foto'=>$v['foto'],
                    'id'=>$v['id'],
                    'sisi'=>$v['sisi'],
                    'prioritas'=>('kiri' == $v['sisi']) ? 2 : 1,
                    'head'=>$v['nomor']."#".$v['sisi'],
                    'kiri'=>$kiri,
                    'kanan'=>$kanan,
                    'level'=>0,
                    'omset'=> $v['omset'],
                    'omsetKiri'=>$omsetKiri,
                    'omsetKanan'=>$omsetKanan,
                    'omsetAll'=>$omsetKiri+$omsetKanan,
                    'header_posting_di' => $postingdi,
                    'kedalaman' => $kedalaman
                    ];
                    
                    
                    
                if($v['sisi']=="kanan" && $level == 1){
                    break;
                }
            }
        }
        
                

        $result[0]['children'] = $adjust($data, $indukId, 1);
    }
    else {
        $result = $adjust($data, $indukId);
    }

    return $result;
}

function getSenarai($data, $result = array()) {
    if(!empty($data) && is_array($data)) {
        foreach($data as $v) {
            $vv = $v;
            unset($vv['children']);

            if(!empty($v['children'])) 
                $result = getSenarai($v['children'], $result);
            
            $result[] = $vv;
        }
    }

    return $result;
}

function goToTop($data, $id) {
    $btree = getBTree($data);
    $senarai = getSenarai($btree);
    
    $result = array();
    $first = null;

    foreach($senarai as $v) {
        if($v['id'] == $id)
            $first = $v;
    }

    $cariInduk = function($data, $indukId, $result = []) use (&$cariInduk) {
        foreach($data as $v) {
            if($v['id'] == $indukId) {
                $result[] = $v;
                return $cariInduk($data, $v['induk_id'], $result);
            }
        }

        return $result;
    };

    if(!empty($first))
        $result = $cariInduk($senarai, $first['induk_id']);

    return $result;
}

function getOmsetTerkecil($data, $result = null) {
    foreach($data as $v) {
        if(empty($result)) {
            $result = $v;
        }
        else {
            // if($result['omsetAll'] == $v['omsetAll']) {
            //     if( $result['prioritas'] < $v['prioritas'] )
            //         $result = $v;
            //     else
            //         $result = $v;
            // }
            if($result['omsetAll'] > $v['omsetAll']) { 
                $result = $v;
            }
        }

        if($result['children']) {
            return getOmsetTerkecil($result['children'], null);
        }
    }

    return $result;
}

function getMemberTerdalam($data, $sisi = 'kiri') {
    
    $senarai = getSenarai($data);

    // urutkan tinggi ke rendah berdasarkan omset
    function cmpByOmset($a, $b)
    {
        if ($a['omsetAll'] == $b['omsetAll']) {
            return 0;
        }
        
        return ($a['omsetAll'] < $b['omsetAll']) ? -1 : 1;
    }

    usort($senarai, "cmpByOmset");

    // ambil member dengan level terdalam
    $result = array();    
    foreach($senarai as $v) {
        if(empty($result))
            $result[] = $v;
        else {
            if($v['omsetAll'] < $result[0]['omsetAll'])
                $result = array($v);
            else if($v['omsetAll'] == $result[0]['omsetAll'])
                $result[] = $v;
        }
    }

    // jika hasil lebih dari satu, ambil sisi terkiri / terkanan sesuai param
    if(count($result) > 1) {
        // urutkan berdasarkan id
        function cmpByLvl($a, $b)
        {
            if ($a['level'] == $b['level']) {
                return 0;
            }
            return ($a['level'] > $b['level']) ? -1 : 1;
        }

        usort($result, "cmpByLvl");

        if($sisi == 'kiri')
            $result = array($result[count($result)-1]);
        else
            $result = array($result[0]);
    }
    
    return $result[0];
}

function getSpillOverMember($data, $id = 0, $sisi = 'kiri') {    
    $btree = getBTree($data, $id);
   
    if(!empty($btree)) {
        if(!empty($btree[0]['children'])) {
            $temp = [];

            foreach($btree[0]['children'] as $v) {
                if(empty($temp)) {
                    $temp = $v;
                }
                else {
                    if($v['omsetAll'] < $temp['omsetAll']) {
                        $temp = $v;
                    }
                    else if($v['omsetAll'] == $temp['omsetAll']) {
                        if($v['prioritas'] > $temp['prioritas']) {
                            $temp = $v;
                        }
                    }
                }
            }

            $btree = getBTree($data, $temp['id']);
        }
    }

    return getMemberTerdalam($btree, $sisi);
}



function getPlacement($data, $id = 0, $sisi = 'kiri') {    
    $btree = getBTree($data, $id);
   
    if(!empty($btree)) {
        if(!empty($btree[0]['children'])) {
            $temp = [];

            foreach($btree[0]['children'] as $v) {
                if(empty($temp)) {
                    $temp = $v;
                }
                else {
                    if($v['omsetAll'] < $temp['omsetAll']) {
                        $temp = $v;
                    }
                    else if($v['omsetAll'] == $temp['omsetAll']) {
                        //salah if($v['prioritas'] > $temp['prioritas']) {
                            
                        if($v['prioritas'] < $temp['prioritas']) {
                            $temp = $v;
                        }
                    }
                }
            }

            $btree = getBTree($data, $temp['id']);
        }
    }
    

    // placement dulu baru member terdalam ==== return getMemberTerdalam($btree, $sisi);
    return getOmsetTerkecil($btree);
}



function getSpillOverMember2($data, $id = 0, $sisi = 'kiri') {
    $result = [];
    $btree = getBTree($data, $id);
    $btreeChild = [];
    $sisiMember = array();
    $getById = 0;

    // foreach($btree as $v) {
    //     if(!empty($v['children'])) {
    //         foreach($v['children'] as $k=>$vv) {                
    //             if($vv['sisi'] == $sisi) {
    //                 $btreeChild = $vv;
    //             }
    //         }
    //     }
    // }

    // buat hirarki jadi flat
    $senarai = getSenarai($btree);
    
    // urutkan tinggi ke rendah berdasarkan omset
    function cmpByOmset($a, $b)
    {
        if ($a['omsetAll'] == $b['omsetAll']) {
            return 0;
        }
        
        return ($a['omsetAll'] < $b['omsetAll']) ? -1 : 1;
    }

    usort($senarai, "cmpByOmset");

    

    // ambil member dengan level terdalam
    $result = array();    
    foreach($senarai as $v) {
        if(empty($result))
            $result[] = $v;
        else {
            if($v['omsetAll'] < $result[0]['omsetAll'])
                $result = array($v);
            else if($v['omsetAll'] == $result[0]['omsetAll'])
                $result[] = $v;
        }
    }


    // jika hasil lebih dari satu, ambil sisi terkiri / terkanan sesuai param
    if(count($result) > 1) {
        // urutkan berdasarkan id
        function cmpByLvl($a, $b)
        {
            if ($a['level'] == $b['level']) {
                return 0;
            }
            return ($a['level'] < $b['level']) ? -1 : 1;
        }

        usort($result, "cmpByLvl");

        if($sisi == 'kiri')
            $result = array($result[count($result)-1]);
        else
            $result = array($result[0]);
    }

    return $result;
}