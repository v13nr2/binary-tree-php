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
            if( ($v['induk_id'] == $indukId)  && ($v['sisi'] == $sisi)) 
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
    $indukId = 0;
    $result = array();
 
    $adjust = function($data, $indukId, $level = 0) use (&$adjust) {
        $result = array();

        foreach($data as $v) {
            if($v['induk_id'] == $indukId) {
                $children = $adjust($data, $v['id'], $level+1);
                // inisialisasi semua omset
                $omsetKanan = $omsetKiri = $kanan = $kiri = 0;

                // hitung jumlah anak di setiap sisi
                $kanan = hitungSisi($data, $v['id'], 'kanan');
                $kiri  = hitungSisi($data, $v['id'], 'kiri');
                
                // hitung omset dari anak di setiap sisi
                $omsetKanan = hitungSisiOmset($data, $v['id'], 'kanan');
                $omsetKiri = hitungSisiOmset($data, $v['id'], 'kiri');

                // susun attribut array
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
                    // set konten untuk ditampilkan di html
                    'contents'=>'<img src="./Images/' . $v['foto'] .'" class="img-btree" data-id="' . $v['id'] . '" style="width:auto;height:125px"/><br/>
                                 <span>' . $kiri . ' | ' . $kanan . ' </span><br>
                                 <span>' . $omsetKiri . ' | ' . $omsetKanan . ' </span><br>
                                 <label>' . $v['nomor'] . '</label>
                                 <h4>' . $v['nama'] .'</h4>',
                    'children'=>empty($children) ? null : $children
                ];
            }
        }

        return $result;
    };

    // jika memiliki id, set data awal
    if(!empty($id)) {
        foreach($data as $v) {
            if($v['id'] == $id) {
                $indukId = $v['id'];

                $omsetKanan = $omsetKiri = $kanan = $kiri = 0;

                // hitung jumlah anak di setiap sisi
                $kanan = hitungSisi($data, $v['id'], 'kanan');
                $kiri  = hitungSisi($data, $v['id'], 'kiri');
                
                // hitung jumlah omset anak di setiap sisi
                $omsetKanan = hitungSisiOmset($data, $v['id'], 'kanan');
                $omsetKiri = hitungSisiOmset($data, $v['id'], 'kiri');
                
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
                    'contents'=>'<img src="./Images/' . $v['foto'] .'" class="img-btree" data-id="' . $v['id'] . '" style="width:auto;height:125px"/><br/>
                                    <span>' . $kiri . ' | ' . $kanan . ' </span><br>
                                 <span>' . $omsetKiri . ' | ' . $omsetKanan . ' </span><br>
                                    <label>' . $v['nomor'] . '</label>
                                    <h4>' . $v['nama'] .'</h4>'
                    ];
            }
        }

        // susun member dari data sebagai anak
        $result[0]['children'] = $adjust($data, $indukId, 1);
    }
    else {
        // susun member dari data
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

function setOmsetBaru($data, $id) {
    $btree = getBTree($data);
    $senarai = getSenarai($btree);
    
    $result = array();
    $first = null;

    foreach($senarai as $v) {
        if($v['id'] == $id) {
            if('kiri' == $v['sisi']) {
                $v['omset_kiri_baru'] = $v['omset'];
                $v['omset_kanan_baru'] = 0;
            }
            else if('kanan' == $v['sisi']) {
                $v['omset_kiri_baru'] = 0;
                $v['omset_kanan_baru'] = $v['omset'];
            }

            $first = $v;
        }
    }

    $cariInduk = function($data, $indukId, $sisi, $omset, $result = []) use (&$cariInduk) {
        foreach($data as $v) {
            if($v['id'] == $indukId) {
                if('kiri' == $sisi) {
                    $v['omset_kiri_baru'] = $omset;
                    $v['omset_kanan_baru'] = 0;
                }
                else if('kanan' == $sisi) {
                    $v['omset_kiri_baru'] = 0;
                    $v['omset_kanan_baru'] = $omset;
                }
                
                $result[] = $v;

                return $cariInduk($data, $v['induk_id'], $v['sisi'], $omset, $result);
            }
        }

        return $result;
    };

    if(!empty($first))
        $result = $cariInduk($senarai, $first['induk_id'], $first['sisi'], $first['omset']);

    return $result;
}

function getOmsetTerkecil($data, $result = null) {
    foreach($data as $v) {
        if(empty($result)) {
            $result = $v;
        }
        else {
            if($result['omsetAll'] == $v['omsetAll']) {
                if( $result['prioritas'] < $v['prioritas'] )
                    $result = $v;
                else
                    $result = $v;
            }
            else if($result['omsetAll'] > $v['omsetAll']) { 
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

    // susun data menjadi tree
    $btree = getBTree($data, $id);
    $result = [];
    
    // cari member sebagai induk
    $cariMember = function($data, $result = []) use (&$cariMember) {
        $v = $data[0];

        // jika data tidak memiliki member, berarti pencarian terhenti
        if(empty($v['children'])) {
            $result = $v;
        } else {
            // jika omset kanan kiri tidak sama, cari berdasarkan omset
            if($v['omsetKiri'] != $v['omsetKanan']) {
                
                if($v['omsetKiri'] < $v['omsetKanan']) {
                    $sisi = 'kiri';
                }
                else if($v['omsetKiri'] > $v['omsetKanan']) {
                    $sisi = 'kanan'; 
                }

                // jika sisi tersebut kosong, berarti di sisi ini member akan ditempatkan
                if($v[$sisi] < 1) {
                    $v['children'] = [];
                    $result = $v;
                } else {
                    // jika memiliki lebih dari 1 member maka, cari lebih dalam berdasarkan member dengan sisi tersebut
                    if(!empty($v['children'])) {
                        foreach($v['children'] as $vv) {
                            if($vv['sisi'] == $sisi)
                                return $cariMember(array($vv));
                        }
                    }
                }
            }
            else {
                // jika memiliki member lebih dari satu dan memiliki member di sisi kiri, ulangi pencarian
                if(count($v['children']) > 1 ) {
                    foreach($v['children'] as $vv) {
                        if($vv['sisi'] == 'kiri')
                            return $cariMember(array($vv));
                    }
                    
                }
                else {                       
                    // jika tidak memiliki member, kembalikan $v['children'] sebagai pencarian akhir
                    return $cariMember($v['children']);
                }
            }
        }

        return $result;
    };

    $result = $cariMember($btree);

    if($result['omsetKiri'] > $result['omsetKanan'])
        $result['posisi_di'] = 'kanan';
    else
        $result['posisi_di'] = 'kiri';    

    return $result;
}

function getSpillOverMember1($data, $id = 0, $sisi = 'kiri') {    
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

    $member = getMemberTerdalam($btree, $sisi);

    $senarai = getSenarai(getBTree($data));
    $member['posisi_di'] = 'kiri';
    $memberIndukId = $member['induk_id'];
    
    foreach($senarai as $v) {
        if($memberIndukId == $v['id']){           
           if(empty($v['kiri'])) {
             $v['posisi_di'] = 'kiri';
             $member = $v;
           }
           else if(empty($v['kanan'])) {
             $v['posisi_di'] = 'kanan';
             $member = $v;
           }
        }
    }

    return $member;
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