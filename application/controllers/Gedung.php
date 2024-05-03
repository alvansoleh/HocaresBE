<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gedung extends CI_Controller {

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

            $response = $this->api_model->pagination_gedung(
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
            ->response(200, "Berhasil mendapatkan daftar gedung, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function detail()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $gedung_id = $this->input->get("gedung_id");
            if (isset($gedung_id) && !empty($gedung_id)) {
                $response = $this->api_model->get_gedung(
                    $gedung_id
                );
    
                if (isset($response)) {
                    return $this->api_model
                    ->response(200, "Berhasil mendapatkan detail gedung", $response);
                } else {
                    return $this->api_model
                    ->response(400, "Data gedung dengan id $gedung_id tidak ditemukan", null);
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
            if (isset($request->nama_gedung)) {
                $is_gedung_exists = $this->api_model->is_gedung_exists($request->nama_gedung);

                if ($is_gedung_exists > 0) {
                    // Return Failed
                    return $this->api_model->response(500, "Gedung sudah ada sebelumnya", null);
                } else {
                    // Doing Create
                    $status_insert = $this->api_model->insert_gedung(array(
                        "nama_gedung"     => $request->nama_gedung,
                    ));
        
                    if (isset($status_insert)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil membuat gedung", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Failed to create gedung", null);
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
            $gedung_id = $this->input->get("gedung_id");
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($gedung_id)) {
                $is_user_exists = $this->api_model->get_gedung($gedung_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Data gedung dengan id $gedung_id tidak ditemukan", null);
                } else {
                    // Doing Create
                    $body = array();
                    if (isset($request->nama_gedung) && !empty($request->nama_gedung)) {
                        $body["nama_gedung"] = $request->nama_gedung;
                    }
                    $status_update = $this->api_model->update_gedung($body, $gedung_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil mengubah gedung", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal mengubah gedung", null);
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
            $gedung_id = $this->input->get("gedung_id");

            // Sanity Check
            if (isset($gedung_id)) {
                $is_user_exists = $this->api_model->get_gedung($gedung_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Gedung tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "Gedung sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $body = array("is_delete" => 1);
                    $status_update = $this->api_model->update_gedung($body, $gedung_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus gedung", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus gedung", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }
}
