<?php 


try {
    $dbh = new PDO('mysql:host=localhost;dbname=binari', 'root', '');
    $members = array();
    foreach($dbh->query('SELECT * from nng_members ORDER BY id ASC') as $row) {
        array_push($members, $row);
    }
    $dbh = null;
    
    /*
    [0] => Array
        (
            [id] => 1
            [0] => 1
            [nomor] => 11001
            [1] => 11001
            [nama] => bjhabibie
            [2] => bjhabibie
            [induk_id] => 0
            [3] => 0
            [foto] => bjhabibie.jog
            [4] => bjhabibie.jog
            [no_kiri] => 0
            [5] => 0
            [no_kanan] => 1
            [6] => 1
            [sisi] => kiri
            [7] => 
        )
    */

    $adjust = function($data, $indukId) use (&$adjust) {
        $result = array();

        foreach($data as $v) {
            if($v['induk_id'] == $indukId) {
                $children = $adjust($data, $v['id']);
                $kanan = $kiri = 0;
               
                $hitungSisi = function($data, $indukId, $sisi, $initLvl = 0, $jumlah = 0) use(&$hitungSisi) {
            
                    foreach($data as $v) {
                        if(0 == $initLvl) {
                            if( ($v['induk_id'] == $indukId) && ($v['sisi'] == $sisi) ) {
                                ++$jumlah;
                                $jumlah += $hitungSisi($data, $v['id'], 'kiri');
                                $jumlah += $hitungSisi($data, $v['id'], 'kanan');
                            }
                        }
                        else {
                            if( ($v['induk_id'] == $indukId) ) 
                                $jumlah++;
                        }
                    }
            
                    return $jumlah;
                };                

                $kanan = $hitungSisi($data, $v['id'], 'kanan');
                $kiri  = $hitungSisi($data, $v['id'], 'kiri');

                $result[] = [
                    'id'=>$v['id'],
                    'nomor'=>$v['nomor'],
                    'nama'=>$v['nama'],
                    'induk_id'=>$v['induk_id'],
                    'foto'=>$v['foto'],
                    'id'=>$v['id'],
                    'sisi'=>$v['sisi'],
                    'head'=>$v['nomor'],
                    'id'=>$v['id'],
                    'contents'=>'<img src="./Images/' . $v['foto'] .'" class="img-btree" data-id="' . $v['id'] . '" style="width:auto;height:125px"/><br/>
                                 <span>' . $kiri . ' | ' . $kanan . ' </span><br>
                                 <label>' . $v['nomor'] . '</label>
                                 <h4>' . $v['nama'] .'</h4>',
                    'children'=>empty($children) ? null : $children
                ];
            }
        }

        return $result;
    };

    $result1 = $adjust($members, 0);
    
    // echo '<pre>';
    // print_r($members);
    // // print_r($result);
    // echo '</pre>';
    echo json_encode($result1);

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