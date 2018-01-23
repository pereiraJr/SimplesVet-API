# Instruções de uso
Ambiente de Dev:

XAMPP versão: 5.6.30-1;
Composer versão: 1.6.2;

SETUP Antes de Utilização:

{PROJETO ROOT}: $ composer install;



Modelagem Banco:

Banco de Dados MySQL;

create database restapi;

create table animal(
	id int primary key AUTO_INCREMENT,
    nome varchar(15),
    raca varchar(15),
    peso decimal(65, 3));

