<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lantai extends CI_Controller {

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

            $response = $this->api_model->pagination_lantai(
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
            ->response(200, "Berhasil mendapatkan daftar lantai, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function detail()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $lantai_id = $this->input->get("lantai_id");
            if (isset($lantai_id) && !empty($lantai_id)) {
                $response = $this->api_model->get_lantai(
                    $lantai_id
                );
    
                if (isset($response)) {
                    return $this->api_model
                    ->response(200, "Berhasil mendapatkan detail lantai", $response);
                } else {
                    return $this->api_model
                    ->response(400, "Data lantai dengan id $lantai_id tidak ditemukan", null);
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

    public function gedung_list()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $gedung_id = $this->input->get("gedung_id");
            $page = $this->input->get("page");
            $limit = $this->input->get("limit");
            $filter_by = $this->input->get("filter_by");
            $sort_by = $this->input->get("sort_by");
            $query = $this->input->get("query");

            $response = $this->api_model->get_lantai_gedung(
                $gedung_id,
                isset($page) ? $page : 0,
                isset($limit) ? $limit : 10,
                isset($filter_by) ? $filter_by : "id",
                isset($sort_by) ? $sort_by : "DESC",
                isset($query) ? $query : ""
            );

            if (!isset($response)) {
                return $this->api_model
                ->response(200, "Data lantai tidak ditemukan pada gedung dengan id $gedung_id", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar lantai pada gedung dengan id $gedung_id", $response);
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
            if (isset($request->nama_lantai) && isset($request->gedung_id)) {
                $is_lantai_exists = $this->api_model->is_lantai_exists($request->nama_lantai, $request->gedung_id);

                if ($is_lantai_exists > 0) {
                    // Return Failed
                    return $this->api_model->response(500, "lantai sudah ada sebelumnya pada gedung dengan id $request->gedung_id", null);
                } else {
                    // Doing Create
                    $status_insert = $this->api_model->insert_lantai(array(
                        "nama_lantai"     => $request->nama_lantai,
                        "id_gedung"     => $request->gedung_id,
                    ));
        
                    if (isset($status_insert)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil membuat lantai", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Failed to create lantai", null);
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
            $lantai_id = $this->input->get("lantai_id");
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($lantai_id)) {
                $is_user_exists = $this->api_model->get_lantai($lantai_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Data lantai dengan id $lantai_id tidak ditemukan", null);
                } else {
                    // Doing Create
                    $body = array();
                    if (isset($request->nama_lantai) && !empty($request->nama_lantai)) {
                        $body["nama_lantai"] = $request->nama_lantai;
                    }
                    $status_update = $this->api_model->update_lantai($body, $lantai_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil mengubah lantai", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal mengubah lantai", null);
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
            $lantai_id = $this->input->get("lantai_id");

            // Sanity Check
            if (isset($lantai_id)) {
                $is_user_exists = $this->api_model->get_lantai($lantai_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "lantai tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "lantai sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $body = array("is_delete" => 1);
                    $status_update = $this->api_model->update_lantai($body, $lantai_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus lantai", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus lantai", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }
}
