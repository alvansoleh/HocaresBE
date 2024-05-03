<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

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
        
	}

    private function _header($file_name) {
        header('Content-Type: application/vnd-ms-excel');
        header('Content-Disposition: attachment; filename="'.$file_name.'_'.date("YmdHis").'.xls"');
        header('Cache-Control: max-age=0');
    }

    public function count_of_place()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $response = $this->api_model->get_count_place();

            return $this->api_model
            ->response(200, "Berhasil memuat data jumlah place", $response);
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function download_of_place_building()
    {
        $data_profile = $this->api_model->check_auth_get();

        if (isset($data_profile)) {
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->download_gedung(
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (empty($response)) {
                $this->_header("download_of_asset_transaction");
                echo("");
            } else {
                $excelData = "Building ID,";
                $excelData .= "Building Name\n";
                
                foreach ($response as $item) {
                    $excelData .= "{$item->id},";
                    $excelData .= "{$item->nama_gedung}\n";
                }
                
                $this->_header("download_of_place_building");
                header('Content-Length: ' . strlen($excelData));
                
                echo ($excelData);
            }
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function download_of_place_floor()
    {
        $data_profile = $this->api_model->check_auth_get();

        if (isset($data_profile)) {
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->download_lantai_gedung(
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (empty($response)) {
                $this->_header("download_of_asset_transaction");
                echo("");
            } else {
                $excelData = "Building ID,";
                $excelData .= "Floor ID,";
                $excelData .= "Building Name,";
                $excelData .= "Floor Name\n";
                
                foreach ($response as $item) {
                    $excelData .= "{$item->id_gedung},";
                    $excelData .= "{$item->id},";
                    $excelData .= "{$item->nama_gedung},";
                    $excelData .= "{$item->nama_lantai}\n";
                }
                
                $this->_header("download_of_place_floor");
                header('Content-Length: ' . strlen($excelData));
                
                echo ($excelData);
            }
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function download_of_place_room()
    {
        $data_profile = $this->api_model->check_auth_get();

        if (isset($data_profile)) {
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->download_ruangan_lantai(
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (empty($response)) {
                $this->_header("download_of_asset_transaction");
                echo("");
            } else {
                $excelData = "Building ID,";
                $excelData .= "Floor ID,";
                $excelData .= "Room ID,";
                $excelData .= "Building Name,";
                $excelData .= "Floor Name,";
                $excelData .= "Room Name\n";
                
                foreach ($response as $item) {
                    $excelData .= "{$item->id_gedung},";
                    $excelData .= "{$item->id_lantai},";
                    $excelData .= "{$item->id},";
                    $excelData .= "{$item->nama_gedung},";
                    $excelData .= "{$item->nama_lantai},";
                    $excelData .= "{$item->nama_ruangan}\n";
                }
                
                $this->_header("download_of_place_room");
                header('Content-Length: ' . strlen($excelData));
                
                echo ($excelData);
            }
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function count_of_aset()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $response = $this->api_model->get_count_asset();

            return $this->api_model
            ->response(200, "Berhasil memuat data jumlah aset", $response);
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function download_of_aset()
    {
        $data_profile = $this->api_model->check_auth_get();

        if (isset($data_profile)) {
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->download_asset(
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (empty($response)) {
                $this->_header("download_of_asset_transaction");
                echo("");
            } else {
                $excelData = "Asset ID,";
                $excelData .= "Asset Name,";
                $excelData .= "Type,";
                $excelData .= "Serial Number,";
                $excelData .= "Condition,";
                $excelData .= "Price,";
                $excelData .= "Quantity\n";

                foreach ($response as $item) {
                    $excelData .= "{$item->id},";
                    $excelData .= "{$item->nama_aset},";
                    $excelData .= "{$item->jenis_aset},";
                    $excelData .= "{$item->nomor_seri},";
                    $excelData .= "{$item->kondisi},";
                    $excelData .= "{$item->harga},";
                    $excelData .= "{$item->jumlah}\n";
                }

                $this->_header("download_of_asset");
                header('Content-Length: ' . strlen($excelData));

                echo ($excelData);
            }
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function count_of_aset_transaction()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $response = $this->api_model->get_count_asset_transaction();

            return $this->api_model
            ->response(200, "Berhasil memuat data jumlah transaksi aset", $response);
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function download_of_aset_transaction()
    {
        $data_profile = $this->api_model->check_auth_get();

        if (isset($data_profile)) {
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");
            $status_keluar = $this->input->get("status_keluar");
            $is_closed = $this->input->get("is_closed");

            $response = $this->api_model->download_asset_used(
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : "",
                isset($status_keluar) ? $status_keluar : "",
                isset($is_closed) ? $is_closed : "",
            );

            if (empty($response)) {
                $this->_header("download_of_asset_transaction");
                echo("");
            } else {
                $excelData = "TRX ID,";
                $excelData .= "Asset ID,";
                $excelData .= "Asset Name,";
                $excelData .= "User Noted,";
                $excelData .= "Transaction Type,";
                $excelData .= "Quantity,";
                $excelData .= "Out Status,";
                $excelData .= "Notes,";
                $excelData .= "Transaction Date,";
                $excelData .= "Is Closed\n";

                foreach ($response as $item) {
                    $excelData .= "{$item->id},";
                    $excelData .= "{$item->id_aset},";
                    $excelData .= "{$item->nama_aset},";
                    $excelData .= "{$item->full_name},";
                    $excelData .= "{$item->jenis_transaksi},";
                    $excelData .= "{$item->jumlah},";
                    $excelData .= "{$item->status_keluar},";
                    $excelData .= "{$item->keterangan},";
                    $excelData .= "{$item->tgl_transaksi},";
                    $excelData .= "{$item->is_closed}\n";
                }

                $this->_header("download_of_asset_transaction");
                header('Content-Length: ' . strlen($excelData));

                echo ($excelData);
            }
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function count_of_schedule()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $response = $this->api_model->get_count_schedule();

            return $this->api_model
            ->response(200, "Berhasil memuat data jumlah schedule", $response);
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function download_of_schedule()
    {
        $data_profile = $this->api_model->check_auth_get();

        if (isset($data_profile)) {
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->download_schedule(
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (empty($response)) {
                $this->_header("download_of_schedule");
                echo("");
            } else {
                $excelData = "TRX ID,";
                $excelData .= "Asset ID,";
                $excelData .= "Asset Name,";
                $excelData .= "User Noted,";
                $excelData .= "Datetime,";
                $excelData .= "Notes,";
                $excelData .= "Status\n";

                foreach ($response as $item) {
                    $excelData .= "{$item->id},";
                    $excelData .= "{$item->id_aset},";
                    $excelData .= "{$item->nama_aset},";
                    $excelData .= "{$item->full_name},";
                    $excelData .= "{$item->tanggal_waktu},";
                    $excelData .= "{$item->catatan},";
                    $excelData .= "{$item->status}\n";
                }

                $this->_header("download_of_schedule");
                header('Content-Length: ' . strlen($excelData));
                
                echo ($excelData);
            }
        }
        else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }
}
