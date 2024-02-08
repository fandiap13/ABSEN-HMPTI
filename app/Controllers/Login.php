<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MemberModel;

class Login extends BaseController
{
    protected $MemberModel;
    protected $google_client;

    public function __construct()
    {
        $this->google_client = new \Google_Client();
        $this->google_client->setClientId("422074532634-lburpjg5usi22se2hdur0875nspftmpr.apps.googleusercontent.com");
        $this->google_client->setClientSecret("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");
        $this->google_client->setRedirectUri(base_url('loginWithGoogle'));
        $this->google_client->addScope(['email', 'profile']);

        $this->MemberModel = new MemberModel();
    }

    public function index()
    {
        if ($this->request->getPost()) {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|trim|valid_email',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'valid_email' => '{field} tidak valid'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
            ]);

            // reset space
            $input = [];
            foreach ($this->request->getPost() as $key => $value) {
                $input[$key] = htmlspecialchars(trim($value));
            }
            $email = $input['email'];
            $password = $input['password'];

            if (!$validation->run($input)) {
                return redirect()->to(base_url('login'))->withInput();
            }

            // cek email
            $cekEmail = $this->MemberModel->getAnggotaEmail($email)->getRowArray();
            if (!$cekEmail) {
                session()->setFlashdata('msg', 'error#Email ' . $email . ' tidak terdaftar pada sistem');
                return redirect()->to(base_url('login'));
            }

            // mengambil keputusan
            $accessAdmin = ['ketua', 'wakil ketua', 'sekertaris'];
            if (in_array(strtolower($cekEmail['nama_jabatan']), $accessAdmin)) {
                $role = 'admin';
                $redirect = base_url('admin/dashboard');
            } else {
                $role = 'user';
                $redirect = base_url('anggota');
            }

            // cek password untuk admin dan user
            if ($role === "admin") {
                $passwordVerifikasi = $cekEmail['nim'] . "-rahasiaKita";
                if ($password !== $passwordVerifikasi) {
                    session()->setFlashdata('msg', 'error#Password salah!');
                    return redirect()->to(base_url('login'))->withInput();
                }
            } else {
                $passwordVerifikasi = $cekEmail['nim'];
                if ($password !== $passwordVerifikasi) {
                    session()->setFlashdata('msg', 'error#Password salah!');
                    return redirect()->to(base_url('login'))->withInput();
                }
            }

            $payload = [
                'LoggedUserData' => [
                    'nim' => $cekEmail['nim'],
                    'email' => $email,
                    'nama' => $cekEmail['nama'],
                    'image' => $cekEmail['image'],
                    'jabatan' => $cekEmail['nama_jabatan'],
                    'divisi' => $cekEmail['nama_divisi'],
                    'waktu_login' => date('d-m-Y H:i:s'),
                    'role' => $role
                ]
            ];
            session()->set($payload);
            session()->setFlashdata('msg', 'success#Berhasil login sebagai ' . $cekEmail['nama']);
            return redirect()->to($redirect)->withInput();
        } else {
            return view('v_login', [
                'googleButton' => $this->google_client->createAuthUrl()
            ]);
        }
    }

    public function loginWithGoogle()
    {
        if (isset($_GET['code'])) {
            $token = $this->google_client->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
            if (!isset($token["error"])) {
                $this->google_client->setAccessToken($token['access_token']);
                session()->set('access_token', $token['access_token']);
                $google_service = new \Google_Service_Oauth2($this->google_client);
                $data = $google_service->userinfo->get();

                // $user_data = array(
                //     'first_name' => $data['given_name'],
                //     'last_name'  => $data['family_name'],
                //     'email_address' => $data['email'],
                //     'profile_picture' => $data['picture'],
                //     'updated_at' => $current_datetime
                // );
                // dd($user_data);
                // $this->session->set_userdata('user_data', $data);

                $cekAkun = $this->MemberModel->getAnggotaEmail($data['email'])->getRowArray();
                if ($cekAkun) {
                    // mengambil keputusan
                    $accessAdmin = ['ketua', 'wakil ketua', 'sekertaris'];
                    if (in_array(strtolower($cekAkun['nama_jabatan']), $accessAdmin)) {
                        $role = 'admin';
                        $redirect = base_url('admin/dashboard');
                    } else {
                        $role = 'user';
                        $redirect = base_url('anggota');
                    }
                    $payload = [
                        'LoggedUserData' => [
                            'nim' => $cekAkun['nim'],
                            'email' => $data['email'],
                            'nama' => $cekAkun['nama'],
                            'image' => $cekAkun['image'],
                            'jabatan' => $cekAkun['nama_jabatan'],
                            'divisi' => $cekAkun['nama_divisi'],
                            'waktu_login' => date('d-m-Y H:i:s'),
                            'role' => $role
                        ]
                    ];
                    session()->set($payload);
                    session()->setFlashdata('msg', 'success#Berhasil login sebagai ' . $cekAkun['nama']);
                    return redirect()->to($redirect);
                } else {
                    session()->setFlashdata('msg', 'error#Email ' . $data['email'] . ' tidak terdaftar pada sistem');
                    return redirect()->to(base_url('login'));
                }
            }
        } else {
            return redirect()->to(base_url('login'));
        }
    }
}
