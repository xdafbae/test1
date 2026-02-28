Web Developer Technical Test


Petunjuk Umum :
 Test ini bersifat Technical, Anda diwajibkan untuk membuat /
menyelesaikan tugas sesuai dengan petunjuk yang diberikan.
 Berikan jawaban terbaik Anda dalam memecahkan permasalahan yang ada.
 Penilaian diberikan berdasarkan cara / solusi untuk memecahkan
permasalahan serta efektifitas solusi.
 Bahasa yang digunakan PHP Laravel 5.
Hal-hal yang wajib ada di dalam project dan menjadi penilaian :
 URL Demo untuk applikasi nya
 README.md yang berisi tentang tentang library apa saja yang digunakan, 
Architecture apa yang digunakan, dan screenshot dari aplikasi yang
dikerjakan.
 Anda tidak diperkenankan untuk mengunggah paper dan solusi yang Anda
kerjakan secara public melalui media lain seperti Github, Gitlab, dan
sebagainya.
 
Petunjuk Test :
 Silakan buka dan registrasi free account terlebih dahulu
di http://www.omdbapi.com/ untuk mendapatkan API Key yang nantinya
akan dipakai untuk mengerjakan soal test ini
 Dokumentasi cara penggunaan API dapat dibaca
di http://www.omdbapi.com/
 
Soal Test :
 Buatlah aplikasi Laravel 5 yang berisikan 3 halaman utama sebagai berikut:
1. Halaman Login
Username: aldmic
Password: 123abc123
2. Halaman List Movie
3. Halaman Detail Movie

 Sebelum dapat mengakses List Movie ataupun melihat Detail
Movie, user diharuskan untuk melakukan Login terlebih dahulu
menggunakan credential sesuai yang tertera di atas. Apabila credential yang
dimasukkan tidak sesuai, maka aplikasi harus dapat menampilkan Pesan
Kesalahan.
 User dapat melakukan pencarian Movie menggunakan
beberapa Parameter, dan apabila salah satu Movie di-klik, maka akan
ditampilkan informasi detail dari Movie tersebut.
 Selain itu, tidak kalah penting juga, user juga dapat menambahkan Favorite
Movie yang dia suka lewat halaman List Movie maupun halaman
Detail Movie, dan user dapat melihat kumpulan daftar Favorite Movie yang
sudah ditambahkan sebelumnya di halaman tersendiri, dimana di halaman
ini, user juga dapat menghapus Favorite Movie yang sudah ditambahkan
sebelumnya.
 
Ketentuan Test :
 Aplikasi yang dikerjakan harus menggunakan Laravel 5
 Aplikasi juga harus menerapkan sistem Multi Language (ID / EN),
dimana Default Language yang digunakan adalah Inggris (EN), akan tetapi
user dapat mengganti bahasa yang diinginkan. (optional)
 Lokalisasi bahasa yang dimaksud hanya diterapkan untuk kata-kata statik di
luar data yang diperoleh dari OMDb API. Untuk data yang didapatkan
dari response OMDb API tidak perlu dilakukan lokalisasi.
 Pada halaman Daftar Movie, wajib mengimplementasikan Infinite
Scroll untuk menampilkan data selanjutnya.
 Wajib mengimplementasikan Lazy Load untuk menampilkan gambar /
foto Movie.
 
Kriteria Penilaian :
 Desain UI / UX.
 Library yang digunakan.
 Kerapian Coding (Penamaan variable, function, indentation, dll).
 Penggunaan &amp; Pengimplementasian OMDb API dalam aplikasi.
 Pengimplementasian Keamanan aplikasi (Login requirement).
 Pengimplementasian Filter &amp; Pencarian Movie.
 Penggunaan empty layout jika data yang akan ditampilkan tidak ada / data
kosong.