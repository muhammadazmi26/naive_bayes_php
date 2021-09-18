<?php

#
# Metode Naive Bayes
# 18 September 2021
# PHP Version 5
#


          # ['Outlook','Temp','Humidity','Windy','Play Golf']
$dataset =  array(['Rainy','Hot','High','FALSE','No'],    # 1
            ['Rainy','Hot','High','TRUE','No'],
            ['Overcast','Hot','High','FALSE','Yes'], 
            ['Sunny','Mild','High','FALSE','Yes'],    
            ['Sunny','Cool','Normal','FALSE','Yes'],      # 5
            ['Sunny','Cool','Normal','TRUE','No'],
            ['Overcast','Cool','Normal','TRUE','Yes'],
            ['Rainy','Mild','High','FALSE','No'],
            ['Rainy','Cool','Normal','FALSE','Yes'],
            ['Sunny','Mild','Normal','FALSE','Yes'],      # 10
            ['Rainy','Mild','Normal','TRUE','Yes'],
            ['Overcast','Mild','High','TRUE','Yes'],   
            ['Overcast','Hot','Normal','FALSE','Yes'],
            ['Sunny','Mild','High','TRUE','No']);         # 14
            
// print_r($dataset);

# Memasukkan data  kedalam  $data_uji
$data_uji = ['Rainy', 'Mild', 'High', 'FALSE'];

echo("|------------------------------------------|");echo("</br>");
echo("|          METODE NAIVE BAYES              |");echo("</br>"); 
echo("|------------------------------------------|");echo("</br>"); 
//////////////////////////////

$jumlah_dataset = sizeof($dataset);
echo("</br>");echo("</br>");
echo("JUMLAH DATASET : ".$jumlah_dataset);

///////////////////////////////

$jumlah_kolom = sizeof($dataset[0]);
echo("</br>");echo("</br>");
echo("JUMLAH KOLOM : ".$jumlah_kolom);

/////////////////////////////

function mendapatkan_isi_tiap_kolom($dataset, $jumlah_kolom){
    $isi_kolom_ke = array();
    for ($i = 0; $i < $jumlah_kolom; $i++) {
        $tampung = array();
        for ($j = 0; $j < sizeof($dataset); $j++) {
            array_push($tampung, $dataset[$j][$i]);
        }
        array_push($isi_kolom_ke, $tampung);
    }
    # print(isi_kolom_ke) # print semua isi kolom, yg terdiri dari 5 indek, masing-masing indek punya 14 indeks
    return $isi_kolom_ke;
}

$isi_kolom_ke = mendapatkan_isi_tiap_kolom($dataset, $jumlah_kolom);
// print_r($isi_kolom_ke); 

////////////////////////

echo("</br>");echo("</br>");
$kolom_label = $isi_kolom_ke[sizeof($isi_kolom_ke)-1]; // isi label/kelas
$cc = array_unique($kolom_label);
// print_r($cc);
$isi_label = array_values($cc); //$cc harus dimasukkan ke method array value agar indexnya berurutan/di-reset
echo("ISI LABEL : ");
print_r($isi_label);
echo("</br>");echo("</br>");

/////////////////////////

$counter_label = array_count_values($isi_kolom_ke[sizeof($isi_kolom_ke)-1]);
// print_r($counter_label);
    
$banyak_label = array();
for ($i = 0; $i < sizeof($isi_label); $i++) {
    array_push($banyak_label, $counter_label[$isi_label[$i]]);
}  
echo("BANYAK MASING2 LABEL : ");
print_r($banyak_label); 
    

echo("</br>");echo("</br>");
echo("DATA TESTING : ");
print_r($data_uji);
    
//////////

function cek_kasus_sama_dgn_klas($isi_atribut_apa, $dataset, $isi_label_apa){
    $jumlah = 0;
    for ($i = 0; $i < sizeof($dataset); $i++) {
        $apakah_ada = in_array($isi_atribut_apa, $dataset[$i], TRUE) && in_array($isi_label_apa, $dataset[$i], TRUE);
        if($apakah_ada == TRUE){
            $jumlah = $jumlah + 1;
        }
    }

    return $jumlah;
}



# Mencari P(X|Ci) atau jumlah kasus yang sama dengan class yang sama
function mencari_jumlah_kasus_sama($data_uji, $dataset, $isi_label){
    $hasil_jumlah_kasus_sama = [];
    for ($i = 0; $i < sizeof($data_uji); $i++) {
        $tampung = [];
        for ($j = 0; $j < sizeof($isi_label); $j++) {
            array_push($tampung, cek_kasus_sama_dgn_klas($data_uji[$i], $dataset, $isi_label[$j]));
        }
        array_push($hasil_jumlah_kasus_sama, $tampung);
    }
    echo("</br>");echo("</br>"); echo("</br>"."JUMLAH KASUS YANG SAMA DENGAN KELAS : ");print("<pre>".print_r($hasil_jumlah_kasus_sama ,true)."</pre>");
    return $hasil_jumlah_kasus_sama;
}    
  
$hasil_jumlah_kasus_sama = mencari_jumlah_kasus_sama($data_uji, $dataset, $isi_label);


# Menghitung P(Ci) atau jumlah class / label 
function mencari_prob_label($label, $jumlah_dataset){
    $probabilitas_label = [];
    for ($i = 0; $i < sizeof($label); $i++) {
        array_push($probabilitas_label, $label[$i]/floatval($jumlah_dataset));
    }
    echo("</br>"); echo("</br>"."PROBABILITAS LABEL/KELAS : ");print("<pre>".print_r($probabilitas_label ,true)."</pre>");
    return $probabilitas_label;
}

$probabilitas_label = mencari_prob_label($banyak_label, $jumlah_dataset);


# cek nilai probabilitas atribut ke-n
function menghitung_prob_atribut($hasil_jlh_kasus_sama, $banyak_label){
    $probabilitas_atribut = [];
    for ($n = 0; $n < sizeof($hasil_jlh_kasus_sama); $n++) {    
        $probabilitas_satu = [];
        for ($i = 0; $i < sizeof($banyak_label); $i++) {    
            array_push($probabilitas_satu, floatval($hasil_jlh_kasus_sama[$n][$i])/$banyak_label[$i]);
        }
        array_push($probabilitas_atribut, $probabilitas_satu);
    }
    echo("</br>"); echo("</br>"."PROBABILITAS ATRIBUT : ");print("<pre>".print_r($probabilitas_atribut ,true)."</pre>");
    return $probabilitas_atribut;
}

$probabilitas_atribut = menghitung_prob_atribut($hasil_jumlah_kasus_sama, $banyak_label);


# ubah bentuk array # baris jadi kolom, kolom jadi baris supaya dapat dikalikan
function ubah_bentuk_prob_atribut($label, $probabilitas_atribut){
    $ubah_bentuk_prob_atribut = [];
    for ($i = 0; $i < sizeof($label); $i++) {    
        $tampung_prob = [];
        for ($j = 0; $j < sizeof($probabilitas_atribut); $j++) {    
            array_push($tampung_prob, $probabilitas_atribut[$j][$i]);
        }
        array_push($ubah_bentuk_prob_atribut, $tampung_prob);
    }
    echo("</br>"); echo("</br>"."HASIL UBAH BENTUK PROB ATRIBUT : ");print("<pre>".print_r($ubah_bentuk_prob_atribut ,true)."</pre>");
    return $ubah_bentuk_prob_atribut;
}

$ubah_bentuk_prob_atribut = ubah_bentuk_prob_atribut($banyak_label, $probabilitas_atribut);


# mengalikan tiap probabilitas atribut
function kalikan_prob_atribut($banyak_label, $probabilitas_atribut, $probabilitas_atribut_ubah){
    $hasil_kali_atribut = [];
    for ($i = 0; $i < sizeof($banyak_label); $i++) { 
        $m=1;
        for ($j = 0; $j < sizeof($probabilitas_atribut); $j++) {    
            $m = $m*$probabilitas_atribut_ubah[$i][$j];
        }
        array_push($hasil_kali_atribut, $m);
    }
    echo("</br>"); echo("</br>"."HASIL PERKALIAN TIAP PROB ATRIBUT : ");print("<pre>".print_r($hasil_kali_atribut ,true)."</pre>");
    return $hasil_kali_atribut;
}

$hasil_kali_atribut = kalikan_prob_atribut($banyak_label, $probabilitas_atribut, $ubah_bentuk_prob_atribut);


# mengalikan probabilitas atribut dengan probabilitas label
function kalikan_prob_label_dan_prob_atribut($banyak_label, $probabilitas_label, $hasil_kali_atribut){
    $hasil_kali_all_prob = [];
    for ($i = 0; $i < sizeof($banyak_label); $i++) { 
        array_push($hasil_kali_all_prob, $hasil_kali_atribut[$i]*$probabilitas_label[$i]);
    }
    echo("</br>"); echo("</br>"."HASIL PERKALIAN PROB ATRIBUT DAN PROB LABEL : ");print("<pre>".print_r($hasil_kali_all_prob ,true)."</pre>");
    return $hasil_kali_all_prob;
}

$hasil_kali_all_prob = kalikan_prob_label_dan_prob_atribut($banyak_label, $probabilitas_label, $hasil_kali_atribut);


# mencari hasil probabilitas tertinggi
function mencari_nilai_prob_tertinggi($hasil_kali_all_prob, $isi_label){
    $index_nilai_tertinggi = array_search(max($hasil_kali_all_prob), $hasil_kali_all_prob);
    return $isi_label[$index_nilai_tertinggi];
}

$hasil_klasifikasi = mencari_nilai_prob_tertinggi($hasil_kali_all_prob, $isi_label);
echo("</br>"); echo("</br>"."HASIL KLASIFIKASI : ".$hasil_klasifikasi);

//selesai