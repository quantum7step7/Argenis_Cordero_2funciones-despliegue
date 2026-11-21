CREATE DATABASE IF NOT EXISTS agenda_uno CHARACTER SET utf8mb4;
USE agenda_uno;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO categorias (nombre) VALUES
  ('Familia'),
  ('Amigos'),
  ('Trabajo'),
  ('Universidad'),
  ('Otro')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

CREATE TABLE IF NOT EXISTS contactos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_categoria INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  telefono VARCHAR(30) NOT NULL,
  correo VARCHAR(120) NOT NULL,
  ciudad VARCHAR(80) NOT NULL,
  favorito TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
  FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

-- Usuario de demostracion: ana@correo.com / password
INSERT INTO usuarios (id, nombre, correo, password) VALUES
  (1, 'Ana Torres', 'ana@correo.com', '$2y$10$E02BkV687M5PVTHYmPqBBO1mioTMSsFOMJwzqwGy0yUsTv3mE.h.2')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- Contactos de demostracion: uno pertenece a Familia y es favorito.
INSERT INTO contactos (id_usuario, id_categoria, nombre, telefono, correo, ciudad, favorito) VALUES
  (1, 1, 'Luis Herrera', '0991112233', 'luis.herrera@correo.com', 'Quito', 1),
  (1, 2, 'Marta Vela', '0984445566', 'marta.vela@correo.com', 'Cuenca', 0);
