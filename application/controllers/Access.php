<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller {

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
            $response = $this->api_model->get_user_access_by_id($data_profile->id);

            if (count($response) == 0) {
                return $this->api_model
                ->response(200, "Data user access menus tidak ditemukan", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar user access menus", $response);
        }
    }

    public function list_by_id()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $id_user = $this->input->get("id_user");
            $response = $this->api_model->get_user_access_by_id($id_user);

            if (count($response) == 0) {
                return $this->api_model
                ->response(200, "Data user access menus tidak ditemukan", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar user access menus", $response);
        }
    }

    public function create()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($request->id_user) && isset($request->id_menu) 
                && isset($request->lihat)
                && isset($request->tambah)
                && isset($request->ubah)
                && isset($request->hapus)) {
                // Doing Create
                $status_insert = $this->api_model->insert_user_access(array(
                    "id_user"     => $request->id_user,
                    "id_menu"     => $request->id_menu,
                    "lihat"     => $request->lihat,
                    "tambah"     => $request->tambah,
                    "ubah"     => $request->ubah,
                    "hapus"     => $request->hapus
                ));
    
                if (isset($status_insert)) {
                    // Request success
                    return $this->api_model->response(200, "Berhasil membuat user access menu", $request);
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Failed to create user access menu", null);
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
                $is_user_exists = $this->api_model->get_user_access($id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Data user access menu dengan id $id tidak ditemukan", null);
                } else {
                    // Doing Create
                    $body = array(
                        "lihat"     => $request->lihat,
                        "tambah"     => $request->tambah,
                        "ubah"     => $request->ubah,
                        "hapus"     => $request->hapus,
                    );
                    if (isset($request->id_user) && !empty($request->id_user)) {
                        $body["id_user"] = $request->id_user;
                    }
                    if (isset($request->id_menu) && !empty($request->id_menu)) {
                        $body["id_menu"] = $request->id_menu;
                    }
                    $status_update = $this->api_model->update_user_access($body, $id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil mengubah user access menu", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal mengubah user access menu", null);
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
                $is_user_exists = $this->api_model->get_user_access($id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "User access menu tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "User access menu sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $status_update = $this->api_model->remove_user_access($id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus user access menu", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus user access menu", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }
}
