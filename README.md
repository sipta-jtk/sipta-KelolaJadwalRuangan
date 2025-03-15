# sipta-KelolaJadwalRuangan

Repositori ini merupakan bagian dari proyek SIPTA.

## Prerequisites

- Docker
- Docker Compose

## Pengaturan

1. Clone repositori:

    ```sh
    git clone https://github.com/sipta-jtk/sipta-KelolaJadwalRuangan.git
    cd sipta-KelolaJadwalRuangan
    ```

2. Salin file `.env.example` ke `.env` dan perbarui variabel lingkungan sesuai kebutuhan:

    ```sh
    cp .env.example .env
    ```

3. Jika Anda tidak ingin menggunakan seeder, ubah variabel `RUN_SEEDERS` di `.env` menjadi `false` (opsional).

4. Bangun dan mulai kontainer Docker:

    ```sh
    docker-compose up -d --build
    ```

## Usage

- Aplikasi akan tersedia di `http://localhost:8080`.
- Anda dapat mengakses database MySQL di `localhost:3306`.
