<?php 

class Model_asset extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveAsset()
	{
		$sql = "SELECT assets.*,expences_code.title FROM `assets` JOIN expences_code ON expences_code.code=assets.code WHERE assets.active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getAssetData($id = null)
	{
		if($id) {
			$sql = "SELECT assets.*,expences_code.title FROM `assets` JOIN expences_code ON expences_code.code=assets.code WHERE assets.id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT assets.*,expences_code.title FROM `assets` JOIN expences_code ON expences_code.code=assets.code";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('assets', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('assets', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('assets');
			return ($delete == true) ? true : false;
		}
	}

}