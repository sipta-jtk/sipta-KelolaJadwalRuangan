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

- Aplikasi akan tersedia di `http://localhost:8005`.
- Anda dapat mengakses database MySQL di `localhost:3309`.

## Jika menggunakan docker image dockerhub:

1. Pull image dari dockerhub repositori:

    ```sh
    docker pull bujank/sipta-kelolajadwalruangan:latest
    ```
2. Buat folder baru, lalu isi dengan docker-compose.yml sebagai berikut, lalu simpan:

    ```yaml
    version: '3.8'

    services:
        app:
            image: bujank/sipta-kelolajadwalruangan:latest
            container_name: kelola_ruangan_app
            restart: unless-stopped
            working_dir: /var/www
            env_file:
            - .env
            volumes:
            - vendor:/var/www/vendor
            - node_modules:/var/www/node_modules
            ports:
            - "8005:9000"
            networks:
            - app_network
            depends_on:
            - db
            environment:
            - APP_ENV=local
            - APP_DEBUG=true
            - APP_KEY=
        
        db:
            image: mysql:8.0
            container_name: kelola_ruangan_db
            restart: unless-stopped
            environment:
            MYSQL_DATABASE: ${DB_DATABASE}
            MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
            MYSQL_USER: ${DB_USERNAME}
            MYSQL_PASSWORD: ${DB_PASSWORD}
            ports:
            - "3309:3306"
            networks:
            - app_network
            volumes:
            - db_data:/var/lib/mysql

    networks:
        app_network:

    volumes:
        db_data:
        vendor:
        node_modules:
    ```

3. Buat juga file .env (cukup copy dari .env.example dari repo ini).

4. Struktur isi folder menjadi seperti berikut:
    - tesSiptaKelolaJadwal
        - docker-compose.yml
        - .env

5. Jalankan docker-compose:

    ```sh
    docker-compose up -d
    ```