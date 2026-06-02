-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema montessori_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `dni` VARCHAR(20) NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `apellido` VARCHAR(50) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `estado_activo` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `dni_UNIQUE` (`dni` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `perfiles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfiles` (
  `id_perfil` INT NOT NULL AUTO_INCREMENT,
  `nombre_perfil` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_perfil`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `permisos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` INT NOT NULL AUTO_INCREMENT,
  `modulo` VARCHAR(45) NOT NULL,
  `accion` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_permiso`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cursos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cursos` (
  `id_curso` INT NOT NULL AUTO_INCREMENT,
  `anio_grado` INT NOT NULL,
  `division` VARCHAR(10) NOT NULL,
  `turno` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id_curso`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `alumnos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id_alumno` INT NOT NULL AUTO_INCREMENT,
  `dni` VARCHAR(20) NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `apellido` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_alumno`),
  UNIQUE INDEX `dni_UNIQUE` (`dni` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `matriculas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `matriculas` (
  `id_matricula` INT NOT NULL AUTO_INCREMENT,
  `ciclo_lectivo` INT NOT NULL,
  `alumnos_id_alumno` INT NOT NULL,
  `cursos_id_curso` INT NOT NULL,
  PRIMARY KEY (`id_matricula`),
  INDEX `fk_matriculas_alumnos1_idx` (`alumnos_id_alumno` ASC) ,
  INDEX `fk_matriculas_cursos1_idx` (`cursos_id_curso` ASC) ,
  CONSTRAINT `fk_matriculas_alumnos1`
    FOREIGN KEY (`alumnos_id_alumno`)
    REFERENCES `alumnos` (`id_alumno`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_matriculas_cursos1`
    FOREIGN KEY (`cursos_id_curso`)
    REFERENCES `cursos` (`id_curso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `asistencia_diaria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `asistencia_diaria` (
  `id_asistencia` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `estado` VARCHAR(20) NOT NULL,
  `matriculas_id_matricula` INT NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_asistencia`),
  INDEX `fk_asistencia_diaria_matriculas1_idx` (`matriculas_id_matricula` ASC) ,
  INDEX `fk_asistencia_diaria_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_asistencia_diaria_matriculas1`
    FOREIGN KEY (`matriculas_id_matricula`)
    REFERENCES `matriculas` (`id_matricula`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_asistencia_diaria_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `comedor_registro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comedor_registro` (
  `id_registro_comedor` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `cantidad_raciones` INT NOT NULL,
  `cursos_id_curso` INT NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  `raciones_sobrantes` INT NOT NULL,
  PRIMARY KEY (`id_registro_comedor`),
  INDEX `fk_comedor_registro_cursos_idx` (`cursos_id_curso` ASC) ,
  INDEX `fk_comedor_registro_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_comedor_registro_cursos`
    FOREIGN KEY (`cursos_id_curso`)
    REFERENCES `cursos` (`id_curso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comedor_registro_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `documentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `documentos` (
  `id_documento` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(100) NOT NULL,
  `tipo_documento` VARCHAR(30) NOT NULL,
  `archivo_url` VARCHAR(255) NOT NULL,
  `fecha_publicacion` DATETIME NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_documento`),
  INDEX `fk_documentos_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_documentos_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `libros_catalogo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `libros_catalogo` (
  `id_libro` INT NOT NULL AUTO_INCREMENT,
  `isbn` VARCHAR(30) NOT NULL,
  `titulo` VARCHAR(150) NOT NULL,
  `autor` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_libro`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `materias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `materias` (
  `id_materia` INT NOT NULL AUTO_INCREMENT,
  `nombre_materia` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_materia`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `usuario_perfil`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario_perfil` (
  `usuarios_id_usuario` INT NOT NULL,
  `perfiles_id_perfil` INT NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `perfiles_id_perfil`),
  INDEX `fk_usuarios_has_perfiles_perfiles1_idx` (`perfiles_id_perfil` ASC) ,
  INDEX `fk_usuarios_has_perfiles_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_usuarios_has_perfiles_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_perfiles_perfiles1`
    FOREIGN KEY (`perfiles_id_perfil`)
    REFERENCES `perfiles` (`id_perfil`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `perfil_permiso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfil_permiso` (
  `perfiles_id_perfil` INT NOT NULL,
  `permisos_id_permiso` INT NOT NULL,
  PRIMARY KEY (`perfiles_id_perfil`, `permisos_id_permiso`),
  INDEX `fk_perfiles_has_permisos_permisos1_idx` (`permisos_id_permiso` ASC) ,
  INDEX `fk_perfiles_has_permisos_perfiles1_idx` (`perfiles_id_perfil` ASC) ,
  CONSTRAINT `fk_perfiles_has_permisos_perfiles1`
    FOREIGN KEY (`perfiles_id_perfil`)
    REFERENCES `perfiles` (`id_perfil`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_perfiles_has_permisos_permisos1`
    FOREIGN KEY (`permisos_id_permiso`)
    REFERENCES `permisos` (`id_permiso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ejemplares`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ejemplares` (
  `id_ejemplar` INT NOT NULL AUTO_INCREMENT,
  `estado_fisico` VARCHAR(50) NOT NULL,
  `libros_catalogo_id_libro` INT NOT NULL,
  PRIMARY KEY (`id_ejemplar`),
  INDEX `fk_ejemplares_libros_catalogo1_idx` (`libros_catalogo_id_libro` ASC) ,
  CONSTRAINT `fk_ejemplares_libros_catalogo1`
    FOREIGN KEY (`libros_catalogo_id_libro`)
    REFERENCES `libros_catalogo` (`id_libro`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prestamos_biblioteca`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prestamos_biblioteca` (
  `id_prestamo` INT NOT NULL AUTO_INCREMENT,
  `fecha_retiro` DATETIME NOT NULL,
  `fecha_devolucion_esperada` DATETIME NOT NULL,
  `fecha_devolucion_real` DATETIME NOT NULL,
  `ejemplares_id_ejemplar` INT NOT NULL,
  `matriculas_id_matricula` INT NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  `observaciones_devolucion` TEXT NOT NULL,
  PRIMARY KEY (`id_prestamo`),
  INDEX `fk_prestamos_biblioteca_ejemplares1_idx` (`ejemplares_id_ejemplar` ASC) ,
  INDEX `fk_prestamos_biblioteca_matriculas1_idx` (`matriculas_id_matricula` ASC) ,
  INDEX `fk_prestamos_biblioteca_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_prestamos_biblioteca_ejemplares1`
    FOREIGN KEY (`ejemplares_id_ejemplar`)
    REFERENCES `ejemplares` (`id_ejemplar`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestamos_biblioteca_matriculas1`
    FOREIGN KEY (`matriculas_id_matricula`)
    REFERENCES `matriculas` (`id_matricula`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prestamos_biblioteca_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `grilla_horarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `grilla_horarios` (
  `id_horario` INT NOT NULL AUTO_INCREMENT,
  `dia_semana` VARCHAR(15) NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `hora_final` TIME NOT NULL,
  `cursos_id_curso` INT NOT NULL,
  `materias_id_materia` INT NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_horario`),
  INDEX `fk_grilla_horarios_cursos1_idx` (`cursos_id_curso` ASC) ,
  INDEX `fk_grilla_horarios_materias1_idx` (`materias_id_materia` ASC) ,
  INDEX `fk_grilla_horarios_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_grilla_horarios_cursos1`
    FOREIGN KEY (`cursos_id_curso`)
    REFERENCES `cursos` (`id_curso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grilla_horarios_materias1`
    FOREIGN KEY (`materias_id_materia`)
    REFERENCES `materias` (`id_materia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grilla_horarios_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lectura_documentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lectura_documentos` (
  `id_lectura` INT NOT NULL AUTO_INCREMENT,
  `fecha_lectura` DATETIME NOT NULL,
  `leido` TINYINT(1) NOT NULL,
  `documentos_id_documento` INT NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_lectura`),
  INDEX `fk_lectura_documentos_documentos1_idx` (`documentos_id_documento` ASC) ,
  INDEX `fk_lectura_documentos_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_lectura_documentos_documentos1`
    FOREIGN KEY (`documentos_id_documento`)
    REFERENCES `documentos` (`id_documento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lectura_documentos_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `incidencias_conducta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `incidencias_conducta` (
  `id_incidencia` INT NOT NULL AUTO_INCREMENT,
  `fecha_incidencia` DATE NOT NULL,
  `gravedad` VARCHAR(20) NOT NULL,
  `observacion` VARCHAR(150) NOT NULL,
  `matriculas_id_matricula` INT NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_incidencia`),
  INDEX `fk_incidencias_conducta_matriculas1_idx` (`matriculas_id_matricula` ASC) ,
  INDEX `fk_incidencias_conducta_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_incidencias_conducta_matriculas1`
    FOREIGN KEY (`matriculas_id_matricula`)
    REFERENCES `matriculas` (`id_matricula`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_incidencias_conducta_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `espacios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `espacios` (
  `id_espacio` INT NOT NULL AUTO_INCREMENT,
  `nombre_espacio` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_espacio`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `visitas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `visitas` (
  `id_visita` INT NOT NULL AUTO_INCREMENT,
  `nombre_visitante` VARCHAR(50) NOT NULL,
  `motivo` VARCHAR(50) NOT NULL,
  `fecha_hora` DATETIME NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_visita`),
  INDEX `fk_visitas_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_visitas_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reservas_espacios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reservas_espacios` (
  `id_reserva` INT NOT NULL AUTO_INCREMENT,
  `fecha_reserva` DATETIME NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `hora_fin` TIME NOT NULL,
  `espacios_id_espacio` INT NOT NULL,
  `usuarios_id_usuario` INT NOT NULL,
  PRIMARY KEY (`id_reserva`),
  INDEX `fk_reservas_espacios_espacios1_idx` (`espacios_id_espacio` ASC) ,
  INDEX `fk_reservas_espacios_usuarios1_idx` (`usuarios_id_usuario` ASC) ,
  CONSTRAINT `fk_reservas_espacios_espacios1`
    FOREIGN KEY (`espacios_id_espacio`)
    REFERENCES `espacios` (`id_espacio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reservas_espacios_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
