<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {

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

    public function list()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $page = $this->input->get("page");
            $limit = $this->input->get("limit");
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->pagination_schedule(
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
            ->response(200, "Berhasil mendapatkan daftar schedule, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function detail()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $id = $this->input->get("id");
            if (isset($id) && !empty($id)) {
                $response = $this->api_model->get_schedule(
                    $id
                );
    
                if (isset($response)) {
                    return $this->api_model
                    ->response(200, "Berhasil mendapatkan detail schedule", $response);
                } else {
                    return $this->api_model
                    ->response(400, "Data schedule dengan id $id tidak ditemukan", null);
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
            if (isset($request->id_aset) && isset($request->id_pencatat) && isset($request->tanggal_waktu)) {
                // Doing Create
                $status_insert = $this->api_model->insert_schedule(array(
                    "id_aset"     => $request->id_aset,
                    "id_pencatat"     => $request->id_pencatat,
                    "tanggal_waktu"     => $request->tanggal_waktu,
                    "catatan"     => isset($request->catatan) ? $request->catatan : "-",
                    "status"     => isset($request->status) ? $request->status : "terjadwal"
                ));
    
                if (isset($status_insert)) {
                    // Request success
                    return $this->api_model->response(200, "Berhasil membuat schedule", $request);
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Failed to create schedule", null);
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
            $id = $this->input->get("id");
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($id)) {
                $is_user_exists = $this->api_model->get_schedule($id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Data schedule dengan id $id tidak ditemukan", null);
                } else {
                    // Doing Create
                    $body = array();
                    if (isset($request->id_aset) && !empty($request->id_aset)) {
                        $body["id_aset"] = $request->id_aset;
                    }
                    if (isset($request->id_pencatat) && !empty($request->id_pencatat)) {
                        $body["id_pencatat"] = $request->id_pencatat;
                    }
                    if (isset($request->tanggal_waktu) && !empty($request->tanggal_waktu)) {
                        $body["tanggal_waktu"] = $request->tanggal_waktu;
                    }
                    if (isset($request->catatan) && !empty($request->catatan)) {
                        $body["catatan"] = $request->catatan;
                    }
                    if (isset($request->status) && !empty($request->status)) {
                        $body["status"] = $request->status;
                    }
                    $status_update = $this->api_model->update_schedule($body, $id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil mengubah schedule", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal mengubah schedule", null);
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
            $id = $this->input->get("id");

            // Sanity Check
            if (isset($id)) {
                $is_user_exists = $this->api_model->get_schedule($id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Schedule tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "Schedule sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $body = array("is_delete" => 1);
                    $status_update = $this->api_model->update_schedule($body, $id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus schedule", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus schedule", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }
}
