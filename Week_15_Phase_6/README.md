# EduTrack Student Management System
**Subject:** IT 105 – Information Management I  
**Course & Section:** BSIS 2-A  
**Team Name:** StrivingFeathers

## Project Overview
EduTrack is a high-performance, cloud-integrated database system designed to manage academic records. It is built to handle over 100,000 records, providing school administrators with advanced Business Intelligence (BI) insights and optimized query speeds.

## Group Members and Roles
* **Andrei Arnaldo** – Project Manager
* **Arjiannah Carmelle Borleo** – SQL Developer
* **Alexis Consuelo** – QA/Tester
* **Keira Latasha Creollo** – QA/Tester
* **Gabrielle Sapico** – Documentation Lead
* **John Euben Lopez** – Security Officer
* **Enoch Andrew Querol** – Frontend 

## Key Features
* **Scalable Data:** 100,000+ records generated and managed via Python and MySQL.
* **BI Analytics:** Advanced GPA tracking, enrollment trends, and workload analysis.
* **Performance Tuning:** B-Tree indexing reducing query scan times by 99%.
* **Cloud Integration:** Fully hosted on Aiven Cloud with SSL security.
* **Disaster Recovery:** Automated backup and restoration strategies.

## Installation and Restoration
1. Create a database named `edutrack_db` in MySQL Workbench.
2. Navigate to **Server > Data Import**.
3. Choose the `backups/edutrack_backup.sql` file.
4. Click **Start Import**.
5. All system tables and 100k records will be restored automatically.

## Notes
All technical proofs, including cloud deployment screenshots and optimization benchmarks, are located in the `/screenshots` folder.
