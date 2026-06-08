-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: montessori_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alumnos` (
  `id_alumno` int(11) NOT NULL AUTO_INCREMENT,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `estado_activo` int(11) DEFAULT 1,
  PRIMARY KEY (`id_alumno`),
  UNIQUE KEY `dni_UNIQUE` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumnos`
--

LOCK TABLES `alumnos` WRITE;
/*!40000 ALTER TABLE `alumnos` DISABLE KEYS */;
INSERT INTO `alumnos` VALUES (1,'45123456','Lucas','Gimenez',1);
/*!40000 ALTER TABLE `alumnos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asistencia_diaria`
--

DROP TABLE IF EXISTS `asistencia_diaria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia_diaria` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `estado` varchar(20) NOT NULL,
  `matriculas_id_matricula` int(11) NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_asistencia`),
  KEY `fk_asistencia_diaria_matriculas1_idx` (`matriculas_id_matricula`),
  KEY `fk_asistencia_diaria_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_asistencia_diaria_matriculas1` FOREIGN KEY (`matriculas_id_matricula`) REFERENCES `matriculas` (`id_matricula`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_asistencia_diaria_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia_diaria`
--

LOCK TABLES `asistencia_diaria` WRITE;
/*!40000 ALTER TABLE `asistencia_diaria` DISABLE KEYS */;
INSERT INTO `asistencia_diaria` VALUES (1,'2026-06-08 08:00:00','Presente',1,1);
/*!40000 ALTER TABLE `asistencia_diaria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comedor_registro`
--

DROP TABLE IF EXISTS `comedor_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comedor_registro` (
  `id_registro_comedor` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `cantidad_raciones` int(11) NOT NULL,
  `cursos_id_curso` int(11) NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  `raciones_sobrantes` int(11) NOT NULL,
  PRIMARY KEY (`id_registro_comedor`),
  KEY `fk_comedor_registro_cursos_idx` (`cursos_id_curso`),
  KEY `fk_comedor_registro_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_comedor_registro_cursos` FOREIGN KEY (`cursos_id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_comedor_registro_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comedor_registro`
--

LOCK TABLES `comedor_registro` WRITE;
/*!40000 ALTER TABLE `comedor_registro` DISABLE KEYS */;
/*!40000 ALTER TABLE `comedor_registro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `anio_grado` int(11) NOT NULL,
  `division` varchar(10) NOT NULL,
  `turno` varchar(20) NOT NULL,
  PRIMARY KEY (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (1,1,'A','Mañana');
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentos` (
  `id_documento` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `tipo_documento` varchar(30) NOT NULL,
  `archivo_url` varchar(255) NOT NULL,
  `fecha_publicacion` datetime NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_documento`),
  KEY `fk_documentos_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_documentos_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos`
--

LOCK TABLES `documentos` WRITE;
/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ejemplares`
--

DROP TABLE IF EXISTS `ejemplares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ejemplares` (
  `id_ejemplar` int(11) NOT NULL AUTO_INCREMENT,
  `estado_fisico` varchar(50) NOT NULL,
  `libros_catalogo_id_libro` int(11) NOT NULL,
  PRIMARY KEY (`id_ejemplar`),
  KEY `fk_ejemplares_libros_catalogo1_idx` (`libros_catalogo_id_libro`),
  CONSTRAINT `fk_ejemplares_libros_catalogo1` FOREIGN KEY (`libros_catalogo_id_libro`) REFERENCES `libros_catalogo` (`id_libro`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ejemplares`
--

LOCK TABLES `ejemplares` WRITE;
/*!40000 ALTER TABLE `ejemplares` DISABLE KEYS */;
/*!40000 ALTER TABLE `ejemplares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `espacios`
--

DROP TABLE IF EXISTS `espacios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `espacios` (
  `id_espacio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_espacio` varchar(50) NOT NULL,
  PRIMARY KEY (`id_espacio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `espacios`
--

LOCK TABLES `espacios` WRITE;
/*!40000 ALTER TABLE `espacios` DISABLE KEYS */;
/*!40000 ALTER TABLE `espacios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grilla_horarios`
--

DROP TABLE IF EXISTS `grilla_horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grilla_horarios` (
  `id_horario` int(11) NOT NULL AUTO_INCREMENT,
  `dia_semana` varchar(15) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_final` time NOT NULL,
  `cursos_id_curso` int(11) NOT NULL,
  `materias_id_materia` int(11) NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `fk_grilla_horarios_cursos1_idx` (`cursos_id_curso`),
  KEY `fk_grilla_horarios_materias1_idx` (`materias_id_materia`),
  KEY `fk_grilla_horarios_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_grilla_horarios_cursos1` FOREIGN KEY (`cursos_id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_grilla_horarios_materias1` FOREIGN KEY (`materias_id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_grilla_horarios_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grilla_horarios`
--

LOCK TABLES `grilla_horarios` WRITE;
/*!40000 ALTER TABLE `grilla_horarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `grilla_horarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incidencias_conducta`
--

DROP TABLE IF EXISTS `incidencias_conducta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `incidencias_conducta` (
  `id_incidencia` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_incidencia` date NOT NULL,
  `gravedad` varchar(20) NOT NULL,
  `observacion` varchar(150) NOT NULL,
  `matriculas_id_matricula` int(11) NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_incidencia`),
  KEY `fk_incidencias_conducta_matriculas1_idx` (`matriculas_id_matricula`),
  KEY `fk_incidencias_conducta_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_incidencias_conducta_matriculas1` FOREIGN KEY (`matriculas_id_matricula`) REFERENCES `matriculas` (`id_matricula`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_incidencias_conducta_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incidencias_conducta`
--

LOCK TABLES `incidencias_conducta` WRITE;
/*!40000 ALTER TABLE `incidencias_conducta` DISABLE KEYS */;
INSERT INTO `incidencias_conducta` VALUES (1,'2026-06-08','Leve','El alumno estuvo interrumpiendo constantemente la clase tirando tizas.',1,1);
/*!40000 ALTER TABLE `incidencias_conducta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lectura_documentos`
--

DROP TABLE IF EXISTS `lectura_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lectura_documentos` (
  `id_lectura` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_lectura` datetime NOT NULL,
  `leido` tinyint(1) NOT NULL,
  `documentos_id_documento` int(11) NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_lectura`),
  KEY `fk_lectura_documentos_documentos1_idx` (`documentos_id_documento`),
  KEY `fk_lectura_documentos_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_lectura_documentos_documentos1` FOREIGN KEY (`documentos_id_documento`) REFERENCES `documentos` (`id_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lectura_documentos_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lectura_documentos`
--

LOCK TABLES `lectura_documentos` WRITE;
/*!40000 ALTER TABLE `lectura_documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `lectura_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libros_catalogo`
--

DROP TABLE IF EXISTS `libros_catalogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `libros_catalogo` (
  `id_libro` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(30) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `autor` varchar(100) NOT NULL,
  PRIMARY KEY (`id_libro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libros_catalogo`
--

LOCK TABLES `libros_catalogo` WRITE;
/*!40000 ALTER TABLE `libros_catalogo` DISABLE KEYS */;
/*!40000 ALTER TABLE `libros_catalogo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materias`
--

DROP TABLE IF EXISTS `materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materias` (
  `id_materia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_materia` varchar(50) NOT NULL,
  PRIMARY KEY (`id_materia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias`
--

LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;
/*!40000 ALTER TABLE `materias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matriculas`
--

DROP TABLE IF EXISTS `matriculas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matriculas` (
  `id_matricula` int(11) NOT NULL AUTO_INCREMENT,
  `ciclo_lectivo` int(11) NOT NULL,
  `alumnos_id_alumno` int(11) NOT NULL,
  `cursos_id_curso` int(11) NOT NULL,
  PRIMARY KEY (`id_matricula`),
  KEY `fk_matriculas_alumnos1_idx` (`alumnos_id_alumno`),
  KEY `fk_matriculas_cursos1_idx` (`cursos_id_curso`),
  CONSTRAINT `fk_matriculas_alumnos1` FOREIGN KEY (`alumnos_id_alumno`) REFERENCES `alumnos` (`id_alumno`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_matriculas_cursos1` FOREIGN KEY (`cursos_id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matriculas`
--

LOCK TABLES `matriculas` WRITE;
/*!40000 ALTER TABLE `matriculas` DISABLE KEYS */;
INSERT INTO `matriculas` VALUES (1,2026,1,1);
/*!40000 ALTER TABLE `matriculas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil_permiso`
--

DROP TABLE IF EXISTS `perfil_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil_permiso` (
  `perfiles_id_perfil` int(11) NOT NULL,
  `permisos_id_permiso` int(11) NOT NULL,
  PRIMARY KEY (`perfiles_id_perfil`,`permisos_id_permiso`),
  KEY `fk_perfiles_has_permisos_permisos1_idx` (`permisos_id_permiso`),
  KEY `fk_perfiles_has_permisos_perfiles1_idx` (`perfiles_id_perfil`),
  CONSTRAINT `fk_perfiles_has_permisos_perfiles1` FOREIGN KEY (`perfiles_id_perfil`) REFERENCES `perfiles` (`id_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_perfiles_has_permisos_permisos1` FOREIGN KEY (`permisos_id_permiso`) REFERENCES `permisos` (`id_permiso`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_permiso`
--

LOCK TABLES `perfil_permiso` WRITE;
/*!40000 ALTER TABLE `perfil_permiso` DISABLE KEYS */;
INSERT INTO `perfil_permiso` VALUES (1,1),(1,2);
/*!40000 ALTER TABLE `perfil_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfiles`
--

DROP TABLE IF EXISTS `perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfiles` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_perfil` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfiles`
--

LOCK TABLES `perfiles` WRITE;
/*!40000 ALTER TABLE `perfiles` DISABLE KEYS */;
INSERT INTO `perfiles` VALUES (1,'Directivo','Acceso total al sistema'),(2,'Directivo','Acceso total');
/*!40000 ALTER TABLE `perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(45) NOT NULL,
  `accion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_permiso`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,'asistencia','ver'),(2,'asistencia','cargar');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestamos_biblioteca`
--

DROP TABLE IF EXISTS `prestamos_biblioteca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestamos_biblioteca` (
  `id_prestamo` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_retiro` datetime NOT NULL,
  `fecha_devolucion_esperada` datetime NOT NULL,
  `fecha_devolucion_real` datetime NOT NULL,
  `ejemplares_id_ejemplar` int(11) NOT NULL,
  `matriculas_id_matricula` int(11) NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  `observaciones_devolucion` text NOT NULL,
  PRIMARY KEY (`id_prestamo`),
  KEY `fk_prestamos_biblioteca_ejemplares1_idx` (`ejemplares_id_ejemplar`),
  KEY `fk_prestamos_biblioteca_matriculas1_idx` (`matriculas_id_matricula`),
  KEY `fk_prestamos_biblioteca_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_prestamos_biblioteca_ejemplares1` FOREIGN KEY (`ejemplares_id_ejemplar`) REFERENCES `ejemplares` (`id_ejemplar`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestamos_biblioteca_matriculas1` FOREIGN KEY (`matriculas_id_matricula`) REFERENCES `matriculas` (`id_matricula`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestamos_biblioteca_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestamos_biblioteca`
--

LOCK TABLES `prestamos_biblioteca` WRITE;
/*!40000 ALTER TABLE `prestamos_biblioteca` DISABLE KEYS */;
/*!40000 ALTER TABLE `prestamos_biblioteca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservas_espacios`
--

DROP TABLE IF EXISTS `reservas_espacios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservas_espacios` (
  `id_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_reserva` datetime NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `espacios_id_espacio` int(11) NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `fk_reservas_espacios_espacios1_idx` (`espacios_id_espacio`),
  KEY `fk_reservas_espacios_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_reservas_espacios_espacios1` FOREIGN KEY (`espacios_id_espacio`) REFERENCES `espacios` (`id_espacio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservas_espacios_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservas_espacios`
--

LOCK TABLES `reservas_espacios` WRITE;
/*!40000 ALTER TABLE `reservas_espacios` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservas_espacios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_perfil`
--

DROP TABLE IF EXISTS `usuario_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_perfil` (
  `usuarios_id_usuario` int(11) NOT NULL,
  `perfiles_id_perfil` int(11) NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`,`perfiles_id_perfil`),
  KEY `fk_usuarios_has_perfiles_perfiles1_idx` (`perfiles_id_perfil`),
  KEY `fk_usuarios_has_perfiles_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_usuarios_has_perfiles_perfiles1` FOREIGN KEY (`perfiles_id_perfil`) REFERENCES `perfiles` (`id_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_perfiles_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_perfil`
--

LOCK TABLES `usuario_perfil` WRITE;
/*!40000 ALTER TABLE `usuario_perfil` DISABLE KEYS */;
INSERT INTO `usuario_perfil` VALUES (1,1);
/*!40000 ALTER TABLE `usuario_perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `estado_activo` tinyint(1) NOT NULL,
  `intentos_fallidos` int(11) NOT NULL DEFAULT 0,
  `token_sesion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL,
  `pin_recuperacion` varchar(10) DEFAULT NULL,
  `estado_desbloqueo` varchar(30) DEFAULT 'ninguno',
  `debe_cambiar_clave` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `dni_UNIQUE` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'12345678','Juan','Pérez','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,0,'ff2021bd206f23ed8801452cf56b557eb549441054eb590cb9360c313be2c2e2','2026-06-08 01:09:33',NULL,'ninguno',0),(2,'1111','Maria','Gomez','$2y$10$bGSbJ48kvsribnHOpfVu6uEKvUDyNx7HAtc/Z79cGwHxqlhZhkKNe',1,1,NULL,NULL,NULL,'ninguno',0);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitas`
--

DROP TABLE IF EXISTS `visitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visitas` (
  `id_visita` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_visitante` varchar(50) NOT NULL,
  `motivo` varchar(50) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `usuarios_id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_visita`),
  KEY `fk_visitas_usuarios1_idx` (`usuarios_id_usuario`),
  CONSTRAINT `fk_visitas_usuarios1` FOREIGN KEY (`usuarios_id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitas`
--

LOCK TABLES `visitas` WRITE;
/*!40000 ALTER TABLE `visitas` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-08  0:29:02
