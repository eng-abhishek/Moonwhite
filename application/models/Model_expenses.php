<?php 

class Model_expenses extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getExpencesCode(){
    return $this->db->select('*')->from('expences_code')->where('active','1')->order_by('code','AESC')->get()->result_array();
	}

	/* get active brand infromation */
	public function getActiveExpenses()
	{
		$sql = "SELECT expenses.*,expences_code.title FROM `expenses` JOIN expences_code ON expences_code.code=expenses.code WHERE expenses.active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getExpensesData($id = null)
	{
		if($id) {
			$sql = "SELECT expenses.*,expences_code.title FROM `expenses` JOIN expences_code ON expences_code.code=expenses.code WHERE expenses.id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT expenses.*,expences_code.title FROM `expenses` JOIN expences_code ON expences_code.code=expenses.code";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('expenses', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('expenses', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('expenses');
			return ($delete == true) ? true : false;
		}
	}

}