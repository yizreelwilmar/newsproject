<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Posting extends CI_Controller
{

	var $table = 'posting';
	var $tableJoin = 'category';
	var $id = 'id';
	var $select = ['posting.*', 'category.category_name AS category'];
	var $column_order = ['posting.id', 'posting.title', 'posting.featured', 'posting.choice', 'posting.thread', 'category.category_name', 'posting.is_active', 'posting.date'];
	var $column_search = ['posting.title', 'posting.seo_title', 'posting.featured', 'posting.choice', 'posting.thread', 'category.category_name', 'posting.is_active', 'posting.date'];

	public function __construct()
	{
		parent::__construct();
		$this->load->model('my_model', 'my', true);
		$this->load->model('posting_model', 'posting', true);
		$this->load->model('menu_model', 'menu', true);
		$this->load->model('category_model', 'category', true);
	}

	public function ajax_list()
	{
		$list = $this->my->get_datatables($this->tableJoin, $this->select);
		$data = [];
		foreach ($list as $li) {
			$row = [];
			$row[] = '<input type="checkbox" class="data-check" value="' . $li->id . '">';
			$row[] = $li->title;
			$row[] = $li->featured;
			$row[] = $li->choice;
			$row[] = $li->thread;
			$row[] = $li->category;
			$row[] = $li->is_active;
			$row[] = $li->date;

			$row[] =
				'<a class="btn btn-sm btn-warning text-white" href="' . base_url("back/posting/update/$li->id") . '" 
         title="Edit">
			<i class="fa fa-pencil-alt mr-1"></i></a>

			<a class="btn btn-sm btn-danger" href="#" 
			title="Delete" onclick="delete_posting(' . "'" . $li->id . "'" . ')">
			<i class="fa fa-trash mr-1"></i></a>';
			$data[] = $row;
		}

		$output = [
			'draw'            => $_POST['draw'],
			'recordsTotal'    => $this->my->count_all(),
			'recordsFiltered' => $this->my->count_filtered(),
			'data'            => $data
		];

		echo json_encode($output);
	}

	public function get_data()
	{
		$data = $this->my->get_by_id($this->input->post('id', true));
		echo json_encode($data);
	}

	public function create_guest()
	{
		if (!$_POST) {
			$input = (object) $this->posting->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}


		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');
		$this->form_validation->set_rules('id_category', 'Category', 'required');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Tambah Posting';
			$data['form_action'] = base_url("back/posting/create_guest");
			//$data['menu'] = $this->menu->getMenu();
			$data['category'] = $this->category->getCategory();
			$data['input'] = $input;
			$this->load->view('back/pages/article/form_post_frontend', $data);
		} else {
			$data = [
				'title' => $this->input->post('title', true),
				'seo_title' => slugify($this->input->post('title', true)),
				'content' => $this->input->post('content', true),
				'featured' => $this->input->post('featured', true),
				'choice' => $this->input->post('choice', true),
				'thread' => $this->input->post('thread', true),
				'id_category' => $this->input->post('id_category', true),
				'is_active' => 'N', // status not show karena akan di lihat oleh admin dulu
				'date' => date('Y-m-d')
			];

			if (!empty($_FILES['photo']['name'])) {
				$upload = $this->posting->uploadImage();
				$this->_create_thumbs($upload);
				$data['photo'] = $upload;
			}

			// Load PHPMailer library
			$mail = new PHPMailer;

			try {
				// Server settings
				$mail->isSMTP(); // Set mailer to use SMTP
				$mail->Host = 'smtp.hostinger.com'; // Specify main and backup SMTP servers
				$mail->SMTPAuth = true; // Enable SMTP authentication
				$mail->Username = 'no_reply@cvbsmtrans.com'; // SMTP username
				$mail->Password = 'Admin1981022!'; // SMTP password
				$mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 465; // TCP port to connect to

				// Recipients
				$mail->setFrom('no_reply@cvbsmtrans.com', 'News');
				$mail->addAddress('kunywm@gmail.com'); // Add a recipient

				// Content
				$mail->isHTML(true); // Set email format to HTML
				$mail->Subject = 'New Posting Created';
				$mail->Body    = " <html>
									<head>
										<title>New Posting Created!</title>
										<style>
											body {
												font-family: Arial, sans-serif;
												color: #333;
												line-height: 1.6;
											}
											.container {
												width: 80%;
												margin: 0 auto;
												padding: 20px;
												border: 1px solid #ddd;
												border-radius: 8px;
												background-color: #f9f9f9;
											}
											h1 {
												color: #0056b3;
											}
											p {
												margin: 10px 0;
											}
											.footer {
												margin-top: 20px;
												padding-top: 10px;
												border-top: 1px solid #ddd;
												text-align: center;
												font-size: 0.9em;
												color: #666;
											}
										</style>
									</head>
									<body>
										<div class='container'>
											<h1>New Posting Created!</h1>
											<p>A new posting has been created on your site.</p>
											<p><strong>Title:</strong> " . htmlspecialchars($this->input->post('title', true)) . "</p>
											<p><strong>Content:</strong> " . nl2br(htmlspecialchars($this->input->post('content', true))) . "</p>
											<p><strong>Category:</strong> " . htmlspecialchars($this->input->post('id_category', true)) . "</p>
											<div class='footer'>
												<p>Thank you for using our service.</p>
												<p>&copy; " . date('Y') . " News by Yiz_dev</p>
											</div>
										</div>
									</body>
									</html>
								";


				// Send the email
				$mail->send();

				// If email is sent successfully, save the data to the database
				$this->my->save($data);

				// Set success flash message and redirect
				$this->session->set_flashdata('success', 'Posting Berhasil Ditambahkan.');
				redirect(base_url('back/posting/create_guest'));
			} catch (Exception $e) {
				// Email sending failed
				$this->session->set_flashdata('error', 'Failed to send email. Error: ' . $mail->ErrorInfo);
				redirect(base_url('back/posting/create_guest'));
			}
		}
	}

	public function create()
	{
		if (!$_POST) {
			$input = (object) $this->posting->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}


		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');
		$this->form_validation->set_rules('id_category', 'Category', 'required');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Tambah Posting';
			$data['form_action'] = base_url("back/posting/create");
			$data['menu'] = $this->menu->getMenu();
			$data['category'] = $this->category->getCategory();
			$data['input'] = $input;
			$this->load->view('back/pages/article/form_post', $data);
		} else {
			$data = [
				'title' => $this->input->post('title', true),
				'seo_title' => slugify($this->input->post('title', true)),
				'content' => $this->input->post('content', true),
				'featured' => $this->input->post('featured', true),
				'choice' => $this->input->post('choice', true),
				'thread' => $this->input->post('thread', true),
				'id_category' => $this->input->post('id_category', true),
				'is_active' => $this->input->post('is_active', true),
				'date' => date('Y-m-d')
			];

			if (!empty($_FILES['photo']['name'])) {
				$upload = $this->posting->uploadImage();
				$this->_create_thumbs($upload);
				$data['photo'] = $upload;
			}

			// Load PHPMailer library
			$mail = new PHPMailer;

			try {
				// Server settings
				$mail->isSMTP(); // Set mailer to use SMTP
				$mail->Host = 'smtp.hostinger.com'; // Specify main and backup SMTP servers
				$mail->SMTPAuth = true; // Enable SMTP authentication
				$mail->Username = 'no_reply@cvbsmtrans.com'; // SMTP username
				$mail->Password = 'Admin1981022!'; // SMTP password
				$mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 465; // TCP port to connect to

				// Recipients
				$mail->setFrom('no_reply@cvbsmtrans.com', 'News');
				$mail->addAddress('kunywm@gmail.com'); // Add a recipient

				// Content
				$mail->isHTML(true); // Set email format to HTML
				$mail->Subject = 'New Posting Created';
				$mail->Body    = "
            <html>
            <head>
                <title>New Posting Created</title>
            </head>
            <body>
                <p>A new posting has been created on your site.</p>
                <p><strong>Title:</strong> " . $this->input->post('title', true) . "</p>
                <p><strong>Content:</strong> " . $this->input->post('content', true) . "</p>
                <p><strong>Category:</strong> " . $this->input->post('id_category', true) . "</p>
            </body>
            </html>
            ";

				// Send the email
				$mail->send();
				$this->my->save($data);
				$this->session->set_flashdata('success', 'Posting Berhasil Ditambahkan.');
				redirect(base_url('admin/posting'));
			} catch (Exception $e) {
				// Email sending failed
				$this->session->set_flashdata('error', 'Failed to send email. Error: ' . $mail->ErrorInfo);
				redirect(base_url('admin/posting'));
			}
		}
	}


	public function test_email()
	{
		$this->load->library('email');
		$this->email->initialize($this->config->item('email'));

		$this->email->from('no_reply@cvbsmtrans.com', 'Your Site Name');
		$this->email->to('kunywm@gmail.com');
		$this->email->subject('Test Email');
		$this->email->message('This is a test email.');

		if ($this->email->send()) {
			echo 'Email sent successfully!';
		} else {
			echo 'Failed to send email.';
			echo $this->email->print_debugger();
		}
	}





	public function update($id)
	{
		$dataPost = $this->posting->getPostingById($id);

		if (!$dataPost) {
			$this->session->set_flashdata('warning', 'Maaf, data tidak dapat ditemukan!');
			redirect(base_url('admin/posting'));
		}

		if (!$_POST) {
			$input = $dataPost;
		} else {
			$input = (object) $this->input->post(null, true);
		}

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');
		$this->form_validation->set_rules('id_category', 'Category', 'required');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Edit Posting';
			$data['form_action'] = base_url("back/posting/update/$id");
			$data['menu'] = $this->menu->getMenu();
			$data['category'] = $this->category->getCategory();
			$data['input'] = $input;
			$this->load->view('back/pages/article/form_post', $data);
		} else {

			$data = [
				'title' => $this->input->post('title', true),
				'seo_title' => slugify($this->input->post('title', true)),
				'content' => $this->input->post('content', true),
				'featured' => $this->input->post('featured', true),
				'choice' => $this->input->post('choice', true),
				'thread' => $this->input->post('thread', true),
				'id_category' => $this->input->post('id_category', true),
				'is_active' => $this->input->post('is_active', true),
				'date' => date('Y-m-d')
			];


			if (!empty($_FILES['photo']['name'])) {
				$upload = $this->posting->uploadImage();
				$this->_create_thumbs($upload);
				$posting = $this->my->get_by_id($id);

				if (file_exists('images/posting/' . $posting->photo) && $posting->photo) {
					unlink('images/posting/' . $posting->photo);
					unlink('images/posting/large/' . $posting->photo);
					unlink('images/posting/medium/' . $posting->photo);
					unlink('images/posting/small/' . $posting->photo);
					unlink('images/posting/xsmall/' . $posting->photo);
				}

				$data['photo'] = $upload;
			}

			$this->my->update(['id' => $id], $data);
			$this->session->set_flashdata('success', 'Posting Berhasil Diupdate.');

			redirect(base_url('admin/posting'));
		}
	}

	public function _create_thumbs($file_name)
	{
		$config = [
			// Large Image
			[
				'image_library'	=> 'GD2',
				'source_image'		=> './images/posting/' . $file_name,
				'maintain_ratio'	=> TRUE,
				'width'				=> 770,
				'height'				=> 450,
				'new_image'			=> './images/posting/large/' . $file_name
			],
			// Medium Image
			[
				'image_library'	=> 'GD2',
				'source_image'		=> './images/posting/' . $file_name,
				'maintain_ratio'	=> FALSE,
				'width'				=> 300,
				'height'				=> 188,
				'new_image'			=> './images/posting/medium/' . $file_name
			],
			// Small Image
			[
				'image_library'	=> 'GD2',
				'source_image'		=> './images/posting/' . $file_name,
				'maintain_ratio'	=> FALSE,
				'width'				=> 270,
				'height'				=> 169,
				'new_image'			=> './images/posting/small/' . $file_name
			],
			// XSmall Image
			[
				'image_library'	=> 'GD2',
				'source_image'		=> './images/posting/' . $file_name,
				'maintain_ratio'	=> FALSE,
				'width'				=> 170,
				'height'				=> 100,
				'new_image'			=> './images/posting/xsmall/' . $file_name
			],
		];

		$this->load->library('image_lib', $config[0]);

		foreach ($config as $item) {
			$this->image_lib->initialize($item);

			if (!$this->image_lib->resize()) {
				return false;
			}

			$this->image_lib->clear();
		}
	}

	public function delete()
	{
		$id = $this->input->post('id', true);
		$posting = $this->my->get_by_id($id);

		if (file_exists('images/posting/' . $posting->photo) && $posting->photo) {
			unlink('images/posting/' . $posting->photo);
			unlink('images/posting/large/' . $posting->photo);
			unlink('images/posting/medium/' . $posting->photo);
			unlink('images/posting/small/' . $posting->photo);
			unlink('images/posting/xsmall/' . $posting->photo);
		}

		$this->my->delete($id);
		echo json_encode(["status" => TRUE]);
	}

	public function bulk_delete()
	{
		$list_id = $this->input->post('id', true);

		foreach ($list_id as $id) {
			$posting = $this->my->get_by_id($id);

			if (file_exists('images/posting/' . $posting->photo) && $posting->photo) {
				unlink('images/posting/' . $posting->photo);
				unlink('images/posting/large/' . $posting->photo);
				unlink('images/posting/medium/' . $posting->photo);
				unlink('images/posting/small/' . $posting->photo);
				unlink('images/posting/xsmall/' . $posting->photo);
			}

			$this->my->delete($id);
		}

		echo json_encode(["status" => TRUE]);
	}
}

/* End of file Posting.php */
