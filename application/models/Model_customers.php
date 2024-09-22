<?php 

class Model_customers extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function customerData($id){
    return $this->db->select('code,name')->from('customers')->where('id',$id)->get()->row_array();
	}

	/* get the active store data */
	public function getActiveCustomers()
	{
		$sql = "SELECT * FROM `customers` WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getCustomersData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM `customers` where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM `customers`";
		$query = $this->db->query($sql);
		return $query->result_array();
	}



	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('customers', $data);
            $insertId=$this->db->insert_id();
            $arr=array('code'=>1000+$insertId);
            $this->db->set($arr)->where('id',$insertId)->update('customers');
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('customers', $data);
			return ($update == true) ? true : false;
		}

		  
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('customers');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalCustomers()
	{
		$sql = "SELECT * FROM `customers` WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

}