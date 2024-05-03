<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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

    public function login()
    {
        $request = $this->api_model->get_request();

        // Sanity Check

        if (!isset($request->username) || empty($request->username) || !isset($request->password) || empty($request->password)) {
            return $this->api_model->response(400, "Silahkan masukan Username dan Password", null);
        }

        if (isset($request->username) && isset($request->password)) {
            $data = $this->api_model->check_user(
                $request->username,
                $request->password
            );
            if (isset($data)) {
                // Request success
                return $this->api_model->response(200, "Berhasil melakukan login", array(
                    "token" => base64_encode(json_encode($data))
                ));
            } else {
                // User not found
                return $this->api_model->response(404, "Username dan password tidak ditemukan", null);
            }
        } else {
            // Request tidak valid
            return $this->api_model->response(400, "Request tidak valid", null);
        }
    }

    public function registration()
    {
        $request = $this->api_model->get_request();

        // Sanity Check
        if (isset($request->username) && isset($request->password)
        && isset($request->full_name) && isset($request->email) && isset($request->no_hp)) {
            $is_user_exists = $this->api_model->is_user_exists($request->username); // By Username

            if ($is_user_exists > 0) {
                // Return Failed
                return $this->api_model->response(500, "User sudah ada sebelumnya", null);
            } else {

                $is_user_exists = $this->api_model->is_user_exists_by("email", $request->email); // By Email
                if ($is_user_exists > 0) {
                    // Return Failed
                    return $this->api_model->response(500, "Email sudah terdaftar", null);
                }

                // Doing Create
                $status_insert = $this->api_model->insert_user(array(
                    "full_name"     => $request->full_name,
                    "no_hp"         => isset($request->no_hp) ? $request->no_hp : null,
                    "email"         => isset($request->email) ? $request->email : null,
                    "username"      => $request->username,
                    "password"      => $request->password
                ));
    
                if (isset($status_insert)) {
                    // Request Success
                    $data = $this->api_model->check_user(
                        $request->username,
                        $request->password
                    );
                    if (isset($data)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil melakukan registrasi", array(
                            "token" => base64_encode(json_encode($data))
                        ));
                    } else {
                        // User not found
                        return $this->api_model->response(404, "Username dan password tidak ditemukan", null);
                    }
                } else {
                    // Failed to create user
                    return $this->api_model->response(500, "Failed to create user", null);
                }
            }
        } else {
            // Request tidak valid
            return $this->api_model->response(400, "Mohon untuk mengisi semua data ", null);
        }
    }

}
