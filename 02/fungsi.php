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
                $jumlah += hitungSisiOmset($data, $v['id'], 'kanan');
            }
        }
        else {
            if( ($v['induk_id'] == $indukId) ) 
                $jumlah += $v['omset'];
        }
    }

    return $jumlah;
}

function getBTree($data, $id = 0) {
    $indukId = 0;
    $result = array();

    $adjust = function($data, $indukId) use (&$adjust) {
        $result = array();

        foreach($data as $v) {
            if($v['induk_id'] == $indukId) {
                $children = $adjust($data, $v['id']);
                $omsetKanan = $omsetKiri = $kanan = $kiri = 0;

                $kanan = hitungSisi($data, $v['id'], 'kanan');
                $kiri  = hitungSisi($data, $v['id'], 'kiri');
                
                $omsetKanan = hitungSisiOmset($data, $v['id'], 0, 'kanan');
                $omsetKiri = hitungSisiOmset($data, $v['id'], 0, 'kiri');

                $result[] = [
                    'id'=>$v['id'],
                    'nomor'=>$v['nomor'],
                    'nama'=>$v['nama'],
                    'induk_id'=>$v['induk_id'],
                    'foto'=>$v['foto'],
                    'id'=>$v['id'],
                    'sisi'=>$v['sisi'],
                    'head'=>$v['id']."#".$v['sisi'],
                    'id'=>$v['id'],
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

    if(empty($id)) {
        foreach($data as $v) {
            if($v['id'] == $id) {
                $indukId = $v['id'];

                $omsetKanan = $omsetKiri = $kanan = $kiri = 0;

                $kanan = hitungSisi($members, $v['id'], 'kanan');
                $kiri  = hitungSisi($members, $v['id'], 'kiri');
                
                $omsetKanan = hitungSisiOmset($members, $v['omset'], 'kanan');
                $omsetKiri = hitungSisiOmset($members, $v['omset'], 'kiri');
                
                $result[0] = [
                    'id'=>$v['id'],
                    'nomor'=>$v['nomor'],
                    'nama'=>$v['nama'],
                    'induk_id'=>$v['induk_id'],
                    'foto'=>$v['foto'],
                    'id'=>$v['id'],
                    'sisi'=>$v['sisi'],
                    'head'=>$v['nomor']."#".$v['sisi'],
                    'id'=>$v['id'],
                    'contents'=>'<img src="./Images/' . $v['foto'] .'" class="img-btree" data-id="' . $v['id'] . '" style="width:auto;height:125px"/><br/>
                                    <span>' . $kiri . ' | ' . $kanan . ' </span><br>
                                 <span>' . $omsetKiri . ' | ' . $omsetKanan . ' </span><br>
                                    <label>' . $v['nomor'] . '</label>
                                    <h4>' . $v['nama'] .'</h4>'
                    ];
            }
        }

        $result[0]['children'] = $adjust($data, $indukId);
    }
    else {
        $result = $adjust($data, $indukId);
    }

    return $result;
}

function getSpillOverMember($data, $id, $sisi = 'kiri') {
    $result = [];

    if(!empty($data) && !empty($id) && !empty($sisi)) {
        foreach($data as $v) {

        }

    }

    return $result;
}