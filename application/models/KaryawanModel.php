<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class KaryawanModel extends CI_Model
{
    private $table;
    private $primaryKey;
    private $column_order;
    private $column_search;
    private $order;

    function __construct()
    {
        parent::__construct();
        $this->table = 'karyawan';
        $this->primaryKey = 'id_karyawan';
        $this->column_order = [null, 'id_karyawan', 'email', 'no_hp'];
        $this->column_search = ['nama_lengkap', 'email', 'no_hp'];
        $this->order = ['id_karyawan' => 'DESC'];
    }

    function get($orderBy)
    {
        $this->db->order_by($orderBy);
        $query = $this->db->get($this->table);
        return $query;
    }

    function getWithLimit($limit, $offset, $orderBy)
    {
        $this->db->limit($limit, $offset);
        $this->db->order_by($orderBy);
        $query = $this->db->get($this->table);
        return $query;
    }

    function getById($id)
    {
        $this->db->where($this->primaryKey, $id);
        $query = $this->db->get($this->table);
        return $query;
    }

    function getWhereCustom($col, $value)
    {
        $this->db->where($col, $value);
        $query = $this->db->get($this->table);
        return $query;
    }

    function insertTable($data)
    {
        $this->db->insert($this->table, $data);
        $cek = $this->db->error();
        if($cek['code']!==0){
            return [
                'success' => false,
                'message' => $cek['message']
            ];
        }else{
            return [
                'success' => true,
                'message' => $this->db->insert_id()
            ];
        }
    }

    function updateTable($id, $data)
    {
        $this->db->where($this->primaryKey, $id);
        $this->db->update($this->table, $data);
        $cek = $this->db->error();
        if($cek['code']!==0){
            return [
                'success' => false,
                'message' => $cek['message']
            ];
        }else{
            return [
                'success' => true,
                'message' => 'success'
            ];
        }
    }

    function deleteTable($id)
    {
        $this->db->where($this->primaryKey, $id);
        $this->db->delete($this->table);
        $cek = $this->db->error();
        if($cek['code']!==0){
            return [
                'success' => false,
                'message' => $cek['message']
            ];
        }else{
            return [
                'success' => true,
                'message' => 'success'
            ];
        }
    }

    function countWhere($col, $value)
    {
        $this->db->where($col, $value);
        $query = $this->db->get($this->table);
        $count = $query->num_rows();
        return $count;
    }

    function countAll()
    {
        $query = $this->db->get($this->table);
        $count = $query->num_rows();
        return $count;
    }

    function getMax()
    {
        $this->db->select_max($this->primaryKey);
        $query = $this->db->get($this->table);
        $row = $query->row();
        return $row->{$this->primaryKey};
    }

    function customQuery($sql)
    {
        return $this->db->query($sql);
    }

    /**
     * Khusus untuk DataTables
     */
    private function _get_datatables_query($post)
    {
        
        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($post['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $post['search']['value']);
                } else {
                    $this->db->or_like($item, $post['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($post['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$post['order']['0']['column']], $post['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($post)
    {
        $this->_get_datatables_query($post);
        if ($post['length'] != -1)
            $this->db->limit($post['length'], $post['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($post)
    {
        $this->_get_datatables_query($post);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}