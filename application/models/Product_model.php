<?php

class Product_model extends CI_Model{
	function getProduct($id = NULL)
	{
        $product            = $this->db->select('*')
                            ->from('products');
        if(!empty($id)){
            $product        = $this->db->where('id', $id);
        }
        $product            = $this->db->get();

        //echo $this->db->last_query();
		return $product;

    }
    function InsertProduct($data)
    {
        $product            = $this->db->insert('products', $data);

        return $product;
    }
    function UpdateProduct($id,$data)
    {
        $update             = $this->db->where('id', $id)
                            ->update('products', $data);
        return $update;
    }
    function DeleteProduct($id)
    {
        $product            = $this->db->from('products')->where('id', $id)->get();
        if(!empty($product)){
            $this->db->where('id', $id)->delete('products');
            return $product;
        }
    }
}