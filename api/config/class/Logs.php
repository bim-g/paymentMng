<?php

namespace Wepesi\App;

use Exception;
use Wepesi\App\Core\DB;

class Logs
{
    function __construct()
    {
        $this->db = DB::getInstance();
    }

    function saveLogs(array $data)
    {
        try {
            $req = $this->db->insert("logs")->fields($data)->result();
            if ($this->db->error()) {
                throw new Exception($this->db->error());
            }
            return true;
        } catch (Exception $ex) {
            return ["error" => $ex->getMessage()];
        }
    }
    function getLogs()
    {
        try {
            $req = $this->db->get("logs")->result();
            return $req;
        } catch (Exception $ex) {
            return ["error" => $ex->getMessage()];
        }
    }
}
