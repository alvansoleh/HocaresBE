<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruangan extends CI_Controller {

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

            $response = $this->api_model->pagination_ruangan(
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
            ->response(200, "Berhasil mendapatkan daftar ruangan, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function detail()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $ruangan_id = $this->input->get("ruangan_id");
            if (isset($ruangan_id) && !empty($ruangan_id)) {
                $response = $this->api_model->get_ruangan(
                    $ruangan_id
                );
    
                if (isset($response)) {
                    return $this->api_model
                    ->response(200, "Berhasil mendapatkan detail ruangan", $response);
                } else {
                    return $this->api_model
                    ->response(400, "Data ruangan dengan id $ruangan_id tidak ditemukan", null);
                }

            } else {
                // Request tidak valid
                return $this->api_model
                ->response(400, "Request tidak valid", null);
            }
        } else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function lantai_list()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $lantai_id = $this->input->get("lantai_id");
            $page = $this->input->get("page");
            $limit = $this->input->get("limit");
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->get_ruangan_lantai(
                $lantai_id,
                isset($page) ? $page : 0,
                isset($limit) ? $limit : 10,
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (!isset($response)) {
                return $this->api_model
                ->response(200, "Data ruangan tidak ditemukan pada lantai dengan id $lantai_id", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar ruangan pada lantai dengan id $lantai_id", $response);
        } else {
            // Request tidak valid
            return $this->api_model
            ->response(400, "Request tidak valid", null);
        }
    }

    public function create()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($request->nama_ruangan) && isset($request->lantai_id)) {
                $is_ruangan_exists = $this->api_model->is_ruangan_exists($request->nama_ruangan, $request->lantai_id);

                if ($is_ruangan_exists > 0) {
                    // Return Failed
                    return $this->api_model->response(500, "ruangan sudah ada sebelumnya pada lantai dengan id $request->lantai_id", null);
                } else {
                    // Doing Create
                    $status_insert = $this->api_model->insert_ruangan(array(
                        "nama_ruangan"     => $request->nama_ruangan,
                        "id_lantai"     => $request->lantai_id,
                    ));
        
                    if (isset($status_insert)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil membuat ruangan", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Failed to create ruangan", null);
                    }
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
            $ruangan_id = $this->input->get("ruangan_id");
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($ruangan_id)) {
                $is_user_exists = $this->api_model->get_ruangan($ruangan_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Data ruangan dengan id $ruangan_id tidak ditemukan", null);
                } else {
                    // Doing Create
                    $body = array();
                    if (isset($request->nama_ruangan) && !empty($request->nama_ruangan)) {
                        $body["nama_ruangan"] = $request->nama_ruangan;
                    }
                    $status_update = $this->api_model->update_ruangan($body, $ruangan_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil mengubah ruangan", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal mengubah ruangan", null);
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
            $ruangan_id = $this->input->get("ruangan_id");

            // Sanity Check
            if (isset($ruangan_id)) {
                $is_user_exists = $this->api_model->get_ruangan($ruangan_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "ruangan tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "ruangan sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $body = array("is_delete" => 1);
                    $status_update = $this->api_model->update_ruangan($body, $ruangan_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus ruangan", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus ruangan", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }
}
