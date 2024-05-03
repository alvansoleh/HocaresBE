<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

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

    public function profile()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile))
            return $this->api_model
            ->response(200, "Berhasil melakukan login", $data_profile);
    }

    public function update_profile()
    {
        $data_profile = $this->api_model->check_auth();
        if (isset($data_profile)) {
            $request = $this->api_model->get_request();
            if (isset($request->full_name) && !empty($request->full_name) &&
                isset($request->email) && !empty($request->email) &&
                isset($request->no_hp) && !empty($request->no_hp)) {
                    $body = array(
                        "full_name"     => $request->full_name,
                        "email"         => $request->email,
                        "no_hp"  => $request->no_hp,
                        "is_delete"    => isset($request->is_delete) ? $request->is_delete : $data_profile->is_delete
                    );

                    $status = $this->api_model->update_user($body, $data_profile->id);
                    if ($status) {
                        $data_profile = $this->api_model->check_auth();
                        return $this->api_model
                        ->response(200, "Berhasil mengubah profil", $data_profile);
                    } else {
                        // Failed to update user
                        return $this->api_model
                        ->response(500, "Gagal mengubah profil", null);
                    }
                } else {
                    // Request tidak valid
                    return $this->api_model
                    ->response(400, "Request tidak valid", null);
                }
        }
    }

    public function change_password()
    {
        $data_profile = $this->api_model->check_auth_with_password();
        if (isset($data_profile)) {
            $request = $this->api_model->get_request();
            if (isset($request->password) && !empty($request->password) &&
                isset($request->new_password) && !empty($request->new_password) &&
                isset($request->retype_password) && !empty($request->retype_password)) {

                    if ($data_profile->password != $request->password) {
                        // Failed to update user
                        return $this->api_model
                        ->response(500, "Password lama salah", null);
                    }
                    if ($data_profile->password == $request->new_password) {
                        // Failed to update user
                        return $this->api_model
                        ->response(500, "Password baru tidak boleh sama dengan yang lama", null);
                    }
                    if ($request->retype_password != $request->new_password) {
                        // Failed to update user
                        return $this->api_model
                        ->response(500, "Password baru dan retype password tidak sama", null);
                    }

                    $body = array(
                        "password"     => $request->new_password,
                    );

                    $status = $this->api_model->update_user($body, $data_profile->id);
                    if ($status) {
                        $data_profile = $this->api_model->check_auth();
                        return $this->api_model
                        ->response(200, "Berhasil mengubah password", $data_profile);
                    } else {
                        // Failed to update user
                        return $this->api_model
                        ->response(500, "Gagal mengubah password", null);
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

            $response = $this->api_model->pagination_user(
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
            ->response(200, "Berhasil mendapatkan daftar pengguna, menampilkan filter $filter_by untuk pencarian $query di urutkan secara $sort_by", $response);
        }
    }

    public function detail()
    {
        $data_profile = $this->api_model->check_auth();

        if (isset($data_profile)) {
            $user_id = $this->input->get("user_id");
            if (isset($user_id) && !empty($user_id)) {
                $response = $this->api_model->get_profile(
                    $user_id
                );
    
                if (isset($response)) {
                    return $this->api_model
                    ->response(200, "Berhasil mendapatkan detail pengguna", $response);
                } else {
                    return $this->api_model
                    ->response(400, "Data pengguna dengan id $user_id tidak ditemukan", null);
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
            if (isset($request->username) && isset($request->password)
            && isset($request->full_name) && isset($request->email) && isset($request->no_hp)) {
                $is_user_exists = $this->api_model->is_user_exists($request->username);

                if ($is_user_exists > 0) {
                    // Return Failed
                    return $this->api_model->response(500, "User sudah ada sebelumnya", null);
                } else {
                    // Doing Create
                    $status_insert = $this->api_model->insert_user(array(
                        "full_name"     => $request->full_name,
                        "no_hp"         => isset($request->no_hp) ? $request->no_hp : null,
                        "email"         => isset($request->email) ? $request->email : null,
                        "username"      => $request->username,
                        "password"      => $request->password
                    ));
        
                    if (isset($status_insert)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil membuat user", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Failed to create user", null);
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
            $user_id = $this->input->get("user_id");
            $request = $this->api_model->get_request();

            // Sanity Check
            if (isset($user_id)) {
                $is_user_exists = $this->api_model->get_profile($user_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "Data pengguna dengan id $user_id tidak ditemukan", null);
                } else {
                    // Doing Create
                    $body = array();
                    if (isset($request->full_name) && !empty($request->full_name)) {
                        $body["full_name"] = $request->full_name;
                    }
                    if (isset($request->no_hp) && !empty($request->no_hp)) {
                        $body["no_hp"] = $request->no_hp;
                    }
                    if (isset($request->email) && !empty($request->email)) {
                        $body["email"] = $request->email;
                    }
                    $status_update = $this->api_model->update_user($body, $user_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil mengubah user", $request);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal mengubah user", null);
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
            $user_id = $this->input->get("user_id");

            // Sanity Check
            if (isset($user_id)) {
                $is_user_exists = $this->api_model->get_profile($user_id);

                if (!isset($is_user_exists)) {
                    // Return Failed
                    return $this->api_model->response(500, "User tidak ditemukan", null);
                } else {
                    if ($is_user_exists->is_delete == 1 || $is_user_exists->is_delete == "1") {
                        // Return Failed
                        return $this->api_model->response(500, "User sudah dihapus sebelumnya", null);
                    }
                    // Doing Create
                    $body = array("is_delete" => 1);
                    $status_update = $this->api_model->update_user($body, $user_id);
        
                    if (isset($status_update)) {
                        // Request success
                        return $this->api_model->response(200, "Berhasil menghapus user", null);
                    } else {
                        // Failed to create user
                        return $this->api_model->response(500, "Gagal menghapus user", null);
                    }
                }
            } else {
                // Request tidak valid
                return $this->api_model->response(400, "Request tidak valid", null);
            }
        }
    }
}
