<?php 
require_once './koneksi.php';

if(isset($_POST['action']) == "table_data"){
        $columns = array( 
                        0 =>'id', 
                        1 =>'nama',
                        2=> 'kelas',
                        3=> 'jurusan',
                    );

        $querycount = $mysqli->query("SELECT count(id) as jumlah FROM tbl_siswa");
        $datacount = $querycount->fetch_array();


        $totalData = $datacount['jumlah'];
                
        $totalFiltered = $totalData; 

        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
                
        if(empty($_POST['search']['value']))
        {            
            $query = $mysqli->query("SELECT * FROM tbl_siswa order by $order $dir LIMIT $limit OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $mysqli->query("SELECT * FROM tbl_siswa WHERE nama LIKE '%$search%' or kelas LIKE '%$search%' or jurusan LIKE '%$search%'order by $order $dir LIMIT $limit OFFSET $start");


            $querycount = $mysqli->query("SELECT count(id) as jumlah FROM tbl_siswa WHERE nama LIKE '%$search%'  or kelas LIKE '%$search%' or jurusan LIKE '%$search%' ");
            $datacount = $querycount->fetch_array();
            $totalFiltered = $datacount['jumlah'];
        }

        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
            while ($r = $query->fetch_array())
            {
                $nestedData['no'] = $no;
                $nestedData['nama'] = $r['nama'];
                $nestedData['kelas'] = $r['kelas'];
                $nestedData['jurusan'] = $r['jurusan'];
                $nestedData['aksi'] = "<button onClick='update_siswa(".$r['id'].")' class='btn-primary btn-sm'>Update</button><button onClick='delete_siswa(".$r['id'].")' class='btn-danger btn-sm'>Delete</button>";
                $data[] = $nestedData;
                $no++;
            }
        }
            
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
                
        echo json_encode($json_data);
}

if(isset($_GET['action']) == "add_data"){
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
            
    // Insert user data into table
    $result = mysqli_query($mysqli, "INSERT INTO tbl_siswa VALUES (null,'$nama','$kelas','$jurusan')");
    
    if($result){
        echo json_encode(array("status"=>TRUE, "message"=>"Data Berhasil Disimpan"));
    }else{
        echo json_encode(array("status"=>FALSE, "message"=>"Data Gagal Disimpan"));
    }
    // Show message when user added
}

if(isset($_GET['actions']) == "update_data"){
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
            
    // Insert user data into table
    $result = mysqli_query($mysqli, "UPDATE tbl_siswa SET nama='$nama', kelas='$kelas', jurusan='$jurusan' WHERE id='$id'");
    
    if($result){
        echo json_encode(array("status"=>TRUE, "message"=>"Data Berhasil DiUpdate"));
    }else{
        echo json_encode(array("status"=>FALSE, "message"=>"Data Gagal DiUpdate"));
    }
    // Show message when user added
}

if(isset($_POST['actions']) == "show"){
    $id = $_POST['id'];
	$query_mysql = mysqli_query($mysqli, "SELECT * FROM tbl_siswa WHERE id='$id'");
    $data = $query_mysql->fetch_assoc();
    echo json_encode($data);
}

if(isset($_POST['actiond']) == "delete"){
    $id = $_POST['id'];
    $hapus = mysqli_query($mysqli, "DELETE FROM tbl_siswa WHERE id='$id'");
    if($hapus){
        echo json_encode(array("status"=>TRUE, "message"=>"Data Berhasil Dihapus"));
    }else{
        echo json_encode(array("status"=>FALSE, "message"=>"Data Gagal Dihapus"));
    }
}

if(isset($_GET['actionlog']) == "login"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql_check="select * from tbl_user where 
                username='".$username."'";

    $result = mysqli_query($mysqli,$sql_check);

    $getUser = mysqli_num_rows($result);

    //print_r($getUser); die();
    $getDataUser = mysqli_fetch_array($result);
    if ($getUser === 1) 
    {
        if (password_verify($password,$getDataUser['password'])) 
        {
            $random=md5(rand());
            $id=$getDataUser['id_user'];
            $res = mysqli_query($mysqli, "UPDATE tbl_user SET token='$random' WHERE id_user='$id'");
            if($res){
                session_start();
                $_SESSION['token'] = $random;
                echo json_encode(array("status"=>TRUE, "message"=>"Login Succesfully!!"));
            }else{
                echo json_encode(array("status"=>FALSE, "message"=>"Server Error!!"));
            }
        }
        else
        {
            echo json_encode(array("status"=>FALSE, "message"=>"Login Failed!!!"));
        }	
    }
    else
    {
        echo json_encode(array("status"=>FALSE, "message"=>"Login Failed!!!"));
    }
}

if(isset($_GET['actionlgout']) == "logout"){
    session_start();
    session_destroy();
    echo json_encode(array("status"=>TRUE, "message"=>"Logout Succesfully!!"));
}

?>