<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

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
            $response = $this->api_model->get_menu_list();

            if (count($response) == 0) {
                return $this->api_model
                ->response(200, "Data menus tidak ditemukan", null);
            }

            return $this->api_model
            ->response(200, "Berhasil mendapatkan daftar menus", $response);
        }
    }

    public function create()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($request->nama_menu) && isset($request->path)) {
                // Doing Create
                $status_insert = $this->api_model->insert_menu(array(
                    "nama_menu"     => $request->nama_menu,
                    "path"          => $request->path,
                    "icon"          => isset($request->icon) ? $request->icon : null,
                ));
    
                if (isset($status_insert)) {
                    // Request success
                    return $this->api_model->response(200, "Berhasil membuat menu", $request);
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Failed to create menu", null);
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
            $menu_id = $this->input->get("menu_id");
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($menu_id)) {
                $is_user_exists = $this->api_model->get_menu_by_id($menu_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Data menu dengan id $menu_id tidak ditemukan", null);
                } else {
                    // Doing Create
                    $body = array();
                    if (isset($request->nama_menu) && !empty($request->nama_menu)) {
                        $body["nama_menu"] = $request->nama_menu;
                    }
                    if (isset($request->path) && !empty($request->path)) {
                        $body["path"] = $request->path;
                    }
                    if (isset($request->icon) && !empty($request->icon)) {
                        $body["icon"] = $request->icon;
                    }
                    $status_update = $this->api_model->update_menu($body, $menu_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil mengubah menu", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal mengubah menu", null);
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
            $menu_id = $this->input->get("menu_id");

            // Sanity Check
            if (isset($menu_id)) {
                $is_user_exists = $this->api_model->get_menu_by_id($menu_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Menu tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "Menu sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $status_update = $this->api_model->remove_menu($menu_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus menu", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus menu", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }
}
