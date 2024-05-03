<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {
    // Starts
    public function get_request() {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        return $request;
    }

    public function get_auth() {
        $headers = getallheaders();
        if (!array_key_exists('Authorization', $headers)) {
            return null;
        } else return $headers["Authorization"];
    }

    public function response($code, $message, $data) {

        $body_response = array();
        $body_response["status"] = ($code === 200 || $code === 201);
        $body_response["message"] = $message;
        if (isset($data) && !empty($data)) {
            $body_response["data"] = $data;
        }

        return $this->output
            ->set_status_header($code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($body_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function check_auth() {
        $token = $this->get_auth();

        if (isset($token)) {
            $token = base64_decode($token);
            $auth = json_decode($token);

            if (isset($auth->full_name)) {
                $data_profile = $this->get_profile($auth->id);
                if (isset($data_profile)) {
                    unset($data_profile->password);
                    return $data_profile;
                }
                else {
                    // Request tidak valid
                    $this->response(404, "Invalid token", null);
                    return null;
                }
            } else {
                // Request tidak valid
                $this->response(400, "Request tidak valid", null);
                return null;
            }
        } else {
            // Request tidak valid
            $this->response(403, "User not authenticated", null);
            return null;
        }
    }

    public function check_auth_get() {
        $token = $this->input->get("token");

        if (isset($token)) {
            $token = base64_decode($token);
            $auth = json_decode($token);

            if (isset($auth->full_name)) {
                $data_profile = $this->get_profile($auth->id);
                if (isset($data_profile)) {
                    unset($data_profile->password);
                    return $data_profile;
                }
                else {
                    // Request tidak valid
                    $this->response(404, "Invalid token", null);
                    return null;
                }
            } else {
                // Request tidak valid
                $this->response(400, "Request tidak valid", null);
                return null;
            }
        } else {
            // Request tidak valid
            $this->response(403, "User not authenticated", null);
            return null;
        }
    }

    public function check_auth_with_password() {
        $token = $this->get_auth();

        if (isset($token)) {
            $token = base64_decode($token);
            $auth = json_decode($token);

            if (isset($auth->full_name)) {
                $data_profile = $this->get_profile($auth->id);
                if (isset($data_profile)) {
                    return $data_profile;
                }
                else {
                    // Request tidak valid
                    $this->response(404, "Invalid token", null);
                    return null;
                }
            } else {
                // Request tidak valid
                $this->response(400, "Request tidak valid", null);
                return null;
            }
        } else {
            // Request tidak valid
            $this->response(403, "User not authenticated", null);
            return null;
        }
    }
    // Ends

    // Starts
    public function check_user($username, $password) {
        $this->db->where("username", $username);
        $this->db->where("password", $password);
        $this->db->where("is_delete", 0);
        $query = $this->db->get("m_users");

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function is_user_exists($username) {
        $this->db->where("username", $username);
        $this->db->where("is_delete", 0);
        $query = $this->db->get("m_users");

        $data = $query->result();
        return count($data);
    }

    public function is_user_exists_by($by, $value) {
        $this->db->where($by, $value);
        $this->db->where("is_delete", 0);
        $query = $this->db->get("m_users");

        $data = $query->result();
        return count($data);
    }

    public function get_profile($id) {
        $this->db->where("id", $id);
        $this->db->where("is_delete", 0);
        $query = $this->db->get("m_users");

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function insert_user($body) {
        $status_insert = $this->db->insert("m_users", $body);
        return $status_insert;
    }

    public function update_user($body, $id) {
        $this->db->where("id", $id);
        $status_update = $this->db->update("m_users", $body);
        return $status_update;
    }

    public function pagination_user($page, $limit, $filter_by, $sort_by, $query) {
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get("m_users")->result();
    }
    // Ends

    // Starts
    public function pagination_asset($page, $limit, $filter_by, $sort_by, $query) {
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get("m_aset")->result();
    }

    public function download_asset($filter_by, $sort_by, $query) {
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        return $this->db->get("m_aset")->result();
    }

    public function get_asset($id) {
        $this->db->where("id", $id);
        $query = $this->db->get("m_aset");

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function insert_asset($body) {
        $status_insert = $this->db->insert("m_aset", $body);
        return $status_insert;
    }

    public function update_asset($body, $id) {
        $this->db->where("id", $id);
        $status_update = $this->db->update("m_aset", $body);
        return $status_update;
    }
    
    public function pagination_asset_used($aset_id, $page, $limit, $filter_by, $sort_by, $query) {
        $this->db->select("u.*, ma.nama_aset, ma.jenis_aset, ma.nomor_seri, mu.full_name");
        $this->db->from("u_riwayat_aset u");
        $this->db->join("m_aset ma", "u.id_aset = ma.id");
        $this->db->join("m_users mu", "u.id_pencatat = mu.id");
        
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("u.id_aset", $aset_id);
        $this->db->where("u.is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get()->result();
    }

    public function pagination_asset_used_all($page, $limit, $filter_by, $sort_by, $query, $status_keluar, $is_closed) {
        $this->db->select("u.*, ma.nama_aset, ma.jenis_aset, ma.nomor_seri, mu.full_name");
        $this->db->from("u_riwayat_aset u");
        $this->db->join("m_aset ma", "u.id_aset = ma.id");
        $this->db->join("m_users mu", "u.id_pencatat = mu.id");
        
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        if (isset($status_keluar) && !empty($status_keluar)) {
            $this->db->like("status_keluar", $status_keluar, "both");
        }
        if (isset($is_closed) && !empty($is_closed)) {
            $this->db->like("is_closed", $is_closed, "both");
        }

        $this->db->where("u.is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get()->result();
    }

    public function download_asset_used($filter_by, $sort_by, $query, $status_keluar, $is_closed) {
        $this->db->select("u.*, ma.nama_aset, ma.jenis_aset, ma.nomor_seri, mu.full_name");
        $this->db->from("u_riwayat_aset u");
        $this->db->join("m_aset ma", "u.id_aset = ma.id");
        $this->db->join("m_users mu", "u.id_pencatat = mu.id");
        
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        if (isset($status_keluar) && !empty($status_keluar)) {
            $this->db->like("status_keluar", $status_keluar, "both");
        }
        if (isset($is_closed) && !empty($is_closed)) {
            $this->db->like("is_closed", $is_closed, "both");
        }
        $this->db->where("u.is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        return $this->db->get()->result();
    }

    public function insert_asset_used($body) {
        $status_insert = $this->db->insert("u_riwayat_aset", $body);
        return $status_insert;
    }

    public function update_asset_used($body, $id) {
        $this->db->where("id", $id);
        $status_update = $this->db->update("u_riwayat_aset", $body);
        return $status_update;
    }

    public function get_asset_used($id) {
        $this->db->where("id", $id);
        $query = $this->db->get("u_riwayat_aset");

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function pagination_asset_mapping($aset_id, $page, $limit, $filter_by, $sort_by, $query) {
        $this->db->select("u.*, ma.nama_aset, ma.jenis_aset, ma.nomor_seri, mg.nama_gedung, ml.nama_lantai, mr.nama_ruangan");
        $this->db->from("u_aset_mapping u");
        $this->db->join("m_aset ma", "u.id_aset = ma.id");
        $this->db->join("m_gedung mg", "u.id_gedung = mg.id");
        $this->db->join("m_lantai ml", "u.id_lantai = ml.id", "left");
        $this->db->join("m_ruangan mr", "u.id_ruangan = mr.id", "left");
        
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("u.id_aset", $aset_id);
        $this->db->where("u.is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get()->result();
    }

    public function download_asset_mapping($filter_by, $sort_by, $query) {
        $this->db->select("u.*, ma.nama_aset, ma.jenis_aset, ma.nomor_seri, mg.nama_gedung, ml.nama_lantai, mr.nama_ruangan");
        $this->db->from("u_aset_mapping u");
        $this->db->join("m_aset ma", "u.id_aset = ma.id");
        $this->db->join("m_gedung mg", "u.id_gedung = mg.id");
        $this->db->join("m_lantai ml", "u.id_lantai = ml.id", "left");
        $this->db->join("m_ruangan mr", "u.id_ruangan = mr.id", "left");
        
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("u.is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        return $this->db->get()->result();
    }

    public function insert_asset_mapping($body) {
        $status_insert = $this->db->insert("u_aset_mapping", $body);
        return $status_insert;
    }

    public function update_asset_mapping($body, $id) {
        $this->db->where("id", $id);
        $status_update = $this->db->update("u_aset_mapping", $body);
        return $status_update;
    }

    public function get_asset_mapping($id) {
        $this->db->where("id", $id);
        $query = $this->db->get("u_aset_mapping");

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function is_asset_mapping_exists($id_aset, $id_gedung, $id_lantai, $id_ruangan) {
        $this->db->where("id_aset", $id_aset);
        $this->db->where("id_gedung", $id_gedung);
        $this->db->where("id_lantai", $id_lantai);
        $this->db->where("id_ruangan", $id_ruangan);
        $query = $this->db->get("u_aset_mapping");

        $this->db->where("is_delete", 0);
        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }
    // End

    // Start
    public function get_gedung($id) {
        $this->db->where("id", $id);
        $this->db->where("is_delete", 0);
        $query = $this->db->get("m_gedung");

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function is_gedung_exists($nama_gedung) {
        $this->db->where("nama_gedung", $nama_gedung);
        $this->db->where("is_delete", 0);
        $query = $this->db->get("m_gedung");

        $data = $query->result();
        return count($data);
    }

    public function insert_gedung($body) {
        $status_insert = $this->db->insert("m_gedung", $body);
        return $status_insert;
    }

    public function update_gedung($body, $id) {
        $this->db->where("id", $id);
        $status_update = $this->db->update("m_gedung", $body);
        return $status_update;
    }

    public function pagination_gedung($page, $limit, $filter_by, $sort_by, $query) {
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get("m_gedung")->result();
    }

    public function download_gedung($filter_by, $sort_by, $query) {
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        return $this->db->get("m_gedung")->result();
    }
    // End

    // Start
    public function get_lantai($id) {
        $this->db->select("ml.*, mg.nama_gedung");
        $this->db->from("m_lantai ml");
        $this->db->join("m_gedung mg", "ml.id_gedung = mg.id");

        $this->db->where("ml.id", $id);
        $this->db->where("ml.is_delete", 0);
        $query = $this->db->get();

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function get_lantai_gedung($gedung_id, $page, $limit, $filter_by, $sort_by, $query) {
        $this->db->select("ml.*, mg.nama_gedung");
        $this->db->from("m_lantai ml");
        $this->db->join("m_gedung mg", "ml.id_gedung = mg.id");

        $this->db->where("ml.id_gedung", $gedung_id);
        $this->db->where("ml.is_delete", 0);
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        $query = $this->db->get();

        $data = $query->result();
        if (count($data) > 0) {
            return $data;
        } else return null;
    }

    public function download_lantai_gedung($filter_by, $sort_by, $query) {
        $this->db->select("ml.*, mg.nama_gedung");
        $this->db->from("m_lantai ml");
        $this->db->join("m_gedung mg", "ml.id_gedung = mg.id");

        $this->db->where("ml.is_delete", 0);
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->order_by($filter_by, $sort_by);
        $query = $this->db->get();

        $data = $query->result();
        if (count($data) > 0) {
            return $data;
        } else return null;
    }

    public function is_lantai_exists($nama_lantai, $gedung_id) {
        $this->db->where("nama_lantai", $nama_lantai);
        $this->db->where("id_gedung", $gedung_id);
        $this->db->where("is_delete", 0);
        $query = $this->db->get("m_lantai");

        $data = $query->result();
        return count($data);
    }

    public function insert_lantai($body) {
        $status_insert = $this->db->insert("m_lantai", $body);
        return $status_insert;
    }

    public function update_lantai($body, $id) {
        $this->db->where("id", $id);
        $status_update = $this->db->update("m_lantai", $body);
        return $status_update;
    }

    public function pagination_lantai($page, $limit, $filter_by, $sort_by, $query) {
        $this->db->select("ml.*, mg.nama_gedung");
        $this->db->from("m_lantai ml");
        $this->db->join("m_gedung mg", "ml.id_gedung = mg.id");

        $this->db->where("ml.is_delete", 0);
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get()->result();
    }
    // End

    // Start
    public function get_ruangan($id) {
        $this->db->select("mr.*, ml.nama_lantai, mg.nama_gedung");
        $this->db->from("m_ruangan mr");
        $this->db->join("m_lantai ml", "mr.id_lantai = ml.id");
        $this->db->join("m_gedung mg", "ml.id_gedung = mg.id");

        $this->db->where("mr.id", $id);
        $this->db->where("mr.is_delete", 0);
        $query = $this->db->get();

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function get_ruangan_lantai($lantai_id, $page, $limit, $filter_by, $sort_by, $query) {
        $this->db->select("mr.*, ml.nama_lantai, mg.nama_gedung");
        $this->db->from("m_ruangan mr");
        $this->db->join("m_lantai ml", "mr.id_lantai = ml.id");
        $this->db->join("m_gedung mg", "ml.id_gedung = mg.id");

        $this->db->where("mr.id_lantai", $lantai_id);
        $this->db->where("mr.is_delete", 0);
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        $query = $this->db->get();

        $data = $query->result();
        if (count($data) > 0) {
            return $data;
        } else return null;
    }

    public function download_ruangan_lantai($filter_by, $sort_by, $query) {
        $this->db->select("mr.*, ml.nama_lantai, ml.id_gedung, mg.nama_gedung");
        $this->db->from("m_ruangan mr");
        $this->db->join("m_lantai ml", "mr.id_lantai = ml.id");
        $this->db->join("m_gedung mg", "ml.id_gedung = mg.id");

        $this->db->where("mr.is_delete", 0);
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }

        $this->db->order_by($filter_by, $sort_by);
        $query = $this->db->get();

        $data = $query->result();
        if (count($data) > 0) {
            return $data;
        } else return null;
    }

    public function is_ruangan_exists($nama_ruangan, $lantai_id) {
        $this->db->where("nama_ruangan", $nama_ruangan);
        $this->db->where("id_lantai", $lantai_id);
        $this->db->where("is_delete", 0);
        $query = $this->db->get("m_ruangan");

        $data = $query->result();
        return count($data);
    }

    public function insert_ruangan($body) {
        $status_insert = $this->db->insert("m_ruangan", $body);
        return $status_insert;
    }

    public function update_ruangan($body, $id) {
        $this->db->where("id", $id);
        $status_update = $this->db->update("m_ruangan", $body);
        return $status_update;
    }

    public function pagination_ruangan($page, $limit, $filter_by, $sort_by, $query) {
        $this->db->select("mr.*, ml.nama_lantai, mg.nama_gedung");
        $this->db->from("m_ruangan mr");
        $this->db->join("m_lantai ml", "mr.id_lantai = ml.id");
        $this->db->join("m_gedung mg", "ml.id_gedung = mg.id");

        $this->db->where("mr.is_delete", 0);
        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get()->result();
    }
    // End
    
    // Start
    public function get_schedule($id) {
        $this->db->select("umsa.*, ma.nama_aset, mu.full_name");
        $this->db->from("u_maintenance_schedule_aset umsa");
        $this->db->join("m_aset ma", "umsa.id_aset = ma.id");
        $this->db->join("m_users mu", "umsa.id_pencatat = mu.id");
        $this->db->where("umsa.id", $id);
        $this->db->where("umsa.is_delete", 0);
        $query = $this->db->get();

        $data = $query->result();
        if (count($data) > 0) {
            return $data[0];
        } else return null;
    }

    public function insert_schedule($body) {
        $status_insert = $this->db->insert("u_maintenance_schedule_aset", $body);
        return $status_insert;
    }

    public function update_schedule($body, $id) {
        $this->db->where("id", $id);
        $status_update = $this->db->update("u_maintenance_schedule_aset", $body);
        return $status_update;
    }

    public function pagination_schedule($page, $limit, $filter_by, $sort_by, $query) {
        $this->db->select("umsa.*, ma.nama_aset, mu.full_name");
        $this->db->from("u_maintenance_schedule_aset umsa");
        $this->db->join("m_aset ma", "umsa.id_aset = ma.id");
        $this->db->join("m_users mu", "umsa.id_pencatat = mu.id");

        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("umsa.is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        $this->db->limit($limit, $page * $limit);
        return $this->db->get()->result();
    }
    
    public function download_schedule($filter_by, $sort_by, $query) {
        $this->db->select("umsa.*, ma.nama_aset, mu.full_name");
        $this->db->from("u_maintenance_schedule_aset umsa");
        $this->db->join("m_aset ma", "umsa.id_aset = ma.id");
        $this->db->join("m_users mu", "umsa.id_pencatat = mu.id");

        if (isset($query) && !empty($query)) {
            $this->db->like($filter_by, $query, "both");
        }
        $this->db->where("umsa.is_delete", 0);
        $this->db->order_by($filter_by, $sort_by);
        return $this->db->get()->result();
    }
    // End
    public function get_count_place() {
        $this->db->select('(SELECT COUNT(*) FROM m_gedung WHERE is_delete=0) AS gedung_count');
        $this->db->select('(SELECT COUNT(*) FROM m_lantai WHERE is_delete=0) AS lantai_count');
        $this->db->select('(SELECT COUNT(*) FROM m_ruangan WHERE is_delete=0) AS ruangan_count');
        $query = $this->db->get();
        return $query->row();
    }

    public function get_count_asset() {
        $this->db->select('

        COUNT(CASE WHEN m_aset.kondisi = "tersedia" AND m_aset.jumlah != "0" THEN 1 ELSE 0 END) AS count_tersedia,
        COUNT(CASE WHEN m_aset.kondisi = "tersedia" AND m_aset.jumlah = "0" THEN 1 ELSE 0 END) AS count_tersedia_0,
        COUNT(CASE WHEN m_aset.kondisi = "habis" THEN 1 ELSE 0 END) AS count_habis,
        SUM(CASE WHEN m_aset.kondisi = "tersedia" AND m_aset.jumlah != "0" THEN m_aset.jumlah ELSE 0 END) AS sum_tersedia,
        SUM(CASE WHEN m_aset.kondisi = "tersedia" AND m_aset.jumlah = "0" THEN 1 ELSE 0 END) AS sum_tersedia_0,
        SUM(CASE WHEN m_aset.kondisi = "habis" THEN m_aset.jumlah ELSE "0" END) AS sum_habis,
        SUM(CASE WHEN m_aset.kondisi = "tersedia" AND m_aset.jumlah != "0" THEN m_aset.harga * m_aset.jumlah ELSE 0 END) AS value_tersedia,
        SUM(CASE WHEN m_aset.kondisi = "tersedia" AND m_aset.jumlah = "0" THEN m_aset.harga * 1 ELSE 0 END) AS value_tersedia_0,
        SUM(CASE WHEN m_aset.kondisi = "habis" THEN m_aset.harga * m_aset.jumlah ELSE 0 END) AS value_habis

        ');
        $this->db->from('m_aset');
        $this->db->where('m_aset.is_delete', 0);
        return $this->db->get()->row();
    }

    public function get_count_asset_transaction() {
        $this->db->select(
            'COUNT(CASE WHEN jenis_transaksi = "masuk" THEN 1 END) AS count_masuk, ' .
            'COUNT(CASE WHEN jenis_transaksi = "keluar" and status_keluar = "rusak" THEN 1 END) AS count_keluar_rusak, ' .
            'COUNT(CASE WHEN jenis_transaksi = "keluar" and status_keluar = "dijual" THEN 1 END) AS count_keluar_dijual, ' .
            'COUNT(CASE WHEN jenis_transaksi = "keluar" and status_keluar = "pinjam" and is_closed = 0 THEN 1 END) AS count_keluar_pinjam_open, ' .
            'COUNT(CASE WHEN jenis_transaksi = "keluar" and status_keluar = "pinjam" and is_closed = 1 THEN 1 END) AS count_keluar_pinjam_close, ' .
            'SUM(CASE WHEN jenis_transaksi = "masuk" THEN jumlah END) AS sum_masuk, ' .
            'SUM(CASE WHEN jenis_transaksi = "keluar" and status_keluar = "rusak" THEN jumlah END) AS sum_keluar_rusak, ' .
            'SUM(CASE WHEN jenis_transaksi = "keluar" and status_keluar = "dijual" THEN jumlah END) AS sum_keluar_dijual, ' .
            'SUM(CASE WHEN jenis_transaksi = "keluar" and status_keluar = "pinjam" and is_closed = 0 THEN jumlah END) AS sum_keluar_pinjam_open, ' .
            'SUM(CASE WHEN jenis_transaksi = "keluar" and status_keluar = "pinjam" and is_closed = 1 THEN jumlah END) AS sum_keluar_pinjam_close'
        );
        $this->db->from('u_riwayat_aset');
        $this->db->where_in('jenis_transaksi', ['masuk', 'keluar']);
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    public function get_count_schedule() {
        $this->db->select('SUM(CASE WHEN status = "terjadwal" THEN 1 ELSE 0 END) AS count_terjadwal');
        $this->db->select('SUM(CASE WHEN status = "berlangsung" THEN 1 ELSE 0 END) AS count_berlangsung');
        $this->db->select('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) AS count_selesai');
        $this->db->from('u_maintenance_schedule_aset');
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    // Start
    public function get_menu_list() {
        $this->db->where('is_delete', 0);
        $query = $this->db->get('m_menus');
        return $query->result();
    }

    public function get_menu_by_id($id) {
        $this->db->where('is_delete', 0);
        $this->db->where('id', $id);
        $query = $this->db->get('m_menus');
        return $query->row();
    }

    public function insert_menu($data) {
        $this->db->insert('m_menus', $data);
        return $this->db->insert_id();
    }

    public function update_menu($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('m_menus', $data);
        return $this->db->affected_rows();
    }

    public function remove_menu($id) {
        $this->db->where('id', $id);
        $this->db->set('is_delete', 1);
        $this->db->update('m_menus');
        return $this->db->affected_rows();
    }
    // End

    // Start
    public function get_user_access_by_id($user_id) {
        $this->db->select("uuam.*, mm.nama_menu, mm.path, mu.full_name");
        $this->db->from("u_user_access_menu uuam");
        $this->db->join("m_menus mm", "uuam.id_menu = mm.id");
        $this->db->join("m_users mu", "uuam.id_user = mu.id");

        $this->db->where('uuam.is_delete', 0);
        $this->db->where('uuam.id_user', $user_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_user_access($id) {
        $this->db->where('is_delete', 0);
        $this->db->where('id', $id);
        $query = $this->db->get('u_user_access_menu');
        return $query->row();
    }

    public function insert_user_access($data) {
        $this->db->insert('u_user_access_menu', $data);
        return $this->db->insert_id();
    }

    public function update_user_access($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('u_user_access_menu', $data);
        return $this->db->affected_rows();
    }

    public function remove_user_access($id) {
        $this->db->where('id', $id);
        $this->db->set('is_delete', 1);
        $this->db->update('u_user_access_menu');
        return $this->db->affected_rows();
    }
    // End
}