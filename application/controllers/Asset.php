<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends CI_Controller {

    public function __construct() {
		parent::__construct();

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        
        $this->load->model('api_model');
        $this->load->library('Pdf');
        $this->load->library('PdfWithBarcode');
    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
        // $pdf->Output('D', 'report.pdf'); // Download
	}

    private function print_label($title, $id, $serial_number, $qty, $row, $col) {
        $pdf = new PdfWithBarcode('L', 'mm','Letter');

        for ($i = 0; $i < $row; $i++) {
            if (($i) % 8 === 0) {
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(0,7,$title,0,1,'C');
                $pdf->Cell(10,7,'',0,1);
                $pdf->SetFont('Arial','',10);
            }

            for ($j = 0; $j < $col; $j++) {
                if ((($i * $col) + ($j + 1)) <= $qty) {
                    $id_pad = str_pad($id, 7, '0', STR_PAD_LEFT);
                    $serial_number_pad = $serial_number . "/" . str_pad(($i * $col) + ($j + 1), 7, '0', STR_PAD_LEFT);
                    
                    // Generate Cell
                    if ($j < $col - 1) {
                        $pdf->Cell(52,20,'',1,0,'C');
                    } else {
                        $pdf->Cell(52,20,'',1,1,'C');
                    }
    
                    // Generate Barcode
                    $pdf->Code39(12 + (52 * $j),27 + (20 * ($i % 8)), $id_pad, $serial_number_pad,1,10); // Position Penjumlahan 52 * column index dari 12
                } else break;
            }
        }

        // $pdf->Output(); // Preview
        $pdf->Output('D', 'print_label_'.date("YmdHis").'.pdf'); // Download
    }

    public function download_label_asset() {
        $data_profile = $this->api_model->check_auth_get();

        if (isset($data_profile)) {
            $aset_id = $this->input->get("aset_id");
            if (isset($aset_id) && !empty($aset_id)) {
                $response = $this->api_model->get_asset(
                    $aset_id
                );
    
                if (isset($response)) {
                    
                    return $this->print_label(
                        "LABEL ".strtoupper($response->nama_aset)." BY SERIAL NUMBER",
                        (int) $response->id,
                        $response->nomor_seri,
                        $response->jumlah,
                        (int) ($response->jumlah) / 5,
                        5
                    );

                } else {
                    return $this->api_model
                    ->response(400, "Data aset dengan id $aset_id tidak ditemukan", null);
                }

            } else {
                // Request tidak valid
                return $this->api_model
                ->response(400, "Request tidak valid", null);
            }
        }
    }

    public function list()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $page = $this->input->get("page");
            $limit = $this->input->get("limit");
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->pagination_asset(
                isset($page) ? $page : 0,
                isset($limit) ? $limit : 10,
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (count($response) == 0) {
                return $this->api_model
                ->response(200, "Data $query tidak ditemukan dengan filter $filter_by", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar aset, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function detail()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $aset_id = $this->input->get("aset_id");
            if (isset($aset_id) && !empty($aset_id)) {
                $response = $this->api_model->get_asset(
                    $aset_id
                );
    
                if (isset($response)) {
                    return $this->api_model
                    ->response(200, "Berhasil mendapatkan detail aset", $response);
                } else {
                    return $this->api_model
                    ->response(400, "Data aset dengan id $aset_id tidak ditemukan", null);
                }

            } else {
                // Request tidak valid
                return $this->api_model
                ->response(400, "Request tidak valid", null);
            }
        }
    }

    public function create()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($request->nama_aset) && isset($request->jenis_aset)
            && isset($request->nomor_seri) && isset($request->kondisi) && isset($request->harga) && isset($request->jumlah)) {

                if ($request->kondisi != "tersedia" && $request->kondisi != "habis") {
                    return $this->api_model->response(500, "Kondisi aset hanya bisa \'tersedia\' atau \'habis\'", null);
                }
                if (!is_int($request->harga)) {
                    return $this->api_model->response(500, "Harga hanya bisa angka pecahan contoh: 10.00", null);
                }
                if (!is_int($request->jumlah)) {
                    return $this->api_model->response(500, "Jumlah hanya bisa angka desimal contoh: 10", null);
                }
                if ($request->jumlah > 99999) {
                    return $this->api_model->response(500, "Jumlah tidak boleh lebih dari 99999", null);
                }
                if ($request->jumlah < 0) {
                    return $this->api_model->response(500, "Jumlah tidak boleh kurang dari 0", null);
                }

                // Doing Create
                $status_insert = $this->api_model->insert_asset(array(
                    "nama_aset"     => $request->nama_aset,
                    "jenis_aset"    => $request->jenis_aset,
                    "nomor_seri"    => $request->nomor_seri,
                    "kondisi"       => $request->kondisi,
                    "harga"         => $request->harga,
                    "jumlah"        => $request->jumlah,
                ));
    
                if (isset($status_insert)) {
                    // Request success
                    return $this->api_model->response(200, "Berhasil membuat aset", $request);
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Gagal membuat aset", null);
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Mohon untuk mengisi semua data", null);
            }
        }
    }

    public function update()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $aset_id = $this->input->get("aset_id");
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($aset_id)) {
                $is_user_exists = $this->api_model->get_asset($aset_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Aset tidak ditemukan", null);
                } else {
                    // Sanity Check
                    if (isset($request->nama_aset) && isset($request->jenis_aset)
                    && isset($request->nomor_seri) && isset($request->kondisi) && isset($request->harga) && isset($request->jumlah)) {

                        if ($request->kondisi != "tersedia" && $request->kondisi != "habis") {
                            return $this->api_model->response(500, "Kondisi aset hanya bisa \'tersedia\' atau \'habis\'", null);
                        }
                        if (!is_int($request->harga)) {
                            return $this->api_model->response(500, "Harga hanya bisa angka pecahan contoh: 10.00", null);
                        }
                        if (!is_int($request->jumlah)) {
                            return $this->api_model->response(500, "Jumlah hanya bisa angka desimal contoh: 10", null);
                        }
                        if ($request->jumlah > 99999) {
                            return $this->api_model->response(500, "Jumlah tidak boleh lebih dari 99999", null);
                        }
                        if ($request->jumlah < 0) {
                            return $this->api_model->response(500, "Jumlah tidak boleh kurang dari 0", null);
                        }

                        // Check Perubahan Qty
                        if ($is_user_exists->jumlah != $request->jumlah) {
                            $status_insert = $this->api_model->insert_asset_used(array(
                                "id_aset"           => $aset_id,
                                "id_pencatat"       => $data_profile->id,
                                "jenis_transaksi"   => $is_user_exists->jumlah < $request->jumlah ? "masuk" : "keluar",
                                "jumlah"            => $is_user_exists->jumlah - $request->jumlah,
                                "status_keluar"     => null, 
                                "is_closed"         => 1,
                                "is_from_change"    => 1,
                                "keterangan"        => "User melakukan update jumlah pada data master aset",
                                "tgl_transaksi"     => date("Y-m-d H:i:s")
                            ));
                        }

                        // Doing Create
                        $status_insert = $this->api_model->update_asset(array(
                            "nama_aset"     => $request->nama_aset,
                            "jenis_aset"    => $request->jenis_aset,
                            "nomor_seri"    => $request->nomor_seri,
                            "kondisi"       => $request->kondisi,
                            "harga"         => $request->harga,
                            "jumlah"        => $request->jumlah,
                        ), $aset_id);
            
                        if (isset($status_insert)) {
                            // Request success
                            return $this->api_model->response(200, "Berhasil mengubah aset", $request);
                        } else {
                            // Failed to create user
                            return $this->api_model->response(500, "Gagal mengubah aset", null);
                        }
                    } else {
                        // Request tidak valid
                        return $this->api_model->response(400, "Mohon untuk mengisi semua data", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }

    public function remove()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $aset_id = $this->input->get("aset_id");

            // Sanity Check
            if (isset($aset_id)) {
                $is_user_exists = $this->api_model->get_asset($aset_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Aset tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "Aset sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $body = array("is_delete" => 1);
                    $status_update = $this->api_model->update_asset($body, $aset_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus aset", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus aset", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }

    public function list_used()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $id_aset = $this->input->get("id_aset");
            $page = $this->input->get("page");
            $limit = $this->input->get("limit");
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->pagination_asset_used(
                $id_aset,
                isset($page) ? $page : 0,
                isset($limit) ? $limit : 10,
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (count($response) == 0) {
                return $this->api_model
                ->response(200, "Data $query tidak ditemukan dengan filter $filter_by", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar riwayat aset, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function list_used_all()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $page = $this->input->get("page");
            $limit = $this->input->get("limit");
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");
            $status_keluar = $this->input->get("status_keluar");
            $is_closed = $this->input->get("is_closed");

            $response = $this->api_model->pagination_asset_used_all(
                isset($page) ? $page : 0,
                isset($limit) ? $limit : 10,
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : "",
                isset($status_keluar) ? $status_keluar : "",
                isset($is_closed) ? $is_closed : "",
            );

            if (count($response) == 0) {
                return $this->api_model
                ->response(200, "Data $query tidak ditemukan dengan filter $filter_by", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar riwayat aset, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function create_used_in()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($request->id_aset) && isset($request->id_pencatat)
            && isset($request->jumlah)) {

                if (!is_int($request->jumlah)) {
                    return $this->api_model->response(500, "Jumlah hanya bisa angka desimal contoh: 10", null);
                }
                if ($request->jumlah > 99999) {
                    return $this->api_model->response(500, "Jumlah tidak boleh lebih dari 99999", null);
                }
                if ($request->jumlah < 0) {
                    return $this->api_model->response(500, "Jumlah tidak boleh kurang dari 0", null);
                }

                $check_user = $this->api_model->get_profile($request->id_pencatat);
                if (!isset($check_user) || empty($check_user)) {
                    return $this->api_model->response(500, "User pencatat dengan id $request->id_pencatat tidak ditemukan", null);
                }

                $check_aset = $this->api_model->get_asset($request->id_aset);
                if (!isset($check_aset) || empty($check_aset)) {
                    return $this->api_model->response(500, "Aset dengan id $request->id_aset tidak ditemukan", null);
                }

                // Doing Create
                $status_insert = $this->api_model->insert_asset_used(array(
                    "id_aset"     => $request->id_aset,
                    "id_pencatat"    => $request->id_pencatat,
                    "jenis_transaksi"    => "masuk",
                    "jumlah"       => $request->jumlah,
                    "is_closed"         => 1,
                    "keterangan"        => isset($request->keterangan) ? $request->keterangan : "-",
                    "tgl_transaksi" => date("Y-m-d H:i:s")
                ));
    
                if (isset($status_insert)) {
                    $status_update = $this->api_model->update_asset(array(
                        "jumlah"    => ($check_aset->jumlah + $request->jumlah)
                    ), $request->id_aset);

                    // Request success
                    return $this->api_model->response(200, "Berhasil mencatat aset masuk", $request);
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Gagal mencatat aset masuk", null);
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Mohon untuk mengisi semua data", null);
            }
        }
    }

    public function create_used_out()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($request->id_aset) && isset($request->id_pencatat)
            && isset($request->jumlah) && isset($request->status_keluar)) {

                if (!is_int($request->jumlah)) {
                    return $this->api_model->response(500, "Jumlah hanya bisa angka desimal contoh: 10", null);
                }
                if ($request->jumlah > 99999) {
                    return $this->api_model->response(500, "Jumlah tidak boleh lebih dari 99999", null);
                }
                if ($request->jumlah < 0) {
                    return $this->api_model->response(500, "Jumlah tidak boleh kurang dari 0", null);
                }
                if ($request->status_keluar != "rusak" && $request->status_keluar != "pinjam" && $request->status_keluar != "dijual") {
                    return $this->api_model->response(500, "Status keluar aset hanya bisa \'rusak\', \'pinjam\' atau \'dijual\'", null);
                }

                $check_user = $this->api_model->get_profile($request->id_pencatat);
                if (!isset($check_user) || empty($check_user)) {
                    return $this->api_model->response(500, "User pencatat dengan id $request->id_pencatat tidak ditemukan", null);
                }

                $check_aset = $this->api_model->get_asset($request->id_aset);
                if (!isset($check_aset) || empty($check_aset)) {
                    return $this->api_model->response(500, "Aset dengan id $request->id_aset tidak ditemukan", null);
                }

                // Doing Create
                $status_insert = $this->api_model->insert_asset_used(array(
                    "id_aset"     => $request->id_aset,
                    "id_pencatat"    => $request->id_pencatat,
                    "jenis_transaksi"    => "keluar",
                    "jumlah"       => $request->jumlah,
                    "status_keluar"       => $request->status_keluar,
                    "is_closed"         => ($request->status_keluar != "pinjam") ? 1 : 0,
                    "keterangan"        => isset($request->keterangan) ? $request->keterangan : "-",
                    "tgl_transaksi" => date("Y-m-d H:i:s")
                ));
    
                if (isset($status_insert)) {
                    if ($request->status_keluar != "pinjam") {
                        $status_update = $this->api_model->update_asset(array(
                            "jumlah"    => ($check_aset->jumlah - $request->jumlah)
                        ), $request->id_aset);
                    }

                    // Request success
                    return $this->api_model->response(200, "Berhasil mencatat aset masuk", $request);
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Gagal mencatat aset masuk", null);
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Mohon untuk mengisi semua data", null);
            }
        }
    }

    public function close_used_out()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($request->id_transaction)) {

                $check_aset_used = $this->api_model->get_asset_used($request->id_transaction);
                if (!isset($check_aset_used) || empty($check_aset_used)) {
                    return $this->api_model->response(500, "Transaksi aset dengan id $request->id_transaction tidak ditemukan", null);
                } else if ($check_aset_used->is_closed == 1) {
                    return $this->api_model->response(500, "Transaksi aset dengan id $request->id_transaction sudah ditutup", null);
                }

                $check_aset = $this->api_model->get_asset($check_aset_used->id_aset);
                if (!isset($check_aset) || empty($check_aset)) {
                    return $this->api_model->response(500, "Aset dengan id $request->id_transaction tidak ditemukan", null);
                }

                // Doing Create
                $status_update = $this->api_model->update_asset_used(array(
                    "is_closed"         => 1,
                    "tgl_transaksi" => date("Y-m-d H:i:s")
                ), $request->id_transaction);
    
                if (isset($status_update)) {
                    if ($check_aset_used->status_keluar != "pinjam") {
                        $status_update = $this->api_model->update_asset(array(
                            "jumlah"    => ($check_aset->jumlah + $check_aset_used->jumlah)
                        ), $check_aset_used->id_aset);
                    }

                    // Request success
                    return $this->api_model->response(200, "Berhasil mengembalikan aset yang dipinjam", $request);
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Gagal mengembalikan aset yang dipinjam", null);
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Mohon untuk mengisi semua data", null);
            }
        }
    }

    public function list_mapping()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $id_aset = $this->input->get("id_aset");
            $page = $this->input->get("page");
            $limit = $this->input->get("limit");
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->pagination_asset_mapping(
                $id_aset,
                isset($page) ? $page : 0,
                isset($limit) ? $limit : 10,
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (count($response) == 0) {
                return $this->api_model
                ->response(200, "Data $query tidak ditemukan dengan filter $filter_by", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar mapping aset, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function create_mapping()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($request->id_aset) && isset($request->id_gedung)) {

                $gedung = $this->api_model->get_gedung($request->id_gedung);
                if (!isset($gedung) || empty($gedung)) {
                    return $this->api_model->response(500, "Gedung dengan id $request->id_gedung tidak ditemukan", null);
                }

                $aset = $this->api_model->get_asset($request->id_aset);
                if (!isset($aset) || empty($aset)) {
                    return $this->api_model->response(500, "Aset dengan id $request->id_aset tidak ditemukan", null);
                }

                $check_is_exists = $this->api_model->is_asset_mapping_exists(
                    $request->id_aset,
                    $request->id_gedung,
                    isset($request->id_lantai) ? $request->id_lantai : null,
                    isset($request->id_ruangan) ? $request->id_ruangan : null,
                );

                if (!isset($check_is_exists)) {
                    // Doing Create
                    $status_insert = $this->api_model->insert_asset_mapping(array(
                        "id_aset"     => $request->id_aset,
                        "id_gedung"    => $request->id_gedung,
                        "id_lantai"    => isset($request->id_lantai) ? $request->id_lantai : null,
                        "id_ruangan"    => isset($request->id_ruangan) ? $request->id_ruangan : null,
                    ));
        
                    if (isset($status_insert)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil memapping aset", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal memapping aset", null);
                    }
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Mapping aset tersebut sudah ada sebelumnya", null);
                }

            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Mohon untuk mengisi semua data", null);
            }
        }
    }

    public function remove_mapping()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $id = $this->input->get("id");

            // Sanity Check
            if (isset($id)) {
                $is_user_exists = $this->api_model->get_asset_mapping($id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Mapping aset tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "Mapping aset sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $body = array("is_delete" => 1);
                    $status_update = $this->api_model->update_asset_mapping($body, $id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus mapping aset", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus mapping aset", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }    
}
