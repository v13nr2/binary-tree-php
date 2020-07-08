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
               
                $jumlah += $v["omset"];
                //if ?
                $jumlah += hitungSisiOmset($data, $v['id'],  'kiri');
                $jumlah += hitungSisiOmset($data, $v['id'],  'kanan');
            }
        }
        else {
            if( ($v['induk_id'] == $indukId) ) 
                $jumlah += $v["omset"];
        }
    }

    return $jumlah;
}

try {
    $dbh = new PDO('mysql:host=localhost;dbname=u7154823_api', 'u7154823_api', 'LPw_eHtQ)kY%');
    $members = array();
    foreach($dbh->query('SELECT * from nng_members ORDER BY sisi DESC, id ASC') as $row) {
        array_push($members, $row);
    }
    $dbh = null;

    $adjust = function($data, $indukId) use (&$adjust) {
        $result = array();

        foreach($data as $v) {
            if($v['induk_id'] == $indukId) {
                $children = $adjust($data, $v['id']);
                $omsetKanan = $omsetKiri = $kanan = $kiri = 0;

                $kanan = hitungSisi($data, $v['id'], 'kanan');
                $kiri  = hitungSisi($data, $v['id'], 'kiri');
                
                $omsetKanan = hitungSisiOmset($data, $v['id'],'kanan');
                //$omsetKiri = hitungSisiOmset($data, $v['id'], 'kiri',0, $v['omset']); //jika menjumlahkan dengan dirinya sendiri
                $omsetKiri = hitungSisiOmset($data, $v['id'], 'kiri');

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
                    'posisi_kecil' => "kiri",
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

    $indukId = 0;
    $result = array();
    if(!empty($_GET['id'])) {
        foreach($members as $v) {
            if($v['id'] == $_GET['id']) {
                $indukId = $v['id'];

                $omsetKanan = $omsetKiri = $kanan = $kiri = 0;

                $kanan = hitungSisi($members, $v['id'], 'kanan');
                $kiri  = hitungSisi($members, $v['id'], 'kiri');
                
                $omsetKanan = hitungSisiOmset($members, $v['id'], 'kanan');
                //$omsetKiri = hitungSisiOmset($members, $v['id'], 'kiri', 0, $v['omset']); //jika menjumlahkan dengan omsetnya sendiri
                $omsetKiri = hitungSisiOmset($members, $v['id'], 'kiri');
                
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
                    'posisi_kecil' => "kiri",
                    'contents'=>'<img src="./Images/' . $v['foto'] .'" class="img-btree" data-id="' . $v['id'] . '" style="width:auto;height:125px"/><br/>
                                    <span>' . $kiri . ' | ' . $kanan . ' </span><br>
                                 <span>' . $omsetKiri . ' | ' . $omsetKanan . ' </span><br>
                                    <label>' . $v['nomor'] . '</label>
                                    <h4>' . $v['nama'] .'</h4>'
                    ];
            }
        }
    }

    if(!empty($_GET['id']))
        $result[0]['children'] = $adjust($members, $indukId);
    else
        $result = $adjust($members, $indukId);
    
    // echo '<pre>';
    // print_r($members);
    // // print_r($result);
    // echo '</pre>';
    echo json_encode($result);
    //echo "sponsor";
    
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
/*
[{
                        "head": "A",
                        "id": "aa",
                        "contents": "<img src=\"./Images/bjhabibie.jpg\" style=\"width:100%\"/><br/><span>0 | 1</span><br><label>112200054</label><h4>A Contents</h4>",
                        "children": [
                            {
                                "head": "A-1",
                                "id": "a1",
                                "contents": "A-1 Contents",
                                "children": [
                                    { "head": "A-1-1", "id": "a11", "contents": "A-1-1 Contents" }
                                ]
                            },
                            {
                                "head": "A-2",
                                "id": "a2",
                                "contents": "A-2 Contents",
                                "children": [
                                    { "head": "A-2-1", "id": "a21", "contents": "A-2-1 Contents" },
                                    { "head": "A-2-2", "id": "a22", "contents": "A-2-2 Contents" }
                                ]
                            }
                        ]
                    }]
*/                    