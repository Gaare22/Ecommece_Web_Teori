<?php 

class Data_barang extends CI_Controller{
    public function __construct(){
        parent::__construct();

        if($this->session->userdata('role_id') != '1'){
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Warning!</strong> Anda Belum Login
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>');
          redirect('auth/login');
        }
    }
    
    public function index()
    {
        $data['barang'] = $this->model_barang->tampil_data()->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/data_barang', $data);
        $this->load->view('templates_admin/footer');   
    }

    public function tambah_aksi()
    {
        $nama_barang    = $this->input->post('nama_barang');
        $keterangan     = $this->input->post('keterangan');
        $kategori       = $this->input->post('kategori');
        $harga          = $this->input->post('harga');
        $stok           = $this->input->post('stok');
        $gambar     = $_FILES['gambar']['name'] ;
        if ($gambar = ''){}else{
            $config ['upload_path'] = './uploads';
            $config ['allowed_types'] = 'jpg|jpeg|png|gif';

            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('gambar')){
                echo "Gambar Gagal DiUpload!";
            }else{
                $gambar = $this->upload->data('file_name');
            }
        }

        $data = array(
            'nama_barang'       => $nama_barang,
            'keterangan'        => $keterangan,
            'kategori'          => $kategori,
            'harga'             => $harga,
            'stok'              => $stok,
            'gambar'        => $gambar
        );

        $this->model_barang->tambah_barang($data, 'product');
        redirect('admin/data_barang/index');
    }

    public function edit($id)
    {
        $where = array('id_barang' =>$id);
        $data['barang'] = $this->model_barang->edit_barang($where, 'product')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/edit_barang', $data);
        $this->load->view('templates_admin/footer');   
    }

    public function update()
    {
        $id             = $this->input->post('id_barang');
        $nama_barang    = $this->input->post('nama_barang');
        $keterangan     = $this->input->post('keterangan');
        $kategori       = $this->input->post('kategori');
        $harga          = $this->input->post('harga');
        $stok           = $this->input->post('stok');
        
        $data = array(
            'nama_barang'   => $nama_barang,
            'keterangan'    => $keterangan,
            'kategori'      => $kategori,
            'harga'         => $harga,
            'stok'          => $stok
        );

        $where = array(
            'id_barang' => $id
        );

        $this->model_barang->update_data($where, $data, 'product');
        redirect('admin/data_barang/index');
    }

    public function hapus($id){
        $where = array('id_barang' => $id);
        $this->model_barang->hapus_data($where, 'product');
        redirect('admin/data_barang/index');
    }

    public function detail($id_barang)
    {
        $data['barang'] = $this->model_barang->detail_barang_admin($id_barang);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/detail_barang_admin', $data);
        $this->load->view('templates_admin/footer');
    }
}