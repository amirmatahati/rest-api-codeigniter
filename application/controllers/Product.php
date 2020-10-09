<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;


class Product extends RestController {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('product_model');
        $this->load->library('form_validation');
    }
    public function index_get()
    {   
        $id                             = $this->uri->segment(4);

        $product                        = $this->product_model->getProduct($id)->result();
        if($product){
            $this->response( $product, 200 );
        }else{
            $this->response( [
                'status' => false,
                'message' => 'No users were found'
            ], 404 );
        }
        
    }   

    public function index_post()
    {
        $data = array(
            'name'          => $this->post('name'),
            'description'   => $this->post('description'),
            'price'         => $this->post('price'),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        );
        $config = [
            [
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'required|min_length[5]|alpha_dash',
                    'errors' => [
                            'required' => 'name cannot be null',
                            'min_length' => 'Minimum Name length is 5 characters',
                            'alpha_dash' => 'You can only use a-z 0-9 _ . – characters for input',
                    ],
            ],
            [
                    'field' => 'price',
                    'label' => 'Price',
                    'rules' => 'required|regex_match[/^[0-9]{10}$/]',
                    'errors' => [
                            'required' => 'Price cannot be null',
                            'min_length' => 'must input numeric',
                    ],
            ],
        ];
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()==FALSE){
            $product = $this->product_model->InsertProduct($data);
            if ($product) {
                $this->response($product, 200);
            } else {
                $this->response(array('status' => 'fail', 502));
            }
        }else{
            $this->response(array('status' => 'fail', 502));
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $data = array(
            'name'          => $this->put('name'),
            'description'   => $this->put('description'),
            'price'         => $this->put('price')
        );
        $product                    = $this->product_model->UpdateProduct($id,$data);
        if ($product) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    public function index_patch()
    {
        echo "PATCH_request";
    }

    public function index_delete()
    {
        $id                         = $this->delete('id');
        $product                    = $this->product_model->DeleteProduct($id);
        //$this->response($product);
        if ($product->num_rows()) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'no data found', 502));
        }
    }
}