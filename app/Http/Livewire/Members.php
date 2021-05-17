<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Member;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;

class Members extends Component
{
    use WithFileUploads;
    public $members, $nama, $harga, $keterangan, $image, $kategori, $member_id;
    public $isModal = 0;

    //FUNGSI INI UNTUK ME-LOAD VIEW YANG AKAN MENJADI TAMPILAN HALAMAN MEMBER
    public function render()
    {
        $this->members = Member::orderBy('created_at', 'DESC')->get(); //MEMBUAT QUERY UNTUK MENGAMBIL DATA
        return view('livewire.members'); //LOAD VIEW MEMBERS.BLADE.PHP YG ADA DI DALAM FOLDER /RESOURSCES/VIEWS/LIVEWIRE
    }

    //FUNGSI INI AKAN DIPANGGIL KETIKA TOMBOL TAMBAH ANGGOTA DITEKAN
    public function create()
    {
        //KEMUDIAN DI DALAMNYA KITA MENJALANKAN FUNGSI UNTUK MENGOSONGKAN FIELD
        $this->resetFields();
        //DAN MEMBUKA MODAL
        $this->openModal();
    }

    //FUNGSI INI UNTUK MENUTUP MODAL DIMANA VARIABLE ISMODAL KITA SET JADI FALSE
    public function closeModal()
    {
        $this->isModal = false;
    }

    //FUNGSI INI DIGUNAKAN UNTUK MEMBUKA MODAL
    public function openModal()
    {
        $this->isModal = true;
    }

    //FUNGSI INI UNTUK ME-RESET FIELD/KOLOM, SESUAIKAN FIELD APA SAJA YANG KAMU MILIKI
    public function resetFields()
    {
        $this->nama = '';
        $this->harga = '';
        $this->keterangan = '';
        $this->image = '';
        $this->kategori = '';
        $this->member_id = '';
    }

    //METHOD STORE AKAN MENG-HANDLE FUNGSI UNTUK MENYIMPAN / UPDATE DATA
    public function store()
    {
        //MEMBUAT VALIDASI
        $this->validate([
            'nama' => 'required|string',
            'harga' => 'required|string,',
            'keterangan' => 'required|string',
            'image' => 'required|max:1024',
            'kategori' => 'required'
        ]);

        //QUERY UNTUK MENYIMPAN / MEMPERBAHARUI DATA MENGGUNAKAN UPDATEORCREATE
        //DIMANA ID MENJADI UNIQUE ID, JIKA IDNYA TERSEDIA, MAKA UPDATE DATANYA
        //JIKA TIDAK, MAKA TAMBAHKAN DATA BARU
        Member::updateOrCreate(['id' => $this->member_id], [
            'nama' => $this->nama,
            'harga' => $this->harga,
            'keterangan' => $this->keterangan,
            'image' => $this->image,
            'kategori' => $this->kategori,
        ]);

        //BUAT FLASH SESSION UNTUK MENAMPILKAN ALERT NOTIFIKASI
        session()->flash('message', $this->member_id ? $this->name . ' Diperbaharui' : $this->name . ' Ditambahkan');
        $this->closeModal(); //TUTUP MODAL
        $this->resetFields(); //DAN BERSIHKAN FIELD
    }

    //FUNGSI INI UNTUK MENGAMBIL DATA DARI DATABASE BERDASARKAN ID MEMBER
    public function edit($id)
    {
        $member = Member::find($id); //BUAT QUERY UTK PENGAMBILAN DATA
        //LALU ASSIGN KE DALAM MASING-MASING PROPERTI DATANYA
        $this->member_id = $id;
        $this->nama = $member->nama;
        $this->harga = $member->harga;
        $this->keterangan = $member->keterangan;
        $this->image = $member->image;
        $this->kategori = $member->kategori;

        $this->openModal(); //LALU BUKA MODAL
    }

    //FUNGSI INI UNTUK MENGHAPUS DATA
    public function delete($id)
    {
        $member = Member::find($id); //BUAT QUERY UNTUK MENGAMBIL DATA BERDASARKAN ID
        $member->delete(); //LALU HAPUS DATA
        session()->flash('message', $member->nama . ' Dihapus'); //DAN BUAT FLASH MESSAGE UNTUK NOTIFIKASI
    }
}
