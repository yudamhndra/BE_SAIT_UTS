<?php
include 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        viewNilaiMahasiswa($mysqli, $input);
        break;

    case 'POST':
        addNilaiMahasiswa($mysqli, $input);
        break;

    case 'PUT':
        updateNilaiMahasiswa($mysqli, $input);
        break;

    case 'DELETE':
        deleteNilaiMahasiswa($mysqli, $input);
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}

function viewNilaiMahasiswa($mysqli, $input) {
    $nim = isset($_GET['nim']) ? $_GET['nim'] : null;
    if ($nim) {
        $sql = "SELECT * FROM view_data_lengkap_mahasiswa WHERE nim = '$nim'";
    } else {
        $sql = "SELECT * FROM view_data_lengkap_mahasiswa";
    }
    $data=array();
    $result=$mysqli->query($sql);
    while($row=mysqli_fetch_object($result))
    {
        $data[]=$row;
    }
    $response=array(
        'status' => 1,
        'message' =>'Get List Mahasiswa Successfully.',
        'data' => $data
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}

function addNilaiMahasiswa($mysqli, $input) {
    $nim = isset($input['nim']) ? $input['nim'] : null;
    $kode_mk = isset($input['kode_mk']) ? $input['kode_mk'] : null;
    $nilai = isset($input['nilai']) ? $input['nilai'] : null;
    
    if ($nim && $kode_mk && $nilai) {
        $sql = "INSERT INTO perkuliahan (nim, kode_mk, nilai) VALUES ('$nim', '$kode_mk', '$nilai')";
        $result = $mysqli->query($sql);
        
        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Nilai mahasiswa berhasil ditambahkan',
                'data' => array(
                    'nim' => $nim,
                    'kode_mk' => $kode_mk,
                    'nilai' => $nilai
                )
            );
        } else {
            $response = array(
                'success' => false,
                'error' => 'Gagal menambahkan nilai mahasiswa'
            );
        }
    } else {
        $response = array(
            'success' => false,
            'error' => 'Data tidak lengkap. Pastikan nim, kode_mk, dan nilai tersedia'
        );
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function updateNilaiMahasiswa($mysqli, $input) {
    $nim = isset($_GET['nim']) ? $_GET['nim'] : null;
    $kode_mk = isset($_GET['kode_mk']) ? $_GET['kode_mk'] : null;
    $nilai = $input['nilai'];
    if ($nim && $kode_mk) {
        $sql = "UPDATE perkuliahan SET nilai = '$nilai' WHERE nim = '$nim' AND kode_mk = '$kode_mk'";
        $result = $mysqli->query($sql);
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Nilai mahasiswa berhasil diupdate',
                'data' => [
                    'nim' => $nim,
                    'kode_mk' => $kode_mk,
                    'nilai' => $nilai
                ]
            ];
        } else {
            $response = [
                'success' => false,
                'error' => 'Gagal mengupdate nilai mahasiswa'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'error' => 'NIM atau kode_mk tidak ditemukan'
        ];
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}


function deleteNilaiMahasiswa($mysqli, $input) {
    $nim = isset($_GET['nim']) ? $_GET['nim'] : null;
    $kode_mk = isset($_GET['kode_mk']) ? $_GET['kode_mk'] : null;
    if ($nim && $kode_mk) {
        $sql = "DELETE FROM perkuliahan WHERE nim = '$nim' AND kode_mk = '$kode_mk'";
    } else {
        echo json_encode(['error' => 'NIM atau kode_mk tidak ditemukan']);
        exit;
    }
    $result = $mysqli->query($sql);
    
    if ($mysqli->affected_rows > 0) {
        echo json_encode(['success' => 'Data berhasil dihapus'], JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan'], JSON_PRETTY_PRINT);
    }
}
?>
