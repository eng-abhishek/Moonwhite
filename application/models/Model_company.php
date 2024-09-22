<?php 

class Model_company extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getCompanyData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM `company` WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('company', $data); 
			return ($update == true) ? true : false;
		}
	}

	public function getDateFormat()
	{
			$sql = "SELECT * FROM `date_format`";
			$query = $this->db->query($sql);
			return $query->result_array();
	}

	public function get_currency_date_format(){
    return $this->db->select('a.currency,b.date_format')->from('company a')->join('date_format b','b.id=a.date_format')->get()->row_array();
    }

}