# 🅿️ Controle de Estacionamento Inteligente 🚘

Projeto desenvolvido como exercício prático "Controle de Estacionamento Inteligente", aplicando princípios SOLID,DRY, KISS, boas práticas de Clean Code e Object Calisthenics, PSR-4, organização em camadas e boas práticas de engenharia de software, utilizando PHP 8+, Composer e SQLite.

---

## 🎯 Objetivo

Desenvolver um sistema de controle de estacionamento que permita:

*Cadastrar entrada e saída de veículos;
*Calcular tarifas conforme tipo de veículo;
*Controlar tempo de permanência no estacionamento;
*Gerar relatórios de uso e faturamento por tipo de veículo;
*Aplicar princípios de arquitetura limpa e código sustentável.

O sistema possui uma interface visual utilizando via HTML e Tailwind CSS.

---

## ⚙️ Tecnologias Utilizadas

*PHP 8.2+;
*SQLite 3;
*Composer (autoload PSR-4);
*PSR-12;
*Arquitetura modular (Application / Domain / Infra);
*HTML + Tailwind CSS.

---

## 🧩 Arquitetura e Organização do Projeto

| Camada / Classe | Responsabilidade Principal |
| :--- | :--- |
Controle-Estacionamento-Inteligente/ 
├─ composer.json | # Configura autoload PSR-4 e scripts auxiliares |
├─ public/ | # Camada de apresentação acessada pelo navegador |
│ └─ index.php  | # Página Inicial que mostra o Menu para direcionar para tela de cadastro de entrada e saída de veículo e também a de relatório |
│ └─ register_entry.php | # Página com formuário para Cadastro de entrada de véiculo |
| └─ register_exit.php | # Página com formuário para Cadastro de saída de véiculo |
| └─ register_entry.php | # Página com formuário para Cadastro de entrada de véiculo |
| └─ report.php | # Página para vizualizar entrada e saída dos véiculos |
├─ src/
| ├─ Application/ Services/
| └─ ParkingService.php | # Orquestra as regras de negócios para cadastrar entrada e saída de veículos |
| └─ ReportService.php | # Orquestra as regras de negócios do relatório de entrada e saída de veículos |
│ ├─ Domain/ | # Entidades, Interfaces e Contratos
| ├─  Entities/
| └─ ParkingRecord.php |
| └─ Tariff.php |
| └─ Vehicle.php |
| ├─  Interfaces/
|└─ ParkingRecordRepositoryInterface.php |
| └─ ParkingRepositoryInterface.php |
| └─ TariffInterface.php |
| └─ VehicleRepositoryInterface.php
| ├─  Tariffs/
| └─ CarTariff.php |
| └─ MotorcycleTariff.php |
| └─ TruckTariff.php |
| ├─  ValueObjects/
| └─ TariffFactory.php |
| └─ VehicleType.php |
| └─ TariffFactory.php |
│ ├─  Infra/
| ├─  Repositories/ | # Repositórios do projeto |
| └─ ParkingRecordFileRepository.php |
| └─ ParkingRepository.php |
| └─ VehicleRepository.php |
├─ storage/ 
| └─ parking.jsonl | # JSON por linha contendo a lista dos véiculos que entram e saem do estacionamento |
└─ vendor/
| └─ autoload.php  | # Autoloader simples gerado pelo Composer |

---

## 🔍 Como os princípios SOLID foram aplicados?

* SRP: Cada classe com uma responsabilidade única;
* OCP: Novos tipos de veículo devem ser adicionados sem modificar lógica existente;
* LSP: Todas estratégias de precificação substituem a interface;
* ISP: Interfaces segregadas (ex: separação de repositório e estratégia de precificação);
* DIP: Services dependem de interfaces, não implementações concretas.

  ---

  ## 📋 Regras de Negócio

1. Todo veículo possui tipo:
* Carro → R$ 5/h
* Moto → R$ 3/h
* Caminhão → R$ 10/h

2. Tempo de permanência é calculado em horas inteiras (sempre arredondando para cima).

3. Relatório deve exibir:
* total de veículos por tipo
* faturamento por tipo

4. Entrada registra:
* tipo do veículo
* placa
* horário de entrada

5. Saída registra:
* horário de saída
* cálculo da tarifa
* persistência do valor pago

---

## ▶ Como Executar o Projeto

1. Clone o repositório na pasta htdocs do xampp:

   ```bash
   git clone https://github.com/maisamarall/Controle-Estacionamento-Inteligente.git
   ```

2. Acessar a pasta:

   ```bash
   cd Controle-Estacionamento-Inteligente
   ```

3. Instalar as dependências e gerar autoload:
     ```bash
     composer install
     composer dump 
     ```
     
4. Acessar no navegador:

     ```bash
     http://localhost/Controle-Estacionamento-Inteligente/public/index.php
     ```

---

## 🧠 Conceitos Aplicados

### * SOLID
### * DRY (nenhuma lógica duplicada)
### * KISS (implementação simples e direta)
### * Object Calisthenics
- classes pequenas
- métodos curtos
- nomes expressivos
### * PSR-4 e PSR-12
### * Arquitetura Limpa

---

### 🧑‍🎓 Participantes do Grupo

| Nome do Discente | RA        |
| ---------------- | --------- |
| Jênie Danielle  | 1993310 |
| Maisa Amaral    | 1997058 |
| Samara Adorno     | 2001639 |
| Simone Siqueira  | 2001915 |
---

## 💻 Demonstrativo

### Tela Inicial
<img width="410" height="365" alt="Sem título" src="https://github.com/user-attachments/assets/b8df674a-9d04-4fd0-b36a-b90b22d06d33" />


### Tela de Registrar Entrada de Veículo
<img width="403" height="356" alt="image" src="https://github.com/user-attachments/assets/14d62778-0d2f-485a-ab03-429351641264" />

### Tela de Registrar Saída de Veículo
<img width="397" height="279" alt="image" src="https://github.com/user-attachments/assets/84b08200-d5e0-44d7-bbc9-f90a67bc40e8" />

### Tela de Relatório do Estacionamento
<img width="975" height="802" alt="image" src="https://github.com/user-attachments/assets/ee6e7035-55c1-4293-ae73-724b1612420f" />

---
###  🎓 Disciplina

**DESIGN PATTERNS E CLEAN CODE - Profº Valdir Junior**
